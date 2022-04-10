<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $data = Role::whereNotIn('id', [1]);
        if (request()->has('filter') && count($data->get()))
            $data = $data->filter();
        $data = $data->get();
        return $this->showAll($data->load('permissions'));
    }

    public function show_permissions()
    {
        $data = Permission::where('is_admin', '<>', 1);
        if (request()->has('filter') && count($data->get()))
            $data = $data->search();
        $data = $data->get();
        return $this->showAll($data);
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|max:255|unique:roles,description',
            'name'         => 'required|max:255|unique:roles,name',
            'permissions' => 'required|array',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        //quitar administrador del array de permisos
        if ($request->has('permissions')) {
            $permissions = $request->permissions;
            $data['permissions'] = $permissions;
        }
        $role = Role::create($data);
        $role->givePermissionTo($data['permissions']);
        return $this->showOne($role->load('permissions'), 201);
    }

    public function show(Role $role)
    {
        return $this->showOne($role->load('permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->id == 1 || $role->id == 2) {
            return $this->errorResponse('No puedes editar este rol', null, 409);
        }
        $rules = [
            'name'        => 'required|max:255|unique:roles,name,' . $role->id,
            'description' => 'required|max:255|unique:roles,description,' . $role->id,
            'permissions' => 'required|array',
        ];
        $data = $this->validate($request, $rules);

        if ($request->has('permissions')) {
            $permissions = $request->permissions;
            $data['permissions'] = $permissions;
        }
        $role->syncPermissions($data['permissions']);
        return $this->showOne($role->load('permissions'));
    }


    public function destroy(Role $role)
    {
        if ($role->id == 1 || $role->id == 2) {
            return $this->errorResponse('No se puede eliminar este rol', null, 422);
        }
        $role->delete();
        return $this->showOne($role);
    }
}
