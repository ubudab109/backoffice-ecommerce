<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Request;
use Yajra\DataTables\DataTables;
use DB;
use Webpatser\Uuid\Uuid;

class BannerController extends Controller
{
    /**
     * View Banner
     */
    public function viewBanner()
    {
        return view('banner.banner-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Banner',
                    'url'   => '/banner'
                ]
            ],
            'subtitle' => 'Daftar Banner',
            'grup' => 'banner'
        ]);
    }

    /**
     * List datatable banner
     */
    public function listBanner()
    {
        $banner = Banner::all();

        return DataTables::of($banner)
        ->editColumn('title', function ($row) {
            return "<center><a style='color:#35BA4D;' type='button' href='#' onclick='openFile(".json_encode($row->file).")'>$row->title</a></center>";
        })
        ->editColumn('creator', function ($row) {
            return "<center>".$row->creator->userAdmin->name."</center>";
        })
        ->editColumn('string_status', function ($row) {
            if ($row->status == '1') {
                return "Aktif";
            }

            return "Tidak Aktif";
        })
        ->editColumn('status_banner', function ($row) {

            if (canAccess('banner_management_edit')) {
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
        ->addColumn('action', function ($row) {
            if (canAccess('banner_management_detail')) {
                $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.banner', $row->id) . '>
                    <img src='.asset('image/edit.svg').' /> Edit Data
                </a>';
            }
            if (canAccess('banner_management_delete')) {
                $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(".json_encode($row->id).")'>
                    <img src=".asset('image/delete.svg')." /> Delete Data
                </a>";
            }
            $data = '
            <center>
                <div class="dropdown">
                    <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src='.asset('image/dropdown.svg').' />
                    </button>
                
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    '. $buttonDetail . $buttonDelete .'
                    </div>
                </div>
            </center>
            ';
            return $data;
        })
        ->rawColumns(['title', 'creator','status_banner', 'action'])
        ->make(true);
    }

    /**
     * View Add Banner
     */
    public function viewAddBanner()
    {
        return view('banner.banner-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Banner',
                    'url'   => '/banner'
                ],
            ],
            'subtitle' => 'Add Banner',
            'grup' => 'banner',
        ]);
    }

     /**
     * Process add banner
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBanner()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set('Asia/Jakarta');
            $banner = new Banner;
            $banner->id = Uuid::generate()->string;
            $banner->title = $data['titleBanner'];
            if (isset($data['flagStatus'])) {
                $banner->status = '1';
            } else {
                $banner->status = '0';
            }
            $banner->creator_id = session('session_id.id');
            if (Request::hasFile('file')) {
                $newImageName = storeImages('public/images/banner/', Request::file('file'));
                $banner->file = URL::to(Storage::url('public/images/banner/' . $newImageName));
            }
            $banner->save();
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Banner Success Added',
                'message' => 'Kamu telah berhasil menambahkan banner baru',
                'url' => '/banner',
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
     * View banner detail
     * 
     * @return View
     */
    public function viewBannerDetail($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banner.banner-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Banner',
                    'url'   => '/banner'
                ],
            ],
            'subtitle' => 'Detail Banner',
            'grup' => 'banner',
            'banner' => $banner,
        ]);
    }

     /**
     * Post Update Banner Data
     * 
     * @return array
     */
    public function editBanner()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");
            $banner = Banner::find($data['idBanner']);
            $banner->title = $data['titleBanner'];
            $banner->creator_id = session('session_id.id');
            if (isset($data['flagStatus'])) {
                $banner->status = '1';
            } else {
                $banner->status = '0';
            }
            if (Request::hasFile('file')) {
                $newImageName = storeImages('public/images/banner/', Request::file('file'));
                $banner->file = URL::to(Storage::url('public/images/banner/' . $newImageName));
            }
            $banner->save();
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Edit Banner',
                'message' => 'Kamu telah berhasil merubah data banner',
                'url' => '/banner',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];
            return response()->json($messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $e->getMessage(),
                'button' => "Oke, Mengerti",
            ];
        }
    }

    /**
     * Change Status banner
     */
    public function changeStatusBanner()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $banner = Banner::find($data['idBanner']);

            if (!$banner) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Kategori tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusChange'] == '1') {
                $banner->status = '1';
            } else {
                $banner->status = '0';
            }

            $banner->updated_at = date('Y-m-d H:i:s');

            if (!$banner->save()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan kategori',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Ubah status Banner',
                'message' => 'Ubah status Banner berhasil.',
                'url' => '/banner',
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
     * Delete Banner
     */
    public function deleteBanner()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $banner = Banner::find($data['idBanner']);

            if (!$banner) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Banner tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (!$banner->delete()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menghapus banner',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Menghapus Banner',
                'message' => 'Banner berhasil dihapus.',
                'url' => '/banner',
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
