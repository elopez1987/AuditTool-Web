<?php

namespace App\Http\Controllers\Yard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Yard\CreateGeofenceRequest;
use App\Models\Yard\Geofence;
use App\Services\Yard\GeofenceService;
use App\ViewModels\Yard\GeofenceViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GeofencesController extends BaseController
{
    public function __construct(
        private readonly GeofenceService $geofenceService,
        private readonly GeofenceViewModel $geofenceViewModel,
    ) {}

    public function index(Request $request)
    {
        return view('Yards.Geofence.index', $this->geofenceViewModel->index($request->query('centerid')));
    }

    public function getDataGeofence(Request $request): JsonResponse
    {
        $centerId = $request->query('centerid');
        $geofence = $centerId ? $this->geofenceService->findForDistributionCenter($centerId) : null;

        return response()->json([
            'name' => $geofence?->name,
            'points' => $this->geofenceService->pointsToArray($geofence),
        ]);
    }

    public function create()
    {
        return view('Yards.Geofence.create',
            $this->geofenceViewModel->create());
    }

    public function store(CreateGeofenceRequest $request): RedirectResponse
    {
        $this->geofenceService->saveForDistributionCenter($request->validated());

        return $this->redirectBackSuccessMessage('Sitio creado con exito.');
    }

    public function edit(Geofence $geofence) {}
}
