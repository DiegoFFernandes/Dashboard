<?php

namespace App\Http\Controllers\admin\PessoaUser;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpresasGrupoPessoa;
use App\Models\Pessoa;
use App\Models\TipoPessoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PessoaUSerController extends Controller
{
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
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function connectPeopleUser()
    {
        $title = "Associar Usúarios / Pessoas";
        $user_auth = $this->user;
        $uri      = $this->request->route()->uri();
        $users     = User::where('id', '<>', 1)->get();

        return view('admin.usuarios.connect-people-user', compact(
            'user_auth',
            'uri',
            'users',
            'title'
        ));
    }
    public function createPeopleUser()
    {
        $dados = $this->_validate($this->request);
        $connect['id_user'] = $dados['id_user'];
        $connect['nr_cnpjcpf'] = $dados['cpfcnpj'];

        if ($this->grupo->verifyIfExists($connect['nr_cnpjcpf'])) {
            return redirect()->route('connect-people-user')->with('warning', 'Esse CNPJ já está associada a uma pessoa!');
        }
        EmpresasGrupoPessoa::create($connect);

        return redirect()->route('connect-people-user')->with('status', 'Associado com sucesso!');
    }
    public function getPeopleUserAll()
    {
        $data = $this->grupo->PessoaUserAll();
        return DataTables($data)
            ->addColumn('actions', function ($d) {
                return '<a href="' . route('edit-connect-people-user', $d->id) . '" class="btn btn-warning btn-sm">Editar</a>
            <a href="' . route('delete-connect-people-user', $d->id) . '" class="btn btn-danger btn-sm">Deletar</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function edit($id)
    {
        $title = "Editar associação Usúarios / Pessoas";
        $user_auth = $this->user;
        $uri      = $this->request->route()->uri();
        $users     = User::where('id', '<>', 1)->get();
        $user_id  = EmpresasGrupoPessoa::findOrFail($this->request->id);

        return view('admin.usuarios.connect-people-user', compact(
            'user_auth',
            'uri',
            'users',
            'title',
            'user_id'
        ));
    }
    public function update()
    {
        $dados = $this->_validate($this->request);
        $empresa = EmpresasGrupoPessoa::findOrFail($this->request->id);
        $empresa['id_user'] = $dados['id_user'];

        if ($dados['cpfcnpj'] == $empresa->nr_cnpjcpf) {
            $empresa['nr_cnpjcpf'] = $dados['cpfcnpj'];
        } else {
            if ($this->grupo->verifyIfExists($dados['cpfcnpj'])) {
                return redirect()->route('connect-people-user')->with('warning', 'Esse CNPJ já está associada a uma pessoa!');
            } else {
                $empresa['nr_cnpjcpf'] = $dados['cpfcnpj'];
            }
        }

        $empresa['id_user'] = $dados['id_user'];
        $empresa['nr_cnpjcpf'] = $dados['cpfcnpj'];
        $empresa->save();

        return redirect()->route('connect-people-user')->with('status', 'Atualizado com sucesso!');
    }
    public function _validate($request)
    {
        return $request->validate([
            'id_user' => 'required|integer',
            'cpfcnpj' => 'required|string'
        ]);
    }
    public function delete($id)
    {
        try {
            $user = EmpresasGrupoPessoa::findOrFail($id);
            $user->delete();
            return redirect()->route('connect-people-user')->with('status', 'Deletado com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->route('connect-people-user')->with('warning', 'Não pode ser excluido, está associados a outras tabelas do banco de dados!');
        }
    }
}
