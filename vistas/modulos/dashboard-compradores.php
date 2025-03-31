<?php
session_start();
require_once("../../vendor/autoload.php");
date_default_timezone_set('America/Lima');
use Controladores\ControladorVentas;
use Controladores\ControladorClientes;
use Controladores\ControladorReportes;
use Controladores\ControladorSucursal;

$sucursal = ControladorSucursal::ctrSucursal();
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
if (isset($_POST['fechaInicial'])) {
    $fechaini = $_POST['fechaInicial'];
    $fechafin = $_POST['fechaFinal'];
    $fechai = str_replace('/', '-', $fechaini);
    $fechaInicial = date('Y-m-d', strtotime($fechai));

    $fechaf = str_replace('/', '-', $fechafin);
    $fechaFinal = date('Y-m-d', strtotime($fechaf));

    $tabla = 'venta';
    $ventas = ControladorReportes::ctrReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal, $id_sucursal);
} else {
    $fechaInicial = null;
    $fechaFinal = null;
    $tabla = 'venta';
    $ventas = ControladorReportes::ctrReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal, $id_sucursal);
}
$item = null;
$valor = null;
$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

$arrayClientes = array();
$arrayListaClientes = array();
foreach ($ventas as $key => $valVentas) {

    foreach ($clientes as $key => $valClientes) {
        if ($valClientes['id'] == $valVentas['codcliente']) {
            $cliente = $valClientes['ruc'] != null ? $valClientes['razon_social'] : $valClientes['nombre'];
            array_push($arrayClientes, $cliente);
            $arrayListaClientes = array(
                $cliente => $valVentas['total']
            );

            foreach ($arrayListaClientes as $key => $value) {
                @$sumaTotalClientes[$key] += $value;
            }
        }
    }
}
$noRepetirNombres = array_unique($arrayClientes);
?>
<div class="box box-default contenedor-pie-chart">
    <div class="box-header with-border">
        <h3 class="box-title">COMPRADORES</h3>

        <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="box-body chart-responsive">
                    <div class="chart" id="bar-chart1" style="height: 250px;"></div>
                </div>
                <!-- ./chart-responsive -->
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>

    <!-- /.footer -->
</div>
<!-- /.box -->


<script>
    $(document).ready(function() {
        //BAR CHART
        var bar = new Morris.Bar({
            element: 'bar-chart1',
            resize: true,
            data: [
                <?php
                foreach ($noRepetirNombres as $value) {
                    echo " {y: `$value`,
        a: '" . $sumaTotalClientes[$value] . "'},";
                }

                ?>
            ],
            barColors: ['#8dbdeb', '#fff000'],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['ventas'],
            preUnits: 'S/',
            hideHover: 'auto'
        });
    })
</script>