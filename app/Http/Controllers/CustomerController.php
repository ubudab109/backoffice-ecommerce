<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Transaction;
use Carbon\Carbon;
use Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * View Customer
     */
    public function viewCustomer()
    {
        return view('customer.customer-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Customer',
                    'url'   => '/customer'
                ]
            ],
            'subtitle' => 'Daftar Customer',
            'grup' => 'customer'
        ]);
    }

    /**
     * List Customer Data
     */
    public function listCustomer()
    {
        $customer = Customers::with('address')->get();
        return DataTables::of($customer)
            ->editColumn('id', function ($row) {
                return "<center>$row->id</center>";
            })
            ->editColumn('whatsapp_number', function ($row) {
                return "<center>$row->whatsapp</center>";
            })
            ->editColumn('fullname', function ($row) {
                return "<center>$row->name</center>";
            })
            ->addColumn('action', function ($row) {
                if (canAccess('customer_management_detail')) {
                    $buttonDetail = "<a class='dropdown-item' href='/customer/detail-customer?customer=$row->id'>
                        <img src=" . asset('image/detail.svg') ."  /> Detail Data
                    </a>";
                }

                $data = '
                    <center>
                        <div class="dropdown">
                            <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src=' . asset('image/dropdown.svg') . ' />
                            </button>
                        
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            ' . $buttonDetail . '
                            </div>
                        </div>
                    </center>
                    ';
                return $data;
            })
            ->rawColumns(['id', 'whatsapp_number', 'fullname', 'action'])
            ->make(true);
    }

    /**
     * View Detail Customer
     */
    public function viewDetailCustomer()
    {
        $id = Request::query('customer');
        $customer = Customers::find($id);
        if (Request::ajax()) {
            $transaction = Transaction::where('customer_id', $id)->get();
            return DataTables::of($transaction)
                ->editColumn('transaction_id', function ($row) {
                    if (canAccess('transaction_management_detail')) {
                        return "<center>
                        <a href='" . route('get.transaction.detail', $row->id) . "'>$row->id</a>
                        </center>";
                    }
                    return "<center>
                        <a>$row->id</a>
                        </center>";
                })
                ->editColumn('invoice', function ($row) {
                    return "<center>$row->no_invoice</center>";
                })
                ->editColumn('customer_name', function ($row) {
                    return "<center>" . $row->customer->name . "</center>";
                })
                ->editColumn('whatsapp', function ($row) {
                    return "<center>" . $row->customer->whatsapp . "</center>";
                })
                ->editColumn('transaction_date', function ($row) {
                    $date = Carbon::parse($row->transaction_date);
                    return "<center>" . $date->format('Y-m-d') . "</center>";
                })
                ->editColumn('total', function ($row) {
                    return "<center>" . rupiah($row->total_price) . "</center>";
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == '0') {
                        $option = "<option value='0' " . ($row->status == '0' ? 'selected' : '') . ">Menunggu Pembayaran</option>";
                    } else if ($row->status == '1') {
                        $option = "
                    <option value='1' " . ($row->status == '1' ? 'selected' : '') . ">Pembayaran COD</option>
                    <option value='2' " . ($row->status == '2' ? 'selected' : '') . ">Konfirmasi</option>
                    <option value='6' " . ($row->status == '6' ? 'selected' : '') . ">Pesanan Dibatalkan</option>
                ";
                    } else if ($row->status == '2') {
                        $option = "
                    <option value='2' " . ($row->status == '2' ? 'selected' : '') . ">Menunggu Konfirmasi</option>
                    <option value='3' " . ($row->status == '3' ? 'selected' : '') . ">Pesanan Diproses</option>
                    <option value='6' " . ($row->status == '6' ? 'selected' : '') . ">Pesanan Dibatalkan</option>
                ";
                    } else if ($row->status == '3') {
                        $option = "
                    <option value='3' " . ($row->status == '3' ? 'selected' : '') . ">Pesanan Diproses</option>
                    <option value='4' " . ($row->status == '4' ? 'selected' : '') . ">Kirim Pesanan</option>
                ";
                    } else if ($row->status == '4') {
                        $option = "
                    <option value='4' " . ($row->status == '4' ? 'selected' : '') . ">Pesanan Dikirim</option>
                    <option value='5' " . ($row->status == '5' ? 'selected' : '') . ">Pesanan Selesai</option>
                ";
                    } else if ($row->status == '5') {
                        $option = "<option value='5' " . ($row->status == '5' ? 'selected' : '') . ">Pesanan Selesai</option>";
                    } else if ($row->status == '6') {
                        $option = "<option value='56' " . ($row->status == '6' ? 'selected' : '') . ">Pesanan Dibatalkan</option>";
                    }

                    $style = 'status-active';
                    $data = "
                <select 
                disabled
                style='width: 197px;'
                name='flagStatus' id='flagStatus' class='form-select-table $style'>
                    $option
                </select>
            ";

                    return '<center>' . $data . '</center>';
                })
                ->rawColumns([
                    'transaction_id',
                    'invoice',
                    'customer_name',
                    'whatsapp',
                    'transaction_date',
                    'total',
                    'status',
                ])
                ->make(true);
        }
        return view('customer.customer-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Customer',
                    'url'   => '/customer'
                ]
            ],
            'subtitle'  => 'Detail Customer',
            'grup'      => 'customer',
            'customer'  => $customer
        ]);
    }

    /**
     * History Transaction
     */
    public function historyTransactionCustomer()
    {
        $id = Request::query('customer');
        $customer = Customers::find($id);
        if (Request::ajax()) {
            $transaction = Transaction::where('customer_id', $customer->id)->get();
            return DataTables::of($transaction)
                ->editColumn('transaction_id', function ($row) {
                    if (canAccess('transaction_management_detail')) {
                        return "<center>
                        <a href='" . route('get.transaction.detail', $row->id) . "' style='color: #35BA4D;'>$row->id</a>
                        </center>";
                    }
                    return "<center>
                        <a>$row->id</a>
                        </center>";
                })
                ->editColumn('invoice', function ($row) {
                    return "<center>$row->no_invoice</center>";
                })
                ->editColumn('customer_name', function ($row) {
                    return "<center>" . $row->customer->name . "</center>";
                })
                ->editColumn('whatsapp', function ($row) {
                    return "<center>" . $row->customer->whatsapp . "</center>";
                })
                ->editColumn('transaction_date', function ($row) {
                    $date = Carbon::parse($row->transaction_date);
                    return "<center>" . $date->format('Y-m-d') . "</center>";
                })
                ->editColumn('total', function ($row) {
                    return "<center>" . rupiah($row->total_price) . "</center>";
                })
                ->editColumn('status', function ($row) {
                    return '<center>' . transactionStatusBadget($row->status, $row->uuid) . '</center>';
                })
                ->rawColumns([
                    'transaction_id',
                    'invoice',
                    'customer_name',
                    'whatsapp',
                    'transaction_date',
                    'total',
                    'status',
                ])
                ->make(true);
        }
        return view('customer.customer-history-transaction')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Customer',
                    'url'   => '/customer'
                ],
                [
                    'title' => 'Detail Customer Customer',
                    'url'   => '/customer/detail-customer?customer='.$id.''
                ]
            ],
            'subtitle'  => 'History Transaksi',
            'grup'      => 'customer',
        ]);
    }
}
