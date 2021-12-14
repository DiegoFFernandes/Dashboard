<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

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
        $this->user = $user;   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()    {
        $request = $this->request;
        $user = $this->user;        
        $this->subject("Pedido de Cancelamento");
        $this->to(env('EMAIL_TO'), "Controladoria")->cc(env('EMAIL_CC'));
        //$this->to("rafael.duarte@ivorecap.com.br", "Rafael Duarte");
        return $this->markdown("admin.comercial.envio-email", compact('request','user'));
    }
}
