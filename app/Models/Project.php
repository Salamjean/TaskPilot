<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'type',
        'company',
        'logo',
        'description',
        'status',
        'created_by',
    ];

    /**
     * Admin qui a créé le projet.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Membres de l'équipe affectés au projet.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    /**
     * Tâches du projet.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Label lisible du statut.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'actif' => 'Actif',
            'en_pause' => 'En pause',
            'termine' => 'Terminé',
            default => $this->status,
        };
    }

    /**
     * Couleur badge du statut.
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'actif' => '#059669',
            'en_pause' => '#D97706',
            'termine' => '#6B7280',
            default => '#6B7280',
        };
    }
}
