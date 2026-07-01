<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function profile()
    {
        $user = auth()->user()->load('aspirante');
        $aspirante = $user->aspirante;
        $inscripcion = $aspirante
            ? Inscripcion::with('curso')->where('aspirante_id', $aspirante->id)->latest()->first()
            : null;
        $ultimaActividad = Auditoria::where('user_id', $user->id)->latest('created_at')->first();

        return view('account.profile', compact('user', 'aspirante', 'inscripcion', 'ultimaActividad'));
    }

    public function updateProfile(Request $request)
    {
        $this->updateAccountData($request);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function configuration()
    {
        $user = auth()->user()->load('aspirante');
        $aspirante = $user->aspirante;

        return view('account.configuration', compact('user', 'aspirante'));
    }

    public function updateConfiguration(Request $request)
    {
        $this->updateAccountData($request, true);

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    private function updateAccountData(Request $request, bool $allowPassword = false): void
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'email_notifications' => ['nullable', 'boolean'],
        ];

        if ($user->isAspirante()) {
            $rules = array_merge($rules, [
                'nombre_completo' => ['required', 'string', 'max:100'],
                'celular' => ['required', 'string', 'max:20'],
                'colegio_procedencia' => ['required', 'string', 'max:150'],
                'anio_egreso' => ['required', 'digits:4'],
            ]);
        }

        if ($allowPassword) {
            $rules = array_merge($rules, [
                'current_password' => ['required_with:password', 'nullable', 'current_password'],
                'password' => ['nullable', 'confirmed', 'min:8'],
            ]);
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->has('email_notifications')) {
            $data['email_notifications'] = $request->boolean('email_notifications');
        }

        if ($allowPassword && !empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update($data);

        if ($user->isAspirante() && $user->aspirante) {
            $user->aspirante->update([
                'nombre_completo' => $validated['nombre_completo'],
                'correo' => $validated['email'],
                'celular' => $validated['celular'],
                'colegio_procedencia' => $validated['colegio_procedencia'],
                'anio_egreso' => $validated['anio_egreso'],
            ]);
        }

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => $allowPassword ? 'Actualización de configuración' : 'Actualización de perfil',
            'descripcion' => "El usuario {$user->name} actualizó sus datos de cuenta.",
        ]);
    }
}
