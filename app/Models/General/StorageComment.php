<?php

namespace App\Models\General;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static where(string $string, mixed $id)
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
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
        'modeltype', 'modelid', 'comment', 'createuser',
        'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public static function getCommentForModel($id, $model)
    {
        return StorageComment::where('modelid', $id)->where('modeltype', $model)->get();
    }

    public static function createCommentary($model, $key, $request)
    {
        StorageComment::create([
            'modeltype' => $model,
            'modelid' => $key,
            'comment' => $request['comment'],
            'createuser' => Auth::user()->id,
        ]);
    }
}
