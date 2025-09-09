<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptUnitArrive extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;
    protected $table = 'storagereceiptunitarrive';

    protected $fillable = ['token', 'startdate', 'closedate', 'value01', 'value02', 'value03', 'value04'];



}
