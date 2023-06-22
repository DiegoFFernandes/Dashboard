<?php

namespace App\Console\Commands;

use App\Models\LiberaOrdemComercial;
use Illuminate\Console\Command;

class SendPedidoBloquedosWpp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:pedidobloqueadowpp {localizacao} {funcao}';
    protected $libera;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia os pedidos bloqueados para o whatsapp do responsavel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct( LiberaOrdemComercial $libera)
    {
        parent::__construct();
        $this->libera = $libera;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $localizacao = $this->argument('localizacao');
        $funcao = $this->argument('funcao');

        $id = 0;        
        $pedidos_coordenador = [];
        $pedidos_gerencia = [];
        $dados = $this->libera->listPneusOrdensBloqueadas($id, $localizacao);
        
        //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
        foreach ($dados as $p) {
            if (floatval($p->PC_DESCONTO) >= 20) {
                $pedidos_gerencia[] = $p->PEDIDO;
            } elseif (floatval($p->PC_DESCONTO) >= 15 || floatval($p->PC_DESCONTO) <= 19.9) {
                $pedidos_coordenador[] = $p->PEDIDO;
            }
        }        
        if ($funcao == 'gerente') {
            $pedidos = array_unique($pedidos_gerencia);
            //Serealize os pedidos separando em (,)
            $pedidos = implode(",", $pedidos);
            $cd_regiao = "";
        } 
        elseif ($funcao == 'coordenador') {
            //Criar condição caso o usuario for gerente mais não estiver associado no painel
            $find = $this->area->findAreaUser($this->user->id);
            $regiao = $this->regiao->regiaoArea($find[0]->cd_areacomercial);
            foreach ($regiao as $r) {
                $cd_regiao[] = $r->CD_REGIAOCOMERCIAL;
            }
            //serialize a informação vinda do banco e faz o implode dos valores separados por (;)
            $cd_regiao = implode(",", $cd_regiao);
            $pedidos = array_unique($pedidos_coordenador);
            //Serealize os pedidos separando em (,)
            $pedidos = implode(",", $pedidos);
        }

        
        $data = $this->libera->listOrdensBloqueadas($cd_regiao, $pedidos, $localizacao);

        dd($data);
        return 0;
    }
}
