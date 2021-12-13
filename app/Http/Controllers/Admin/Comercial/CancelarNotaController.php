<?php

namespace App\Http\Controllers\Admin\Comercial;

use App\Http\Controllers\Controller;
//use App\Jobs\CancelaNotaMail;
use App\Mail\CancelaNotaMail;
use App\Models\CancelarNota;
use App\Models\Empresa;
use App\Models\Motivo;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CancelarNotaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        Motivo $motivo,
        Pessoa $pessoa,
        CancelarNota $nota,
        
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->motivo = $motivo;
        $this->pessoa = $pessoa;
        $this->nota = $nota;
        

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function cancelarNota(){     
           
        $title_page   = 'Cancelar Nota';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();        
        $motivo = $this->motivo->motivoAll();
        $listNotasCancelar = $this->nota->list($this->user->id);
        
        return view('admin.comercial.cancelar-nota', compact('title_page', 'user_auth', 'uri', 'motivo', 'listNotasCancelar'));
    }

    public function getCancelarNota(){        
        $this->request['cd_requerente'] = Auth::user()->id;   
        $this->_validate($this->request);       
        $store = $this->nota->store($this->request);  
        Mail::send(new CancelaNotaMail($this->request, Auth::user()));
              
        if($store == 1){
            return response()->json(['success' => 'Pedido de cancelamento realizado com sucesso!']);
        }else{
            return response()->json(['error' => 'Houve algum erro! Já pode ter um pedido de cancelamento para essa nota!']);
        }
    }    

    public function searchCliente(){
        $data = [];

        if ($this->request->has('q')) {
            $search = $this->request->q;
            $data = $this->pessoa->FindPessoaJunsoftAll($search);
        }
        return response()->json($data);
    }

    public function SearchNota(){        
        //$data = [];
        if ($this->request) {                      
            $data =  $this->nota->SearchNota($this->request->nr_nota, $this->request->cd_empresa);
        }
        return response()->json($data);
    }

    public function _validate(){
        return Validator::make(
            $this->request->all(),
            ['cd_empresa'  => 'required'],
            ['nr_nota'  => 'required|integer'],
            ['observacao'  => 'string|size:512'],
        );
    }

    public function envioEmail(){
        $request = new stdClass();
        $request->cd_empresa = 3;
        $request->name = "Diego Ferreira";
        $request->nr_lancamento = 28;
        $request->nr_nota = 123;
        $request->nr_cnpjcpf = '00.520.634/0003-37';
        $request->nm_pessoa = 'TRANSPORTES SELGEMAY LTDA';
        $request->motivo = 'Faturamento parcial';
        $request->observacao = 'Teste de observação';

        //return new CancelaNotaMail($request);
        //return Mail::send(new CancelaNotaMail($request));
    }
}
