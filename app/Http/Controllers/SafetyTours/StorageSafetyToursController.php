<?php

namespace App\Http\Controllers\SafetyTours;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\SafetyTours\CreateSafetyTourRequest;
use App\Models\SafetyTours\StorageSafetyTourDetail;
use App\Models\SafetyTours\StorageSafetyTours;
use App\Models\SafetyTours\StorageSafetyTourType;
use App\Models\SafetyTours\StorageSafetyTourTypeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class StorageSafetyToursController extends BaseController
{
    public function index()
    {
        $this->authorize('storagesafetytours.index');

        return view('SafetyTours.StorageSafetyTours.index');
    }

    public function getDataStorageSafetyTours(Request $request)
    {
        $data = StorageSafetyTours::controlAccessUser()
            ->with(['status', 'center', 'user'])
            ->Name($request->get('name'))->get();

        return $this->showDataToView($data, 'SafetyTours.StorageSafetyTours.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagesafetytours.create');
        $types = StorageSafetyTourType::controlAccessUser()
            ->where('statusid', '=', 'TYP-01')->select(['id', 'name'])->get();

        return view('SafetyTours.StorageSafetyTours.create', compact('types'));
    }

    public function store(CreateSafetyTourRequest $request)
    {
        $this->authorize('storagesafetytours.create');
        DB::beginTransaction();
        try {
            $tour = new StorageSafetyTours($request->validated() + ['centerid' => $this->getCenterForUser()]);
            $tour->save();
            $tags = StorageSafetyTourTypeDetail::controlAccessUser()->where('typeid', $request->typeid)
                ->select(['tagid', 'id'])->get();
            if ($tags->isEmpty()) {
                return $this->errorResponse('Tipo de recorrido no cuenta con tag registrados.');
            }
            foreach ($tags as $tag) {
                StorageSafetyTourDetail::create([
                    'tourid' => $tour->id,
                    'centerid' => $tour->centerid,
                    'tagid' => $tag->tagid,
                ]);
            }
            DB::commit();

            return redirect('storagesafetytours/' . $tour->touruuid);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return $this->errorResponse('Error al registrar el folio :' . $e->getMessage());
        }
    }

    public function show($touruuid)
    {
        $this->authorize('storagesafetytours.show');
        $tour = StorageSafetyTours::controlAccessUser()->where('touruuid', '=', $touruuid)->first();
        $details = StorageSafetyTourDetail::controlAccessUser()->where('tourid', $tour->id)->get();
        $locations = StorageSafetyTourDetail::controlAccessUser()->where('tourid', $tour->id)
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'storagesafetytourdetail.statusid')
            ->leftJoin('storagetags', 'storagetags.id', '=', 'storagesafetytourdetail.tagid')
            ->select(['storagetags.latitude as lat', 'storagetags.name as name',
                'storagetags.longitude as lng', 'systemstatus.name as status',
            ])
            ->get()->map(function ($location) {
                $location->lat = (float) $location->lat;
                $location->lng = (float) $location->lng;

                return $location;
            });

        return view('SafetyTours.StorageSafetyTours.show', compact('tour', 'details', 'locations'));
    }

    public function edit($touruuid)
    {
        $this->authorize('storagesafetytours.edit');
        $tour = StorageSafetyTours::controlAccessUser()->where('touruuid', '=', $touruuid)->first();

        return view('SafetyTours.StorageSafetyTours.edit', compact('tour'));
    }

    public function update(Request $request, $touruuid)
    {
        $this->authorize('storagesafetytours.edit');
        $tour = StorageSafetyTours::controlAccessUser()->where('touruuid', '=', $touruuid)->first();
        $tour->update($request->all());

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy($touruuid)
    {
        $this->authorize('storagesafetytours.destroy');
        $tour = StorageSafetyTours::controlAccessUser()->where('touruuid', '=', $touruuid)->first();
        $tour->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
