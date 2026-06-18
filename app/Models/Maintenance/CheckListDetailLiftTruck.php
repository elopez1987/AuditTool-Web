<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $checklisttruckid
 * @property int $checklistid
 * @property int $checklistdetailid
 * @property string $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $note
 * @property string $created_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereChecklistdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereChecklisttruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListDetailLiftTruck whereValue04($value)
 *
 * @mixin \Eloquent
 */
class CheckListDetailLiftTruck extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'checklistlifttruckdetails';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'checklisttruckid',
        'checklistid',
        'checklistdetailid',
        'note',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'created_at',
    ];

    protected $hidden = [
        'schemaid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
