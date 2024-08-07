<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(function () {
                    require base_path('routes/api.php');
                });

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(function () {
                    require base_path('routes/web.php');
                    require base_path('routes/financeiro.php');
                    require base_path('routes/epi.php');
                    require base_path('routes/procedimentos.php');
                    require base_path('routes/sgi.php');
                    require base_path('routes/clientes.php');
                    require base_path('routes/manutencao.php');
                    require base_path('routes/analise_frota.php');
                    require base_path('routes/comercial.php');
                    require base_path('routes/digisac.php');
                    require base_path('routes/junsoft.php');
                    require base_path('routes/indicadores.php');
                    require base_path('routes/cobranca.php');
                });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
