<?php

namespace App\Models\Configuration\Mails;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $id)
 * @method static create(array $array)
 * @property string $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $mailid
 * @property string $name
 * @property string $email
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $configurationflag
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereMailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailMail whereWarehouseid($value)
 * @mixin \Eloquent
 */
class SystemDetailMail extends Model
{
    public $keyType = 'string';
    protected static $logAttributes = ['*'];
    protected $table = 'systemdetailmails';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'mailid',
        'name',
        'email',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return SystemDetailMail::whereIn('systemdetailmails.warehouseid', $warehouses)
            ->whereIn('systemdetailmails.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
