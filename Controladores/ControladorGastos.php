<?php

namespace Controladores;

use Modelos\ModeloGastos;
date_default_timezone_set('America/Lima');
class ControladorGastos
{

    public static function ctrMostrarGastos($item, $valor)
    {

        $tabla = 'gastos';

        $respuesta = ModeloGastos::mdlMostrarGastos($tabla, $item, $valor);
        return $respuesta;
    }


    public static function ctrCrearGastos()
    {
        session_start();
        $item = 'id_usuario';
        $valor = $_SESSION['id'];
        $arqueocajas = ControladorCaja::ctrMostrarArqueoCajasid($item, $valor);
        if ($arqueocajas) {
            $valorapertura = 1;
        } else {
            $valorapertura = 0;
        }
        if (empty($_POST['montogasto']) || empty($_POST['descripciongasto'])) {
            echo "LLENE TODOS LOS CAMPOS";
            exit;
        }
        $tabla = 'gastos';

        $datos = array(
            'descripcion' => strtoupper($_POST['descripciongasto']),
            'monto' => $_POST['montogasto'],
            'fecha' => date('Y-m-d H:i:s'),
            'apertura' => $valorapertura,
            'id_usuario' => $_SESSION['id'],
        );
        $respuesta = ModeloGastos::mdlCrearGasto($tabla, $datos);

        return $respuesta;
    }
    public static function ctrEditarGastos()
    {
        if (empty($_POST['editarnombre']) || empty($_POST['editarnumero'])) {
            echo "LLENE TODOS LOS CAMPOS";
            exit;
        }
        $tabla = 'cajas';
        $fechamodifica = date("Y-m-d H:i:s");
        $datos = array(
            'id' => $_POST['idCajae'],
            'nombre' => $_POST['editarnombre'],
            'numero_caja' => $_POST['editarnumero'],
            'activo' => $_POST['cajaactiva'],
            'fecha_modifica' => $fechamodifica,
        );
        $respuesta = ModeloGastos::mdlEditarGastos($tabla, $datos);

        return $respuesta;
    }

public static function ctrMostrarEliminarGasto($valor){

    $tabla = 'gastos';
    $respuesta = ModeloGastos::mdlEliminarGasto($tabla, $valor);
    return $respuesta;
}



    public  function ctrListarGastos()
    {

        $content = ModeloGastos::mdlListarGastos();
        echo $content;
    }
}
