<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageUnitStatus extends Model
{
    protected $table = 'storageunitstatus';
    protected $dateFormat = 'Y-m-d H:i:s';
    use SoftDeletes;


    protected $fillable = [
        'name',
        'unitstatusid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];


    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('storageunitstatus.name', 'like', "%$name%");
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) != "") {
            $query->where('storageunitstatus.statusid', '=', $statusid);
        }
    }

}
