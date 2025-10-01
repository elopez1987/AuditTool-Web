<?php

namespace App\Models\Security\Roles;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $centerid
 * @property int $roleid
 * @property int $categoryincid
 * @property int $categoryincdetailid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereCategoryincdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereCategoryincid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereRoleid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryRole whereValue03($value)
 * @mixin \Eloquent
 */
class StorageCategoryRole extends Model
{
    public $timestamps = false;
    protected $table = 'storagecategoryrole';

    protected $fillable = [
        'centerid',
        'roleid',
        'categoryincid',
        'categoryincdetailid',
        'value01',
        'value02',
        'value03 ',
    ];
}
