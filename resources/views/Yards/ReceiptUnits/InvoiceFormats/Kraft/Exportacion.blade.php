<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Folio de Unidad para embarque</title>
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

        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        tr:nth-child(even) {
            /*background-color: #f2f2f2*/
        }

        .border-none {
            border: 0;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <table>
            <th style="width: 120px;text-align: center">
                <img src="{{ public_path('img/kraft/logo.png') }}" alt="" style="width: 80px;height: 40px">
            </th>
            <th>
                <p style="font-size: 18px;font-weight: 700;text-align: center">
                    CHECK LIST DE EXPORTS
                </p>
            </th>
            <th style="width:20%">
                <table>
                    <tr>
                        <th style="height: 15px;border-top: 0;border-left: 0;border-right: 0">Código</th>
                        <th style="border-top: 0;border-right: 0">CK-EXP2.3201</th>
                    </tr>
                    <tr>
                        <th style="height: 15px;border-left: 0">Revisión</th>
                        <th style="border-right: 0">2</th>
                    </tr>
                    <tr>
                        <th style="height: 15px;border-bottom: 0;border-left: 0">Fecha de revisión</th>
                        <th style="border-bottom: 0;border-right: 0">12/12/22</th>
                    </tr>
                </table>
            </th>
        </table>
    </div>
    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <tr>
                <th style="width: 20%;height: 15px;border-bottom: 0;border-top: 0;border-left: 0"></th>
                <th style="width: 15%;text-align: left;background-color: darkblue;color: white">No. Contenedor</th>
                <th style="width: 20%">{{$receiptunit->boxnumber}} / {{$receiptunit->econumber}}</th>
                <th style="width: 5%;border-bottom: 0;border-top: 0"></th>
                <th style="width: 15%;background-color: darkblue;color: white">Fecha</th>
                <th style="width: 30%;border-bottom: 1px solid">{{$receiptunit->entrydate}}</th>
            </tr>

        </table>
    </div>
    <div class="row" style="margin-top: 10px">
        <table class="border-none">
            <tr>
                <th style="width: 20%;height: 15px;border-bottom: 0;border-top: 0;border-left: 0;margin-top: 5px"></th>
                <th style="width: 15%;text-align: left;background-color: darkblue;color: white;border-bottom: 1px solid !important;">
                    No. Folio
                </th>
                <th style="width: 20%">{{$receiptunit->id}}</th>
                <th style="width: 5%;" class="border-none"></th>
                <th style="width: 15%;" class="border-none"></th>
                <th style="width: 30%;" class="border-none"></th>
            </tr>
        </table>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="border-none">
            <tr class="border-none">
                <th style="height: 15px;text-align: center;font-size: 12px;border: 0;font-style: oblique"
                    class="border-none">Marque con una  <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px"> o una <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px"> conforme aplique.
                </th>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 20px">
        <table>
            <tr>
                <th style="background-color: darkblue;color: white;height: 15px;text-align: center;font-size: 11px"
                    class="4">Check List de inspección
                </th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table>
            <tr>
                <th style="width: 5%;height: 15px">#</th>
                <th style="width: 15%;text-align: left">Defecto</th>
                <th style="width: 70%">Descripción del defecto.</th>
                <th style="width: 10%">SI/NO</th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">1</th>
                <th style="width: 15%;text-align: left">Filtración de luz.</th>
                <th style="width: 70%;text-align: left">¿El contenedor está libre de algún orificio que permita la
                    entrada de luz/partículas/etc. al interior?
                </th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '1')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">2</th>
                <th style="width: 15%;text-align: left">Limpieza</th>
                <th style="width: 70%;text-align: left">¿El contenedor está limpio de el piso y en las paredes
                    internas?
                </th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '2')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">3</th>
                <th style="width: 15%;text-align: left">Marcaje</th>
                <th style="width: 70%;text-align: left">¿El contenedor tiene marcas/etiquetas/letreros en
                    interior/exterior ajenas?
                </th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '3')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">4</th>
                <th style="width: 15%;text-align: left">Olor.</th>
                <th style="width: 70%;text-align: left">¿El contenedor está libre de algún tipo de olor inusual?</th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '4')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">5</th>
                <th style="width: 15%;text-align: left">Contaminación.</th>
                <th style="width: 70%;text-align: left">¿El contenedor está libre de cualquier tipo de
                    hongo/sarro/materia extraña que afecte el interior?
                </th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '5')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">6</th>
                <th style="width: 15%;text-align: left">Sellado.</th>
                <th style="width: 70%;text-align: left">¿El contenedor puede ser sellado de forma habitual?</th>
                <th style="width: 10%">
                    @foreach($verified as $model)
                        @if($model->idcheck == '6')
                            @if($model->verified == 'SI')
                                <img src="{{ public_path('img/kraft/check-mark-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @else
                                <img src="{{ public_path('img/kraft/close-icon.png') }}" alt="" style="width: 9px;height: 9px">
                            @endif
                        @endif
                    @endforeach
                </th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">7</th>
                <th style="width: 15%;text-align: left">Otro.</th>
                <th style="width: 70%;text-align: left"></th>
                <th style="width: 10%"></th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">8</th>
                <th style="width: 15%;text-align: left">Otro.</th>
                <th style="width: 70%;text-align: left"></th>
                <th style="width: 10%"></th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">9</th>
                <th style="width: 15%;text-align: left">Otro.</th>
                <th style="width: 70%;text-align: left"></th>
                <th style="width: 10%"></th>
            </tr>
            <tr>
                <th style="width: 5%;height: 15px;text-align: center">10</th>
                <th style="width: 15%;text-align: left">Otro.</th>
                <th style="width: 70%;text-align: left"></th>
                <th style="width: 10%"></th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 20px">
        <table class="border-none">
            <tr class="border-none">
                <th class="border-none">
                    <p style="font-size: 11px;font-weight: 700;text-align: center">
                        En caso de que un punto de los anteriores no cumpla, comuníquese inmediatamente con el personal
                        de Export KraftHeinz para definir acuerdos/plan de acción para el/los contenedores.
                    </p>
                </th>
            </tr>
        </table>
    </div>


    <div class="row" style="margin-top: 20px">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: center;height: 25px;vertical-align:middle" colspan="3">Ayudas
                    visuales de defectos.
                </th>
            </tr>
            <tr>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/filtracionluz.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/limpieza.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/marcaje.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
            </tr>
            <tr>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/olor.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/contaminacion.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
                <th style="width: 33%;height: 60px">
                    <img src="{{ public_path('img/kraft/sellado.png') }}" alt="" style="width: 200px;height: 100px">
                </th>
            </tr>
        </table>
    </div>

    <div class="row" style="margin-top:20px">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">#</th>
                <th style="border: 1px solid;text-align: center;vertical-align:middle;font-size: 12px; font-weight: 700">
                    Acuerdos / Información adicional
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">1</th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">2</th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">3</th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">4</th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 8%;height: 25px;vertical-align:middle">5</th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom"></th>
            </tr>
        </table>
        <table>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 50%;height: 25px;vertical-align: bottom">Revisó
                </th>
                <th style="border: 1px solid;text-align: center;vertical-align: bottom">Autorizó</th>
            </tr>
        </table>
    </div>
</div>
</body>

</html>
