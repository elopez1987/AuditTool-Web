<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTEO EN ANDEN -- {{ $orderDelivery->shipmentid  }}</title>
    <style>
        .container {
            padding: 0;
            border: 0;
            width: 1050px;
            height: 12px;
        }

        html {
            margin: 0;
            font-weight: 400;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
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
            border: 0;
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
                            Conteo de Embarques de Supervisor
                        </th>
                        <br>
                    </table>
                </div>

                @if($conteos->count() > 0)
                    <div class="row" style="margin-bottom: 10px;margin-top: 10px">
                        <table class="border-none" style="width: 60%;margin-left: 100px">
                            {{--                            <th class="border-none" style="height: 20px;padding-left: 10px;width: 20%;font-size: 10px">Supevisor de embarque: </th>--}}
                            {{--                            <th style="border-bottom: 1px solid;width: 20%;font-size: 9px" class="border-none">{{$conteos[0]->name}} </th>--}}
                            <th class="border-none" style="width: 10%;font-size: 10px">Cortina</th>
                            <th class="border-none"
                                style="border-bottom: 1px solid;width: 10%;font-size: 10px">{{$conteos[0]->location ? $conteos[0]->location->name : ''}}</th>
                            <th class="border-none" style="width: 10%;font-size: 10px">Turno</th>
                            <th class="border-none"
                                style="border-bottom: 1px solid;width: 10%;font-size: 10px">{{$conteos[0]->turn ? $conteos[0]->turn->name : ''}}</th>

                        </table>
                    </div>
                    <div class="row">
                        <table class="border-none" style="width: 60%;margin-left: 100px">
                            <th class="border-none" style="font-size: 10px">Embarque</th>
                            <th class="border-none"
                                style="border-bottom: 1px solid;width: 10%;font-size: 10px;text-align: left">{{$orderDelivery->shipmentid}}</th>
                            <th class="border-none" style="font-size: 10px;">Fecha</th>
                            <th class="border-none"
                                style="border-bottom: 1px solid;width: 10%;font-size: 10px;text-align: left;">{{$orderDelivery->workday}}</th>
                        </table>
                    </div>
                @endif


                <div class="row">
                    @if(!empty($conteos))
                        @foreach($conteos as $conteo)
                            <table
                                style="width: 20%;border: 0;border-collapse: collapse;border-spacing: 0;margin-top: 10px;padding-left: 15px;margin-bottom: 80px "
                                class="table-bordered">
                                <thead>
                                <th style="font-size: 10px;background-color: black;color: white">Lado Izquierdo</th>
                                <th style="font-size: 10px;background-color: black;color: white">Lado Derecho</th>
                                </thead>
                                <tbody>
                                <td width="50%">
                                    <table class="table-bordered"
                                           style="width: 100%;border: 0;border-collapse: collapse;border-spacing: 0">
                                        <thead>
                                        <tr>
                                            <th style="font-size: 8px !important;background-color: black;color: white">
                                                Rack
                                            </th>
                                            <th style="font-size: 8px !important;background-color: black;color: white">
                                                Código
                                            </th>
                                            <th style="font-size: 8px !important;background-color: black;color: white">
                                                Canal
                                            </th>
                                            <th style="background-color: #0c69f1;font-size: 8px !important;">A</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($conteo->detalleizquierdo as $detalle)
                                            @if(isset($conteo->detalleizquierdo))
                                                <tr>
                                                    @php
                                                        $codigo = '';
                                                        $canal = '';
                                                        $cantidad = '';
                                                        foreach($detalle as $key => $value) {
                                                            if(str_contains($key, 'codigoIzq_')) {
                                                                $codigo = $value;
                                                            } elseif(str_contains($key, 'canalIzq_')) {
                                                                $canal = $value;
                                                            } elseif(str_contains($key, 'cantidadIzq_')) {
                                                                $cantidad = $value;
                                                            }
                                                        }
                                                    @endphp
                                                    <td style="font-size: 8px !important;">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                                    <td style="font-size: 8px !important;">{{ $codigo }}</td>
                                                    <td style="font-size: 8px !important;">{{ $canal }}</td>
                                                    <td style="font-size: 8px !important;">{{ $cantidad }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="3" style="font-size: 7px !important;">Total</td>
                                            <td style="font-size: 7px !important;">{{$conteo->totalIzquierdo}}</td>
                                        </tr>
                                        <tr style="border: 0 solid black;">
                                            <td colspan="4"
                                                style="font-size: 7px !important;background-color:green;height: 5px"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="font-size: 7px !important;">Sobrante</td>
                                            <td style="font-size: 7px !important;">{{$conteo->sobranteizq}}</td>
                                        </tr>
                                        <tr style="border: 0 solid black;">
                                            <td colspan="4"
                                                style="font-size: 7px !important;background-color:green;height: 5px "></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="font-size: 7px !important;">Llanta NC</td>
                                            <td style="font-size: 7px !important;">{{$conteo->ncizq}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="font-size: 7px !important;">Total</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="50%">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th style="background-color: green;font-size: 8px !important;">B</th>
                                            <th style="font-size: 8px !important;background-color: black;color: white">
                                                Código
                                            </th>
                                            <th style="font-size: 8px !important;background-color: black;color: white">
                                                Canal
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($conteo->detallederecho))
                                            @foreach($conteo->detallederecho as $detalle)
                                                <tr>
                                                    @php
                                                        $codigo = '';
                                                        $canal = '';
                                                        $cantidad = '';
                                                        foreach($detalle as $key => $value) {
                                                            if(str_contains($key, 'codigoDer_')) {
                                                                $codigo = $value;
                                                            } elseif(str_contains($key, 'canalDer_')) {
                                                                $canal = $value;
                                                            } elseif(str_contains($key, 'cantidadDer_')) {
                                                                $cantidad = $value;
                                                            }
                                                        }
                                                    @endphp
                                                    <td style="font-size: 8px !important;">{{ $cantidad }}</td>
                                                    <td style="font-size: 8px !important;">{{ $codigo }}</td>
                                                    <td style="font-size: 8px !important;">{{ $canal }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td style="font-size: 7px !important;">{{$conteo->totalderecho}}</td>
                                            <td style="font-size: 7px !important;" colspan="2">Total</td>
                                        </tr>
                                        <tr style="border: 0 solid black;">
                                            <td colspan="4"
                                                style="font-size: 7px !important;background-color:green;height: 5px"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 7px !important;">{{$conteo->sobranteder}}</td>
                                            <td colspan="2" style="font-size: 7px !important;">Sobrante</td>
                                        </tr>
                                        <tr style="border: 0 solid black;">
                                            <td colspan="4"
                                                style="font-size: 7px !important;background-color:green;height: 5px "></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 7px !important;">{{$conteo->ncder}}</td>
                                            <td colspan="2" style="font-size: 7px !important;">Llanta NC</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"
                                                style="font-size: 7px !important;">{{$conteo->totalfinal}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                                </tbody>
                            </table>
                        @endforeach
                    @endif
                </div>


                {{--                <div class="row">--}}
                {{--                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-top: 100px"--}}
                {{--                           class="border-none">--}}
                {{--                        <tr>--}}
                {{--                            <td class="border-none">Clave: SEG-CA-013PC</td>--}}
                {{--                            <td class="border-none" style="border-top: 1px solid">Nombre y Firma del supervisor de--}}
                {{--                                embarques.--}}
                {{--                            </td>--}}
                {{--                            <td align="right" class="border-none">Bridgestone de México SA. de C.V</td>--}}
                {{--                        </tr>--}}
                {{--                    </table>--}}
                {{--                </div>--}}


            </th>
        </table>
    </div>


</div>


</body>

</html>
