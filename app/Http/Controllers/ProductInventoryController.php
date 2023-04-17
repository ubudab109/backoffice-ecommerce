<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Security\EncryptController;
use App\Models\Product;
use App\Models\ProductInventory;
use Request;
use DB;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class ProductInventoryController extends Controller
{
    /**
     * View Inventory
     */
    public function viewInventory()
    {

        return view('inventory.inventory-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Inventory',
                    'url'   => '/inventory'
                ]
            ],
            'subtitle' => 'Daftar Inventory',
            'grup' => 'inventory'
        ]);
    }

    /**
     * List data inventory
     */
    public function listInventory()
    {
        if (session('session_id.type') == '1') {
            $inventory = Product::whereHas('inventory')->with('inventory.creator')->get();
        } else {
            $inventory = Product::whereHas('inventory', function ($query) {
                $query->where('creator', session('session_id.id'));
            })->with('inventory.creator')->get();
        }
        return DataTables::of($inventory)
            ->editColumn('code_product', function ($row) {
                return "<center>" . $row->code_product . "</center>";
            })
            ->editColumn('product_name', function ($row) {
                return "<center> <a style='color:#35BA4D;' href=" . route('get.detail.product', $row->id) . ">" . $row->name . "</a></center>";
            })
            ->editColumn('product_price', function ($row) {
                return "<center>" . rupiah($row->price) . "</center>";
            })
            ->editColumn('category', function ($row) {
                return "<center> " . $row->category->name . " </center>";
            })
            ->editColumn('status_promo', function ($row) {
                if ($row->promo_status == true) {
                    $active = 'checked';
                } else {
                    $active = '';
                }
                $data = "
                <div class='custom-control custom-switch'>
                    <input disabled $active type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
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
                if ($row->status == true) {
                    $active = 'checked';
                } else {
                    $active = '';
                }
                $data = "
                <div class='custom-control custom-switch'>
                    <input disabled $active type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
                    <label class='custom-control-label' for='customSwitches-$row->id'></label>
                </div>
                ";

                return '<center>' . $data . '</center>';
            })
            ->editColumn('total_inventory', function ($row) {
                return "<center>$row->total_inventory</center>";
            })
            ->editColumn('total_sold', function ($row) {
                return "<center>$row->total_sold</center>";
            })
            ->editColumn('current_qty', function ($row) {
                $data = $row->total_inventory - $row->total_sold;
                return "<center>$data</center>";
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
            ->addColumn('action', function ($row) {
                $inventoryId = $row->inventory()
                                    // ->where('creator_id', session('session_id.id'))
                                    ->first();

                $buttonDelete = '';
                $buttonEdit = '';
                $buttonDetail = '';
                if($inventoryId) {
                    if (canAccess('inventory_management_detail')) {
                        $buttonEdit = '<a class="dropdown-item" href=' . route('get.edit-detail.inventory', $inventoryId->id) . '>
                            <img src=' . asset('image/edit.svg') . ' /> Edit Data
                        </a>';
                    }
                    if (canAccess('inventory_management_delete')) {
                        $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(" . json_encode($inventoryId->id) . ")'>
                            <img src=" . asset('image/delete.svg') . " /> Delete Data
                        </a>";
                    }
    
                    if (canAccess('inventory_management_detail')) {
                        $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.inventory', $row->id) . '>
                                <img src=' . asset('image/detail.svg') . ' /> Detail Data
                            </a>';
                    }
                }
                $data = '
                    <center>
                        <div class="dropdown">
                            <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src=' . asset('image/dropdown.svg') . ' />
                            </button>
                        
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            ' . $buttonDetail . $buttonEdit . $buttonDelete . '
                            </div>
                        </div>
                    </center>
                    ';
                return $data;
            })
            ->rawColumns([
                'code_product',
                'product_name',
                'category',
                'status_promo',
                'tipe_promo',
                'promo_value',
                'promo_total_price',
                'product_status',
                'total_inventory',
                'product_price',
                'action',
                'total_sold',
                'current_qty',
            ])
            ->make(true);
    }

    /**
     * View Add Banner
     */
    public function viewAddInventory()
    {
        $products = Product::where('status', '1')->get();
        return view('inventory.inventory-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Inventory',
                    'url'   => '/inventory'
                ],
            ],
            'subtitle' => 'Add Inventory',
            'grup' => 'inventory',
            'products' => $products,
        ]);
    }

    /**
     * Post Add Inventory
     */
    public function addInventory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            if (is_array($data['productId'])) {
                foreach ($data['productId'] as $key => $request) {
                    $checkExistst = ProductInventory::where('product_id', $data['productId'][$key])->where('creator_id', session('session_id.id'))->exists();
                    if ($checkExistst) {
                        $messages = [
                            'status' => 'error',
                            'title' => 'Kamu tidak bisa menambah lebih dari 1 produk yang sama',
                            'message' => 'Kamu tidak bisa menambah lebih dari 1 produk yang sama',
                            'url' => '/inventory',
                            'callback' => 'redirect',
                            'button' => "Oke, Mengerti",
                        ];
                        return response()->json($messages);
                    }
                    $inventory = new ProductInventory;
                    $inventory->id = Uuid::generate()->string;
                    $inventory->product_id = $data['productId'][$key];
                    $inventory->qty = $data['totalInventory'][$key];
                    $inventory->note = $data['note'][$key];
                    $inventory->creator_id = session('session_id.id');
                    $inventory->save();
                }
            } else {
                $checkExistst = ProductInventory::where('product_id', $data['productId'])->where('creator_id', session('session_id.id'))->exists();
                if ($checkExistst) {
                    $messages = [
                        'status' => 'error',
                        'title' => 'Kamu tidak bisa menambah lebih dari 1 produk yang sama',
                        'message' => 'Kamu tidak bisa menambah lebih dari 1 produk yang sama',
                        'url' => '/inventory',
                        'callback' => 'redirect',
                        'button' => "Oke, Mengerti",
                    ];
                    return response()->json($messages);
                }
                $inventory = new ProductInventory;
                $inventory->id = Uuid::generate()->string;
                $inventory->product_id = $data['productId'];
                $inventory->qty = $data['totalInventory'];
                $inventory->note = $data['note'];
                $inventory->creator_id = session('session_id.id');
                $inventory->save();
            }
            date_default_timezone_set('Asia/Jakarta');
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New inventory Success Added',
                'message' => 'Kamu telah berhasil menambahkan inventory baru',
                'url' => '/inventory',
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
     * Edit Inventory
     */
    public function editInventory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            $inventory = ProductInventory::find($data['idInventory']);
            $inventory->qty = $data['totalInventory'];
            $inventory->save();
            date_default_timezone_set('Asia/Jakarta');
            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'inventory Success Updated',
                'message' => 'Kamu telah berhasil merubah data inventory',
                'url' => '/inventory',
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

    public function viewDetailInventory($id)
    {
        $product = Product::find($id);
        $inventory = ProductInventory::where('product_id', $id)->get();
        return view('inventory.inventory-detail')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Inventory',
                    'url'   => '/inventory'
                ]
            ],
            'subtitle' => 'Detail Inventory',
            'grup' => 'inventory',

            'product' => $product,
            'inventories' => $inventory,
        ]);
    }

    /**
     * View Edit
     */
    public function viewEditInventory($id)
    {
        $inventory = ProductInventory::with('product')->with('creator')->find($id);
        return view('inventory.inventory-edit')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Inventory',
                    'url'   => '/inventory'
                ]
            ],
            'subtitle' => 'Edit Inventory',
            'grup' => 'inventory',
            'inventory' => $inventory,
        ]);
    }

    /**
     * Delete Inventory
     */
    public function deleteInventory()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $inventory = ProductInventory::find($data['idInventory']);

            if (!$inventory) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Inventory tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (!$inventory->delete()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menghapus inventory',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Menghapus Inventory',
                'message' => 'Inventory berhasil dihapus.',
                'url' => '/inventory',
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
