<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Genera un respaldo de la base de datos';

    public function handle()
    {
        $filename = "backup-" . now()->format('Y-m-d_H-i-s') . ".sql";
        $path = storage_path("app/backups/" . $filename);

        if (!is_dir(storage_path("app/backups"))) {
            mkdir(storage_path("app/backups"), 0755, true);
        }

        $driver = config('database.default');
        $conn = config("database.connections.{$driver}");

        if ($driver === 'mysql') {
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                $conn['username'],
                $conn['password'],
                $conn['host'],
                $conn['database'],
                $path
            );
        } elseif ($driver === 'pgsql') {
            $command = sprintf(
                'pg_dump --username=%s --host=%s --dbname=%s --file=%s',
                $conn['username'],
                $conn['host'],
                $conn['database'],
                $path
            );
            putenv("PGPASSWORD={$conn['password']}");
        } else {
            $this->error("Driver '{$driver}' no soportado para backup.");
            return 1;
        }

        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Respaldo generado exitosamente: {$filename}");
        } else {
            $this->error("Error al generar el respaldo.");
        }
    }
}
