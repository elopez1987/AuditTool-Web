<?php

namespace App\Console\Commands\General;

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

            if (empty($parameter) || ! in_array('SDC-08', $parameter)) {
                continue;
            }

            // Reinicia todas las ubicaciones a disponible
            StorageLocation::where('centerid', $centerid)
                ->update(['statusid' => 'E', 'auxiliary01' => '']);

            // Bloquea las cortinas: actualiza ubicaciones con unidades activas
            $units = ReceiptUnit::where('centerid', $centerid)
                ->whereNotIn('statusid', ['TR'])
                ->whereNotNull('locationid')
                ->select(['locationid', 'boxnumber'])
                ->get()
                ->keyBy('locationid');

            foreach ($units as $locationId => $unit) {
                StorageLocation::where('id', $locationId)
                    ->where('centerid', $centerid)
                    ->update(['statusid' => 'D', 'auxiliary01' => $unit->boxnumber]);
            }

            // Bloquea las precargas: actualiza ubicaciones con embarques activos
            $shipmentLocationIds = ShipmentHeader::leftJoin('orderdeliveries as o', function ($join) {
                $join->on('shipmentheader.orderid', '=', 'o.id');
                $join->on('shipmentheader.centerid', '=', 'o.centerid');
            })
                ->leftJoin('shipmentdataoperation as s2', function ($join) {
                    $join->on('o.id', '=', 's2.orderid');
                    $join->on('o.centerid', '=', 's2.centerid');
                })
                ->where('shipmentheader.centerid', $centerid)
                ->whereNotNull('shipmentheader.locationid')
                ->whereNull('s2.senddocument')
                ->whereNotIn('o.statusid', ['DH', 'CA'])
                ->pluck('shipmentheader.locationid');

            if ($shipmentLocationIds->isNotEmpty()) {
                StorageLocation::where('centerid', $centerid)
                    ->whereIn('id', $shipmentLocationIds)
                    ->update(['statusid' => 'D']);
            }
        }
    }
}
