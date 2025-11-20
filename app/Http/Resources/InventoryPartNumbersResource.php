<?php

namespace App\Http\Resources;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InventoryPartnumber */
class InventoryPartNumbersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prtnum' => $this->prtnum,
            'lngdsc' => $this->lngdsc,
            'untcas' => $this->untcas,
            'palhgt' => $this->palhgt,
            'abccod' => $this->abccod,
            'shelflife' => $this->shelflife,
            'untpal' => $this->untpal,

            //			'configurationflag' => $this->configurationflag,
            //			'palnetwgt' => $this->palnetwgt,
            //			'palgrswgt' => $this->palgrswgt,
            //			'caslvl' => $this->caslvl,
            //			'palwid' => $this->palwid,
            //			'pallen' => $this->pallen,
            //			'barcode' => $this->barcode,
            //			'codeean' => $this->codeean,
            //			'codedun' => $this->codedun,
            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'auxiliary04' => $this->auxiliary04,
            //			'auxiliary05' => $this->auxiliary05,
            //			'schemaid' => $this->schemaid,
            //			'created_at' => $this->created_at,
            //			'updated_at' => $this->updated_at,
            //			'activities_count' => $this->activities_count,
            //
            //			'centerid' => $this->centerid,
            //			'warehouseid' => $this->warehouseid,
            //			'category01id' => $this->category01id,
            //			'category02id' => $this->category02id,
            //			'stkuom' => $this->stkuom,
            //			'statusid' => $this->statusid,
        ];
    }
}
