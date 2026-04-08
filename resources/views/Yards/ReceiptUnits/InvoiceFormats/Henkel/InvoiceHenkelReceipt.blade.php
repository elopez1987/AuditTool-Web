 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Folio de Unidad de Recibo</title>
    <style>
        .container {
            padding: 0;
            border: 0;
            width: 700px;
            height: 612px;
        }

        html {
            margin: 0;
            font-weight: 400;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 10mm 10mm 10mm 10mm;
            font-size: 11px !important;
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
    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <table>
            <th style="width: 120px;text-align: center">
                <img src="{{ public_path('img/henkel.png') }}" alt="" class="logo" style="width: 100px;height: 60px;">
            </th>
            <th>
                <div class="row">
                    <table class="border-none">
                        <th
                            style="height:25px;border-top: 0;border-left: 0;border-right: 0;
                        align-content: center;text-align: center;width: 80%;padding: 0;border-bottom: 0;font-size: 18px;">
                            CHECK LIST DE TRANSPORTE PARA DESCARGA
                        </th>
                        <th style="height: 25px;border-top: 0;border-right: 0;padding: 0;border-bottom: 0;">
                            Codigo: <br>

                        </th>
                    </table>
                </div>
                <div>
                    <table class="border-none">
                        <th
                            style="width: 40%;border-bottom: 0;border-left: 0;text-align: center;height: 30px;font-size: 14px;">
                            CORTINA ASIGNADA
                        </th>
                        <th style="width: 40%;border-bottom: 0;font-size: 18px;color: red">
                            {{$receiptunit->location ? $receiptunit->location->name : ''}}
                        </th>
                        <th style="width:20%;border-bottom: 0;border-right: 0;font-size: 12px;">
                            "Versión: 1"
                        </th>
                    </table>
                </div>
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="height: 10px;width: 10%;border-top: 0;text-align: right;font-size: 18px;">Folio:</th>
            <th style="width: 15%;border-top: 0;font-size: 18px !important;color: red">{{$folio}}</th>
            <th style="width: 50%;border-top: 0;text-align: center;font-size: 14px;"> CENTRO DE DISTRIBUCION
                HENKEL ONE ROOF
            </th>
            <th style="height: 10px;width: 10%;border-top: 0;text-align: right;font-size: 18px;">YardTrax:</th>
            <th style="width: 15%;border-top: 0;font-size: 18px !important;color: red">{{$receiptunit->id}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="height: 30px;width: 13.9%;border-top: 1px solid black;text-align: right;font-size: 12px;background-color: #E8EAF6;">
                Código de barra:
            </th>
            <th style="width: 25%;border-top: 0;font-size: 16px !important;">{{$receiptunit->barcode}}</th>
            <th style="width: 25%;border-top: 1px solid black;text-align: center;font-size: 12px;background-color: #E8EAF6;">
                Embarque :
            </th>
            <th style="width: 25%;border-top: 0;text-align: center;font-size: 16px !important;"> {{$receiptunit->shipmentid}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="height: 30px;width: 13.9%;border-top: 0;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Operador:
            </th>
            <th style="width: 65%;border-top: 0;font-size: 16px;">{{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}</th>
            <th style="width: 10%;border-top: 0;text-align: center;font-size: 12px;"> Pagina: 1/1</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Fecha de
                Revisión:
            </th>
            <th style="width: 40%;border-top: 0;font-size: 10px;">{{$receiptunit->entrydate}}</th>
            <th style="width: 20%;border-top: 1px solid black;font-size: 10px;background-color: #E8EAF6;">No.Placas
                Tracto
            </th>
            <th style="width: 20%;border-top: 0;text-align: center;font-size: 10px;">{{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Linea de
                transporte:
            </th>
            <th style="width: 40%;border-top: 0;font-size: 10px;">{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}</th>
            <th style="width: 20%;border-top: 1px solid black;font-size: 10px;background-color: #E8EAF6;">No. Placas
                Caja
            </th>
            <th style="width: 20%;border-top: 0;text-align: center;font-size: 10px;">{{$receiptunit->boxnumber}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="height: 20px;width: 14.8%;border-top: 0;text-align: right;font-size: 10px;background-color: #E8EAF6;">
                Licencia:
            </th>
            <th style="width: 40%;border-top: 0;font-size: 10px;">{{$receiptunit->tracto ? $receiptunit->tracto->drivernumber : ''}}</th>
            <th style="width: 20%;border-top: 0;font-size: 10px;background-color: #E8EAF6;"> Tipo de Vehiculo
            </th>
            <th style="width: 20%;border-top: 0;text-align: center;font-size: 10px;">{{$receiptunit->truck ? $receiptunit->truck->name : ''}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 1px solid black;background-color: #E8EAF6;text-align: center;font-size: 14px;">
                Inspección del
                Vehiculo
            </th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;font-size: 11px;height: 25px;">Concepto Obligatorio
                por cumplir, se rechazara la unidad.
            </th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 10%;font-size: 11px;">¿CUMPLE?</th>
            <th style="border-top: 0;background-color: #E8EAF6;text-align: center;width: 40%;font-size: 11px;">Observaciones</th>
        </table>
    </div>
    @foreach($verified as $detail)
    <div class="row">
        <table class="border-none">
            <th style="width: 40%;background-color: #E8EAF6;border-top: 0;height: 20px;font-size: 11px;">{{$detail->description}}</th>
            <th style="border-top: 0;text-align: center;width: 10%;">{{$detail->verified}}</th>
            <th style="border-top: 0;"></th>
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
            <th
                style="border-top: 0;text-align: center;width: 20%;height: 20px;font-size: 11px;background-color: #E8EAF6">
                Dictamen
            </th>
            <th style="border-top: 0;text-align: center;width: 15%;font-size: 11px;">Aceptado</th>
            <th style="border-top: 0;text-align: center;width: 15%;font-size: 11px;"></th>
            <th style="border-top: 0;text-align: center;width: 15%;font-size: 11px;">Rechazado</th>
            <th style="border-top: 0;text-align: center;width: 15%;"></th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th style="border-top: 0;text-align: center;font-size: 11px;background-color: #E8EAF6">
                Observaciones:
            </th>
            <th style="border-top: 0;text-align: center;width: 75%;height: 20px;">{{$receiptunit->auxiliary02}}</th>
        </table>
    </div>
    <div class="row">
        <table class="border-none">
            <th
                style="border-top: 0;text-align: center;width: 25%;height: 20px;font-size: 11px;background-color: #E8EAF6">
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
                style="border-top: 0;text-align: center;width: 25%;height: 40px;vertical-align:bottom;font-size: 11px;background-color: #E8EAF6">
                Firma
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;">
                {{$receiptunit->user ? $receiptunit->user->name : ''}}
            </th>
            <th style="border-top: 0;text-align: center;width: 25%;"></th>
            <th style="border-top: 0;text-align: center;">
                {{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}
            </th>
        </table>
    </div>
    <div class="row">
        <div style="text-align: center;font-size: 9px;margin-top: 6px;">
                <span style="text-align: center;">Este documento es propiedad de Henkel Capital S.A de C.V., queda
                    prohibida su reproducción total o parcial.</span>
            <br>
        </div>
        <br><br><br><br>

    </div>
</div>

</body>

</html>
