<?php

namespace App\Http\ControllersApp\SafetyTours;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SafetyTours\SaveNewTagRequest;
use App\Http\Resources\SafetyTours\StorageTagsResource;
use App\Models\SafetyTours\StorageTags;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class StorageTagsController extends Controller
{
    use ResponseApi;

    public function index()
    {
        return $this->showAll('storagetagindex',
            StorageTagsResource::collection(StorageTags::controlAccessUser()->get()));
    }

    public function store(SaveNewTagRequest $request)
    {
        $centerid = $this->getCenterForUserForApi();
        $tag = StorageTags::create($request->validated() + ['centerid' => $centerid]);
        return $this->showOne('storagetagstore', new StorageTagsResource($tag));
    }

    public function update(Request $request)
    {
       $tag = StorageTags::controlAccessUser()->where('tagid', $request['tagid'])->first();
        if (empty($tag)) {
            return  $this->getResponseWithErrors('storagetagupdate',
                'El número de tag ingresado no existe, favor de validar');
        }
        $tag->update($request->except('centerid'));
        return  $this->showOne('storagetagupdate', new StorageTagsResource($tag));
    }

    public function destroy(Request $request)
    {
        $storageTags = StorageTags::controlAccessUser()->where('tagid', $request['tagid'])->first();
        if (empty($storageTags)) {
            return  $this->getResponseWithErrors('storagetagupdate',
                'El número de tag ingresado no existe (Eliminar), favor de validar');
        }
        $storageTags->statusid = 'TAG-02';
        $storageTags->save();
        $storageTags->delete();
       return  $this->getResponseSuccess('storagetagdelete','Tag eliminado con exito');
    }


}
