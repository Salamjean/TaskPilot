<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLog extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'content',
        'file_path',
        'linked_task_ids',
    ];

    protected $casts = [
        'date' => 'date',
        'linked_task_ids' => 'array',
    ];

    // ── Relations ──────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
