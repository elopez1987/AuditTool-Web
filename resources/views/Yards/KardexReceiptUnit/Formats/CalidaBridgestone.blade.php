<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check List Calidad Bridgestone</title>
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

        /*tr:nth-child(even) {*/
        /*    background-color: #f2f2f2*/
        /*}*/

        .border-none {
            border: 0px;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <table class="border-none">
            <tr>
                <th width="25%" class="border-none">
                    <img src="img/Bridgestone.png" alt="" style="height: 25px;width: 100px">
                </th>
                <th class="border-none">INSPECCION DEL TRACTOCAMION Y DEL SEMIREMOLQUE - {{$receiptunit->id}}</th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th width="10%" style="background-color: #D5D5D5">FECHA</th>
            <th WIDTH="30%">{{$receiptunit->entrydate}}</th>
            <th width="30%" style="background-color: #D5D5D5">TIPO DE REMOLQUE</th>
            <th>
                <table class="border-none">
                    <th style="border-right: 0px">CONTENEDOR</th>
                    <th style="border-left: 0px;border-right: 0px">
                        <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;">
                            {{$receiptunit->truckcategoryid == 'CO' ? 'X' : ''}}
                        </div>
                    </th>
                    <th style="border-left: 0px;border-right: 0px">CAJA</th>
                    <th style="border-right: 0px;border-left: 0px">
                        <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;">
                            {{$receiptunit->truckcategoryid == 'CS' ? 'X' : ''}}
                        </div>
                    </th>
                </table>
            </th>
        </table>
    </div>
    <div class="row">
        <table>
            <th width="10%" style="background-color: #D5D5D5">LINEA</th>
            <th width="30%">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</th>
            <th width="30%" style="background-color: #D5D5D5">NU.CAJA/CONTENEDOR</th>
            <th>{{$receiptunit->econumber}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th width="10%" style="background-color: #D5D5D5">DESTINO</th>
            <th WIDTH="30%">{{$receiptunit->unitdata ? $receiptunit->unitdata->customername : ''}}</th>
            <th width="60%">
                EL No DE REMOLQUE Y LA LINEA COINCIDEN CON LO REPORTADO POR DITRIBUCION
                <table class="border-none">
                    <th style="border-right: 0px;border-top: 0px">INTERNACIONAL</th>
                    <th style="border-right: 0px;border-left: 0px;border-top:0px">SI</th>
                    <th style="border-left: 0px;border-right: 0px;border-top:0px">
                        <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                            {{$receiptunit->unittype->auxiliary01  == 'EXP' ? 'X' : ''}}
                        </div>
                    </th>
                    <th style="border-left: 0px;border-right: 0px;border-top:0px">NO</th>
                    <th style="border-right: 0px;border-left: 0px;border-top:0px">
                        <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;">
                            {{$receiptunit->unittype->auxiliary01  == 'NAC' ? 'X' : ''}}
                        </div>
                    </th>
                </table>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th STYLE="background-color: #D5D5D5">INSPECCION DE CONTENEDOR / REMOLQUE</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th STYLE="background-color: #D5D5D5">INSTRUCCIONES : REGISTRAR LAS MEDIDAS DEL REMOLQUE OBTENIDAS EN LA
                INSPECCION / VERIFICACION UNIDAD : MT/CM
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th width="40%" style="background-color: #D5D5D5">EN CASO DE CONTENEDOR, ESPECIFICAR LA MEDIDA:</th>
            <th width="15%">
                <table class="border-none">
                    <th class="border-none">
                        <div
                                style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;;margin-left: 5px"></div>
                    </th>
                    <th class="border-none">20 FT</th>
                </table>
            </th>
            <th width="15%">
                <table class="border-none">
                    <th class="border-none">
                        <div
                                style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;;margin-left: 5px">
                            {{ $receiptunit->truck?->auxiliary01 == 'CJ-40' ? 'X' : '' }}
                        </div>
                    </th>
                    <th class="border-none">40 FT</th>
                </table>
            </th>
            <th width="15%">
                <table class="border-none">
                    <th class="border-none">
                        <div
                                style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;;margin-left: 5px">
                            {{ $receiptunit->truck?->auxiliary01 == 'CJ-48' ? 'X' : '' }}
                        </div>
                    </th>
                    <th class="border-none">48-HC FT</th>
                </table>
            </th>
            <th width="15%">
                <table class="border-none">
                    <th class="border-none">
                        <div
                                style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center;margin-left: 5px">
                            {{ $receiptunit->truck?->auxiliary01 == 'CJ-53' ? 'X' : '' }}
                        </div>
                    </th>
                    <th class="border-none">53-HC FT</th>
                </table>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th width="30%" style="height: 40px">
                <img src="img/A.png" alt="" style="width: 100%;height: 100px">
            </th>
            <th width="10%">
                <div class="row">
                    <table>
                        <tr>
                            <th style="text-align: center">A</th>
                        </tr>
                        <tr>
                            <th>TOLERANCIA</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th style="text-align: center">B</th>
                        </tr>
                        <tr>
                            <th>TOLERANCIA</th>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <th STYLE="text-align: center">C</th>
                        </tr>
                        <tr>
                            <th>TOLERANCIA</th>
                        </tr>
                    </table>
                </div>
            </th>

            @if($receiptunit->truck?->auxiliary01 == 'CJ-40' || $receiptunit->truck?->auxiliary01 == 'CJ-20')
                <th WIDTH="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">2.38M</th>
                            </tr>
                            <tr>
                                <th STYLE="text-align: center">+ 21 CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">5.89M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+16CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center">2.33M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+10CM</th>
                            </tr>
                        </table>
                    </div>
                </th>
                <th width="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px"></th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px"></th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center;height: 30px"></th>
                            </tr>
                        </table>
                    </div>
                </th>
            @endif

            @if($receiptunit->truck?->auxiliary01 == 'CJ-48')
                <th width="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">2.38M</th>
                            </tr>
                            <tr>
                                <th STYLE="text-align: center">+ 21 CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">12.01M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+18CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center">2.33M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+10CM</th>
                            </tr>
                        </table>
                    </div>
                </th>
                <th width="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[0] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[1] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[2] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </th>
            @endif

            @if($receiptunit->truck?->auxiliary01 == 'CJ-53')
                <th width="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">2.69M</th>
                            </tr>
                            <tr>
                                <th STYLE="text-align: center">+ 20CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center">12.01M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+18CM</th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center">2.33M</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">+10CM</th>
                            </tr>
                        </table>
                    </div>
                </th>
                <th width="10%">
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[0] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th style="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[1] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <th STYLE="text-align: center;height: 30px">
                                    {{ $receiptunit->unitdata->tolerance[2] ?? '' }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </th>
            @endif

        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th STYLE="background-color: #D5D5D5">INSPECCION DE AGRICULTURA</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th>INSTRUCCIONES: Señalar con <img src="img/kraft/check-mark-icon.png" alt=""
                                                style="width: 9px;height: 9px"> =Inspeccionado Aprobado <img
                        src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px"> = Inspeccionado No
                Aprobado NO dejar espacios en
                blanco
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th width="12.5%" style="background-color: #D5D5D5">DAÑOS</th>
            <th width="12.5%">
                @foreach($verified as $item)
                    @if($item->idcheck == "32")
                        @if($item->verified == 'SI')
                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                        @elseif($item->verified == 'NO')
                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                        @else
                            {{$item->verified}}
                        @endif
                    @endif
                @endforeach
            </th>
            <th width="12.5%" style="background-color: #D5D5D5">OLORES</th>
            <th width="12.5%">
                @foreach($verified as $item)
                    @if($item->idcheck == "33")
                        @if($item->verified == 'SI')
                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                        @elseif($item->verified == 'NO')
                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                        @else
                            {{$item->verified}}
                        @endif
                    @endif
                @endforeach
            </th>
            <th width="12.5%" style="background-color: #D5D5D5">PLAGAS</th>
            <th width="12.5%">
                @foreach($verified as $item)
                    @if($item->idcheck == "34")
                        @if($item->verified == 'SI')
                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                        @elseif($item->verified == 'NO')
                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                        @else
                            {{$item->verified}}
                        @endif
                    @endif
                @endforeach
            </th>
            <th width="12.5%" style="background-color: #D5D5D5">BASURA</th>
            <th width="12.5%">
                @foreach($verified as $item)
                    @if($item->idcheck == "35")
                        @if($item->verified == 'SI')
                            <img src="img/kraft/check-mark-icon.png" alt="" style="width: 9px;height: 9px">
                        @elseif($item->verified == 'NO')
                            <img src="img/kraft/close-icon.png" alt="" style="width: 9px;height: 9px">
                        @else
                            {{$item->verified}}
                        @endif
                    @endif
                @endforeach
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th STYLE="background-color: #D5D5D5">INSPECCION A OBSCURAS</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th>INSTRUCCIONES: SEÑALAR LAS FILTRACIONES DE LUZ Y/0 GOTERAS EN CADA UNA DE LAS IMAGENES</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <tr>
                <th style="width: 300px;height: 150px;">
                    @foreach($photos as $photo)
                        @if($photo->auxiliary05 == '_floor')
                            @php
                                $floorPhotoPath = str_starts_with($photo->path, 'public/')  ? storage_path('app/' . $photo->path) : storage_path('app/public/' . $photo->path);
                            @endphp
                            @if(file_exists($floorPhotoPath))
                                <img src="{{ $floorPhotoPath }}" alt="" style="width: 350px;height: 100px">
                            @endif
                        @endif
                    @endforeach
                </th>
                <th style="width: 300px;height: 150px">
                    @foreach($photos as $photo)
                        @if($photo->auxiliary05 == '_roof')
                            @php
                                $roofPhotoPath = str_starts_with($photo->path, 'public/') 
                                    ? storage_path('app/' . $photo->path)
                                    : storage_path('app/public/' . $photo->path);
                            @endphp
                            @if(file_exists($roofPhotoPath))
                                <img src="{{ $roofPhotoPath }}" alt="" style="width: 350px;height: 100px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 300px;height: 150px">
                    @foreach($photos as $photo)
                        @if($photo->auxiliary05 == 'background')
                            @php
                                $backgroundPhotoPath = str_starts_with($photo->path, 'public/') 
                                    ? storage_path('app/' . $photo->path)
                                    : storage_path('app/public/' . $photo->path);
                            @endphp
                            @if(file_exists($backgroundPhotoPath))
                                <img src="{{ $backgroundPhotoPath }}" alt="" style="width: 350px;height: 100px">
                            @endif
                        @endif
                    @endforeach
                </th>
                <th style="width: 300px;height: 150px">
                    @foreach($photos as $photo)
                        @if($photo->auxiliary05 == 'doors')
                            @php
                                $doorsPhotoPath = str_starts_with($photo->path, 'public/') 
                                    ? storage_path('app/' . $photo->path)
                                    : storage_path('app/public/' . $photo->path);
                            @endphp
                            @if(file_exists($doorsPhotoPath))
                                <img src="{{ $doorsPhotoPath }}" alt="" style="width: 350px;height: 100px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th STYLE="background-color: #D5D5D5">RESULTADO DE LA REVISION</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <tr>
                <th style="width: 25%">DOCUMENTACION CORRECTO</th>
                <th style="width: 25%">DISCREPANCIAS EN DOCUMENTOS</th>
                <th style="width: 25%">SALIDA AUTORIZADA</th>
                <th style="width: 25%">NOMBRE DE QUIEN AUTORIZA</th>
            </tr>
            <tr>
                <th style="width: 25%">
                    <table class="border-none">
                        <th class="border-none">APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "1")
                                        @if($item->verified == '1')
                                            <img src="img/kraft/che" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                        <th class="border-none">NO APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "1")
                                        @if($item->verified == '0')
                                            <img src="img/kraft/che" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                    </table>
                </th>
                <th style="width: 25%">
                    <table class="border-none">
                        <th class="border-none">APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "2")
                                        @if($item->verified == '1')
                                            <img src="img/kraft/che" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                        <th class="border-none">NO APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "2")
                                        @if($item->verified == '0')
                                            <img src="img/kraft/che" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                    </table>
                </th>
                <th style="width: 25%">
                    <table class="border-none">
                        <th class="border-none">APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "3")
                                        @if($item->verified == '1')
                                            <img src="img/kraft/check-mark-icon.png" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                        <th class="border-none">NO APROBADO</th>
                        <th class="border-none">
                            <div style="width: 10px;height: 10px;border: 1px solid #D5D5D5;align-items: center">
                                @foreach($verified as $item)
                                    @if($item->idcheck == "3")
                                        @if($item->verified == '0')
                                            <img src="img/kraft/check-mark-icon.png" alt=""
                                                 style="width: 9px;height: 9px">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </th>
                    </table>
                </th>
                <th style="width: 25%;text-transform: uppercase;">
                    {{$receiptunit->key ? $receiptunit->key->nameautoriza : ''}}
                </th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <tr>
                <th>COMENTARIOS/OBSERVACIONES</th>
                <th>FIRMA DE AUTORIZACION</th>
            </tr>
            <tr>
                <th style="height: 45px;background: white">
                    @foreach($comments as $comment)
                        @if($comment->auxiliary01 == 'AU')
                            <ul>{{$comment->comment}}</ul>
                        @endif
                    @endforeach
                </th>
                <th style="background: white">
                    @if(!empty($receiptunit->key))
                        <img src="{{storage_path('app/' . $receiptunit->key->keyautoriza )}}" alt="Firma"
                             style="height: 40px;max-height: 40px"/>
                    @endif
                </th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="background-color: #D5D5D5">Certifico que el remolque corresponde al destino que se indica y fue
                inspeccionado en su INTERIOR previo a la carga del producto y se coloca sello de seguridad.
            </th>
        </table>
    </div>
    <div class="row" style="position:relative; z-index: 1">
        <table>
            <tr>
                <th style="width: 20%;background-color: #D5D5D5;height: 35px !important;">SELLOS COLOCADOS</th>
                <th style="width: 40%">{{$receiptunit->key?->containersealout}}</th>
                <th></th>
            </tr>
            <tr>
                <th style="width: 20%;background-color: #D5D5D5;height: 50px !important;">OFICIAL DE ALMACEN</th>
                <th style="width: 40%;text-align: left">
                    {{--                    {{$receiptunit->key ? $receiptunit->key->nameguard02 : ''}}--}}
                    {{$receiptunit->order?->folio_count?->user?->name}}
                </th>
                <th style="position: relative;">
                    <div >
{{--                        @if(!empty($receiptunit->key))--}}
{{--                            <img src="{{storage_path('app/' . $receiptunit->key->keyguard02 )}}" alt="Firma" style="height: 28px;max-height: 28px"/>--}}
{{--                        @endif--}}
                        @if(!empty($receiptunit->order->folio_count))
                            <img src="{{storage_path('app/' . $receiptunit->order->folio_count->keyguard )}}" alt="Firma" style="max-height: 40px;max-width: 40px;"/>
                        @endif
{{--                        <img src="{{asset('img/firma.png')}}" alt="Firma" style="max-height: 40px;max-width: 40px;"/>--}}
                    </div>
                </th>
            </tr>
            <tr>
                <th style="width: 20%;background-color: #D5D5D5;height: 50px !important;">SUPERVISOR - ALMACEN</th>
                <th style="width: 40%;text-align: left">
{{--                    {{$receiptunit->key ? $receiptunit->key->supervisorname : ''}}--}}
                    {{$receiptunit->order?->verification_pending?->usersupr?->name}}
                </th>
                <th>
{{--                    @if(!empty($receiptunit->key))--}}
{{--                        <img src="{{storage_path('app/' . $receiptunit->key->supervisorkey )}}" alt="Firma"--}}
{{--                             style="height: 28px;max-height: 28px"/>--}}
{{--                    @endif--}}
                    @if(!empty($receiptunit->order->folio_count))
                        <img src="{{storage_path('app/' . $receiptunit->order?->folio_count?->keysupervisor )}}" alt="Firma" style="max-height: 40px;max-width: 40px;"/>
                    @endif
{{--                        <img src="{{asset('img/firma.png')}}" alt="Firma" style="max-height: 40px;max-width: 40px;"/>--}}
                </th>
            </tr>
        </table>
    </div>

    <div class="row" style="position:relative; z-index: 1;margin-top: 20px">
        <table class="border-none">
            <tr>
                <th style="width: 20%;height: 20px;border: none">SEG-IIUC-058-PC</th>
            </tr>
        </table>
    </div>

</div>

</body>

</html>

