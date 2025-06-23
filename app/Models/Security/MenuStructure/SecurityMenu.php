<?php

namespace App\Models\Security\MenuStructure;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Fuctions\SecurityFunctionsMenuStructure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class SecurityMenu extends Model
{
    public $timestamps = false;
    protected $table = 'securitymenustructure';
    protected $primaryKey = 'menustrucid';
    protected $keyType = 'string';


    protected $fillable = [
        'centerid',
        'menustrucid',
        'description',
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

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return SecurityMenu::whereIn('securitymenustructure.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function moduleDetails()
    {
        return $this->hasMany(SecurityDetailsMenuStructure::class, 'menustrucid', 'menustrucid');
    }

    public function functiondDetails()
    {
        return $this->hasMany(SecurityFunctionsMenuStructure::class, 'menustrucid', 'menustrucid');
    }

}
