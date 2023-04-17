<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\CategoryProduct;
use App\Models\productProduct;
use App\Models\Product;
use App\Models\ProductFiles;
use App\Models\ProductInventory;
use App\Models\TagProduct;
use Carbon\Carbon;
use Request;
use DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
// use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * View Product
     */
    public function viewProduct()
    {
        return view('product.product-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Product',
                    'url'   => '/product'
                ]
            ],
            'subtitle' => 'Daftar Produk',
            'grup' => 'product'
        ]);
    }

    /**
     * View Product Detail
     */
    public function viewProductDetail($id)
    {
        $product = Product::with('category')->with('tag')->find($id);
        $categories = CategoryProduct::withTrashed()->where('status', '1')->get();

        return view('product.product-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Product',
                    'url'   => '/product'
                ]
            ],
            'subtitle' => 'Detail Produk',
            'grup' => 'product',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * View Product Detail with Inventory
     */
    public function viewProductDetailInventory()
    {
        $id = Request::query('product');
        $product = Product::with('category')->with('tag')->find($id);
        $categories = CategoryProduct::withTrashed()->where('status', '1')->get();
        $inventory = ProductInventory::where('product_id', $id)->get();
        return view('product.product-detail-inventory')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Product',
                    'url'   => '/product'
                ]
            ],
            'subtitle' => 'Inventory Produk',
            'grup' => 'product',
            'product' => $product,
            'categories' => $categories,
            'inventories' => $inventory,
        ]);
    }

    /**
     * View Product Detail
     */
    public function viewProductEdit($id)
    {
        $product = Product::with('category')->with('tag')->find($id);
        $categories = CategoryProduct::where('status', '1')->get();
        return view('product.product-edit')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Product',
                    'url'   => '/product'
                ]
            ],
            'subtitle' => 'Edit Produk',
            'grup' => 'product',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * List datatable Product
     */
    public function listProduct()
    {
        $product = Product::with('files')->with('category')->with('creator')->get();

        return DataTables::of($product)
            ->editColumn('code', function ($row) {
                return "<center>" . $row->code_product . "</center>";
            })
            ->editColumn('product_name', function ($row) {
                return "<center>" . $row->name . "</center>";
            })
            ->editColumn('category_name', function ($row) {
                if($row->category) {
                    return "<center>" . $row->category->name . "</center>";
                }
            })
            ->editColumn('creator', function ($row) {
                if($row->creator->userAdmin){
                    return "<center>" . $row->creator->userAdmin->name . "</center>";
                }
            })
            ->editColumn('price_product', function ($row) {
                return "<center>" . rupiah($row->product_price) . "</center>";
            })
            ->editColumn('string_status', function ($row) {
                if ($row->status == '1') {
                    return "Aktif";
                }

                return "Tidak Aktif";
            })
            ->editColumn('string_promo_status', function ($row) {
                if ($row->promo_status == '1') {
                    return "Aktif";
                }
                return "Tidak Aktif";
            })
            ->editColumn('status_promo', function ($row) {
                if (canAccess('product_management_edit')) {
                    $disabled = '';
                } else {
                    $disabled = 'disabled';
                }

                if ($row->promo_status == true) {
                    $active = 'checked';
                } else {
                    $active = '';
                }

                $data = "
                <div class='custom-control custom-switch'>
                    <input $disabled  $active onchange='openModalStatusPromo($row->promo_status," . json_encode($row->id) . ", $row->promo_price, ".json_encode($row->promo_type).", $row->promo_value, $row->price)' type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
                    <label class='custom-control-label' for='customSwitches-$row->id'></label>
                </div>
                
                ";

                return '<center>' . $data . '</center>';
            })
            ->editColumn('tipe_promo', function ($row) {
                if ($row->promo_type == 'fixed') {
                    $data = "<center>Potongan Harga</center>";
                } else if ($row->promo_type == 'discount') {
                    $data = "<center>Diskon</center>";
                } else {
                    $data = "<center>Tidak ada Promo</center>";
                }

                return $data;
            })
            ->editColumn('promo_value', function ($row) {
                if ($row->promo_type == 'fixed') {
                    $data = "<center>" . rupiah($row->promo_value) . "</center>";
                } else if ($row->promo_type == 'discount') {
                    $data = "<center>" . $row->promo_value . "%</center>";;
                } else {
                    $data = "<center>Tidak ada Promo</center>";
                }

                return $data;
            })
            ->editColumn('promo_total_price', function ($row) {
                if ($row->promo_status == '1') {
                    $data = "<center>" . rupiah($row->promo_price) . "</center>";
                } else {
                    $data = "<center>Tidak ada Promo</center>";
                }
                return $data;
            })
            ->editColumn('product_status', function ($row) {
                if (canAccess('product_management_edit')) {
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
                    <input $disabled  $active onchange='openModalStatus($row->status," . json_encode($row->id) . ")' type='checkbox' class='custom-control-input' id='productStatus-$row->id'>
                    <label class='custom-control-label' for='productStatus-$row->id'></label>
                </div>
                
                ";

                return '<center>' . $data . '</center>';
            })
            ->editColumn('updated_at', function ($row) {
                $data = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('Y-m-d');
                return "<center>" . $data . "</center>";
            })
            ->addColumn('action', function ($row) {
                if (canAccess('product_management_detail')) {
                    $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.product', $row->id) . '>
                        <img src=' . asset('image/detail.svg') . ' /> Detail Data
                    </a>';
                }
                if (canAccess('product_management_edit')) {
                    $buttonEdit = '<a class="dropdown-item" href=' . route('get.edit-detail.product', $row->id) . '>
                        <img src=' . asset('image/edit.svg') . ' /> Edit Data
                    </a>';
                }
                if (canAccess('product_management_delete')) {
                    $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(" . json_encode($row->id) . ")'>
                        <img src=" . asset('image/delete.svg') . " /> Delete Data
                    </a>";
                }

                if (canAccess('product_management_inventory')) {
                    $buttonInventory = "<a class='dropdown-item' href=/product/inventory-detail-product?product=" . $row->id . ">
                        <img src=" . asset('image/detail.svg') . " /> Inventory
                    </a>";
                }
                $data = '
                    <center>
                        <div class="dropdown">
                            <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src=' . asset('image/dropdown.svg') . ' />
                            </button>
                        
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            ' . $buttonDetail . $buttonEdit . $buttonDelete . $buttonInventory . '
                            </div>
                        </div>
                    </center>
                    ';
                return $data;
            })
            ->rawColumns([
                'code',
                'product_name',
                'creator',
                'category_name',
                'price_product',
                'status_promo',
                'tipe_promo',
                'promo_value',
                'promo_total_price',
                'product_status',
                'updated_at',
                'action',
            ])
            ->make(true);
    }

    /**
     * View Add Product
     */
    public function viewAddProduct()
    {
        $categories = CategoryProduct::where('status', '1')->get();
        return view('product.product-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Product',
                    'url'   => '/product'
                ],
            ],
            'subtitle' => 'Add Product',
            'grup' => 'product',
            'categories' => $categories,
        ]);
    }

    /**
     * Process add product
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set('Asia/Jakarta');
            $countProduct = Product::withTrashed()->count();
            $code = str_pad($countProduct, 3, '0', STR_PAD_LEFT);
            $category = CategoryProduct::withTrashed()->find((int)$data['categoryId']);
            $product = new Product;
            $productId = $data['id_product'];
            $product->id = $data['id_product'];
            $product->code_product = $category->code_category . '-' . $code;
            $product->category_id = (int)$data['categoryId'];
            $product->name = $data['productName'];
            $product->description = $data['description'];
            $product->creator_id = session('session_id.id');
            $product->product_price = (float)$data['priceProduct'][0];
            if (isset($data['promoStatus'])) {
                $product->promo_value = (float)$data['promoValue'];
                $product->promo_status = '1';
                if ($data['promoType'] == 'fixed') {
                    $totalPromoPrice = (float)$data['priceProduct'][0] - (float)$data['promoValue'];
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'][0] - (float)$data['promoValue'];
                    }
                    $product->promo_type = 'fixed';
                } else if ($data['promoType'] == 'discount') {
                    $discount = (float)$data['promoValue'] / 100;
                    $totalPromoPrice = (float)$data['priceProduct'][0] - (float)($data['priceProduct'][0] * $discount);
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'][0] - (float)($data['priceProduct'][0] * $discount);
                    }
                    $product->promo_type = 'discount';
                }
            }
            $product->save();
            
            
            if (isset($data['tags'])) {
                if ($data['tags'] != '') {
                    TagProduct::create([
                        'product_id'    => $productId,
                        'tag_name'      => $data['tags']
                    ]);
                }
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Product Success Added',
                'message' => 'Kamu telah berhasil menambahkan produk baru',
                'url' => '/product',
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

    public function uploadFile($productId)
    {
        try {

            $data = [];
            if (Request::hasFile('file')) {
                $files = Request::file('file');
                foreach ($files as $file) {
                    $newImageName = storeImages('public/images/product/', $file);
                    $res = ProductFiles::create([
                        'product_id' => $productId,
                        'files'      => URL::to(Storage::url('public/images/product/' . $newImageName))
                    ]);
                    $data[] = $res;
                }
            }
            DB::commit();
            return response()->json($data);
        } catch (\Exception $er) {
            DB::rollBack();
            return response()->json($er->getMessage());
        }
    }

    public function deleteImage($imageId)
    {
        ProductFiles::find($imageId)->delete();
        return true;
    }

    /**
     * Process add product
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProduct()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set('Asia/Jakarta');
            $product = Product::find($data['productId']);
            $product->category_id = (int)$data['categoryId'];
            $product->name = $data['productName'];
            $product->description = $data['description'];
            $product->creator_id = session('session_id.id');
            $product->product_price = (float)$data['priceProduct'];
            if (isset($data['promoStatus'])) {
                if ($product->promo_type == 'fixed') {
                    $totalPromoPrice = (float)$data['priceProduct'] - $data['promoValue'];
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'] - $data['promoValue'];
                    }
                } else if ($product->promo_type == 'discount') {
                    $discount = $data['promoValue'] / 100;
                    $totalPromoPrice = (float)$data['priceProduct'] - (float)($data['priceProduct'] * $discount);
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'] - (float)($data['priceProduct'] * $discount);
                    }
                }

                $product->promo_value = $data['promoValue'];
                $product->promo_status = '1';
            } else {
                $product->promo_status = '0';
            }
            $product->save();

            if (isset($data['tags'])) {
                if ($data['tags'] != '') {
                    TagProduct::where('product_id', $data['productId'])->delete();
                    TagProduct::create([
                        'product_id'    => $data['productId'],
                        'tag_name'      => $data['tags']
                    ]);
                }
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Product Success Updated',
                'message' => 'Kamu telah berhasil mengubah data produk',
                'url' => '/product',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];
            return response()->json($messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => [
                    $e
                ],
                'button' => "Oke, Mengerti",
            ];
        }
    }

    /**
     * Change Promo Status
     */
    public function changePromoStatus()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $product = Product::find($data['idProduk']);

            if (!$product) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Produk tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusPromoChange'] == '1') {
                $product->promo_status = '1';
                $product->promo_value = (float)$data['promoValue'];
                $product->promo_status = '1';
                if ($data['promoType'] == 'fixed') {
                    $totalPromoPrice = (float)$data['priceProduct'][0] - (float)$data['promoValue'];
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'][0] - (float)$data['promoValue'];
                    }
                    $product->promo_type = 'fixed';
                } else if ($data['promoType'] == 'discount') {
                    $discount = (float)$data['promoValue'] / 100;
                    $totalPromoPrice = (float)$data['priceProduct'][0] - (float)($data['priceProduct'][0] * $discount);
                    if ($totalPromoPrice < 0) {
                        $product->promo_price = 0;
                    } else {
                        $product->promo_price = (float)$data['priceProduct'][0] - (float)($data['priceProduct'][0] * $discount);
                    }
                    $product->promo_type = 'discount';
                }
            } else {
                $product->promo_status = '0';
            }

            $product->updated_at = date('Y-m-d H:i:s');

            if (!$product->save()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan Produk',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Ubah status promo Produk',
                'message' => 'Ubah status promo Produk berhasil.',
                'url' => '/product',
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
     * Change Status Produk
     */
    public function changeStatusProduct()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $product = Product::find($data['idProduk']);

            if (!$product) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Produk tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusChange'] == '1') {
                $product->status = '1';
            } else {
                $product->status = '0';
            }

            $product->updated_at = date('Y-m-d H:i:s');

            if (!$product->save()) {
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
                'title' => 'Ubah status Produk',
                'message' => 'Ubah status Produk berhasil.',
                'url' => '/product',
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
     * Delete produk
     */
    public function deleteProduct()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $product = Product::find($data['idProduk']);

            if (!$product) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Produk tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (!$product->delete()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menghapus produk',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success', // supaya tidak muncul popup setelah hapus
                'tableId' => 'tableProduct',
                'callback' => 'reloadTable',
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
