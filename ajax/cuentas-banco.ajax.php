<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorCuentasBanco;

class AjaxCuentasBanco
{

    public $idCuentaBanco;
    public function ajaxEditarCuentaBanco()
    {

        $item = "id";
        $valor = $this->idCuentaBanco;

        $resultado = ControladorCuentasBanco::ctrMostrarCuentasBanco($item, $valor);

        echo json_encode($resultado);
    }
    public function ajaxUpdateCuentaBanco()
    {
        $resultado = ControladorCuentasBanco::ctrEditarCuentasBanco();
    }



    public function ajaxCrearNuevaCuentaBanco()
    {
        $respuesta = ControladorCuentasBanco::ctrGuardarNuevaCuentaBanco();
    }
}

// EDITAR CUENTA BANCO
if (isset($_POST['idCuentaB'])) {

    $idCuenta = new AjaxCuentasBanco();
    $idCuenta->idCuentaBanco = $_POST['idCuentaB'];
    $idCuenta->ajaxEditarCuentaBanco();
}
// EDITAR CUENTA BANCO
if (isset($_POST['emonedacuenta'])) {

    $idCuenta = new AjaxCuentasBanco();
    $idCuenta->ajaxUpdateCuentaBanco();
}


if (isset($_POST['monedacuenta'])) {
    $objCuenta = new AjaxCuentasBanco();
    $objCuenta->ajaxCrearNuevaCuentaBanco();
}
