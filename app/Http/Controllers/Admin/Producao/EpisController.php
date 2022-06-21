<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\ExecutorEtapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa, 
        ExecutorEtapa $executor
    ) {
        $this->request = $request;
        $this->empresa = $empresa;
        $this->executor = $executor;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        // $executor = $this->executor->searchExecutorEtapaJunsoft();
        // $this->executor->StoreExecutorEtapa($executor);
    }
}
