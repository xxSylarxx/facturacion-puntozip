<?php

require_once "../vendor/autoload.php";

use Controladores\ControladorConductores;

class AjaxConductores
{
    public $idConductor;
    public $idEliminarConductor;

    public function ajaxEditarConductor()
    {
        $item = "id";
        $valor = $this->idConductor;
        $resultado = ControladorConductores::ctrMostrarConductores($item, $valor);
        echo json_encode($resultado);
    }

    public function ajaxCrearNuevoConductor()
    {
        $respuesta = ControladorConductores::ctrGuardarNuevoConductor();
    }

    public function ajaxUpdateConductor()
    {
        $resultado = ControladorConductores::ctrEditarConductor();
    }

        public function ajaxEliminarConductor()
        {
            $datos = $this->idEliminarConductor;
            $resultado = ControladorConductores::ctrEliminarConductor($datos); 
        }
}

if (isset($_POST['idConductor'])) {
    $objConductor = new AjaxConductores();
    $objConductor->idConductor = $_POST['idConductor'];
    $objConductor->ajaxEditarConductor();
}

if (isset($_POST['idConductorEditar'])) {
    $objConductor = new AjaxConductores();
    $objConductor->ajaxUpdateConductor();
}

if (isset($_POST['insertaConductor'])) {
    $objConductores = new AjaxConductores();
    $objConductores->ajaxCrearNuevoConductor();
}

if (isset($_POST['idEliminarConductor'])) {
    $eliminar = new AjaxConductores();
    $eliminar->idEliminarConductor = $_POST['idEliminarConductor'];
    $eliminar->ajaxEliminarConductor();
}