<?php

namespace App\Console\Commands;

use App\Services\BrevoService;
use Illuminate\Console\Command;

class TestBrevoApi extends Command
{
    protected $signature = 'brevo:test {email? : Email to send the test to}';

    protected $description = 'Test Brevo API configuration by sending a test email';

    public function handle(BrevoService $brevoService): int
    {
        $this->info('=== Diagnóstico de Brevo API ===');
        $this->line('');

        $apiKey = $brevoService->getApiKeyMasked();
        $this->line("API Key: {$apiKey}");

        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        $this->line("Remitente: {$fromName} <{$fromAddress}>");

        $this->line('');

        if (!$brevoService->isConfigured()) {
            $this->error('✗ BREVO_API_KEY no está configurada o tiene un valor placeholder.');
            $this->line('');
            $this->line('SOLUCIÓN: Agrega tu API key real en .env:');
            $this->line('  BREVO_API_KEY=xkeysib-tu-api-key-real');
            $this->line('(la obtienes en https://app.brevo.com → API Keys)');
            return Command::FAILURE;
        }

        $this->info('✓ API key presente y con formato válido');
        $this->line('');

        $email = $this->argument('email') ?? $this->ask('Email para enviar la prueba');

        $html = '<h1>Prueba de envío - Sistema de Inscripciones UPEA</h1>';
        $html .= '<p>Si recibes este correo, la integración con Brevo API funciona correctamente.</p>';
        $html .= '<p><strong>Fecha:</strong> ' . now()->format('d/m/Y H:i:s') . '</p>';

        $this->line("Enviando a: {$email}...");
        $this->line('');

        $result = $brevoService->sendEmail($email, null, 'Prueba API Brevo - Sistema Inscripciones', $html);

        if ($result['success']) {
            $this->info('✓ CORREO ENVIADO EXITOSAMENTE');
            $this->line("  Message ID: {$result['message_id']}");
            return Command::SUCCESS;
        }

        $this->error('✗ ERROR AL ENVIAR');
        $this->line("  Código HTTP: {$result['code']}");
        $this->line("  Mensaje: {$result['error']}");
        $this->line('');

        if ($result['code'] === 401) {
            $this->line('CAUSA PROBABLE: La API key es inválida o expiró.');
            $this->line('SOLUCIÓN: Generá una nueva en https://app.brevo.com → API Keys');
        } elseif ($result['code'] === 400 && str_contains($result['error'], 'sender')) {
            $this->line('CAUSA PROBABLE: El remitente no está verificado en Brevo.');
            $this->line('SOLUCIÓN: Verificá ' . config('mail.from.address') . ' en https://app.brevo.com → Senders');
        } elseif ($result['code'] === 0) {
            $this->line('CAUSA PROBABLE: Error de red o DNS.');
            $this->line('Verificá que tu servidor pueda alcanzar api.brevo.com');
        }

        $this->line('');
        $this->line("Log completo en storage/logs/laravel.log");

        return Command::FAILURE;
    }
}
