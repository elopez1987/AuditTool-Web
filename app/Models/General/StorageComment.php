<?php

namespace App\Models\General;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static> where(string $column, mixed $value)
 *
 * @property int $id
 * @property string $modeltype
 * @property int $modelid
 * @property string|null $comment
 * @property int $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereModeltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageComment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StorageComment extends Model
{
    use LogsActivity;

    protected $table = 'storagecomments';

    protected $fillable = [
        'modeltype', 'modelid', 'comment', 'createuser', 'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public static function createCommentary($model, $key, $request): void
    {
        StorageComment::create([
            'modeltype' => $model,
            'modelid' => $key,
            'comment' => $request['comment'],
            'createuser' => Auth::user()->id,
        ]);
    }
}
