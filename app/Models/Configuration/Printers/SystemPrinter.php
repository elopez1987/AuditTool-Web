<?php

namespace App\Models\Configuration\Printers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $centerid
 * @property string|null $warehouseid
 * @property int $printerid
 * @property string $description
 * @property string $ip
 * @property string $command
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read SystemCenterDistribution|null $center
 * @property-read SystemStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter wherePrinterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemPrinter whereWarehouseid($value)
 * @mixin \Eloquent
 */
class SystemPrinter extends Model
{
    public $timestamps = false;
    protected $table = 'systemprinters';
    protected $keyType = 'integer';
    protected $primaryKey = 'printerid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'description',
        'ip',
        'command',
        'configurationflag',
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
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return SystemPrinter::whereIn('systemprinters.centerid', $centers);
    }
}
