<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageLocation extends Model
{
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagelocations';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'zoneid',
        'name',
        'locationid',
        'barcode',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageLocation $model) {
                $model->barcode = rand(100000, 999999);
            });
            self::updating(function (StorageLocation $model) {});
        }
    }

    public static function controlAccessUser() {}

    //    Relaciones
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id')
            ->withDefault(['name' => ' ']);
    }

    //    Filtros Scopes
    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('storagelocations.name', 'like', "%$name%");
        }
    }
}
