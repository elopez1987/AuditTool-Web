<?php

namespace App\Models\Configuration\TablesDataBase;

use Illuminate\Database\Eloquent\Model;

class SystemColumn extends Model
{
    public $timestamps = false;
    protected $table = 'systemcolumns';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tableid',
        'description',
        'shortdesc',
        'columnid',
    ];
}
