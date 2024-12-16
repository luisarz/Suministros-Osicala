<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Luecano\NumeroALetras\NumeroALetras;

class OrdenController extends Controller
{
    public function getConfiguracion()
    {
        $configuracion = Company::find(1);
        if ($configuracion) {
            return $configuracion;
        } else {
            return null;
        }
    }

    public function generarPdf($idVenta)
    {
        //abrir el json en DTEs
        $datos = Sale::with('customer', 'saleDetails', 'whereHouse', 'saleDetails.inventory', 'saleDetails.inventory.product', 'documenttype', 'seller')->find($idVenta);
        $empresa = $this->getConfiguracion();

        $formatter = new NumeroALetras();
        $montoLetras = $formatter->toInvoice($datos->sale_total, 2, 'DoLARES');
        $pdf = Pdf::loadView('order.order-print-pdf', compact('datos', 'empresa', 'montoLetras')); // Cargar vista y pasar datos


        return $pdf->stream("Orden-ventas-.{$idVenta}.pdf"); // El PDF se abre en una nueva pestaña

    }
}
