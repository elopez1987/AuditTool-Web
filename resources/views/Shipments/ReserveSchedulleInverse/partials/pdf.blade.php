<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cita de devolución -- {{$reserveschedule->id}}</title>
    <style>
        @page {
            margin: 0 0;
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
            left: 0;
            right: 0;
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

        .uuid-footer {
            position: fixed;
            bottom: 20px; /* Ajusta el valor según sea necesario */
            left: 10px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
<div class="container">

    <main>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <th style="height: 20px;width: 25%;text-align: center">
                    {!! DNS2D::getBarcodeHTML($reserveschedule->token, 'QRCODE',2,2) !!}
                </th>
                <th style="height: 20px;width: 50%;text-align: center;font-size: 15px;">
                    FORMATO DE RECHAZOS Y DEVOLUCIONES
                </th>
                <th style="height: 20px;width: 25%;text-align: left;">
                    Código: <br>
                    Nivel de Revisión: 00 <br>
                    Fecha de Aplicación: <br>
                    Fecha de vigencia: <br>
                    Páginas: 1 de 1 <br>

                </th>
            </table>
        </div>
        <div class="row" style="margin-top: 5px;border-top:0">
            <table class="border-none">
                <th style="height: 20px;width: 20%;">FOLIO DE CITA:</th>
                <th style="height: 20px;width: 60%;text-align: center;"> LINEA DE TRANSPORTE</th>
                <th style="height: 20px;width: 20%;"> FECHA DE CITA</th>
            </table>
        </div>
        <div class="row ">
            <table class="border-none">
                <th style="height: 20px;width: 20%;border-top: 0;">YMS - {{$reserveschedule->id}}</th>
                <th style="height: 20px;width: 60%;text-align: center;border-top: 0;">
                    {{$reserveschedule->carrier ? $reserveschedule->carrier->name : ''}}
                </th>
                <th style="height: 20px;width: 20%;border-top: 0;"> {{$details->workday}} - {{$details->bloque}} </th>
            </table>
        </div>
        <br>
        <div class="row" style="margin-top: 5px">
            <table class="border-none">
                <thead>
                <tr>
                    <th style="height: 20px;width: 15%;background-color: black;color: white">EMBARQUE</th>
                    <th style="height: 15px;width: 70%;text-align: left;background-color: black;color: white"> CLIENTE
                    </th>
                    <th style="height: 10px;width: 15%;background-color: black;color: white"> CANTIDAD</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shipments as $item)
                    <tr>
                        <td style="height: 20px;width: 10%;">{{$item->shipmentid}}</td>
                        <td style="height: 15px;width: 10%;text-align: left;"> {{$item->customername}}</td>
                        <td style="height: 20px;width: 25%;"> {{$item->quantity}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </main>

</div>
<div class="uuid-footer">
    UUID: {{$reserveschedule->value01}}
</div>
</body>

</html>
