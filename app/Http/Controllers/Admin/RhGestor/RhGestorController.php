<?php

namespace App\Http\Controllers\Admin\RhGestor;

use App\Http\Controllers\Controller;
use App\Models\RhGestor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RhGestorController extends Controller

{
    public $request, $rh;
    public function __construct(Request $request, RhGestor $rh)
    {
        $this->request = $request;
        $this->rh = $rh;
    }
    public function list()
    {
    }
    public function storeEmployee()
    {
    }
    public function storeArea()
    {
    }
    public function IndicadorFinanceiroAgrupado()
    {
        $data = $this->request->json()->all();

        $errors = [];

        foreach ($data as $index => $item) {
            //Faz a divisão da lotação pegando somente o codigo do Area ex: 11011 - Administração - CGS
            $cd_area_lotacao = array_map('trim', explode('-', $item['DsLotacaoArea']));
            // Troca a informação no Array, somente para 11011
            $data[$index]['DsLotacaoArea'] = $cd_area_lotacao[0];

            $validator = $this->__validate($item);
            if ($validator->fails()) {
                $errors[$index] = [
                    'corrigir' => $validator->errors(),
                    'competencia' => $item['Competencia'],
                    'ds_lotacao_area' => $item['DsLotacaoArea'],
                    'cd_indicador' => $item['CodIndicador']
                ];
            }
        }
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        foreach ($data as $index => $item) {
            try {
                $this->rh->store($item);
            } catch (\Throwable $th) {
                $errors[$index] = $th->getMessage();
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        return response()->json('Dados Armazenados com sucesso', 200);
    }

    public function __validate($item)
    {
        return Validator::make(
            $item,
            [
                'Competencia'  => 'string|required',
                'CodIndicador'  => 'integer|required',
                'DsLotacaoArea' => 'string|required',
                'Valor'  => 'numeric|required'
            ],
            [
                'Competencia.required'  => 'Competencia deve ser preenchida',
                'CodIndicador.required' => 'Codigo indicador deve ser informado',
                'DsLotacaoArea.required'   => 'Codigo da lotação deve ser informado',
                'DsLotacaoArea.integer'   => 'Codigo da lotação deve ser inteiro',
                'Valor.required' => 'valor deve ser informador'
            ]
        );
    }
    public function ListFinanceiroAgrupado()
    {
        $list = $this->rh->all();
        return response()->json($list, 200);
    }
    public function SumFinanceiroAgrupado()
    {
        $data = $this->request->all();
       
        $list = RhGestor::where('comp', $data['Competencia'])->where('cd_indicador', $data['CodIndicador'])->sum('valor');
        return response()->json($list, 200);
    }
}
