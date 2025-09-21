<?php

namespace App\Http\Controllers\Configuration\WhatsApp;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\WhatsApp\CreateDetailWhatsApp;
use App\Models\Configuration\WhatsApp\NotificationWhatsApp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;

class WhatsAppDetailController extends BaseController
{
    public function createNotificationDetail(NotificationWhatsApp $systemwhatsapp)
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $details = SystemDetailWhatsApp::controlAccessUser()->with('center', 'warehouse')
            ->where('whatsappid', $systemwhatsapp->whatsappid)->get();
        return view(
            'Configuration.SystemWhatsApps.createdetails',
            compact('systemwhatsapp', 'centers', 'warehouses', 'details')
        );
    }
    public function storenotificationdetail(CreateDetailWhatsApp $request, NotificationWhatsApp $systemwhatsapp)
    {
        $detailWhatsApp = new SystemDetailWhatsApp($request->all() + ['whatsappid' => $systemwhatsapp->whatsappid]);
        $detailWhatsApp->save();
        return $this->redirectBackInfo();
    }
    public function deletecontact($id)
    {
        $this->authorize('deletecontact');
        SystemDetailWhatsApp::destroy($id);
        return $this->redirectBackInfo();
    }
}
