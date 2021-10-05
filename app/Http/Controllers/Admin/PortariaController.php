<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\LinhaMotorista;
use App\Models\MotoristaVeiculo;
use App\Models\MovimentoVeiculo;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PortariaController extends Controller
{
    public function __construct(
        Request $request,
        MotoristaVeiculo $motorista,
        Empresa $empresa,
        Pessoa $pessoa,
        LinhaMotorista $linha,
        MovimentoVeiculo $movimento
    ) {
        $this->resposta = $request;
        $this->motorista = $motorista;
        $this->empresa = $empresa;
        $this->pessoa = $pessoa;
        $this->linha = $linha;
        $this->movimento = $movimento;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $user_auth  = $this->user;
        $uri        = $this->resposta->route()->uri();

        if ($uri == 'portaria/movimento/entrada') {
            $title_page = 'Entrada Veiculo';
        } else {
            $title_page = 'Saida Veiculo';
        }

        return view('admin.portaria.index', compact('user_auth', 'uri', 'title_page'));
    }

    public function list(Request $request)
    {
        $uri        = $this->resposta->route()->uri();
        $user_auth  = $this->user;
        $validator  = $this->_validator($request);

        if ($validator->fails()) {
            return view('admin.portaria.index', compact('uri', 'user_auth'));
        }

        $motorista  = $this->motorista->listPlaca($request->placa);
        $empresas   = $this->empresa->empresa();
        $pessoas    = $this->pessoa->PessoasAll();
        $linhas     = $this->linha->linhaAll();


        if ($uri == 'portaria/movimento/entrada') {
            $title_page = 'Entrada Veiculo';
        } else {
            $title_page = 'Saida Veiculo';
        }

        return view('admin.portaria.index', compact('uri', 'user_auth', 'motorista', 'empresas', 'title_page', 'pessoas', 'linhas'));
    }

    public function editar(Request $request)
    {
    }

    public function autocomplete(Request $request)
    {
        $data = $this->motorista->findPlaca($request['placa']);

        $output = '<ul class="dropdown-menu" style="display:block; width:100%">';
        foreach ($data as $row) {
            $output .= '<li><a href="#">' . $row->placa . '</a></li>';
        }
        $output .= '</ul>';
        return $output;
    }

    public function search(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = $this->motorista->findPlaca($search);
        }
        return response()->json($data);
    }

    public function saveEntrada(Request $request)
    {
        $this->_validatorMovimento($request);

        $store = $this->movimento->storeDataEntrada($request);

        if ($store) {
            return redirect()->route('admin.portaria.entrada')->with('status', 'Entrada feita com sucesso!');
        } else {
            return redirect()->route('admin.portaria.saida')->with('warning', 'Existe saida, deve ser finalizada!');
        }
    }

    public function saveSaida(Request $request)
    {
        $this->_validatorMovimento($request);
        $update = $this->movimento->storeDataSaida($request);

        if ($update) {
            return  redirect()->route('admin.portaria.saida')->with('status', 'Saida feita com sucesso');
        } else {
            return redirect()->route('admin.portaria.entrada')->with('warning', 'Não existe entrada!');
        }
    }

    public function _validator(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'placa'  => 'required'

            ],
            [
                'placa.required' => 'Placa deve ser preenchida'

            ]
        );
    }

    public function _validatorMovimento(Request $request)
    {
        return $request->validate(
            [
                'cd_empresa' => 'integer|required|not_in:0',
                'cd_motorista_veiculos' => 'integer|required|not_in:0',
                'cd_pessoa' => 'integer|required|not_in:0',
                'cd_linha' => 'integer|required|not_in:0',
            ],
            [
                'cd_empresa.integer' => 'Código da empresa deve ser númerico',
                'cd_empresa.required' => 'Código da emrpesa deve existir',
                'cd_empresa.not_in' => 'Código da empresa não pode ser zero'
            ]
        );
    }
}
