@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check List Entrada Bridgestone</title>
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
            font-size: 8px !important;
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
            font-size: 8px !important;
        }

        th,
        td {
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px !important;
        }

        th:first-child,
        td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        .border-none {
            border: 0;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <table class="border-none">
            <tr>
                <th width="25%">
                    <img src="img/Bridgestone.png" alt="" style="height: 25px;width: 100px">
                </th>
                <th>INSPECCION DEL TRACTOCAMION Y DEL SEMIREMOLQUE</th>
                <th width="30%" style="font-size: 14px;color: red; padding: 0;">
                    <table style="width: 100%; height: 10px; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="border: none; vertical-align: middle; text-align: left; width: 60%;">
                                Folio: {{$receiptunit->id}}
                            </td>
                            <td style="border: none; vertical-align: middle; text-align: right; width: 40%;">
                                {!! DNS2D::getBarcodeHTML($receiptunit->barcode, 'QRCODE',1.5,1.5) !!}
                            </td>
                        </tr>
                    </table>
                </th>

            </tr>
        </table>
    </div>
    <div class="row">
        <table>
            <th style="width:50%">
                <div class="row">
                    <table class="border-none">
                        <th style="height:20px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 12px;">
                            Entrada del Tractocamion y del Semiremolque.
                        </th>
                    </table>
                </div>

                <div class="row" style="margin-top: 5px">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%">Folio-Báscula #</th>
                            <th style="border-bottom: 0px;">Fecha</th>
                            <th style="border-bottom: 0px">Hora</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->tiket ? $receiptunit->tiket->ticket : ''}}</th>
                            <th style="border-top: 0px;background-color: white">{{Carbon::parse($receiptunit->entrydate)->format('Y-m-d')}}</th>
                            <th style="border-top: 0px;background-color: white">{{Carbon::parse($receiptunit->entrydate)->format('H:i:s')}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th>
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none;height: 15px">{{$receiptunit->unittype->auxiliary01 == '' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;width: 20%;font-size: 9px;height: 15px">Importación</th>
                            <th>
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none">{{$receiptunit->unittype->auxiliary01  == 'EXP' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Exportación</th>
                            <th>
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none">{{$receiptunit->unittype->auxiliary01  == 'NAC' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Nacional</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 50%;font-size: 9px">Nombre de guardia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Nombre del operador:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->key ? $receiptunit->key->nameguard01 : '' }}</th>
                            <th style="border-top: 0px;background-color: white">{{$receiptunit->key ? $receiptunit->key->nameoperator01 : ''}}</th>
                        </tr>
                    </table>
                </div>

                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 100%;font-size: 9px">Transportista:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 50%;font-size: 9px">Embarque y/o Remisión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Procedencia:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->shipmentid}}</th>
                            <th style="border-top: 0px;background-color: white">{{$receiptunit->unitdata ? $receiptunit->unitdata->providername : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">Tipo de Licencia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Vigancia de Licencia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">No. de Licencia:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 20%">{{$receiptunit->unitdata ? $receiptunit->unitdata->typeoflicense : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->validity : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->drivernumber}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 12px;background-color: darkgray;text-align: center">
                                Condiciones del Tractocamion en la Entrada
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">No.Economico:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Marca del tractocamión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">No. de placas:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 20%">{{$tracto ? $tracto->econumber : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->brand : '' }}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->trucknumber}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">T.Circulación / F.Expedición / Nombre del
                                titular:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Nivel del combustible:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 60%">  {{$receiptunit->unitdata ? $receiptunit->unitdata->expedition : ''}} / {{$receiptunit->unitdata ? $receiptunit->unitdata->holder : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->fuellevel : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px">Espejos laterales:</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Izquierdo
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Bueno</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorright == 'b' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Roto</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorright == 'r' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Derecha
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Bueno</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorleft == 'b' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Roto</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorleft == 'r' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">Llantas dañadas o baja presión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Desgaste del piso:
                                {{$receiptunit->unitdata ? $receiptunit->unitdata->floorwear : ''}}
                            </th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 60%">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tire == 'SI' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tire == 'NO' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                            <th style="border-top: 0px;background-color: white;width: 40%"></th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;width: 30%">Luces funcionan correctamente:</th>
                            <th style="border-bottom: 0px;font-size: 9px">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lightstruck == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lightstruck == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            :{{$receiptunit->unitdata ? $receiptunit->unitdata->texttruck : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;width: 30%">Calaveras en buenas condiciones:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->skulltruck == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if($receiptunit->unitdata)
                                                    {{$receiptunit->unitdata->skulltruck == '0' ? 'X' : ''}}</div>
                                            @endif
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            : {{$receiptunit->unitdata ? $receiptunit->unitdata->skulltext : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
            </th>
            <th style="width:50%">
                <div class="row">
                    <table class="border-none">
                        <th style="height:20px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 12px;">
                            Salida del Tractocamion y del Semiremolque.
                        </th>
                    </table>
                </div>

                <div class="row" style="margin-top: 5px">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%">Folio-Báscula #</th>
                            <th style="border-bottom: 0px;">Fecha</th>
                            <th style="border-bottom: 0px">Hora</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->tiket ? $receiptunit->tiket->ticket : ''}}</th>
                            <th style="border-top: 0;background-color: white">
                                @if($receiptunit->outputdate)
                                    {{ Carbon::parse($receiptunit->outputdate)->format('Y-m-d') }}
                                @endif
                            </th>
                            <th style="border-top: 0px;background-color: white">
                                @if($receiptunit->outputdate)
                                    {{ Carbon::parse($receiptunit->outputdate)->format('H:i:s') }}
                                @endif
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th>
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none;height: 15px">{{$receiptunit->unittype->auxiliary01 == '' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;width: 20%;font-size: 9px;height: 15px">Importación</th>
                            <th>
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none">{{$receiptunit->unittype->auxiliary01  == 'EXP' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Exportación</th>
                            <th>
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 5px;border: none">{{$receiptunit->unittype->auxiliary01  == 'NAC' ? 'X' : ''}}</div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Nacional</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 50%;font-size: 9px">Nombre de guardia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Nombre del operador:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->key ? $receiptunit->key->nameguard03 : ''}}</th>
                            <th style="border-top: 0px;background-color: white">{{$receiptunit->key ? $receiptunit->key->nameoperator03 : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 100%;font-size: 9px">Transportista:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 50%;font-size: 9px">Embarque y/o Remisión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Procedencia:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">{{$receiptunit->shipmentid}}</th>
                            <th style="border-top: 0px;background-color: white">{{$receiptunit->unitdata ? $receiptunit->unitdata->providername : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">Tipo de Licencia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Vigancia de Licencia:</th>
                            <th style="border-bottom: 0px;font-size: 9px">No. de Licencia:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 20%">{{$receiptunit->unitdata ? $receiptunit->unitdata->typeoflicense : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->validity : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->drivernumber}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 12px;background-color: darkgray;text-align: center">
                                Condiciones del Tractocamion en la Entrada
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">No.Economico:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Marca del tractocamión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">No. de placas:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 20%">{{$tracto ? $tracto->econumber : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->brand : '' }}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->trucknumber}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">T.Circulación / F.Expedición / Nombre del
                                titular:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">Nivel del combustible:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 60%">{{$receiptunit->unitdata ? $receiptunit->unitdata->expedition : ''}}/ {{$receiptunit->unitdata ? $receiptunit->unitdata->holder : ''}}</th>
                            <th style="border-top: 0px;background-color: white;width: 40%">{{$receiptunit->unitdata ? $receiptunit->unitdata->fuellevel : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px">Espejos laterales:</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Izquierdo
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Bueno</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorright == 'b' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Roto</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorright == 'r' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Derecha
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Bueno</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorleft == 'b' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Roto</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->mirrorleft == 'r' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px">Llantas dañadas o baja presión:</th>
                            <th style="border-bottom: 0px;font-size: 9px">Desgaste del piso:
                                {{$receiptunit->unitdata ? $receiptunit->unitdata->floorwear : ''}}
                            </th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white;width: 60%">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tire == 'SI' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tire == 'NO' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                            <th style="border-top: 0px;background-color: white;width: 40%">

                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;width: 30%">Luces funcionan correctamente:</th>
                            <th style="border-bottom: 0px;font-size: 9px">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lightstruck == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lightstruck == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            :{{$receiptunit->unitdata ? $receiptunit->unitdata->texttruck : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;width: 30%">Calaveras en buenas condiciones:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->skulltruck == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if($receiptunit->unitdata)
                                                    {{$receiptunit->unitdata->skulltruck == '0' ? 'X' : ''}}</div>
                                            @endif
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            : {{$receiptunit->unitdata ? $receiptunit->unitdata->skulltext : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
            </th>
        </table>
    </div>

    <div class="row">
        <div class="row">
            <table class="border-none">
                <tr>
                    <th style="text-align: center;font-size: 10px;color: black">Semiremolque
                        (Información General)
                    </th>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="border-none">
                <tr>
                    <th style="border-bottom: 0px;font-size: 9px;border-right: 0px">Tipo de Semiremolque:</th>
                    <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px">{{$receiptunit->truckcategoryid == 'CS' ? 'X' : ''}}</div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Caja Seca</th>

                    <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{$receiptunit->truckcategoryid == 'PL' ? 'X' : ''}}</div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Plataforma</th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{$receiptunit->truckcategoryid == 'CO' ? 'X' : ''}} </div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">Contenedor</th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{$receiptunit->truckcategoryid == 'DR' ? 'X' : ''}}</div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Doble Remolque</th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{$receiptunit->truckcategoryid == 'TH' ? 'X' : ''}}</div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">Torthon y/o Rabon
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                        <div
                            style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{$receiptunit->truckcategoryid == 'OT' ? 'X' : ''}}</div>
                    </th>
                    <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;width: 20%;text-align: left">
                        Otros
                    </th>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="border-none">
                <tr>
                    <th style="border-bottom: 0px;font-size: 9px">No.Economico:</th>
                    <th style="border-bottom: 0px;font-size: 9px">Marca del tractocamión:</th>
                    <th style="border-bottom: 0px;font-size: 9px">No. de placas:</th>
                    <th style="border-bottom: 0px;font-size: 9px">Medidas:</th>
                </tr>
                <tr>
                    <th style="height: 15px;border-top: 0px;background-color: white;width: 20%">{{$receiptunit->econumber}}</th>
                    <th style="border-top: 0px;background-color: white;width: 20%">{{$receiptunit->unitdata ? $receiptunit->unitdata->semitrailerbrand : ''}}</th>
                    <th style="border-top: 0px;background-color: white;width: 20%">{{$receiptunit->boxnumber}}</th>
                    <th style="background-color: white;width: 40%">
                        <table class="border-none">
                            <tr>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                    <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">{{ $receiptunit->truck?->auxiliary01 == 'CJ-40' ? 'X' : '' }}</div>
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                    40 Ft.
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                    <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{ $receiptunit->truck?->auxiliary01 == 'CJ-48' ? 'X' : '' }}</div>
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                    48 Ft.
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                    <div
                                        style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">{{ $receiptunit->truck?->auxiliary01 == 'CJ-53' ? 'X' : '' }}</div>
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                    53 Ft.
                                </th>
                                <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                    ¿Cuales? :
                                        {{$receiptunit->truck ? $receiptunit->truck->name : ''}}
                                </th>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="border-none">
                <th style="width: 25%">Medidas del remolque es correcto ?</th>
                <th style="width: 25%">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                    @if($receiptunit->unitdata)
                                        {{$receiptunit->unitdata->correctm == '1' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                SI
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->correctm == '0' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                NO
                            </th>
                        </tr>
                    </table>
                </th>
                <th>
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 100%;font-size: 9px">Observaciones:</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">
                                {{$receiptunit->unitdata  ? $receiptunit->unitdata->observations : ''}}
                            </th>
                        </tr>
                    </table>
                </th>
            </table>
        </div>
    </div>
    <div class="row">
        <table>
            <th style="width:50%">
                <div class="row">
                    <table class="border-none">
                        <th style="height:20px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 14px;">
                            Entrada del Semiremolque.
                        </th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Entrada</th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">No de Sellos</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">
                                <table class="border-none">
                                    <tr>

                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            Cargada
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                {{$receiptunit->unitstatusid == '1' ? 'X' : ''}}
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            Vacia
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                {{$receiptunit->unitstatusid == '2' ? 'X' : ''}}
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                            <th style="border-top: 0px;background-color: white">{{$receiptunit->key ? $receiptunit->key->containerseal : ''}}</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">LLantas dañadas o
                                baja presión:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tires == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tires == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            No
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            : {{$receiptunit->unitdata ? $receiptunit->unitdata->tiresterxt : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Luces funcionan
                                correctamente:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lights == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata ? $receiptunit->unitdata->lights == '0' ? 'X' : '' : ''}}</div>
                                            @endif
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            No
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            : {{$receiptunit->unitdata ? $receiptunit->unitdata->lightstext : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Calaveras en
                                buenas condiciones:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->skullst == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{ $receiptunit->unitdata->skullst == '0' ? 'X' : ''}}</div>
                                            @endif
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            No
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?
                                            : {{$receiptunit->unitdata ? $receiptunit->unitdata->skulltexts : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>

            </th>
            <th style="width:50%">
                <div class="row">
                    <table class="border-none">
                        <th style="height:25px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 14px;">
                            Salida del Semiremolque.
                        </th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Entrada</th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">No de Sellos
                                : {{$receiptunit->key ? $receiptunit->key->containersealout : ''}}</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;border-top: 0px;background-color: white">
                                <table class="border-none">
                                    <tr>

                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            Cargada
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px"></div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            Vacia
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;"></div>
                                        </th>
                                    </tr>
                                </table>

                            </th>
                            <th style="border-top: 0px;background-color: white"></th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">LLantas dañadas o
                                baja presión:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tires == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->tires == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?: {{$receiptunit->unitdata ? $receiptunit->unitdata->tiresterxt : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Luces funcionan
                                correctamente:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lights == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->lights == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            No
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?: {{$receiptunit->unitdata ? $receiptunit->unitdata->lightstext : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;width: 33%;font-size: 9px;text-align: left">Calaveras en
                                buenas condiciones:
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;text-align: left">
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->skullst == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->skullst == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            No
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;text-align: left">
                                            ¿Cuales?: {{$receiptunit->unitdata ? $receiptunit->unitdata->skulltexts : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>

            </th>
        </table>
    </div>
    <div class="row">
        <table>
            <th style="width:100%">
                <div class="row">
                    <table class="border-none">
                        <th style="height:25px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 14px;">
                            Inspección del Semiremolque.
                        </th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px">GRAPAS/CLAVOS</th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->staples == '1' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                                PARCHES/PEGATINAS
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if(!empty($receiptunit->unitdata))
                                        {{$receiptunit->unitdata->patches == '1' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px">BORDES
                                DISPAREJOS
                            </th>
                            <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px">
                                <div
                                    style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                    @if($receiptunit->unitdata)
                                        {{$receiptunit->unitdata->uneven == '1' ? 'X' : ''}}
                                    @endif
                                </div>
                            </th>
                            <th style="text-align: left;width: 30%">
                                Inspección de calidad
                                <table class="border-none">
                                    <tr>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            APROBADA:
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-left: 0px;border-right: 0px;border-top: 0px;width: 10%">
                                            SI
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->approved == '1' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 10%">
                                            NO
                                        </th>
                                        <th style="border-bottom: 0px;font-size: 9px;border-right: 0px;border-left: 0px;border-top: 0px;width: 5%">
                                            <div
                                                style="width: 10px;height:10px;border:1px solid;border-radius: 10px;">
                                                @if(!empty($receiptunit->unitdata))
                                                    {{$receiptunit->unitdata->approved == '0' ? 'X' : ''}}
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                            <th style="text-align: left;width: 30%">Observaciones
                                <table class="border-none">
                                    <tr class="border-none">
                                        <th style="height: 15px" class="border-none">
                                            {{$receiptunit->unitdata ? $receiptunit->unitdata->observationsinspec : ''}}
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row">
        <table>
            <td style="width:50%">
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="height:25px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 12px;">
                                Entrada Inspección del Tratocamion y del Semiremolque.
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 9px">INTRUCCIONES: REGISTRAR LA INSPECCION UTILIZANDO LA
                                SIGUIENTE
                                SIMBOLOGIA
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 9px"> <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px"> = Inspección Aprobado   <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">= Inspeccionado NO Aprobado
                                N/A=No
                                Aplica
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">1.-DEFENSA DEL TRACTOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "1")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">17.-PAREDES
                                LATERALES-INTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "17")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">2.-MOTOR</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "2")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">18.-TECHO INTERIOR DEL
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "18")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">3.-LLANTAS DEL TRACTO
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "3")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">19.-PARED
                                FRONTAL-INTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "19")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">4.-PISO
                                INTERIOR-TRACTOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "4")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">
                                20.-PUERTAS-REMOLQUE-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "20")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">5.-CABINA Y
                                COMPARTIMENTOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "5")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">21.-PISO
                                EXTERIOR-REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "21")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">6.-TANQUE DE
                                COMBUSTIBLE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "6")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">22.-SELLOS EN PUERTA /
                                V.V.T.T
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "22")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">7.-TANQUE DE AIRE</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "7")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">
                                23.-TECHO-EXTERIOR-REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "23")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">8.-TRACTOR CHASIS Y PISO
                                EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "8")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">24.-ESCAPE DE MOTOR</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "24")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">9.-QUINTA RUEDA</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "9")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%;background-color: #00ccae">
                                INSPECCION
                                DE AGRICULTURA
                            </th>
                            <th style="width: 5%;background-color: #00ccae"></th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">10.-PARED
                                FRONTAL-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "10")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">25.-PLANTAS Y SEMILLAS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "25")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">11.-LLANTAS - REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "11")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">26.-HUMEDAD / HONGOS</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "26")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">12.-CHASIS
                                EXTERNO-REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "12")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">27.-ANIMALES VIVOS O
                                MUERTOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "27")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">13.-PAREDES
                                LATERALES-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "13")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">28.-TIERRA / LODO</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "28")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">14.-DEFENSA-REMOLQUE</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "14")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">29.-SANGRE /
                                DESECHOS-ANIMALES
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "29")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">15.-INTERIOR DE PUERTAS -
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "15")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">30.-BASURA /
                                DESPERDICIOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "30")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">16.-PISO INTERIOR -
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "16")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">31.-DETECCION DE OLORES
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "31")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row" style="position:relative; z-index: 1">
                    @if(!empty($receiptunit->key))
                        <img src='{{storage_path('app/' . $receiptunit->key->keyguard01 )}}'
                             style="position:absolute; width: 50px;height:50px; z-index: 999; top:0; left:0;">
                        <img src='{{storage_path('app/' .  $receiptunit->key->keyoperator01 )}}'
                             style="position:absolute; width: 50px;height:50px; z-index: 999; top:0; right:0;">
                    @endif
                    <table>
                        <tr>
                            <th style="text-align: center">Guardia Entrada</th>
                            <th style="text-align: center">Operador Entrada</th>
                        </tr>
                        <tr>
                            <th style="height: 15px">{{$receiptunit->user ? $receiptunit->user->name : ''}}</th>
                            <th>{{$receiptunit->operatorname}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: center">Firma</th>
                            <th style="text-align: center">Firma</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th style="text-align: left">Comentarios</th>
                        </tr>
                        <tr>
                            <th style="height: 18px">

                            </th>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 50%">
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th style="height:25px;border-top: 0;border-left: 0;border-right: 0;background-color: darkgray;
                                    align-content: center;text-align: center;width: 100%;padding: 0;border-bottom: 0;font-size: 12px;">
                                Salida Inspección del Tratocamion y del Semiremolque.
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 9px">INTRUCCIONES: REGISTRAR LA INSPECCION UTILIZANDO LA
                                SIGUIENTE
                                SIMBOLOGIA
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 9px"> <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px"> = Inspección Aprobado   <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">= Inspeccionado NO Aprobado
                                N/A=No
                                Aplica
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">1.-DEFENSA DEL TRACTOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "1")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">17.-PAREDES
                                LATERALES-INTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "17")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">2.-MOTOR</th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "2")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">18.-TECHO INTERIOR DEL
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "18")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">3.-LLANTAS DEL TRACTO
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "3")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">19.-PARED
                                FRONTAL-INTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "19")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">4.-PISO
                                INTERIOR-TRACTOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "4")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">
                                20.-PUERTAS-REMOLQUE-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "20")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">5.-CABINA Y
                                COMPARTIMENTOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "5")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">21.-PISO
                                EXTERIOR-REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "21")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">6.-TANQUE DE
                                COMBUSTIBLE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "6")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">22.-SELLOS EN PUERTA /
                                V.V.T.T
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "22")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">7.-TANQUE DE AIRE</th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "7")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">
                                23.-TECHO-EXTERIOR-REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "23")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">8.-TRACTOR CHASIS Y PISO
                                EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "8")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">24.-ESCAPE DE MOTOR</th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "24")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">9.-QUINTA RUEDA</th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "9")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%;background-color: #00ccae">
                                INSPECCION
                                DE AGRICULTURA
                            </th>
                            <th style="width: 5%;background-color: #00ccae"></th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">10.-PARED
                                FRONTAL-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "10")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">25.-PLANTAS Y SEMILLAS</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "25")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">11.-LLANTAS - REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "11")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">26.-HUMEDAD / HONGOS</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "26")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">12.-LLANTAS - REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "12")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">27.-ANIMALES VIVOS O
                                MUERTOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "27")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">13.-PAREDES
                                LATERALES-EXTERIOR
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "13")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">28.-TIERRA / LODO</th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "28")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">14.-DEFENSA-REMOLQUE</th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "14")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">29.-SANGRE /
                                DESECHOS-ANIMALES
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "29")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">15.-INTERIOR DE PUERTAS -
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "15")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">30.-BASURA /
                                DESPERDICIOS
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "30")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                        <tr>
                            <th style="font-size: 8px;text-align: left;width: 40%">16.-PISO INTERIOR -
                                REMOLQUE
                            </th>
                            <th style="width: 5%">
                                @foreach($verifiedOut as $item)
                                    @if($item->idcheckOut == "16")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                            <th style="font-size: 8px;text-align: left;width: 40%">31.-DETECCION DE OLORES
                            </th>
                            <th style="width: 5%">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "31")
                                        @if($item->verified == 'SI')
                                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                                        @elseif($item->verified == 'NO')
                                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                                        @else
                                            {{$item->verified}}
                                        @endif
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="row" style="position:relative; z-index: 1">
                    @if(!empty($receiptunit->key))
                        <img src='{{storage_path('app/' . $receiptunit->key->keyguard03 )}}' style="position:absolute; width: 50px;height:50px; z-index: 999; top:0; left:0;">
                        <img src='{{storage_path('app/' .  $receiptunit->key->keyoperator03 )}}' style="position:absolute; width: 50px;height:50px; z-index: 999; top:0; right:0;">
                    @endif
                    <table>
                        <tr>
                            <th style="text-align: center">Guardia Salida</th>
                            <th style="text-align: center">Operador Salida</th>
                        </tr>
                        <tr>
                            <th style="height: 15px;">{{$receiptunit->userclose ? $receiptunit->userclose->name : ''}}</th>
                            <th>{{$receiptunit->operatorname}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: center">Firma</th>
                            <th style="text-align: center">Firma</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th style="text-align: left">Comentarios</th>
                        </tr>
                        <tr>
                            <th style="height: 18px">

                            </th>
                        </tr>
                    </table>
                </div>
            </td>
        </table>
    </div>
</div>

</body>

</html>

