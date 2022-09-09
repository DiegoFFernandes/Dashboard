<?php

namespace App\Http\Controllers\admin\WebHook;

use App\Http\Controllers\Controller;
use App\Models\WebHook;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index(){
        // return $this->request;
        if($this->request->Descricao == 'endereco inexistente'){
            $webhook = new WebHook();
            $webhook->email = $this->request->Email;
            $webhook->descricao = $this->request->Descricao;
            $webhook->save();
        };        
    }
}
