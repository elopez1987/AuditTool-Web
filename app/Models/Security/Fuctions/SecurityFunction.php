<?php

namespace App\Models\Security\Fuctions;

use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $moduleid
 * @property int $functionid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $auxiliary06
 * @property string|null $auxiliary07
 * @property string|null $auxiliary08
 * @property string|null $auxiliary09
 * @property string|null $auxiliary10
 * @property-read SecurityModule|null $module
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction description($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction module($moduleid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereFunctionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityFunction whereModuleid($value)
 *
 * @mixin \Eloquent
 */
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
