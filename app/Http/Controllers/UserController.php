<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\EncryptController;
use App\Http\Controllers\Security\ValidatorController;
use App\Http\Controllers\RequestController;
use App\Jobs\EmailJobUser;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\Users;
use App\Models\UserAdmin;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Request;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function viewUser(){
        return view('user.user-list')->with([
            'breadcumb' => [
                [
                    'title' => 'Management User',
                    'url'   => '/user'
                ]
            ],
            'subtitle' => 'Daftar User',
            'grup' => 'user'
        ]);
    }

    public function listUser(){
        $users = Users::with('creator')->get();
        return DataTables::of($users)
        ->editColumn('fullname', function ($row) {
            return "<center>".ucfirst($row->userAdmin->name)."</center>";
        })
        ->editColumn('email_user', function ($row) {
            return "<center>$row->email</center>";
        })
        ->editColumn('role', function ($row) {
            return "<center>$row->role_name</center>";
        })
        ->editColumn('string_status', function ($row) {
            if ($row->status == '1') {
                return "Aktif";
            }

            return "Tidak Aktif";
        })
        ->editColumn('phone', function ($row) {
            if ($row->phone_number != null) {
                return "<center>$row->phone_number</center>";
            } else {
                return "<center>Not Have Phone Number</center>";
            }
        })
        ->editColumn('status_user', function ($row) {

            if (canAccess('user_management_edit')) {
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
                <input $disabled  $active onchange='openModalStatus(".$row->status.",".json_encode($row->id).")' type='checkbox' class='custom-control-input' id='customSwitches-$row->id'>
                <label class='custom-control-label' for='customSwitches-$row->id'></label>
            </div>
            ";
            return '<center>' . $data . '</center>';
        })
        ->editColumn('creator', function ($row) {
            if ($row->creator != null) {
                return "<center>".ucfirst($row->creator->name)."</center>";
            } else {
                return "<center>Admin</center>";
            }
        })
        ->editColumn('updated_at', function ($row) {
            $data = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('Y-m-d');
            return "<center>" . $data . "</center>";
        })
        ->addColumn('action', function ($row) {
            if (canAccess('user_management_detail')) {
                $buttonDetail = '
                <a class="dropdown-item" href=' . route('get.detail.user', $row->id) . '>
                    <img src='.asset('image/detail.svg').' /> Detail Data
                </a>
                
                ';
            }
            if (canAccess('user_management_edit')) {
                $buttonEdit = '<a class="dropdown-item" href=' . route('get.edit-detail.user', $row->id) . '>
                    <img src='.asset('image/edit.svg').' /> Edit Data
                </a>';
            }
            if (canAccess('user_management_delete')) {
                $buttonDelete = "<a class='dropdown-item' href='#' onclick='openModalDelete(" . json_encode($row->id) . ")'>
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
                    '. $buttonEdit . $buttonDetail . $buttonDelete .'
                    </div>
                </div>
            </center>
            ';
            return $data;
        })
        ->rawColumns(['fullname','email_user','status_user','phone','creator','role','updated_at','action'])
        ->make(true);
    }

    public function viewUserAdd(){
        $role = Role::where('title', '!=', 'merchant')->where('status', true)->get()->toArray();
        return view('user.user-add')->with([
            'breadcumb' => [
                [
                    'title' => 'Management User',
                    'url'   => '/user'
                ],
            ],
            'subtitle' => 'Add User',
            'grup' => 'user',
            'role' => $role
        ]);
    }

    public function addUser(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            date_default_timezone_set("Asia/Jakarta");


            $checkUser = Users::where('email', $data['email'])->first();

            if($checkUser){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Email sudah digunakan.',
                    'button' => "Oke, Mengerti",
                ];
            }
            $user_id = Uuid::generate()->string;
            $plainPassword = Str::random(8);

            $user = new Users;
            $user->id = $user_id;
            $user->email = $data['email'];
            $user->password = Hash::make($plainPassword);
            $user->phone_number = $data['phoneNumber'];
            $user->address = $data['address'];
            $user->creator_id = session('session_id.id');
            $user->status = true;
            $user->type = 1;
            $user->updated_at = date('Y-m-d H:i:s');

            if(!$user->save()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan user',
                    'button' => "Oke, Mengerti",
                ];
            }

            $admin = new UserAdmin;
            $admin->id = Uuid::generate()->string;
            $admin->updated_at = date('Y-m-d H:i:s');
            $admin->name = $data['name'];
            $admin->user_id = $user_id;
            $admin->role_id = $data['role'];
            

            if(!$admin->save()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan user',
                    'button' => "Oke, Mengerti",
                ];
            }

            $messages = [
                'status' => 'success',
                'title' => 'New User Success Added',
                'message' => 'Kamu telah berhasil menambahkan user baru',
                'url' => '/user',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];

            $credential = [
                'username' => $data['email'],
                'password' => $plainPassword,
                'name' => $data['name']
            ];

            $sendEmail = new EmailController;
            $sendEmail->newUser($credential);
            DB::commit();

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

    public function viewUserDetail($id){
        $role = Role::where('title', '!=', 'merchant')->where('status', true)->get()->toArray();
        $user = Users::where('id', $id)->first();
        if ($user) {
            $admin = UserAdmin::select('name', 'role_id')->where('user_id', $user->id)->first();
            if ($admin) {
                $permission = RolePermission::select('permission.title')->join('permission', 'permission.id', 'role_permission.permission_id')->where('role_permission.role_id', $admin->role_id)->get()->toArray();
                for ($i=0; $i < count($permission); $i++) { 
                    $permission[$i]['title'] = ucwords(str_replace('_', ' ', $permission[$i]['title']));
                }
                return view('user.user-detail')->with([
                    'breadcumb' => [
                        [
                            'title' => 'Management User',
                            'url'   => '/user'
                        ],
                    ],
                    'subtitle' => 'Detail User',
                    'grup' => 'user',
                    'role' => $role,
                    'user' => $user,
                    'admin' => $admin,
                    'permission' => $permission
                ]);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function viewUserEdit($id)
    {
        $role = Role::where('title', '!=', 'merchant')->where('status', true)->get()->toArray();
        $user = Users::where('id', $id)->first();
        if ($user) {
            $admin = UserAdmin::select('name', 'role_id')->where('user_id', $user->id)->first();
            if ($admin) {
                $permission = RolePermission::select('permission.title')->join('permission', 'permission.id', 'role_permission.permission_id')->where('role_permission.role_id', $admin->role_id)->get()->toArray();
                for ($i=0; $i < count($permission); $i++) { 
                    $permission[$i]['title'] = ucwords(str_replace('_', ' ', $permission[$i]['title']));
                }
                return view('user.user-detail-edit')->with([
                    'breadcumb' => [
                        [
                            'title' => 'Management User',
                            'url'   => '/user'
                        ],
                    ],
                    'subtitle' => 'Edit User',
                    'grup' => 'user',
                    'role' => $role,
                    'user' => $user,
                    'admin' => $admin,
                    'permission' => $permission
                ]);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function editUser(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            $sendEmail = false;
            if ($data['oldStatus'] != $data['flagStatus']) {
                $sendEmail = true;
            }
            date_default_timezone_set("Asia/Jakarta");

            $user = Users::find($data['idUser']);

            if(!$user){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            $admin = UserAdmin::where('user_id', $data['idUser'])->first();

            if(!$admin){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            $admin->name = $data['name'];
            $admin->role_id = $data['role'];

            $checkEmail = User::where('email',$data['email'])->exists();
            if ($user->email !== $data['email']) {
                if ($checkEmail) {
                    return [
                        'status'  => 'error',
                        'title'   => 'Email sudah pernah digunakan',
                        'message' => 'Email sudah pernah digunakan',
                        'button' => "Oke, Mengerti",
                    ];
                }
            }
            $user->email = $data['email'];
            $user->phone_number = $data['phoneNumber'];
            $user->address = $data['address'];


            $admin->updated_at = date('Y-m-d H:i:s');
            
            if(!$admin->save() || !$user->save()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal megubah user',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Edit User',
                'message' => 'Kamu telah berhasil mengubah data user',
                'url' => '/user',
                'callback' => 'redirect',
                'button' => "Oke, Mengerti",
            ];

            return response()->json($messages);
        } catch (\Exception  $e) {
            return $e;
            DB::rollback();
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $e->getMessage(),
                'button' => "Oke, Mengerti",
            ];
        }
    }

    public function deleteUser(){
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'),true);
            date_default_timezone_set("Asia/Jakarta");

            $user = Users::find($data['idDeleteUser']);

            if(!$user){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            $admin = UserAdmin::where('user_id', $data['idDeleteUser'])->first();

            if(!$admin){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }
            $user->updated_at = date('Y-m-d H:i:s');

            if(!$user->delete()){
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Change Status gagal',
                    'button' => "Oke, Mengerti",
                ];
            }

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Delete User',
                'message' => 'Delete User berhasil.',
                'url' => '/user',
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

    public function changeStatusUser()
    {
        DB::beginTransaction();
        try {
            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            date_default_timezone_set("Asia/Jakarta");

            $users = Users::find($data['idUser']);

            if (!$users) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                ];
            }

            if ($data['statusChange'] == '1') {
                $users->status = true;
                $status = 'active';
                $subject = 'Account Unblocked';
            }else{
                $users->status = false;
                $status = 'block';
                $subject = 'Account Blocked';
            }

            $users->updated_at = date('Y-m-d H:i:s');

            if (!$users->save()) {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Gagal menyimpan perubahan User',
                    'button' => "Oke, Mengerti",
                ];
            }

            date_default_timezone_set("Asia/Jakarta");
            $dataEmail = [
                'merchant_name' => $users->name,
                'status' => $status,
                'date' => date('Y-m-d')
            ];

            // $mail = new EmailController;
            // $email = $mail->changeStatusMail($dataEmail);
            // $render = $email->render();

            // $req = new RequestController;

            // $module = 'sendEmail';
            // $dataReq = [
            //     'email' => [
            //         "to" => $users->email,
            //         "subject" => $subject,
            //         "content" => $render,
            //         "content_type" => "text/html"
            //     ]
            // ];
            // $res = $req->sendRequest($module, $dataReq);

            DB::commit();
            $messages = [
                'status' => 'success',
                'title' => 'Ubah status User',
                'message' => 'Ubah status User berhasil.',
                'url' => '/user',
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

    public function searchPermission(){
        $role = Request::input('role');

        $permission = RolePermission::select('permission.title')->join('permission', 'permission.id', 'role_permission.permission_id')->where('role_permission.role_id', $role)->get()->toArray();
        for ($i=0; $i < count($permission); $i++) { 
            $permission[$i]['title'] = ucwords(str_replace('_', ' ', $permission[$i]['title']));
        }
        return response()->json($permission);
    }

    function randomString($length) {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $str;
    }
}
