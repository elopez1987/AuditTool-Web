<?php

namespace App\Models\Security\MenuStructure;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $menustrucid
 * @property int $moduleid
 * @property int $id
 * @property-read SecurityModule $module
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure whereMenustrucid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityDetailsMenuStructure whereModuleid($value)
 *
 * @mixin \Eloquent
 */
class SecurityDetailsMenuStructure extends Model
{
    public $timestamps = false;

    protected $table = 'securitydetailsmenustructure';

    protected $primaryKey = 'id';

    protected $fillable = [
        'menustrucid',
        'moduleid',
    ];

    public function module()
    {
        return $this->belongsTo(SecurityModule::class, 'moduleid', 'moduleid');
    }
}
