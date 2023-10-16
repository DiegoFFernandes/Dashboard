<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComercialController extends Controller
{
    public $empresa, $request, $user;

    public function __construct(
        Request $request,
        Empresa $empresa
        
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index(){
        $title_page   = 'Indicadores';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        
        return view('admin.diretoria.index', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoComercialNorte(){
        $title_page   = 'Rede Ivorecap - Norte';
        $user_auth    = $this->user;

        $uri         = $this->request->route()->uri();
        
        return view('admin.comercial.comercial-norte', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoComercialSul(){
        $title_page   = 'Rede Ivorecap - Sul';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        
        return view('admin.comercial.comercial-sul', compact('title_page', 'user_auth', 'uri'));
    }

    public function ivoDiretoriaNorte(){
        $title_page   = 'Rede Ivorecap - Norte';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        
        return view('admin.diretoria.diretoria-norte', compact('title_page', 'user_auth', 'uri'));
    }
    public function ivoDiretoriaSul(){
        
        $title_page   = 'Rede Ivorecap - Sul';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        
        return view('admin.diretoria.diretoria-sul', compact('title_page', 'user_auth', 'uri'));
    }
    public function ivoDiretoriaRede()
    {
        $title_page   = 'Rede Ivorecap - Rede';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        
        return view('admin.diretoria.diretoria-rede', compact('title_page', 'user_auth', 'uri'));

    }
}
