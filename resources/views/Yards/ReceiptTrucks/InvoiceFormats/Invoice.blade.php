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
            <th style="height: 60px;width: 33.3%">
                <img src="{{asset('/img/logo.png')}}" alt="" class="logo">
            </th>
            <th style="vertical-align: center;width: 33.3%">
                <h3 class='formato'>FOLIO DE ENTRADA</h3>
            </th>
            <th style="width: 33.3%">
                <p style="text-align: center;margin: auto">
                    <?php
                    $folio = strval($receipttruck->id);
                    echo DNS1D::getBarcodeHTML($folio, 'C128', 2, 50, 'black');
                    ?>
                    Folio : {{$receipttruck->id}}
            </th>
        </table>
    </div>
    <br>
    <table>
        <td class="centro">DISTRIBUCIÓN Y ALMACENAJE AVIOR SA DE CV</td>
    </table>
    <table>
        <tr>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w20"> Folio</td>
                        <td><?php echo str_pad($receipttruck->id, 10, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <td class="datos w20"> Etiqueta</td>
                        <td>
                            {{$receipttruck->barcode}}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border-none" style="width: 50%">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w20"> Destino</td>

                    </tr>
                    <tr>
                        <td class="datos w20"> Evento</td>
                        <td>

                        </td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
    <br>
    <table align="center" border=0 cellspacing=0 cellpadding=0 class="border-none">
        <tr>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w30"> Licencia</td>
                        <td class="pleft"> {{$receipttruck->drivernumber ? $receipttruck->drivernumber : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">N. Operador</td>
                        <td class="pleft"> {{$receipttruck->operatorname ? $receipttruck->operatorname : ''}} </td>
                    </tr>
                    <tr>
                        <td class="datos w30">Teléfono</td>
                        <td class="pleft"> {{$receipttruck->phone}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Linea</td>
                        <td class="pleft"> {{$receipttruck->carrier->name}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Fecha/Hora</td>
                        <td class="pleft"> {{$receipttruck->entrydate}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">N.Caja</td>
                        <td class="pleft">{{$receipttruck->econumber}}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">N.Tracto</td>
                        <td class="pleft"> {{ $receipttruck->trucknumber }}</td>
                    </tr>
                    <tr>
                        <td class="datos w30">Longitud</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Estatus</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Embarque</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Sellos</td>
                        <td class="pleft"></td>
                    </tr>
                </table>
            </td>
            <td class="border-none" style="width: 50%;">
                <table align="center" border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class="datos w30">Licencia</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">N. Operador</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Teléfono</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Fecha/Hora</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">N.Tracto</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Estatus</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Embarque</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Sellos</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Comentario 01</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Comentario 02</td>
                        <td class="pleft"></td>
                    </tr>
                    <tr>
                        <td class="datos w30">Comentario 03</td>
                        <td class="pleft"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br><br><br>

    <table>
        <td class="border-none">
            Etiqueta : {{$receipttruck->barcode}}
            <?php
            echo DNS1D::getBarcodeHTML($receipttruck->barcode, 'C128', 2, 50, 'black');
            ?>
        </td>
    </table>


    <br><br><br>
    <table>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Vigilancia
        </td>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Operador
        </td>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Analista
        </td>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Vigilancia
        </td>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Operador
        </td>
        <td class="border-none" style="text-align: center;">
            <hr>
            Firma Analista
        </td>
    </table>
    <br>

    <h5 class='nota'>Este documento le sera solicitado al momento de su salida, de no contar con el la salida le sera
        negada</h5>

</div>

</body>
</html>

