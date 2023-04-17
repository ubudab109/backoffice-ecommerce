<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\EncryptController;
use App\Http\Controllers\Security\ValidatorController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\EmailController;
use App\Models\ForgotPasswordToken;
use App\Models\Users;
use App\Models\Role;
use App\Models\UserAdmin;
use App\Models\UserMerchant;
use App\Models\RolePermission;
use App\Models\Token;
use Webpatser\Uuid\Uuid;
use Request;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;
use Exception;
class LoginController extends Controller
{
    public function loginView()
    {
        if (Session()->get('session_id') != null) {
            return redirect()->back();
        } else {
            return view('login');
        }
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }

    public function postLogin()
    {
        try {

            $encrypt = new EncryptController;
            $data = $encrypt->fnDecrypt(Request::input('data'), true);
            $user = Users::where('email', $data['username'])->first();
            // dd($user);
            $rules = [
                'captchaLogin'     => 'captcha'
            ];

            $validate = new ValidatorController;
            $responseValidator = $validate->validateTry($data, $rules);

            if ($responseValidator != "pass") {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => $responseValidator->first(),
                    'button' => "Oke, Mengerti",
                    'captcha' => captcha_img('flat')
                ];
            }

            if ($user != null) {
                if (Hash::check($data['password'], $user->password)) {
                    $token = Session::getId();
                    if ($user->status == true) {
                        Users::where('email', $data['username'])->update([
                            'token' => $token,
                            'attempts' => 0
                        ]);
                        if ($user->type == '1') {
                            $userAdmin = UserAdmin::where('user_id', $user->id)->first();
                            if (!$userAdmin) {
                                return [
                                    'status'  => 'error',
                                    'title'   => 'Maaf terjadi kesalahan',
                                    'message' => 'User tidak ditemukan',
                                    'button' => "Oke, Mengerti",
                                    'captcha' => captcha_img('flat')
                                ];
                            }
                            $roleAdmin = Role::where('id', $userAdmin->role_id)->where('status', true)->first();
                            if (!$roleAdmin) {
                                return [
                                    'status'  => 'error',
                                    'title'   => 'Maaf terjadi kesalahan',
                                    'message' => 'Role tidak ditemukan',
                                    'button' => "Oke, Mengerti",
                                    'captcha' => captcha_img('flat')
                                ];
                            }

                            $listPermission = RolePermission::select('permission.title')->join('permission', 'permission.id', 'role_permission.permission_id')->where('role_id', $roleAdmin->id)->get()->toArray();
                            $permission = [];
                            if ($listPermission != []) {
                                for ($i = 0; $i < count($listPermission); $i++) {
                                    array_push($permission, $listPermission[$i]['title']);
                                }
                            }

                            $session = [
                                'id'             => $user->id,
                                'email'          => $user->email,
                                'type'           => $user->type,
                                'status'         => $user->status,
                                'name'           => $userAdmin->name,
                                'user_id'        => $userAdmin->id,
                                'role_id'        => $userAdmin->role_id,
                                'role_name'      => $roleAdmin->title,
                                'token'          => $token,
                                'user_corporate' => "user01",
                                'permission'     => $permission
                            ];
                        }
                        session()->put('session_id', $session);
                        return [
                            'status'   => 'success',
                            'url'      => '/',
                            'callback' => 'login'
                        ];
                    } else {
                        return [
                            'status'  => 'error',
                            'title'   => 'Maaf terjadi kesalahan',
                            'message' => 'User diblokir, silahkan menghubungi Admin',
                            'button' => "Oke, Mengerti",
                            'captcha' => captcha_img('flat')
                        ];
                    }
                }
                else {
                    return [
                        'status'  => 'error',
                        'title'   => 'Login gagal',
                        'message' => 'periksa kembali <br> email dan password Anda',
                        'button' => "Oke, Mengerti",
                        'captcha' => captcha_img('flat')
                    ];
                }
            }
            else {
                return [
                    'status'  => 'error',
                    'title'   => 'Login gagal',
                    'message' => 'periksa kembali <br> email dan password Anda',
                    'button' => "Oke, Mengerti",
                    'captcha' => captcha_img('flat')
                ];
            }
        } catch (\Exception $err) {
            return $err;
        }
    }

    public function newPasswordView($token)
    {
        $passwordToken = ForgotPasswordToken::where('token', $token)->first();
        if (Date::now() > $passwordToken->expired_date) {
            $messages = [
                'status' => 'error',
                'title'   => 'Verifikasi Lupa Password Gagal',
                'message' => 'Link Telah Kadaluarsa',
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
            return redirect(route('get.login.view'))->with('notif', $messages);
        }
        return view('new-password')->with('token', $passwordToken);
    }

    public function newPassword()
    {
        $encrypt = new EncryptController;
        $data = $encrypt->fnDecrypt(Request::input('data'), true);
        $rules = [
            'captchaLogin'     => 'captcha'
        ];

        $validate = new ValidatorController;
        $responseValidator = $validate->validateTry($data, $rules);
        if ($responseValidator != "pass") {
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $responseValidator->first(),
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
        }
        $token = ForgotPasswordToken::where('token', $data['token'])->first();
        $pass = Users::where('id', $token->user_id)->first();
        if ($pass) {
            $update = $pass->update([
                'password' => Hash::make($data['newPassword']),
            ]);

            if ($update) {
                $messages = [
                    'status' => 'success',
                    'title'   => 'Password mu berhasil diganti',
                    'message' => 'Password berhasil diubah',
                    'url' => '/',
                    'button' => "Login Now",
                    'callback' => 'redirect',
                ];

                return response()->json($messages);
            } else {
                $messages = [
                    'status' => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                    'captcha' => captcha_img('flat')
                ];

                return response()->json($messages);
            }
        }
    }

    public function logout()
    {
        FacadesSession::flush();

        Auth::logout();
        return redirect('/login');
    }

    public function viewForgetPassword()
    {
        return view('forget-password');
    }

    public function forgetPassword()
    {
        $encrypt = new EncryptController;
        $data = $encrypt->fnDecrypt(Request::input('data'), true);
        $rules = [
            'captchaLogin'     => 'captcha'
        ];

        $validate = new ValidatorController;
        $responseValidator = $validate->validateTry($data, $rules);
        // dd($responseValidator);
        if ($responseValidator != "pass") {
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $responseValidator->first(),
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
        }
        $searchEmail = Users::where('email', $data['email'])->first();
        // dd($searchEmail);
        if ($searchEmail) {
            $searchAdmin = UserAdmin::where('user_id', $searchEmail->id)->first();
            if ($searchAdmin) {
                date_default_timezone_set("Asia/Jakarta");
                $exp = date_add(date_create(), date_interval_create_from_date_string("1 days"));
                $token = date('YmdHis') . $this->randomString(10);
                ForgotPasswordToken::insert([
                    'id' => Uuid::generate()->string,
                    'token' => $token,
                    'email' => $data['email'],
                    'expired_date' => $exp,
                    'status' => '0',
                    'user_id' => $searchEmail->id
                ]);

                Mail::send('email.forgot-password', ['token' => $token, 'user' => $searchEmail, 'dataUser' => $searchAdmin], function ($messages) use ($searchEmail) {
                    $messages->to($searchEmail->email);
                    $messages->subject('Reset Password');
                });

                $messages = [
                    'status' => 'success',
                    'title' => 'Request Forgot Password Berhasil',
                    'message' => 'Kami telah mengirimkan verifikasi ke email kamu, <br> silahkan cek email mu sekarang juga',
                    'url' => '/login',
                    'callback' => 'redirect',
                    'button' => "Oke, Mengerti",
                ];

                return response()->json($messages);
            } else {
                return [
                    'status'  => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'User tidak ditemukan',
                    'button' => "Oke, Mengerti",
                    'captcha' => captcha_img('flat')
                ];
            }
        } else {
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => 'Email tidak ditemukan',
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
        }
    }

    public function viewResetPassword($userId)
    {
        date_default_timezone_set("Asia/Jakarta");
        $users = Users::find($userId);
        if (!$users) {
            $messages = [
                'status' => 'error',
                'title'   => 'Terjadi Kesalahan',
                'message' => 'Data user tidak ditemukan',
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
            return redirect(route('get.login.view'))->with('notif', $messages);
        }

        return view('reset-password')->with(['user' => $users]);

    }

    public function resetPassword()
    {
        $encrypt = new EncryptController;
        $data = $encrypt->fnDecrypt(Request::input('data'), true);
        $rules = [
            'captchaLogin'     => 'captcha'
        ];

        $validate = new ValidatorController;
        $responseValidator = $validate->validateTry($data, $rules);
        if ($responseValidator != "pass") {
            return [
                'status'  => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => $responseValidator->first(),
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];
        }
        $pass = Users::where('id', $data['idUser'])->first();
        if ($pass) {

            $update = Users::where('id', $data['idUser'])->update([
                'password' => Hash::make($data['newPassword'])
            ]);

            if ($update) {
                $messages = [
                    'status' => 'success',
                    'title'   => 'Password mu berhasil diganti',
                    'message' => 'Kamu dapat login menggunakan password baru mu <br> Silahkan login untuk melanjutkan.',
                    'url' => '/login',
                    'button' => "Login Now",
                    'callback' => 'redirect',
                ];

                return response()->json($messages);
            } else {
                $messages = [
                    'status' => 'error',
                    'title'   => 'Maaf terjadi kesalahan',
                    'message' => 'Membuat password baru gagal',
                    'button' => "Oke, Mengerti",
                    'captcha' => captcha_img('flat')
                ];

                return response()->json($messages);
            }
        } else {
            $messages = [
                'status' => 'error',
                'title'   => 'Maaf terjadi kesalahan',
                'message' => 'User tidak ditemukan',
                'button' => "Oke, Mengerti",
                'captcha' => captcha_img('flat')
            ];

            return response()->json($messages);
        }
    }

    function randomString($length)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $str;
    }
}
