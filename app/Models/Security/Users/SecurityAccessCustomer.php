<?php

namespace App\Models\Security\Users;

use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $customerid
 * @property string $username
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read OrderCustomer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessCustomer whereUsername($value)
 * @mixin \Eloquent
 */
class SecurityAccessCustomer extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'securityaccesscustomer';

    protected $fillable = [
        'centerid', 'customerid', 'username',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id');
    }
}
