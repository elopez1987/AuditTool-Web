<?php

namespace App\Models\Configuration\ActivityLog;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $properties
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $event
 * @property string|null $batch_uuid
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forDate($date_in, $date_out)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forDescription($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forFolio($id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forModel($model)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forSubject($subject_id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forUser($user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ActivityLog extends Model
{
    public $timestamps = false;

    protected $table = 'activity_log';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'causer_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id', 'id')
            ->select('users.name', 'users.id');
    }

    public function scopeForDescription($query, $description)
    {
        if (trim($description) !== '') {
            $query->where('description', $description);
        }
    }

    public function scopeForModel($query, $model)
    {
        if (trim($model) !== '') {
            $query->where('activity_log.subject_type', $model);
        }
    }

    public function scopeForUser($query, $user)
    {
        if ($user !== '') {
            $query->where('activity_log.causer_id', $user);
        }
    }

    public function scopeForFolio($query, $id)
    {
        if ($id !== '') {
            $query->where('activity_log.id', $id);
        }
    }

    public function scopeForSubject($query, $subject_id)
    {
        if ($subject_id !== '') {
            $query->where('activity_log.subject_id', $subject_id);
        }
    }

    public function scopeForDate($query, $date_in, $date_out)
    {
        if ($date_in !== '' && $date_out !== '') {
            $query->whereBetween('created_at', [$date_in, $date_out]);
        }
    }
}
