<?php

namespace App\Console\Commands\General;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMessageForReceiptEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-message-for-receipt-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un mensaje a los usuarios al arribo de una unidad para descarga.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $warehouses = SystemWarehouse::where('statusid', '=', 'E')->get();
        if (!empty($warehouses)) {
            foreach ($warehouses as $warehouse) {
                $parameter = $warehouse->configurationflag;
                if (!empty($parameter) && in_array('SWH-29', $parameter)) {
                    $tasks = StorageTaskWhatsapp::where('warehouseid', $warehouse->warehouseid)->where('whatsappid', 'WTH-INGREREC')->where('statusid', 'E')->get();
                    $contacts = $this->getContacts($warehouse->warehouseid);
                    if (!empty($tasks)) {
                        foreach ($tasks as $task) {
                            $providername = ReceiptProvider::where('centerid', $task->centerid)
                                ->where('warehouseid', $task->warehouseid)->where('id', $task->value01)->value('name');
                            foreach ($contacts as $contact) {
                                try {
                                    $data = $this->getBodyData($task, $contact, $providername);
                                    $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                                    $respJson = $response->getBody();
                                    $this->createLog(json_decode($respJson), $contact, $task);
                                } catch (\Exception $e) {
                                    Log::error($e->getMessage());
                                    Log::error('Error al enviar mensaje de ingreso de proveedor a CeDis.');
                                }
                            }
                            $task->statusid = 'D';
                            $task->save();
                        }
                    }
                }
            }
        }
    }

    private function getContacts($warehouseid)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', "WTH-INGREREC")
            ->where('warehouseid', '=', $warehouseid)->select(['phonenumber'])->get();
    }


    public function createLog($resp, $contact, $model)
    {
        LogMessageWhatsApp::create([
            'centerid' => $model->centerid,
            'shipmentid' => '',
            'folionumber' => $model->modelid,
            'chatid' => $contact->phonenumber,
            'message' => 'Registro de proveedor para descarga en CeDis: .',
            'value01' => 'WTH-INGREREC',
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
            'value04' => $model->modeltype,
        ]);
    }


    public function getBodyData($task, $contact, $providername)
    {
        $receiptUnit = ReceiptUnit::where('receiptunits.centerid', $task->centerid)
            ->join('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->where('receiptunits.warehouseid', $task->warehouseid)->where('receiptunits.id', $task->modelid)
            ->select(['receiptunits.boxnumber', 'receiptunits.shipmentid','shipmentcarriers.name as carriername'])->first();
        return array(
            "token" => "mheZM8xq46kdZv6tCCIyvrtj8vG7nyNx",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "arribodedescargaacedis",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => $providername),
                        array("type" => "text", "text" => $receiptUnit->boxnumber),
                        array("type" => "text", "text" => $receiptUnit->carriername),
                        array("type" => "text", "text" => $receiptUnit->shipmentid),
                    )
                )
            ),
            "phone" => '1' . $contact->phonenumber
        );
    }

}
