<?php
session_start();
require_once "../../pdf/vendor/autoload.php";

//clases de acceso a datos
require_once("../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorCuentasBanco;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorUsuarios;
use Controladores\ControladorSunat;
use Spipu\Html2Pdf\Html2Pdf;

require_once(dirname(__FILE__) . "/../../Controladores/cantidad_en_letras.php");


$item = "id";
$valor = $_POST['idVenta'];

$venta = ControladorVentas::ctrMostrarVentas($item, $valor);


$item = "id";
$valor = $venta['id_sucursal'];
$sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);


$item = 'idenvio';
$valor = $venta['idbaja'];
$ticket = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

$item = "id";
$valor = $venta['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor = ControladorEmpresa::ctrEmisor();

$item = "idventa";
$valor = $venta['id'];
$detalle = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);



$valor = $venta['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

$item = 'id';
$valor = $venta['codvendedor'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

$item = 'id';
$valor = $venta['id_cuenta'];
$cuentaBanco = ControladorCuentasBanco::ctrMostrarCuentasBanco($item, $valor);

$item = "codigo";
$valor = $venta['metodopago'];
$metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item, $valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
if ($venta['codmoneda'] == 'PEN') {
    $nombreMoneda = "SOLES";
} else {
    $nombreMoneda = "DÓLARES";
}
$cantidad_en_letras = CantidadEnLetra($venta['total'], $nombreMoneda);




$todos = array(
    'detalles' => $detalle,
    "cliente" => $cliente,
    "venta" => $venta,
    "emisor" => $emisor,
    "sucursal" => $sucursal,
    "vendedor" => $vendedor,
    "comprobante" => $tipo_comprobante,
    "mediodepago" => $metodo_pago,
    "cuentabanco" => $cuentaBanco,
    "cantidadLetras" => $cantidad_en_letras,
);

$fields = http_build_query($todos);

echo json_encode($fields);
