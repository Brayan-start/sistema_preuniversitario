<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('destinatario');
            $table->string('asunto');
            $table->string('tipo_evento'); // e.g., bienvenida, inscripcion_aprobada, pago_rechazado
            $table->enum('estado_envio', ['enviado', 'fallido', 'pendiente'])->default('pendiente');
            $table->text('mensaje_error')->nullable();
            $table->foreignId('usuario_responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->string('horario')->nullable()->after('requisitos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('horario');
        });
    }
};
