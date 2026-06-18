<?php

namespace App\Models\SafetyTours\Incidents;

use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StoragePhotosIncidents extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storage_photos_incidents';

    public $timestamps = false;

    protected $fillable = [
        'storage_incidence_safety_id',
        'path',
        'create_by',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StoragePhotosIncidents $model) {
                $model->create_by = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }

    public function storageincidencesafety(): BelongsTo
    {
        return $this->belongsTo(StorageIncidenceSafety::class, 'storage_incidence_safety_id', 'id');
    }

    public function createuser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d-m-Y H:i',
        ];
    }
}
