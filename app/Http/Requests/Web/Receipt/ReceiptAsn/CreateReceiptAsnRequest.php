<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class CreateReceiptAsnRequest extends FormRequest
{
    private const string AUTO_SHIPMENT_FLAG = 'SWH-16';

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
            'operatorid' => 'required|exists:storageoperators,id',
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
