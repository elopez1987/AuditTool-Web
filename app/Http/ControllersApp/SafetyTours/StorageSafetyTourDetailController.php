<?php

namespace App\Http\ControllersApp\SafetyTours;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SafetyTours\StorageSafetyTourDetailRequest;
use App\Http\Resources\SafetyTours\StorageSafetyTourDetailResource;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\SafetyTours\StorageSafetyTourDetail;
use App\Models\SafetyTours\StorageSafetyTours;
use App\Models\SafetyTours\StorageTags;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\ResponseApi;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StorageSafetyTourDetailController extends Controller
{
    use ResponseApi;
    use SavePhotoInRegister;


    public function index(Request $request)
    {
        return $this->showAll('index',
            StorageSafetyTourDetailResource::collection(
                StorageSafetyTourDetail::controlAccessUser()->where('tourid', $request->tourid)
                    ->where('statusid', 'RSE-01')->get()));
    }

    public function store(StorageSafetyTourDetailRequest $request)
    {
        DB::beginTransaction();
        try {
            $tour = StorageSafetyTours::controlAccessUser()->where('id', $request->tourid)->first();
            if (empty($tour) || $tour->statusid == 'RSE-02') {
                return $this->getResponseWithErrors('store', 'El tour no existe o esta cerrado');
            }
            $tag = StorageTags::controlAccessUser()->where('tagid', $request->tagid)->first();
            if (empty($tag)) {
                return $this->getResponseWithErrors('store', 'El número de tag ingresado no existe, favor de validar');
            }
            $exisTagId = StorageSafetyTourDetail::controlAccessUser()->where('tourid', $request->tourid)->where('tagid', $tag->id)
                ->where('statusid', 'RSE-01')->first();
            if (empty($exisTagId)) {
                return $this->getResponseWithErrors('store', 'El tag no existe en el tour o ya fue registrado');
            }
            $this->registerStartTour($request, $tour);
            $this->registerProcessTour($exisTagId, $request);
            $this->regiserFinishTour($request, $tour);
            if ($request->has('evidencefile')) {
                $path = $this->createNewEvidenceForRegister($request['evidencefile'], $exisTagId, 'SafetyTours', 'TuorStorageSafetyTourDetails', 'Evidence');
                $exisTagId->evidencefile = $path;
                $exisTagId->save();
            }
            DB::commit();
            return $this->getResponseSuccess('getCategoriesIncidencesTour', ['tagid' => $tag->tagid, 'created_at' => $exisTagId->updated_at]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Se produjo un error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->getResponseWithErrors('saveMoveBox', 'Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }

    public function getCategoriesIncidencesTour()
    {
        $categories = SystemStatus::whereSchemaid('CTR')->select(['statusid', 'name'])->get();
        return $this->getResponseSuccess('getCategoriesIncidencesTour', $categories);
    }

    /**
     * @param StorageSafetyTourDetailRequest $request
     * @param StorageSafetyTours $tour
     * @return void
     */
    public function registerStartTour(StorageSafetyTourDetailRequest $request, StorageSafetyTours $tour): void
    {
        $totalUsers = StorageSafetyTourDetail::controlAccessUser()->where('tourid', $request->tourid)->whereNotNull('userid')->count();
        if ($totalUsers == 0) {
            $tour->statusid = 'RSE-03';
            $tour->startdate = now();
            $tour->save();
        }
    }

    /**
     * @param StorageSafetyTourDetail $exisTagId
     * @param StorageSafetyTourDetailRequest $request
     * @return void
     */
    public function registerProcessTour(StorageSafetyTourDetail $exisTagId, StorageSafetyTourDetailRequest $request): void
    {
        $exisTagId->statusid = 'RSE-02';
        $exisTagId->latitude = $request['latitude'];
        $exisTagId->longitude = $request['longitude'];
        $exisTagId->comment = $request['comment'];
        $exisTagId->statustype = $request['statustype'];
        $exisTagId->userid = Auth::id();
        $exisTagId->updated_at = now();
        $exisTagId->save();
    }

    /**
     * @param StorageSafetyTourDetailRequest $request
     * @param StorageSafetyTours $tour
     * @return void
     */
    public function regiserFinishTour(StorageSafetyTourDetailRequest $request, StorageSafetyTours $tour): void
    {
        $totalClose = StorageSafetyTourDetail::controlAccessUser()->where('tourid', $request->tourid)->where('statusid', 'RSE-01')->count();
        if ($totalClose == 0) {
            $tour->statusid = 'RSE-02';
            $tour->finishdate = now();
            $tour->save();
        }
    }

}
