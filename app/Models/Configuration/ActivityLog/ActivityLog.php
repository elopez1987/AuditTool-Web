<?php

namespace App\Models\Configuration\ActivityLog;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_log';
    protected $dateFormat = 'Y-m-d H:i:s';


    protected $fillable = [
        'causer_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id', 'id')
            ->select('users.name', 'users.id');
    }

    public function scopeForDescription($query, $description)
    {
        if (trim($description) != '') {
            $query->where('description', $description);
        }
    }

    public function scopeForModel($query, $model)
    {
        if (trim($model) != '') {
            $query->where('activity_log.subject_type', $model);
        }
    }

    public function scopeForUser($query, $user)
    {
        if ($user != '') {
            $query->where('activity_log.causer_id', $user);
        }
    }

    public function scopeForFolio($query, $id)
    {
        if ($id != '') {
            $query->where('activity_log.id', $id);
        }
    }
    public function scopeForSubject($query, $subject_id)
    {
        if ($subject_id != '') {
            $query->where('activity_log.subject_id', $subject_id);
        }
    }

    public function scopeForDate($query, $date_in, $date_out)
    {
        if ($date_in != '' && $date_out != '') {
            $query->whereBetween('created_at', [$date_in, $date_out]);
        }
    }


}
