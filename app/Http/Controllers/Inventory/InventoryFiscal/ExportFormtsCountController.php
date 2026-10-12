<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ExportFormtsCountController extends BaseController
{
    /**
     * Genera PDFs de conteo 01 de todas las cédulas del inventario y las descarga en un ZIP.
     */
    public function generatecount01($inventoryId): BinaryFileResponse|RedirectResponse
    {
        return $this->generateZipForCount(
            inventoryId: (int) $inventoryId,
            countType: 1,
            viewName: 'Inventory.InventoryHead.CardDetail.Formats.Estandard.count01',
            zipPrefix: 'formatos-conteo-01',
        );
    }

    /**
     * Genera PDFs de conteo 02 de todas las cédulas del inventario y las descarga en un ZIP.
     */
    public function generatecount02($inventoryId): BinaryFileResponse|RedirectResponse
    {
        return $this->generateZipForCount(
            inventoryId: (int) $inventoryId,
            countType: 2,
            viewName: 'Inventory.InventoryHead.CardDetail.Formats.Estandard.count02',
            zipPrefix: 'formatos-conteo-02',
        );
    }

    /**
     * Recorre InventoryCardCount por inventory_head_id, genera un PDF por cédula
     * y empaqueta todos en un ZIP temporal para descarga.
     */
    private function generateZipForCount(
        int $inventoryId,
        int $countType,
        string $viewName,
        string $zipPrefix,
    ): BinaryFileResponse|RedirectResponse {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $inventoryId)
            ->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('El inventario no existe o no tiene acceso.');
        }

        $cards = InventoryCardCount::query()
            ->with([
                'inventoryhead:id,name',
                'inventorycard:id,name',
                'user_count01:id,name',
                'user_count02:id,name',
            ])
            ->where('inventory_head_id', $inventoryHead->id)
            ->orderBy('id')
            ->get();

        if ($cards->isEmpty()) {
            return $this->errorResponse('El inventario no tiene cédulas para exportar.');
        }

        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $imageRoot = $pathImage?->value01 ? realpath($pathImage->value01) : realpath(base_path());

        $tmpDir = storage_path('app/tmp/inventory-formats/' . $inventoryHead->id . '-' . uniqid('', true));
        File::ensureDirectoryExists($tmpDir);

        $zipFileName = $zipPrefix . '-' . $inventoryHead->id . '-' . now()->format('Ymd-His') . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            File::deleteDirectory($tmpDir);

            return $this->errorResponse('No se pudo crear el archivo ZIP.');
        }

        $addedAny = false;

        try {
            foreach ($cards as $inventoryCardCount) {
                $locations = $this->locationsForCard($inventoryCardCount, $countType);

                $pdf = \PDF::setOptions([
                    'isRemoteEnabled' => true,
                    'chroot' => array_values(array_filter([
                        realpath(base_path()),
                        $imageRoot,
                    ])),
                    'httpContext' => stream_context_create([
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ],
                    ]),
                ])->loadView($viewName, compact('inventoryCardCount', 'locations'));

                $cardName = $inventoryCardCount->inventorycard->name ?? ('card-' . $inventoryCardCount->id);
                $safeCardName = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $cardName) ?: 'card';
                $pdfFileName = 'cedula-conteo-0' . $countType . '-' . $safeCardName . '-' . $inventoryCardCount->id . '.pdf';
                $pdfPath = $tmpDir . DIRECTORY_SEPARATOR . $pdfFileName;

                // output() genera el binario sin forzar descarga HTTP por archivo.
                file_put_contents($pdfPath, $pdf->output());

                if (is_file($pdfPath)) {
                    $zip->addFile($pdfPath, $pdfFileName);
                    $addedAny = true;
                }
            }
        } catch (\Throwable $e) {
            $zip->close();
            File::deleteDirectory($tmpDir);
            @unlink($zipPath);

            Log::error('Error al generar ZIP de formatos de inventario', [
                'inventory_head_id' => $inventoryHead->id,
                'count_type' => $countType,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ocurrió un error al generar los formatos PDF.');
        }

        $zip->close();
        File::deleteDirectory($tmpDir);

        if (! $addedAny || ! is_file($zipPath)) {
            @unlink($zipPath);

            return $this->errorResponse('No se generaron PDFs para descargar.');
        }

        return response()
            ->download($zipPath, $zipFileName)
            ->deleteFileAfterSend(true);
    }

    /**
     * Ubicaciones de una cédula según el tipo de conteo (1 o 2).
     */
    private function locationsForCard(InventoryCardCount $inventoryCardCount, int $countType)
    {
        $query = InventoryLocationCount::query()
            ->where('inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('inventory_card_id', $inventoryCardCount->id)
            ->with(['inventorylocation:id,name,inventoryunitsmeasure_id']);

        if ($countType === 1) {
            return $query
                ->with(['inventorypartnumber01:id,prtnum,lngdsc'])
                ->select([
                    'id',
                    'inventory_location_id',
                    'inventory_head_id',
                    'inventory_card_id',
                    'inventorypartnumber01_id',
                    'quantity01',
                    'user_count01_id',
                ])
                ->orderBy('id')
                ->get();
        }

        return $query
            ->with(['inventorypartnumber02:id,prtnum,lngdsc'])
            ->select([
                'id',
                'inventory_location_id',
                'inventory_head_id',
                'inventory_card_id',
                'inventorypartnumber02_id',
                'quantity02',
                'user_count02_id',
            ])
            ->orderBy('id')
            ->get();
    }
}
