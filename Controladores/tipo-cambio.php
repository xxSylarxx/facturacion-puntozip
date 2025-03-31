<?php
if (isset($_POST['tipo_cambio'])) {
  $fecha = date("Y-m-d", strtotime($_POST['fecha'] . "- 1 days"));
  $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

  // Iniciar llamada a API
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' . $fecha,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $token
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  // Datos listos para usar
  $empresa = json_decode($response);
  // var_dump($empresa);



  if (isset($empresa->compra)) {
    $datos = array(
      'compra' => $empresa->compra,
      'venta' => $empresa->venta,

    );
    echo json_encode($datos);
  } else {
    $result = json_decode(file_get_contents('https://www.deperu.com/api/rest/cotizaciondolar.json'), true);
    // var_dump($result);
    $compra = number_format($result['Cotizacion'][0]['Compra'], 3);
    $venta = number_format($result['Cotizacion'][0]['Venta'], 3);
    $datos = array(
      'compra' =>  $compra,
      'venta' =>   $venta,
    );

    echo json_encode($datos);
  }
}
