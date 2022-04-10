<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['index', 'login']);
        $this->middleware('verified')->except(['index', 'login', 'logout', 'user_data', 'update_only_email']);
    }
    public function index()
    {
        if (request()->expectsJson()) {
            $this->showMessage('Login required', false, 401);
        }
        return redirect()->away(env('APP_URL_WEB'));
    }
    public function login(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if (!is_null($user) && Hash::check($request->password, $user->password)) {
            if (!$user->active)
                return $this->errorResponse('El usuario no está activo', null, 409);
            $token = $user->createToken("Laravel Personal Access Client");
            $permissions = $user->getAllPermissions()->map(function ($item, $key) {
                return $item->only('id', 'name', 'guard_name', 'action', 'subject');
            });
            $roles = $user->roles->map(function ($item, $key) {
                return $item->only('id', 'name', 'guard_name', 'action', 'subject');
            });
            $data = [
                'user' => $user->makeHidden(['permissions', 'roles']),
                'token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
                'permissions' => $permissions,
                'roles' => $roles
            ];
            return $this->showMessage("Login exitoso. Bienvenido al sistema", $data, 200);
        } else
            return $this->errorResponse('Usuario o contraseña incorrectos', null, 401);
    }
    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();
        return $this->showMessage("Logout exitoso", null, 200);
    }
}
