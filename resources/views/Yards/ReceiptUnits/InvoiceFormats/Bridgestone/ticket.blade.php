<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket de bascula - {{$receiptunit->id}} </title>
    <style>
        .container {
            padding: 0px;
            border: 0px;
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

    <div class="row border-none">
        <table class="border-none">
            <th class="border-none">
                <div class="row">
                    <table class="border-none">
                        <th
                                style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: left;text-align: left;width: 80%;padding: 0;border-bottom: 0;font-size: 18px;"
                                class="border-none">
                            BRIDGESTONE DE MEXICO S.A DE C.V
                        </th>
                        <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;color: red;text-align: center;width: 20%;padding: 0;border-bottom: 0;font-size: 18px;">
                            NO. {{$receiptunit->tiket ? $receiptunit->tiket->ticket : ''}}
                        </th>
                    </table>
                </div>
                <div class="row border-none" style="margin-top: 10px">
                    <table class="border-none">
                        <th style="width: 60%;font-size: 10px;color: black;text-align: center" class="border-none">
                            TICKET DE BASCULA :
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px">
        <table class="border-none">
            <tr>
                <th
                        style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 80%;padding: 0;border-bottom: 0;font-size: 11px;"
                        class="border-none">
                    Fecha: <span
                            style="font-size: 10px;text-decoration: underline">  {{ \Illuminate\Support\Str::before($receiptunit->entrydate, ' ') }}</span>
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 40px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                    FORMA-BRE :
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;"
                    class="border-none">
                    Peso de entrada : <span
                            style="font-size: 14px;color: blue"> {{$receiptunit->tiket ? $receiptunit->tiket->weightin : '' }}  {{$receiptunit->tiket ? $receiptunit->tiket->value01 : '' }}</span>
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                    Peso de salida : <span
                            style="color: blue;font-size: 14px">{{$receiptunit->tiket ? $receiptunit->tiket->weightout : '' }} {{$receiptunit->tiket ? $receiptunit->tiket->value02 : '' }}</span>
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 1px solid;font-size: 10px;"
                    class="border-none">
                    MERCANCÁ :
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 60%"
                    class="border-none">
                    FACTURA O REMISIÓN: <span style="font-size: 14px;color: blue"> {{$receiptunit->shipmentid}}</span>
                </th>
                <th class="border-none">CHOFER ARRIBA ( ) ABAJO ( )</th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 65%"
                    class="border-none">
                    PROCEDENCIA :
                </th>
                <th class="border-none"></th>
                <th class="border-none"
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 30%">
                    CAJA: <span style="font-size: 14px;color: blue">{{$receiptunit->boxnumber}} </span>
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 65%"
                    class="border-none">
                    DESTINO :
                    @if(!empty($orderDelivery))
                        {{$orderDelivery->customer ? $orderDelivery->customer->name : ''}}
                    @endif
                </th>
                <th class="border-none" style="width: 5%"></th>
                <th class="border-none"
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 30%">
                    NO.ECO: <span style="font-size: 14px;color: blue">{{$receiptunit->econumber}}  </span>
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 65%"
                    class="border-none">
                    DIRECCIÓN :
                    @if(!empty($orderDelivery))
                        {{$orderDelivery->customer ? $orderDelivery->customer->city : ''}}
                    @endif
                </th>
                <th class="border-none" style="width: 5%"></th>
                <th class="border-none"
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 30%">
                    PLACAS: <span
                            style="font-size: 14px;color: blue"> {{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}} </span>
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 65%"
                    class="border-none">
                    TRANSPORTES : <span
                            style="font-size: 14px; color: blue">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</span>
                </th>
                <th class="border-none" style="width: 5%"></th>
                <th class="border-none"
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 30%">
                    No. SAL DE FAB:
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 60%"
                    class="border-none">
                    OBSERVACIONES :
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 60%"
                    class="border-none">
                    Sellos : <span style="font-size: 14px;color: blue">{{$receiptunit->containerseal ?? ''}}</span>
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 70px;">
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 45%"
                    class="border-none">
                    CONFIRMACION DEL CHOFER :
                    <p style="font-size: 14px;color: blue">{{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}</p>
                </th>
                <th class="border-none" style="width: 10%"></th>
                <th class="border-none"
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 1px solid;font-size: 10px;width: 45%">
                    PESADO POR: <p style="font-size: 14px;color: blue">{{$trace->user ? $trace->user->name : ''}}</p>
                </th>
            </tr>
        </table>
    </div>
    <hr style="border: 1px dotted;margin-top: 30px;margin-bottom: 30px">
    <div class="row">
        <table class="border-none">
            <tr>
                <td style="width: 50%" class="border-none">
                    <p>Folio de Unidad : {{$receiptunit->id}}</p>
                    <p>Código de Unidad : {{$receiptunit->barcode}}</p>
                    {!! DNS2D::getBarcodeHTML($receiptunit->barcode, 'QRCODE',3,3) !!}
                </td>
            </tr>
        </table>
    </div>

</div>


</body>

</html>
