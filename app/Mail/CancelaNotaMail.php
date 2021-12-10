<?php

namespace App\Mail;

use App\Models\CancelarNota;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use stdClass;

class CancelaNotaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, User $user)
    {
        $this->request = $request;
        $this->user = Auth::user();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $request = $this->request;
        $user = $this->user;
        $this->subject("Pedido de Cancelamento");
        $this->to("rafael.duarte@ivorecap.com.br", "Rafael Duarte")->cc("ti.campina@ivorecap.com.br");
        return $this->markdown("admin.comercial.envio-email", compact('request'));
    }
}
