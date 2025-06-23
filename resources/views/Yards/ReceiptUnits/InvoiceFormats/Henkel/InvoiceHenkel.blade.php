<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Folio de Unidad para embarque</title>
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
        <table>
            <th style="width: 120px;text-align: center">
                <img src="img/henkel.png" alt="" style="width: 80px;height: 40px">
            </th>
            <th>
                <div class="row">
                    <table class="border-none">
                        <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 80%;padding: 0;border-bottom: 0;">
                            CHECK LIST DE TRANSPORTE DE CARGA
                        </th>
                        <th style="height: 30px;border-top: 0;border-right: 0;padding: 0;border-bottom: 0;">
                            Código: <br>
                        </th>
                    </table>
                </div>
                <div>
                    <table class="border-none">
                        <th style="width: 40%;border-bottom: 0;border-left: 0;text-align: center;">
                            Documento Fuente
                        </th>
                        <th style="width: 40%;border-bottom: 0;">
                        </th>
                        <th style="width:20%;border-bottom: 0;border-right: 0;">
                            "Versión: 1"
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row" style="height: 30px">
        <table class="border-none">
            <th style="width: 120px;border-top: 0;">
                {{--                <img src="img/sid300x150.png" alt="" >--}}
            </th>
            <th style="border-top: 0;height: 10px">
                <div class="row">
                    <table class="border-none">
                        <th style="border-left: 0;border-right: 0;text-align: right;width: 80%;border-bottom: 1px solid;border-top: 0;">
                            <span style="margin-right: 10px;">FOLIO</span>
                        </th>
                        <th style="border-top: 0;border-right: 0;">
                            <span
                                style="font-size: 20px;color:red;vertical-align: middle;border-bottom: 0;"> {{$folio}}</span>
                        </th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <th style="width: 37.8%;height: 20px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;">
                            No. de Guia de Carga : <span
                                style="font-size: 16px;margin-left: 20px;">{{$receiptunit->shipmentid}}</span>
                        </th>
                        <th style="width: 15%;height: 20px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;margin-left: 10px">
                            No. Anden
                        </th>
                        <th style="height: 20px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: center;margin-left: 10px;font-size: 15px;">{{$receiptunit->location ? $receiptunit->location->name : ''}}</th>
                        <th style="width: 15%;height: 20px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;">
                            R.Control
                        </th>
                        <th style="height: 20px;padding: 0;border-bottom: 0;border-top: 0;border-left: 0;text-align: left;border-right: 0">
                            ---
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>


    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <th style="height: 20px;width: 16.5%;border-top: 1px solid black;">Fecha de Revisión</th>
            <th style="width: 10%;border-top: 1px solid black;font-size: 12px;">{{\Carbon\Carbon::parse($receiptunit->entrydate)->format('d/m/Y')}}</th>
            <th style="width: 20.2%;border-top: 1px solid black;text-align: left">Hora de llegada a Caseta del Almacén</th>
            <th style="width: 10%;border-top: 1px solid black;"
                class="font-18 font-bold">{{\Carbon\Carbon::parse($receiptunit->entrydate)->format('H:i')}}</th>
            <th style="width: 19.8%;border-top: 1px solid black;border-bottom: 1px solid;">Hora de Ingreso a Almacén</th>
            <th style="width: 10%;border-top: 1px solid black;border-bottom: 1px solid;"></th>
            <th style="width: 10%;border-top: 1px solid black;border-bottom: 1px solid;">PAGINA 1/1</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="height: 15px;width: 15.6%;border-top: 0;">Nombre del Operador</th>
            <th style="width: 43.9%;border-top: 0;font-size: 12PX;"> {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}</th>
            <th style="width: 8.8%;border-top: 0px;">TIPO</th>
            <th style="border-top: 0;">
                <span>{{$receiptunit->truck ?  $receiptunit->truck->name : ''}}</span>
            </th>
        </table>
    </div>


    <div class="row">
        <table class="border-none">
            <th style="border-top: 0px;width: 25%;height: 15px;">Linea de Transporte</th>
            <th style="width: 42.4%;border-top: 0px;text-align: left;">
                <span style="margin-left:15px"> {{$receiptunit->carrier  ? $receiptunit->carrier->name : ''}} </span>
            </th>
            <th style="border-top: 0px;">Centro:</th>
            <th style="border-top: 0px;"> ONE ROOF</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="border-top:0px;width: 25%;height: 25px;">N°. De Placas</th>
            <th style="border-top:0px;width: 25%;font-size: 16PX;"> {{$receiptunit->boxnumber}}</th>
            <th style="border-top:0px;">Código de barra de Caja / Tracto</th>
            <th style="border-top:0px;font-size: 16px">{{$receiptunit->barcode}} / {{$receiptunit->tracto ? $receiptunit->tracto->barcode : ''}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;">Inspección del Vehiculo</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;">Concepto</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;">SI</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;">NO</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;">Observaciones</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 18px;">01. Tarjeta de circulación:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '62' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '62' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;width: 40%;">

            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 18px;">02. Licencia de conducir:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '63' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '63' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;">
                <div class="row">
                    <table class="border-none">
                        <th style="border-left: 0;border-right: 0;border-top: 0;border-bottom: 0;width: 40%;height: 12px;">Celular :</th>
                        <th style="border-top: 0;border-bottom: 0;border-right: 0;width: 30%;">Licencia :</th>
                        <th style="border-top: 0;border-bottom: 0;border-right: 0;">Folio YardTrax:</th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <th style="width: 40%;border-left: 0;border-bottom: 0;height: 14px;border-right: 0;">{{$receiptunit->tracto ? $receiptunit->tracto->phone : ''}} </th>
                        <th style="border-bottom: 0;border-right: 0;width: 30%;">{{$receiptunit->tracto ? $receiptunit->tracto->drivernumber : ''}}</th>
                        <th style="border-bottom: 0;border-right: 0;font-size: 14px !important;color: red">{{$receiptunit->id}}</th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 18px;">03. Placas,al frente y
                posterior:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '64' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '64' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;">
                <div class="row">
                    <table class="border-none">
                        <th style="border-left: 0;border-right: 0;border-top: 0;border-bottom: 0;width: 40%;height: 12px;">Numero:(Tracto)</th>
                        <th style="border-top: 0;border-bottom: 0;border-right: 0;">Numero:(Caja)</th>
                    </table>
                </div>
                <div class="row">
                    <table class="border-none">
                        <th style="width: 40%;border-left: 0;border-bottom: 0;height: 6px;border-right: 0;height: 14px;font-size: 12px;text-align: center;">
                            {{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}
                        </th>
                        <th style="border-bottom: 0;border-right: 0;font-size: 12PX;">{{$receiptunit->boxnumber}} /  {{ $receiptunit->econumber}}</th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;">Cabina Exterior</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;height: 15px;">EN BUENAS
                CONDICIONES
            </th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;">SI</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;">NO</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;">Observaciones</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">04. Espejos Retrovisores:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '42' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '49' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">05. Parabrisas y limpi brisas:
                (Funcional):
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '43' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '43' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">06. Estribos:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '44' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '44' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">07. Fugas de aceite o algún
                otro liquido :
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '45' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '45' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;;height: 15px">Cabina Interior</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">08. Cinturones de seguridad:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '46' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '46' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">09. Switch</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '47' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '47' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">10. Nivel de conbustible:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '48' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '48' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;;height: 15px">Carroceria y Caja de la
                Unidad
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">11. Rines y llantas en buen
                estado: (Daños Visibles, alambres expuestos):
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '49' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '49' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">12. Estribos en caja de
                unidad:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '50' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '50' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">13. Elementos de fijacion de
                carga (Gatas, bandas o redes)
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '51' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '51' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;height: 15px">Iluminación</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">14. Luz de corto alcance (Luz
                baja):
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '52' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '52' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">15. Luz de Giro o de dirección
                (Direccionales):
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '53' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '53' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">16. Luz de Freno:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '54' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '54' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">17. Luz de estacionamineto y
                emergencia (Intermitentes):
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '55' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '55' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">18. Alarma de retroceso:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '56' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '56' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;height: 15px">Interior Caja de
                Unidad
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">19. Puertas de la caja en buen
                estado:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '57' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '57' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">20. Condiciones interiores
                (Materiales, gotoreras, agujeros, basura)
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '58' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '58' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;height: 15px">Equipo de proteccion
                personal
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">21. Chaleco:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '59' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '59' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">22. Zapatos:</th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '60' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '60' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;">23. Guantes en caso de doble
                remolque:
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '61' && $item->verified == 'SI'))
                    X
                @endif
            </th>
            <th style="border-top: 0;text-align: center;width: 10%;">
                @if ($verified->contains(fn($item) => $item->checklitsdetailid == '61' && $item->verified == 'NO'))
                    X
                @endif
            </th>
            <th style="border-top: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;height: 15px">Observaciones
                Generales
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="margin-left: 10px">
                <div style="margin-left: 80px;">
                    <span style="margin-bottom: 30px;font-size: 15px;">{{$receiptunit->barcode}}</span> <br>
                    {!! DNS2D::getBarcodeHTML($receiptunit->barcode, 'QRCODE',3,3) !!}
                </div>
            </th>
            <th style="height: 120px;border-top: 0;width: 33%">
                <span>No. De Sellos de Seguridad</span><br>
            </th>
            <th style="height: 120px;border-top: 0;width: 33%;">
                <span>Calzas</span>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;width: 15%;height: 15px;text-align: left;">Dictamen</th>
            <th style="border-top: 0;text-align: center;width: 10%;text-align: left;">Aceptado</th>
            <th style="border-top: 0;text-align: center;width: 15%;"></th>
            <th style="border-top: 0;text-align: center;width: 15%;text-align: left;">Rechazado</th>
            <th style="border-top: 0;text-align: center;width: 25%;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;text-align: left;">Observaciones:</th>
            <th style="border-top: 0;text-align: center;width: 75%;height: 15px;">{{$receiptunit->auxiliary02}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;width: 25%;text-align: left;height: 14px;">Revisado por:</th>
            <th style="border-top: 0;text-align: center;width: 25%;">Nombre y firma Guardia (Entrada)</th>
            <th style="border-top: 0;text-align: center;width: 25%;">Nombre y firma de analista de trafico (Salida)</th>
            <th style="border-top: 0;text-align: center;width: 25%;">Nombre y firma del operador (Entrada)</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;width: 25%;height: 25px;text-align: left;vertical-align:bottom;">
                Firma
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;">
                {{$receiptunit->user ? $receiptunit->user->name : ''}}
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;"></th>
            <th style="border-top: 0;text-align: center;">
                {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}
            </th>
        </table>
    </div>
    <div class="row">
        <div style="text-align: center;font-size: 9px;margin-top: 6px;">
            <span style="text-align: center;">Este documento es propiedad de Henkel Capital S.A de C.V., queda prohibida su reproducción total o parcial.</span>
            <br>
        </div>

    </div>
</div>
</body>

</html>
