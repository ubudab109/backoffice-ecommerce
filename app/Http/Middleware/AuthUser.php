<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $session_id = Session()->get('session_id');
        // dd($session_id);
        if ($session_id != null) {
            $admin = Users::where('email', $session_id['email'])->first();
            if ($admin == null) {
                Session::flush();
                Auth::logout();
                return redirect('/login');
            } else {
                if ($session_id['token'] != $admin->token) {
                    Session::flush();
                    Auth::logout();
                    if ($request->ajax()) {
                        return response()->json([
                            'status' => 'error',
                            'title' => 'Session Berakhir',
                            'message' => 'Session Anda berakhir, silahkan login kembali',
                            'url' => '/login',
                            'callback' => 'redirect',
                            'button' => "Oke, Mengerti",
                        ]);
                    } else {
                        $messages = [
                            'status'  => 'error',
                            'title'   => 'Session Berakhir',
                            'message' => 'Session Anda berakhir, silahkan login kembali',
                            'button' => "Oke, Mengerti",
                        ];
                        return redirect('/login')->with('notif', $messages);
                    }
                } else {
                    return $next($request);
                }
            }
        } else {
            Session::flush();
            Auth::logout();
            return redirect('login');
        }
    }
}
