<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cédula de Inventario</title>
    <style>
        @page {
            margin: 0 0;
        }

        html {
            font-weight: 400;
            position: relative;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 10px;
            color: black;
            font-weight: 400
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

        .container {
            padding: 0;
            border: 0;
            width: 719px !important;
            height: 512px !important;
        }

    </style>
</head>

<body>

<div class="row">
    <table class="border-none">
        <th style="height: 20px;width: 25%;">
            <img src='{{asset('storage/' . $inventoryCardCount->key_user_count_01 )}}'
                 style="width: 50px;height:50px; z-index: 999; top:0; left:0;position: relative">
        </th>
        <th style="height: 20px;width: 50%;text-align: center;font-size: 20px;">
            CÉDULA PARA TOMA FÍSICA DE INVENTARIOS
        </th>
        <th style="height: 20px;width: 25%;text-align: left;">
            Código: GSID-16-SIGEO-FO-47 <br>
            Nivel de Revisión: 00 <br>
            Fecha de Aplicación: 15.09.2023 <br>
            Fecha de vigencia: 15.09.2024 <br><br>
        </th>
    </table>
</div>
<div class="row" style="margin-top: 5px">
    <table class="border-none">
        <th style="height: 20px;width: 20%;">PRIMER CONTEO </th>
        <th style="height: 20px;width: 50%;text-align: left;"> NOMBRE: {{$inventoryCardCount->inventoryhead->name}}</th>
    </table>
</div>
<div class="row" style="margin-top: 5px">
    <table class="border-none">
        <th style="height: 20px;width: 50%;text-align: left;"> CONTADOR: {{$inventoryCardCount->user_count01->name}}</th>
    </table>
</div>
<div class="row">
    <table class="border-none">
        <th style="height: 20px;width: 80%;background-color: black;color: white;font-size: 20px;">INVENTARIO FISICO</th>
        <th style="height: 20px;width: 20%;font-size: 24px;border-top: 0;vertical-align: text-top;"> {{$inventoryCardCount->inventorycard ? $inventoryCardCount->inventorycard->name : ''}}</th>
    </table>
</div>


{{--<main>--}}
<div class="container">
    <div class="row">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 25%;">UBICACIÓN</th>
                <th style="height: 20px;width: 25%;text-align: left;"> IDH</th>
                <th style="height: 20px;width: 25%;"> CANTIDAD</th>
                <th style="height: 20px;width: 25%;"> U.MEDIDA</th>
            </tr>
            @foreach($locations as $location)
                <tr>
                    <th style="height: 20px;width: 25%;text-align: center;">{{$location->inventorylocation ? $location->inventorylocation->name : ''}}</th>
                    <th style="height: 20px;width: 25%;text-align: center;">{{$location->inventorypartnumber01 ? $location->inventorypartnumber01->prtnum : ''}}</th>
                    <th style="height: 20px;width: 25%;text-align: center;">{{$location->quantity01}}</th>
                    <th style="height: 20px;width: 25%;text-align:center;">{{$location->inventorylocation ? $location->inventorylocation->inventoryunitsmeasure?->description : ''}}</th>
                </tr>
            @endforeach
        </table>
    </div>
</div>
{{--</main>--}}

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(260, 810, "Página :  $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
</script>


</body>

</html>
