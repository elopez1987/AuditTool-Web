<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validación de Inventario </title>

    <style>
        .container {
            padding: 0;
            border: 0;
            width: 700px;
            height: 612px;
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

<div class="container">

    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <th style="height: 20px;width: 25%;">
                INVENTARIO : {{$card->inventoryid}}
            </th>
            <th style="height: 20px;width: 50%;text-align: center;font-size: 20px;">
                CÉDULA PARA TOMA FÍSICA DE INVENTARIOS
            </th>
            <th style="height: 20px;width: 25%;text-align: left;">
                Código: GSID-16-SIGEO-FO-47 <br>
                Nivel de Revisión: 00 <br>
                Fecha de Aplicación: 15.09.2023 <br>
                Fecha de vigencia: 15.09.2024 <br>
                Páginas: 1 de 1 <br>
            </th>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <th style="height: 20px;width: 20%;">VALIDACION DE INVENTARIO</th>
            <th style="height: 20px;width: 60%;text-align: left;"> NOMBRE Y FIRMA</th>
            <th style="height: 20px;width: 20%;"> CÉDULA</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="height: 20px;width: 20%;">FECHA</th>
            <th style="height: 20px;width: 60%;text-align: left;"></th>
            <th style="height: 20px;width: 20%;border-bottom: 0;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="height: 20px;width: 80%;background-color: black;color: white;font-size: 20px;">INVENTARIO
                FISICO
            </th>
            <th style="height: 20px;width: 20%;font-size: 30px;border-top: 0;vertical-align: text-top;"> {{$card->cardid}}</th>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 25%;">POSICIÓN</th>
                <th style="height: 20px;width: 25%;text-align: left;"> IDH</th>
                <th style="height: 20px;width: 25%;"> CANTIDAD</th>
                <th style="height: 20px;width: 25%;"> U.MEDIDA</th>
            </tr>
            @foreach($locations as $location)
                <tr>
                    <th style="height: 20px;width: 25%;text-align: center;">{{$location->locationid}}</th>
                    <th style="height: 20px;width: 25%;text-align: center;"></th>
                    <th style="height: 20px;width: 25%;text-align: center;"></th>
                    <th style="height: 20px;width: 25%;text-align:center;">{{$location->unitmeasureid}}</th>
                </tr>
            @endforeach
        </table>
    </div>
</div>

</body>

</html>
