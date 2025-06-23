<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> MAPA DE CARGA</title>
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

        .border-none {
            border: 0;
        }

        .bg-cell {
            background-color: #cfdd01 !important;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <th style="height: 20px;width: 25%;">
                <img src="img/trax.jpg" alt="" style="height: 60px;width: 180px;">
            </th>
            <th style="height: 20px;width: 50%;text-align: center;font-size: 18px;">
               MAPA DE CARGA
            </th>
            <th style="height: 20px;width: 25%;text-align: left;font-size: 11px !important;">
                Código: <br>
                Nivel de Revisión: 00 <br>
                Fecha de Aplicación: 15.09.2022 <br>
                Fecha de vigencia: 15.09.2023 <br>
            </th>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">EMBARQUE</th>
                <th style="height: 20px;width: 60%;text-align: left;" class="bg-cell"> CLIENTE</th>
                <th style="height: 20px;width: 20%;" class="bg-cell"> CORTINA</th>
            </tr>
            <tr>
                <th style="height: 20px;width: 20%;">{{$model->shipmentid}}</th>
                <th style="height: 20px;width: 60%;text-align: left;"> {{$model->provider ? $model->provider->name : ''}} {{$model->customer ? $model->customer->name : ''}}</th>
                <th style="height: 20px;width: 20%;"> {{$model->location ? $model->location->name :'' }}</th>
            </tr>
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">TURNO</th>
                <th style="height: 20px;width: 60%;text-align: left;" class="bg-cell"> FECHA DE TRABAJO</th>
                <th style="height: 20px;width: 20%;" class="bg-cell"> PLACA</th>
            </tr>
            <tr>
                <th style="height: 20px;width: 20%;">{{$model->turn ? $model->turn->name : ''}}</th>
                <th style="height: 20px;width: 60%;text-align: left;"> {{$model->workday}}</th>
                <th style="height: 20px;width: 20%;"> {!! $model->boxnumber  !!} / {!! $model->econumber !!}</th>
            </tr>
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">ORIGEN</th>
                <th style="height: 20px;width: 60%;text-align: left;" class="bg-cell">TIPO DE MOVIMIENTO</th>
                <th style="height: 20px;width: 20%;" class="bg-cell">ECONOMICO</th>
            </tr>
            <tr>
                <th style="height: 20px;width: 20%;">{{$model->origen ? $model->origen->description : ''}}</th>
                <th style="height: 20px;width: 60%;text-align: left;"> {{$model->razon ? $model->razon->name : ''}}</th>
                <th style="height: 20px;width: 20%;">{{$model->econumber}} </th>
            </tr>
        </table>
    </div>
    <br>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 33%;" class="bg-cell">LPN</th>
                <th style="height: 20px;width: 33%;text-align: left;" class="bg-cell">USUARIO</th>
                <th style="height: 20px;width: 3%;" class="bg-cell">FECHAD DE MOVIMIENTO</th>
            </tr>
            @foreach($containers as $lpn)
                <tr>
                    <th style="height: 20px;width: 33%;">{{$lpn->lpnid}}</th>
                    <th style="height: 20px;width: 33%;">{{$lpn->userload ? $lpn->userload->name : ''}}</th>
                    <th style="height: 20px;width: 33%;text-align: left;">{{$lpn->adddateload}}</th>
                </tr>
            @endforeach
        </table>
    </div>


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(260, 810, "Página :  $PAGE_NUM de $PAGE_COUNT", $font, 9);
            ');
        }
    </script>

</div>


</body>

</html>
