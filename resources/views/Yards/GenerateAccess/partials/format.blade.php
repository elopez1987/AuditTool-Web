<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DETALLE DE ACCESO CIRCUITO PLANTA</title>
    <style>
        .container {
            padding: 0;
            border: 0;
            width: 690px;
        }

        html {
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 11px;
            color: black;

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
        }

        th, td {
            text-align: center;
            padding: 5px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        .logo {
            width: 150px;
            height: 60px;
            margin: auto;
        }

        .formato {
            padding-top: 10px;
        }

        .w20 {
            width: 20%;
        }

        .nota {
            color: red;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
            font-weight: 400;
            text-align: center;
            font-size: 11px;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="row">
        <table>
            <th style="width: 33%;">
                <img src="img/henkel.png" alt="" style="height: 70px;" class="logo">
            </th>
            <th style="width: 33%;">
                <h3 class='formato'> CONTROL DE ACCESO CIRCUITO PLANTA</h3>
            </th>
            <th style="text-align: left;font-size: 9px;">
                <span>Formato : </span><br>
                <span>Código : </span><br>
                <span>Nivel de Revisión: </span><br>
                <span>Fecha de Aplicación: </span><br>
                <span>Páginas : 1 de 1</span>
            </th>
        </table>
    </div>
    <div class="row" style="margin-bottom: 8px;margin-top: 20px;">
        <span style="font-size: 11px;color: black;margin-bottom: 5px;">DATOS GENERALES.</span>
    </div>
    <table>
        <thead>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">OPERADOR:</th>
            <th class="w30" style="text-align: left;padding: 2px;"> {{$data->operador}}</th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">TELEFONO</th>
            <th class="w30" style="text-align: left;padding: 2px;"> {{$data->phone}}</th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">LICENCIA</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->drivernumber}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">LINEA</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->carrier}}
            </th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">PLACA DE CAJA</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->caja}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">PLACA DE TRACTO</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->tracto}}
            </th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">CODIGO CAJA</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->barcodebox}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">CODIGO TRACTO</th>
            <th class="w30"
                style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->barcodetruck}}
            </th>
        </tr>
        <tr>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">F.INICIO</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->datestart}}
            </th>
            <th class="w20" style="text-align: left;padding: 2px;background-color:#eeeeee">F.FINAL</th>
            <th class="w30" style="text-align: left;padding: 2px;background-color:#ffffff"> {{$data->datefinish}}
            </th>
        </tr>
        </thead>
    </table>
    <br><br><br>


    <table>
        <thead>
            <tr>
                <th style="text-align: center;background-color:#eeeeee;width: 50%">CÓDIGO DE INGRESO</th>
                <th style="text-align: center;background-color:#eeeeee;width: 50%">CÓDIGO DE CAJA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td  style="padding: 10px;">
                    <?php
                    $token = strval($data->token);
                    echo DNS2D::getBarcodeHTML($token, 'QRCODE', 4, 4);
                    ?>
                </td>
                <td style="padding: 10px;">
                    <?php
                    $barcodebox = strval($data->barcodebox);
                    echo DNS2D::getBarcodeHTML($barcodebox, 'QRCODE', 4, 4);
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <br><br><br><br><br><br>

    <div class="row m-t-15">
        <div class="row" style="margin-bottom: 5px;margin-top: 60px;text-align: center">
        <span
            style="font-size: 11px;color: black;margin-bottom: 5px;text-align: center"> CODIGOS PARA ACCESO A PARKS </span>
        </div>
        <table style="border: 1px solid black">
            <tbody>
            <tr>
                <th style="width: 50%">
                    @if($data->qr != null)
                        <img src="{!! 'storage/' . $data->qr !!}" alt=""
                             style="max-width: 150px;height: 150px">
                    @endif
                </th>
                <th style="width: 50%">
                    @if($data->logo != null)
                        <img src="{!! 'storage/' . $data->logo !!}" alt=""
                             style="height: 150px;max-width: 150px">
                    @endif
                </th>
            </tr>
            </tbody>
        </table>
    </div>

    <h5 class='nota' style="margin-top:50px">Documento controlado por medios electrónicos.Para su uso exclusivo de
        Grupo SID Logística.Uso Restringido.</h5>
</div>

</body>

</html>
