<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CheckListEvidenceProcessUnit extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

//    Tabla para guardar las fotos de proceso de carga o descarga mediante check list
    public $timestamps = true;
    protected $table = 'storagecheckListevidenceprocessunit';
    protected $primaryKey = 'id';


    protected $fillable = [
        'centerid', 'modelid', 'modeltype', 'description', 'path', 'statusid',
        'value01', 'value02', 'value03', 'value04', 'value05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'value01', 'username')->withTrashed();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return CheckListEvidenceProcessUnit::whereIn('storagecheckListevidenceprocessunit.centerid', $centers);
    }


}
