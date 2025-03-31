<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorProveedores;
use Controladores\ControladorClientes;

class AjaxProveedores
{

    public $idProveedor;
    public function ajaxEditarProveedor()
    {

        $item = "id";
        $valor = $this->idProveedor;

        $resultado = ControladorProveedores::ctrMostrarProveedores($item, $valor);

        echo json_encode($resultado);
    }
    public function ajaxUpdateProveedor()
    {
        $resultado = ControladorProveedores::ctrEditarProveedor();
    }
    // BUSCAR RUC O DNI
    public $rucProveedor;
    public $tipoDoc;
    public function ajaxBuscarRuc()
    {

        $numDoc = $this->rucProveedor;
        $tipoDoc = $this->tipoDoc;
        $resultado = ControladorClientes::ctrBuscarRuc($numDoc, $tipoDoc);
    }
    // VER SI EXISTE EL CLIENTE EN LA BD
    public function ajaxExisteProveedor($numDocumento)
    {
        if (strlen($numDocumento)  == 8) {
            $item = "documento";
        }
        if (strlen($numDocumento) > 8) {
            $item = "ruc";
        }
        $valor = $numDocumento;
        $respuesta = ControladorProveedores::ctrMostrarProveedores($item, $valor);
        echo json_encode($respuesta);
    }

    // BUSCAR CLIENTE PARA COMPROBANTE
    public function ajaxBuscarProveedor($numeroDoc)
    {
        $valor = $numeroDoc;
        $respuesta = ControladorProveedores::ctrBucarProveedor($valor);
        // echo json_encode($respuesta);
        foreach ($respuesta as $k => $v) {
            if ($_POST['tipoDocumentoP'] == '1') {
                echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add-p" idProveedor="' . $v['id'] . '" >' . ++$k . '. ' . $v['documento'] . ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">' . $v['nombre'] . '</b></a></legend>';
            } else {
                echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add-p" idProveedor="' . $v['id'] . '" >' . ++$k . '. ' . $v['ruc'] . ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">' . $v['razon_social'] . '</b></a></legend>';
            }
        }
    }

    public function ajaxCrearNuevoProveedor()
    {
        $respuesta = ControladorProveedores::ctrGuardarNuevoProveedor();
    }
}

// EDITAR PROVEEDOR
if (isset($_POST['idProveedor'])) {

    $idProveedor = new AjaxProveedores();
    $idProveedor->idProveedor = $_POST['idProveedor'];
    $idProveedor->ajaxEditarProveedor();
}
// EDITAR PROVEEDOR
if (isset($_POST['idProveedorEditar'])) {

    $idProveedor = new AjaxProveedores();
    $idProveedor->ajaxUpdateProveedor();
}
// BUSCAR RUC Proveedor
if (isset($_POST['rucProveedor'])) {
    $rucProveedor = new AjaxProveedores();
    $rucProveedor->rucProveedor = trim($_POST['rucProveedor']);
    $rucProveedor->tipoDoc = $_POST['tipoDoc'];
    $rucProveedor->ajaxBuscarRuc();
}
// SI EXISTE EL  Proveedor
if (isset($_POST['numDocumentoP'])) {
    $existeProveedor = new AjaxProveedores();
    //$existeProveedor->numDocumento = $_POST['numDocumento'];
    $existeProveedor->ajaxExisteProveedor(trim($_POST['numDocumentoP']));
}
// BUSCAR Proveedor PARA COMPROBANTE
if (isset($_POST['numeroDocP'])) {
    $existeProveedor = new AjaxProveedores();
    //$existeProveedor->numeroDoc = $_POST['numDocumento'];
    $existeProveedor->ajaxBuscarProveedor(trim($_POST['numeroDocP']));
}
if (isset($_POST['insertaProveedor'])) {
    $objProveedores = new AjaxProveedores();
    $objProveedores->ajaxCrearNuevoProveedor();
}
