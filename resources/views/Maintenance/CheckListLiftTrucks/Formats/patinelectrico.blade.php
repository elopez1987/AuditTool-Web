<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check list de patin -- {{ $truckChecklist->serialnumber  }} - electrico </title>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        .container {
            padding: 0;
            border: 0;
            width: 700px;
            height: 612px;
        }

        header {
            position: fixed;
            margin-left: 38px;
            height: 3cm !important;
            width: 700px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 100px;
            width: 700px;
            margin-left: 38px;
            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
        }

        html {
            margin: 0;
            font-weight: 400;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 10px;
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

        .border-none {
            border: 0;
        }
    </style>

</head>

<body>

<main>
    <div class="container">
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <th style="height: 20px;width: 25%;">
                </th>
                <th style="height: 20px;width: 50%;text-align: center;font-size: 11px;">
                    SISTEMA INTEGRADO DE GESTION PARA LA EXCELENCIA OPERATIVA.
                    <br>
                    <span style="text-align: center;">Check List patin hidráulico</span>
                </th>
                <th style="height: 20px;width: 25%;text-align: left;">
                    Código: GSID-16-SIGEO-FO-56 <br>
                    Nivel de Revisión: 00 <br>
                    Fecha de Aplicación: 15.09.2023 <br>
                    Fecha de vigencia: 15.09.2026 <br>
                    Páginas: 1 de 1 <br>
                </th>
            </table>
        </div>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <th style="height: 20px;width: 33%;text-align: left;"> NÚM. DE EQUIPO : {{ $truckChecklist->serialnumber  }}</th>
                <th style="height: 20px;width: 33%;text-align: left;"> HOROMETRO : {{$truckChecklist->horometro}}</th>
                <th style="height: 20px;width: 33%;text-align: left;"> FECHA : {{$truckChecklist->created_at}}</th>
            </table>
            <br>
        </div>
        <div class="row" style="text-align: center;">
                <span style="color: red;text-align: center;">LA INSPECCION DEL EQUIPO ANTES DE SU OPERACION ESOBLIGATORIA</span>
        </div>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <tr>
                    <th style="height: 20px;width: 25%;">PUNTO DE INSPECCIÓN</th>
                    <th style="height: 20px;width: 15%;text-align: left;"> CONDICION CRITICA</th>
                    <th style="height: 20px;width: 5%;"> SI</th>
                    <th style="height: 20px;width: 5%;"> NO</th>
                    <th style="height: 20px;width: 25%;"> COMENTARIOS</th>
                </tr>
                @foreach($details as $detail)
                    <tr>
                        <th style="height: 20px;text-align: left;font-size: 10px;font-weight: 400;">{{$detail->description}}</th>
                        <th style="height: 20px;">
                            @if($detail->typeid == 'TP-01')
                                <img src="{{asset('ico/mtnormal.png')}}" alt="" class="img-16">
                            @else
                                <img src="{{asset('ico/mtcritico.png')}}" alt="" class="img-16">
                            @endif
                        </th>
                        <th style="height: 20px;text-align: center;font-size: 11px;font-weight: 400;">
                            @if($detail->value01 == 1)
                                x
                            @endif
                        </th>
                        <th style="height: 20px;text-align:center;font-size: 11px;font-weight: 400;">
                            @if($detail->value01 == 0)
                                x
                            @endif
                        </th>
                        <th style="height: 20px;text-align:center;font-size: 11px;font-weight: 400;">
                            {{$detail->note}}
                        </th>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <tr>
                    <th style="height: 20px;text-align: center;" colspan="3"> Daños visibles del equipo</th>
                </tr>
                <tr>
                    <th
                        style="height: 100px;width: 33%;text-align: center;background-color: white;border-right: 0;">
                        <img src="{{asset('/img/patinelectricoizq.jpg')}}" alt="" width="80%">
                    </th>
                    <th
                        style="height: 100px;width: 33%;text-align: center;background-color: white;border-left: 0;border-right: 0;">
                        <img src="{{asset('/img/patinelectricoder.jpg')}}" alt="" width="80%">
                    </th>
                    <th style="height: 100px;width: 33%;text-align: center;background-color: white;border-left: 0;">
                        <img src="{{asset('/img/riesgosmonta.png')}}" alt="" width="80%">
                    </th>
                </tr>
            </table>
            <br>
        </div>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <tr>
                    <th style="height: 20px;width: 50%;" colspan="3"> NOMBRE
                        : {{ $truckChecklist->user ? $truckChecklist->user->name : '' }}</th>
                </tr>
                <tr>
                    <th style="height: 20px;width: 50%;" colspan="2"> LICENCIA :</th>
                    <th style="height: 20px;width: 50%;text-align: right;"> FIRMA : <img
                            src='{{storage_path('app/' . $truckChecklist->auxiliary02)}}'
                            style="width: 50px;height:50px">
                    </th>
                </tr>
                <tr>
                    <th style="height: 20px;width: 50%;" colspan="2"> SUPERVISOR
                        : {{$truckChecklist->supervisorname}}</th>
                    <th id="firmasuper" style="height: 20px;width: 50%;text-align: right;"> FIRMA : <img
                            src='{{storage_path('app/' . $truckChecklist->auxiliary03)}}'
                            style="width: 50px;height:50px">
                    </th>
                </tr>
            </table>
            <br>
        </div>
        <div class="row" style="text-align: center;">
            <span style="color: red;text-align: center;"> OBSERVACIONES</span>
        </div>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <tr>
                    <td style="height: 100px;width: 50%;" colspan="2">
                        {{$truckChecklist->auxiliary01}}
                    </td>
                </tr>
            </table>
            <br>
        </div>
        <div class="row" style="text-align: center;margin-top: 100px;">
                <span style="color: red;text-align: center;"> Documento controlado por medios electrónicos.Para uso
                    exclusivo de Grupo SID Logistica. <br> Uso restringido.</span>
        </div>
    </div>

</main>


</body>

</html>
