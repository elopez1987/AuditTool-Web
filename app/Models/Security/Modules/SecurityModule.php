<?php

namespace App\Models\Security\Modules;

use App\Models\Security\Fuctions\SecurityFunction;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $moduleid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read SecurityFunction|null $securityfunction
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule description($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityModule whereModuleid($value)
 *
 * @mixin \Eloquent
 */
class SecurityModule extends Model
{
    public $timestamps = false;

    public $incrementing = true;

    protected $table = 'securitymodules';

    protected $primaryKey = 'moduleid';

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
            $query->where('description', 'like', "%{$description}%");
        }
    }

    public function securityfunction()
    {
        return $this->belongsTo(SecurityFunction::class, 'moduleid', 'moduleid');
    }
}
