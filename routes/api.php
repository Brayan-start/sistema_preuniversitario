<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AspiranteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuditoriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // Rutas para Aspirantes
    Route::group(['middleware' => 'role:aspirante', 'prefix' => 'aspirante'], function () {
        Route::get('perfil', [AspiranteController::class, 'getPerfil']);
        Route::get('cursos-disponibles', [CursoController::class, 'indexActive']);
        Route::post('inscribirse', [InscripcionController::class, 'store']);
        Route::get('mis-inscripciones', [InscripcionController::class, 'misInscripciones']);
        Route::post('subir-documentos', [DocumentoController::class, 'upload']);
        Route::post('registrar-pago', [PagoController::class, 'store']);
        Route::get('estado-inscripcion/{id}', [InscripcionController::class, 'show']);
    });

    // Rutas para Administradores
    Route::group(['middleware' => 'role:administrador', 'prefix' => 'admin'], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        
        // Gestión de Cursos
        Route::apiResource('cursos', CursoController::class);
        
        // Gestión de Inscripciones
        Route::get('inscripciones', [InscripcionController::class, 'index']);
        Route::get('inscripciones/{id}', [InscripcionController::class, 'showAdmin']);
        Route::post('inscripciones/{id}/cambiar-estado', [InscripcionController::class, 'cambiarEstado']);
        
        // Gestión de Pagos
        Route::get('pagos', [PagoController::class, 'index']);
        Route::post('pagos/{id}/verificar', [PagoController::class, 'verificar']);
        
        // Gestión de Documentos
        Route::post('documentos/{id}/verificar', [DocumentoController::class, 'verificar']);
        
        // Gestión de Usuarios
        Route::apiResource('usuarios', UsuarioController::class);
        
        // Reportes
        Route::get('reportes/inscripciones', [ReporteController::class, 'inscripciones']);
        Route::get('reportes/pagos', [ReporteController::class, 'pagos']);
        Route::get('reportes/exportar-pdf', [ReporteController::class, 'exportPdf']);
        Route::get('reportes/exportar-excel', [ReporteController::class, 'exportExcel']);
        
        // Auditoría
        Route::get('auditoria', [AuditoriaController::class, 'index']);
    });
});
