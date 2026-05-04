<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Folio de Entrada {{$receiptunit->id}}</title>
    <style>
        .cols {
            border: 1px solid black;
            display: table;
        }

        .div1 {
            display: inline-block;
            width: 50%;
            float: left;
        }

        .div2 {
            display: inline-block;
            width: 50%;
            float: right;
        }

        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap');


        .container {
            padding: 0px;
            border: 0px;
            width: 1056px;
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

        .column {
            float: left;
            width: 33.33%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .tipo {
            font-family: 'Roboto Condensed', sans-serif !important;
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
            background-color: #f2f2f2
        }

        .table-bordered {
            border: 1px solid #212121;
        }

        .border-none {
            border: 0px;
            font-size: 9px;
            font-weight: 500;
        }

        .logo {
            width: 150px;
            height: 60px;
            margin: auto;
        }

        .formato {
            padding-top: 5px;
        }

        .centro {
            text-align: center;
            display: block;
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .datos {
            background-color: #f5f5f5;
            width: 90%;
            text-align: left;
            font-size: 11px;
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        .foto {
            width: 120px;
            height: 120px;
            border: 1px solid #0d47a1;
            border-radius: 5px;
            margin-left: 30px;
        }

        .w80 {
            width: 80%;
        }

        .w50 {
            width: 50%;
            text-align: center;
        }

        .w20 {
            width: 20%;
        }

        .w30 {
            width: 30%;
        }

        .w70 {
            width: 70%;
        }

        .name-station {
            border-left: 0px;
            border-right: 0px;
            border-bottom: 0px;
        }

        .nota {
            color: red;
            font-family: 'Roboto Condensed', sans-serif !important;
            font-style: italic;
            font-weight: 600;
            text-align: center;
            font-size: 9px;
        }

        .pleft {
            text-align: left;
            font-size: 11px;
            font-family: 'Roboto Condensed', sans-serif !important;
        }

        .pd2 {
            padding: 2px;
        }

        .h10 {
            height: 10px;
            background-color: darkgray
        }

        .tiempo {
            border-top: 0px;
            border-left: 0px;
            border-right: 0px;
        }

        .cab {
            padding: 1px;
            text-align: left;
            font-size: 9px;


        }

        .datos-empleado {
            height: 10px;
            padding: 1px;
            text-align: left;
        }

        .duracion {
            border-top: 0px;
            border-left: 0px;
            border-right: 0px;
            background-color: #ffffff;
        }


        input[type="radio" i] {
            margin: 1px 1px 0px 3px;
        }

        .f11 {
            font-size: 11px;
        }

    </style>
</head>
<body>

<div class="container">

    <div class="row">
        <table cellpadding="20">
            <th style="height: 70px;width: 33.3%;align-items: center">
                <img src="img/logo.png" alt="" class="logo">
            </th>
            <th style="vertical-align: center;width: 33.3%">
                <h3 class='formato' style="font-size: 15px;">FOLIO DE ENTRADA DE UNIDAD</h3>
            </th>
            <th style="width: 33.3%">
                @if(!empty($logo->auxiliary09))
                    <img src="{{ storage_path('app/' . $logo->auxiliary09) }}"
                         class="align-self-center img-responsive" alt="" style="height: 70px;width: 280px">
                @endif
            </th>
        </table>
    </div>
    <br>
    <table>
        <td class="centro" style="font-size: 14px;">{{$receiptunit->center ? $receiptunit->center->description : ''}} - {{$receiptunit->warehouse ? $receiptunit->warehouse->name : ''}}</td>
    </table>
    <table>
        <tr>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w20 f11"> Folio</td>
                        <td style="font-size: 13px !important;"><?php echo str_pad($receiptunit->id, 10, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <td class="datos w20 f11"> Etiqueta</td>
                        <td style="font-size: 13px !important;">
                            {{$receiptunit->barcode}}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border-none" style="width: 50%">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w20 f11"> Destino</td>
                        <td style="font-size: 13px;">{{$receiptunit->warehouse ? $receiptunit->warehouse->name : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w20 f11"> Evento</td>
                        <td style="font-size: 13px !important;">
                            {{$receiptunit->event ? $receiptunit->event->name : ''}}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border-none" style="width: 50%">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w20 f11"> Zona</td>
                        <td style="font-size: 13px;">{{$receiptunit->zone ? $receiptunit->zone->name : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w20 f11"> Ubicación</td>
                        <td style="font-size: 13px !important;">{{$receiptunit->location ? $receiptunit->location->name : ''}}</td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
    <br>
    <table align="center" border=0 cellspacing=0 cellpadding=0 class="border-none">
        <tr>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td colspan="2"
                            style="font-size: 12px;background-color: #1A8BCD;color: white;text-align: center">DATOS DE
                            ENTRADA DE UNIDAD
                        </td>
                    </tr>
                    <tr>
                        <td class="datos w30 tipo"> Licencia</td>
                        <td class="pleft tipo"> {{$receiptunit->tracto ? $receiptunit->tracto->drivernumber : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">N. Operador</td>
                        <td class="pleft"> {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">Teléfono</td>
                        <td class="pleft"> {{$receiptunit->tracto ? $receiptunit->tracto->phone : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Linea</td>
                        <td class="pleft"> {{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Fecha/Hora</td>
                        <td class="pleft"> {{$receiptunit->entrydate}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">N.Caja</td>
                        <td class="pleft"> {{$receiptunit->boxnumber}} / {{$receiptunit->econumber}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">N.Tracto</td>
                        <td class="pleft"> {{ $receiptunit->tracto ? $receiptunit->tracto->trucknumber : '' }}
                            / {{$receiptunit->tracto ? $receiptunit->tracto->econumber : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Longitud</td>
                        <td class="pleft"> {{$receiptunit->truck ? $receiptunit->truck->name : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Tipo de Caja</td>
                        <td class="pleft"> {{$receiptunit->truckcategory ? $receiptunit->truckcategory->description : ''}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Embarque</td>
                        <td class="pleft"> {{$receiptunit->shipmentid}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Sellos</td>
                        <td class="pleft"> {{$receiptunit->containerseal}}</td>
                    </tr>
                </table>
            </td>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td colspan="2"
                            style="font-size: 12px;background-color: #1A8BCD;color: white;text-align: center">DATOS DE
                            SALIDA DE UNIDAD
                        </td>
                    </tr>
                    <tr style="height: 25px !important;">
                        <td class="datos w30" style="height: 15px;">Operador</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr style="height: 15px !important;">
                        <td class="datos w30">Licencia</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr style="height: 15px !important;">
                        <td class="datos w30">Fecha/Hora</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr style="height: 15px !important;">
                        <td class="datos w30">N.Tracto</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr style="height: 15px !important;">
                        <td class="datos w30">Embarque</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr style="height: 15px !important;">
                        <td class="datos w30">Sellos</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            style="font-size: 12px;background-color: #1A8BCD;color: white;text-align: center">
                            COMENTARIO DE ENTRADA
                        </td>
                    </tr>
                    <tr style="height: 45px !important;">
                        <td colspan="2">
                            @if(!empty($comment))
                                {{$comment->comment}}
                            @endif
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <br>
    <table class="border-none">
        <tr>
            <td width="50%">
                <img src="img/trailer.png" alt=""
                     style="width: 400px;height: 150px;margin-top: 20px;margin-left:50px ">
                <br>
                <div style="border: 1px dashed black;margin: auto;width: 250px;padding: 5px;text-align: center;">
                    <span style="font-size: 16px;font-weight: 700;text-align: center;margin-top: 5px">Sr.Operador</span>
                    <br>
                    <span>Te recordamos que se debe de colocar las calzas al momento de enrampar su unidad,</span>
                    <br>
                    <span>y no retirarlas hasta que reciba instrucciones  para ello. </span>
                    <br>
                    <span style="font-weight: 800;font-size: 16px;">Tomar y colocar en su lugar.</span>
                </div>
            </td>
            <td width="49%">
                <table class="border-none">
                    <tr>
                        <td class="border-none" width="50%">
                            <table class="border-none">
                                <thead>
                                <tr>
                                    <th class="border-none" style="background-color: #0ba8ef;color: #ffffff">
                                        DESCRIPCIÓN
                                    </th>
                                    <th class="border-none" style="background-color: #0ba8ef;color: #ffffff">¿CUMPLE?
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($verified as $detail)
                                    <tr>
                                        <td class="border-none"
                                            style="font-size: 9px !important;">{{$detail->description}}</td>
                                        <td class="border-none">{{$detail->verified}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td class="border-none" width="50%">
                             <span style="font-size: 12px;margin: auto;text-align: center">
                                    Etiqueta: {{$receiptunit->barcode}}
                              </span>
                            <br>
                            <div style="margin:auto;text-align: center;padding-top: 10px">
                                <span style="margin: auto;display: block">
                                    {!! DNS2D::getBarcodeHTML($receiptunit->barcode, 'QRCODE',3,3) !!}
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table>
        <td class="border-none" style="text-align: center;font-size: 10px;">
            <hr>
            Nombre y Firma Vigilancia
        </td>
        <td class="border-none" style="text-align: center;font-size: 10px">
            <hr>
            Nombre y Firma Operador
        </td>
        <td class="border-none" style="text-align: center;font-size: 10px">
            <hr>
            Nombre y Firma Analista
        </td>
        <td class="border-none" style="text-align: center;font-size: 10px">
            <hr>
            Nombre y Firma Vigilancia
        </td>
        <td class="border-none" style="text-align: center;font-size: 10px">
            <hr>
            Nombre y Firma Operador
        </td>
        <td class="border-none" style="text-align: center;font-size: 10px">
            <hr>
            Nombre y Firma Analista
        </td>
    </table>
    <br>
    @if($firma)
        <table>
            <td class="border-none" style="text-align: center;font-size: 10px;">
                <img src="{{ storage_path('app/' . $firma->keyguard01) }}" alt="Firma"
                     style="height: 100px;max-height: 200px"/>
            </td>
            <td class="border-none" style="text-align: center;font-size: 10px">
                <img src="{{ storage_path('app/' . $firma->keyoperator01) }}"
                     alt="Firma"
                     style="height: 100px;max-height: 200px"/>
            </td>
            <td class="border-none" style="text-align: center;font-size: 10px">

            </td>
            <td class="border-none" style="text-align: center;font-size: 10px">
                <img src="{{ storage_path('app/' . $firma->keyguard03) }}"
                     alt="Firma"
                     style="height: 100px;max-height: 200px"/>
            </td>
            <td class="border-none" style="text-align: center;font-size: 10px">
                <img src="{{ storage_path('app/' . $firma->keyoperator03) }}"
                     alt="Firma"
                     style="height: 100px;max-height: 200px"/>
            </td>
            <td class="border-none" style="text-align: center;font-size: 10px">

            </td>
        </table>
    @endif


</div>

</body>
</html>

