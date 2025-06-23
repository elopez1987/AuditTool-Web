<?php

namespace App\Models\General;

use App\User;
use Auth;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Storage;

class EvidencePhoto extends Model
{
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

//    Funciones auxiliares
    static function getPhotosForModel($id, $model)
    {
        return EvidencePhoto::where('model_id', $id)->where('model_type', '=', $model)->get();
    }

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (EvidencePhoto $model) {
                $model->createuser = Auth::id();
            });
        }
    }


// TODO Validar las funciones y remplazarlas por la global y eliminar estas
    public static function uploadFileToServer($request, $centerid, $fileroute)
    {
        $file = $request->file('file');
        $nameFile = Str::random(10) . $request->file('file')->getClientOriginalName();
        $pathbase = Storage::disk('local')->put('public/' . $centerid . '/' . $fileroute . '/' . $nameFile, File::get($file));
        if ($pathbase) {
            $path = 'public/' . $centerid . '/' . $fileroute . '/' . $nameFile;
            return $path;
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


}
