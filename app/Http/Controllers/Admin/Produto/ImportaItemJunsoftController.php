<?php

namespace App\Http\Controllers\Admin\Produto;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Item;
use App\Models\MarcaPneu;
use App\Models\ModeloPneu;
use App\Models\MotivoPneu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class ImportaItemJunsoftController extends Controller
{
    public $empresa, $request, $item, $marca, $user, $motivo;
    
    public function __construct(
        Request $request,
        Empresa $empresa,
        MarcaPneu $marca,
        Item $item, 
        MotivoPneu $motivo,
    ) {
        $this->empresa  = $empresa;
        $this->request = $request;
        $this->item = $item;
        $this->marca = $marca;
        $this->motivo = $motivo;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {   
        // return $import = $this->item->ImportaItemJunsoft(30);
        $title_page   = 'Importa do Junsoft';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $marcas        = $this->marca->MarcaAll();      
        
        return view('admin.importa_junsoft.index', compact(
            'title_page', 'user_auth', 'uri', 'marcas'));
    }

    public function AjaxImportaItem(){
        $cd_marca = $this->request->cd_marca;             
        $import = $this->item->ImportaItemJunsoft($cd_marca);
        
        if($import == 1){
            return response()->json(['success' => "Importação de produto realizada com sucesso!"]);
        }
        return response()->json(['error' => $import]);
    }

    public function AjaxImportaMotivoPneu(){
                   
        $import = $this->motivo->ImportaMotivoPneuJunsoft();        
        if($import == 1){
            return response()->json(['success' => "Importação de Motivos realizada com sucesso!"]);
        }
        return response()->json(['error' => $import]);
    }
}
