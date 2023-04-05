<?php

namespace App\Http\Controllers\Admin\Produto;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public $request, $item;

    public function __construct(Item $item, Request $request)
    {
        $this->item = $item;
        $this->request = $request;
    }

    public function searchProduto()
    {
        // Helper::searchCliente($this->user->conexao)
        $data = [];
        
        if ($this->request->has('q')) {
            $search = $this->request->q;
            $data = $this->item->FindProdutoAll($search);
        }
        return response()->json($data);
    }
}
