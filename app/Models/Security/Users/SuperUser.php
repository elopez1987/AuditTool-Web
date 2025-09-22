<?php

namespace App\Models\Security\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SuperUser extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'securitysuperusers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'userid',
        'created_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (SuperUser $model) {
                $model->created_at = Carbon::now();
            });
        }
    }
}
