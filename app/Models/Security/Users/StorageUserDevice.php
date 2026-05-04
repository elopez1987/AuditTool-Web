<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $username
 * @property string $serialnumber
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice forSerialNumber($serialnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUserDevice whereValue05($value)
 *
 * @mixin \Eloquent
 */
class StorageUserDevice extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'securitydeviceforuser';

    protected $fillable = [
        'id',
        'username',
        'serialnumber',
        'created_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function scopeForSerialNumber($query, $serialnumber)
    {
        if (trim($serialnumber) != '') {
            $query->where('securitydeviceforuser.serialnumber', $serialnumber);
        }
    }
}
