<?php

namespace App\Models\General;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EvidencePhoto extends Model
{
    use LogsActivity;

    protected $table = 'storageevidencephotos';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'model_type',
        'model_id',
        'path',
        'createuser',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    //    Funciones auxiliares
    public static function getPhotosForModel($id, $model)
    {
        return EvidencePhoto::where('model_id', $id)->where('model_type', '=', $model)->get();
    }

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (EvidencePhoto $model) {
                $model->createuser = Auth::id();
            });
        }
    }
}
