<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Folio de Unidad</title>
    <style>
        .container {
            padding: 0px;
            border: 0px;
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
            font-size: 9px;
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

        .table-bordered {
            border: 1px solid #212121;
        }

        .border-none {
            border: 0px;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="row">
        <table>
            <th style="width: 120px;">
                @if(!empty($logo->auxiliary09))
                    <img src="{{ storage_path('app/' . $logo->auxiliary09) }}"
                         class="align-self-center img-responsive" alt="" style="height: 70px;width: 280px">
                @endif
            </th>
            <th>
                <div class="row">
                    <table class="border-none">
                        <th
                            style="height:15px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 80%;padding: 0;border-bottom: 0;font-size: 18px;">
                            CHECK LIST DE TRANSPORTE
                        </th>
                        <th style="height: 20px;border-top: 0;border-right: 0;padding: 0;border-bottom: 0;">
                            Codigo: <br>
                        </th>
                    </table>
                </div>
                <div>
                    <table class="border-none">
                        <th
                            style="width: 40%;border-left: 0;text-align: center;height: 30px;font-size: 14px;">
                            Evento
                        </th>
                        <th style="width: 60%;;font-size: 14px;color: blue">
                            {{$receiptunit->event ? $receiptunit->event->name : ''}}
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="height: 30px;width: 14.8%;border-top: 0px;text-align: right;font-size: 12px;background-color: #E8EAF6;">
                Folio:
            </th>
            <th style="width: 20%;border-top: 0px;font-size: 16px;color: red">{{$receiptunit->id}}</th>
            <th style="width: 70%;border-top: 0px;text-align: center;font-size: 18px;"> {{$receiptunit->center ? $receiptunit->center->description : ''}}</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="height: 30px;width: 13.9%;border-top: 0px;text-align: right;font-size: 12px;background-color: #E8EAF6;">
                No. de
                Etiqueta:
            </th>
            <th style="width: 25%;border-top: 0px;font-size: 16px;color: red">  {{$receiptunit->barcode}} </th>
            <th style="width: 25%;border-top: 0px;text-align: center;font-size: 12px;background-color: #E8EAF6;">
                Almacén:
            </th>
            <th style="width: 25%;border-top: 0px;text-align: center;font-size: 12px;color: red"> {{$receiptunit->warehouse ? $receiptunit->warehouse->name : ''}}</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="height: 30px;width: 14.8%;border-top: 0px;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Operador:
            </th>
            <th style="width: 85.2%;border-top: 0px;font-size: 12px;"> {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0px;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Fecha de
                Revisión:
            </th>
            <th style="width: 40%;border-top: 0px;font-size: 10px;">{{$receiptunit->entrydate}} </th>
            <th style="width: 20%;border-top: 0px;font-size: 10px;background-color: #E8EAF6;">No.Placas Tracto</th>
            <th style="width: 20%;border-top: 0px;text-align: center;font-size: 10px;"> {{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}
                / {{$receiptunit->tracto ? $receiptunit->tracto->econumber : ''}}</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0px;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Linea de
                transporte:
            </th>
            <th style="width: 40%;border-top: 0px;font-size: 10px;">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}} </th>
            <th style="width: 20%;border-top: 0px;font-size: 10px;background-color: #E8EAF6;">No. Placas Caja</th>
            <th style="width: 20%;border-top: 0px;text-align: center;font-size: 10px;"> {{$receiptunit->boxnumber}}
                / {{$receiptunit->econumber}}</th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0px;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Licencia:
            </th>
            <th style="width: 40%;border-top: 0px;font-size: 10px;">{{$receiptunit->tracto ? $receiptunit->tracto->drivernumber : ''}} </th>
            <th style="width: 20%;border-top: 0px;font-size: 10px;background-color: #E8EAF6;"> Tipo de Vehiculo</th>
            <th style="width: 20%;border-top: 0px;text-align: center;font-size: 10px;">
                {{$receiptunit->truck ? $receiptunit->truck->name : ''}}
            </th>
        </table>
    </div>


    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;font-size: 14px;">Inspección del
                Vehiculo
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;font-size: 10px;height: 20px;">
                Concepto Obligatorio
                por cumplir, se rechazara la unidad.
            </th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;font-size: 11px;">
                ¿CUMPLE?
            </th>
        </table>
    </div>

    @foreach($verified as $detail)
        <div class="row">
            <table class="border-none">
                <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 15px;font-size: 9px;">{{$detail->description}}</th>
                <th style="border-top: 0;text-align: center;width: 10%;">{{$detail->verified}}</th>
            </table>
        </div>
    @endforeach

    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;height: 20px;font-size: 13px;">
                Observaciones
                Generales
            </th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="margin-left: 50px">
                <div style="margin-left: 80px;">
                    <span style="margin-bottom: 30px;">Código: {{$receiptunit->barcode}}</span> <br>
                    {!! DNS2D::getBarcodeHTML($receiptunit->barcode, 'QRCODE',3,3) !!}
                </div>
            </th>
            <th style="height: 120px;border-top: 0;width: 33%">
                <span>
                    No. De Sellos de Seguridad <br><br>
                    {{$receiptunit->containerseal}}
                </span><br>
            </th>
            <th style="height: 120px;border-top: 0;width: 33%">
                <span>Calzas de Seguridad</span><br>
            </th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;text-align: left;font-size: 11px;background-color: #E8EAF6">
                Observaciones:
            </th>
            <th style="border-top: 0;text-align: center;width: 75%;height: 20px;">
                @if(!empty($comment))
                    {{$comment->comment}}
                @endif
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="border-top: 0;text-align: center;width: 25%;text-align: left;height: 20px;font-size: 11px;background-color: #E8EAF6">
                Revisado por:
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;font-size: 10px;background-color: #E8EAF6">Nombre
                y firma Guardia
                (Entrada)
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;font-size: 10px;background-color: #E8EAF6">Nombre
                y firma de analista de
                trafico (Salida)
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;font-size: 10px;background-color: #E8EAF6">Nombre
                y firma del operador
                (Entrada)
            </th>
        </table>
    </div>

    <div class="row">
        <table class="border-none">
            <th
                style="border-top: 0;text-align: center;width: 25%;height: 40px;text-align: left;vertical-align:bottom;font-size: 11px;background-color: #E8EAF6">
                Firma
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;">
                {{$receiptunit->user ? $receiptunit->user->name : ''}}
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;">

            </th>
            <th style="border-top: 0;text-align: center;">
                {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}
            </th>
        </table>
    </div>

    @if(!empty($firma))
        <div class="row">
            <table class="border-none">
                <th class="border-none"></th>
                <th style="border-top: 0;text-align: center;width: 25%;" class="border-none">
                    <img src="{{storage_path('app/' . $firma->keyguard01 )}}" alt="Firma" style="height: 70px;max-height: 70px"/>
                </th>
                <th style="border-top: 0;text-align: center;width: 25%;" class="border-none">

                </th>
                <th style="border-top: 0;text-align: center;" class="border-none">
                    <img src="{{storage_path('app/' . $firma->keyoperator01)}}" alt="Firma" style="height: 70px;max-height: 70px"/>
                </th>
            </table>
        </div>
    @endif


</div>


</body>

</html>
