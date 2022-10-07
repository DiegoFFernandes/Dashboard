<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Pessoa;
use App\Models\TipoPessoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        Empresa $empresa,
        Request $request,
        Pessoa $pessoa,
        TipoPessoa $tipo,
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->pessoa = $pessoa;
        $this->tipopessoa = $tipo;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $uri       = $this->request->route()->uri();
        $user_auth = $this->user;
        $users     = User::where('id', '<>', 1)->get();
        // $empresas  = $this->empresa->empresa();
        $empresas  = $this->empresa->EmpresaAll();
        $tipopessoa = $this->tipopessoa->tipoPessoaAll();

        return view('admin.usuarios.usuarios', compact(
            'users',
            'user_auth',
            'empresas',
            'uri', 
            'tipopessoa'
        ));
    }
    public function create(Request $request)
    {
        // return $this->request;       
        $data = User::where('email', $request->email)->exists();
        if ($data) {
            return redirect()->route('admin.usuarios.listar')->with('warning', 'Email já existe, favor cadastrar outro!');
        }
        $empresas  = $this->empresa->EmpresaAll();
        // $empresas  = $this->empresa->empresa();

        foreach ($empresas as $empresa) {
            if ($empresa->CD_EMPRESA == $request->empresa) {
                $request['conexao'] = $empresa->CONEXAO;
            }
        }
        $request['password'] = Hash::make($request['password']);
        $request['ds_tipopessoa'] = $this->verifyTipoPessoa($this->request->ds_tipopessoa);        
        $request['email'] = strtolower($this->request->email);
        $request['name'] = mb_convert_case($this->request->name, MB_CASE_TITLE, 'UTF-8');
        $user                = $this->_validade($request);
        $user                = User::create($user);
        if ($user->ds_tipopessoa == 'Cliente' || $user->ds_tipopessoa == 'Cliente e fornecedor') {
            $user->assignRole('acesso-cliente');
        }

        return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário criado com sucesso!');
    }

    public function edit(Request $request)
    {
        $user_auth = $this->user;
        $uri      = $this->request->route()->uri();
        $users    = User::all();
        $user_id  = User::findOrFail($request->id);
        $empresas = $this->empresa->EmpresaAll();
        $tipopessoa = $this->tipopessoa->tipoPessoaAll();

        return view('admin.usuarios.usuarios', compact(
            'user_id',
            'users',
            'empresas',
            'uri',
            'user_auth',
            'tipopessoa'
        ));
    }
    public function update(Request $request)
    {
        // return $this->request;
        $user = User::findOrFail($request->id);
        $request['name']     = mb_convert_case($this->request->name, MB_CASE_TITLE, 'UTF-8');
        $request['ds_tipopessoa'] = $this->verifyTipoPessoa($this->request->ds_tipopessoa);
        $request['email']    = $request->email;
        $request['empresa']  = $request->empresa;
        $data             = $this->_validade($request);

        if ($user->password == $request->password) {
            $data['password'] = $request->password;
        } else {
            $data['password'] = Hash::make($request->password);
        }

        $empresas  = $this->empresa->EmpresaAll();
        foreach ($empresas as $empresa) {
            if ($empresa->CD_EMPRESA == $request->empresa) {
                $data['conexao'] = $empresa->CONEXAO;
            }
        }

        $user->fill($data);
        $status = $user->save();
        if ($status == 1) {
            return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário atualizado com sucesso!');
        } else {
            return redirect()->route('admin.usuarios.listar')->with('warning', 'Ouve algum erro!');
        }
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário deletado com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->route('admin.usuarios.listar')->with('warning', 'Usúario não pode ser excluido, está associados a outras tabelas do banco de dados!');
        }

        return $user;
        return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário deletado com sucesso!');
    }
    public function _validade(Request $request)
    {
        return $request->validate(
            [
                'id'       => 'integer',
                'name'     => 'required|max:255',
                'email'    => 'required|email',
                'password' => 'required', 'alpha_num',
                'empresa'  => 'required', 'integer:1,2,3,21,22',
                'cd_pessoa' => 'integer',
                'ds_tipopessoa' => 'required|max:60',
                'conexao' => 'max:30',
            ],
            [
                'name.required'    => 'Por favor informe um nome.',
                'email.required'    => 'Por favor informe um email.',
                'password.required' => 'Por favor informe uma senha.',
                'empresa.required'  => 'Por favor informe uma empresa valida.',
            ]
        );
    }
    public function profileUser()
    {
        $uri       = $this->request->route()->uri();
        $user_auth = $this->user;
        return view('admin.usuarios.user-profile', compact('uri', 'user_auth'));
    }
    public function updateProfileUser(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        User::find($this->user->id)->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => 'Perfil atualizado com sucesso! Você será redirecionado a home para entrar novamente.']);
    }
    public function searchPessoa()
    {
        // Helper::searchCliente($this->user->conexao)
        $data = [];

        if ($this->request->has('q')) {
            $search = $this->request->q;
            $data = $this->pessoa->FindPessoaJunsoftAll($search);
        }
        return response()->json($data);
    }
    public function verifyTipoPessoa($cd_tipo){
        if($cd_tipo == 1){
            return "Cliente";
        }elseif($cd_tipo == 3){
            return "Cliente e fornecedor";
        }elseif($cd_tipo == 5){
            return "Funcionario";
        }
    }
}
