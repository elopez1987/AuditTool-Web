<?php

namespace App\Models\Security\Roles;

use Illuminate\Database\Eloquent\Model;

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
