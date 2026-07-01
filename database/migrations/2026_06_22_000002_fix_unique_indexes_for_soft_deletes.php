<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE aspirantes DROP CONSTRAINT IF EXISTS aspirantes_ci_unique');
        DB::statement('ALTER TABLE aspirantes DROP CONSTRAINT IF EXISTS aspirantes_correo_unique');
        DB::statement('DROP INDEX IF EXISTS aspirantes_ci_active');
        DB::statement('DROP INDEX IF EXISTS aspirantes_correo_active');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE aspirantes ADD CONSTRAINT aspirantes_ci_unique UNIQUE (ci)');
        DB::statement('ALTER TABLE aspirantes ADD CONSTRAINT aspirantes_correo_unique UNIQUE (correo)');
    }
};