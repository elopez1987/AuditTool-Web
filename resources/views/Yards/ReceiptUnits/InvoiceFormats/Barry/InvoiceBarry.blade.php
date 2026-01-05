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


        .border-none {
            border: 0;
        }
    </style>


</head>

<body>

<div class="container">

    <div class="row">
        <table class="border-none">
            <th class="border-none"><img src="{{ public_path('img/Barry.png') }}" alt="" style="width: 250px;height: 40px"></th>
        </table>
    </div>

    <div class="row" style="margin-top: 30px">
        <table>
            <tbody>
            <tr>
                <td style="height: 15px;text-align: right" colspan="4">Nombre del documento: Revisión de unidades de
                    transporte.
                </td>
            </tr>
            <tr>
                <td style="width: 25%;height: 10px">ID del documento</td>
                <td style="width: 25%">Fecha de revisión</td>
                <td style="width: 20%">Revisión</td>
                <td style="width: 30%;border-bottom: 0"></td>
            </tr>
            <tr>
                <td style="width: 25%;height: 10px">QDS-TOLU-WAD-126</td>
                <td style="width: 25%">09/ABRIL/2025</td>
                <td style="width: 20%">1</td>
                <td style="width: 30%;border-top: 0;border-bottom:0">Propietario del documento: Cadena de Suministro
                </td>
            </tr>
            <tr>
                <td style="width: 25%;height: 10px"></td>
                <td style="width: 25%"></td>
                <td style="width: 20%"></td>
                <td style="width: 30%"></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <th style="border-top: 2px solid;background-color: #cfd8dc">
                <p>
                    <b>Intrucciones:</b> Llene los datos del encabezado (Nombre del inspector, fecha, línea
                    transportista, placas del tractor y de la caja,
                    nombre del chofer, cliente o proveedor). Posteriormente, inspecciones cada uno de los puntos de
                    referencia en caso de identificar anomalias reportenlas
                    en el campo de observaciones y finalmente indique si la unidad esta apta para carga.
                </p>
            </th>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <tbody>
            <tr class="border-none">
                <td class="border-none" style="width: 20%">Inspector</td>
                <td class="border-none"
                    style="border-bottom: 1px solid">{{$receiptunit->seal ? $receiptunit->seal->user->name : ''}}</td>
                <td style="width: 15%;text-align: left;padding-left: 10px" class="border-none">Fecha</td>
                <td style="width: 15%;border-bottom: 1px solid" class="border-none">{{$receiptunit->entrydate}}</td>
            </tr>
            <tr class="border-none">
                <td class="border-none" style="width: 20%">Linea transportista</td>
                <td class="border-none"
                    style="border-bottom: 1px solid">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</td>
                <td style="width: 15%;text-align: left;padding-left: 10px" class="border-none">Placa de tractor</td>
                <td style="width: 15%;border-bottom: 1px solid"
                    class="border-none">{{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}</td>
            </tr>
            <tr class="border-none">
                <td class="border-none" style="width: 20%">Nombre del chofer</td>
                <td class="border-none"
                    style="border-bottom: 1px solid">{{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}</td>
                <td style="width: 15%;text-align: left;padding-left: 10px" class="border-none">Placa de caja</td>
                <td style="width: 15%;border-bottom: 1px solid" class="border-none">{{$receiptunit->boxnumber}}</td>
            </tr>
            <tr class="border-none">
                <td class="border-none" style="width: 20%">Cliente o Proveedor</td>
                <td class="border-none" style="border-bottom: 1px solid">{{$receiptunit->auxiliary01}}</td>
                <td style="width: 15%;text-align: left;padding-left: 10px" class="border-none">Inicio</td>
                <td style="width: 15%;border-bottom: 1px solid" class="border-none"> Hrs.</td>
            </tr>
            <tr class="border-none">
                <td class="border-none" style="width: 20%"></td>
                <td class="border-none"></td>
                <td style="width: 15%;text-align: left;padding-left: 10px" class="border-none">Fin</td>
                <td style="width: 15%;border-bottom: 1px solid" class="border-none"> Hrs.</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="border-none" style="width: 70%">
            <b>Intrucciones:</b> Llene los datos del encabezado (Nombre del inspector, fecha, línea
            transportista, placas del tractor y de la caja,
            nombre del chofer, cliente o proveedor). Posteriormente, inspecciones cada uno de los puntos de
            referencia en caso de identificar anomalias reportenlas
            en el campo de observaciones y finalmente indique si la unidad esta apta para carga.
        </table>
    </div>

    <div class="row" style="margin-top: 30px">
        <img src="{{ public_path('img/Barry/unidad.png') }}" alt="" style="width: 100%;height: 180px">
    </div>

    <div class="row" style="margin-top: 20px">
        <p>Los puntos de inspección son:</p>
    </div>

    <div class="row">
        <table class="border-none" style="width: 100%">
            <td style="width: 50%" class="border-none">
                <table class="border-none">
                    <tr>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                        <td>OK</td>
                        <td>NO</td>
                        <td>NA</td>
                    </tr>
                    @foreach($verified->slice(0,9) as $check)
                        <tr>

                            <td>{{$loop->iteration}}</td>
                            <td style="text-align: left !important;">{{$check->description}}</td>
                            <td>
                                @if($check->verified == 'SI')
                                    X
                                @endif
                            </td>
                            <td>
                                @if($check->verified == 'NO')
                                    X
                                @endif
                            </td>
                            <td>
                                @if($check->verified == 'NA')
                                    X
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%" class="border-none">
                <table class="border-none">
                    <tr>
                        <td class="border-none"></td>
                        <td class="border-none"></td>
                        <td>OK</td>
                        <td>NO</td>
                        <td>NA</td>
                    </tr>
                    @foreach($verified->slice(9,19) as $check)
                        <tr>

                            <td style="text-align: left">{{$loop->iteration}}</td>
                            <td style="text-align: left !important;">{{$check->description}}</td>
                            <td>
                                @if($check->verified == 'SI')
                                    X
                                @endif
                            </td>
                            <td>
                                @if($check->verified == 'NO')
                                    X
                                @endif
                            </td>
                            <td>
                                @if($check->verified == 'NA')
                                    X
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </table>
    </div>

    <div class="row" style="margin-top: 30px">
        <table class="border-none">
            <tr>
                <td class="border-none">Observaciones :</td>
            </tr>
            <tr>
                <p style="border-bottom: 1px solid;padding-left: 20px">
                    {{$comment->comment}}
                </p>
            </tr>
        </table>
    </div>

    <div class="row" style="margin-top: 30px">
        <table class="border-none">
            <tbody>
            <tr>
                <td class="border-none">Como resultado de la inspección, la unidad es apta :</td>
                <td style="border-bottom: 1px solid">SI</td>
                <td class="border-none"></td>
{{--                <td style="border-bottom: 1px solid">MO</td>--}}
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row" style="margin-top: 30px">
        <table class="border-none">
            <tbody>
            <tr>
                <td class="border-none" >
                    En caso de contestar NO, suspender el embarque o recepción e informar a Aseguramiento de Calidad
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
</body>

</html>
