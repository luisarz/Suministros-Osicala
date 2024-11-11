<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
@php
    $DTE=$data['respuestaHacienda'];
    $codigoGeneracion = $DTE['respuestaHacienda']['codigoGeneracion'];
    $selloRecepcion = $DTE['respuestaHacienda']['selloRecibido'];
    $numeroControl = $DTE['identificacion']['numeroControl'];
    $modeloFacturacion = $DTE['identificacion']['tipoModelo'];
    $tipoTransmision = $DTE['identificacion']['tipoOperacion'];
    $versionJSON = $DTE['identificacion']['version'];
    $fechaEmision = $DTE['identificacion']['fecEmi'];
    $horaEmision = $DTE['identificacion']['horEmi'];
    $documentoInterno ="00001";

@endphp
</body>
</html>