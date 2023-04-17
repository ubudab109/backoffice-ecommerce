<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\Transaction;
use App\Models\TransactionReceiver;
use App\Models\TrStatusHistory;
use App\Traits\WhatsappAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Request;
use Illuminate\Http\Request as RequestFacade;
use Yajra\DataTables\DataTables;
use App\Services\DownloadStruk;

class TransactionController extends Controller
{
    use WhatsappAuth;
    /**
     * View Transaction
     */
    public function viewTransaction()
    {
        return view('transaction.transaction-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Transaction',
                    'url'   => '/transaction'
                ]
            ],
            'subtitle' => 'Daftar Transaksi',
            'grup' => 'transaction'
        ]);
    }

    /**
     * View Detail Transaction
     */
    public function viewDetailTransaction($id)
    {
        $transaction = Transaction::where('uuid',$id)->first();
        return view('transaction.transaction-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Transaction',
                    'url'   => '/transaction'
                ]
            ],
            'subtitle' => 'Detail Transaksi',
            'grup' => 'transaction',
            'transaction' => $transaction,
        ]);
    }

    /**
     * List Transaction Data
     */
    public function listTransactions()
    {
        $transaction = Transaction::with('customer')->get();

        return DataTables::of($transaction)
        ->editColumn('transaction_id', function ($row) {
            return "<center>$row->id</center>";
        })
        ->editColumn('invoice', function ($row) {
            return "<center>$row->no_invoice</center>";
        })
        ->editColumn('customer_name', function ($row) {
            return "<center>".$row->customer->name."</center>";
        })
        ->editColumn('whatsapp', function ($row) {
            return "<center>".$row->customer->whatsapp."</center>";
        })
        ->editColumn('transaction_date', function ($row) {
            $date = Carbon::parse($row->transaction_date);
            return "<center>".$date->format('Y-m-d')."</center>";
        })
        ->editColumn('total', function ($row) {
            return "<center>". rupiah($row->total_price) ."</center>";
        })
        ->editColumn('status', function ($row) {
            return '<center>' . transactionStatusBadget($row->uuid, $row->status) . '</center>';
        })
        ->addColumn('action', function ($row) {
            if (canAccess('transaction_management_detail')) {
                $buttonDetail = '<a class="dropdown-item" href="transaction/detail-transaction/'. $row->uuid.'">
                    <img src='.asset('image/detail.svg').' /> Detail Data
                </a>';
            }

            $data = '
                    <center>
                        <div class="dropdown">
                            <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src='.asset('image/dropdown.svg').' />
                            </button>
                        
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            '. $buttonDetail . '
                            </div>
                        </div>
                    </center>
                    ';
            return $data;
        })
        ->rawColumns([
            'transaction_id',
            'invoice',
            'customer_name',
            'whatsapp',
            'transaction_date',
            'total',
            'status',
            'action'
        ])
        ->make(true);
    }

    /**
     * Update Status Transaction
     */
    public function updateStatusTransaction()
    {
        DB::beginTransaction();
        try {

            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $transaction = Transaction::where('uuid',$data['idTransaction'])->first();

            if (!$transaction) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Transaksi tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }
            // '0: menunggu pembayaran, 1:Pembayaran COD, 2:Menunggu Konfirmasi, 3:Pesanan Diproses, 4:Pesanan Dikirim, 5:Pesanan Selesai, 6:Pesanan Dibatalkan'
            if ($data['statusChange'] == '4') {
                $transaction->delivered_type = $data['deliveredType'];
                if ($data['deliveredType'] == 'kurir' || $data['deliveredType'] == 'ojol') {
                    $transaction->shipping_type = 'delivered';
                } else {
                    $transaction->shipping_type = 'pickup';
                }
                $transaction->postman_name = $data['postmanName'];
                $transaction->transaction_send_date = Date::now();
                $dataNotif = [
                    'id_transaction' => $transaction->id,
                    'invoice'        => $transaction->no_invoice,
                ];
                $this->transactionSend($dataNotif);
            }

            if ($data['statusChange'] == '5') {
                $receiver = new TransactionReceiver;
                $receiver->transaction_id = $transaction->id;
                $receiver->receiver_name = $data['receiverName'];
                $receiver->save();
            }

            $transaction->status = $data['statusChange'];
            $transaction->updated_at = date('Y-m-d H:i:s');

            $transaction->save();
            $this->insertHistory($data, $transaction);
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Ubah status Transaksi',
                'message' => 'Ubah status Transaksi berhasil.',
                'url' => '/transaction/detail-transaction/'.$transaction->uuid,
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];

            return response()->json($messages);
        } catch (\Exception  $e) {
            DB::rollback();
            dd($e);
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => 'Harap Perika Inputan Anda',
                'url' => '/transaction',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];
        }
    }

    private function insertHistory(array $input, Transaction $transaction) {
        $newHistory = new TrStatusHistory();
        $newHistory->transaction_id = $transaction->id;
        $newHistory->trx_status_id = $transaction->status;
        $newHistory->trx_status_text = $transaction->status_transaction;
        $newHistory->status_notes = json_encode($input);
        $newHistory->save();        
    }

    public function downloadInvoice(RequestFacade $request)
    {
        $id = $request->id;

        $invoice = Transaction::find($id);
        
        if(!$invoice) {
            return abort(404, 'Transaction Not Found'); 
        }

        $struk = new DownloadStruk;

        return $struk->download($invoice);
    }
}
