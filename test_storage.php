<?php

try {
    Storage::disk('r2')->put('test.txt', 'Hola desde Supabase Storage');
    echo "FUNCIONA: el archivo se guardo correctamente\n";
} catch (\Throwable $e) {
    echo "ERROR PRINCIPAL: " . $e->getMessage() . "\n\n";

    $previous = $e->getPrevious();
    $level = 1;
    while ($previous) {
        echo "CAUSA #{$level}: " . get_class($previous) . "\n";
        echo "MENSAJE: " . $previous->getMessage() . "\n\n";
        $previous = $previous->getPrevious();
        $level++;
    }
}

echo "--- CONFIGURACION ACTUAL DEL DISCO r2 ---\n";
print_r(config('filesystems.disks.r2'));