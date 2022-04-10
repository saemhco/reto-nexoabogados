<?php

namespace App\Http\Controllers\v1\Auth;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\FilesHelper;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\Update;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified'])->except(['store']);
        $this->middleware('can:user.index')->only('index');
        $this->middleware('can:user.show')->only('show');
        //$this->middleware('can:user.store')->only('store');
        $this->middleware('can:user.update')->only('update');
        $this->middleware('can:user.destroy')->only('destroy');
    }
    public function index()
    {
        $data = User::filters()->WithDataAll()->get();
        return $this->showAll($data);
    }

    public function store(Store $request)
    {
        $user = User::create($request->input());
        $user->assignRole('Abogado(a)');
        if ($request->hasFile('avatar')) {
            $user->avatar_id = FilesHelper::save($request->file('avatar'), 'users/avatar');
            $user->save();
        }
        return $this->showMessage("Datos registrados correctamente", $user->withData(), 201);
    }

    public function show(User $user)
    {
        return $this->showOne($user->load(['roles', 'permissions']));
    }

    public function update(Update $request, User $user)
    {
        if ($user->id == 1)
            return $this->errorResponse('Este usuario no puede ser editado', null, 409);

        if (!$request->has('password'))
            $request = Arr::except($request, ['password']);

        if ($request->has('active'))
            $request['active'] = (bool) $request['active'];
        else
            $request = Arr::except($request, ['active']);
        $user->fill($request->input());
        $user->save();

        if ($request->hasFile('avatar')) {
            $user->avatar_id = FilesHelper::save($request->file('avatar'), 'users/avatar');
            $user->save();
        }
        return $this->showMessage("Se actualizó correctamente", $user->fresh()->WithData(), 200);
    }

    public function user_data()
    {
        $user = auth()->user()->makeHidden(['created_at', 'updated_at', 'permissions', 'roles', 'email_verified_at']);
        $permissions = $user->getAllPermissions()->map(function ($item, $key) {
            return $item->only('id', 'name', 'guard_name', 'action', 'subject');
        });
        $roles = $user->roles->map(function ($item, $key) {
            return $item->only('id', 'name', 'guard_name', 'action', 'subject');
        });
        return $this->showMessage("Datos de usuario", [
            'user' => $user,
            'permissions' => $permissions,
            'roles' => $roles
        ], 200);
    }

    public function password_update(Request $request)
    {
        if (Hash::check($request->old_password, auth()->user()->password)) {
            auth()->user()->password = $request->new_password;
            auth()->user()->save();
            return $this->showMessage("Contraseña actualizada", null, 200);
        } else {
            return $this->errorResponse('Contraseña actual incorrecta', null, 401);
        }
    }
    public function update_only_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->user()->id
        ]);
        auth()->user()->update([
            'email' => $request->email,
            'email_verified_at' => null
        ]);
        return $this->showMessage("Email actualizado", auth()->user()->email, 200);
    }
}
