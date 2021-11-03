<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'editar']);
        Permission::create(['name' => 'excluir']);
        Permission::create(['name' => 'listar']);
        Permission::create(['name' => 'criar']);
        Permission::create(['name' => 'ver-financeiro']);
        Permission::create(['name' => 'ver-cobranca-sul']);
        Permission::create(['name' => 'ver-cobranca-norte']);
        Permission::create(['name' => 'ver-faturamento']);
        Permission::create(['name' => 'ver-comercial']);
        Permission::create(['name' => 'ver-producao']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'editar', 'listar', 'criar', 'ver-financeiro', 'ver-cobranca', 'ver-faturamento', 'ver-comercial-sul', 'ver-comercial-norte','ver-producao']);

        $role = Role::create(['name' => 'portaria'])
        ->givePermissionTo(['editar', 'listar', 'criar']);

        $role = Role::create(['name' => 'comercial'])
        ->givePermissionTo(['ver-comercial-sul', 'ver-comercial-norte']);

        $role = Role::create(['name' => 'financeiro'])
        ->givePermissionTo(['ver-financeiro']);

        $role = Role::create(['name' => 'producao'])
        ->givePermissionTo(['ver-producao']);

        $role = Role::create(['name' => 'usuario']);

        $role = Role::create(['name' => 'diretoria'])
        ->givePermissionTo(['diretoria-sul', 'diretoria-norte']);
        
    }
}
