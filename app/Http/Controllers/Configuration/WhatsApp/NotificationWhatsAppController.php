<?php

namespace App\Http\Controllers\Configuration\WhatsApp;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\WhatsApp\CreateWhatsApp;
use App\Models\Configuration\WhatsApp\NotificationWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use Illuminate\Http\Request;

class NotificationWhatsAppController extends BaseController
{
    public function index()
    {
        $this->authorize('systemwhatsapps.index');
        return view('Configuration.SystemWhatsApps.index');
    }

    public function getDataNotifications(Request $request)
    {
        $data = NotificationWhatsApp::WhatsAppId($request->get('whatsappid'))->Name($request->get('name'))->with([
            'status' => function ($q) {
                $q->select('statusid', 'name');
            },
        ])->select('systemwhatsapps.*');
        return $this->showDataToView($data, 'Configuration.SystemWhatsApps.partials.buttons');
    }

    public function create()
    {
        $this->authorize('systemwhatsapps.create');
        return view('Configuration.SystemWhatsApps.create');
    }

    public function store(CreateWhatsApp $request)
    {
        $notification = new NotificationWhatsApp($request->all());
        $notification->save();
        return $this->redirectBackSuccess();
    }

    public function edit(NotificationWhatsApp $systemwhatsapp)
    {
        $this->authorize('systemwhatsapps.edit');
        $status = $this->getStatusForSchema('STS');
        return view('Configuration.SystemWhatsApps.edit', compact('systemwhatsapp', 'status'));
    }

    public function update(Request $request, NotificationWhatsApp $systemwhatsapp)
    {
        $systemwhatsapp->update($request->all());
        return $this->redirectBackInfo();
    }

    public function show(NotificationWhatsApp $systemwhatsapp)
    {
        $this->authorize('systemwhatsapps.show');
        return view('Configuration.SystemWhatsApps.show', compact('systemwhatsapp'));
    }

    public function getKardexEventsForWhatApp(Request $request)
    {
        $data = LogMessageWhatsApp::where('value01', '=', $request['whatsappid'])
            ->ForShipment($request->get('shipmentid'))
            ->ForNumber($request->get('phonenumber'))
            ->ForNumber($request->get('folionumber'))
            ->leftJoin('systemcenterdistributions', 'logsmessagewhatsapp.centerid', '=', 'systemcenterdistributions.centerid')
            ->select(['logsmessagewhatsapp.*', 'systemcenterdistributions.name as centername'])->orderBy('id', 'desc')->limit(1000)->get();
        return $this->showDataToView($data, '');
    }
}
