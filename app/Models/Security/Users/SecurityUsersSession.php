<?php

namespace App\Models\Security\Users;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SecurityUsersSession extends Model
{
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
