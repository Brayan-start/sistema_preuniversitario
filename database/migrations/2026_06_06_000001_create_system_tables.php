<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar tabla users existente para agregar columnas faltantes
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['aspirante', 'administrador'])->default('aspirante')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::create('aspirantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre_completo', 100);
            $table->string('ci', 20)->unique();
            $table->string('correo', 100)->unique();
            $table->string('celular', 20);
            $table->string('colegio_procedencia', 150);
            $table->year('anio_egreso');
            $table->timestamps();
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_curso', 100);
            $table->text('descripcion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto_arancel', 10, 2);
            $table->integer('cupos_disponibles');
            $table->text('requisitos');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->constrained('aspirantes');
            $table->foreignId('curso_id')->constrained('cursos');
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->string('grupo', 5)->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->dateTime('fecha_cambio_estado')->nullable();
            $table->foreignId('admin_responsable_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->constrained('aspirantes');
            $table->foreignId('inscripcion_id')->constrained('inscripciones');
            $table->enum('tipo', ['ci', 'certificado_bachillerato', 'fotografia']);
            $table->string('archivo_path', 255);
            $table->enum('formato', ['pdf', 'jpg', 'jpeg']);
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->unique()->constrained('inscripciones');
            $table->string('numero_comprobante', 100);
            $table->string('comprobante_path', 255);
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('accion', 255);
            $table->text('descripcion');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('inscripciones');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('aspirantes');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};