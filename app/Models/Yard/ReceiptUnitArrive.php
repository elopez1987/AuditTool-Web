<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;

class ReceiptUnitArrive extends Model
{
    public $timestamps = false;
    protected $table = 'storagereceiptunitarrive';

    protected $fillable = ['token', 'startdate', 'closedate', 'value01', 'value02', 'value03', 'value04'];



}
