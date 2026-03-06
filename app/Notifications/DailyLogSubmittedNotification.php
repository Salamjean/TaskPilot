<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyLogSubmittedNotification extends Notification
{
    use Queueable;

    public $log;
    public $user; // Le personnel qui a soumis le rapport

    /**
     * Create a new notification instance.
     */
    public function __construct($log, $user)
    {
        $this->log = $log;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'daily_log_submitted',
            'title' => 'Nouveau rapport journalier',
            'message' => $this->user->prenom . ' ' . $this->user->name . ' a soumis son rapport du ' . \Carbon\Carbon::parse($this->log->date)->format('d/m/Y') . '.',
            'link' => route('admin.daily-logs.index', ['user_id' => $this->user->id, 'date' => $this->log->date]),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
