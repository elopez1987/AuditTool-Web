<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Bitácora de peticiones recibidas por los endpoints de conteo de inventario.
 *
 * @property int $id
 * @property string $transaction_id
 * @property int|null $inventory_head_id
 * @property int|null $inventory_card_id
 * @property int|null $inventory_location_id
 * @property string|null $location_id
 * @property string|null $partnumber_name
 * @property int|null $number_count
 * @property float|null $quantity
 * @property int|null $user_id
 * @property string|null $username
 * @property string|null $centerid
 * @property string|null $warehouseid
 * @property string|null $service_name
 * @property string|null $endpoint
 * @property string|null $http_method
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $status
 * @property int|null $http_code
 * @property string|null $message
 * @property array<array-key, mixed>|null $request_payload
 * @property array<array-key, mixed>|null $response_payload
 * @property string|null $error_detail
 * @property string|null $file_path
 */
class InventoryCountRequestLog extends Model
{
    public const string STATUS_RECEIVED = 'RECIBIDO';

    public const string STATUS_FINISHED = 'TERMINADO';

    public const string STATUS_ERROR = 'ERROR';

    protected $table = 'inventory_count_request_logs';

    protected $fillable = [
        'transaction_id',
        'inventory_head_id',
        'inventory_card_id',
        'inventory_location_id',
        'location_id',
        'partnumber_name',
        'number_count',
        'quantity',
        'user_id',
        'username',
        'service_name',
        'endpoint',
        'http_method',
        'ip_address',
        'user_agent',
        'status',
        'http_code',
        'message',
        'request_payload',
        'response_payload',
        'error_detail',
        'file_path',
        'received_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
            'quantity' => 'float',
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    protected $attributes = [
        'status' => self::STATUS_RECEIVED,
    ];

    protected static function booted(): void
    {
        // El transaction_id siempre viaja como UUID, aunque no se envíe al crear el registro.
        static::creating(function (InventoryCountRequestLog $log): void {
            $log->transaction_id ??= (string) Str::uuid();
            $log->received_at ??= now();
        });
    }

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventorycard(): BelongsTo
    {
        return $this->belongsTo(InventoryCardCount::class, 'inventory_card_id', 'id');
    }

    public function inventorylocationcount(): BelongsTo
    {
        return $this->belongsTo(InventoryLocationCount::class, 'inventory_location_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Marca la petición como TERMINADA guardando la respuesta entregada al cliente.
     */
    public function markAsFinished(array $responsePayload, int $httpCode, string $message): void
    {
        $this->update([
            'status' => self::STATUS_FINISHED,
            'http_code' => $httpCode,
            'message' => $message,
            'response_payload' => $responsePayload,
            'processed_at' => now(),
        ]);
    }

    /**
     * Marca la petición como ERROR por validación fallida o excepción durante el proceso.
     */
    public function markAsError(array $responsePayload, int $httpCode, string $message, ?string $errorDetail = null): void
    {
        $this->update([
            'status' => self::STATUS_ERROR,
            'http_code' => $httpCode,
            'message' => $message,
            'response_payload' => $responsePayload,
            'error_detail' => $errorDetail,
            'processed_at' => now(),
        ]);
    }
}
