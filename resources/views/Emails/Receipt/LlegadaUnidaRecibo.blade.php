<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de unidad</title>
</head>
<body style="margin:0; padding:0; background-color:#e6e7e8; font-family: Arial, sans-serif;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#e6e7e8; padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:4px; overflow:hidden;">
                <tr>
                    <td align="center" style="background-color:#1e2545; padding:20px;">
                        <h2 style="color:#ffffff; margin:0; font-size:20px;">
                            REGISTRO DE UNIDAD
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 40px; color:#333333; font-size:14px;">
                        <hr style="border: 0; border-top: 1px solid #dcdcdc; margin:20px 0;">
                        <table width="100%" style="font-size: 14px; color:#333;">
                            <tr>
                                <td style="padding: 6px 0;">
                                    <strong>Bodega:</strong> {{$receipt->warehouse ? $receipt->warehouse->name : ''}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0;"><strong>Número de embarque:</strong> {{$receipt->shipmentid}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0;"><strong>Línea de transporte:</strong> {{$receipt->carrier ? $receipt->carrier->name : ''}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0;"><strong>Fecha y hora de registro:</strong> {{$receipt->entrydate}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0;"><strong>Placa de caja:</strong> {{$receipt->boxnumber}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0;"><strong>Eco. de caja:</strong> {{$receipt->econumber }}</td>
                            </tr>
                        </table>

                        <hr style="border: 0; border-top: 1px solid #dcdcdc; margin:20px 0;">
                    </td>
                </tr>
                <tr>
                    <td align="center" style="background-color:#1e2545; padding:15px;">
                        <p style="color:#ffffff; font-size:12px; margin:0;">
                            Este mensaje se generó automáticamente. No responda a este correo.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
