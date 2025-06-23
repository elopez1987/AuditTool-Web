<?php

namespace App\Models\General;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $id)
 */
class StorageComment extends Model
{
    protected $table = 'storagecomments';
    protected $fillable = [
        'modeltype', 'modelid', 'comment', 'createuser',
        'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }


    static function getCommentForModel($id, $model)
    {
        return StorageComment::where('modelid', $id)->where('modeltype', $model)->get();
    }

    static function createCommentary($model, $key, $request)
    {
        StorageComment::create([
            'modeltype' => $model,
            'modelid' => $key,
            'comment' => $request['comment'],
            'createuser' => Auth::user()->id,
        ]);
    }


}
