<?php

namespace App\Http\Controllers\Admin\Trucks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use solutekWpp;

class TruckController extends Controller
{
    

    public function __construct()
    {
        

    }
    public function MsgTrucksWpp(){
        
        $phones = ['41985227055'];
        $mensagem = 'teste';

        return solutekWpp::SendSolutekWpp($phones, $mensagem);
    }
}
