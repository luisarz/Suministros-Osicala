<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Corte de Caja</title>
    <style>
        /* Reset y base */
        body, html {
            margin: 0; padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        /* Contenedor principal con márgenes reducidos para A4 */
        .container {
            padding: 10mm 15mm;
            box-sizing: border-box;
            margin: auto;
            background: #fff;
            border: 1px solid #ddd;
        }

        /* Encabezado */
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            color: #1e3a8a;
            font-weight: 700;
            user-select: none;
        }
        .header small {
            font-size: 9px;
            color: #666;
            display: block;
            margin-top: 3px;
        }

        /* Secciones */
        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: 700;
            font-size: 12px;
            color: #1e3a8a;
            border-left: 4px solid #3b82f6;
            padding-left: 6px;
            margin-bottom: 6px;
            user-select: none;
        }

        /* Tablas generales */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            padding: 4px 6px;
            border: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background: #f9fafb;
            font-weight: 700;
            color: #1e3a8a;
            user-select: none;
        }
        .currency {
            text-align: right;
            color: #3b82f6;
            font-weight: 700;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }
        .totals {
            background: #f0f4ff;
            font-weight: 700;
        }

        /* Tablas internas */
        .nested-table th, .nested-table td {
            font-size: 10.5px;
            padding: 3px 5px;
        }

        /* Layout de dos columnas */
        .flex-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .flex-col {
            flex: 1 1 48%;
            min-width: 250px;
        }

        /* Hover para filas */
        tbody tr:hover {
            background: #f0f4ff;
        }

        /* Evitar que se corte tabla en impresión */
        tr, td, th {
            page-break-inside: avoid;
        }

        /* Ajuste para impresión */
        @media print {
            body, .container {
                margin: 0;
                box-shadow: none;
                border: none;
                width: auto;
                min-height: auto;
                padding: 10mm 10mm;
            }
            .header h1 {
                font-size: 14px;
            }
            .section-title {
                font-size: 11px;
            }
            table, th, td {
                font-size: 10px;
            }
            .flex-row {
                flex-wrap: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{$empresa->name}} - Corte de Caja</h1>
        <small>Fecha de impresión: {{date('d-m-Y H:i:s')}}</small>
    </div>

    <div class="section">
        <div class="section-title">Datos de Apertura</div>
        <table>
            <tr><td>Caja</td><td>{{$caja->cashbox->description}}</td></tr>
            <tr><td>Fecha Apertura</td><td>{{ date('d-m-Y H:i:s', strtotime( $caja->created_at))}}</td></tr>
            <tr><td>Monto Apertura</td><td class="currency">${{number_format($caja->open_amount, 2)}}</td></tr>
            <tr><td>Empleado</td><td>{{$caja->openEmployee->name}} {{$caja->openEmployee->lastname}}</td></tr>
        </table>
    </div>

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

    <div class="section flex-row">
        <div class="flex-col">
            <div class="section-title">Saldo de Cierre</div>
            <table>
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

        <div class="flex-col">
            <div class="section-title">Detalle de Efectivo</div>
            <table>
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

</div>
</body>
</html>
