<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchLpnIDRequest;
use App\Models\Configuration\Labels\InventoryLabelDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class PrintLabelWallmartController extends Controller
{
    use ResponseApi;


    public function index(SearchLpnIDRequest $request)
    {

        $lpn = ShipmentStock::where('lpnid', $request['lpnid'])->first();

        if (empty($lpn)) {
            return $this->getResponseWithErrors('printlabellpncheck', 'El LPN no existe, favor de validar.');
        }

        $order = OrderDeliverie::controlAccessUser()->where('id', $lpn->orderid)->first();
        $products = $this->getTotalProducts($request['lpnid']);
        $total = $this->getTotalStock($request['lpnid']);
        $label = $this->generateLabelWallmart($order, $products, $total);
        return $this->getResponseSuccess('printlabellpncheck', ['dataZpl' => $label]);
    }


    public function getTotalProducts($lpnid): array
    {
        return DB::table('shipmentstock as s')
            ->select('i.codedun', 'i.codeean', 's.quantity')
            ->leftJoin('inventorypartnumbers as i', function ($join) {
                $join->on('s.partnumberid', '=', 'i.id');
                $join->on('s.centerid', '=', 'i.centerid');
            })->where('lpnid', $lpnid)->get()->toArray();
    }


    public function getTotalStock($lpnid)
    {
        return DB::table('shipmentstock as s')
            ->select('s.lpnid', DB::raw('SUM(quantity) as totalcajas'))
            ->where('lpnid', $lpnid)
            ->groupBy('lpnid')->first();
    }

    //    Etiquetas de stock

    public function generateLabelWallmart($shipment, $products, $total)
    {
        $nameFile = "LabelWallmart.txt";
        $file = @fopen($nameFile, "w");

        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LBL-WALLMART');
        foreach ($detailsLabel as $item) {

            if (str_contains($item->description, '$shipment')) {
                $valor = str_replace('$shipment', $shipment->shipmentid, $item->description);
            } elseif (str_contains($item->description, '$customername')) {
                $valor = str_replace('$customername', $shipment->customer->name, $item->description);
            } elseif (str_contains($item->description, '$oc')) {
                $valor = str_replace('$oc', $shipment->confirmation, $item->description);
            } elseif (str_contains($item->description, '$confirmation')) {
                $valor = str_replace('$confirmation', $shipment->confirmation, $item->description);
            } elseif (str_contains($item->description, '$total')) {
                $valor = str_replace('$total', $total->totalcajas, $item->description);
            } elseif (str_contains($item->description, '$lpn')) {
                $valor = str_replace('$lpn', $total->lpnid, $item->description);
            }
            elseif (str_contains($item->description, '$upc01')) {
                $valor = str_replace('$upc01',$products[0]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity01')) {
                $valor = str_replace('$quantity01',$products[0]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc02')) {
                $valor = str_replace('$upc02',$products[1]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity02')) {
                $valor = str_replace('$quantity02',$products[1]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc03')) {
                $valor = str_replace('$upc03',$products[2]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity03')) {
                $valor = str_replace('$quantity03',$products[2]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc04')) {
                $valor = str_replace('$upc04',$products[3]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity04')) {
                $valor = str_replace('$quantity04',$products[3]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc05')) {
                $valor = str_replace('$upc05',$products[4]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity05')) {
                $valor = str_replace('$quantity05',$products[4]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc06')) {
                $valor = str_replace('$upc06',$products[5]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity06')) {
                $valor = str_replace('$quantity06',$products[5]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc07')) {
                $valor = str_replace('$upc07',$products[6]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity07')) {
                $valor = str_replace('$quantity07',$products[6]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc08')) {
                $valor = str_replace('$upc08',$products[7]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity08')) {
                $valor = str_replace('$quantity08',$products[7]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc09')) {
                $valor = str_replace('$upc09',$products[8]->codeean ?? '', $item->description);
            }elseif (str_contains($item->description, '$quantity09')) {
                $valor = str_replace('$quantity09',$products[8]->quantity ?? '', $item->description);
            }

            elseif (str_contains($item->description, '$upc10')) {
                $valor = str_replace('$upc10  ', $products[9]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity10')) {
                $valor = str_replace('$quantity10',$products[9]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc11')) {
                $valor = str_replace('$upc11  ', $products[10]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity11')) {
                $valor = str_replace('$quantity11',$products[10]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc12')) {
                $valor = str_replace('$upc12  ', $products[11]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity12')) {
                $valor = str_replace('$quantity12',$products[11]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc13')) {
                $valor = str_replace('$upc13  ', $products[12]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity13')) {
                $valor = str_replace('$quantity13',$products[12]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc14')) {
                $valor = str_replace('$upc14  ', $products[13]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity14')) {
                $valor = str_replace('$quantity14',$products[13]->quantity ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$upc15')) {
                $valor = str_replace('$upc15  ', $products[14]->codeean ?? '', $item->description);
            }
            elseif (str_contains($item->description, '$quantity15')) {
                $valor = str_replace('$quantity15',$products[14]->quantity ?? '', $item->description);
            }
            else {
                $valor = $item->description;
            }
            fwrite($file, $valor . "\n");
        }
        fclose($file);
        return file_get_contents($nameFile);
    }

}
