<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BrevoService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key');
        $this->apiUrl = 'https://api.brevo.com/v3/smtp/email';
    }

    public function sendEmail(string $toEmail, ?string $toName, string $subject, string $htmlContent, ?array $attachments = null): array
    {
        $payload = [
            'sender' => [
                'email' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName ?? $toEmail,
                ],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        if ($attachments) {
            $payload['attachment'] = $attachments;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $body = $response->json();
                return [
                    'success' => true,
                    'message_id' => $body['messageId'] ?? null,
                    'code' => $response->status(),
                ];
            }

            $errorBody = $response->body();
            $errorJson = $response->json();
            $brevoMessage = $errorJson['message'] ?? ($errorJson['error'] ?? $errorBody);

            Log::error("Brevo API error [{$response->status()}] {$brevoMessage}");

            if ($response->status() === 401) {
                Log::error("→ La BREVO_API_KEY en .env es inválida o ha expirado.");
            } elseif ($response->status() === 400 && str_contains($brevoMessage, 'sender')) {
                Log::error("→ El remitente '" . config('mail.from.address') . "' no está verificado en Brevo.");
            }

            return [
                'success' => false,
                'error' => $brevoMessage,
                'code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error("Brevo API exception: " . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => 0,
            ];
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'xkeysib-xxxxxxxxxxxxxxxxxxxxxxxxx';
    }

    public function getApiKeyMasked(): string
    {
        if (empty($this->apiKey)) return '(vacio)';
        if (strlen($this->apiKey) < 10) return '(invalido)';
        return substr($this->apiKey, 0, 8) . '...' . substr($this->apiKey, -4);
    }
}
