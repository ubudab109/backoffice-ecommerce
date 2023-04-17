<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\EncryptController;
use App\Http\Controllers\Security\ValidatorController;
use App\Http\Controllers\RequestController;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{

    public function viewRole(){
        return view('role.role-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Role',
                    'url'   => '/role'
                ]
            ],
            'subtitle' => 'Daftar Role',
            'grup' => 'role'
        ]);
    }

    public function listRole(){
        $role = Role::with('creator')->get();
        return DataTables::of($role)
        ->editColumn('title_name', function ($row) {
            return "<center>$row->title</center>";
        })
        ->editColumn('creator', function ($row) {
            if ($row->creator != null) {
                return "<center>". ucfirst($row->creator->name) ."</center>";
            } else {
                return "<center>Admin</center>";
            }
        })
        ->editColumn('updated_at', function ($row) {
            $data = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('Y-m-d');
            return "<center>" . $data . "</center>";
        })
        ->addColumn('action', function ($row) {
            $buttonDetail = '';
            $buttonEdit = '';
            $buttonDelete = '';
            if (canAccess('role_management_detail')) {
                $buttonDetail = '<a class="dropdown-item" href=' . route('get.detail.role', $row->id) . '>
                    <img src='.asset('image/detail.svg').' /> Detail Data
                </a>';
            } else {
                $buttonDetail = '';
            }
            if (canAccess('role_management_edit')) {
                $buttonEdit = '<a class="dropdown-item" href=' . route('get.edit-detail.role', $row->id) . '>
                    <img src='.asset('image/edit.svg').' /> Edit Data
                </a>';
            } else  {
                $buttonEdit = '';
            }
            if (canAccess('role_management_delete')) {
                $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(" . json_encode($row->id) . ")'>
                    <img src=".asset('image/delete.svg')." /> Delete Data
                </a>";
            } else {
                $buttonDelete = '';
            }

            $data = '
            <center>
                <div class="dropdown">
                    <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src='.asset('image/dropdown.svg').' />
                    </button>
                
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    '. $buttonEdit . $buttonDetail . $buttonDelete .'
                    </div>
                </div>
            </center>
            ';
            return $data;
        })
        ->rawColumns(['title_name','creator','updated_at','action'])
        ->make(true);

    }

    public function viewRoleAdd(){
        $permission = Permission::where('parent_id', null)->where('title','NOT LIKE','%dashboard%')->with('child')->get()->toArray();
        return view('role.role-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management Role',
                    'url'   => '/role'
                ],
            ],
            'subtitle' => 'Add Role',
            'grup' => 'role',
            'permission' => $permission
        ]);
    }

    public function addRole(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            date_default_timezone_set("Asia/Jakarta");

            if (!isset($data['permission'])) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Harus memilih permission',
                    'button' => "Oke, Mengerti",
                ];
            }

            $checkRole = Role::where('title', strtolower($data['titleRole']))->first();
            if ($checkRole) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Title Role sudah tersedia',
                    'button' => "Oke, Mengerti",
                ];
            }

            $idRole = Uuid::generate()->string;
            $role = new Role;
            $role->id = $idRole;
            $role->title = strtolower($data['titleRole']);
            $role->created_at = date('Y-m-d H:i:s');

            if(!$role->save()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan role',
                    'button' => "Oke, Mengerti",
                ];
            }

            $rolePermission = [];
            if (!is_array($data['permission'])) {
                $rolePermission[0]['id'] = Uuid::generate()->string;
                $rolePermission[0]['created_at'] = date('Y-m-d H:i:s');
                $rolePermission[0]['permission_id'] = $data['permission'];
                $rolePermission[0]['role_id'] = $idRole;
            }else{
                for ($i=0; $i < count($data['permission']); $i++) { 
                    $rolePermission[$i]['id'] = Uuid::generate()->string;
                    $rolePermission[$i]['created_at'] = date('Y-m-d H:i:s');
                    $rolePermission[$i]['permission_id'] = $data['permission'][$i];
                    $rolePermission[$i]['role_id'] = $idRole;
                }
            }

            $addRolePermission = RolePermission::insert($rolePermission);

            if(!$addRolePermission){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan role',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'New Role Success Added',
                'message' => 'Kamu telah berhasil menambahkan role baru',
                'url' => '/role',
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

    public function viewRoleDetail($id){
        $permission = Permission::where('parent_id', null)->where('title','NOT LIKE','%dashboard%')->with('child')->get()->toArray();
        $role = Role::where('id', $id)->first();
        if ($role) {
            $rolePermission = RolePermission::where('role_id', $id)->get()->toArray();
            $listPermission = [];
            for ($i=0; $i < count($rolePermission); $i++) { 
                array_push($listPermission, $rolePermission[$i]['permission_id']);
            }
            
            return view('role.role-detail')->with([
                'breadcumb' => [
                    [
                        'title' => 'Management Role',
                        'url'   => '/role'
                    ],
                ],
                'subtitle' => 'Detail Role',
                'grup' => 'role',
                'permission' => $permission,
                'role' => $role,
                'listPermission' => $listPermission
            ]);
        }else{
            abort(404);
        }
    }

    public function viewRoleEdit($id)
    {
        $permission = Permission::where('parent_id', null)->where('title','NOT LIKE','%dashboard%')->with('child')->get()->toArray();
        $role = Role::where('id', $id)->first();
        if ($role) {
            $rolePermission = RolePermission::where('role_id', $id)->get()->toArray();
            $listPermission = [];
            for ($i=0; $i < count($rolePermission); $i++) { 
                array_push($listPermission, $rolePermission[$i]['permission_id']);
            }
            
            return view('role.role-detail-edit')->with([
                'breadcumb' => [
                    [
                        'title' => 'Management Role',
                        'url'   => '/role'
                    ],
                ],
                'subtitle' => 'Add Role',
                'grup' => 'role',
                'permission' => $permission,
                'role' => $role,
                'listPermission' => $listPermission
            ]);
        }else{
            abort(404);
        }
    }

    public function editRole(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            date_default_timezone_set("Asia/Jakarta");
            if (!isset($data['permission'])) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Harus memilih permission',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['titleOld'] != strtolower($data['titleRole'])) {
                $checkRole = Role::where('title', strtolower($data['titleRole']))->first();
                if ($checkRole) {
                    return [
                        'status'  => 'error',
                        'title'   => 'Maaf terjadi kesalahan',
                        'message' => 'Title Role sudah tersedia',
                        'button' => "Oke, Mengerti",
                    ];
                }
            }

            $role = Role::find($data['idRole']);

            if(!$role){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Role tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            
            $role->title = strtolower($data['titleRole']);
            $role->updated_at = date('Y-m-d H:i:s');
            if ($data['flagStatus'] == '1') {
                $role->status = true;
            }else{
                $role->status = false;
            }

            if(!$role->save()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan role',
                    'button' => "Oke, Mengerti",
                ];
            }

            $deleteRolePermission = RolePermission::where('role_id', $data['idRole'])->delete();
            $rolePermission = [];
            if (!is_array($data['permission'])) {
                $rolePermission[0]['id'] = Uuid::generate()->string;
                $rolePermission[0]['created_at'] = date('Y-m-d H:i:s');
                $rolePermission[0]['permission_id'] = $data['permission'];
                $rolePermission[0]['role_id'] = $data['idRole'];
            }else{
                for ($i=0; $i < count($data['permission']); $i++) { 
                    $rolePermission[$i]['id'] = Uuid::generate()->string;
                    $rolePermission[$i]['created_at'] = date('Y-m-d H:i:s');
                    $rolePermission[$i]['permission_id'] = $data['permission'][$i];
                    $rolePermission[$i]['role_id'] = $data['idRole'];
                }
            }

            $addRolePermission = RolePermission::insert($rolePermission);

            if(!$addRolePermission){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal megubah role',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Edit Role',
                'message' => 'Kamu telah berhasil merubah data role',
                'url' => '/role',
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

    public function deleteRole(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            date_default_timezone_set("Asia/Jakarta");

            $role = Role::find($data['idRole']);

            if(!$role){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Role tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if (isset($data['statusChange'])) {
                if ($data['statusChange'] == '1') {
                    $role->status = true;
                }else{
                    $role->status = false;
                }
            }
            
            $role->updated_at = date('Y-m-d H:i:s');

            if(!$role->delete()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan role',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Delete Role',
                'message' => 'Delete Role berhasil.',
                'url' => '/role',
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
