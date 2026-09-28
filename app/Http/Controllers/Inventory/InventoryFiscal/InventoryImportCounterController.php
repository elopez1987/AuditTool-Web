<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryImportCounterRequest;
use App\Services\Inventory\InventoryCounterImportService;

class InventoryImportCounterController extends BaseController
{
    public function __construct(
        private readonly InventoryCounterImportService $importService,
    ) {}

    public function index(InventoryImportCounterRequest $request)
    {
        $this->importService->import(
            $request->file('file'),
            (int) $request->validated('inventoryid'),
        );

        return $this->redirectBackSuccessMessage(
            'Tu archivo se está procesando en segundo plano. Te notificaremos cuando termine.',
        );
    }
}
