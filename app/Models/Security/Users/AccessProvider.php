<?php

namespace App\Models\Security\Users;

use App\Models\Receipt\ReceiptProvider;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessProvider extends Model
{
    protected $table = 'securityaccessproviders';

    protected $fillable = [
        'centerid',
        'providerid',
        'username',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid');
    }

    public function username(): BelongsTo
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
