<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\EncryptController;
use App\Http\Controllers\Security\ValidatorController;
use App\Http\Controllers\RequestController;
use Webpatser\Uuid\Uuid;
use Request;
use Hash;
use Response;
use DB;

class HomeController extends Controller
{
    public function welcomeView(){
        
        return view('welcome')->with([
            'breadcumb' => [
                [
                    'title' => 'Dashboard',
                    'url'   => '/'
                ]
            ],
            'grup' => 'dashboard'
        ]);
    }
}
