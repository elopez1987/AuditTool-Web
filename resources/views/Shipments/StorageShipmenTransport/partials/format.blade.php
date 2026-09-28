<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DETALLE DE ACCESO PARA EMBARQUES</title>
    <style>
        html {
            margin: 0;
        }

        body {
            margin: 10mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .container {
            width: 690px;
            padding: 0;
            border: 0;
        }

        .row {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #212121;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        th:first-child,
        td:first-child {
            text-align: left;
        }

        .header-table th {
            border: 0;
        }

        .header-logo {
            width: 150px;
            height: 65px;
            object-fit: contain;
        }

        .header-title {
            padding-top: 10px;
        }

        .header-info {
            font-size: 9px;
            text-align: left !important;
        }

        .section-title {
            margin: 20px 0 8px;
            font-size: 11px;
        }

        .label {
            width: 20%;
            padding: 2px;
            background-color: #eee;
            text-align: left;
        }

        .value {
            width: 30%;
            padding: 2px;
            background-color: #fff;
            text-align: left;
        }

        .value-highlight {
            font-size: 16px;
            color: red;
        }

        .vehicle-plate {
            font-size: 14px;
            color: blue;
        }

        .access-code {
            width: 50%;
            margin-top: 50px;
        }

        .access-code-title {
            color: darkblue;
            font-size: 18px;
            text-align: center;
        }

        .qr-container {
            padding: 2px 10px;
            text-align: center;
        }

        .qr {
            display: inline-block;
        }

        .parks-code {
            margin-top: 100px;
        }

        .parks-code-title {
            color: darkblue;
            font-size: 18px;
            text-align: center;
        }

        .parks-code-description {
            color: #000;
            font-size: 12px;
        }

        .transport-images th {
            width: 50%;
        }

        .transport-image {
            max-width: 300px;
            height: 200px;
            margin: 5px;
            object-fit: contain;
        }

        .note {
            margin-top: 50px;
            color: red;
            font-size: 11px;
            font-style: italic;
            font-weight: 400;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Encabezado --}}
    <div class="row">
        <table class="header-table">
            <tr>
                <th style="width: 33%;">
                    <img
                            src="{{ storage_path('app/' . $data->center->auxiliary04) }}"
                            alt="Logo"
                            class="header-logo"
                    >
                </th>

                <th style="width: 33%;">
                    <h3 class="header-title">
                        CONTROL DE ACCESO
                        <br>
                        EMBARQUES
                    </h3>
                </th>

                <th class="header-info">
                    <span>Formato :</span><br>
                    <span>Código :</span><br>
                    <span>Nivel de Revisión :</span><br>
                    <span>Fecha de Aplicación :</span><br>
                    <span>Páginas : 1 de 1</span>
                </th>
            </tr>
        </table>
    </div>

    {{-- Datos generales --}}
    <div class="section-title">
        DATOS GENERALES.
    </div>

    <table>
        <tbody>

        <tr>
            <th class="label">OPERADOR:</th>
            <td class="value">
                {{ $data->operatorname ?? '' }}
            </td>

            <th class="label">TELÉFONO</th>
            <td class="value">
                {{ $data->phone ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">LICENCIA</th>
            <td class="value" colspan="3">
                {{ $data->drivernumber ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">EMBARQUE</th>
            <td class="value value-highlight">
                {{ $data->order?->shipmentid ?? '' }}
            </td>

            <th class="label">N. CONFIRMACIÓN</th>
            <td class="value">
                {{ $data->order?->confirmation ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">CLIENTE / DESTINO</th>
            <td class="value value-highlight" colspan="3">
                {{ $data->order?->customer?->name ?? '' }}
                /
                {{ $data->order?->customer?->city ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">LÍNEA</th>
            <td class="value" colspan="3">
                {{ $data->carrier?->name ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">PLACA DE CAJA</th>
            <td class="value vehicle-plate">
                {{ $data->boxnumber ?? '' }}
            </td>

            <th class="label">PLACA DE TRACTO</th>
            <td class="value vehicle-plate">
                {{ $data->trucknumber ?? '' }}
            </td>
        </tr>

        <tr>
            <th class="label">F. DE CARGA</th>
            <td class="value">
                {{ $data->order?->loadingdate ?? '' }}
            </td>

            <th class="label">F. DE ENTREGA</th>
            <td class="value">
                {{ $data->order?->downloaddate ?? '' }}
            </td>
        </tr>

        </tbody>
    </table>

    {{-- Código de acceso --}}
    <div class="row">
        <table class="access-code">
            <tbody>
            <tr>
                <th class="access-code-title">
                    CÓDIGO DE ACCESO
                </th>
            </tr>

            <tr>
                <td class="qr-container">
                    <div class="qr">
                        {!! DNS2D::getBarcodeHTML($data->auxiliary05, 'QRCODE', 3, 3) !!}
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- Código de acceso para Parks --}}
    <div class="row parks-code">
        <table>
            <tbody>
            <tr>
                <th class="parks-code-title">
                    CÓDIGO DE ACCESO PARA PARKS
                    <br>

                    <span class="parks-code-description">
                        Favor de presentar este código QR en la entrada principal del parque.
                    </span>
                </th>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- Información del transporte --}}

    @php
        $carrierImage = $data->carrier->auxiliary04;
        $driverImage = $data->carrier->auxiliary05;

        $carrierImagePath = $carrierImage
            ? (str_starts_with($carrierImage, 'public/')
                ? storage_path('app/' . $carrierImage)
                : storage_path('app/public/' . $carrierImage))
            : null;

        $driverImagePath = $driverImage
            ? storage_path('app/' . $driverImage)
            : null;
    @endphp

    <div class="row">
        <table class="transport-images">
            <tbody>
            <tr>
                <th>
                    @if ($carrierImagePath && file_exists($carrierImagePath))
                        <img
                                src="{{ $carrierImagePath }}"
                                alt="Imagen del transporte"
                                class="transport-image"
                        >
                    @endif
                </th>

                <th>
                    @if ($driverImagePath && file_exists($driverImagePath))
                        <img
                                src="{{ $driverImagePath }}"
                                alt="Imagen del operador"
                                class="transport-image"
                        >
                    @endif
                </th>
            </tr>
            </tbody>
        </table>
    </div>


    {{-- Aviso --}}
    <h5 class="note">
        La información registrada en este documento es responsabilidad
        de la línea de transporte y quien la comparta.
    </h5>

</div>

</body>

</html>
