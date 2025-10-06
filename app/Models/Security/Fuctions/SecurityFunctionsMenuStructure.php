<?php

namespace App\Models\Security\Fuctions;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $menustrucid
 * @property int $functionid
 * @property int $moduleid
 * @property-read \App\Models\Security\Fuctions\SecurityFunction $funtion
 * @property-read SecurityModule $module
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure whereFunctionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure whereMenustrucid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunctionsMenuStructure whereModuleid($value)
 *
 * @mixin \Eloquent
 */
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
