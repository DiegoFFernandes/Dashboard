<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\ExecutorEtapa;
use Illuminate\Http\Request;

class ExecutorController extends Controller
{
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function searchExecutor()
    {
        try {
            $executor = ExecutorEtapa::where('matricula', $this->request->cd_executor)->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Executor não encontrado!']);
        }
        return response()->json(['success' => $executor->nmexecutor]);
    }
}
