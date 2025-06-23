<?php

namespace App\Models\Configuration\Parameters;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemParameter extends Model
{
    public $incrementing = false;
    protected $table = 'systemparameters';
    protected $primaryKey = 'parameterid';
    protected $dateFormat = 'Y-m-d H:i:s';
    public $keyType = 'string';
    use SoftDeletes;


    protected $fillable = [
        'parameterid',
        'name',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS'
    ];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function detailparameter()
    {
        return $this->hasMany(SystemDetailParameter::class, 'parameterid', 'parameterid');
    }

    public function scopeForName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('systemparameters.name', 'like', "%$name%");
        }
    }

    public function scopeForParameter($query, $parameterid)
    {
        if (trim($parameterid) != '') {
            $query->where('systemparameters.parameterid', 'like', "%$parameterid%");
        }
    }


}
