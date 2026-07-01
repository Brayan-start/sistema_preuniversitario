<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aspirante;
use App\Models\Auditoria;
use App\Mail\ForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Services\NotificationEmailService;
use App\Mail\AdminNuevoRegistroMail;

class AuthController extends Controller
{
    protected $emailService;

    public function __construct(NotificationEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    private function crearAspirante(array $data): array
    {
        $user = User::where('email', $data['correo'])->first();

        if ($user) {
            $user->update([
                'name' => $data['nombre_completo'],
                'password' => Hash::make($data['password']),
                'role' => 'aspirante',
                'is_active' => true,
            ]);
        } else {
            $user = User::create([
                'name' => $data['nombre_completo'],
                'email' => $data['correo'],
                'password' => Hash::make($data['password']),
                'role' => 'aspirante',
            ]);
        }

        $aspirante = Aspirante::create([
            'user_id' => $user->id,
            'nombre_completo' => $data['nombre_completo'],
            'ci' => $data['ci'],
            'correo' => $data['correo'],
            'celular' => $data['celular'],
            'colegio_procedencia' => $data['colegio_procedencia'],
            'anio_egreso' => $data['anio_egreso'],
        ]);

        $this->emailService->send($user, new \App\Mail\BienvenidaMail($user), 'bienvenida');
        $this->notificarAdminNuevoRegistro($aspirante);

        return [$user, $aspirante];
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:100',
            'ci' => ['required', 'string', 'max:20', Rule::unique('aspirantes', 'ci')->whereNull('deleted_at')],
            'correo' => ['required', 'string', 'email', 'max:100', Rule::unique('aspirantes', 'correo')->whereNull('deleted_at')],
            'celular' => 'required|string|max:20',
            'colegio_procedencia' => 'required|string|max:150',
            'anio_egreso' => 'required|digits:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        [$user, $aspirante] = $this->crearAspirante($request->all());

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
            'ci' => ['required', 'string', 'max:20', Rule::unique('aspirantes', 'ci')->whereNull('deleted_at')],
            'correo' => ['required', 'string', 'email', 'max:100', Rule::unique('aspirantes', 'correo')->whereNull('deleted_at')],
            'celular' => 'required|string|max:20',
            'colegio_procedencia' => 'required|string|max:150',
            'anio_egreso' => 'required|digits:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        [$user, $aspirante] = $this->crearAspirante($request->all());

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'Registro de aspirante (Web)',
            'descripcion' => "El aspirante {$aspirante->nombre_completo} se ha registrado exitosamente vía web.",
        ]);

        return redirect()->route('login')->with('success', 'Usuario registrado correctamente. Por favor, inicie sesión.');
    }

    private function notificarAdminNuevoRegistro(Aspirante $aspirante): void
    {
        $admins = User::where('role', 'administrador')->where('is_active', true)->get();
        foreach ($admins as $admin) {
            $this->emailService->send($admin, new AdminNuevoRegistroMail($aspirante, $admin), 'nuevo_aspirante', $admin->id);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $user = auth()->user();

        if ($user->role === 'aspirante' && !$user->aspirante) {
            auth()->logout();
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

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

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh(),
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'No existe una cuenta con ese correo.'], 404);
        }

        $token = Str::random(60);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => now()]
        );

        $this->emailService->send($user, new ForgotPasswordMail($user, $token), 'password_reset');

        return response()->json(['message' => 'Enlace de recuperación enviado a tu correo.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = \DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json(['error' => 'Token inválido o expirado.'], 400);
        }

        if (now()->diffInMinutes($reset->created_at) > 60) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json(['error' => 'Token expirado. Solicita un nuevo enlace.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        $user->update(['password' => Hash::make($request->password)]);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Contraseña restablecida exitosamente.']);
    }

    public function sendResetLinkEmailWeb(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No existe una cuenta con ese correo.']);
        }

        $token = Str::random(60);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => now()]
        );

        $this->emailService->send($user, new ForgotPasswordMail($user, $token), 'password_reset');

        return back()->with('success', 'Enlace de recuperación enviado a tu correo electrónico.');
    }

    public function showResetForm($token)
    {
        return view('auth.password-reset', ['token' => $token]);
    }

    public function resetPasswordWeb(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = \DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Token inválido o expirado.']);
        }

        if (now()->diffInMinutes($reset->created_at) > 60) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token expirado. Solicita un nuevo enlace.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Contraseña restablecida exitosamente. Inicia sesión.');
    }
}
