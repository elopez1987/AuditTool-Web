<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryCountRequestLog;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class InventoryLogController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $statusOptions = $this->statusOptions();

        return view(
            'Inventory.InventoryHead.InventoryLogs.index',
            compact('inventoryHead', 'statusOptions'),
        );
    }

    public function getInventoryLog(Request $request, $id)
    {
        $inventoryHead = $this->resolveInventoryHeadById((int) $id);
        $query = $this->baseLogQuery($request, $inventoryHead);

        return DataTables::of($query)
            ->editColumn('status', function (InventoryCountRequestLog $log) {
                return $this->statusBadgeHtml((string) $log->status);
            })
            ->editColumn('received_at', function (InventoryCountRequestLog $log) {
                return optional($log->received_at)?->format('Y-m-d H:i:s');
            })
            ->editColumn('processed_at', function (InventoryCountRequestLog $log) {
                return optional($log->processed_at)?->format('Y-m-d H:i:s');
            })
            ->addColumn('btn', function (InventoryCountRequestLog $log) use ($inventoryHead) {
                return view('Inventory.InventoryHead.InventoryLogs.partials.buttons', [
                    'id' => $log->id,
                    'inventoryUuid' => $inventoryHead->inventory_uuid,
                ])->render();
            })
            ->rawColumns(['status', 'btn'])
            ->orderColumn('received_at', 'inventory_count_request_logs.received_at $1')
            ->orderColumn('processed_at', 'inventory_count_request_logs.processed_at $1')
            ->orderColumn('status', 'inventory_count_request_logs.status $1')
            ->orderColumn('location_id', 'inventory_count_request_logs.location_id $1')
            ->orderColumn('partnumber_name', 'inventory_count_request_logs.partnumber_name $1')
            ->orderColumn('inventory_card_id', 'inventory_count_request_logs.inventory_card_id $1')
            ->toJson();
    }

    public function getInventoryLogKpis(Request $request, $id)
    {
        $inventoryHead = $this->resolveInventoryHeadById((int) $id);

        $totals = $this->applyLogFilters(
            InventoryCountRequestLog::query()->where(
                'inventory_count_request_logs.inventory_head_id',
                $inventoryHead->id,
            ),
            $request,
        )
            ->selectRaw("
                COUNT(*) as total_logs,
                SUM(CASE WHEN status = 'RECIBIDO' THEN 1 ELSE 0 END) as total_recibido,
                SUM(CASE WHEN status = 'TERMINADO' THEN 1 ELSE 0 END) as total_terminado,
                SUM(CASE WHEN status = 'ERROR' THEN 1 ELSE 0 END) as total_error
            ")
            ->first();

        return response()->json([
            'totalLogs' => (int) ($totals->total_logs ?? 0),
            'totalRecibido' => (int) ($totals->total_recibido ?? 0),
            'totalTerminado' => (int) ($totals->total_terminado ?? 0),
            'totalError' => (int) ($totals->total_error ?? 0),
        ]);
    }

    public function show($id, $logId)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $log = InventoryCountRequestLog::query()
            ->where('inventory_head_id', $inventoryHead->id)
            ->where('id', $logId)
            ->firstOrFail();

        return view(
            'Inventory.InventoryHead.InventoryLogs.show',
            compact('inventoryHead', 'log'),
        );
    }

    /**
     * Descarga el JSON completo guardado en file_path (payload de la petición).
     */
    public function downloadPayloadFile($id, $logId): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $log = InventoryCountRequestLog::query()
            ->where('inventory_head_id', $inventoryHead->id)
            ->where('id', $logId)
            ->firstOrFail();

        if (blank($log->file_path)) {
            return $this->errorResponse('El log no tiene archivo JSON asociado.');
        }

        $relativePath = ltrim((string) $log->file_path, '/');
        $fileName = basename($relativePath);
        $disk = Storage::disk('local');

        if ($disk->exists($relativePath)) {
            return $disk->download($relativePath, $fileName, [
                'Content-Type' => 'application/json',
            ]);
        }

        // Fallback por si el path se guardó absoluto o bajo storage/app.
        $absoluteCandidates = [
            $relativePath,
            storage_path('app/' . $relativePath),
        ];

        foreach ($absoluteCandidates as $absolutePath) {
            if (is_file($absolutePath)) {
                return response()->download($absolutePath, $fileName, [
                    'Content-Type' => 'application/json',
                ]);
            }
        }

        return $this->errorResponse('El archivo JSON no existe en el servidor.');
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function statusOptions(): array
    {
        return [
            ['value' => InventoryCountRequestLog::STATUS_RECEIVED, 'label' => 'RECIBIDO'],
            ['value' => InventoryCountRequestLog::STATUS_FINISHED, 'label' => 'TERMINADO'],
            ['value' => InventoryCountRequestLog::STATUS_ERROR, 'label' => 'ERROR'],
        ];
    }

    private function resolveInventoryHeadById(int $id): InventoryHead
    {
        return InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();
    }

    private function baseLogQuery(Request $request, InventoryHead $inventoryHead): Builder
    {
        return $this->applyLogFilters(
            InventoryCountRequestLog::query()->where(
                'inventory_count_request_logs.inventory_head_id',
                $inventoryHead->id,
            ),
            $request,
        )->select([
            'inventory_count_request_logs.id',
            'inventory_count_request_logs.transaction_id',
            'inventory_count_request_logs.inventory_card_id',
            'inventory_count_request_logs.location_id',
            'inventory_count_request_logs.partnumber_name',
            'inventory_count_request_logs.number_count',
            'inventory_count_request_logs.quantity',
            'inventory_count_request_logs.username',
            'inventory_count_request_logs.service_name',
            'inventory_count_request_logs.endpoint',
            'inventory_count_request_logs.http_method',
            'inventory_count_request_logs.status',
            'inventory_count_request_logs.http_code',
            'inventory_count_request_logs.message',
            'inventory_count_request_logs.received_at',
            'inventory_count_request_logs.processed_at',
        ]);
    }

    private function applyLogFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('inventory_card_id'), function (Builder $builder) use ($request) {
                $builder->where(
                    'inventory_count_request_logs.inventory_card_id',
                    (int) $request->input('inventory_card_id'),
                );
            })
            ->when($request->filled('location_id'), function (Builder $builder) use ($request) {
                $term = trim((string) $request->input('location_id'));
                $builder->where('inventory_count_request_logs.location_id', 'like', '%' . $term . '%');
            })
            ->when($request->filled('partnumber_name'), function (Builder $builder) use ($request) {
                $term = trim((string) $request->input('partnumber_name'));
                $builder->where('inventory_count_request_logs.partnumber_name', 'like', '%' . $term . '%');
            })
            ->when($request->filled('status'), function (Builder $builder) use ($request) {
                $builder->where(
                    'inventory_count_request_logs.status',
                    trim((string) $request->input('status')),
                );
            });
    }

    private function statusBadgeHtml(string $status): string
    {
        $colors = [
            InventoryCountRequestLog::STATUS_RECEIVED => '#29b6f6',
            InventoryCountRequestLog::STATUS_FINISHED => '#00e676',
            InventoryCountRequestLog::STATUS_ERROR => '#ff1744',
        ];

        $bgColor = $colors[$status] ?? '#e0e0e0';
        $label = e($status !== '' ? $status : '-');

        return '<span style="background-color:' . $bgColor . '; color: black; padding: 2px 8px; border-radius: 4px; display: inline-block;">'
            . $label
            . '</span>';
    }
}
