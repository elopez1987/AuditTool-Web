<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTROL DE CONTEO EN APT </title>
    <style>
        /* Definir orientación horizontal */
        @page {
            size: A4 landscape; /* Tamaño A4 en orientación horizontal */
            margin: 10mm; /* Margen opcional, ajustable según necesidad */
        }

        .container {
            padding: 0px;
            border: 0px;
            /*width: 720px;*/
            /*width: 820px;*/
            /*height: 12px;*/
            width: 100%;
        }

        html {
            margin: 0;
            font-weight: 400;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 9px;
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

        th,
        td {
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px !important;
        }

        th:first-child,
        td:first-child {
            text-align: left;
        }

        .border-none {
            border: 0px;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="row">
        <table>
            <th style="padding: 10px">

                <div class="row">
                    <table class="border-none">
                        <th
                            style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 18px;">
                            BRIDGESTONE DE MEXICO S.A DE C.V
                        </th>
                        <br>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <th
                            style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 18px;">
                            CONTROL DE CONTEO APT
                        </th>
                        <br>
                    </table>
                </div>

                <div class="row" style="margin-top: 10px">
                    <table class="border-none">
                        <th style="width: 50%;text-align: left;padding: 10px;font-size: 14px" class="border-none">
                            PLANTA CUERNAVACA 2025
                        </th>
                        <th style="width: 50%;text-align: left;padding-left: 90px;font-size: 14px" class="border-none">
                            Folio N°: {{$folio->folioid}}
                        </th>
                    </table>
                </div>

                <div class="row" style="margin-bottom: 10px;margin-top: 10px">
                    <table class="border-none">
                        <th class="border-none" style="height: 20px;padding-left: 10px;width: 20%">Nombre del oficial:
                        </th>
                        <th style="border-bottom: 1px solid;width: 20%"
                            class="border-none">{{$folio->user ? $folio->user->name : ''}}</th>
                        <th style="" class="border-none">Grupo</th>
                        <th style="border-bottom: 1px solid;width: 10%" class="border-none">
                            {{$folio->team}}
                        </th>
                        <th class="border-none" style="width: 10%">Turno</th>
                        <th class="border-none"
                            style="border-bottom: 1px solid;width: 10%">{{$folio->turn ? $folio->turn->name : ''}}</th>
                        <th class="border-none">Fecha</th>
                        <th class="border-none" style="border-bottom: 1px solid;width: 10%">{{$folio->workday}}</th>
                    </table>
                </div>

                <div class="row" style="margin-top: 20px">
                    <table style="padding: 10px">
                        <thead>
                        <tr>
                            <th style="height: 15px">HORA INICIO</th>
                            <th>NOMBNRE DEL OPERADOR</th>
                            <th>TRANSPORTE</th>
                            <th>SELLO</th>
                            <th>N°.CAJA</th>
                            <th>N°.ANDEN</th>
                            <th>HORA FINAL</th>
                            <th>EMBARQUE/REMISION</th>
                            <th>TOTAL DE LLANTAS</th>
                            <th>FIRMA APT</th>
                            <th>FIRMA OPERADOR</th>
                            <th>FIRMA GUARDIA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($detalleFolio as $controlCount)
                            <tr>
                                <th>{{$controlCount->order ? $controlCount->order->check->startdate : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->folio->tracto->operatorname : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->folio->carrier->name : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->folio->containerseal : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->folio->boxnumber : ''}}</th>
                                <th>{{$controlCount->location ? $controlCount->location->name : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->check->finishdate : ''}}</th>
                                <th>{{$controlCount->order ? $controlCount->order->shipmentid : ''}}</th>
                                <th>{{$controlCount->quantity}}</th>
                                <th>
                                    <img src="{{asset('storage/' . $controlCount->keysupervisor )}}" alt="Firma"
                                         style="height: 40px;max-height: 40px"/>
                                </th>
                                <th>
                                    <img src="{{asset('storage/' . $controlCount->keyoperator )}}" alt="Firma"
                                         style="height: 40px;max-height: 40px"/>
                                </th>
                                <th>
                                    <img src="{{asset('storage/' . $controlCount->keyguard )}}" alt="Firma"
                                         style="height: 40px;max-height: 40px"/>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-top: 100px"
                           class="border-none">
                        <tr>
                            <td class="border-none"></td>
                            <td class="border-none">{{$folio->namesupervisor}} <img
                                    src="{{asset('storage/' . $folio->keysupervisor )}}" alt="Firma"
                                    style="height: 70px;max-height: 70px"/></td>
                            <td align="right" class="border-none"></td>
                        </tr>
                        <tr>
                            <td class="border-none">Clave: SEG-CA-013PC</td>
                            <td class="border-none" style="border-top: 1px solid">Nombre y Firma del lider.</td>
                            <td align="right" class="border-none">Bridgestone de México SA. de C.V</td>
                        </tr>
                    </table>
                </div>
            </th>
        </table>
    </div>
</div>
</body>

</html>
