<?php

namespace App\Models\Shipments\OrderDelivery;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $message
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogImportFile whereMessage($value)
 * @mixin \Eloquent
 */
class LogImportFile extends Model
{
    public $timestamps = false;
    protected $table = 'logimports';

    protected $fillable = [
        'message', 'created_at',
    ];
}
