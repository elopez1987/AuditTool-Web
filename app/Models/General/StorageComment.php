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
