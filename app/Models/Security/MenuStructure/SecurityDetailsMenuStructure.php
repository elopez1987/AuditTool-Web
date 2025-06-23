<?php

namespace App\Models\Security\MenuStructure;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

class SecurityDetailsMenuStructure extends Model
{
    public $timestamps = false;
    protected $table = 'securitydetailsmenustructure';
    protected $primaryKey = 'id';

    protected $fillable = [
        'menustrucid',
        'moduleid'
    ];

    public function module()
    {
        return $this->belongsTo(SecurityModule::class, 'moduleid', 'moduleid');
    }


}
