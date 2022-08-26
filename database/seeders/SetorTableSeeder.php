<?php

namespace Database\Seeders;

use App\Models\Setor;
use Illuminate\Database\Seeder;

class SetorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setores = [
            ['nm_setor' => 'Faturamento'],
            ['nm_setor' => 'Financeiro'],
            ['nm_setor' => 'Cobrança'],
            ['nm_setor' => 'Tecnologia da Informação'],
            ['nm_setor' => 'Departamento Pessoal'],
            ['nm_setor' => 'Fiscal'],
            ['nm_setor' => 'Compras'],
            ['nm_setor' => 'Produção'],
            ['nm_setor' => 'Manutenção'],
            ['nm_setor' => 'Qualidade'],
            ['nm_setor' => 'Almoxarifado'],
            ['nm_setor' => 'Planejamento Operacional'],
            ['nm_setor' => 'Júridico'],
            ['nm_setor' => 'Crédito'],
            ['nm_setor' => 'Recrutamento e Seleção'],
            ['nm_setor' => 'Endo Markenting'],
            ['nm_setor' => 'Saúde e Segurança'],
            ['nm_setor' => 'Meio Ambiente e Auditoria'],
            ['nm_setor' => 'Treinamento'],
            ['nm_setor' => 'Marketing'],
            ['nm_setor' => 'Truck Center'],
            ['nm_setor' => 'Frotas'],
            ['nm_setor' => 'AF'],
            ['nm_setor' => 'Vendas'],
            ['nm_setor' => 'Fiscal'],
            ['nm_setor' => 'Contabilidade'],
            ['nm_setor' => 'Controller'],           

        ];

        foreach ($setores as $s) {
            Setor::insert([
                'nm_setor' => $s['nm_setor'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
