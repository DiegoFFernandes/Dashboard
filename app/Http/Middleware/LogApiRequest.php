<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se a requisição tem arquivos anexados
        if ($request->isJson() || $request->wantsJson()) {
            // Formata os dados da requisição para log
            $logData = [
                'method' => $request->getMethod(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ];

            // Loga os dados da requisição no arquivo de log
            Log::channel('api_requests')->info('API Request', $logData);
        }

        return $next($request);
    }
}
