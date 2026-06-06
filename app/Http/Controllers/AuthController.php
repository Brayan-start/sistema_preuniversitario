<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aspirante;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:aspirantes',
            'correo' => 'required|string|email|max:100|unique:users,email',
            'celular' => 'required|string|max:20',
            'colegio_procedencia' => 'required|string|max:150',
            'anio_egreso' => 'required|digits:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->nombre_completo,
            'email' => $request->correo,
            'password' => Hash::make($request->password),
            'role' => 'aspirante',
        ]);

        $aspirante = Aspirante::create([
            'user_id' => $user->id,
            'nombre_completo' => $request->nombre_completo,
            'ci' => $request->ci,
            'correo' => $request->correo,
            'celular' => $request->celular,
            'colegio_procedencia' => $request->colegio_procedencia,
            'anio_egreso' => $request->anio_egreso,
        ]);

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'Registro de aspirante',
            'descripcion' => "El aspirante {$aspirante->nombre_completo} se ha registrado exitosamente.",
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'aspirante', 'token'), 201);
    }

    public function registerWeb(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:aspirantes',
            'correo' => 'required|string|email|max:100|unique:users,email',
            'celular' => 'required|string|max:20',
            'colegio_procedencia' => 'required|string|max:150',
            'anio_egreso' => 'required|digits:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->nombre_completo,
            'email' => $request->correo,
            'password' => Hash::make($request->password),
            'role' => 'aspirante',
        ]);

        $aspirante = Aspirante::create([
            'user_id' => $user->id,
            'nombre_completo' => $request->nombre_completo,
            'ci' => $request->ci,
            'correo' => $request->correo,
            'celular' => $request->celular,
            'colegio_procedencia' => $request->colegio_procedencia,
            'anio_egreso' => $request->anio_egreso,
        ]);

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'Registro de aspirante (Web)',
            'descripcion' => "El aspirante {$aspirante->nombre_completo} se ha registrado exitosamente vía web.",
        ]);

        return redirect()->route('login')->with('success', 'Usuario registrado correctamente. Por favor, inicie sesión.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $user = auth()->user();

        if (!$user->is_active) {
            return response()->json(['error' => 'Usuario desactivado'], 403);
        }

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'Login',
            'descripcion' => "El usuario {$user->email} ha iniciado sesión.",
        ]);

        return response()->json(compact('user', 'token'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();
        
        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'Logout',
            'descripcion' => "El usuario {$user->email} ha cerrado sesión.",
        ]);

        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh(),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
