<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AddUser;
use App\Mail\ForgetPassword;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    //
    public function newUser($credential)
    {
        # code...
        Mail::to($credential['username'])->queue(new AddUser($credential));
    }

    
    public function forgetPassword($to, $credential)
    {
        # code...
        Mail::to($to)->send(new ForgetPassword($credential));
    }
}
