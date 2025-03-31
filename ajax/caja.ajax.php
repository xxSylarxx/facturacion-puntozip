<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorCaja;
use Controladores\ControladorUsuarios;

class AjaxCajas
{
    public function ajaxCrearCaja()
    {


        $respuesta = ControladorCaja::ctrCrearCaja();
        echo $respuesta;
    }
    public function ajaxEditarCaja()
    {


        $respuesta = ControladorCaja::ctrEditarCaja();
        echo $respuesta;
    }

    public function ajaxDatosCaja()
    {

        $item = 'id';
        $valor = $_POST['idCaja'];

        $respuesta = ControladorCaja::ctrMostrarCajas($item, $valor);
        echo json_encode($respuesta);
    }

    //ARQUEO DE CAJAS=====================================================
    public function ajaxAperturaCaja()
    {


        $respuesta = ControladorCaja::ctrAperturaCaja();
        echo $respuesta;
    }

    public function ajaxMostrarArqueoCaja()
    {


        $item = 'id';
        $valor = $_POST['idcajaabierta'];

        $respuesta = ControladorCaja::ctrMostrarArqueoCajas($item, $valor);
        if ($respuesta['estado'] == 1) {
            echo json_encode($respuesta);
        }
    }
    public function ajaxMostrarArqueoCierreCaja()
    {
        $gastos = 0;
        $egresos = 0;
        $item = 'id';
        $valor = $_POST['idCierreCaja'];

        $respuesta = ControladorCaja::ctrMostrarArqueoCajas($item, $valor);
        if ($respuesta['estado'] == 1) {
            $respuesta = $respuesta;
            $id_usuario = $respuesta['id_usuario'];

            $where = "WHERE (anulado = 'n' && feestado <> 2) && codvendedor = $id_usuario && apertura = 1";
            $ventas = ControladorCaja::ctrTotalVentas($where);

            $where = "WHERE anulado && codvendedor = $id_usuario && apertura = 1";
            $compras = ControladorCaja::ctrTotalCompras($where);

            $where = "WHERE (anulado = 'n' && feestado <> 2) && codvendedor = $id_usuario && apertura = 1";
            $toralRegistrosventas = ControladorCaja::ctrTotalRegistrosVentas($where);


            $where = "WHERE id_usuario = $id_usuario && apertura = 1";
            $toralGastos = ControladorCaja::ctrTotalGastos($where);
            $gastos = $toralGastos['total'];


            $tabla = 'nota_credito';
            $where = "WHERE feestado <> 2 && id_usuario = $id_usuario && apertura = 1";
            $totalnotacre = ControladorCaja::ctrTotalNotas($tabla, $where);
            $tabla = 'nota_debito';
            $where = "WHERE feestado <> 2 && id_usuario = $id_usuario && apertura = 1";
            $totalnotade = ControladorCaja::ctrTotalNotas($tabla, $where);

            $totalnotacredito = $totalnotacre['total'] != null ? $totalnotacre['total'] : 0;
            $totalnotadebito = $totalnotade['total'] != null ? $totalnotade['total'] : 0;

            $item = 'id';
            $valor = $respuesta['id_caja'];
            $caja = ControladorCaja::ctrMostrarCajas($item, $valor);


            $item = 'id';
            $valor = $respuesta['id_usuario'];
            $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

            $egresos =  $compras['total'];
            $gastos = $gastos == null ? 0 : $gastos;
            $monto_final = round(($respuesta['monto_inicial'] + $ventas['total'] - $totalnotacredito + $totalnotadebito) - $gastos, 2);
            $monto_ventas = round($ventas['total'] != null ? ($ventas['total']  + $totalnotadebito) - $totalnotacredito : 0, 2);
            $datos = array(
                'id' => $respuesta['id'],
                'caja' => $caja['nombre'] . ' - ' . $caja['numero_caja'],
                'monto_inicial' => $respuesta['monto_inicial'],
                'monto_final' => $monto_final,
                'monto_ventas' => $monto_ventas,
                'total_ventas' => $toralRegistrosventas[0],
                'egresos' => $egresos == null ? 0 : $egresos,
                'gastos' => $gastos,
                'usuario' => $usuarios['perfil'],
                'id_usuario' => $respuesta['id_usuario']


            );
            echo json_encode($datos);
        }
    }
    public function ajaxCierreCaja()
    {


        $respuesta = ControladorCaja::ctrCierreCaja();
        echo $respuesta;
    }
}

if (isset($_POST['nuevonombre'])) {
    $caja = new AjaxCajas();
    $caja->ajaxCrearCaja();
}
if (isset($_POST['idCaja'])) {
    $caja = new AjaxCajas();
    $caja->ajaxDatosCaja();
}
if (isset($_POST['idCajae'])) {
    $caja = new AjaxCajas();
    $caja->ajaxEditarCaja();
}
if (isset($_POST['cajaid'])) {
    $caja = new AjaxCajas();
    $caja->ajaxAperturaCaja();
}
if (isset($_POST['idcajaabierta'])) {
    $caja = new AjaxCajas();
    $caja->ajaxMostrarArqueoCaja();
}
if (isset($_POST['idCierreCaja'])) {
    $caja = new AjaxCajas();
    $caja->ajaxMostrarArqueoCierreCaja();
}
if (isset($_POST['idCajaCierre'])) {
    $caja = new AjaxCajas();
    $caja->ajaxCierreCaja();
}
