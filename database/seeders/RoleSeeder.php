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

        Permission::create(['name' => "subscription.index",  'description' => 'Listar data'])->syncRoles([$panel]);
        Permission::create(['name' => "subscription.store",  'description' => 'Registrar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "subscription.show",   'description' => 'Mostrar data'])->syncRoles([$panel]);
        Permission::create(['name' => "subscription.update", 'description' => 'Actualizar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "subscription.renewal_cancel", 'description' => 'Cancelar renovación de suscripción'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "subscription.current_subscription", 'description' => 'Ver suscripción actual'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "subscription.cancel", 'description' => 'Cancelar cualquier suscripción'])->syncRoles([$panel]);
        Permission::create(['name' => "subscription.processing_payment", 'description' => 'Reintentar pago de cualquier suscripción'])->syncRoles([$panel]);

        Permission::create(['name' => "plan.index",  'description' => 'Listar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "plan.store",  'description' => 'Registrar data'])->syncRoles([$panel]);
        Permission::create(['name' => "plan.show",   'description' => 'Mostrar data'])->syncRoles([$panel, $abogado]);
        Permission::create(['name' => "plan.update", 'description' => 'Actualizar data'])->syncRoles([$panel]);
        Permission::create(['name' => "plan.destroy", 'description' => 'Eliminar data'])->syncRoles([$panel]);
    }
}
