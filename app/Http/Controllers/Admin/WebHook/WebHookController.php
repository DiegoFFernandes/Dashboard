<?php

namespace App\Http\Controllers\admin\WebHook;

use App\Http\Controllers\Controller;
use App\Mail\EmailProblemWebHookMail;
use App\Models\WebHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebHookController extends Controller
{
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        // return $this->request;
        if ($this->request->Descricao == 'endereco inexistente' || $this->request->Descricao == 'caixa lotada') {
            WebHook::updateOrInsert(
                ['email' => $this->request->Email],
                [
                    'email' => $this->request->Email,
                    'descricao' => $this->request->Descricao,
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
            );            
            // Mail::send(new EmailProblemWebHookMail($this->request));
            return;
        }
    }
}
