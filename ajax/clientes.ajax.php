<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorClientes;
use Controladores\ControladorVentas;

class AjaxClientes
{

    public function ajaxCrearCliente()
    {

        $datosPost = $_POST;

        $respuesta = ControladorClientes::ctrCrearCliente($datosPost);
        echo $respuesta;
    }

    public function ajaxEditarCliente()
    {

        $datosPost = $_POST;
        // var_dump($datosPost);
        // exit();
        $respuesta = ControladorClientes::ctrEditarCliente($datosPost);
        echo $respuesta;
    }
    // TRAER LOS CLIENTES PARA  EDITAR
    public $idCliente;
    public function ajaxTraerCliente()
    {

        $item = "id";
        $valor = $this->idCliente;

        $resultado = ControladorClientes::ctrMostrarClientes($item, $valor);

        echo json_encode($resultado);
    }

    // ELIMINAR CLIENTE
    public $idEliminarCliente;
    public function ajaxEliminarCliente()
    {

        $item='codcliente';
        $valor = $this->idEliminarCliente;
        $ventas = ControladorVentas::ctrMostrarVentas($item, $valor);
        if(!empty($ventas)){
            echo 'ESTE CLIENTE NO PUEDE SER ELIMINADO PORQUE TIENE COMPROBANTES RELACIONADOS';
        }else{
        $datos = $this->idEliminarCliente;
        $resultado = ControladorClientes::ctrEliminarCliente($datos); 
        }

       
    }
    // BUSCAR RUC O DNI
    public $rucCliente;
    public $tipoDoc;
    public function ajaxBuscarRuc()
    {

        $numDoc = $this->rucCliente;
         $tipoDoc = $this->tipoDoc;
         $resultado = ControladorClientes::ctrBuscarRuc($numDoc, $tipoDoc);
    }
    // VER SI EXISTE EL CLIENTE EN LA BD
    public function ajaxExisteCliente($numDocumento)
    {
        if (strlen($numDocumento)  == 8) {
            $item = "documento";
        }
        if (strlen($numDocumento) > 8) {
            $item = "ruc";
        }
        $valor = $numDocumento;
        $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);
        echo json_encode($respuesta);
    }

    // BUSCAR CLIENTE PARA COMPROBANTE
    public function ajaxBuscarCliente($numeroDoc)
    {
        $valor = $numeroDoc;
        $respuesta = ControladorClientes::ctrBucarCliente($valor);
        // echo json_encode($respuesta);
        foreach ($respuesta as $k => $v) {
            if ($_POST['tipoDocumento'] == '6' and $v['ruc'] != '' and $v['activo'] != 'n') {

                echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add" idCliente="' . $v['id'] . '" > ' . $v['ruc'] . ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">' . $v['razon_social'] . '</b></a></legend>';
            } else {
                if ($_POST['tipoDocumento'] != '6' and $v['documento'] != '' and $v['activo'] != 'n') {

                    echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add" idCliente="' . $v['id'] . '" > ' . $v['documento'] . ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">' . $v['nombre'] . '</b></a></legend>';
                }
            }
        }
    }

    public function ajaxActivaDesactivaCliente()
    {
        $datos = array(
            "id" => $_POST['id_clientea'],
            "modo" => $_POST['activos']
        );
        $resultado = ControladorClientes::ctrActivaDesactivaCliente($datos);
    }
}

// CREAR CLIENTES
if (isset($_POST['nuevonumdoc'])) {

    $idCliente = new AjaxClientes();
    $idCliente->ajaxCrearCliente();
}
if (isset($_POST['editarnumdoc'])) {

    $idCliente = new AjaxClientes();
    $idCliente->ajaxEditarCliente();
}
// TRAER PARA EDITAR CLIENTES
if (isset($_POST['idCliente'])) {

    $idCliente = new AjaxClientes();
    $idCliente->idCliente = $_POST['idCliente'];
    $idCliente->ajaxTraerCliente();
}
// ELIMINAR CLIENTE
if (isset($_POST['idEliminarCliente'])) {

    $eliminar = new AjaxClientes();
    $eliminar->idEliminarCliente = $_POST['idEliminarCliente'];
    $eliminar->ajaxEliminarCliente();
}
// BUSCAR RUC CLIENTE
if (isset($_POST['rucCliente'])) {
    $rucCliente = new AjaxClientes();
    $rucCliente->rucCliente = trim($_POST['rucCliente']);
    $rucCliente->tipoDoc = $_POST['tipoDoc'];
    $rucCliente->ajaxBuscarRuc();
}
// SI EXISTE EL  CLIENTE
if (isset($_POST['numDocumento'])) {
    $existeCliente = new AjaxClientes();
    //$existeCliente->numDocumento = $_POST['numDocumento'];
    $existeCliente->ajaxExisteCliente(trim($_POST['numDocumento']));
}
// BUSCAR CLIENTE PARA COMPROBANTE
if (isset($_POST['numeroDoc'])) {
    $existeCliente = new AjaxClientes();
    //$existeCliente->numeroDoc = $_POST['numDocumento'];
    $existeCliente->ajaxBuscarCliente(trim($_POST['numeroDoc']));
}
if (isset($_POST['id_clientea'])) {
    $actDesa = new AjaxClientes();
    $actDesa->ajaxActivaDesactivaCliente();
}
