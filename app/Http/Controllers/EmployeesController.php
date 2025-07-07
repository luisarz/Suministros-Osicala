<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{

    public function sales($id_employee, $star_date, $end_date)
    {


        /* 1. Rango de fechas */
        $startDate = Carbon::createFromFormat('d-m-Y', $star_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end_date)->endOfDay();

        /* 2. IDs a excluir — 57 y su categoría padre (si existe) */
        // 1. Obtener categorías hijas de 56
        $childIds = DB::table('categories')
            ->where('parent_id', 56)
            ->pluck('id')
            ->toArray();

// 2. Lista de exclusión: 56 y sus hijas
        $excludedIds = array_merge([56], $childIds);

        /* 3. Consulta principal */
        $rawData = DB::table('sale_items as si')
            ->join('sales       as s', 's.id', '=', 'si.sale_id')
            ->join('inventories as i', 'i.id', '=', 'si.inventory_id')
            ->join('products    as p', 'p.id', '=', 'i.product_id')
            ->join('categories  as c', 'c.id', '=', 'p.category_id')        // hija
            ->leftJoin('categories as cp', 'cp.id', '=', 'c.parent_id')       // padre
            ->whereBetween('s.operation_date', [$startDate, $endDate])
            ->whereIn('s.sale_status', ['Facturada', 'Finalizado']) // Excluye ventas canceladas
            ->whereNotIn(DB::raw('COALESCE(cp.id, c.id)'), $excludedIds)      // ⬅️ excluye 57 y su padre
            ->where('s.seller_id', $id_employee) // Filtra por empleado
            ->whereIn('s.operation_type', ['Sale', 'Order', 'Quote'])
            ->selectRaw('
        DATE(s.operation_date)                                       AS sale_day,
        COALESCE(cp.id,  c.id)                                       AS parent_id,
        COALESCE(cp.name, c.name)                                    AS parent_name,
        COALESCE(cp.commission_percentage, c.commission_percentage)  AS commission_percentage,
        SUM(si.quantity * si.price)                                  AS total_amount
    ')
            ->groupByRaw('DATE(s.operation_date), parent_id, parent_name, commission_percentage')
            ->orderBy('sale_day')
            ->get();

        /* ---------- Tabla dinámica ---------- */
        $pivotData = [];
        $categoriesMap = [];   // mantiene orden de aparición

        foreach ($rawData as $r) {
            $date = date('Y-m-d', strtotime($r->sale_day));
            $category = $r->parent_name . ' (' . $r->commission_percentage . '%)';
            $amount = round($r->total_amount, 2);
            $commission = round($amount * ($r->commission_percentage / 100), 2);

            $pivotData[$date][$category] = [
                'amount' => $amount,
                'commission' => $commission,
            ];

            $categoriesMap[$category] ??= true;  // registra 1.ª aparición
        }

        $categories = array_keys($categoriesMap);          // orden natural

        /* Totales por día y columnas faltantes */
        foreach ($pivotData as $date => &$row) {
            $totalDay = $totalCommission = 0;

            foreach ($categories as $cat) {
                $row[$cat] ??= ['amount' => 0, 'commission' => 0];
                $totalDay += $row[$cat]['amount'];
                $totalCommission += $row[$cat]['commission'];
            }

            $row['Total Día'] = $totalDay;
            $row['Total Comisión'] = $totalCommission;
        }

        ksort($pivotData); // fechas ordenadas


        $empresa = Company::find(1);
        $id_sucursal = \Auth::user()->employee->branch_id;
        $sucursal = Branch::find($id_sucursal);
        $empleado = Employee::where('id', $id_employee)->select('name', 'lastname')->first();

        $pdf = Pdf::loadView('DTE.comission_sale_pdf',
            compact(
                'empresa',
                'sucursal',
                'empleado',
                'categories',
                'pivotData',
                'startDate',
                'endDate'
            ))
            ->setPaper('letter', 'landscape');
        return $pdf->stream("reporte_comision.pdf"); // El PDF se abre en una nueva pestaña


    }

    public function salesWork($id_employee, $star_date, $end_date)
    {
        /* 1. Rango de fechas */
        $startDate = Carbon::createFromFormat('d-m-Y', $star_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end_date)->endOfDay();

/// 1. Obtener categorías hijas de 56
        $childIds = DB::table('categories')
            ->where('parent_id', 56)
            ->pluck('id')
            ->toArray();

// 2. Incluir categoría 56 + sus hijas
        $includedIds = array_merge([56], $childIds);
//        dd  ($includedIds);

// 3. Consulta principal (solo categoría hija)
        $rawData = DB::table('sale_items as si')
            ->join('sales       as s', 's.id', '=', 'si.sale_id')
            ->join('inventories as i', 'i.id', '=', 'si.inventory_id')
            ->join('products    as p', 'p.id', '=', 'i.product_id')
            ->join('categories  as c', 'c.id', '=', 'p.category_id') // solo hija
            ->whereBetween('s.operation_date', [$startDate, $endDate])
            ->whereIn('s.sale_status', ['Facturada', 'Finalizado']) // Excluye ventas canceladas
            ->whereIn('c.id', $includedIds) // solo categorías hijas (sin COALESCE)
            ->where('s.mechanic_id', $id_employee)
            ->whereIn('s.operation_type', ['Sale', 'Order', 'Quote'])
            ->selectRaw('
                DATE(s.operation_date)      AS sale_day,
                c.id                        AS category_id,
                c.name                      AS category_name,
                c.commission_percentage     AS commission_percentage,
                SUM(si.quantity * si.price) AS total_amount,
                COUNT(DISTINCT s.id)        AS total_operations,

                    GROUP_CONCAT(DISTINCT CAST(s.order_number AS UNSIGNED)) AS order_numbers



            ')
            ->groupByRaw('DATE(s.operation_date), c.id, c.name, c.commission_percentage')
            ->orderBy('sale_day')
            ->get();
//        dd($rawData);

        $pivotData = [];
        $categoriesMap = [];

        foreach ($rawData as $r) {
            $date = date('Y-m-d', strtotime($r->sale_day));
            $category = $r->category_name . ' (' . $r->commission_percentage . '%)';
            $amount = round($r->total_amount, 2);
            $commission = round($amount * ($r->commission_percentage / 100), 2);
            $operations = (int)$r->total_operations;
            $orders = $r->order_numbers;

            $pivotData[$date][$category] = [
                'amount' => $amount,
                'commission' => $commission,
                'operations' => $operations,
                'orders' => $orders,

            ];

            $categoriesMap[$category] ??= true;
        }

        $categories = array_keys($categoriesMap);

        foreach ($pivotData as $date => &$row) {
            $totalDay = $totalCommission = $totalOperations = 0;

            foreach ($categories as $cat) {
                $row[$cat] ??= ['amount' => 0, 'commission' => 0, 'operations' => 0];
                $totalDay += $row[$cat]['amount'];
                $totalCommission += $row[$cat]['commission'];
                $totalOperations += $row[$cat]['operations'];
            }

            $row['Total Día'] = $totalDay;
            $row['Total Comisión'] = $totalCommission;
            $row['Total Operaciones'] = $totalOperations;
        }

        ksort($pivotData);


        $empresa = Company::find(1);
        $id_sucursal = \Auth::user()->employee->branch_id;
        $sucursal = Branch::find($id_sucursal);
        $empleado = Employee::where('id', $id_employee)->select('name', 'lastname')->first();

        $pdf = Pdf::loadView('DTE.comission_sale_pdf',
            compact(
                'empresa',
                'sucursal',
                'empleado',
                'categories',
                'pivotData',
                'startDate',
                'endDate'
            ))
            ->setPaper('letter', 'portrait');
        return $pdf->stream("reporte_comision.pdf"); // El PDF se abre en una nueva pestaña

    }
}
