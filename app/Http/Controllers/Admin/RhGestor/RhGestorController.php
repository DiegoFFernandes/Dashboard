<?php

namespace App\Http\Controllers\Admin\RhGestor;

use App\Http\Controllers\Controller;
use App\Models\AreaLotacao;
use App\Models\Pessoa;
use App\Models\RhGestor;
use DateTime;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RhGestorController extends Controller

{
    public $request, $rh, $pessoa, $area;
    public function __construct(
        Request $request,
        RhGestor $rh,
        Pessoa $pessoa,
        AreaLotacao $area,
    ) {
        $this->request = $request;
        $this->pessoa = $pessoa;
        $this->rh = $rh;
        $this->area = $area;
    }
    public function list() {}
    public function storeEmployee() {}
    public function storeArea() {}
    public function IndicadorFinanceiroAgrupado()
    {
        ini_set('max_execution_time', 10000);

        $data = $this->request->json()->all();      

        $dateTime = DateTime::createFromFormat('n/j/Y g:i:s A', $data[0]['Competencia']);
        $dateTime->modify('+1 month');
        $comp = $dateTime->format('m/Y');

        //deleta todos os dados da competencia que o rhgestor esta enviando para nós para inserir novamente.
        $this->rh->destroyData($comp);

        $errors = [];

        foreach ($data as $index => $item) {
            //Faz a divisão da lotação pegando somente o codigo do Area ex: 11011 - Administração - CGS
            $cd_area_lotacao = array_map('trim', explode('-', $item['DsLotacao_Area']));

            $dateTime = DateTime::createFromFormat('n/j/Y g:i:s A', $item['Competencia']);
            $dateTime->modify('+1 month');

            $data[$index]['Competencia'] = $dateTime->format('m/Y');

            $data[$index]['cpf'] = Helper::RemoveSpecialChar($data[$index]['cpf']);
            // Troca a informação no Array, somente para 11011

            //Quando não existe Lotação criada no RhGestor, por padrão o banco deles vem A Denifir, a rotina abaixo muda para 99998.
            if ($cd_area_lotacao[0] === 'A definir') {
                $cd_area_lotacao[0] = 99996; // 'GRUPO A DEFINIR NO MYSQL'
                $cd_area_lotacao[1] = 'A DEFENIR';
            }

            $data[$index]['DsLotacao_Area'] = $cd_area_lotacao[0];


            $validator = $this->__validate($item);

            //Verifica se existe a pessoa se não vai o insert ou atualiza os dados dela de acordo com o CPF
            $this->pessoa->UpdateOrInsert($data[$index]);

            //Armazenas novas lotações caso não existir
            $this->area->StoreAreaLotacao($cd_area_lotacao);


            if ($validator->fails()) {
                $errors[$index] = [
                    'corrigir' => $validator->errors(),
                    'competencia' => $item['Competencia'],
                    'ds_lotacao_area' => $item['DsLotacao_Area'],
                    'ds_lotacao_empresa' => $item['DsLotacao_Empresa'],
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
                'DsLotacao_Area' => 'string|required',
                'NomeColaborador' => 'string|required',
                'DsLotacao_Empresa' => 'string|required',
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
