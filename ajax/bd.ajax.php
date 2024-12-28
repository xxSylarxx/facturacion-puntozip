<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorBD;;

class AjaxBD
{



    public function ajaxActualizarBD()
    {

        $respuesta = ControladorBD::ctrAgregarCampoTablas();
        echo $respuesta;
    }
}

if (isset($_POST['updateBd'])) {

    $objbSelva = new AjaxBD();
    $objbSelva->ajaxActualizarBD();
}
