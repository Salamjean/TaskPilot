<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'status',
        'is_verified',
        'priority',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Helpers ────────────────────────────────────────────────────

    /**
     * Vérifie si la tâche est en retard.
     */
    public function isLate(): bool
    {
        if (!$this->due_date || $this->status === 'termine') {
            return false;
        }

        // Le retard est effectif après 17h00 le jour de l'échéance
        return now()->isAfter($this->due_date->copy()->setTime(17, 0, 0));
    }

    /**
     * Statut effectif (en tenant compte du retard automatique).
     */
    public function effectiveStatus(): string
    {
        if ($this->isLate())
            return 'en_retard';
        return $this->status;
    }

    /**
     * Couleur CSS selon le statut effectif.
     */
    public function statusColor(): string
    {
        return match ($this->effectiveStatus()) {
            'termine' => '#059669',  // vert
            'en_cours' => '#2563EB',  // bleu
            'a_faire' => '#D97706',  // ambre
            'en_retard' => '#DC2626',  // rouge
            default => '#6B7280',
        };
    }

    /**
     * Libellé lisible du statut.
     */
    public function statusLabel(): string
    {
        return match ($this->effectiveStatus()) {
            'termine' => 'Terminé',
            'en_cours' => 'En cours',
            'a_faire' => 'À faire',
            'en_retard' => 'En retard',
            default => $this->status,
        };
    }

    /**
     * Couleur CSS de la priorité.
     */
    public function priorityColor(): string
    {
        return match ($this->priority) {
            'urgente' => '#DC2626',
            'haute' => '#D97706',
            'normale' => '#2563EB',
            'faible' => '#6B7280',
            default => '#6B7280',
        };
    }

    /**
     * Libellé lisible de la priorité.
     */
    public function priorityLabel(): string
    {
        return match ($this->priority) {
            'urgente' => 'Urgente',
            'haute' => 'Haute',
            'normale' => 'Normale',
            'faible' => 'Faible',
            default => $this->priority,
        };
    }
}
