<?php

namespace App\Console\Commands\HENKELTOL01;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Traits\Web\General\GetParameters;
use Illuminate\Console\Command;

class ClearLocationCommand extends Command
{
    use GetParameters;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:clearlocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza la limpieza de ubicaciones colgadas en el proceso de autorizacion de salida';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $centers = SystemCenterDistribution::select(['centerid', 'configurationflag'])->get();
        foreach ($centers as $center) {
            $centerid = $center->centerid;
            $parameter = $center->configurationflag;
            if (! empty($parameter) && in_array('SDC-08', $parameter)) {
                StorageLocation::where('centerid', $centerid)->update(['statusid' => 'E', 'auxiliary01' => '']);
                $locations = StorageLocation::where('centerid', $centerid)->select(['id', 'auxiliary01', 'statusid'])->get();
                $units = ReceiptUnit::where('centerid', $centerid)->whereNotIn('statusid', ['TR'])->select(['locationid', 'boxnumber'])->get();
                $shipments = ShipmentHeader::leftJoin('orderdeliveries as o', function ($join) {
                    $join->on('shipmentheader.orderid', '=', 'o.id');
                    $join->on('shipmentheader.centerid', '=', 'o.centerid');
                })
                    ->leftJoin('shipmentdataoperation as s2', function ($join) {
                        $join->on('o.id', '=', 's2.orderid');
                        $join->on('o.centerid', '=', 's2.centerid');
                    })
                    ->whereNotNull('shipmentheader.locationid')
                    ->whereNull('s2.senddocument')
                    ->whereNotIn('o.statusid', ['DH', 'CA'])
                    ->select(['shipmentheader.id', 'shipmentheader.locationid'])->get();
                foreach ($locations as $location) {
//                Bloquea las cortinas
                    foreach ($units as $unit) {
                        if ($location->id === $unit->locationid) {
                            $location->auxiliary01 = $unit->boxnumber;
                            $location->statusid = 'D';
                            $location->save();
                        }
                    }
//                Bloquea las precargas
                    foreach ($shipments as $shipment) {
                        if ($location->id === $shipment->locationid) {
                            $location->statusid = 'D';
                            $location->save();
                        }
                    }
                }
            }
        }
    }
}
