<?php

namespace App\ViewModels\Configuration;

use App\Models\Configuration\ActivityLog\ActivityLog;
use App\Services\Configuration\ActivityLogService;
use App\Services\Security\ServiceUser;
use Illuminate\Support\Collection;

readonly class ActivityLogViewModel
{
    private const EVENT_LABELS = [
        'created' => 'Creado',
        'updated' => 'Actualizado',
        'deleted' => 'Eliminado',
    ];

    private const EVENT_BADGE_CLASSES = [
        'created' => 'text-bg-success',
        'updated' => 'text-bg-warning',
        'deleted' => 'text-bg-danger',
    ];

    private const STATUS_LABELS = [
        'changed' => 'Modificado',
        'added' => 'Agregado',
        'removed' => 'Eliminado',
        'unchanged' => 'Sin cambio',
    ];

    private const STATUS_BADGE_CLASSES = [
        'changed' => 'text-bg-warning',
        'added' => 'text-bg-success',
        'removed' => 'text-bg-danger',
        'unchanged' => 'text-bg-light text-muted border',
    ];

    private const STATUS_ROW_CLASSES = [
        'changed' => 'table-warning',
        'added' => 'table-success',
        'removed' => 'table-danger',
        'unchanged' => '',
    ];

    public function __construct(
        private ServiceUser $serviceUser,
        private ActivityLogService $logService,
    ) {}

    public function index(): array
    {
        return [
            'users' => $this->serviceUser->all(),
            'descriptions' => $this->logService->findByDescription('description'),
            'models' => $this->logService->findByDescription('subject_type'),
        ];
    }

    /**
     * Data for show.blade.php. Builds a field-by-field diff between the
     * "old" and "attributes" snapshots stored in the log entry, so
     * modified fields can be spotted at a glance.
     *
     * @return array{register: ActivityLog, changes: Collection, totalCount: int, changedCount: int, eventLabel: string, eventBadgeClass: string, changedBadgeClass: string}
     */
    public function show(int $id): array
    {
        $register = $this->logService->findById($id);
        $changes = $this->buildChanges($register);
        $totalCount = $changes->count();
        $changedCount = $changes->where('status', 'changed')->count();
        $eventLabel = self::EVENT_LABELS[$register->event] ?? ucfirst((string) $register->event);
        $eventBadgeClass = self::EVENT_BADGE_CLASSES[$register->event] ?? 'text-bg-secondary';
        $changedBadgeClass = $changedCount > 0 ? 'text-bg-warning' : 'text-bg-secondary';

        return compact('register', 'changes', 'totalCount', 'changedCount', 'eventLabel', 'eventBadgeClass', 'changedBadgeClass');
    }

    /**
     * @return Collection<int, array{field: string, old: string, new: string, status: string, status_label: string, status_class: string, row_class: string}>
     */
    private function buildChanges(ActivityLog $register): Collection
    {
        $properties = (array) ($register->properties ?? []);
        $old = (array) ($properties['old'] ?? []);
        $new = (array) ($properties['attributes'] ?? []);

        return collect(array_keys($old + $new))
            ->map(fn (string $field) => $this->buildFieldChange($field, $old, $new));
    }

    private function buildFieldChange(string $field, array $old, array $new): array
    {
        $hasOld = array_key_exists($field, $old);
        $hasNew = array_key_exists($field, $new);

        $status = match (true) {
            $hasOld && $hasNew && $this->normalize($old[$field]) !== $this->normalize($new[$field]) => 'changed',
            $hasOld && $hasNew => 'unchanged',
            $hasNew => 'added',
            default => 'removed',
        };

        return [
            'field' => $field,
            'old' => $this->formatValue($old[$field] ?? null),
            'new' => $this->formatValue($new[$field] ?? null),
            'status' => $status,
            'status_label' => self::STATUS_LABELS[$status],
            'status_class' => self::STATUS_BADGE_CLASSES[$status],
            'row_class' => self::STATUS_ROW_CLASSES[$status],
        ];
    }

    private function normalize(mixed $value): string
    {
        return is_array($value) ? json_encode($value) : (string) $value;
    }

    private function formatValue(mixed $value): string
    {
        return match (true) {
            is_null($value), $value === '' => '—',
            is_bool($value) => $value ? 'Sí' : 'No',
            is_array($value) => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            default => (string) $value,
        };
    }
}
