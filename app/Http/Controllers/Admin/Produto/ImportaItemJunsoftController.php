<?php

namespace App\Http\Controllers\Admin\Produto;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Item;
use App\Models\MarcaPneu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportaItemJunsoftController extends Controller
{
    public function __construct(
        Request $request,
        Empresa $empresa,
        MarcaPneu $marca,
        Item $item
    ) {
        $this->empresa  = $empresa;
        $this->resposta = $request;
        $this->item = $item;
        $this->marca = $marca;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {   
        // return $import = $this->item->ImportaItemJunsoft(30);
        $title_page   = 'Importa/Atualiza produtos cadastrados Junsoft(TPO001)';
        $user_auth    = $this->user;
        $uri          = $this->resposta->route()->uri();
        $marcas        = $this->marca->MarcaAll();        
        return view('admin.importa_junsoft.index', compact(
            'title_page', 'user_auth', 'uri', 'marcas'));
    }
    public function AjaxImportaItem(){
        $cd_marca = $this->resposta->cd_marca;             
        $import = $this->item->ImportaItemJunsoft($cd_marca);
        
        if($import == 1){
            return response()->json(['msg' => "Importação de produto realizada com sucesso!"]);
        }
        return response()->json(['msg' => "Houve algum erro!"]);
    }
}
