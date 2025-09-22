<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentTruckCategory extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];
    protected $table = 'shipmenttruckcategories';
    protected $primaryKey = 'truckcategoryid';
    protected $keyType = 'string';

    protected $fillable = [
        'truckcategoryid',
        'description',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('statusid', '=', $statusid);
        }
    }
}
