@php use Illuminate\Support\Carbon; @endphp
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
            font-size: 7px;
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
               <img src="{{ public_path('img/taxion-logistic.png') }}" alt="" style="width: 80px;height: 40px">
            </th>
            <th>
                <div class="row">
                    <table class="border-none">
                        <th style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 80%;padding: 0;border-bottom: 0;">
                            CÉDULA DE INSPECCION DE TRANSPORTE
                        </th>
                        <th style="height: 30px;border-top: 0;border-right: 0;padding: 0;border-bottom: 0;">
                            Fecha de actualización
                            <p>
                                {{ \Carbon\Carbon::parse($receiptunit->entrydate)->format('d-m-Y') }}
                            </p>
                        </th>
                    </table>
                </div>
                <div>
                    <table class="border-none">
                        <th style="width: 40%;border-bottom: 0;text-align: center">
                            Procedimiento Referencia: PROCEDIMIENTO PARA EMBARQUE DE PRODUCTO TERMINADO
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>


    <div class="row" style="margin-top: 15px">
        <table class="border-none">
            <th style="border-top: 0;width: 20%;height: 15px;text-align: left;" class="border-none">
                Nombre del operador
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%"> {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}} </th>
            <th style="border-top: 0;width: 20%;text-align: right;" class="border-none">Fecha</th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%"> {{$receiptunit->entrydate}}</th>
        </table>
    </div>
    <div class="row" style="margin-top: 7px">
        <table class="border-none">
            <th style="border-top: 0;width: 20%;height: 15px;text-align: left;" class="border-none">
                Placas de tracto / Caja
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%">{{$receiptunit->boxnumber}}
                | {{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}</th>
            <th style="border-top: 0;width: 20%;text-align: right;" class="border-none">No. de Folio</th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%">{{$receiptunit->id}}</th>
        </table>
    </div>
    <div class="row" style="margin-top: 7px">
        <table class="border-none">
            <th style="border-top: 0;width: 20%;height: 15px;text-align: left;" class="border-none">
                Tipo de Unidad
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%">{{$receiptunit->truckcategory ? $receiptunit->truckcategory->description : ''}}</th>
            <th style="border-top: 0;width: 20%;text-align: right;" class="border-none">Año de caja
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%"></th>
        </table>
    </div>
    <div class="row" style="margin-top: 7px">
        <table class="border-none">
            <th style="border-top: 0;width: 20%;height: 15px;text-align: left;" class="border-none">
                Capacidad para la carga
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%">{{$receiptunit->truck ? $receiptunit->truck->name : ''}}</th>
            <th style="border-top: 0;width: 20%;text-align: right;" class="border-none">Año del
                tracto
            </th>
            <th style="border-top: 0;border-left: 0;border-right: 0;width: 30%"></th>
        </table>
    </div>

    <div class="row" style="margin-top: 20px;border: 1px solid;padding: 10px">
        <table class="border-none">
            <span>INSTRUCCIONES</span>
            <p>Coloar un circulo en el puntaje establecido a partir de los siguientes criterios.</p>
            <p>Aquellos que demanden percepción * serán medidos de acuerdo a la siguiente escala:</p>
            <p style="margin-left: 40px"> 0= No Cumplió 1=Deficiente 2=Insuficiente 3=Bueno 5=Satisfactorio</p>
            <p>Mientras que los criterios que tengan como respuesta no o si, el puntaje se mediará</p>
            <p style="margin-left: 40px"> 0 = NO 5 = SI</p>
            <p style="font-weight: bold">El mínimo requerido por criterio se encuentra identificado con negritas, de
                forma que, la suma de los puntajes minimos requeridos deberán dar la suma de 90 puntos para que la
                unidad pueda cargarse.</p>
        </table>
    </div>

    <div class="row" style="margin-top:20px">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: center;width: 35%">CRITERIOS</th>
                <th style="border: 1px solid;text-align: center">PUNTAJE</th>
                <th style="border: 1px solid;text-align: center;width: 35%">OBSERVACIONES</th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;background: none" colspan="3">CALIDAD DE LA UNIDAD</th>
            </tr>
        </table>
    </div>
    <div class="row">
        <table style="border-top: 0">
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">Libre de olores extraños.</th>
                @foreach($verified as $model)
                    @if($model->idcheck == '1')
                        @for($i = 0; $i <= 6; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
{{--                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>--}}
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">Libre de basura, vidrio y/o
                    material extraño (granos, trapos, botellas, grasa/aceite, telaraña, suciedad, residuos o cualquier
                    objeto ajeno a la carga)
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '2')
                        @for($i = 0; $i <= 6; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
{{--                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>--}}
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">Estado de las hendiduras en el
                    piso y paredes
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '3')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">¿Libre de filtraciones de luz u
                    hoyos con profundidad?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '4')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">¿Libre de plagas?</th>
                @foreach($verified as $model)
                    @if($model->idcheck == '5')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">¿Libre de humedad?</th>
                @foreach($verified as $model)
                    @if($model->idcheck == '6')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                    <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0;">¿Libre de puntos salientes,
                    astillas y punzantes que puedan perforar o dañar los empaques?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '7')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0;">¿Techo en condiciones óptimas
                    que no afecte al producto o amenace la carga? (pinturas o se descarapelamiento del material de la
                    unidad)
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '8')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">¿Caja resistente e impermeable a
                    la lluvia/ sin evidencia de goteras?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '9')
                        @for($i = 0; $i <= 5; $i++)
                            <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                {{ $i < 6 ? $i : '' }}
                            </th>
                        @endfor
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;background: none" colspan="8">SEGURIDAD DE LA UNIDAD
                </th>
            </tr>
        </table>
    </div>

    <div class="row">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuál es el porcentaje de vida de las llantas para la carga?
                </th>
                <th>
                    <table class="border-none" width="100%">
                        <tr>
                            @foreach($verified as $model)
                                @if($model->idcheck == '10')
                                    @for($i = 0; $i <= 5; $i++)
                                        <th style="border: 1px solid; text-align: center; width: 5%; border-top: 0; background-color: {{ $model->verified == "$i" ? 'red !important' : 'transparent' }}">
                                            {{ $i < 6 ? $i : '' }}
                                        </th>
                                    @endfor
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0;border-left: 0">
                                50%
                            </th>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0">
                                60%
                            </th>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0">
                                70%
                            </th>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0">
                                80%
                            </th>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0">
                                90%
                            </th>
                            <th style="border: 1px solid;text-align: center;width: 5%;border-top: 0;border-bottom: 0;border-right: 0">
                                100%
                            </th>
                        </tr>
                    </table>
                </th>
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
        </table>
    </div>

    <div class="row">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con luces (delanteras y reverseros) y parachoques funcionales?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '11')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0;border-right: 1px solid"></th>
            </tr>

            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con GPS instalado?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '12')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0;border-right: 1px solid"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con cinturón de seguridad?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '13')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0;border-right: 1px solid"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuentas con Gatas? (Opcional)
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '14')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0;border-right: 1px solid"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: center;background: none;border-bottom: 0" colspan="8">
                    DOCUMENTOS DEL OPERADOR
                </th>
            </tr>
        </table>
    </div>

    <div class="row">
        <table>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 1px solid">
                    ¿Cuenta con certificado de fumigación vigente con licencia sanitaria?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '15')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 1px solid"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Operador cuenta con licencia y vigente?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '16')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con el documento de Seguro de la Unidad?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '17')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con SUA (Sistema Único de Autodeterminación)?
                    Obligatorio para clientes: SAMS, Walmart, OXXO, ALSEA, PFS, Food Service, Tiendas EXTRA, SIGMA FOOD
                    SERVICE.
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '18')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con Tarjeta de circulación (Tractor/caja)?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '19')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
            <tr>
                <th style="border: 1px solid;text-align: left;width: 35%;border-top: 0">
                    ¿Cuenta con INE?
                </th>
                @foreach($verified as $model)
                    @if($model->idcheck == '20')
                        <th style="border: 1px solid;width:15%;background-color: {{ $model->verified == "0" ? 'red !important' : 'transparent' }}">0</th>
                        <th style="border: 1px solid;width: 15% ;background-color: {{ $model->verified == "5" ? 'red !important' : 'transparent' }}">5</th>
                    @endif
                @endforeach
                <th style="border: 1px solid;text-align: center;width: 35%;border-top: 0"></th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 30px">
        <table class="border-none">
            <tr class="border-none">
                <th style="text-align: left;width: 20%;" class="border-none"></th>
                <th style="border-bottom: 1px solid;" class="border-none"></th>
                <th style="width: 20%" class="border-none"></th>
            </tr>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 30px">
        <table>
            <tr>
                <th style="text-align: left;width: 20%;border: 1px solid" class="border-none">
                    <p><b>NOTA:</b> el puntaje minimo requerido es de <span
                            style="border-bottom: 2px solid;font-weight: bold">90 puntos.</span></p>
                    <p>De rechazarse la unidad por parte de Tráfico 4Level y aun asi <span
                            style="border-bottom: 2px solid">Personal Heinz</span> indica cargar la caja, deberá firmar
                        algún responsable y colocar la leyenda: <span style="font-weight: bold"><b>"Autorizo la carga de la unidad"</b></span>,
                        asumiendo
                        la responsabilidad de dicha carga.</p>
                    <p>4Level queda excento de responsabilidad en los criterios de seguridad mecánica, solo realizara
                        revisión visual.</p>
                </th>
        </table>
    </div>

    <div class="row border-none" style="margin-top: 30px;margin-bottom: 30px">
        <table class="border-none">
            <tr class="border-none">
                <th class="border-none">4l Responsable de Trafico</th>
                <th style="border-bottom: 1px solid;width: 25%" class="border-none"></th>
                <th class="border-none">4l Responsable de Heinz</th>
                <th class="border-none" style="border-bottom: 1px solid;width: 25%"></th>
        </table>
    </div>


</div>
</body>

</html>
