<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Kardex de Unidad - {{$receiptunit->id}}</title>
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

        .header-logo {
            width: 150px;
            height: 65px;
            object-fit: contain;
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
            background-color: #eceff1 !important;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <th style="height: 20px;width: 25%;">
                <img
                        src="{{ storage_path('app/' . $receiptunit->center->auxiliary04) }}"
                        alt="Logo"
                        class="header-logo"
                >
            </th>
            <th style="height: 20px;width: 50%;text-align: center;font-size: 14px;">
                KARDEX DE UNIDAD - {{$receiptunit->id}}
            </th>
            <th style="height: 20px;width: 25%;text-align: left;font-size: 9px !important;">
                {{--                @if(!empty($receiptunit->warehouse->auxiliary09))--}}
                {{--                    <img src="{{ storage_path('app/public/' . $receiptunit->warehouse->auxiliary09) }}"--}}
                {{--                         class="align-self-center img-responsive" alt="" style="height: 60px;max-width: 180px">--}}
                {{--                @endif--}}
            </th>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">FOLIO DE UNIDAD</th>
                <th style="height: 20px;width: 60%;text-align: center;" class="bg-cell">LINEA DE TRANSPORTE</th>
                <th style="height: 20px;width: 20%;" class="bg-cell">FECHA DE INGRESO</th>
            </tr>
            <tr>
                <td style="height: 20px;width: 20%;">{{$receiptunit->id}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->entrydate}}</td>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">PLACA DE CAJA</th>
                <th style="height: 20px;width: 60%;text-align: center;" class="bg-cell">NOMBRE DE OPERADOR</th>
                <th style="height: 20px;width: 20%;" class="bg-cell">PLACA DE TRACTO</th>
            </tr>
            <tr>
                <td style="height: 20px;width: 20%;">{{$receiptunit->boxnumber}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->operatorname}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->trucknumber}}</td>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">EMBARQUE</th>
                <th style="height: 20px;width: 60%;text-align: center;" class="bg-cell">CONCEPTO DE ENTRADA</th>
                <th style="height: 20px;width: 20%;" class="bg-cell">SELLOS</th>
            </tr>
            <tr>
                <td style="height: 20px;width: 20%;">{{$receiptunit->shipmentid}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->reason ? $receiptunit->reason->name : ''}}</td>
                <td style="height: 20px;width: 20%;">{{$receiptunit->containerseal}}</td>
            </tr>
        </table>
    </div>
    <br>
    <div class="row" style="margin-top: 10px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">DETALLE DE MOVIMIENTOS</th>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 30%;" class="bg-cell">EVENTO</th>
                <th style="height: 20px;width: 50%;text-align: center;" class="bg-cell">USUARIO</th>
                <th style="height: 20px;width: 20%;" class="bg-cell">FECHA DE MOVIMIENTO</th>
            </tr>
            @foreach($traces as $trace)
                <tr>
                    <td>{{$trace->event ? $trace->event->name  : ''}}</td>
                    <td style="text-transform: uppercase">{{$trace->user ? $trace->user->name  : ''}}</td>
                    <td>{{$trace->created_at}}</td>
                </tr>
            @endforeach
        </table>
    </div>


    <div class="row" style="margin-top: 10px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">EVIDENCIA DE ENTRADA</th>
            </tr>
        </table>
    </div>

    @if ($evidences->isNotEmpty())
        @php
            $count = 0;
        @endphp
        <div class="row">
            <table class="border-none" style="width: 100%;">
                <tr>
                    @foreach($evidences as $image)
                        @if($image->auxiliary01 == 'IN')
                            <td class="border-none" style="text-align: center; padding: 10px;">
                                @php
                                    $doorsPhotoPath = str_starts_with($image->path, 'public/')
                                        ? storage_path('app/' . $image->path)
                                        : storage_path('app/public/' . $image->path);
                                @endphp
                                @if(file_exists($doorsPhotoPath))
                                    <img src="{{ $doorsPhotoPath }}" alt="" style="width: 350px;height: 200px">
                                @endif
                            </td>
                            @php
                                $count++;
                            @endphp
                            @if($count % 2 == 0)
                </tr>
                <tr>
                    @endif
                    @endif
                    @endforeach
                </tr>
            </table>
        </div>
    @endif

    @if ($evidencesCTPAT->isNotEmpty())
        @php
            $count = 0;
        @endphp
        <div class="row">
            <table class="border-none" style="width: 100%;">
                <tr>
                    @foreach($evidencesCTPAT as $image)
                        @if($image->type == 'IN')
                            <td class="border-none" style="text-align: center; padding: 10px;">
                                <img src='{{storage_path('app/' . $image->photo_1)}}' alt=""
                                     style="height: 200px;max-width: 350px">
                            </td>
                            @php
                                $count++;
                            @endphp
                            @if($count % 2 == 0)
                </tr>
                <tr>
                    @endif
                    @endif
                    @endforeach
                </tr>
            </table>
        </div>
    @endif


    <div class="row" style="margin-top: 10px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">EVIDENCIA DE PROCESO</th>
            </tr>
        </table>
    </div>
    <br>
    <div style="margin-top: 10px">

        @if ($evidences->isNotEmpty())
            @php
                $count = 0;
            @endphp
            <div class="row">
                <table class="border-none" style="width: 100%;">
                    <tr>
                        @foreach($evidences as $image)
                            @if($image->auxiliary01 == 'IP')
                                <td class="border-none" style="text-align: center; padding: 10px;">
                                    @php
                                        $ipPhotoPath = str_starts_with($image->path, 'public/')
                                            ? storage_path('app/' . $image->path)
                                            : storage_path('app/public/' . $image->path);
                                    @endphp
                                    @if(file_exists($ipPhotoPath))
                                        <img src="{{ $ipPhotoPath }}" style="width: 350px; height: 200px; margin: 5px;">
                                    @endif
                                </td>
                                @php
                                    $count++;
                                @endphp
                                @if($count % 2 == 0)
                    </tr>
                    <tr>
                        @endif
                        @endif
                        @endforeach
                    </tr>
                </table>
            </div>
        @endif

        @if ($evidencesTiros->isNotEmpty())
            @foreach($evidencesTiros as $image)
                <div class="row">
                    <table class="border-none">
                        <tr>
                            <th class="bg-cell">
                                <span>{{$image->description}}</span>
                            </th>
                        </tr>
                        <tr>
                            <td class="border-none">
                                @php
                                    $tirosPhotoPath = str_starts_with($image->path, 'public/')
                                        ? storage_path('app/' . $image->path)
                                        : storage_path('app/public/' . $image->path);
                                @endphp
                                @if(file_exists($tirosPhotoPath))
                                    <img src="{{ $tirosPhotoPath }}"
                                         style="width: 350px;height:200px;margin-left: 10px; margin-right: 10px;">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        @endif


    </div>

    <div class="row" style="margin-top: 5px">
        <table class="border-none">
            <tr>
                <th style="height: 20px;width: 20%;" class="bg-cell">EVIDENCIA DE SALIDA</th>
            </tr>
        </table>
    </div>

    @if ($evidences->isNotEmpty())
        @php
            $count = 0; // Contador para controlar cuando cerrar la fila
        @endphp
        <div class="row">
            <table class="border-none" style="width: 100%;">
                <tr>
                    @foreach($evidences as $image)
                        @if($image->auxiliary01 == 'OUT')
                            <td class="border-none" style="text-align: center; padding: 10px;">
                                @php
                                    $outPhotoPath = str_starts_with($image->path, 'public/')
                                        ? storage_path('app/' . $image->path)
                                        : storage_path('app/public/' . $image->path);
                                @endphp
                                @if(file_exists($outPhotoPath))
                                    <img src="{{ $outPhotoPath }}" style="width: 350px; height: 200px; margin: 5px;">
                                @endif
                            </td>
                            @php
                                $count++;
                            @endphp
                            @if($count % 2 == 0)
                </tr>
                <tr>
                    @endif
                    @endif
                    @endforeach
                </tr>
            </table>
        </div>
    @endif

    @if ($evidencesCTPAT->isNotEmpty())
        @php
            $count = 0;
        @endphp
        <div class="row">
            <table class="border-none" style="width: 100%;">
                <tr>
                    @foreach($evidencesCTPAT as $image)
                        @if($image->type == 'OUT')
                            <td class="border-none" style="text-align: center; padding: 10px;">
                                @php
                                    $doorsPhotoPath = str_starts_with($image->photo_1, 'public/') ? storage_path('app/' . $image->photo_1)  : storage_path('app/public/' . $image->photo_1);
                                @endphp
                                @if(file_exists($doorsPhotoPath))
                                    <img src="{{ $doorsPhotoPath }}" alt="" style="width: 350px;height: 200px">
                                @endif
                            </td>
                            @php
                                $count++;
                            @endphp
                            @if($count % 2 == 0)
                </tr>
                <tr>
                    @endif
                    @endif
                    @endforeach
                </tr>
            </table>
        </div>
    @endif


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

