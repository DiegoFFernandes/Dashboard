<?php

namespace App\Http\Controllers\Admin\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\LoteEntradaEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemLoteEntradaEstoqueController extends Controller
{
    public function __construct(
        Request $request,        
        LoteEntradaEstoque $lote,
        Item $item,
    ) {
        
        $this->request = $request;
        $this->lote = $lote;
        $this->item = $item;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index($id)
    {
        $title_page   = 'Adicionar item Lote de Entrada';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();

        $lote = $this->lote->findLote($id);

        return view('admin.estoque.add-item-lote', compact(
            'title_page',
            'user_auth',
            'uri',
            'lote'
        ));
    }
    public function getBuscaItem($cd_barras){
        $item = $this->item->ItemFind($cd_barras);
        return response()->json($item);
    }
}
