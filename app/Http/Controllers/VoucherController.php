<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\Voucher;
use Request;
use Yajra\DataTables\DataTables;
use DB;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class VoucherController extends Controller
{   
    /**
     * View Voucher
     */
    public function viewVoucher()
    {
        return view('voucher.voucher-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Voucher',
                    'url'   => '/voucher'
                ]
            ],
            'subtitle' => 'Daftar Voucher',
            'grup' => 'voucher'
        ]);
    }

    /**
     * View Detail Voucher
     */
    public function viewVoucherDetail($id)
    {
        $voucher = Voucher::find($id);
        return view('voucher.voucher-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Voucher',
                    'url'   => '/voucher'
                ]
            ],
            'subtitle' => 'Detail Voucher',
            'grup' => 'voucher',
            'voucher' => $voucher,
        ]);
    }

    public function viewEditVoucher($id)
    {
        $voucher = Voucher::find($id);

        return view('voucher.voucher-edit-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Voucher',
                    'url'   => '/voucher'
                ],
                [
                    'title' => 'Detail Voucher',
                    'url'   => '/voucher/detail-voucher/'.$id
                ]
            ],
            'subtitle' => 'Edit Voucher',
            'grup' => 'voucher',
            'voucher' => $voucher,
        ]);
    }

    /**
     * Data list voucher
     */
    public function listVoucher()
    {
        $voucher = Voucher::with('creator')->get();

        return DataTables::of($voucher)
        ->editColumn('title_voucher', function ($row) {
            return "<center>$row->title</center>";
        })
        ->editColumn('code_voucher', function ($row){
            return "<center>$row->code</center>";
        })
        ->editColumn('type', function ($row) {
            if ($row->discount_type == 'fixed') {
                return "<center>Potongan Harga</center>";
            } else if ($row->discount_type == 'discount') {
                return "<center>Diskon</center>";

            }
        })
        ->editColumn('rest_voucher', function ($row) {
            return "<center>".$row->rest_voucher."</center>";
        })
        ->editColumn('used_voucher', function ($row) {
            return "<center>$row->total_reedem</center>";
        })
        ->editColumn('string_status', function ($row) {
            if ($row->status == '1') {
                return "Aktif";
            }

            return "Tidak Aktif";
        })
        ->editColumn('status_voucher', function ($row) {
            if (canAccess('voucher_management_edit')) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }

            if ($row->status == true) {
                $active = 'checked';
            } else {
                $active = '';
            }
            $data = "
                <div class='custom-control custom-switch'>
                    <input $disabled  $active onchange='openModalStatus($row->status,".json_encode($row->id).")' type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
                    <label class='custom-control-label' for='customSwitches-$row->id'></label>
                </div>
            ";

            return '<center>' . $data . '</center>';
        })
        ->editColumn('creator', function ($row) {
            return "<center>".$row->creator->userAdmin->name."</center>";
        })
        ->editColumn('updated_at', function ($row) {
            $data = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('Y-m-d');
            return "<center>" . $data . "</center>";
        })
        ->addColumn('action', function ($row) {
            if (canAccess('voucher_management_detail')) {
                $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.voucher', $row->id) . '>
                    <img src='.asset('image/edit.svg').' /> Detail Data
                </a>';
            }
            if (canAccess('voucher_management_edit')) {
                $buttonEdit = '<a class="dropdown-item" href=' . route('get.edit-detail.voucher', $row->id) . '>
                    <img src='.asset('image/edit.svg').' /> Edit Data
                </a>';
            }
            if (canAccess('voucher_management_delete')) {
                $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(" . json_encode($row->id) . ")'>
                    <img src=".asset('image/edit.svg')." /> Delete Data
                </a>";
            }

            $data = '
            <center>
                <div class="dropdown">
                    <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src='.asset('image/dropdown.svg').' />
                    </button>
                
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    '. $buttonDetail. $buttonEdit . $buttonDelete .'
                    </div>
                </div>
            </center>
            ';
            return $data;
        })
        ->rawColumns([
            'title_voucher',
            'code_voucher',
            'type',
            'used_voucher',
            'rest_voucher',
            'status_voucher',
            'creator',
            'updated_at',
            'action'
        ])
        ->make(true);
    }

    /**
     * View Add Voucher
     */
    public function viewAddVoucher()
    {
        return view('voucher.voucher-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Voucher',
                    'url'   => '/voucher'
                ],
            ],
            'subtitle' => 'Tambah Voucher',
            'grup' => 'voucher',
        ]);
    }

    /**
     * Post create voucher
     */
    public function addVoucher()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set('Asia/Jakarta');
            $fomartStartDate = strtotime($data['startVoucher']);
            $formatEndDate = strtotime($data['endVoucher']);

            if ($formatEndDate <= $fomartStartDate) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Tanggal Berlaku dan Tanggal Selesai Masa Berlaku Tidak Sesuai',
                    'button' => "Oke, Mengerti",
                ];
            }
            $voucher = new Voucher;
            $voucher->id = Uuid::generate()->string;
            $voucher->creator_id = session('session_id.id');
            $voucher->title = $data['judulVoucher'];
            $voucher->description = $data['description'];
            $voucher->minimum_shop_price = $data['minimumShoping'];
            $voucher->discount_type = $data['promoType'];
            $voucher->discount_value = $data['promoValue'];
            $voucher->discount_maximal = $data['maximalDiskon'];
            $voucher->code = $data['codeVoucher'];
            $voucher->kuota = $data['kuota'];

            
            $voucher->date_start_voucher = $data['startVoucher'];
            $voucher->date_end_voucher = $data['endVoucher'];

            
            $voucher->voucher_type = $data['typeVoucher'];
            if (isset( $data['voucherStatus'])) {
                $voucher->status = '1';
            } else {
                $voucher->status = '0';

            }
            $voucher->save();
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Voucher Success Added',
                'message' => 'Kamu telah berhasil menambahkan voucher baru',
                'url' => '/voucher',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];
            return response()->json($messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => 'Kode Voucher Telah Digunakan',
                'button' => "Oke, Mengerti",
            ];
        }
    }

    /**
     * Post edit voucher
     */
    public function editVoucher()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set('Asia/Jakarta');
            $voucher = Voucher::find($data['idVoucher']);
            $voucher->creator_id = session('session_id.id');
            $voucher->title = $data['judulVoucher'];
            $voucher->description = $data['description'];
            $voucher->minimum_shop_price = $data['minimumShoping'];
            $voucher->discount_type = $data['promoType'];
            $voucher->discount_value = $data['promoValue'];
            $voucher->discount_maximal = $data['maximalDiskon'];
            $voucher->code = $data['codeVoucher'];
            $voucher->kuota = $data['kuota'];
            $voucher->date_start_voucher = $data['startVoucher'];
            $voucher->date_end_voucher = $data['endVoucher'];
            $voucher->voucher_type = $data['typeVoucher'];
            if (isset( $data['voucherStatus'])) {
                $voucher->status = '1';
            } else {
                $voucher->status = '0';

            }
            $voucher->save();
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Voucher Updated Added',
                'message' => 'Kamu telah berhasil merubah data voucher',
                'url' => '/voucher',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];
            return response()->json($messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $e->getMessage() . ' - ' . $e->getLine(),
                'button' => "Oke, Mengerti",
            ];
        }
    }

    /**
     * Change Status Voucher
     */
    public function changeStatusVoucher()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $voucher = Voucher::find($data['idVoucher']);

            if (!$voucher) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Voucher tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusChange'] == '1') {
                $voucher->status = '1';
            } else {
                $voucher->status = '0';
            }

            $voucher->updated_at = date('Y-m-d H:i:s');

            if (!$voucher->save()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan voucher',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Ubah status Voucher',
                'message' => 'Ubah status Voucher berhasil.',
                'url' => '/voucher',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];

            return response()->json($messages);
        } catch (\Exception  $e) {
            DB::rollback();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $e->getMessage(),
                'button' => "Oke, Mengerti",
            ];
        }
    }

    /**
     * Delete voucher
     */
    public function deleteVoucher()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $voucher = Voucher::find($data['idVoucher']);

            if (!$voucher) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Voucher tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (!$voucher->delete()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menghapus produk',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Menghapus Voucher',
                'message' => 'Voucher berhasil dihapus.',
                'url' => '/voucher',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];

            return response()->json($messages);
        } catch (\Exception  $e) {
            DB::rollback();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $e->getMessage(),
                'button' => "Oke, Mengerti",
            ];
        }
    }
}
