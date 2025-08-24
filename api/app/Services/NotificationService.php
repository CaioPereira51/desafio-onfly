<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification when pedido status is updated
     *
     * @param Pedido $pedido
     * @param string $statusAnterior
     * @param string $novoStatus
     * @return void
     */
    public function sendStatusUpdateNotification(Pedido $pedido, string $statusAnterior, string $novoStatus)
    {
        try {
            $solicitante = $pedido->solicitante;
            
            // Preparar dados para o email
            $data = [
                'nome' => $solicitante->name,
                'destino' => $pedido->destino,
                'data_ida' => $pedido->data_ida->format('d/m/Y'),
                'data_volta' => $pedido->data_volta->format('d/m/Y'),
                'status_anterior' => $this->getStatusLabel($statusAnterior),
                'novo_status' => $this->getStatusLabel($novoStatus),
                'pedido_id' => $pedido->id,
            ];

            // Enviar email (simulado com MailHog)
            Mail::send('emails.status-update', $data, function ($message) use ($solicitante, $novoStatus) {
                $message->to($solicitante->email, $solicitante->name)
                        ->subject('Atualização do Status do Pedido de Viagem');
            });

            // Log da notificação
            Log::info('Notificação de status enviada', [
                'pedido_id' => $pedido->id,
                'solicitante_email' => $solicitante->email,
                'status_anterior' => $statusAnterior,
                'novo_status' => $novoStatus,
            ]);

        } catch (\Illuminate\Mail\Transport\TransportException $e) {
            Log::error('Erro de transporte ao enviar notificação de status', [
                'pedido_id' => $pedido->id,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao enviar notificação de status', [
                'pedido_id' => $pedido->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Get human readable status label
     *
     * @param string $status
     * @return string
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'solicitado' => 'Solicitado',
            'aprovado' => 'Aprovado',
            'cancelado' => 'Cancelado',
        ];

        return $labels[$status] ?? $status;
    }
}
