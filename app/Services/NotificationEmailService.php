<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Mail\Mailable;
use Exception;

class NotificationEmailService
{
    private BrevoService $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    public function send($recipient, Mailable $mailable, string $eventType, ?int $responsibleId = null): bool
    {
        $user = null;
        $email = $recipient;

        if ($recipient instanceof User) {
            $user = $recipient;
            $email = $user->email;

            if (!$user->email_notifications) {
                return false;
            }
        }

        if (!$this->brevoService->isConfigured()) {
            Log::error("Brevo API key not configured. Cannot send email.");
            $this->logEmail($email, $eventType, 'fallido', 'API key no configurada', $responsibleId);
            return false;
        }

        try {
            // Build the mailable to populate subject and view
            $mailable->build();

            $subject = $mailable->subject ?? 'Sin asunto';

            $viewData = $mailable->buildViewData();

            $htmlContent = View::make($mailable->view, $viewData)->render();

            $recipientName = $user ? $user->name : null;

            $result = $this->brevoService->sendEmail(
                $email,
                $recipientName,
                $subject,
                $htmlContent
            );

            if ($result['success']) {
                $this->logEmail($email, $subject, $eventType, 'enviado', null, $responsibleId);
                return true;
            }

            Log::error("Brevo send failed ($eventType) to $email: " . ($result['error'] ?? 'Unknown'));
            $this->logEmail($email, $subject, $eventType, 'fallido', $result['error'] ?? 'Error desconocido', $responsibleId);
            return false;

        } catch (Exception $e) {
            Log::error("Email error ($eventType) to $email: " . $e->getMessage());
            $subject = isset($mailable) && $mailable->subject ? $mailable->subject : $eventType;
            $this->logEmail($email, $subject, $eventType, 'fallido', $e->getMessage(), $responsibleId);
            return false;
        }
    }

    public function sendRaw(string $email, ?string $recipientName, string $subject, string $htmlContent, string $eventType, ?int $responsibleId = null): bool
    {
        if (!$this->brevoService->isConfigured()) {
            Log::error("Brevo API key not configured.");
            $this->logEmail($email, $subject, $eventType, 'fallido', 'API key no configurada', $responsibleId);
            return false;
        }

        try {
            $result = $this->brevoService->sendEmail(
                $email,
                $recipientName,
                $subject,
                $htmlContent
            );

            if ($result['success']) {
                $this->logEmail($email, $subject, $eventType, 'enviado', null, $responsibleId);
                return true;
            }

            Log::error("Brevo raw send failed ($eventType) to $email: " . ($result['error'] ?? 'Unknown'));
            $this->logEmail($email, $subject, $eventType, 'fallido', $result['error'] ?? 'Error desconocido', $responsibleId);
            return false;

        } catch (Exception $e) {
            Log::error("Email raw error ($eventType) to $email: " . $e->getMessage());
            $this->logEmail($email, $subject, $eventType, 'fallido', $e->getMessage(), $responsibleId);
            return false;
        }
    }

    private function logEmail(string $recipient, string $subject, string $eventType, string $status, ?string $error = null, ?int $responsibleId = null): void
    {
        try {
            EmailLog::create([
                'destinatario' => $recipient,
                'asunto' => $subject,
                'tipo_evento' => $eventType,
                'estado_envio' => $status,
                'mensaje_error' => $error,
                'usuario_responsable_id' => $responsibleId,
            ]);
        } catch (Exception $e) {
            Log::error("Could not log email dispatch: " . $e->getMessage());
        }
    }
}
