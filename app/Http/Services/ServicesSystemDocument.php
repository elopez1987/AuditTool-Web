<?php

namespace App\Http\Services;

use App\Models\Sigeo\SystemDocuments;
use File;
use Illuminate\Support\Str;
use Storage;


class ServicesSystemDocument
{

    public function addNewDocumentToFolder($center, $request)
    {
        $document = SystemDocuments::updateOrCreate([
            'centerid' => $center,
            'categoryid' => $request['categoryid'],
            'folderid' => $request['folderid'],
            'code' => $request['code'],
        ], [
            'name' => $request['nameDocument'],
            'level' => $request['level'],
            'comment' => $request['comment'],
            'aplicationdate' => $request['aplicationdate'],
            'updateddate' => $request['updateddate'],
        ]);

        $filePdf = $this->uploadFilePdf($document,$request);
        $document->filepdf = $filePdf;
        $document->save();

        return $document;
    }


    public function uploadFilePdf($document, $request)
    {
        $file = $request->file('filepdf');
        $nameFile = Str::random(10) . '-' . $request->file('filepdf')->getClientOriginalName();
        $nameFile = str_replace(' ', '', $nameFile);
        Storage::disk('local')->put('public/' . $document->centerid . '/DocumentSigeo/' . $nameFile, File::get($file));
        $pathdb = 'public/' . $document->centerid . '/DocumentSigeo/' . $nameFile;
        return $pathdb;
    }


    public function getAllDocumentForFolder($id)
    {
        return SystemDocuments::controlAccessUser()
            ->join('systemfoldercategories', 'systemdocuments.categoryid', '=', 'systemfoldercategories.id')
            ->join('users', 'systemdocuments.createuser', '=', 'users.username')
            ->where('systemdocuments.folderid', '=', $id)
            ->select([
                'systemfoldercategories.name as category',
                'users.name as username',
                'systemdocuments.name as document',
                'systemdocuments.code',
                'systemdocuments.filepdf',
                'systemdocuments.fileword',
                'systemdocuments.id',
                'systemdocuments.updated_at',
                'systemdocuments.aplicationdate',
            ])->get();
    }



}
