<?php

namespace App\Models\Configuration\Parameters;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemParameter extends Model
{
    use SoftDeletes;

    public $incrementing = true;

    protected $table = 'systemparameters';

    protected $primaryKey = 'id';

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
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
