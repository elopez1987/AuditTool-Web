<?php

namespace App\Models\Security\Users;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $username
 * @property int|null $sessionid
 * @property string|null $lastsessiondate
 * @property string|null $currentsessiondate
 * @property string|null $serialnumber
 * @property string|null $lastpasswordchange
 * @property int|null $statussession
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereCurrentsessiondate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereLastpasswordchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereLastsessiondate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereSessionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereStatussession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityUsersSession whereUsername($value)
 *
 * @mixin \Eloquent
 */
class SecurityUsersSession extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'securityuserssessions';

    protected $fillable = [
        'username', 'sessionid',
        'lastsessiondate', 'currentsessiondate',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
