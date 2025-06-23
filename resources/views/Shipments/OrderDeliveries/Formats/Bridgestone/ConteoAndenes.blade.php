<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTEO EN ANDEN -- {{ $orderDelivery->shipmentid  }}</title>
    <style>
        .container {
            padding: 0px;
            border: 0px;
            width: 720px;
            /*width: 820px;*/
            height: 12px;
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
            font-size: 11px;
        }

        th:first-child,
        td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        .table-bordered {
            border: 1px solid #212121;
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
            <th>
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
                           CONTEO DE ANDEN
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
                            Folio N°:
                        </th>
                    </table>
                </div>

                <div class="row" style="margin-bottom: 10px;margin-top: 10px">
                    <table class="border-none" >
                        <th class="border-none" style="height: 20px;padding-left: 10px;width: 20%">Supevisor de embarque:</th>
                        <th style="border-bottom: 1px solid;width: 20%" class="border-none">{{$conteo->name}}</th>
                        <th style="" class="border-none">Grupo</th>
                        <th style="border-bottom: 1px solid;width: 10%" class="border-none">{{$conteo->team}}</th>
                        <th class="border-none" style="width: 10%">Turno</th>
                        <th class="border-none" style="border-bottom: 1px solid;width: 10%">{{$conteo->turn ? $conteo->turn->name : ''}}</th>
                        <th class="border-none">Fecha</th>
                        <th class="border-none" style="border-bottom: 1px solid;width: 10%">{{$orderDelivery->workday}}</th>
                    </table>
                </div>

                <div class="row" style="margin-bottom: 100px;padding: 5px" >
                    <table  class="border-none">
                        <th style="width: 45%;height: 20px;padding-top: 10px" class="border-none">
                            Embarque {{$orderDelivery->shipmentid }} &nbsp;&nbsp; Total Izquierdo: {{ $conteo->totalizquierdo }}<br><br>
                            <table class="border-none">
                                @foreach(array_chunk($conteo->detalleizquierdo, 12) as $fila)
                                    <tr class="border-none">
                                        @foreach($fila as $item)
                                            <td style="width: 20px; height: 20px; border: 1px solid; font-size: 12px; text-align: center;" >
                                                {{ $item }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                            <br>
                            Complemento: {{ $conteo->complemento }}
                        </th>
                        <th style="width: 45%;height: 20px;padding-top: 10px" class="border-none">
                            Total Derecho: {{ $conteo->totalderecho }}<br><br>
                            <table class="border-none">
                                @foreach(array_chunk($conteo->detallederecho, 12) as $fila)
                                    <tr class="border-none">
                                        @foreach($fila as $item)
                                            <td style="width: 20px; height: 20px; border: 1px solid; font-size: 12px; text-align: center;"
                                                class="{{ $item !== '0' ? 'bg-danger text-white font-16' : 'bg-light' }}">
                                                {{ $item }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                            <br>
                            Total Final: {{ $conteo->totalfinal }}
                        </th>
                        <th class="border-none">
                            <table style="padding-left: 3px">
                                <tr>
                                    <th style="height: 10px;padding-left: 5px;text-align: center">Anden:</th>
                                </tr>
                                <tr>
                                    <th style="height: 20px">
                                        <span style="font-size: 10px;font-weight: 400" class="border-none">
                                        {{$conteo->location ? $conteo->location->name : ''}}
                                        </span>
                                    </th>
                                </tr>
                            </table>
                        </th>
                    </table>
                </div>

                <div class="row">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-top: 100px" class="border-none">
                        <tr>
                            <td class="border-none">Clave: SEG-CA-013PC</td>
                            <td class="border-none" style="border-top: 1px solid">Nombre y Firma  del supervisor de embarques.</td>
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
