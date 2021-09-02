<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Empresa;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmailController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        Email $email,
        Pessoa $pessoa
    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->email = $email;
        $this->pessoa = $pessoa;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Lista de Emails';
        $user_auth    = $this->user;
        $uri          = $this->resposta->route()->uri();
        $empresas     = $this->empresa->empresa();
        $emails       = $this->email->EmailAll();

        return view(
            'admin.email.index',
            compact('user_auth', 'uri', 'title_page', 'empresas', 'emails')
        );
    }

    public function getemail()
    {
        $data = $this->email->EmailAll();
        return DataTables::of($data)
            ->addColumn('Actions', function ($data) {
                return '
            <a href="#" class="btn btn-success btn-sm btn-edit">Editar</a>
            <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Excluir</button>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['cd_usuario'] = $this->user->id;
        $request['email'] = strtolower($request['email']);



        $validator = $this->_validator($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($this->email->VerifyIfExists([$request['email']])) {
            return response()->json(['alert' => 'Email já existe favor verificar!']);
        }

        $this->email->storeData($request->all());

        return response()->json(['success' => 'Email adicionado com sucesso!']);
    }

    public function update(Request $request, $id)
    {
        $request['cd_usuario'] = $this->user->id;
        $request['email'] = strtolower($request['email']);

        $validator = $this->_validator($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($this->email->VerifyIfExists([$request['email']])) {
            return response()->json(['alert' => 'Email já existe favor verificar!']);
        }

        $this->email->updateData($id, $request->all());
        return response()->json(['success' => 'Email atualizada com sucesso!']);
    }

    public function destroy($id)
    {

        if ($this->pessoa->findEmail($id)) {
            return response()->json(['alert' => 'Essa email está associada a uma pessoa, não é possivel excluir!']);
        }

        $this->email->destroyData($id);
        return response()->json(['success' => 'Excluido com sucesso!']);
    }


    public function _validator(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'email' => 'email:rfc|required',
            ],
            [
                'email.required' => 'Email deve ser preenchido',
            ]
        );
    }
}
