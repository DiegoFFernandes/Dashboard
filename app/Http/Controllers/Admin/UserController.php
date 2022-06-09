<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(Empresa $empresa, Request $request)
    {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $uri       = $this->resposta->route()->uri();
        $user_auth = $this->user;
        $users     = User::where('id', '<>', 1)->get();
        // $empresas  = $this->empresa->empresa();
        $empresas  = $this->empresa->EmpresaAll();

        return view('admin.usuarios.usuarios', compact('users', 'user_auth', 'empresas', 'uri'));
    }
    public function create(Request $request)
    {        
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
        $user                = $this->_validade($request);         
        $user                = User::create($user);
        
        return redirect()->route('admin.usuarios.listar')->with('status', 'Usuário criado com sucesso!');
    }

    public function edit(Request $request)
    {
        $uri      = $this->resposta->route()->uri();
        $users    = User::all();
        $user_id  = User::findOrFail($request->id);
        $empresas = $this->empresa->EmpresaAll();

        return view('admin.usuarios.usuarios', compact('user_id', 'users', 'empresas', 'uri'));
    }
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        $data             = $this->_validade($request);
        $data['name']     = $request->name;
        $data['email']    = $request->email;
        $data['empresa']  = $request->empresa;

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
        $uri       = $this->resposta->route()->uri();
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
}
