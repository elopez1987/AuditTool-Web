<?php

namespace App\Models\Security\Modules;

use App\Models\Security\Fuctions\SecurityFunction;
use Illuminate\Database\Eloquent\Model;

class SecurityModule extends Model
{
    protected $table = 'securitymodules';
    public $timestamps = false;
    protected $primaryKey = 'moduleid';
    public $incrementing = true;
    protected $keyType = 'integer';

    protected $fillable = [
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

    public function scopeDescription($query, $description)
    {
        if (trim($description) != '') {
            $query->where('description', 'like', "%$description%");
        }
    }

    public function securityfunction()
    {
        return $this->belongsTo(SecurityFunction::class, 'moduleid', 'moduleid');
    }

}
