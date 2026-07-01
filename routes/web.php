<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AspiranteController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\AuthController;

use App\Models\Curso;

Route::get('/prueba', function () {

    return 'hola';
});

Route::get('/', function () {
    $cursos = Curso::where('is_active', true)->where('cupos_disponibles', '>', 0)->limit(3)->get();
    return view('welcome', compact('cursos'));
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Usuario desactivado']);
        }

        $token = JWTAuth::fromUser($user);
        session(['token' => $token]);

        Auditoria::create([
            'user_id' => $user->id,
            'accion' => 'inicio_sesion',
            'descripcion' => '[autenticacion] | IP: ' . $request->ip() . ' Inicio de sesion web.',
        ]);

        if ($user->role === 'administrador') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/aspirante/dashboard');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'registerWeb']);

// Password Reset Routes
Route::get('/password/reset', function () {
    return view('auth.password-reset');
})->name('password.request');

Route::post('/password/email', [AuthController::class, 'sendResetLinkEmailWeb'])->name('password.email');

Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');

Route::post('/password/reset', [AuthController::class, 'resetPasswordWeb'])->name('password.update');

use App\Http\Controllers\CursoController;

use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuditoriaController;

Route::middleware(['auth'])->group(function () {
    // Rutas Comunes
    Route::get('/perfil', [AccountController::class, 'profile'])->name('perfil');
    Route::put('/perfil', [AccountController::class, 'updateProfile'])->name('perfil.update');
    Route::get('/configuracion', [AccountController::class, 'configuration'])->name('configuracion');
    Route::put('/configuracion', [AccountController::class, 'updateConfiguration'])->name('configuracion.update');
    Route::get('/documentos/{documento}/archivo', [DocumentoController::class, 'showFile'])->name('documentos.archivo');
    Route::get('/pagos/{pago}/comprobante', [PagoController::class, 'showComprobante'])->name('pagos.comprobante');

    // Rutas para Aspirantes
    Route::middleware(['role:aspirante'])->group(function () {
        Route::get('/aspirante/dashboard', [AspiranteController::class, 'dashboardView'])->name('aspirante.dashboard');
        Route::get('/aspirante/cursos', [AspiranteController::class, 'cursosDisponibles'])->name('aspirante.cursos');
        Route::post('/aspirante/inscribirse', [InscripcionController::class, 'storeWeb'])->name('aspirante.inscribirse');

        Route::get('/aspirante/documentos', [DocumentoController::class, 'indexWeb'])->name('aspirante.documentos');
        Route::post('/aspirante/documentos', [DocumentoController::class, 'uploadWeb'])->name('aspirante.documentos.store');

        Route::get('/aspirante/pagos', [PagoController::class, 'indexWeb'])->name('aspirante.pagos');
        Route::post('/aspirante/pagos', [PagoController::class, 'storeWeb'])->name('aspirante.pagos.store');
    });

    // Rutas para Administradores
    Route::middleware(['role:administrador'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboardView'])->name('admin.dashboard');
        Route::get('/admin/dashboard/datos', [AdminController::class, 'dashboard'])->name('admin.dashboard.data');
        Route::get('/admin/estadisticas', [AdminController::class, 'statisticsView'])->name('admin.estadisticas.index');
        Route::get('/admin/estadisticas/datos', [AdminController::class, 'statisticsData'])->name('admin.estadisticas.data');
        Route::get('/admin/aspirantes/busqueda', [AdminController::class, 'advancedSearchView'])->name('admin.aspirantes.search');
        Route::get('/admin/aspirantes/busqueda/datos', [AdminController::class, 'advancedSearchData'])->name('admin.aspirantes.search.data');

        // Gestión de Cursos
        Route::get('/admin/cursos', [CursoController::class, 'indexWeb'])->name('admin.cursos.index');
        Route::get('/admin/cursos/crear', [CursoController::class, 'create'])->name('admin.cursos.create');
        Route::post('/admin/cursos', [CursoController::class, 'storeWeb'])->name('admin.cursos.store');
        Route::get('/admin/cursos/{id}/editar', [CursoController::class, 'edit'])->name('admin.cursos.edit');
        Route::put('/admin/cursos/{id}', [CursoController::class, 'updateWeb'])->name('admin.cursos.update');
        Route::delete('/admin/cursos/{id}', [CursoController::class, 'destroyWeb'])->name('admin.cursos.destroy');

        // Gestión de Aspirantes e Inscripciones
        Route::get('/admin/aspirantes', [AdminController::class, 'aspirantesList'])->name('admin.aspirantes.index');
        Route::patch('/admin/aspirantes/{aspirante}/estado', [AdminController::class, 'updateAspiranteStatus'])->name('admin.aspirantes.estado');
        Route::delete('/admin/aspirantes/{id}', [AdminController::class, 'destroyAspirante'])->name('admin.aspirantes.destroy');
        Route::get('/admin/inscripciones', [InscripcionController::class, 'indexWeb'])->name('admin.inscripciones.index');
        Route::get('/admin/inscripciones/{id}', [InscripcionController::class, 'verInscripcion'])->name('admin.inscripciones.show');
        Route::post('/admin/inscripciones/{id}/validar', [InscripcionController::class, 'validarInscripcion'])->name('admin.inscripciones.validar');

        // Gestión de Documentos
        Route::get('/admin/documentos', [DocumentoController::class, 'indexAdminWeb'])->name('admin.documentos.index');
        Route::post('/admin/documentos/{id}/verificar', [DocumentoController::class, 'verificarWeb'])->name('admin.documentos.verificar');

        // Gestión de Pagos
        Route::get('/admin/pagos', [PagoController::class, 'indexAdmin'])->name('admin.pagos.index');
        Route::post('/admin/pagos/{id}/verificar', [PagoController::class, 'verificarWeb'])->name('admin.pagos.verificar');

        // Reportes y Auditoría
        Route::get('/admin/reportes', [ReporteController::class, 'indexWeb'])->name('admin.reportes.index');
        Route::get('/admin/reportes/exportar-pdf', [ReporteController::class, 'exportPdf'])->name('admin.reportes.exportar-pdf');
        Route::get('/admin/reportes/exportar-excel', [ReporteController::class, 'exportExcel'])->name('admin.reportes.exportar-excel');
        Route::get('/admin/auditoria', [AuditoriaController::class, 'indexWeb'])->name('admin.auditoria.index');
        Route::get('/admin/auditoria/datos', [AuditoriaController::class, 'index'])->name('admin.auditoria.data');
    });

    Route::post('/logout', function (Request $request) {
        $user = $request->user();

        if ($user) {
            Auditoria::create([
                'user_id' => $user->id,
                'accion' => 'cierre_sesion',
                'descripcion' => '[autenticacion] | IP: ' . $request->ip() . ' Cierre de sesion web.',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
