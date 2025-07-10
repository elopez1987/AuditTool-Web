<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex de recibo</title>
    <style>
        .container {
            padding: 0;
            border: 0;
            width: 720px;
            height: 612px;
        }

        html {
            margin: 0;
            font-weight: 400;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 8px;
            color: black;
            font-weight: 400;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        /*tr:nth-child(even) {*/
        /*    background-color: #f2f2f2*/
        /*}*/

        .border-none {
            border: 0;
        }
    </style>


</head>

<body>

<div class="container">
    <div class="row">
        <table>
            <th style="width: 120px;text-align: center">
                <img src="img/taxion-logistic-png" alt="" style="width: 80px;height: 40px">
            </th>
            <th>
                <div class="row">
                    <table class="border-none">
                        <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 80%;padding: 0;border-bottom: 0;">
                            CHECK LIST DE DESCARGA
                        </th>
                        <th style="height: 30px;border-top: 0;border-right: 0;padding: 0;border-bottom: 0;">
                            Folio de Unidad: <br>
                        </th>
                    </table>
                </div>
                <div>
                    <table class="border-none">
                        <th style="width: 40%;border-bottom: 0;border-left: 0;text-align: center;">

                        </th>
                        <th style="width: 40%;border-bottom: 0;"></th>
                        <th style="width:20%;border-bottom: 0;border-right: 0;">
                            {{$receipt->folio ? $receipt->folio->id : ''}}
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row" style="height: 5px">
        <table class="border-none">
            <th style="border-top: 0;height: 10px">
                <div class="row">
                    <table class="border-none">
                        <th style="width: 33%;height: 10px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;">
                            Embarque:{{$receipt->shipmentid}}
                        </th>
                        <th style="width: 33%;height: 10px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;margin-left: 10px">
                            Factura:{{$receipt->billoflading}}
                        </th>
                        <th style="width: 33%;height: 10px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;border-right:0;text-align: left;">
                            O. de compra :{{$receipt->oc}}
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>


    <div class="row" style="margin-top: 10px">
        <table class="border-none">
            <th style="height: 10px;width: 50%;border-top: 1px solid black;">Ingreso de unidad: {{$receipt->folio() ? $receipt->folio->entrydate : ''}} </th>
            <th style="width: 50%;border-top: 1px solid black;text-align: left">Proveedor: {{$receipt->provider ? $receipt->provider->name : ''}}</th>

{{--            <th style="width: 19.8%;border-top: 1px solid black;border-bottom: 1px solid;">Hora de Ingreso a Almacén</th>--}}
{{--            <th style="width: 10%;border-top: 1px solid black;border-bottom: 1px solid;"></th>--}}
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="height: 10px;width: 33%;border-top: 0;">Operador : {{$receipt->folio ? $receipt->folio->operatorname : ''}}</th>
            <th style="width: 33%;border-top: 0px;text-align: left">Placa de caja : {{$receipt->folio ? $receipt->folio->boxnumber : ''}}</th>
            <th style="width: 33%;border-top: 0px;text-align: left">Cortina : {{$receipt->folio ? $receipt->folio->location->name : ''}}</th>
        </table>
    </div>


    <div class="row">
        <table class="border-none">
            <th style="border-top: 0px;width: 50%;height: 10px;">Linea de Transporte :  {{$receipt->folio ? $receipt->folio->carrier->name : ''}}</th>
            <th style="border-top: 0px;text-align: left">Centro: {{$receipt->warehouse ? $receipt->warehouse->name : ''}} </th>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <th class="border-none" style="text-align: center">Datos de la descarga</th>
        </table>
    </div>
    <div class="row" >
        <table>
            <thead >
                <tr>
                    <th>Sku</th>
                    <th>Descripción</th>
                    <th>Lote</th>
                    <th>Cantidad solicitada</th>
                    <th>Cantidad verificada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr>
                        <td>{{$stock->prtnum}}</td>
                        <td>{{$stock->lngdsc}}</td>
                        <td>{{$stock->expirecode}}</td>
                        <td>{{$stock->quantitytrue}}</td>
                        <td>{{$stock->totalescaneado}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <th class="border-none" style="text-align: center">Datos de contenedores</th>
        </table>
    </div>
    <div class="row" >
        <table>
            <thead >
            <tr>
                <th>Sku</th>
                <th>Descripción</th>
                <th>Lote</th>
                <th>Lpn</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody>
            @foreach($containers as $lpn)
                <tr>
                    <td>{{$lpn->partnumber ? $lpn->partnumber->prtnum : ''}}</td>
                    <td>{{$lpn->partnumber ? $lpn->partnumber->lngdsc : ''}}</td>
                    <td>{{$lpn->batch}}</td>
                    <td>{{$lpn->lpnid}}</td>
                    <td>{{$lpn->quantity}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <th class="border-none" style="text-align: center">Datos de incidencias</th>
        </table>
    </div>
    <div class="row" >
        <table>
            <thead >
            <tr>
                <th>Sku</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incidences as $incidence)
                <tr>
                    <td>{{$incidence->partnumber ? $incidence->partnumber->prtnum : ''}}</td>
                    <td>{{$incidence->partnumber ? $incidence->partnumber->lngdsc : ''}}</td>
                    <td>{{$incidence->categoryincidence ? $incidence->categoryincidence->description : ''}}</td>
                    <td>{{$incidence->subcategoryincidence ? $incidence->subcategoryincidence->description : ''}}</td>
                    <td>{{$incidence->quantity}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div class="row" style="margin-top: 20px">
        {{--    Fotos--}}
        @if ($images->isNotEmpty())
            <div style="page-break-after:always;"></div>
            @foreach($images as $image)
                <img src='{{asset('storage/' . $image->path)}}' style="width: 200px;height:200px;margin-top: 50px;margin-left: 10px;
                 margin-right: 10px;border: 2px solid #cfdd01 !important" >
            @endforeach
        @endif
    </div>



    <div class="row" style="margin-top: 30px ">
        <table class="border-none">
            <tr>
                <td class="border-none" style="text-align: center;font-size: 11px">{{$receipt->analista}}</td>
                <td class="border-none" style="text-align: center;font-size: 11px">{{$receipt->monta}}</td>
                <td class="border-none" style="text-align: center;font-size: 11px">{{$receipt->verificador}}</td>
                <td class="border-none" style="text-align: center"></td>
            </tr>
            <tr>
                <td class="border-none" style="text-align: center;font-size: 10px;">
                    <hr>
                    Nombre y Firma <br>
                    ANALISTA DE RECIBO
                </td>
                <td class="border-none" style="text-align: center;font-size: 10px">
                    <hr>
                    Nombre y Firma <br>
                    MONTACARGUISTA RECIBO
                </td>
                <td class="border-none" style="text-align: center;font-size: 10px">
                    <hr>
                    Nombre y Firma <br>
                    VERIFICADOR RECIBO
                </td>
                <td class="border-none" style="text-align: center;font-size: 10px">
                    <hr>
                    Nombre y Firma <br>
                    SUPERVISOR RECIBO
                </td>
            </tr>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <div style="text-align: center;font-size: 9px;margin-top: 6px;">
            <span style="text-align: center;">Este documento es propiedad de Almacenaje y Distribución Avior S.A de C.V., queda prohibida su reproducción total o parcial.</span>
            <br>
        </div>
    </div>


</div>
</body>

</html>
