<?php

namespace Controladores;

use Modelos\ModeloCuentasBanco;

class ControladorCuentasBanco
{

   // MOSTRAR CUENTAS BANCO
   public static  function ctrMostrarCuentasBanco($item, $valor)
   {

      $tabla = "cuentas_banco";
      $respuesta = ModeloCuentasBanco::mdlMostrarCuentasBanco($tabla, $item, $valor);
      return $respuesta;
   }
   
   // MOSTRAR MEDIO DE PAGO
   public static  function ctrMostrarMediodePago($item, $valor)
   {

      $tabla = "medio_pago";
      $respuesta = ModeloCuentasBanco::mdlMostrarCuentasBanco($tabla, $item, $valor);
      return $respuesta;
   }


   public static function ctrGuardarNuevaCuentaBanco()
   {
      if (!empty($_POST['tipocuenta']) && !empty($_POST['nombrebanco']) && !empty($_POST['titular'])  && !empty($_POST['numerocuenta'])  && !empty($_POST['numerocci'])) {


         $tabla = "cuentas_banco";
         date_default_timezone_set('America/Lima');
         $datos = array(
            "moneda" => $_POST['monedacuenta'],
            "tipocuenta" => $_POST['tipocuenta'],
            "nombrebanco" => mb_strtoupper($_POST['nombrebanco']),
            "titular" =>  mb_strtoupper($_POST['titular']),
            "numerocuenta" => $_POST['numerocuenta'],
            'numerocci' => $_POST['numerocci'],
            'descripcion' => $_POST['cuentadescripcion']


         );
         $respuesta = ModeloCuentasBanco::mdlGuardarCuentasBanco($tabla, $datos);
         if ($respuesta == "ok") {

            echo "<script> 
            $('#formNuevaCuentaBanco').each(function(){
                            	this.reset();					
                           });
                 Swal.fire({
                     title: '¡La cuenta ha sido agregado correctamente!',
                     text: '...',
                     icon: 'success',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Cerrar'
                   }).then((result) => {
                     if (result.isConfirmed) {
                       
                       window.location = 'cuentas-bancarias';
                     }
                     if(window.history.replaceState){
                         window.history.replaceState(null,null, window.location.href);
                         }
                   })</script>";
         }
      }
   }
   public static function ctrEditarCuentasBanco()
   {
    if (!empty($_POST['etipocuenta']) && !empty($_POST['enombrebanco']) && !empty($_POST['etitular'])  && !empty($_POST['enumerocuenta'])  && !empty($_POST['enumerocci'])) {


         $tabla = "cuentas_banco";
         date_default_timezone_set('America/Lima');
         $datos = array(
            'id' => $_POST['idCuentaBanco'],
            "moneda" => $_POST['emonedacuenta'],
            "tipocuenta" => $_POST['etipocuenta'],
            "nombrebanco" => mb_strtoupper($_POST['enombrebanco']),
            "titular" =>  mb_strtoupper($_POST['etitular']),
            "numerocuenta" => $_POST['enumerocuenta'],
            'numerocci' => $_POST['enumerocci'],
            'descripcion' => $_POST['ecuentadescripcion']


         );
         $respuesta = ModeloCuentasBanco::mdlEditarCuentasBanco($tabla, $datos);
         if ($respuesta == "ok") {

            echo "<script> 
            $('#formEditarCuentaBanco').each(function(){
                            	this.reset();					
                           });
                 Swal.fire({
                     title: '¡Datos de la cuenta han sido actualiados correctamente!',
                     text: '...',
                     icon: 'success',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Cerrar'
                   }).then((result) => {
                     if (result.isConfirmed) {
                       
                       window.location = 'cuentas-bancarias';
                     }
                     if(window.history.replaceState){
                         window.history.replaceState(null,null, window.location.href);
                         }
                   })</script>";
         }
      }
   }


 
}
