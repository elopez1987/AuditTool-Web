<?php

namespace App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity;

use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\ResponseApi;

trait ValidateCapacityYard
{
	use ResponseApi;

	public function validateCapacityYard($request)
	{
		$centerid = $request->get('centerid');
		$parameterCenter = $this->getParametersOfCenter($centerid);
		if (!$parameterCenter || !in_array('SDC-11', (array)$parameterCenter)) {
			return null;
		}
		$totalExist = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR', 'ES'])->count();
		$totalLocations = StorageZone::controlAccessUser()
			->whereIn('auxiliary01', ['DROP-LOT', 'CORTINAS'])->sum('capacity');
		if ($totalExist > $totalLocations) {
			return $this->errorResponse('La capacidad de almacenamiento se encuentra llena, favor de validar.');
		}
	}
}
