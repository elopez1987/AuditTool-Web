<?php

namespace App\Traits\Api\OutPutUnit;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;

trait CreateReceiptToWarehouse
{
    public function createReceiptToWarehouse($receiptunit,$parameterWarehouse)
    {
        if (!empty($parameterWarehouse) && in_array('SWH-15', (array)$parameterWarehouse)) {
            $customerWarehouse = SystemDetailParameter::where('parameterid', '=', 'RECEIPT-PLANT')
                ->where('value01', $receiptunit->centerid)
                ->where('value02', '=', $receiptunit->warehouseid)->first();
            if (empty($customerWarehouse)) {
                return $this->getResponseWithErrors('', 'El centro de distribución no cuenta con cliente para insertar el recibo.');
            }
            $this->registerNewReceiptInWarehouse($receiptunit, $customerWarehouse);
        }
    }


    /**
     * @param ReceiptUnit $receiptunit
     * @param SystemDetailParameter $customerWarehouse
     * @return void
     */
    public function registerNewReceiptInWarehouse(ReceiptUnit $receiptunit, SystemDetailParameter $customerWarehouse): void
    {
        $receiptunitClose = ReceiptUnit::where('id', $receiptunit->id)->first();
        ReceiptAsn::create([
            'centerid' => $customerWarehouse->value03,
            'warehouseid' => $customerWarehouse->value04,
            'shipmentid' => $receiptunitClose->shipmentid,
            'addasndate' => Carbon::now(),
            'workday' => Carbon::now()->toDateString(),
            'providerid' => $customerWarehouse->value05,
            'carrierid' => $receiptunitClose->carrierid,
            'razonstatusid' => '18',
            'stockcategoryid' => '1',
            'statusid' => 'TA',
            'turnid' => '1',
            'containerseal' => $receiptunitClose->containerseal,
            'operatorname' => $receiptunitClose->operatorname,
            'auxiliary06' => $receiptunitClose->id
        ]);
    }
}
