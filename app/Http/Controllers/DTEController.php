<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\HistoryDte;
use App\Models\Sale;
use DateTime;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

class DTEController extends Controller
{
    public function generarDTE($idVenta)
    {
        if ($this->getConfiguracion() == null) {
            return response()->json(['message' => 'No se ha configurado la empresa']);
        }
        $venta = Sale::with('documenttype')->find($idVenta);
        if (!$venta) {
            return [
                'estado' => 'FALLO', // o 'ERROR'
                'mensaje' => 'Venta no encontrada',
            ];
        }
        if ($venta->is_dte) {
            return [
                'estado' => 'FALLO', // o 'ERROR'
                'mensaje' => 'DTE ya enviado',
            ];
        }
        if ($venta->documenttype->code == '01') {   //factura consumidor final
            return $this->facturaJson($idVenta);
        } elseif ($venta->documenttype->code == '03') {   //factura consumidor final

            return $this->CCFJson($idVenta);
        } else {
            return [
                'estado' => 'FALLO', // o 'ERROR'
                'mensaje' => 'Tipo de documento no soportado',
            ];
        }
    }

    public function getConfiguracion()
    {
        $configuracion = Company::find(1);
        if ($configuracion) {
            return $configuracion;
        } else {
            return null;
        }
    }


    function facturaJson($idVenta)
    {
        $factura = Sale::with('wherehouse.stablishmenttype', 'documenttype', 'seller', 'customer', 'customer.economicactivity', 'customer.departamento', 'customer.documenttypecustomer', 'salescondition', 'paymentmethod', 'saleDetails', 'saleDetails.inventory.product')->find($idVenta);


        $documentTYPE = $factura->documenttype->code;
        $establishmentType = $factura->wherehouse->stablishmenttype->code;
        $conditionCode = $factura->salescondition->code;
        $establishmentType = $factura->wherehouse->stablishmenttype->code;
        $conditionCode = $factura->salescondition->code;
        $receptor = [
            "documentType" => null,//$factura->customer->documenttypecustomer->code ?? null,
            "documentNum" => null,//$factura->customer->dui ?? $factura->customer->nit,
            "nrc" => null,//str_replace("-","",$factura->customer->nrc) ?? null,
            "name" => $factura->customer->name . " " . $factura->customer->last_name ?? null,
            "phoneNumber" => $factura->customer->phone ?? null,
            "email" => $factura->customer->email ?? null,
            "economicAtivity" => $factura->customer->economicactivity->code ?? null,
            "address" => $factura->customer->address ?? null,
            "codeCity" => $factura->customer->departamento->code ?? null,
            "codeMunicipality" => $factura->customer->distrito->code ?? null,
        ];
        $extencion = [
            "deliveryName" => $factura->seller->name . " " . $factura->seller->last_name ?? null,
            "deliveryDoc" => str_replace("-", "", $factura->seller->dui),
        ];
        $items = [];
        $i = 1;
        foreach ($factura->saleDetails as $detalle) {
            $codeProduc = str_pad($detalle->inventory_id, 10, '0', STR_PAD_LEFT);
            $items[] = [
                "itemNum" => intval($i),
                "itemType" => 1,
                "docNum" => null,
                "code" => $codeProduc,
                "tributeCode" => null,
                "description" => $detalle->inventory->product->name,
                "quantity" => doubleval($detalle->quantity),
                "unit" => 1,
                "except" => false,
                "unitPrice" => doubleval(number_format($detalle->price, 8, '.', '')),
                "discountAmount" => doubleval(number_format($detalle->discount, 8, '.', '')),
                "exemptSale" => doubleval(number_format(0, 8, '.', '')),
                "tributes" => null,
                "psv" => doubleval(number_format($detalle->price, 8, '.', '')),
                "untaxed" => doubleval(number_format(0, 8, '.', '')),
            ];
            $i++;
        }
        $dte = [
            "documentType" => "01",
            "invoiceId" => intval($factura->id),
            "establishmentType" => $establishmentType,
            "conditionCode" => $conditionCode,
            "receptor" => $receptor,
            "extencion" => $extencion,
            "items" => $items
        ];

        return response()->json($dte);


        $responseData = $this->SendDTE($dte, $idVenta);
//        dd($responseData["estado"]);
        if (isset($responseData["estado"]) == "RECHAZADO") {
            return [
                'estado' => 'FALLO', // o 'ERROR'
                'mensaje' => 'DTE falló al enviarse: ' . implode(', ', $responseData['observaciones'] ?? []), // Concatenar observaciones
            ];
        } else {
            $venta = Sale::find($idVenta);
            $venta->is_dte = true;
            $venta->save();
            return [
                'estado' => 'EXITO',
                'mensaje' => 'DTE enviado correctamente',
            ];
        }
    }

