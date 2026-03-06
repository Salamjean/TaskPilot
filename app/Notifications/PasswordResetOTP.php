<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOTP extends Notification
{
    use Queueable;

    public $code;
    public $email;

    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Code de réinitialisation de mot de passe')
            ->from('contact@maelysimo.com', 'TaskPilot')
            ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
            ->line('Votre code de vérification est : ' . $this->code)
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune action supplémentaire n\'est requise.');
    }
}
