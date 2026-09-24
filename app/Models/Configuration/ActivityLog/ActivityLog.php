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
        'causer_id',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id', 'id');
    }
}
