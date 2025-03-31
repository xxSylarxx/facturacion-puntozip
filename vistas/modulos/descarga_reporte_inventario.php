<?php

require_once("../../vendor/autoload.php");

use Controladores\ControladorInventarios;

$ojReporte = ControladorInventarios::ctrDescargaReporteInventarioExcel();
