<?php

namespace App\Models\Security\Users;

use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SecurityAccessCustomer extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'securityaccesscustomer';

    protected $fillable = [
        'centerid', 'customerid', 'username'
    ];

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id');
    }

}
