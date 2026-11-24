<?php

namespace App\Http\Controllers\Apis\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Verification\VerificationRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptVerification;
use App\Traits\Api\ModernApiResponse;
use Carbon\Carbon;

class VerificationController extends Controller
{
    use ModernApiResponse;

    public function saveVerification(VerificationRequest $request)
    {
        $receiptAsn = ReceiptAsn::controlAccessUser()
            ->where('uuid_asn', $request->validated('AsnId'))
            ->whereNotIn('statusid', ['CA', 'TR'])
            ->first();

        if (is_null($receiptAsn)) {
            return $this->modernNotFoundResponse('Vale no encontrado', 'Verification process');
        }

        $partNumber = InventoryPartnumber::where('prtnum', $request->validated('PartNumberCode'))
            ->select('id')->first();

        if (is_null($partNumber)) {
            return $this->modernNotFoundResponse('Producto no encontrado.', 'Verification process');
        }

        $typeMaterial = SystemStatus::where('statusid', $request->validated('TypeId'))
            ->select('statusid')->first();

        if (is_null($typeMaterial)) {
            return $this->modernNotFoundResponse('Tipo de ingreso no valido.', 'Verification process');
        }

        if ($request->validated('ProductionDate') > Carbon::now()->format('Y-m-d')) {
            return $this->modernValidationErrorResponse('La fecha de produccion no puede ser mayor a la fecha actual', 'Verification process');
        }

        if ($request->validated('ExpireDate') < $request->validated('ProductionDate')) {
            return $this->modernValidationErrorResponse('La fecha de caducidad no puede ser menor a la fecha de producción.', 'Verification process');
        }

        $validationProduct = ReceiptStock::where('receiptid', $receiptAsn->id)
            ->where('partnumberid', $partNumber->id)->first();

        if (is_null($validationProduct)) {
            return $this->modernNotFoundResponse('Producto no solicitado en el vale.', 'Verification process');
        }

        if ($request->validated('TypeId') === 'TRP-01') {

            ReceiptVerification::create([
                'receiptasn_id' => $receiptAsn->id,
                'inventorypartnumber_id' => $partNumber->id,
                'quantity' => $request->validated('Quantity'),
                'type_id' => $request->validated('TypeId'),
            ]);

            //            $totalVerified = ReceiptVerification::where('receiptasn_id', $receiptAsn->id)
            //                ->where('inventorypartnumber_id', $partNumber->id)
            //                ->where('type_id', 'TRP-01')
            //                ->sum('quantity');
            //
            //            $totalProcessed = $totalVerified + $request->validated('Quantity');
            //
            //            if ($totalProcessed > $totalRequest) {
            //                return $this->modernValidationErrorResponse('La cantidad a verificar no puede ser mayor a la solicidata.', 'Verification process');
            //            }
        }

        return $this->modernSuccessResponse(
            data: [
                'receiptAsn' => $receiptAsn,
            ],
            message: 'Registro procesado exitosamente',
            serviceName: 'verification',
            httpStatus: 201,
        );

    }
}
