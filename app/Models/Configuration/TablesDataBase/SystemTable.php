<?php

namespace App\Models\Configuration\TablesDataBase;

use Illuminate\Database\Eloquent\Model;

class SystemTable extends Model
{
    protected $table = 'systemtables';
    protected $keyType = 'string';
    protected $primaryKey = 'tableid';
    public $incrementing = false;

    protected $fillable = [
        'tableid',
        'description',
        'notes',
    ];

    protected $attributes = [
        'description' => '0',
        'notes' => '0',
    ];


    public function scopeForName($query, $tableid)
    {
        if (trim($tableid) != "") {
            $query->where('systemtables.tableid', 'like', "%$tableid%");
        }
    }

}
