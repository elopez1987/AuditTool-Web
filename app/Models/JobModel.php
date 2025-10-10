<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    public $timestamps = false;

    protected $table = 'jobs';

    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
        'created_at',
    ];
}
