<?php

namespace App\Http\Controllers\Yard\RControl;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Traits\General\Responser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchOperatorInRC extends Controller
{
    use Responser;

    public function searchOperator(Request $request)
    {
        $centerid = $this->getCenterForUser();
        $token = $this->getToken($centerid);
        $endPoint = 'https://www.rcontrol.com.mx/Japi/get_driver_information_restfull_srv?';
        $url = $endPoint . 'token=' . $token;
        $contentBody = $this->createJsonFilter($request);
        $conect = new Client();
        $responseUrl = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
        if ($responseUrl->getStatusCode() == 200) {
            $result = json_decode($responseUrl->getBody()->getContents());
            return response()->json([
                    'operator' => [
                        'operatorname' => $result->{'driverInfo_result'}[0]->{'driver'}->{'driver_name'},
                        'statusoperator' => $result->{'driverInfo_result'}[0]->{'driver'}->{'driver_status'},
                        'licence' => $result->{'driverInfo_result'}[0]->{'driver'}->{'driver_license'},
                        'rfcoperator' => $result->{'driverInfo_result'}[0]->{'driver'}->{'driver_fiscal_code'},
                        'credential' => $result->{'driverInfo_result'}[0]->{'driver'}->{'driver_credential'},
                        'carriername' => $result->{'driverInfo_result'}[0]->{'transline'}->{'transline_name'},
                        'rfccarrier' => $result->{'driverInfo_result'}[0]->{'transline'}->{'transline_fiscal_code'},
                    ]
                ]
            );
        }
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

    public function createJsonFilter($request)
    {
        $json = array(
            array(
                "driver" => array(
                    "driver_credential" => $request['licencerc'],
                    "driver_fiscal_code" => $request['rfcoperator']
                )
            )
        );
        return json_encode($json);
    }


}
