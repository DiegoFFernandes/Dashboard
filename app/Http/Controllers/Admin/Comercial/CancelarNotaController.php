<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Motivo;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancelarNotaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        Motivo $motivo,
        Pessoa $pessoa
        
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->motivo = $motivo;
        $this->pessoa = $pessoa;
        

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function cancelarNota(){
        $title_page   = 'Cancelar Nota';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        //return $this->pessoa->pessoaJunsoftAll();
        $motivo = $this->motivo->motivoAll();
        
        return view('admin.comercial.cancelar-nota', compact('title_page', 'user_auth', 'uri', 'motivo'));
    }

    public function getCancelarNota(){
        return $this->request;
    }

    public function searchCliente(){
        $data = [];

        if ($this->request->has('q')) {
            $search = $this->request->q;
            $data = $this->pessoa->FindPessoaJunsoftAll($search);
        }
        return response()->json($data);
    }
}
