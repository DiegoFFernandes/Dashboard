<?php

namespace App\Http\Controllers\Admin\RhGestor;

use App\Http\Controllers\Controller;
use App\Models\RhGestor;
use DateTime;
use Exception;
use Helper;
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
            $cd_area_lotacao = array_map('trim', explode('-', $item['DsLotacao_Area']));
            
            $dateTime = DateTime::createFromFormat('n/j/Y g:i:s A', $item['Competencia']);
            $dateTime->modify('+1 month');

            $data[$index]['Competencia'] = $dateTime->format('m/Y');            

            $data[$index]['cpf'] = Helper::RemoveSpecialChar($data[$index]['cpf']);            
            // Troca a informação no Array, somente para 11011
            $data[$index]['DsLotacao_Area'] = $cd_area_lotacao[0];

            $validator = $this->__validate($item); 

            if ($validator->fails()) {
                $errors[$index] = [
                    'corrigir' => $validator->errors(),
                    'competencia' => $item['Competencia'],
                    'ds_lotacao_area' => $item['DsLotacao_Area'],
                    'cd_indicador' => $item['CodIndicador']
                ];
            }
        }
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        return $data;
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
                'DsLotacao_Area' => 'string|required',
                'valor'  => 'numeric|required'
            ],
            [
                'Competencia.required'  => 'Competencia deve ser preenchida',
                'CodIndicador.required' => 'Codigo indicador deve ser informado',
                'DsLotacao_Area.required'   => 'Codigo da lotação deve ser informado',
                'DsLotacao_Area.integer'   => 'Codigo da lotação deve ser inteiro',
                'valor.required' => 'Valor deve ser informador'
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
