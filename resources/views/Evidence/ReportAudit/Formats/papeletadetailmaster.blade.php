<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> DETALLE DE MASTER</title>
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
            <th style="height: 20px;width: 50%;text-align: center;font-size: 14px;">
               AUDITORIA DE MISCELÁNEOS FINAL DC
            </th>
            <th style="height: 20px;width: 25%;text-align: left;font-size: 9px !important;">
                Código: GSID-26-SIGEO-FO-167 <br>
                Nivel de Revisión: 00 <br>
                Fecha de Aplicación: 16.10.2023 <br>
                Fecha de vigencia: 16.10.2024 <br>
            </th>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 33%" class="bg-cell">NUMERO DE CONTENEDOR:</th>
                <th style="height: 20px;width: 33%" class="bg-cell"> DESTINO:</th>
                <th style="height: 20px;width: 33%" class="bg-cell"> EMPACADOR:</th>
            </tr>
            <tr>
                <th style="height: 20px;">{{$details[0]->masterid}}</th>
                <th style="height: 20px;"></th>
                <th style="height: 20px;"></th>
            </tr>
            <tr>
                <th style="height: 20px;" class="bg-cell">TURNO:</th>
                <th style="height: 20px;text-align: center;" class="bg-cell"> AUDITOR:</th>
                <th style="height: 20px;" class="bg-cell"> :</th>
            </tr>
            <tr>
                <th style="height: 20px;"></th>
                <th style="height: 20px;"></th>
                <th style="height: 20px;"></th>
            </tr>
        </table>
    </div>
    <br>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;" class="bg-cell">ID.Container</th>
                <th style="height: 20px;" class="bg-cell">Sku</th>
                <th style="height: 20px;" class="bg-cell">Descripción</th>
                <th style="height: 20px;" class="bg-cell">Cantidad</th>
                <th style="height: 20px;" class="bg-cell">Fecha de registro</th>
            </tr>
            @foreach($details as $lpn)
                <tr>
                    <th style="height: 20px;">{{$lpn->lpnid}}</th>
                    <th style="height: 20px;">{{$lpn->partnumberid}}</th>
                    <th style="height: 20px;">{{$lpn->description}}</th>
                    <th style="height: 20px;">{{$lpn->quantity}}</th>
                    <th style="height: 20px;">{{$lpn->created_at}}</th>
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

