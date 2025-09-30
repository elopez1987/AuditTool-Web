<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $modelid
 * @property string $modeltype
 * @property string $description
 * @property string|null $path
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereModeltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListEvidenceProcessUnit whereValue05($value)
 * @mixin \Eloquent
 */
class CheckListEvidenceProcessUnit extends Model
{
    use LogsActivity;

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
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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
