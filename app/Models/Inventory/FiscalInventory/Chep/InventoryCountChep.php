<?php

namespace App\Models\Inventory\FiscalInventory\Chep;

use Illuminate\Database\Eloquent\Model;

class InventoryCountChep extends Model
{

    protected $table = 'inventorycountchep';
    protected $primaryKey = 'recordid';

    protected $fillable = [
      'centerid','warehouseid','inventoryid','locationid','typeid',
        'quantity', 'auxiliary01', 'auxiliary02',
        'auxiliary03', 'auxiliary04', 'auxiliary05',
    ];


}
