<?php

namespace App\Models\General;

use App\User;
use Auth;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Storage;

/**
 * @property string|null $centerid
 * @property string|null $warehouseid
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $path
 * @property int $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvidencePhoto whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class EvidencePhoto extends Model
{
    use LogsActivity;

    protected $table = 'storageevidencephotos';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'model_type',
        'model_id',
        'path',
        'createuser',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    //    Funciones auxiliares
    public static function getPhotosForModel($id, $model)
    {
        return EvidencePhoto::where('model_id', $id)->where('model_type', '=', $model)->get();
    }

    public static function uploadFileToServer($request, $centerid, $fileroute)
    {
        $file = $request->file('file');
        $nameFile = Str::random(10) . $request->file('file')->getClientOriginalName();
        $pathbase = Storage::disk('local')->put('public/' . $centerid . '/' . $fileroute . '/' . $nameFile, File::get($file));
        if ($pathbase) {
            return 'public/' . $centerid . '/' . $fileroute . '/' . $nameFile;
        }
    }

    public static function registerEvidence(string $modeltype, $folioid, string $pathdb, string $typeFile, $shipment)
    {
        EvidencePhoto::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'model_type' => $modeltype,
            'model_id' => $folioid,
            'path' => $pathdb,
            'createuser' => Auth::user()->id,
            'auxiliary01' => $typeFile,
            'auxiliary02' => $shipment->centerid,
            'auxiliary03' => $shipment->warehouseid,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (EvidencePhoto $model) {
                $model->createuser = Auth::id();
            });
        }
    }
}
