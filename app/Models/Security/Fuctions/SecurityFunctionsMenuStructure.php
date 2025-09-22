<?php

namespace App\Models\Security\Fuctions;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

class SecurityFunctionsMenuStructure extends Model
{
    public $timestamps = false;
    protected $table = 'securityfunctionsmenustructure';
    protected $primaryKey = 'id';

    protected $fillable = [
        'menustrucid',
        'functionid',
        'moduleid',
    ];

    public function funtion()
    {
        return $this->belongsTo(SecurityFunction::class, 'functionid', 'functionid');
    }

    public function module()
    {
        return $this->belongsTo(SecurityModule::class, 'moduleid', 'moduleid');
    }
}
