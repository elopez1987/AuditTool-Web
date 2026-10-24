<?php

namespace App\Http\Controllers\Configuration\Mails;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Mails\CreateMailRequest;
use App\Models\Configuration\Mails\SystemMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemMailController extends BaseController
{
    public function index()
    {
        $this->authorize('systemmails.index');

        return view('Configuration.SystemMails.index');
    }

    public function getDataMails()
    {
        $data = SystemMail::with([
            'status' => function ($q) {
                $q->select('statusid', 'name');
            },
        ])->select('systemmails.*');

        return $this->showDataToView($data, 'Configuration.SystemMails.partials.buttons');
    }

    public function create()
    {
        $this->authorize('systemmails.create');

        return view('Configuration.SystemMails.create');
    }

    public function store(CreateMailRequest $request): RedirectResponse
    {
        $mail = new SystemMail($request->validated());
        $mail->save();

        return redirect('systemmails/'.$mail->mailid.'/createMailDetail');
    }

    public function edit(SystemMail $systemmail)
    {
        $this->authorize('systemmails.edit');
        $status = $this->getStatusForSchema('STS');

        return view(
            'Configuration.SystemMails.edit',
            compact('status', 'systemmail')
        );
    }

    public function update(Request $request, SystemMail $systemmail): RedirectResponse
    {
        $systemmail->update($request->all());

        return $this->infoResponse('systemmails.index');
    }
}
