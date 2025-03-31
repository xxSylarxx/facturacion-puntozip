<?php
require_once "../bc/vendor/autoload.php";

use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorSVG;

# Crear generadores

# Crear generador
// TYPE_CODE_39
// TYPE_CODE_39_CHECKSUM
// TYPE_CODE_39E
// TYPE_CODE_39E_CHECKSUM
// TYPE_CODE_93
// TYPE_STANDARD_2_5
// TYPE_STANDARD_2_5_CHECKSUM
// TYPE_INTERLEAVED_2_5
// TYPE_INTERLEAVED_2_5_CHECKSUM
// TYPE_CODE_128
// TYPE_CODE_128_A
// TYPE_CODE_128_B
// TYPE_CODE_128_C
// TYPE_EAN_2
// TYPE_EAN_5
// TYPE_EAN_8
// TYPE_EAN_13
// TYPE_UPC_A
// TYPE_UPC_E
// TYPE_MSI
// TYPE_MSI_CHECKSUM
// TYPE_POSTNET
// TYPE_PLANET
// TYPE_RMS4CC
// TYPE_KIX
// TYPE_IMB
// TYPE_CODABAR
// TYPE_CODE_11
// TYPE_PHARMA_CODE
// TYPE_PHARMA_CODE_TWO_TRACKS
class AjaxBarCode
{


    public  function ajaxGenerarCodigoBarras()
    {
        $generadorDeHTML = new BarcodeGeneratorHTML();
        $generadorDePNG = new BarcodeGeneratorPNG();
        $generadorDeJPG = new BarcodeGeneratorJPG();
        $generadorDeSVG = new BarcodeGeneratorSVG();

        # Ajustes
        if(empty($_POST['codigobarra']) || $_POST['codigobarra'] == null){
            echo "NO CUENTA CON UN CÓDIGO";
        }else{
        $texto = $_POST['codigobarra'];
        $tipo = $generadorDePNG::TYPE_CODE_128;


        $bcGenerado = $generadorDeHTML->getBarcode($texto, $tipo, 3, 60);

        $ruta = __DIR__ . '/../vistas/img/productos/';
        $nombreArchivo = $_POST['codigobarra'];

        # Escribir los datos
        // $bytesEscritos = file_put_contents($ruta . $nombreArchivo, $bcGenerado);

        // # Comprobar si todo fue bien
        // if ($bytesEscritos !== false) {
        //     echo "Correcto. $nombreArchivo";
        // } else {
        //     echo "Error guardando código de barras";
        // }

        # Imprimir encabezados
        // header('Content-Type: application/octet-stream');
        // header("Content-Transfer-Encoding: Binary");
        // header("Content-disposition: attachment; filename=$nombreArchivo");

        echo '<div class="barcodehtml">'.$bcGenerado.'<div class"num">'.$nombreArchivo.'</div></div>';
        }
    }
}

$obj = new AjaxBarCode();
$obj->ajaxGenerarCodigoBarras();
