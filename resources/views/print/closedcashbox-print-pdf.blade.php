<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Corte de Caja</title>
    <style>
        body, html {
            margin: 0; padding: 15;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11px;
            color: #1F2937;
            background: #f5f6fa;
        }

        .container {
            padding: 15px 20px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            max-width: 1000px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #1e3a8a;
            font-weight: 700;
        }
        .header small {
            font-size: 10px;
            color: #4B5563;
            display: block;
            margin-top: 4px;
        }

        .section { margin-bottom: 20px; }
        .section-title {
            font-weight: 700;
            font-size: 13px;
            color: #1e3a8a;
            border-left: 5px solid #3b82f6;
            padding-left: 8px;
            margin-bottom: 8px;
            background: #E0E7FF;
            border-radius: 4px;
            padding: 4px 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            padding: 6px 8px;
            border: 1px solid #CBD5E1;
            vertical-align: middle;
        }
        th {
            background: #F3F4F6;
            color: #1e3a8a;
            font-weight: 700;
            text-align: left;
        }
        .currency {
            text-align: right;
            color: #1e3a8a;
            font-weight: 700;
            white-space: nowrap;
        }
        .totals {
            background: #DBEAFE;
            font-weight: 700;
        }

        .nested-table th, .nested-table td {
            font-size: 10.5px;
            padding: 4px 6px;
        }

        /* Tablas lado a lado */
        .side-by-side {
            display: table;
            width: 100%;
            border-spacing: 20px;
        }
        .side-by-side > div {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        /* Resumen final */
        .summary-table td {
            font-weight: 600;
            padding: 6px 8px;
        }
        .summary-table tr:nth-child(1) td { background: #DBEAFE; }
        .summary-table tr:nth-child(2) td { background: #EFF6FF; }
        .summary-table tr:nth-child(3) td { background: #BFDBFE; color: #1e40af; }

        @media print {
            body, .container { margin: 0; box-shadow: none; border: none; padding: 10mm; }
            .header h1 { font-size: 16px; }
            .section-title { font-size: 12px; }
            table, th, td { font-size: 10px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{$empresa->name}} - Corte de Caja</h1>
        <small>Fecha de impresión: {{date('d-m-Y H:i:s')}}</small>
    </div>

    <!-- Datos de Apertura -->
    <div class="section">
        <div class="section-title">Datos de Apertura</div>
        <table>
            <tr><td>Caja</td><td>{{$caja->cashbox->description}}</td></tr>
            <tr><td>Fecha Apertura</td><td>{{ date('d-m-Y H:i:s', strtotime($caja->created_at))}}</td></tr>
            <tr><td>Monto Apertura</td><td class="currency">${{number_format($caja->open_amount, 2)}}</td></tr>
            <tr><td>Empleado</td><td>{{$caja->openEmployee->name}} {{$caja->openEmployee->lastname}}</td></tr>
        </table>
    </div>

    <!-- Operaciones -->
    <div class="section">
        <div class="section-title">Operaciones</div>
        <table>
            <thead>
            <tr><th>Ingresos</th><th>Egresos</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <table class="nested-table">
                        <tr><td>Factura</td><td class="currency">${{number_format($caja->ingreso_factura, 2)}}</td></tr>
                        <tr><td>CCF</td><td class="currency">${{number_format($caja->ingreso_ccf, 2)}}</td></tr>
                        <tr><td>Órdenes</td><td class="currency">${{number_format($caja->ingreso_ordenes, 2)}}</td></tr>
                        <tr><td>Taller</td><td class="currency">${{number_format($caja->ingreso_taller, 2)}}</td></tr>
                        <tr><td>Caja Chica</td><td class="currency">${{number_format($caja->ingreso_caja_chica, 2)}}</td></tr>
                        <tr class="totals"><td>Total Ingresos</td><td class="currency">${{number_format($caja->ingreso_totales, 2)}}</td></tr>
                    </table>
                </td>
                <td>
                    <table class="nested-table">
                        <tr><td>Caja Chica</td><td class="currency">${{number_format($caja->egreso_caja_chica, 2)}}</td></tr>
                        <tr><td>Notas de Crédito</td><td class="currency">${{number_format($caja->egreso_nc, 2)}}</td></tr>
                        <tr class="totals"><td>Total Egresos</td><td class="currency">${{number_format($caja->egresos_totales, 2)}}</td></tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Saldo de Cierre y Detalle de Efectivo lado a lado -->
    <div class="section side-by-side">
        <div>
            <div class="section-title">Saldo de Cierre</div>
            <table class="nested-table">
                <tbody>
                <tr><td>Efectivo Ventas</td><td class="currency">${{number_format($caja->saldo_efectivo_ventas, 2)}}</td></tr>
                <tr><td>Tarjetas</td><td class="currency">${{number_format($caja->saldo_tarjeta, 2)}}</td></tr>
                <tr><td>Cheque</td><td class="currency">${{number_format($caja->saldo_cheque, 2)}}</td></tr>
                <tr><td>Caja Chica</td><td class="currency">${{number_format($caja->saldo_caja_chica, 2)}}</td></tr>
                <tr><td>Efectivo Órdenes</td><td class="currency">${{number_format($caja->saldo_efectivo_ordenes, 2)}}</td></tr>
                <tr class="totals"><td>Ingresos Totales</td><td class="currency">${{number_format($caja->ingreso_totales, 2)}}</td></tr>
                <tr><td>- Egresos</td><td class="currency">-${{number_format($caja->saldo_egresos_totales, 2)}}</td></tr>
                <tr><td>+ Apertura</td><td class="currency">${{number_format($caja->open_amount, 2)}}</td></tr>
                <tr class="totals"><td>Saldo Total</td><td class="currency">${{number_format($caja->saldo_total_operaciones, 2)}}</td></tr>
                <tr><td>Fecha Cierre</td><td>{{ date('d-m-Y H:i:s', strtotime($caja->updated_at))}}</td></tr>
                <tr><td>Empleado</td><td>{{$caja->closeEmployee->name}} {{$caja->closeEmployee->lastname}}</td></tr>
                </tbody>
            </table>
        </div>

        <div>
            <div class="section-title">Detalle de Efectivo</div>
            <table class="nested-table">
                <thead>
                <tr><th>Denominación</th><th>Cantidad</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                <tr><td>$100</td><td>{{ $caja->cant_cien }}</td><td class="currency">${{ number_format($caja->cant_cien * 100, 2) }}</td></tr>
                <tr><td>$50</td><td>{{ $caja->cant_cincuenta }}</td><td class="currency">${{ number_format($caja->cant_cincuenta * 50, 2) }}</td></tr>
                <tr><td>$20</td><td>{{ $caja->cant_veinte }}</td><td class="currency">${{ number_format($caja->cant_veinte * 20, 2) }}</td></tr>
                <tr><td>$10</td><td>{{ $caja->cant_diez }}</td><td class="currency">${{ number_format($caja->cant_diez * 10, 2) }}</td></tr>
                <tr><td>$5</td><td>{{ $caja->cant_cinco }}</td><td class="currency">${{ number_format($caja->cant_cinco * 5, 2) }}</td></tr>
                <tr><td>$1</td><td>{{ $caja->cant_uno }}</td><td class="currency">${{ number_format($caja->cant_uno * 1, 2) }}</td></tr>
                <tr><td>$0.25</td><td>{{ $caja->cant_cora }}</td><td class="currency">${{ number_format($caja->cant_cora * 0.25, 2) }}</td></tr>
                <tr><td>$0.10</td><td>{{ $caja->cant_cero_diez }}</td><td class="currency">${{ number_format($caja->cant_cero_diez * 0.10, 2) }}</td></tr>
                <tr><td>$0.05</td><td>{{ $caja->cant_cero_cinco }}</td><td class="currency">${{ number_format($caja->cant_cero_cinco * 0.05, 2) }}</td></tr>
                <tr><td>$0.01</td><td>{{ $caja->cant_cero_cero_uno }}</td><td class="currency">${{ number_format($caja->cant_cero_cero_uno * 0.01, 2) }}</td></tr>
                <tr class="totals">
                    <td colspan="2">Total Efectivo</td>
                    <td class="currency">${{ number_format($caja->total_efectivo, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resumen de Cierre -->
    <div class="section">
        <div class="section-title">Resumen de Cierre</div>
        <table class="summary-table">
            <tr><td>DH Cierre</td><td class="currency">${{ number_format($caja->dh_cierre, 2) }}</td></tr>
            <tr><td>Hay al Cierre</td><td class="currency">${{ number_format($caja->hay_cierre, 2) }}</td></tr>
            <tr>
                <td>Diferencia</td>
                <td class="currency" style="color: {{ $caja->dif_cierre >= 0 ? '#16a34a' : '#dc2626' }};">
                    ${{ number_format($caja->dif_cierre, 2) }}
                </td>
            </tr>

        </table>
    </div>
</div>
</body>
</html>
