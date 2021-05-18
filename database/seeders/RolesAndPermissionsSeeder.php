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
        Permission::create(['name' => 'ver financeiro']);
        Permission::create(['name' => 'ver cobranca']);
        Permission::create(['name' => 'ver faturamento']);
        Permission::create(['name' => 'ver comercial']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'portaria'])
        ->givePermissionTo(['editar', 'listar', 'criar']);

        $role = Role::create(['name' => 'comercial'])
        ->givePermissionTo(['editar', 'listar', 'criar']);

        $role = Role::create(['name' => 'financeiro'])
        ->givePermissionTo(['editar', 'listar', 'criar']);

        $role = Role::create(['name' => 'usuario'])
        ->givePermissionTo(['ver comercial']);
        
    }
}
