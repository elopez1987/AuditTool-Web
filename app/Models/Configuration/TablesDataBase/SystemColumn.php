<?php

namespace App\Models\Configuration\TablesDataBase;

use Illuminate\Database\Eloquent\Model;

class SystemColumn extends Model
{

    protected $table = 'systemcolumns';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tableid',
        'description',
        'shortdesc',
        'columnid',
    ];


}
