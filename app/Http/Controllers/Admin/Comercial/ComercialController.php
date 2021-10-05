<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComercialController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa
        
    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(){
        $title_page   = 'Comercial';
        $user_auth    = $this->user;

        $uri         = $this->resposta->route()->uri();
        $src =  'https://is.gd/IVOBI_COMCGS';

        return view('admin.comercial.index', compact('title_page', 'user_auth', 'uri'));
    }
}
