<?php

namespace App\Http\Controllers\Admin\Procedimento;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcedimentoController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $title_page   = 'Processo de Procedimentos';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $users        = User::where('id', '<>', 1)->get();

        return view('admin.qualidade.index', compact(
            'title_page',
            'user_auth',
            'uri', 'users'
        ));
    }
    public function upload(){
        
        // return $this->request;
        $data = $this->__validate($this->request);
        return $this->request->file('file')->store('procedimentos');
        return $data;
    }
    public function __validate($request){
        return $request->validate([
            'file' => 'required|mimes:pdf|max:4096', 
            'setor' => 'required|integer', 
            'users' => 'required|array', 
            'title' => 'required|string', 
            'description' => 'string', 
        ],     
        [
            'file.required' => 'Favor informar uma arquivo PDF!',
            'file.mimes' => 'Arquivo deve ser somente PDF, outro formato não e aceito!'
        ]);
    }
}
