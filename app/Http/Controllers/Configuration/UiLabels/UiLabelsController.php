<?php

namespace App\Http\Controllers\Configuration\UiLabels;

use App\Http\Controllers\BaseController;
use App\Models\UiLabel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UiLabelsController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UiLabel::select(['id', 'field_name', 'label_text', 'type', 'visibled', 'required', 'module_name'])->get();

            return datatables()->of($data)
                ->addColumn('visibled', function ($row) {
                    return $row->visibled
                        ? '<span class="badge badge-success">Sí</span>'
                        : '<span class="badge badge-secondary">No</span>';
                })
                ->addColumn('required', function ($row) {
                    return $row->required
                        ? '<span class="badge badge-warning">Sí</span>'
                        : '<span class="badge badge-secondary">No</span>';
                })
                ->addColumn('btn', function ($row) {
                    return view('Configuration.UiLabels.partials.buttons', ['id' => $row->id])->render();
                })
                ->rawColumns(['btn', 'visibled', 'required'])
                ->make(true);
        }

        return view('Configuration.UiLabels.index');
    }

    public function create()
    {
        return view('Configuration.UiLabels.partials.formCreate');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_name' => 'required|string|max:100|unique:ui_labels,field_name',
                'label_text' => 'required|string|max:255',
                'module_name' => 'required|string|max:255',
                'visibled' => 'nullable|boolean',
                'required' => 'nullable|boolean',
                'type' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación',
                ], 422);
            }

            DB::beginTransaction();

            $uiLabel = UiLabel::create([
                'module_name' => $request->module_name,
                'field_name' => $request->field_name,
                'label_text' => $request->label_text,
                'visibled' => $request->visible ?? true,
                'required' => $request->required ?? false,
                'type' => $request->type ?? 'text',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Etiqueta creada exitosamente',
                'data' => $uiLabel,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al crear UI Label', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la etiqueta: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $uiLabel = UiLabel::findOrFail($id);

        return view('Configuration.UiLabels.partials.formEdit', compact('uiLabel'));
    }

    public function update(Request $request, $id): JsonResponse
    {

        try {
            $validator = Validator::make($request->all(), [
                //				'field_name' => 'required|string|max:100|unique:ui_labels,field_name,' . $uiLabel->id,
                'label_text' => 'required|string|max:255',
                'visibled' => 'nullable|boolean',
                'required' => 'nullable|boolean',
                'type' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación',
                ], 422);
            }

            DB::beginTransaction();
            $uiLabel = UiLabel::findOrFail($id);

            $uiLabel->update([
                'module_name' => $request->module_name ?? $uiLabel->module_name,
                'field_name' => $request->field_name,
                'label_text' => $request->label_text,
                'visibled' => $request->visibled ?? $uiLabel->visibled,
                'required' => $request->required ?? $uiLabel->required,
                'type' => $request->type ?? $uiLabel->type,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Etiqueta actualizada exitosamente',
                'data' => $uiLabel->fresh(),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar UI Label', [
                'error' => $e->getMessage(),
                'uiLabel_id' => $uiLabel->id,
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la etiqueta: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $uiLabel = UiLabel::findOrFail($id);

            $fieldName = $uiLabel->field_name;
            $uiLabel->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Etiqueta '{$fieldName}' eliminada exitosamente",
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar UI Label', [
                'error' => $e->getMessage(),
                'uiLabel_id' => $uiLabel->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la etiqueta: ' . $e->getMessage(),
            ], 500);
        }
    }
}
