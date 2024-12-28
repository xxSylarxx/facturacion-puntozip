<?php
session_start();
require_once("../../vendor/autoload.php");

use Controladores\ControladorSucursal;

$sucursal = ControladorSucursal::ctrSucursal();

use Controladores\ControladorReportes;

@$selectSucursal = $_POST['selectSucursal'];


if ($_SESSION['perfil'] == 'Administrador') {
  if (isset($selectSucursal) && !empty($selectSucursal)) {
    $id_sucursal = "id_sucursal =  $selectSucursal  AND";
  } else {
    $id_sucursal = '';
  }
} else {
  $id_sucursal = "id_sucursal = " . $sucursal['id'] . " AND";
}
// widgets-----------
if (isset($_POST['fechaInicial'])) {
  $fechaini = $_POST['fechaInicial'];
  $fechafin = $_POST['fechaFinal'];
  $fechai = str_replace('/', '-', $fechaini);
  $fechaInicial = date('Y-m-d', strtotime($fechai));

  $fechaf = str_replace('/', '-', $fechafin);
  $fechaFinal = date('Y-m-d', strtotime($fechaf));

  $tabla = 'venta';
  $comprobantes = ControladorReportes::ctrReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal, $id_sucursal);
} else {
  $fechaInicial = null;
  $fechaFinal = null;
  $tabla = 'venta';
  $comprobantes = ControladorReportes::ctrReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal, @$id_sucursal);
}
//  var_dump($comprobantes);
$arrayFechas = array();
$arrayVentas = array();
$sumaMes = array();
foreach ($comprobantes as $key => $value) {

  $fecha = substr($value['fecha_emision'], 0, 7);
  array_push($arrayFechas, $fecha);

  $arrayVentas = array($fecha => $value['total']);


  foreach ($arrayVentas as $key => $value) {

    @$sumaMes[$key] += $value;
  }
}

$noRepetirFechas = array_unique($arrayFechas);
?>
<div class="chart" id="revenue-chart" style="height: 300px;"></div>


<script>
  // AREA CHART

  var area = new Morris.Area({
    element: 'revenue-chart',
    resize: true,
    data: [
      <?php
      if ($noRepetirFechas != null) {
        foreach ($noRepetirFechas as $key) {
          echo "{y: '" . $key . "', ventas: " . $sumaMes[$key] . "},";
        }
        echo "{y: '" . $key . "', ventas: " . $sumaMes[$key] . "}";
      } else {
        echo "{y: '0', ventas: '0'},";
      }
      ?>
    ],
    xkey: 'y',
    ykeys: ['ventas'],
    labels: ['ventas'],
    lineColors: ['#8FCBE0', '#3c8dbc'],
    hideHover: 'auto',
    preUnits: 'S/ '
  });
</script>