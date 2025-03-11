<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento Tributario Electrónico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10.5px;

        }

        .header {
            text-align: center;

        }


        .header img {
            width: 100px;
        }


        .tabla-productos th, .tabla-productos td {
            padding: 2px;
        }


        .resumen p {
            margin: 5px 0;
            text-align: right;
        }

        table {
            width: 100%;
            /*border-collapse: collapse;*/
        }
        tr{
            padding:10px;
            /*border: 1px solid black;*/
        }
        td{
            padding:4px;
            /*border: 1px solid black;*/
        }


        @page {
            margin: 5mm 0mm 0mm 15mm; /* Márgenes: arriba, derecha, abajo, izquierda */
        }


    </style>
</head>
<body>

<!-- Header Empresa -->
<div class="header">


    <table style="width: 100%; padding: 0; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; text-align: left; vertical-align: middle; padding-right: 10px;">

                <img src="{{ asset($logoPath) }}" alt="Logo de la empresa" style="width: 150px; height: auto;">

            </td>

            <td style="width: 50%; text-align: left; vertical-align: middle; padding-left: 10px;">
                <h3>{{$empresa->name}}  {{$datos->whereHouse->name}}</h3></p>
                <p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>Orden de trabajo # <b>{{$datos->order_number}}</b></h4>
            </td>
        </tr>
    </table>
    ---------------------------------------------------------------------------

    <table width="100%" style="border: 0px solid black; border-collapse: collapse;padding: 10px !important;">
        <tbody>
            <tr>
                <td><b>FECHA</b></td>
                <td>{{date('d-m-Y H:s:i',strtotime($datos->created_at))}}</td>
            </tr>

            <tr>
                <td><b>Estado</b></td>
                <td>{{$datos->sale_status??''}}, {{$datos->customer->address??''}}</td>
            </tr>
            <tr>
                <td><b>Cliente</b></td>
                <td>{{$datos->customer->name??''}} {{$datos->customer->last_name??''}}</td>
            </tr>
            <tr>
                <td><b>Teléfono</b></td>
                <td>{{$datos->customer->phone??''}}</td>
            </tr>
            <tr>
                <td><b>Dirección</b></td>
                <td>{{$datos->customer->address??''}}</td>
            </tr>
            <tr>
                <td><b>Vendedor</b></td>
                <td>{{$datos->seller->name??''}} {{$datos->seller->last_name??''}}</td>
            </tr>
            <tr>
                <td><b>Mecánico</b></td>
                <td>{{$datos->mechanic->name??'S/N'}} {{$datos->mechanic->lastname??''}}</td>
            </tr>
        </tbody>
    </table>

    ---------------------------------------------------------------------------
    <table width="100%" style="border: 0px solid black; border-collapse: collapse;">

        <tbody>
        @foreach ($datos->saleDetails as $item)
            @php($inventory = $item)
            <tr>
                <td>{{ $item->quantity }}</td>
                <td colspan="3  ">{{$item->inventory->product->name ?? '' }}</td>

            </tr>
            <tr>
                <td></td>
                <td colspan="3">
                    @if(!empty($item->inventory->product->sku))
                        <b> SKU {{ $item->inventory->product->sku }}</b>
                    @endif
                    @if(!empty($item->description))
                        <br/>
                        <b>DESCRIPCIÓN:</b> <br>
                        {{ $item->description ?? '' }}
                    @endif

                </td>
            </tr>
            <tr>
                <td></td>
                <td>${{ number_format($item->price??0, 2) }}</td>
                <td>Desc. ${{ number_format($item->discount, 2) }}</td>
                <td style="text-align: right">${{ number_format($item->total??0, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p>
        ---------------------------------------------------------------------------
    </p>

</div>


<!-- Footer fijo -->
<div >

    <table>
        <tr>
            <td style="width: 100%">
                <table style="width: 100%">
                    <tr>
                        <td colspan="2"><b>VALOR EN LETRAS:</b> {{ $montoLetras ??''}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background-color: #57595B; color: white;  text-align: center;">
                            EXTENSIÓN-INFORMACIÓN ADICIONAL
                        </td>
                    </tr>
                    <tr>
                        <td>Entregado por:_____________________</td>
                        <td>Recibido por:_____________________</td>
                    </tr>
                    <tr>
                        <td>N° Documento:____________________</td>
                        <td>N° Documento:____________________</td>
                    </tr>
                    <tr>
                        <td>Condicion Operación:____________________</td>
                        <td>{{$datos["DTE"]['resumen']['condicionOperacion']??''}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Observaciones:</td>
                    </tr>
                </table>
            </td>
            <td style="width: 10%">Total Operaciones:
                <table style="width: 100%">
                    <tr>
                        <td>Total No Sujeto:</td>
                        <td>${{ number_format(0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Exento:</td>
                        <td>${{ number_format(0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Gravadas:</td>
                        <td>${{ number_format($datos->sale_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Subtotal:</td>
                        <td>${{ number_format($datos->sale_total, 2) }}</td>
                    </tr>

                    <tr style="background-color: #57595B; color: white;">
                        <td>
                            <b>TOTAL A PAGAR:</b></td>
                        <td> ${{number_format($datos->sale_total, 2)}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


</div>
</body>
</html>
