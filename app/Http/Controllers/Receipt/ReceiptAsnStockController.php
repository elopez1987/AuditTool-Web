<?php

declare(strict_types=1);

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptStockRequest;
use App\Http\Requests\Web\Receipt\ReceiptAsn\UpdateReceiptStockRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Services\Receipt\ExpectedReceiptMutationService;
use Illuminate\Http\Request;

class ReceiptAsnStockController extends BaseController
{
    public function __construct(private readonly ExpectedReceiptMutationService $service) {}

    public function addstock($id)
    {
        $receiptasn = ReceiptAsn::controlAccessUser()->where('uuid_asn', $id)->first();
        if (empty($receiptasn)) {
            return $this->errorResponse('No existe el número de recibo solicitado.');
        }
        $stocks = ReceiptStock::whereReceiptid($receiptasn->id)->get();

        return view(
            'Receipt.ReceiptAsn.addStock',
            compact('receiptasn', 'stocks'),
        );
    }

    public function addstockstore(CreateReceiptStockRequest $request, $id)
    {
        $this->service->addStock((int) $id, (string) $request->user()->username, $request->validated());

        return $this->redirectBackSuccessMessage('Stock agregado con exito.');
    }

    public function updateQuantityStock($id, UpdateReceiptStockRequest $request)
    {
        $this->service->updateStock((int) $id, (string) $request->user()->username, $request->validated());

        return $this->redirectBackSuccess();
    }

    public function deletestock($id, Request $request)
    {
        $this->service->deleteStock((int) $id, (string) $request->user()->username);

        return $this->redirectBackSuccess();
    }
}
