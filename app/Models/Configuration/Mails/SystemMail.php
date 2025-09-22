<?php

namespace App\Models\Configuration\Mails;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed mailid
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
