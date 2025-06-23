<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Folio de Unidad</title>
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
                        <th style="width: 60%;font-size: 12px;color: black;text-align: center" class="border-none">
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
                    style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 80%;padding: 0;border-bottom: 0;font-size: 12px;"
                    class="border-none">
                    Fecha: <span style="font-size: 12px;text-decoration: underline">  {{ \Illuminate\Support\Str::before($receiptunit->entrydate, ' ') }}</span>
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 40px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                    FORMA-BRE :
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                    Peso de entrada : {{$receiptunit->tiket ? $receiptunit->tiket->weightin : '' }}  {{$receiptunit->tiket ? $receiptunit->tiket->value01 : '' }}
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                    Peso de salida : {{$receiptunit->tiket ? $receiptunit->tiket->weightout : '' }} {{$receiptunit->tiket ? $receiptunit->tiket->value02 : '' }}
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 10px;width: 70%" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;width: 50%;padding: 0;border-bottom: 2px solid;font-size: 12px;" class="border-none">
                    MERCANCÁ :
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 60%" class="border-none">
                    FACTURA O REMISIÓN:  {{$receiptunit->shipmentid}}
                </th>
                <th class="border-none">CHOFER ARRIBA (  ) ABAJO (  ) </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 65%" class="border-none">
                    PROCEDENCIA :
                </th>
                <th class="border-none" style="5%"></th>
                <th class="border-none" style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 30%">CAJA: {{$receiptunit->boxnumber}} </th>
            </tr>
        </table>
    </div>
    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 65%" class="border-none">
                    DESTINO :
                </th>
                <th class="border-none" style="width: 5%" "></th>
                <th class="border-none" style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 30%">NO.ECO: {{$receiptunit->econumber}}  </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 65%" class="border-none">
                    DIRECCIÓN :
                </th>
                <th class="border-none" style="width: 5%"></th>
                <th class="border-none" style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 30%">PLACAS: {{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}} </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 65%" class="border-none">
                    TRANSPORTES : {{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}
                </th>
                <th class="border-none" style="width: 5%"></th>
                <th class="border-none" style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 30%">No. SAL DE FAB:  </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 60%" class="border-none">
                    OBSERVACIONES :
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 60%" class="border-none">
                Sellos :    {{$receiptunit->containerseal ?? ''}}
                </th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 70px;" >
        <table class="border-none">
            <tr>
                <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 45%" class="border-none">
                    CONFIRMACION DEL CHOFER : {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}
                </th>
                <th class="border-none" style="width: 10%"></th>
                <th class="border-none" style="height:15px;border-top: 0;border-left: 0;border-right: 0;text-align: left;padding: 0;border-bottom: 2px solid;font-size: 12px;width: 45%" >
                    PESADO POR: {{$trace->user ? $trace->user->name : ''}}
                </th>
            </tr>
        </table>
    </div>


</div>


</body>

</html>
