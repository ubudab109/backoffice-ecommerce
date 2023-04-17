<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{

    /**
     * View User
     * 
     * @return View
     */
    public function viewCategory()
    {
        return view('category.category-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Kategori',
                    'url'   => '/category'
                ]
            ],
            'subtitle' => 'Daftar Kategori',
            'grup' => 'category'
        ]);
    }

    /**
     * Data Category
     * 
     * @return Array
     */
    public function listCategory()
    {
        $categories = CategoryProduct::all();

        return DataTables::make($categories)
            ->editColumn('icon', function ($row) {
                return "<center>
                <a style='color:#35BA4D;' type='button' onclick='openIcon(".json_encode($row->icon).")'>".$row->name."</a></center>";
            })
            ->editColumn('creator', function ($row) {
                return "<center>".$row->creator->userAdmin->name."</center>";
            })
            ->editColumn('category_name', function ($row) {
                return '<center>' . $row->name . '</center>';
            })
            ->editColumn('code_category', function ($row) {
                return '<center>' . $row->code_category . '</center>';
            })
            ->editColumn('string_status', function ($row) {
                if ($row->status == '1') {
                    return "Aktif";
                }

                return "Tidak Aktif";
            })
            ->editColumn('status_category', function ($row) {

                if (canAccess('category_management_edit')) {
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
                    <input $disabled  $active onchange='openModalStatus($row->status,$row->id)' type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
                    <label class='custom-control-label' for='customSwitches-$row->id'></label>
                </div>
                ";
                return '<center>' . $data . '</center>';
            })
            ->addColumn('action', function ($row) {
                if (canAccess('category_management_detail')) {
                    $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.category', $row->id) . '>
                        <img src='.asset('image/edit.svg').' /> Edit Data
                    </a>';
                }
                if (canAccess('category_management_delete')) {
                    $buttonDelete = '<a class="dropdown-item" href="#" onclick="openModalDelete(' . $row->id . ')">
                        <img src='.asset('image/delete.svg').' /> Delete Data
                    </a>';
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
            ->rawColumns(['status_category', 'category_name','creator', 'code_category', 'icon', 'action'])
            ->make(true);
    }

    /**
     * View Add Category
     * 
     * @return View
     */
    public function viewAddCategory()
    {
        return view('category.category-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Kategori',
                    'url'   => '/category'
                ],
            ],
            'subtitle' => 'Add Category',
            'grup' => 'category',
        ]);
    }

    /**
     * Process add category
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCategory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            // dd($encrypt->fnDecrypt(Request::input('data')));
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            // dd($data);
            date_default_timezone_set('Asia/Jakarta');
            $existsCategory = CategoryProduct::withTrashed()->where('code_category', $data['codeCategory'])->exists();
            if ($existsCategory) {
                return [
                    'status'  => 'error',
                    'title'   => 'Data Kategori Telah Ada',
                    'message' => 'Data Kategori dengan kode ini telah ada sebelumnya',
                    'button' => "Oke, Mengerti",
                ];
            }
            $category = new CategoryProduct;
            $category->name = $data['categoryName'];
            $category->code_category = $data['codeCategory'];
            if (isset($data['flagStatus'])) {
                $category->status = '1';
            } else {
                $category->status = '0';
            }
            $category->creator_id = session('session_id.id');
            if (Request::hasFile('file')) {
                $newImageName = storeImages('public/images/category/', Request::file('file'));
                $category->icon = URL::to(Storage::url('public/images/category/' . $newImageName));
            }
            $category->save();
            // CategoryProduct::create($data);
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Category Success Added',
                'message' => 'Kamu telah berhasil menambahkan kategori baru',
                'url' => '/category',
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
     * View category detail
     * 
     * @return View
     */
    public function viewCategoryDetail($id)
    {
        $category = CategoryProduct::findOrFail($id);
        return view('category.category-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Kategori',
                    'url'   => '/category'
                ],
            ],
            'subtitle' => 'Edit Category',
            'grup' => 'category',
            'category' => $category,
        ]);
    }

    /**
     * Post Update Category Data
     * 
     * @return array
     */
    public function editCategory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");
            $existsCategory = CategoryProduct::withTrashed()->where('code_category', $data['codeCategory'])->exists();
            $category = CategoryProduct::find($data['idCategory']);

            if ($existsCategory && $category->code_category !== $data['codeCategory']) {
                return [
                    'status'  => 'error',
                    'title'   => 'Data Kategori Telah Ada',
                    'message' => 'Data Kategori dengan kode ini telah ada sebelumnya',
                    'button' => "Oke, Mengerti",
                ];
            }
            $category->name = $data['categoryName'];
            $category->code_category = $data['codeCategory'];
            if (isset($data['flagStatus'])) {
                $category->status = '1';
            } else {
                $category->status = '0';
            }
            $category->creator_id = session('session_id.id');
            if (Request::hasFile('file')) {
                $newImageName = storeImages('public/images/category/', Request::file('file'));
                $category->icon = URL::to(Storage::url('public/images/category/' . $newImageName));
            }
            $category->save();
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Edit category',
                'message' => 'Kamu telah berhasil merubah data kategori',
                'url' => '/category',
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
     * Change Status category
     */
    public function changeStatusCategory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $category = CategoryProduct::find($data['idCategory']);

            if (!$category) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Kategori tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusChange'] == '1') {
                $category->status = '1';
            } else {
                $category->status = '0';
            }

            $category->updated_at = date('Y-m-d H:i:s');

            if (!$category->save()) {
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
                'title' => 'Ubah status Kategori',
                'message' => 'Ubah status Kategori berhasil.',
                'url' => '/category',
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
     * Delete category
     */
    public function deleteCategory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $category = CategoryProduct::find($data['idCategory']);

            if (!$category) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Kategori tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (!$category->delete()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menghapus kategori',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Menghapus Kategori',
                'message' => 'Kategori berhasil dihapus.',
                'url' => '/category',
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
