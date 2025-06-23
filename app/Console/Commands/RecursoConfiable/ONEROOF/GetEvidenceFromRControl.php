<?php

namespace App\Console\Commands\RecursoConfiable\ONEROOF;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageShipmentsPods;
use App\Traits\GetParameters;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PDF;

class GetEvidenceFromRControl extends Command
{
    use GetParameters;

    protected $signature = 'rc:getevidencefromrcontrol';

    protected $description = 'Recupera la evidencia de los pods de los embarque de one roof';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $parameterCenter = $this->getParametersOfCenter('HENKELTOL01');
        if (!empty($parameterCenter) && in_array('SDC-06', $parameterCenter)) {
            $endPoint = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?';
            $token = $this->getToken('HENKELTOL01');
            $url = $endPoint . 'token=' . $token;
            $shipments = $this->getShipments();
            foreach ($shipments as $shipment) {
                $shipment = OrderDeliverie::where('id', $shipment->id)->first();
                $parameter = $this->getParameterOfCustomer($shipment->customerid);
                if (!empty($parameter) && in_array('OCF-02', $parameter)) {
                    $pod = StorageShipmentsPods::where('orderid', $shipment->id)
                        ->select(['recordid', 'orderid', 'pathfile'])->first();
                    if (empty($pod)) {
                        $contentBody = $this->createJsonFilter($shipment->shipmentid);
                        $conect = new Client();
                        $response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
                        if ($response->getStatusCode() == 200) {
                            $result = json_decode($response->getBody()->getContents());
                            if (!empty($result)) {
                                $records = $result[0]->{'destiny'}[0]->{'track'};
                                foreach ($records as $track) {
                                    if (!empty($track->{'evidence'})) {
                                        $files = $track->{'evidence'};
                                        $fortmat = 'Shipments.MonitorPods.pdf';
                                        $pdf = PDF::loadView($fortmat, compact('files', 'shipment'));
                                        $path = storage_path() . '/app/public/' . 'HENKELTOL01' . '/EvidenceShipment/';
                                        $fileName = $shipment->shipmentid . '-' . Str::random(10) . '.pdf';
                                        $pdf->save($path . '/' . $fileName);
                                        $path = 'public/' . 'HENKELTOL01' . '/EvidenceShipment/' . $fileName;
                                        $this->createRegisterPOD($shipment, $path);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function createJsonFilter($shipment)
    {
        $json = array(
            "search_filters" => array(
                "key" => "STOP",
                "company_type" => "SUPPLIER",
                "shipment" => $shipment,
                "initial_date" => Carbon::now()->subDays(35)->toDateTimeString(),
                "final_date" => Carbon::now()->addDays(15)->toDateTimeString(),
            )
        );
        return json_encode($json);
    }

    public function getToken($centerid)
    {
        $endPoint = 'GetUserToken?';
        $dataapi = $this->getDataApi($centerid);
        $url = $dataapi->value02 . $endPoint . 'arg00=' . $dataapi->value03 . '&' . 'arg01=' . $dataapi->value04;
        $responseRControl = Http::post($url);
        $data = json_decode($responseRControl->getBody()->getContents());
        $token = $data->token;
        return $token;
    }

    private function getDataApi($centerid): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'API-RC')
            ->where('value01', $centerid)->first();
    }

    /**
     * @param $shipment
     * @param string $path
     * @return void
     */
    public function createRegisterPOD($shipment, string $path): void
    {
        StorageShipmentsPods::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'pathfile' => $path,
            'createuser' => 'MXAPIRC',
        ]);
    }

    public function getShipments()
    {
        $shipments = DB::table('orderdeliveries')
            ->select('id', 'shipmentid', 'workday', 'customerid', 'centerid', 'warehouseid')
            ->whereIn('customerid', (function ($query) {
                $query->from('ordercustomers')
                    ->select('id')
                    ->whereNotNull('configurationflag')
                    ->whereRaw("charindex('OCF-02', configurationflag) > 0");
            }))
            ->whereNotIn('id', (function ($query) {
                $query->from('storageshipmentspods')
                    ->select('orderid')
                    ->where('centerid', '=', 'HENKELTOL01');
            }))
            ->where('workday', '>', Carbon::now()->subDays(10))
            ->where('statusid', '=', 'DH')
            ->where('centerid', '=', 'HENKELTOL01')
            ->get();
        return $shipments;
    }

}
