<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiLabel extends Model
{
    public $timestamps = false;

    protected $table = 'ui_labels';

    protected $fillable = [
        'module_name',
        'field_name',
        'label_text',
        'visibled',
        'type',
        'required',
    ];
}
