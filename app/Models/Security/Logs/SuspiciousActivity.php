<?php

namespace App\Models\Security\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspiciousActivity extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'details',
        'severity',
        'reviewed',
        'reviewed_at',
        'reviewed_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected function casts(): array
    {
        return [
            'reviewed' => 'boolean',
        ];
    }
}
