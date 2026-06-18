<?php

namespace App\Models\Security\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $activity_type
 * @property string|null $details
 * @property string $severity
 * @property bool $reviewed
 * @property string|null $reviewed_at
 * @property int|null $reviewed_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $reviewedBy
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereReviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity whereUserId($value)
 *
 * @mixin \Eloquent
 */
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
