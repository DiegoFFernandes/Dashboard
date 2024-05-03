<?php

namespace App\Http\Controllers\Admin\Digisac;

use App\Http\Controllers\Controller;
use Digisac;
use Illuminate\Http\Request;

class DigiSacController extends Controller
{
    public function __construct()
    {
        
    }
    public function index(){
        $oauth = Digisac::OAuthToken();
        return Digisac::SendMessage($oauth);
    }

    
}
