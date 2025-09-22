<?php

namespace App\Models\Configuration\NameSystem;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static get()
 */
class NameSystem extends Model
{
    protected $table = 'systemname';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'version',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
