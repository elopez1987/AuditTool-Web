<?php

//
// namespace App\Http\Requests\Web\Receipt\ReceiptAsn;
//
// use App\Models\Configuration\Folios\SystemFolio;
// use App\Models\Configuration\Warehouses\SystemWarehouse;
// use App\Models\Yard\Carriers\ShipmentCarrier;
// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\DB;
//
// class CreateReceiptAsnRequest extends FormRequest
// {
//    protected function prepareForValidation()
//    {
//        if (! $this->filled('carrierid')) {
//            $defaultCarrier = ShipmentCarrier::whereRaw("charindex('SCR-04', configurationflag) > 0")
//                ->first();
//            if ($defaultCarrier) {
//                $this->merge([
//                    'carrierid' => $defaultCarrier->id,
//                ]);
//            }
//        }
//
//        // Validar si se debe generar shipmentid automático
//        if ($this->filled('warehouseid') && $this->filled('centerid')) {
//            $parameterWarehouse = SystemWarehouse::whereWarehouseid($this->input('warehouseid'))
//                ->value('configurationflag');
//
//            if (! empty($parameterWarehouse) && in_array('SWH-16', (array) $parameterWarehouse)) {
//                $shipment = self::getFolioReceipt($this->input('centerid'));
//                $this->merge([
//                    'shipmentid' => $shipment,
//                ]);
//            }
//        }
//    }
//
//    public function rules()
//    {
//        // Determinar si shipmentid es requerido basado en configuración de warehouse
//        $parameterWarehouse = SystemWarehouse::whereWarehouseid($this->input('warehouseid'))
//            ->value('configurationflag');
//
//        $shipmentidRule = 'required|max:20|unique:receiptasn,shipmentid|regex:/^[a-zA-Z0-9]+$/';
//
//        // Si tiene SWH-16, el shipmentid se genera automáticamente, no necesita validación de unicidad estricta
//        if (! empty($parameterWarehouse) && in_array('SWH-16', (array) $parameterWarehouse)) {
//            $shipmentidRule = 'required|max:20|regex:/^[a-zA-Z0-9]+$/';
//        }
//
//        return [
//            'centerid' => 'required|exists:systemcenterdistributions,centerid',
//            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
//            'providerid' => 'required|exists:receiptproviders,id',
//            'shipmentid' => $shipmentidRule,
//            'addasndate' => 'required|date|after_or_equal:today',
//            'carrierid' => 'nullable|exists:shipmentcarriers,id',
//            'stockcategoryid' => 'required|exists:inventorystockcategories,id',
//            'razonstatusid' => 'required|exists:storagereasons,id',
//            'turnid' => 'required|exists:storageturns,id',
//            'oc' => 'nullable',
//            'billoflading' => 'nullable',
//            'folionumber' => 'nullable|numeric:max_digits:20',
//        ];
//    }
//
//    public function authorize()
//    {
//        return true;
//    }
//
//    private static function getFolioReceipt(string $centerid)
//    {
//        return DB::transaction(function () use ($centerid) {
//            $folio = SystemFolio::where('schemaid', 'FOL')
//                ->where('centerid', $centerid)
//                ->where('statusid', 'REC')
//                ->lockForUpdate()
//                ->firstOrFail(['id', 'folionumber']);
//
//            $folio->increment('folionumber');
//
//            return $folio->folionumber;
//        });
//    }
// }

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use App\Models\Configuration\Folios\SystemFolio;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreateReceiptAsnRequest extends FormRequest
{
    private const DEFAULT_CARRIER_FLAG = 'SCR-04';

    private const AUTO_SHIPMENT_FLAG = 'SWH-16';

    private const CACHE_TTL = 3600; // 1 hora

    protected function prepareForValidation(): void
    {

        $this->generateShipmentIdIfNeeded();
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'providerid' => 'required|exists:receiptproviders,id',
            'shipmentid' => $this->getShipmentIdRule(),
            'addasndate' => 'required|date|after_or_equal:today',
            'stockcategoryid' => 'required|exists:inventorystockcategories,id',
            'razonstatusid' => 'required|exists:storagereasons,id',
            'billoflading' => 'nullable|string|max:50',
            'folionumber' => 'nullable|numeric|max_digits:20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Genera el shipmentid automáticamente si está configurado
     */
    private function generateShipmentIdIfNeeded(): void
    {
        if (! $this->filled('warehouseid') || ! $this->filled('centerid')) {
            return;
        }


    }



    /**
     * Verifica si debe generar shipmentid automáticamente
     */
    private function shouldAutoGenerateShipmentId(): bool
    {
        $warehouseConfig = $this->getWarehouseConfiguration();

        return ! empty($warehouseConfig)
            && in_array(self::AUTO_SHIPMENT_FLAG, (array) $warehouseConfig);
    }

    /**
     * Obtiene la configuración del warehouse con caché
     */
    private function getWarehouseConfiguration(): mixed
    {
        $warehouseId = $this->input('warehouseid');

        return Cache::remember(
            "warehouse_config_{$warehouseId}",
            self::CACHE_TTL,
            fn () => SystemWarehouse::where('warehouseid', $warehouseId)
                ->value('configurationflag')
        );
    }

    /**
     * Obtiene la regla de validación para shipmentid
     */
    private function getShipmentIdRule(): string
    {
        $baseRule = 'required|max:20|regex:/^[a-zA-Z0-9]+$/';

        // Si se auto-genera, no validar unicidad (ya se maneja en la transacción)
        if ($this->shouldAutoGenerateShipmentId()) {
            return $baseRule;
        }

        return $baseRule.'|unique:receiptasn,shipmentid';
    }


    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'shipmentid.unique' => 'El número de embarque ya existe en el sistema.',
            'addasndate.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
        ];
    }
}
