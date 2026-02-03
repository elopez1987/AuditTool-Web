<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DETALLE DE ACCESO PARA EMBARQUES</title>
    <style>
        .container {
            padding: 0;
            border: 0;
            width: 690px;
        }

        html {
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 11px;
            color: black;

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
        }

        th, td {
            text-align: center;
            padding: 5px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        .logo {
            width: 150px;
            height: 60px;
            margin: auto;
        }

        .formato {
            padding-top: 10px;
        }

        .w20 {
            width: 20%;
        }

        .nota {
            color: red;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
            font-weight: 400;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <table>
            <th style="width: 33%;">
                <img src="{{storage_path('app/' . $data->center->auxiliary04)}}" alt=""
                     style="height: 65px;width: 150px;" class="logo">
            </th>
            <th style="width: 33%;">
                <h3 class='formato'>
                    CONTROL DE ACCESO
                    <br>
                    EMBARQUES
                </h3>
            </th>
            <th style="text-align: left;font-size: 9px;">
                <span>Formato : </span><br>
                <span>Código : </span><br>
                <span>Nivel de Revisión : </span><br>
                <span>Fecha de Aplicación : </span><br>
                <span>Páginas : 1 de 1</span>
            </th>
        </table>
    </div>
    <div class="row" style="margin-bottom: 8px;margin-top: 20px;">
        <span style="font-size: 11px;color: black;margin-bottom: 5px;">DATOS GENERALES.</span>
    </div>
    <table>
        <thead>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">OPERADOR:</th>
            <th class="w30"
                style="text-align: left;padding: 2px;">{{$data->transport ? $data->transport->operatorname : ''}} </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">TELEFONO</th>
            <th class="w30"
                style="text-align: left;padding: 2px;">{{$data->transport ? $data->transport->phone : ''}} </th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">LICENCIA</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"
                colspan="3"> {{$data->transport ? $data->transport->drivernumber : ''}}</th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">EMBARQUE</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff;font-size: 16px !important;color: red"> {{$data->shipmentid}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">N.CONFIRMACIÓN</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->confirmation}}
            </th>
        </tr>

        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">CLIENTE / DESTINO</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff;font-size: 16px !important;color: red"
                colspan="3"> {{$data->customer? $data->customer->name : ''}}
                / {{$data->customer ? $data->customer->city : ''}}</th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">LINEA</th>
            <th style="text-align: left;padding: 2px;background-color:#ffffff"
                colspan="3"> {{$data->carrier ? $data->carrier->name : ''}}</th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">PLACA DE CAJA</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff;font-size: 14px;color: blue">{{$data->transport ? $data->transport->boxnumber : ''}} </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">PLACA DE TRACTO</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff;font-size: 14px;color: blue"> {{$data->transport ? $data->transport->trucknumber : ''}}</th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">F. de Carga</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->loadingdate}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">F. de entrega</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->downloaddate}}
            </th>
        </tr>
        </thead>
    </table>

    <div class="row" style="margin-top: 50px!important;">
        <table style="width: 50% !important;">
            <thead>
            <tr>
                <th style="color: darkblue;font-size: 18px !important;text-align: center">CÓDIGO DE ACCESO
                </th>
            </tr>
            <tr>
                <th class="w30" style="text-align: center;padding: 2px;padding-left: 10px;">
                    <div style="text-align: center;">
                        <div style="display: inline-block;">
                            {!! DNS2D::getBarcodeHTML($data->transport->auxiliary05, 'QRCODE',3,3) !!}
                        </div>
                    </div>
                </th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="row" style="margin-top: 100px!important;">
        <table>
            <th style="color: darkblue;font-size: 18px !important;text-align: center">
                CÓDIGO DE ACCESO PARA PARKS
                <br>
                <span style="text-align: center;font-size: 12px !important;color: black">Favor de presentar este código QR en la entrada principal del parque.</span>
            </th>
        </table>
    </div>
    @if($data->transport)
        <div class="row">
            <table style="border: 1px solid black">
                <tbody>
                <tr>
                    <th style="width: 50%">
                        @if($data->transport->carrier->qr != null)
                            <img src='{{asset('storage/' . $data->qr)}}' alt="" style="max-width: 200px;height: 200px">
                        @endif
                    </th>
                    <th style="width: 50%">
                        @if($data->transport->carrier->logo != null)
                            <img src='{{asset('storage/' . $data->logo)}}' alt=""
                                 style="height: 200px;max-width: 200px">
                        @endif
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
    @endif

    <h5 class='nota' style="margin-top:50px">
        La información registrada en este documento es responsabilidad de la linea de transporte y quien la comparta.
    </h5>
</div>

</body>

</html>
