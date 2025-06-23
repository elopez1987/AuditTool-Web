<?php

namespace App\Exports\Receipt\CircuitPlant;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageBoxTravel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportUnitCircuitTravel implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    private $workdayin;
    private $workdayout;
    use Exportable;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function headings(): array
    {
        return [
            "Centro de distribución",
            "Fecha",
            "Clave de acceso",
            "Embarque",
            "Folio de planta",
            "Linea de transporte",
            "Placa de caja",
            "Operador",
            "Tipo",
            "Inicio de vuelta",
            "Registro en planta",
            "Llegada a mesa de control",
            "Enrampe",
            "Inicio de proceso",
            "Fin de proceso",
            "Autorización de salida",
            "Salida de planta",
            "Estadia en planta",
            "Folio de Cedis",
            "Llegada a Cedis",
            "Registro en Cedis",
            "Llegada a mesa de control",
            "Enrampe",
            "Inicio de proceso",
            "Fin de proceso",
            "Autorización de salida",
            "Salida de Cedis",
            "Estadia en Cedis",
            "Fin de vuelta",
            "Tiempo de vuelta completa",
            "Tiempo de ida (Min)",
            "Tiempo de vuelta (Min)",
            "Estatus",
        ];
    }

    public function collection()
    {
        $center = SecurityAccessWarehouse::Centers();
        if (!empty($center)) {
            $centerpl = SystemCenterDistribution::where('centerid', $center)->value('auxiliary02');
        }
        $units = StorageBoxTravel::join('systemstatus', 'vw_storageboxtravels.statusid', '=', 'systemstatus.statusid')
            ->join('systemcenterdistributions', 'vw_storageboxtravels.centerid', '=', 'systemcenterdistributions.centerid')
            ->leftJoin('storagereceiptunitarrive','vw_storageboxtravels.foliowarehouse','=','storagereceiptunitarrive.value01')
            ->where('vw_storageboxtravels.starttravel', '>=', $this->workdayin)
            ->where('vw_storageboxtravels.starttravel', '<=', $this->workdayout)
            ->where('vw_storageboxtravels.centerid', $centerpl)
            ->select([
                'vw_storageboxtravels.*',
                'storagereceiptunitarrive.startdate as llegada',
                'systemstatus.name as status',
                'systemcenterdistributions.name as center'
            ])
            ->get();
        return $units;
    }

    public function map($units): array
    {
        return [
            $units->center,
            Carbon::parse($units->fecha)->format('d/m/Y'),
            $units->barcodekey,
            $units->shipmentid,
            $units->folioplant,
            $units->carriername,
            $units->boxnumber,
            $units->operatorname,
            $units->tipo,
            $units->starttravel,
            $units->checkinplant,
            $units->tmesaplant,
            $units->rampaplant,
            $units->startplant,
            $units->finishplant,
            $units->autorzaplant,
            $units->checkoutplant,
            $units->timeplant,
            $units->foliowarehouse,
            $units->llegada,
            $units->checkinwarehouse,
            $units->tmesawarehouse,
            $units->rampawarehouse,
            $units->startwarehouse,
            $units->finishwarehouse,
            $units->autorwarehouse,
            $units->checkoutwarehouse,
            $units->timewarehouse,
            $units->closetravel,
            $units->tiempovuelta,
            $units->trayectoida,
            $units->trayectovuelta,
            $units->status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AF1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }
}
