<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Kardex de Recibo</title>
    <style>
        .container {
            padding: 0px;
            border: 0px;
            width: 1020px;
            height: 612px;
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        html {
            margin: 0;
            font-weight: 400;
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        body {
            font-family: 'Roboto Condensed', sans-serif !important;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 11px;
            color: #0b0b0b;
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
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        th, td {
            text-align: center;
            padding: 1px;
            border: 1px solid #212121;
            font-size: 9px;
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: white
        }

        .border-none {
            border: 0px;
            font-size: 9px;
            font-weight: 500;
        }

        .formato {
            padding-top: 5px;
        }

        .logo {
            width: 150px;
            height: 60px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="container">
    {{--    Cabeceras--}}
    <div class="row">
        <table cellpadding="20">
            <th style="height: 70px;width: 33.3%">
                <img src="{{asset('/img/logo.png')}}" alt="" class="logo">
            </th>
            <th style="vertical-align: center;width: 33.3%">
                <h3 class='formato' style="font-size: 15px;">Checklist del Proceso de Descarga</h3>
                <span style="font-size: 12px;margin-top: 2px;">Area de descarga</span>
            </th>
            <th style="width: 33.3%">
            </th>
        </table>
    </div>
    <br>

    {{-- Datos principales  --}}
    <div class="row">
        <table>
            <td style="width: 80%;border: 0;padding-right: 20px">
                <div class="row">
                    <table>
                        <tr>
                            <td>Guia</td>
                            <td>{{$receipt->shipmentid}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Delivery</td>
                            <td>{{$receipt->billoflading}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Turno de carga</td>
                            <td></td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Polvo</td>
                            <td style="width: 20px;">
                                @if ($receipt->auxiliary03 == 'PO')
                                    X
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Transporte:</td>
                            <td style="width: 200px">{{$receipt->carrier ? $receipt->carrier->name : ''}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Placas</td>
                            <td>{{$receipt->folio ? $receipt->folio->boxnumber : ''}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Operador</td>
                            <td style="width:180px">{{$receipt->folio ? $receipt->folio->operatorname : ''}}</td>
                            <td style="border-bottom: 0;border-top: 0;"></td>
                            <td style="text-align: left;">Interalmacen:</td>
                            <td>
                                @if ($receipt->auxiliary03 == 'LV')
                                    X
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>F. Arribo:</td>
                            <td>{{\Carbon\Carbon::parse($receipt->folio ? $receipt->folio->entrydate : '')->format('d/m/Y')}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">H. Arribo:</td>
                            <td> {{\Carbon\Carbon::parse($receipt->folio ? $receipt->folio->entrydate : '')->format('H:i')}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align:left;">Cortina #:</td>
                            <td> {{$receipt->folio ? $receipt->folio->location->name : ''}}</td>
                            <td style="border-bottom: 0;border-top: 0;"></td>
                            <td style="text-align: left;">Liquido:</td>
                            <td>
                                @if ($receipt->auxiliary03 == 'LI')
                                    X
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>F.Liberación</td>
                            <td>{{\Carbon\Carbon::parse($receipt->folio ? $receipt->folio->autorizaoutputdate : '')->format('d/m/Y')}}</td>
                            <td style="border-bottom: 0;border-top: 0;"></td>
                            <td style="text-align: left;">H.Liberación;</td>
                            <td>{{\Carbon\Carbon::parse($receipt->folio ? $receipt->folio->autorizaoutputdate : '')->format('H:i')}}</td>
                            <td style="border-bottom: 0;border-top: 0;"></td>
                            <td style="text-align: left;width: 100px">Inicio Verificación:</td>
                            <td>{{\Carbon\Carbon::parse($receipt->unloadstartdate)->format('d/m/Y H:i')}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Maquilador:</td>
                            <td style="width: 20px">
                                @if ($receipt->auxiliary03 == 'MA')
                                    X
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>C.Emisor</td>
                            <td>{{$receipt->provider ? $receipt->provider->name : ''}}</td>
                            <td style="border-bottom: 0;border-top: 0;"></td>
                            <td style="text-align: left;">C.Receptor</td>
                            <td>{{$receipt->warehouse->name}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Fin de Verificación</td>
                            <td>{{\Carbon\Carbon::parse($receipt->unloadfinishdate)->format('d/m/Y H:i')}}</td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="text-align: left;">Otros:</td>
                            <td>
                                @if ($receipt->auxiliary03 == 'OT')
                                    X
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </table>
    </div>

    {{--    Incidencias--}}
    <div class="row">
        <table>
            <thead style="font-size: 8px !important;">
            <tr>
                <th COLSPAN="15"
                    style="font-weight: 600;font-size: 11px;border-left: 0;border-top: 0;border-right: 0;text-align: center">
                    INCIDENCIAS DE PRODUCTO
                </th>
            </tr>
            <tr>
                <th rowspan="2">IDH</th>
                <th rowspan="2">ESTANDAR</th>
                <th rowspan="2">TOTAL</th>
                <th rowspan="2">PALLETS</th>
                <th rowspan="2">RESTOS</th>
                <th colspan="2">INVENTARIO</th>
                <th colspan="2">PLAYO</th>
                <th colspan="3">CORRUGADO DAÑADO</th>
                <th colspan="3">PRODUCTO DAÑADO</th>
                <th colspan="3">ETIQUETA</th>
                <th colspan="3">TARIMA</th>
            </tr>
            <tr>
                <th>SOBRANTE</th>
                <th>FALTANTE</th>

                <th>DAÑO POR GOLPE</th>
                <th>FALTA DE PLAYO</th>

                <th>POR GOLPE</th>
                <th>POR TENSION DE PLAYO</th>
                <th>COLAPSADO</th>

                <th>POR GOLPE</th>
                <th>DERRAME</th>
                <th>FUGAS</th>

                <th>INCORRECTA</th>
                <th>SIN</th>
                <th>DAÑADA</th>

                <th>ROTA</th>
                <th>INCORRECTA</th>
                <th>INESTABLE</th>

            </tr>
            </thead>
            <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{$stock->partnumber->prtnum}}</td>
                    <td>{{$stock->partnumber->untpal}}</td>
                    <td>{{$stock->quantitytrue}}</td>
                    <td>{{ intdiv($stock->quantitytrue,$stock->partnumber->untpal) }}</td>
                    <td>{{ fmod($stock->quantitytrue,$stock->partnumber->untpal) }}</td>

                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 1)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 2)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 5)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 6)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 20)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 21)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 22)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 23)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 7)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 8)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 24)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 9)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 10)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 28)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 29)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($incidences as $item)
                            @if ($stock->partnumberid == $item->partnumberid)
                                @if ($item->categorydetailid == 30)
                                    {{$item->quantity}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--    Analista de recibo--}}
    <div class="row" style="margin-top: 8px;">
        <table>
            <td style="width: 44%;border:0">
                <table>
                    <thead>
                    <tr>
                        <th style="border:0;font-size: 12px;font-weight: 600;">ANALISTA DE RECIBO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 77%;">1) Presenta checklist de ingreso</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%"></td>
                        <td style="width: 5%;">
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 65)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 6%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">2) Presenta Carta Porte</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 66)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">3) Presenta Delivery</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 67)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">4) Presenta Control de Patio</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 68)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">5) Presenta Bitacora</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 69)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">6) Recibe y asegura llaves del Transporte</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 70)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">7) Tipo de transporte</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td colspan="4"> {{$receipt->folio ? $receipt->folio->truck->name : ''}}</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;background-color: white"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">8) Recepción completa en sistema</td>
                        <td style="border-bottom: 0;border-top: 0;background-color: white;"></td>
                        <td>
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 72)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width:5%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">9) # - Sellos</td>
                        <td style="border-bottom: 0;border-top: 0;"></td>
                        <td colspan="4"> {{$receipt->folio ? $receipt->folio->containerseal : ''}}</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    </tbody>

                </table>
            </td>
            <td style="width: 44%;border: 0">
                <table>
                    <thead>
                    <tr>
                        <th style="border:0;font-size: 12px;font-weight: 600;">VERIFICACIÓN</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 77%;">14) Numero de tarimas</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td colspan="4"> {{$receipt->totalcontainers}}</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">15) Lleva tarima correcta</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td style="width: 5%;">
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 79)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 6%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">16) Tipo de Tarima</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td colspan="4"> {{$receipt->name}}</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">17) Vueltas de emplaye 4 - 3 - 4</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td style="width: 5%;">
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 80)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 6%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">18) Lleva separadores</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td style="width: 5%;">
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 81)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 6%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 77%;">19) Etiquetas visibles</td>
                        <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                        <td style="width: 5%;">
                            @foreach($checklistAsn as $item)
                                @if($item->checklistdetailid == 82)
                                    X
                                @endif
                            @endforeach
                        </td>
                        <td style="width: 5%;">SI</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 6%;">NO</td>
                        <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                    </tr>

                    </tbody>
                </table>
            </td>
        </table>
    </div>

    {{--    Montacarguista--}}
    <div class="row">
        <table>
            <td style="width: 42.5%;border: 0;">
                <div class="row">
                    <table>
                        <thead>
                        <tr>
                            <th style="border:0;font-size: 12px;font-weight: 600;">MONTACARGUISTA</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width: 77%;">10) Semaforo previo a carga en color</td>
                            <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                            <td style="width: 5%;">
                                @foreach($checklistAsn as $item)
                                    @if($item->checklistdetailid == 73)
                                        X
                                    @endif
                                @endforeach
                            </td>
                            <td colspan="3">Verde</td>
                            <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                        </tr>
                        <tr>
                            <td style="width: 77%;">11) Se activa retenedor de seguridad de remolque</td>
                            <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                            <td style="width: 5%;">
                                @foreach($checklistAsn as $item)
                                    @if($item->checklistdetailid == 74)
                                        X
                                    @endif
                                @endforeach
                            </td>
                            <td style="width: 5%;">SI</td>
                            <td style="width: 5%;"></td>
                            <td style="width: 6%;">NO</td>
                            <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                        </tr>
                        <tr>
                            <td style="width: 77%;">12) Se colocaron de calzas</td>
                            <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                            <td style="width: 5%;">
                                @foreach($checklistAsn as $item)
                                    @if($item->checklistdetailid == 75)
                                        X
                                    @endif
                                @endforeach
                            </td>
                            <td style="width: 5%;">SI</td>
                            <td style="width: 5%;"></td>
                            <td style="width: 6%;">NO</td>
                            <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                        </tr>
                        {{--                        <tr>--}}
                        {{--                            <td style="width: 77%;">13) Se realizó inspección del remolque</td>--}}
                        {{--                            <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>--}}
                        {{--                            <td style="width: 5%;">--}}
                        {{--                                @foreach($checklistAsn as $item)--}}
                        {{--                                    @if($item->checklistdetailid == 76)--}}
                        {{--                                        X--}}
                        {{--                                    @endif--}}
                        {{--                                @endforeach--}}
                        {{--                            </td>--}}
                        {{--                            <td style="width: 5%;">SI</td>--}}
                        {{--                            <td style="width: 5%;"></td>--}}
                        {{--                            <td style="width: 6%;">NO</td>--}}
                        {{--                            <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>--}}
                        {{--                        </tr>--}}
                        <tr>
                            <td style="width: 77%;">13) Se coloca rampa de carga correctamente</td>
                            <td style="border-bottom: 0;border-top: 0;width: 3.5%;"></td>
                            <td style="width: 5%;">
                                @foreach($checklistAsn as $item)
                                    @if($item->checklistdetailid == 77)
                                        X
                                    @endif
                                @endforeach
                            </td>
                            <td style="width: 5%;">SI</td>
                            <td style="width: 5%;"></td>
                            <td style="width: 6%;">NO</td>
                            <td style="border-top: 0;border-bottom: 0;border-right: 0;"></td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            </td>
            <td style="width: 42.5%;border: 0;">
                <table>
                    <tr>
                        <td style="width: 10%;height: 20px;border-top: 0;border-left: 0;border-right: 0;border-bottom: 0;font-weight: 600;font-size: 12px;">
                            OBSERVACIONES:
                        </td>
                        <td style="border-left: 0;border-top: 0;border-right: 0;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;border-left: 0;border-right: 0;height: 25px;border-top: 0;"></td>
                        <td style="border-left: 0;border-right: 0;"></td>
                    </tr>
                </table>
            </td>
        </table>
    </div>

    <br><br><br><br>
    {{--    Fimas--}}
    <table>
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
    <br>

    {{--    Fotos--}}
    @if ($images->isNotEmpty())
        <div style="page-break-after:always;"></div>
        @foreach($images as $image)
{{--            <img src='storage/{{$image->path}}' alt="Foto" style="width: 300px;height:300px;margin-top: 50px;margin-left: 20px;margin-right: 20px">--}}
            <img src='{{asset('storage/' . $image->path)}}' style="width: 300px;height:300px;margin-top: 50px;margin-left: 10px;
                 margin-right: 10px;border: 2px solid #cfdd01 !important" >
        @endforeach
    @endif
</div>

</body>
</html>

