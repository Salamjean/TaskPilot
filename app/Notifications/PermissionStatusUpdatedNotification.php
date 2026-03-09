<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PermissionStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $statusLabel = $this->permission->status == 'approved' ? 'Approuvée' : 'Rejetée';

        return [
            'type' => 'permission_status_updated',
            'title' => 'Statut de votre demande de permission mis à jour',
            'message' => 'Votre demande de permission (' . $this->permission->type . ') a été ' . $statusLabel . '.',
            'link' => route('personnel.permissions.index'),
        ];
    }
}
