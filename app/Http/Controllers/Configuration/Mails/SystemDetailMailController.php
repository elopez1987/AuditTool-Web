<?php

namespace App\Http\Controllers\Configuration\Mails;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Mails\CreateDetailMailRequest;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Configuration\Mails\SystemMail;
use Illuminate\Http\RedirectResponse;

class SystemDetailMailController extends BaseController
{
    public function createMailDetail($id)
    {
        $systemmail = SystemMail::where('mailid', $id)->first();
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $details = SystemDetailMail::controlAccessUser()->where('mailid', $systemmail->mailid)->get();
        return view(
            'Configuration.SystemMails.createdetails',
            compact('systemmail', 'centers', 'warehouses', 'details')
        );
    }

    public function storeMailDetail(CreateDetailMailRequest $request, $id): RedirectResponse
    {
        $systemmail = SystemMail::where('mailid', $id)->first();
        $detailMail = new SystemDetailMail($request->all() + ['mailid' => $systemmail->mailid]);
        $detailMail->save();
        return $this->redirectBackSuccess();
    }

    public function destroy($id): RedirectResponse
    {
        SystemDetailMail::destroy($id);
        return $this->redirectBackInfo();
    }
}
