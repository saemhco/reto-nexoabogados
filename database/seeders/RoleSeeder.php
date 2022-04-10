<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $panel = Role::create(['name' => 'Panel', 'description' => 'Administrador del sistema']);
        $abogado   = Role::create(['name' => 'Abogado(a)', 'description' => 'Abogado']);

        Permission::create(['name' => "user.index",  'description' => 'Listar usuarios',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "user.store",  'description' => 'Registrar un nuevo usuario',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "user.show",   'description' => 'Mostrar un usuario',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "user.update", 'description' => 'Actualizar datos de un usuario', 'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "user.destroy", 'description' => 'Eliminar un registro de usuario', 'is_admin' => true])->syncRoles($panel);

        Permission::create(['name' => "role.index",  'description' => 'Listar roles',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "role.store",  'description' => 'Registrar un nuevo rol',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "role.show",   'description' => 'Mostrar un role',  'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "role.update", 'description' => 'Actualizar datos de un role', 'is_admin' => true])->syncRoles($panel);
        Permission::create(['name' => "role.destroy", 'description' => 'Eliminar un registro de role', 'is_admin' => true])->syncRoles($panel);

        //roles de prueba
        Permission::create(['name' => "suscription.index",  'description' => 'Listar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "suscription.store",  'description' => 'Registrar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "suscription.show",   'description' => 'Mostrar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "suscription.update", 'description' => 'Actualizar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "suscription.destroy", 'description' => 'Eliminar data'])->syncRoles([$panel, $abogado]);
    }
}
