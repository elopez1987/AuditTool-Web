<?php

namespace App\Models\Security\Users;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SecurityUsersSession extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'securityuserssessions';
    public $timestamps = false;

    protected $fillable = [
        'username', 'sessionid',
        'lastsessiondate', 'currentsessiondate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

}
