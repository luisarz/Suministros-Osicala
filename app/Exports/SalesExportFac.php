<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpParser\Node\Scalar\String_;

class SalesExportFac implements FromCollection, WithColumnFormatting, WithEvents, WithHeadings
{
    protected string $documentType;

    protected Carbon $startDate;

    protected Carbon $endDate;

    protected float $totalGravada = 0;

    protected float $totalDebitoFiscal = 0;

    protected float $totalVenta = 0;

    public function __construct(String $documentType,string $startDate, string $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
        $this->documentType = $documentType;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Clase',
            'Tipo',
            'Sello de recepcion',
            'Codigo de generacion',
            'Numero de control',
            'NRC',
            'NIT',
            'DUI',
            'Razon Social',
            'Exenta',
            'No Sujeta',
            'Gravada Local',
            'Débito Fiscal',
            'Retencion 1%',
            'ISR 10%',
            'Total Venta',
            'Estado',
            'fecha MH',
            'Turismo 5%',
        ];

    }

    public function collection(): Collection
    {
        set_time_limit(0);
        $sales = Sale::select(
            'id',
            'operation_date as fecha',
            'sale_total as venta_gravada',
            'net_amount as neto',
            'taxe as iva',
            'sale_total as total',
            'document_type_id',
            'document_internal_number',
            'seller_id',
            'customer_id',
            'operation_condition_id',
            'payment_method_id',
            'sale_status',
            'billing_model',
            'transmision_type',
            'is_dte',
            'is_hacienda_send'

        )
            ->where('is_dte', '1')
            ->whereIn('document_type_id', [$this->documentType])// 1- Fac 3-CCF 5-NC 11-FExportacion 14-Sujeto excluido
            ->whereBetween('operation_date', [$this->startDate, $this->endDate])
            ->orderBy('operation_date', 'asc')
            ->with(['dteProcesado' => function ($query) {
                $query->select('sales_invoice_id', 'num_control', 'selloRecibido', 'codigoGeneracion', 'fhProcesamiento', 'dte')
                    ->whereNotNull('selloRecibido');
            },
                'documenttype', 'customer', 'billingModel', 'salescondition', 'seller'])
            ->get()
            ->map(function ($sale) {
                //                dd($sale);
                $persontype = $sale->customer->person_type_id ?? 1; // 1: Natural, 2: Jurídica

                $rawNit = $sale->customer->nit ?? '';
                $rawDui = $sale->customer->dui ?? '';

                $cleanNit = str_replace('-', '', $rawNit);
                $formatNit = str_repeat('0', strlen($cleanNit)); // Ejemplo: '00000000000000'
                $cleanDUI = str_replace('-', '', $rawDui);
                $formatDUI = str_repeat('0', strlen($cleanDUI)); // Ejemplo: '00000000000000'
                $nit = ($rawNit === '0000-000000-000-0') ? null
                    : '=TEXT("'.$cleanNit.'","'.$formatNit.'")';
                $dui = ($rawDui === '00000000-0') ? null
                    : '=TEXT("'.$cleanDUI.'","'.$formatDUI.'")';

                $nit_report = '';
                $dui_report = '';

                // Comparar valores sin formato, no fórmulas
                $isSame = $cleanNit === $cleanDUI;

                if ($persontype == 2) { // Jurídica
                    $nit_report = $nit;
                    $dui_report = $isSame ? '' : $dui;
                } else { // Natural
                    $dui_report = $dui;
                    $nit_report = $isSame ? '' : $nit;
                }

                $json = $sale->dteProcesado->dte ?? null;
                $resumen = $json['resumen'] ?? null;
                // Acceder al resumen

                // Extraer los valores que pediste
                $totalGravada = $resumen['totalGravada'] ?? $resumen['totalPagar'];
                $totalGravada_rep = $totalGravada;
                $tributos = $resumen['tributos'] ?? null;
                $totalIva = $resumen['totalIva'] ?? 0;
                $retencion_1 = $resumen['ivaRete1'] ?? 0;
                $retencion_10 = $resumen['reteRenta'] ?? 0;
                $iva = 0;
                $turismo = 0;

                if (isset($resumen['tributos']) && is_array($resumen['tributos'])) {
                    foreach ($resumen['tributos'] as $tributo) {
                        if (($tributo['codigo'] ?? null) === '20') {
                            $iva = $tributo['valor'] ?? 0;
                        }
                        if (($tributo['codigo'] ?? null) === '59') {
                            $turismo = $tributo['valor'] ?? 0;
                        }
                    }
                }

                if ($iva == 0) {
                    $iva = $resumen['totalIva'] ?? 0;
                }

                if ($sale->document_type_id == 1) {
                    $totalGravada_rep -= $iva;
                }

                if ($sale->sale_status == 'Anulado') {
                    $sale->sale_status = 'Invalidado';
                    $totalGravada_rep = 0;
                    $iva = 0;
                    $retencion_1 = 0;
                    $turismo = 0;
                    $retencion_10 = 0;
                }

                return [
                    'fecha' => date('d/m/Y', strtotime($sale->fecha)),
                    'document_type' => '4',
                    'type' => $sale->documenttype->code,
                    'sello_recepcion' => $sale->dteProcesado->selloRecibido ?? null,
                    'cod_generaicon' => $sale->dteProcesado->codigoGeneracion ?? null,
                    'num_control' => $sale->dteProcesado->num_control ?? null, // DTE
                    'nrc' => $sale->customer->nrc ?? null,
                    'nit' => $nit_report,
                    'dui' => $dui_report,
                    'nombre' => $sale->customer->fullname ?? null,
                    'exentas' => $sale->exentas ?? null,
                    'no_sujetas' => $sale->no_sujetas ?? null,
                    'neto' => $totalGravada_rep ?? 0,
                    'iva' => $iva ?? 0,
                    'retencion_1_percetage' => $retencion_1 ?? 0,
                    'isr_10_percentage' => $retencion_10 ?? 0,
                    'total' => ($totalGravada_rep + $iva) - ($retencion_1 + $retencion_10) ?? 0,
                    'estado' => strtoupper($sale->sale_status),
                    'fecha_mh' => date('d/m/Y', strtotime($sale->dteProcesado->fhProcesamiento)),
                    'turismo_5' => $turismo ?? 0,
                ];
            });

        return $sales;
    }

    public function columnFormats(): array
    {
        return [

            'K' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'L' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'M' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'N' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'O' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'P' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'Q' => NumberFormat::FORMAT_ACCOUNTING_USD,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Ajustar ancho automático
                foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Negrita para encabezados
                $sheet->getStyle('A1:R1')->getFont()->setBold(true);

                // Alinear numéricos a la derecha
                $sheet->getStyle('J2:R' . $highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

//                // Resaltar en rojo los valores negativos de la columna EXISTENCIA (columna L)
//                for ($row = 2; $row <= $highestRow; $row++) {
//                    $cellValue = $sheet->getCell('L'.$row)->getValue();
//                    if ((float)$cellValue < 0) {
//                        $sheet->getStyle('L'.$row)->getFont()->getColor()->setRGB('FF0000'); // rojo
//                    }
//                }
            },
        ];
    }
}
