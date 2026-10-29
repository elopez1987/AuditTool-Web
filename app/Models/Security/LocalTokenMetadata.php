<?php

declare(strict_types=1);

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passport\Token;

final class LocalTokenMetadata extends Model
{
    protected $table = 'token_metadata';

    protected $guarded = ['id'];

    protected $hidden = ['credential_fingerprint', 'user_agent', 'additional_claims'];

    protected $casts = [
        'is_revoked' => 'boolean',
        'expires_at' => 'immutable_datetime',
        'last_used_at' => 'immutable_datetime',
        'revoked_at' => 'immutable_datetime',
        'session_started_at' => 'immutable_datetime',
        'session_expires_at' => 'immutable_datetime',
    ];

    public function accessToken(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Users\StorageUserDevice::class, 'device_id');
    }

    public function freshTimestamp(): Carbon
    {
        return Carbon::now('UTC');
    }

    protected function asDateTime(mixed $value): Carbon
    {
        return is_string($value) ? Carbon::parse($value, 'UTC') : parent::asDateTime($value);
    }
}
