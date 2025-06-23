<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class CheckListDetailLiftTruck extends Model
{
    protected $table = 'checklistlifttruckdetails';
    protected $primaryKey = 'id';
    public $timestamps = false;


    protected $fillable = [
        'centerid',
        'serialnumber',
        'checklisttruckid',
        'checklistid',
        'checklistdetailid',
        'note',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'created_at'
    ];

    protected $hidden = [
        'schemaid'
    ];


}
