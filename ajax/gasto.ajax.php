<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorGastos;

class AjaxGasto
{
    public function ajaxCrearGasto()
    {


        $respuesta = ControladorGastos::ctrCrearGastos();
        echo $respuesta;
    }
    public function ajaxEditarGasto()
    {


        $respuesta = ControladorGastos::ctrEditarGastos();
        echo $respuesta;
    }

    public function ajaxEliminarGasto()
    {

        
        $valor = $_POST['idgastodelete'];

        $respuesta = ControladorGastos::ctrMostrarEliminarGasto($valor);
        echo $respuesta;
    }
}

if (isset($_POST['montogasto'])) {
    $gasto = new AjaxGasto();
    $gasto->ajaxCrearGasto();
}
if (isset($_POST['idgastodelete'])) {
    $gasto = new AjaxGasto();
    $gasto->ajaxEliminarGasto();
}
if (isset($_POST['idCajae'])) {
    $caja = new AjaxCajas();
    $caja->ajaxEditarCaja();
}