    function CCFJson($idVenta)
    {
        $factura = Sale::with('wherehouse.stablishmenttype', 'documenttype', 'seller', 'customer', 'customer.economicactivity', 'customer.departamento', 'customer.documenttypecustomer', 'salescondition', 'paymentmethod', 'saleDetails', 'saleDetails.inventory.product')->find($idVenta);


        $documentTYPE = $factura->documenttype->code;
        $establishmentType = $factura->wherehouse->stablishmenttype->code;
        $conditionCode = $factura->salescondition->code;
        $establishmentType = $factura->wherehouse->stablishmenttype->code;
        $conditionCode = $factura->salescondition->code;
        $receptor = [
            "address" => $factura->customer->address ?? null,
            "businessName"=>null,
            "codeCity" => $factura->customer->departamento->code ?? null,
            "codeMunicipality" => $factura->customer->distrito->code ?? null,
            "documentNum" => $factura->customer->dui ?? $factura->customer->nit,
            "documentType" => $factura->customer->documenttypecustomer->code ?? null,
            "economicAtivity" => $factura->customer->economicactivity->code ?? null,

            "nrc" => str_replace("-","",$factura->customer->nrc) ?? null,
            "name" => $factura->customer->name . " " . $factura->customer->last_name ?? null,
            "phoneNumber" =>str_replace(['-', '(', ')',' '], '', $factura->customer->phone)  ?? null,
            "email" => $factura->customer->email ?? null,
            "nit"=>str_replace("-",'',$factura->customer->dui) ?? null,
        ];
        $extencion = [
            "deliveryName" => $factura->seller->name . " " . $factura->seller->last_name ?? null,
            "deliveryDoc" => str_replace("-", "", $factura->seller->dui),
        ];
        $items = [];
        $i = 1;
        foreach ($factura->saleDetails as $detalle) {
            $codeProduc = str_pad($detalle->inventory_id, 10, '0', STR_PAD_LEFT);
            $tributes=["20"];
            $items[] = [
                "itemNum" => intval($i),
                "itemType" => 1,
                "docNum" => null,
                "code" => $codeProduc,
                "tributeCode" => null,
                "description" => $detalle->inventory->product->name,
                "quantity" => doubleval($detalle->quantity),
                "unit" => 1,
                "except" => false,
                "unitPrice" => doubleval(number_format($detalle->price, 8, '.', '')),
                "discountAmount" => doubleval(number_format($detalle->discount, 8, '.', '')),
                "exemptSale" => doubleval(number_format(0, 8, '.', '')),
                "tributes" => $tributes,
                "psv" => doubleval(number_format($detalle->price, 8, '.', '')),
                "untaxed" => doubleval(number_format(0, 8, '.', '')),
            ];
            $i++;
        }
        $dte = [
            "documentType" => "03",
            "invoiceId" => intval($factura->id),
            "establishmentType" => $establishmentType,
            "conditionCode" => $conditionCode,
            "receptor" => $receptor,
            "extencion" => $extencion,
            "items" => $items
        ];

//        return response()->json($dte);


        $responseData = $this->SendDTE($dte, $idVenta);
//        dd($responseData["estado"]);
        if (isset($responseData["estado"]) == "RECHAZADO") {
            return [
                'estado' => 'FALLO', // o 'ERROR'
                'mensaje' => 'DTE falló al enviarse: ' . implode(', ', $responseData['observaciones'] ?? []), // Concatenar observaciones
            ];
        } else {
            $venta = Sale::find($idVenta);
            $venta->is_dte = true;
            $venta->save();
            return [
                'estado' => 'EXITO',
                'mensaje' => 'DTE enviado correctamente',
            ];
        }
    }

    function SendDTE($dteData, $idVenta) // Assuming $dteData is the data you need to send
    {
        try {
            $urlAPI = 'http://api-fel-sv-dev.olintech.com/api/DTE/generateDTE'; // Set the correct API URL
            $apiKey = $this->getConfiguracion()->api_key; // Assuming you retrieve the API key from your config

            // Convert data to JSON format
            $dteJSON = json_encode($dteData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlAPI,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dteJSON,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'apiKey: ' . $apiKey
                ),
            ));

            $response = curl_exec($curl);

            // Check for cURL errors
            if ($response === false) {
                $data = [
                    'estado' => 'RECHAZADO ',
                    'mensaje' => "Ocurrio un eror" . curl_error($curl)
                ];
                return $data;
            }

            // Close the cURL session
            curl_close($curl);

            $responseData = json_decode($response, true);
//            dd($responseData);
            $responseHacienda = (isset($responseData["estado"]) == "RECHAZADO") ? $responseData : $responseData["respuestaHacienda"];
//            dd($responseHacienda);
            $falloDTE = new HistoryDte;
            $falloDTE->sales_invoice_id = $idVenta;
            $falloDTE->version = $responseHacienda["version"]??null;
            $falloDTE->ambiente = $responseHacienda["ambiente"];
            $falloDTE->versionApp = $responseHacienda["versionApp"];
            $falloDTE->estado = $responseHacienda["estado"];
            $falloDTE->codigoGeneracion = $responseHacienda["codigoGeneracion"];
            $falloDTE->selloRecibido = $responseHacienda["selloRecibido"] ?? null;
            $fhProcesamiento = DateTime::createFromFormat('d/m/Y H:i:s', $responseHacienda["fhProcesamiento"]);
            $falloDTE->fhProcesamiento = $fhProcesamiento ? $fhProcesamiento->format('Y-m-d H:i:s') : null;
            $falloDTE->clasificaMsg = $responseHacienda["clasificaMsg"];
            $falloDTE->codigoMsg = $responseHacienda["codigoMsg"];
            $falloDTE->descripcionMsg = $responseHacienda["descripcionMsg"];
            $falloDTE->observaciones = json_encode($responseHacienda["observaciones"]);
            $falloDTE->dte = $responseData ?? null;
            $falloDTE->save();
            return $responseData;

        } catch (Exception $e) {
            // Enhanced error logging
            error_log('Caught exception: ' . $e->getMessage());
            $data = [
                'estado' => 'RECHAZADO ',
                'mensaje' => "Ocurrio ub¿n eror" . $e->getMessage()
            ];
            return $data;

//            echo 'Error occurred while sending DTE: ' . $e->getMessage();
//            die();
        }
    }


}
