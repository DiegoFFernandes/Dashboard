<?php

namespace App\Http\Controllers\Admin\Contabilidade;

use App\Http\Controllers\Controller;
use App\Models\ParmContabil;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametroContabilController extends Controller
{
    public $user, $request, $contabil;

    public function __construct(
        Request $request,
        ParmContabil $contabil
    ) {

        $this->request = $request;
        $this->contabil = $contabil;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Parâmetros Contabeis - Automatico';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $parm_contabil = ParmContabil::select('dt_fechamento', 'status')->first();

        $list = $this->contabil->listEmpresaParmContabil();

        return view('admin.contabilidade.index', compact(
            'title_page',
            'user_auth',
            'uri',
            'list',
            'parm_contabil'
        ));
    }

    public function storeDate()
    {       

        $date = DateTime::createFromFormat('d/m/Y', $this->request->date);

        $this->request['date'] = $date->format('Y-m-d');        

        $this->contabil->store($this->request);


        $parm_contabil = ParmContabil::select('dt_fechamento', 'status')->first();
        
        if($parm_contabil['status'] == 'S'){
            $this->contabil->dt_fechamento($parm_contabil['dt_fechamento']);
        }

        return response()->json(['success' => 'Data de Fechamento incluido/alterado com sucesso!']);
    }

    public function status(){
        $parm_contabil = ParmContabil::select('dt_fechamento', 'status')->first();
        return $parm_contabil;
    }
}
