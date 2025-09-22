<?php

namespace App\Traits\Web\Izel;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Traits\Web\General\GetParameters;
use DB;
use Illuminate\Support\Facades\Http;

trait WebServiceIzel
{
    use GetParameters;

    public function sendDataToWMSIzel($receiptunit, $typeMovement)
    {
        $dataUserCenter = $this->getSystemCenterDistribution($receiptunit->centerid);
        $parameter = $this->getParametersOfCenter($receiptunit->centerid);
        $encodeDataUserAndToken = $this->getTokenWms($parameter, $dataUserCenter);
        if ($typeMovement === 'IN') {
            $dataFolioNumber = $this->getFolioNumberCheckIn($receiptunit->id);
            $url = $dataUserCenter->url . 'receiptunitadd';
        } elseif ($typeMovement === 'OUT') {
            $dataFolioNumber = $this->getFolioNumberCheckOut($receiptunit->id);
            $url = $dataUserCenter->url . 'receiptunitout';
        }
        $contentBody = json_encode($dataFolioNumber[0]);
        return Http::withHeaders(['Content-Type' => 'application/json', 'Authorization' => $encodeDataUserAndToken])
            ->withBody($contentBody, 'application/json')->post($url);
    }

    public function getTokenWms($parameter, $dataUserCenter)
    {
        $url = $this->getUrlLogin($parameter, $dataUserCenter);
        $credentials = ['userid' => $dataUserCenter->userapi, 'password' => $dataUserCenter->passwordapi];
        $contentBody = json_encode($credentials);
        $userEncode = base64_encode("{$dataUserCenter->userapi} {$dataUserCenter->passwordapi}");
        $response = Http::withHeaders(['Content-Type' => 'application/json', 'Authorization' => $userEncode])->withBody($contentBody)->post($url);
        if ($response->status() === 200) {
            $data = json_decode($response->getBody()->getContents());
            $tokenWms = $this->getTokenLogin($parameter, $data);
            return base64_encode("{$dataUserCenter->userapi} {$dataUserCenter->passwordapi} {$tokenWms}");
        }
        return null;
    }

    public function getSystemCenterDistribution($centerid)
    {
        return SystemCenterDistribution::whereCenterid($centerid)->first();
    }

    /**
     * @param $parameter
     * @param $dataUserCenter
     *
     * @return string
     */
    public function getUrlLogin($parameter, $dataUserCenter): string
    {
        if (in_array('SDC-07', (array) $parameter)) {
            $url = 'http://172.24.90.54/gas401/ws/r/ws_izelyard/login/gettoken';
        } else {
            $url = $dataUserCenter->url . 'gettoken';
        }
        return $url;
    }

    /**
     * @param $parameter
     * @param $data
     *
     * @return mixed
     */
    public function getTokenLogin($parameter, $data)
    {
        if (in_array('SDC-07', (array) $parameter)) {
            $tokenWms = $data->Token;
        } else {
            $tokenWms = $data->{'Token'};
        }
        return $tokenWms;
    }

    private function getFolioNumberCheckIn($folionumber)
    {
        return DB::select("
        select s7.center as centerid,s7.warehouse as warehouseid,ru.id as folionumber,ru.barcode,ru.shipmentid as shipmentid,ru.shipmentid as documentid,
       s.carrierid,s.name,s2.truckid,ru.containerseal,rt.operatorname,s3.unitstatusid as unitstatus,
       s4.unittypeid as unittype,ru.entrydate,0 as quantitytotal,0 as quantity,s5.locationid as Droplot,
       rt.trucknumber,concat(ru.boxnumber,'-',ru.econumber) as boxnumber,s6.auxiliary01 as zoneid,s3.unitstatusid as ldastatusid
                        from receiptunits ru
                        join receipttrucks rt on ru.foliotruck = rt.id
                        join shipmentcarriers s on ru.carrierid = s.id
                        join shipmenttrucks s2 on ru.truckid = s2.id
                        join storageunitstatus s3 on ru.unitstatusid = s3.id
                        join storageunittypes s4 on ru.unittypeid = s4.id
                        join storagezones s6 on ru.zoneid = s6.id
                        join systemwarehouses s7 on ru.warehouseid = s7.warehouseid
                        join storagelocations s5 on ru.locationid = s5.id
                        where ru.id=?;
        ", [$folionumber]);
    }

    private function getFolioNumberCheckOut($folionumber)
    {
        return DB::select('
        select s7.center as centerid,s7.warehouse as warehouseid,ru.id as folionumber,outputdate
            from receiptunits ru
            join systemwarehouses s7 on ru.warehouseid = s7.warehouseid
            where ru.id=?;
        ', [$folionumber]);
    }
}
