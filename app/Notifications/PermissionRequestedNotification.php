<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PermissionRequestedNotification extends Notification
{
    use Queueable;

    public $permission;
    public $user;

    public function __construct($permission, $user)
    {
        $this->permission = $permission;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'permission_requested',
            'title' => 'Nouvelle demande de permission',
            'message' => $this->user->prenom . ' ' . $this->user->name . ' a soumis une nouvelle demande de permission (' . $this->permission->type . ').',
            'link' => route('admin.permissions.index'),
        ];
    }
}
