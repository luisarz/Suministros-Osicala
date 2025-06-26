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
        $parentId = DB::table('categories')->where('id', 57)->value('parent_id'); // null si no tiene padre
        $excludedIds = $parentId ? [57, $parentId] : [57];

        /* 3. Consulta principal */
        $rawData = DB::table('sale_items as si')
            ->join('sales       as s', 's.id', '=', 'si.sale_id')
            ->join('inventories as i', 'i.id', '=', 'si.inventory_id')
            ->join('products    as p', 'p.id', '=', 'i.product_id')
            ->join('categories  as c', 'c.id', '=', 'p.category_id')        // hija
            ->leftJoin('categories as cp', 'cp.id', '=', 'c.parent_id')       // padre
            ->whereBetween('s.operation_date', [$startDate, $endDate])
            ->whereNotIn(DB::raw('COALESCE(cp.id, c.id)'), $excludedIds)      // ⬅️ excluye 57 y su padre
            ->where('s.seller_id', $id_employee) // Filtra por empleado
            ->where('s.sale_status', '=', 'Facturada') // Excluye ventas canceladas
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

//*********************

        $startDate = Carbon::createFromFormat('d-m-Y', $star_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end_date)->endOfDay();

        $rawData = DB::table('sale_items as si')
            ->join('sales       as s', 's.id', '=', 'si.sale_id')
            ->join('inventories as i', 'i.id', '=', 'si.inventory_id')
            ->join('products    as p', 'p.id', '=', 'i.product_id')
            ->join('categories  as c', 'c.id', '=', 'p.category_id')
            ->whereBetween('s.operation_date', [$startDate, $endDate])
            ->selectRaw('
        DATE(s.operation_date) AS sale_day,
        c.name AS category_name,
        c.commission_percentage,
        SUM(si.quantity * si.price) AS total_amount
    ')
            ->groupByRaw('DATE(s.operation_date), c.name, c.commission_percentage')
            ->orderBy('sale_day')
            ->get();

        $pivotData = [];

        $categories = collect($rawData)
            ->map(fn($row) => $row->category_name . ' (' . $row->commission_percentage . '%)')
            ->unique()
            ->sort()
            ->values();

        foreach ($rawData as $row) {
            $date = date('Y-m-d', strtotime($row->sale_day));
            $category = $row->category_name . ' (' . $row->commission_percentage . '%)';
            $amount = round($row->total_amount, 2);
            $commission = round($amount * ($row->commission_percentage / 100), 2);

            $pivotData[$date][$category] = [
                'amount' => $amount,
                'commission' => $commission,
            ];
        }

// Completar categorías faltantes y calcular totales por día
        foreach ($pivotData as $date => &$row) {
            $totalDay = 0;
            $totalCommission = 0;

            foreach ($categories as $cat) {
                if (!isset($row[$cat])) {
                    $row[$cat] = ['amount' => 0, 'commission' => 0];
                }
                $totalDay += $row[$cat]['amount'];
                $totalCommission += $row[$cat]['commission'];
            }

            $row['Total Día'] = $totalDay;
            $row['Total Comisión'] = $totalCommission;
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
            ->setPaper('letter', 'landscape');
        return $pdf->stream("reporte_comision.pdf"); // El PDF se abre en una nueva pestaña

//            ->setOptions([
//                'isHtml5ParserEnabled' => true,
//                'isRemoteEnabled' => true,
//            ]);

//        return response()->json([
//            'categories' => $categories,
//            'data'       => $pivotData,
//        ]);


//        $categories = Category::whereNull('parent_id')
//            ->select('id', 'name', 'commission_percentage')
//            ->whereNotIn('id', [56])
//            ->orderBy('name', 'asc')
//            ->get();
//
//        $sales = Sale::with([
//            'saleDetails' => function ($query) {
//                $query->select('sale_id', 'inventory_id', 'quantity', 'price', 'total');
//            },
//            'saleDetails.inventory' => function ($query) {
//                $query->select('id', 'product_id');
//            },
//            'saleDetails.inventory.product' => function ($query) {
//                $query->select('id', 'name', 'category_id');
//            },
////            'saleDetails.inventory.product.category' => function ($query) {
////                $query->select('id', 'name', 'parent_id');
////            },
//            'saleDetails.inventory.product.category.parent' => function ($query) {
//                $query->select('id', 'name', 'commission_percentage');
//            }
//        ])
//            ->whereBetween('operation_date', [$startDate, $endDate])
//            ->where('seller_id', $id_employee)
//            ->select('id', 'document_internal_number', 'operation_date', 'sale_total', 'sale_status')
//            ->get();
//
//        $empleado = Employee::where('id', $id_employee)->select('name', 'lastname', 'phone', 'gender', 'dui', 'nit')->get();
//        $vendedor = $empleado[0]->name . ' ' . $empleado[0]->lastname . ' - DUI ' . $empleado[0]->dui ?? '';
//        $header = [
//            "empleado" => $empleado,
//            'StartDate' => $startDate,
//            'EndDate' => $endDate,
//            'categorias' => []
//        ];
//
//        foreach ($categories as $category) {
//            $header['categorias'][$category->name] = $category->commission_percentage . '%';
//        }
//        $header['categorias']['Sin Categoría'] = '0%'; // Agregar "Sin Categoría" con 0% de comisión
//
//        $report = [$header]; // Incluir el encabezado en el reporte
//
//// Inicializar un arreglo para acumular los totales por categoría
//        $totalCategories = [];
//        $totalCommissions = []; // Arreglo para acumular las comisiones por categoría
//
//        foreach ($categories as $category) {
//            $totalCategories[$category->name] = 0;
//            $totalCommissions[$category->name] = 0; // Inicializar las comisiones en 0
//        }
//        $totalCategories['Sin Categoría'] = 0;
//        $totalCommissions['Sin Categoría'] = 0; // Inicializar las comisiones para "Sin Categoría"
//
//// Crear un arreglo para las ventas por fecha
//        $ventasPorFecha = [];
//
//        foreach ($sales as $sale) {
//            $date = $sale->operation_date;
//
//            if (!isset($ventasPorFecha[$date])) {
//                $ventasPorFecha[$date] = [
//                    'date' => $date,
//                    'categories' => [],
//                ];
//
//                // Inicializar todas las categorías principales con 0 y agregar el porcentaje de comisión
//                foreach ($categories as $category) {
//                    $ventasPorFecha[$date]['categories'][$category->name] = [
//                        'ventas' => 0,
//                        'comision_porcentaje' => $category->commission_percentage, // Agregar el porcentaje de comisión
//                        'comision_total' => 0 // Inicializar la comisión total en 0
//                    ];
//                }
//                // Asegurarse de que la categoría "Sin Categoría" esté presente
//                $ventasPorFecha[$date]['categories']['Sin Categoría'] = [
//                    'ventas' => 0,
//                    'comision_porcentaje' => 0, // No hay comisión para "Sin Categoría"
//                    'comision_total' => 0
//                ];
//            }
//
//            // Sumar las ventas por categoría para esta fecha
//            foreach ($sale->saleDetails as $detail) {
//                // Verificar si el inventario existe
//                if (!$detail->inventory) {
//                    // Si no hay inventario, sumar a "Sin Categoría"
//                    $ventasPorFecha[$date]['categories']['Sin Categoría']['ventas'] += $detail->total;
//                    $totalCategories['Sin Categoría'] += $detail->total;
//                    continue; // Saltar al siguiente detalle
//                }
//
//                // Verificar si el producto existe
//                if (!$detail->inventory->product) {
//                    // Si no hay producto, sumar a "Sin Categoría"
//                    $ventasPorFecha[$date]['categories']['Sin Categoría']['ventas'] += $detail->total;
//                    $totalCategories['Sin Categoría'] += $detail->total;
//                    continue; // Saltar al siguiente detalle
//                }
//
//                // Verificar si la categoría existe
//                $category = $detail->inventory->product->category;
//                if (!$category) {
//                    // Si no hay categoría, sumar a "Sin Categoría"
//                    $ventasPorFecha[$date]['categories']['Sin Categoría']['ventas'] += $detail->total;
//                    $totalCategories['Sin Categoría'] += $detail->total;
//                    continue; // Saltar al siguiente detalle
//                }
//
//                // Verificar si la categoría tiene una categoría padre válida
//                if ($category->parent) {
//                    $categoryName = $category->parent->name; // Usar la categoría padre
//                    $commissionPercentage = $category->parent->commission_percentage; // Obtener el porcentaje de comisión
//                } else {
//                    $categoryName = 'Sin Categoría'; // Si no tiene categoría padre, asignar a "Sin Categoría"
//                    $commissionPercentage = 0; // No hay comisión para "Sin Categoría"
//                }
//
//                // Si la categoría existe en el reporte, sumar las ventas y calcular la comisión
//                if (isset($ventasPorFecha[$date]['categories'][$categoryName])) {
//                    $ventasPorFecha[$date]['categories'][$categoryName]['ventas'] += $detail->total;
//                    $totalCategories[$categoryName] += $detail->total;
//
//                    // Calcular la comisión y sumarla al total de comisiones
//                    $commission = $detail->total * ($commissionPercentage / 100);
//                    $ventasPorFecha[$date]['categories'][$categoryName]['comision_total'] += $commission;
//                    $totalCommissions[$categoryName] += $commission;
//                } else {
//                    // Si la categoría no existe en el reporte, agregarla a "Sin Categoría"
//                    $ventasPorFecha[$date]['categories']['Sin Categoría']['ventas'] += $detail->total;
//                    $totalCategories['Sin Categoría'] += $detail->total;
//                }
//            }
//        }
//
//// Ordenar las comisiones de menor a mayor
//        uasort($totalCommissions, function ($a, $b) {
//            return $a <=> $b; // Ordenar de menor a mayor
//        });
//
//// Crear un arreglo ordenado para los totales por categoría (orden alfabético)
//        $sortedTotalCategories = [];
//        foreach ($categories as $category) {
//            $sortedTotalCategories[$category->name] = $totalCategories[$category->name];
//        }
//        $sortedTotalCategories['Sin Categoría'] = $totalCategories['Sin Categoría'];
//
//// Crear un arreglo ordenado para las comisiones (orden alfabético)
//        $sortedTotalCommissions = [];
//        foreach ($categories as $category) {
//            $sortedTotalCommissions[$category->name] = $totalCommissions[$category->name];
//        }
//        $sortedTotalCommissions['Sin Categoría'] = $totalCommissions['Sin Categoría'];
//
//// Agregar las ventas por fecha al reporte
//        $report[] = [
//            'ventasDiarias' => array_values($ventasPorFecha)
//        ];
//
//// Agregar los totales por categoría y las comisiones al final del reporte
//        $report[] = [
//            'total_by_category' => $sortedTotalCategories,
//            'comission_by_category' => $sortedTotalCommissions
//        ];

//        $ventas = array_values($report);
        $empresa = Company::find(1);
        $id_sucursal = \Auth::user()->employee->branch_id;
        $sucursal = Branch::find($id_sucursal);

        $pdf = Pdf::loadView('DTE.comission_sale_pdf', compact($categories, $pivotData, 'empresa', 'sucursal', 'startDate', 'endDate', 'vendedor'))->setPaper('letter', 'landscape');
//            ->setOptions([
//                'isHtml5ParserEnabled' => true,
//                'isRemoteEnabled' => true,
//            ]);

        return $pdf->stream("reporte_comision.pdf"); // El PDF se abre en una nueva pestaña


    }
}
