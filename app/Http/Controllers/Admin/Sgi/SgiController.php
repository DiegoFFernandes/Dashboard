<?php

namespace App\Http\Controllers\Admin\Sgi;

use App\Http\Controllers\Controller;
use App\Models\Procedimento;
use App\Models\ProcedimentoAprovador;
use App\Models\ProcedimentoPublish;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SgiController extends Controller
{
    public $request, $procedimento, $aprovador, $publish, $user, $setor, $recusa;
    public function __construct(
        Request $request,
        Procedimento $procedimento,
        ProcedimentoAprovador $aprovador,
        ProcedimentoPublish $publish,
        Setor $setor
    ) {
        $this->request = $request;
        $this->procedimento = $procedimento;
        $this->aprovador = $aprovador;
        $this->publish = $publish;
        $this->setor = $setor;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Sgi - Documentos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $users        = User::where('id', '<>', 1)->get();
        $setors       = $this->setor->listData();

        return view('admin.sgi.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'users',
            'setors'
        ));
    }
}
