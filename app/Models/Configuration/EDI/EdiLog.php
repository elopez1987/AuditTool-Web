<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;

class EdiLog extends Model
{

    protected $table = 'logimports';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType= 'integer';


    protected $fillable = [
        "message",
        "created_at",
    ];


}
