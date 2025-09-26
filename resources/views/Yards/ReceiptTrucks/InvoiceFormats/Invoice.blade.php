<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Folio de Entrada {{$receipttruck->id}}</title>
    <link rel="stylesheet" href="{{asset('/css/InvoiceDefault.css')}}">
</head>
<body>

<div class="container">

    <div class="row">
        <table cellpadding="20">
            <tr>
                <th style="vertical-align: center; text-align: center;">
                    <h3 class='formato'>FOLIO DE ENTRADA DE TRACTO</h3>
                </th>
            </tr>
        </table>
    </div>
    <br>
    <table>
        <tr>
            <td class="centro">{{$receipttruck->center ? $receipttruck->center->description : ''}}</td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="border-none">
                <table  border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w30" style="text-align: left"> Folio:</td>
                        <td style="text-align: left"><?php echo str_pad($receipttruck->id, 10, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <td class="datos w30"> Etiqueta:</td>
                        <td style="text-align: left">{{$receipttruck->barcode}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table align="center" border=0 cellspacing=0 cellpadding=0 class="border-none">
        <tr>
            <td class="border-none">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w30">N. Operador:</td>
                        <td class="pleft"> {{$receipttruck->operatorname ? $receipttruck->operatorname : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">Teléfono:</td>
                        <td class="pleft"> {{$receipttruck->phone}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30"> Licencia:</td>
                        <td class="pleft"> {{$receipttruck->drivernumber ? $receipttruck->drivernumber : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">Línea:</td>
                        <td class="pleft"> {{$receipttruck->carrier->name}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Fecha/Hora:</td>
                        <td class="pleft"> {{$receipttruck->entrydate}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Placa de Tracto:</td>
                        <td class="pleft">{{$receipttruck->trucknumber}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Eco.de Tracto:</td>
                        <td class="pleft"> {{ $receipttruck->econumber }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br><br>

    <table>
        <tr>
            <td class="border-none" style="text-align: left;">
                <p>Etiqueta : {{$receipttruck->barcode}}</p>
				{!! DNS2D::getBarcodeHTML($receipttruck->barcode, 'QRCODE',3,3) !!}
            </td>
        </tr>
    </table>

    <br><br>
    <table style="width: 100%;">
        <tr>
            <td class="border-none" style="text-align: center; width: 50%;">
                <hr>
                Firma Vigilancia
            </td>
            <td class="border-none" style="text-align: center; width: 50%;">
                <hr>
                Firma Operador
            </td>
        </tr>
    </table>
    <br>

    <h5 class='nota'>Este documento le será solicitado al momento de su salida, de no contar con él la salida le será
        negada</h5>

</div>

</body>
</html>

