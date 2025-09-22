<?php

namespace App\Models\Security\Fuctions;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

class SecurityFunction extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $table = 'securityfunctions';
    protected $primaryKey = 'functionid';

    protected $fillable = [
        'moduleid',
        'description',
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
    ];

    public function module()
    {
        return $this->belongsTo(SecurityModule::class, 'moduleid', 'moduleid');
    }

    public function scopeDescription($query, $description)
    {
        if (trim($description) !== '') {
            $query->where('securityfunctions.description', 'like', "%{$description}%");
        }
    }

    public function scopeModule($query, $moduleid)
    {
        if (trim($moduleid) !== '') {
            $query->where('securityfunctions.moduleid', '=', $moduleid);
        }
    }
}
