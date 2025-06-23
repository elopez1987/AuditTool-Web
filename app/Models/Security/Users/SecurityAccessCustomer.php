<?php

namespace App\Models\Security\Users;

use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Database\Eloquent\Model;

class SecurityAccessCustomer extends Model
{
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
