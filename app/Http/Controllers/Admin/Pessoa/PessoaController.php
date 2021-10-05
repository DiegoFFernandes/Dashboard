<?php

namespace App\Http\Controllers\Admin\Pessoa;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Empresa;
use App\Models\MotoristaVeiculo;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PessoaController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        Pessoa $pessoa,
        MotoristaVeiculo $motorista,
        Email $email,
    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->pessoas = $pessoa;
        $this->email = $email;
        $this->motorista = $motorista;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Lista de Pessoas';
        $user_auth    = $this->user;
        $uri         = $this->resposta->route()->uri();
        $empresas     = $this->empresa->empresa();
        $emails       = $this->email->EmailAll();

        return view(
            'admin.pessoa.index',
            compact('user_auth', 'uri', 'title_page', 'empresas', 'emails')
        );
    }

    public function getpessoa()
    {
        $data = $this->pessoas->PessoasAll();
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '
            <a href="#" class="btn btn-success btn-sm btn-edit">Visualizar</a>
            <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request['cd_usuario'] = $this->user->id;
        $request['name'] = ucwords(strtolower($request['name']));
        $request['cpf'] = $this->limpaCaracters($request['cpf']);
        $request['phone'] = $this->limpaCaracters($request['phone']);
        $request['cd_email'] = intval($request['cd_email']);
        $validator = $this->_validator($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($this->pessoas->verifyIfExists([$request['cpf']])) {
            return response()->json(['alert' => 'CPF já existe favor verificar!']);
        }

        $this->pessoas->storeData($request->all());

        return response()->json(['success' => 'Pessoa adicionada com sucesso!']);
    }

    public function update(Request $request, $id)
    {
        $request['cd_usuario'] = $this->user->id;
        $request['name'] = ucwords(strtolower($request['name']));
        $request['cpf'] = $this->limpaCaracters($request['cpf']);
        $request['phone'] = $this->limpaCaracters($request['phone']);
        $validator = $this->_validator($request);

       if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $this->pessoas->updateData($id, $request->all());
        return response()->json(['success' => 'Pessoa atualizada com sucesso!']);
    }

    public function destroy($id){

        if($this->motorista->findPessoa($id)){
            return response()->json(['alert' => 'Essa pessoa está associada a um motorista, não é possivel excluir!']);
        }
        
        $this->pessoas->destroyData($id);
        return response()->json(['success' => 'Excluido com sucesso!']);
    }

    public function limpaCaracters($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
        return $valor;
    }

    public function _validator(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'cd_empresa'  => 'integer|required',
                'name' => 'string|required',
                'cpf'  => 'required',
                'cd_email'  => 'required|gt:0',
                'phone' => 'required'
            ],
            [
                'cd_empresa.required' => 'Empresa deve ser preenchida',
                'cd_empresa.integer'   => 'Id empresa deve ser inteiro',
                'name.required' => 'Modelo deve ser preenchida',
                'name.string'  => 'Nome deve ser somente alfabetico',
                'cpf.required'  => 'CPF deve ser preenchida',
                'cd_email.required'  => 'E-mail deve ser preenchido',
                'cd_email.gt'  => 'E-mail deve ser preenchido',
                'phone' => 'Telefone deve ser preenchido!'

            ]
        );
    }
}
