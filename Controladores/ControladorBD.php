<?php

namespace Controladores;

use Modelos\ModeloBD;
use api\ApiFacturacion;

class ControladorBD
{
    public static function ctrAgregarCampoTablas()
    {

        $campos = ModeloBD::mdlAgregarCampoTabla();
        return $campos;
    }
}
