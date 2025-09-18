<?php

namespace App\Http\Controllers\Configuration\StorageTaskMails;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Mails\StorageTaskMail;

class StorageTaskMailsController extends BaseController
{
	public function index()
	{
		return view('Configuration.StorageTaskMail.index');
	}

	public function taskmailslist()
	{
		$data = StorageTaskMail::controlAccessUser()
			->with(['center:centerid,description', 'warehouse:warehouseid,name', 'status:statusid,name'])->get();
		return $this->showDataToView($data, 'Configuration.StorageTaskMail.partials.buttons');
	}

	public function destroy($id)
	{
		StorageTaskMail::destroy($id);
		return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
	}
}
