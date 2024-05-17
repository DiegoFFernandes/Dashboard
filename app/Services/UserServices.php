<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\EmpresasGrupoPessoa;
use App\Models\Pessoa;
use App\Models\TipoPessoa;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    public $empresa, $request, $pessoa, $tipopessoa, $user, $grupo;

    public function __construct(
        Empresa $empresa,
        Request $request,
        Pessoa $pessoa,
        TipoPessoa $tipo,
        EmpresasGrupoPessoa $grupo
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->pessoa = $pessoa;
        $this->tipopessoa = $tipo;
        $this->grupo = $grupo;
    }

    public function create($request)
    {
        $data = User::where('email', $request->email)->exists();
        if ($data) {
            // Código 3 e para email existente
            return 3;
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
        $request['phone'] = Helper::RemoveSpecialChar($this->request->phone);

        $user                = $this->_validade($request);
        $user                = User::create($user);
        if ($user->ds_tipopessoa == 'Cliente' || $user->ds_tipopessoa == 'Cliente e fornecedor') {
            $user->assignRole('acesso-cliente');
        }
        //codigo 1 retorna criado com sucesso
        return 1;
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
                'cargo'     => 'required', 'integer:1,2,3,4',
                'cd_pessoa' => 'integer',
                'phone' => 'numeric|min:10',
                'ds_tipopessoa' => 'required|max:60',
                'conexao' => 'max:30',
            ],
            [
                'name.required'    => 'Por favor informe um nome.',
                'email.required'    => 'Por favor informe um email.',
                'password.required' => 'Por favor informe uma senha.',
                'empresa.required'  => 'Por favor informe uma empresa valida.',
                'cargo.required' => 'Por favor informe um cargo',
                'cargo.integer' => 'Cargo informado não e valido!',
                'phone.numeric' => 'Celular deve numerico',
            ]
        );
    }
    public function verifyTipoPessoa($cd_tipo)
    {
        if ($cd_tipo == 1) {
            return "Cliente";
        } elseif ($cd_tipo == 3) {
            return "Cliente e fornecedor";
        } elseif ($cd_tipo == 5) {
            return "Funcionario";
        }
    }
}
