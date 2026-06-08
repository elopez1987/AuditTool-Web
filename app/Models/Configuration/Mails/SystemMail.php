<?php

namespace App\Models\Configuration\Mails;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property mixed mailid
 * @property string $mailid
 * @property string $name
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $configurationflag
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, SystemDetailMail> $maildetails
 * @property-read int|null $maildetails_count
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereMailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemMail whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SystemMail extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected static $logAttributes = ['*'];

    protected $table = 'systemmails';

    protected $primaryKey = 'mailid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'mailid',
        'name',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function maildetails(): HasMany
    {
        return $this->hasMany(SystemDetailMail::class, 'mailid', 'mailid');
    }
}
