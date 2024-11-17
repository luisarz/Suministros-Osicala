<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header, .footer {
            width: 100%;
            text-align: center;
        }

        .header img {
            width: 100px;
        }

        .empresa-info, .documento-info, .tabla-productos, .resumen {
            margin: 10px 0;
        }

        .tabla-productos th, .tabla-productos td {
            padding: 5px;
            text-align: center;
        }

        .tabla-productos th {
            background-color: #f2f2f2;
        }

        .resumen {
            margin-top: 20px;
            width: 100%;
            text-align: right;
        }

        .qr {
            text-align: center;
            margin-top: 10px;
        }

        .p {
            font-size: 10px;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;

        }
    </style>
</head>
<body>
<!-- Header Empresa -->
<div class="header">


    <table style="width: 100%">
        <tr>
            <td style="width: 40%; ">
                <table style="text-align: left; border: black solid 1px; border-radius: 10px; row-span: inherit">
                    <tr>
                        <td style="width: 5%">
                            <img src="{{ public_path($datos["logo"]??'') }}" alt="Logo Empresa">
                        </td>
                        <td style="width: 95%"><h3>{{ $datos["empresa"]['nombre'] }}</h3>
                            <p style=" font-family: Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 12px; line-height: 1;">
                                NIT: {{ $datos["empresa"]['nit'] }} <br>
                                NRC: {{ $datos["empresa"]['nrc'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 12px;">
                            {{ $datos["empresa"]['descActividad'] }}<br>
                            {{ $datos["empresa"]["direccion"]["complemento"] }}<br>
                            Teléfono: {{ $datos["empresa"]['telefono'] }}<br>

                        </td>
                    </tr>

                </table>
            </td>
            <td style="width: 55%; text-align: left; border: black solid 1px; border-radius: 10px; font-size: 11px;">
                <div style="text-align: center;">
                    <h3>DOCUMENTO TRIBUTARIO ELECTRÓNICO</h3>


                    <h3>{{ $datos["tipoDocumento"] }}</h3>
                </div>

                <table>
                    <tr>
                        <td>Código generación:</td>
                        <td>{{ $datos["DTE"]['respuestaHacienda']['codigoGeneracion'] }}</td>
                    </tr>
                    <tr>
                        <td>Sello de recepción:</td>

                        <td>{{ $datos["DTE"]['respuestaHacienda']['selloRecibido'] }}</td>
                    </tr>
                    <tr>
                        <td>Número de control:</td>

                        <td>{{ $datos["DTE"]['identificacion']['numeroControl'] }}</td>
                    </tr>
                    <tr>
                        <td>Fecha emisión:</td>
                        <td>{{ date('d-m-Y',strtotime($datos["DTE"]['identificacion']['fecEmi'])) }}</td>
                    </tr>
                    <tr>
                        <td>Hora emisión:</td>
                        <td>{{ $datos["DTE"]['identificacion']['horEmi'] }}</td>
                    </tr>

                </table>
            </td>
        </tr>


    </table>

</div>

<!-- Info Documento -->

<!-- Info Cliente -->
<div class="cliente-info">
    <table>
        <tr>

            <td>
                <p>Razón Social: {{ $datos["DTE"]['receptor']['nombre'] }}<br>
                    Documento: {{ $datos["DTE"]['receptor']['numDocumento'] }}<br>
                    Actividad: {{ $datos["DTE"]['receptor']['codActividad'] }}
                    - {{  $datos["DTE"]['receptor']['descActividad'] }}
                    <br>
                    Dirección: {{ $datos["DTE"]['receptor']['direccion']['complemento'] }}<br>
                    Teléfono: {{ $datos["DTE"]['receptor']['telefono'] }} |
                    Correo: {{  $datos["DTE"]['receptor']['correo'] }}</p>


            </td>
            <td style="align-items: end;">
                <img src="{{ public_path($qr ) }}" alt="Logo Empresa" width="100px">
            </td>
        </tr>
    </table>

</div>

<!-- Tabla Productos -->
<table class="tabla-productos" width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>No</th>
        <th>Cant</th>
        <th>Unidad</th>
        <th>Descripción</th>
        <th>Precio Unitario</th>
        <th>Desc Item</th>
        <th>Ventas No Sujetas</th>
        <th>Ventas Exentas</th>
        <th>Ventas Gravadas</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($datos["DTE"]['cuerpo'] as $item)
        <tr>
            <td>{{ $item['numItem'] }}</td>
            <td>{{ $item['cantidad'] }}</td>
            <td>{{ $item['uniMedida'] }}</td>
            <td>{{ $item['descripcion'] }}</td>
            <td>${{ number_format($item['precioUni'], 2) }}</td>
            <td>${{ number_format($item['montoDescu'], 2) }}</td>
            <td>${{ number_format($item['ventaNoSuj'], 2) }}</td>
            <td>${{ number_format($item['ventaExenta'], 2) }}</td>
            <td>${{ number_format($item['ventaGravada'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Resumen -->
<div class="resumen">
    <p>Subtotal: ${{ number_format($datos["DTE"]['resumen']['totalGravada'], 2) }}</p>
    <p>Total No Sujeto: ${{ number_format($datos["DTE"]['resumen']['totalNoSuj'], 2) }}</p>
    <p>Total Exento: ${{ number_format($datos["DTE"]['resumen']['totalExenta'], 2) }}</p>
    <p>Total Gravadas: ${{ number_format($datos["DTE"]['resumen']['totalGravada'], 2) }}</p>
    <p>Total a Pagar: ${{ number_format($datos["DTE"]['resumen']['totalPagar'], 2) }}</p>
    <p>Total en Letras: {{ $datos["DTE"]['resumen']['totalLetras'] }}</p>
</div>

<!-- QR Code -->
<div class="qr">
    {{--    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($urlConsulta)) }}" alt="QR Code">--}}
</div>
</body>
</html>
