<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CheckListDetailLiftTruck extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected $table = 'checklistlifttruckdetails';
    protected $primaryKey = 'id';

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
        'created_at',
    ];

    protected $hidden = [
        'schemaid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
