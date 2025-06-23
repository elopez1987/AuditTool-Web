<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Evidencias en entrega </title>

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
            text-align: center !important;
            vertical-align: middle;
            padding: 10px;
            border: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
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
        <img src="{{asset('img/trax.jpg')}}" alt="" style="width:80%;height: 150px;margin:auto;display:block">
        <div class="row" style="margin-top:30px ;">
            <p style="text-align: center;font-size: 30px;font-weight: 700;">EVIDENCIA FOTOGRAFICA</p>
        </div>

        <div class="row" style="margin-top:100px ;">
            <table>
                <tr>
                    <td class="border-none">EMBARQUE :</td>
                </tr>
                <tr>
                    <td class="border-none">{{$shipment->shipmentid}}</td>
                </tr>
            </table>
            <table style="margin-top:30px ;">
                <tr>
                    <td class="border-none">CLIENTE :</td>
                </tr>
                <tr>
                    <td class="border-none">{{$shipment->customer ? $shipment->customer->name : ''}}</td>
                </tr>
            </table>
        </div>
        <?php
        foreach ($files as $file) {
            if (strpos($file->{'path'}, '/') !== false) {
                $explode = explode("/", $file->{'path'});
            }
            if (strpos($file->{'path'},'\\') !== false){
                $explode = explode("\\", $file->{'path'});
            }
//            $nombreFinal = 'https://app.rcontrol.com.mx/Pictures/' . $explode[0] . '//' . $explode[1] . '//' . $explode[2] . '/' . $explode[3];
            $nombreFinal = 'https://storage.googleapis.com/iron-web-public/Files/Images/' . $explode[0] . '/' . $explode[1] . '/' . $explode[2] . '/' . $explode[3];
            echo "<img src='$nombreFinal' style=' max-width: 90%;max-height: 90%' />";
        }
        ?>
    </div>
</main>


</body>

</html>
