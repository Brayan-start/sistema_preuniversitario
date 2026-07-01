<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(User::where('role', 'administrador')->get());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'administrador',
        ]);

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación de administrador',
            'descripcion' => "Cuenta administradora creada para {$user->email}.",
        ]);

        return response()->json($user, 201);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'email' => 'string|email|unique:users,email,' . $id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->all());

        if ($request->has('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización de usuario',
            'descripcion' => "Cuenta de {$user->email} actualizada.",
        ]);

        return response()->json($user);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación de usuario',
            'descripcion' => "Usuario {$user->email} eliminado.",
        ]);

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
