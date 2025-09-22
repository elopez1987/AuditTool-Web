<?php

namespace App\Models\Security\Users;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
