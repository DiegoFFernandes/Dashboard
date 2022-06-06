<?php

namespace App\Http\Controllers\Admin\Producao;

use App\Http\Controllers\Controller;
use App\Models\GQCBrigestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class GqcBridgestoneController extends Controller
{
    public function __construct(
        Request $request,
        GQCBrigestone $gqc
    ) {

        $this->request = $request;
        $this->gqc = $gqc;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function pneusFaturadosMarcas()
    {
        $title_page   = 'Pneus faturados por Marca';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        $inicio_data = Config::get('constants.options.dti');        
        $fim_data = Config::get('constants.options.today');        
        return view('admin.producao.pneus-faturados-marcas', compact('title_page', 'user_auth', 'uri', 'inicio_data', 'fim_data'));
    }
    public function getPneusFaturadosMarcas()
    {
        $inicio_data = Config::get('constants.options.dti');
        $fim_data = Config::get('constants.options.today');
        $data =  $this->gqc->pneusFaturadosMarca($inicio_data, $fim_data, $this->user->empresa);
        return DataTables::of($data)->make(true);
    }
    public function getBuscarPneusMarcas()
    {           
        $data =  $this->gqc->pneusFaturadosMarca($this->request['inicio_data'], $this->request['fim_data'], $this->user->empresa);
        $html = '<table id="table-gqc" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>                    
                            <th>Descrição</th>
                            <th>Sigla</th>
                            <th>Medida</th>
                            <th>Qtd</th>                           
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as $d) {
            $html .= '
                        <tr>                    
                            <td>' . $d->DSMARCA . '</td>
                            <td>' . $d->SIGLA . '</td>
                            <td>' . $d->DSMEDIDAPNEU . '</td>
                            <td>' . $d->QTD . '</td>                    
                        </tr>';
        }
        $html .= '</tbody>';
        return $html;
    }
}
