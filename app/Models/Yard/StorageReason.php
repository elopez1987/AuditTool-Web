<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;

class StorageReason extends Model
{
    protected $table = 'storagereasons';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'razonstatusid',
        'type',
        'configurationflag',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];

    public function scopeName($query, $name): void
    {
        if (trim($name) !== '') {
            $query->where('storagereasons.name', 'like', "%{$name}%");
        }
    }
}
