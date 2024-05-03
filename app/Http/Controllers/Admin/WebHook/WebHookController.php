<?php

namespace App\Http\Controllers\admin\WebHook;

use App\Http\Controllers\Controller;
use App\Mail\EmailProblemWebHookMail;
use App\Models\WebHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebHookController extends Controller
{
    public $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        return $this->request;
        if (empty($this->request->Descricao)) {
            $descricao = $this->request->Tipo;
        } else {
            $descricao = $this->request->Descricao;
        }

        if ($this->request->Descricao == 'endereco inexistente' || $this->request->Descricao == 'caixa lotada' || $this->request->Tipo == "falha") {
            WebHook::updateOrInsert(
                ['email' => $this->request->Email],
                [
                    'email' => $this->request->Email,
                    'tipo' => $this->request->Tipo,
                    'descricao' => $descricao,
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
            );
            // Mail::send(new EmailProblemWebHookMail($this->request));
            return;
        }
        if ($this->request->Tipo == 'leitura' || $this->request->Tipo == 'entregue') {
            WebHook::insert([
                    'email' => $this->request->Email,
                    'tipo' => $this->request->Tipo,
                    'descricao' => $descricao,
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
            );
            // Mail::send(new EmailProblemWebHookMail($this->request));
            return;
        }
    }
}
