<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\ExecutorEtapa;
use Helper;
use Illuminate\Http\Request;

class ExecutorController extends Controller
{
    public $request;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

   public function searchExecutor()
    {
        try {
            $ip = Helper::Get_Client_Ip();
            if($ip == '177.92.31.150' || $ip == '127.0.0.1'){
                $local = 'SUL';
            }else{
                $local = 'NORTE';
            }
            $executor = ExecutorEtapa::
                        where('matricula', $this->request->cd_executor)
                        // ->where('localizacao', $local)
                        ->firstOrFail();

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Executor não encontrado!']);
        }
        return response()->json(['success' => $executor->nmexecutor]);
    }
}
