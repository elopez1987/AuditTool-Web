<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $token
 * @property string $startdate
 * @property string|null $closedate
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereClosedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitArrive whereValue04($value)
 *
 * @mixin \Eloquent
 */
class ReceiptUnitArrive extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagereceiptunitarrive';

    protected $fillable = ['token', 'startdate', 'closedate', 'value01', 'value02', 'value03', 'value04'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
