<?php

namespace Controladores;

use Modelos\ModeloConductores;

class ControladorConductores
{
   public static function ctrMostrarConductores($item, $valor)
   {
      $respuesta = ModeloConductores::mdlMostrarConductores($item, $valor);
      return $respuesta;
   }

   public static function ctrGuardarConductor($datos)
   {
      $respuesta = ModeloConductores::mdlGuardarConductor($datos);
      return $respuesta;
   }

   public static function ctrGuardarNuevoConductor()
   {
      if (!empty($_POST['nuevoDNI'])) {
         date_default_timezone_set('America/Lima');
         $datos = array(
            'tipdoc' => $_POST['nuevoTipoDoc'],
            "numdoc" => $_POST['nuevoDNI'],
            "nombres"   => mb_strtoupper($_POST['nuevoNombre'], 'UTF-8'),
            "apellidos" => mb_strtoupper($_POST['nuevoApellidos'], 'UTF-8'),
            "numbrevete" => $_POST['nuevoNumBrevete'],
            'numplaca' => $_POST['nuevoNumPlaca'],
            'marca_vehiculo' => $_POST['nuevoMarcaVehiculo']
         );
         $respuesta = ModeloConductores::mdlGuardarConductor($datos);
         if ($respuesta == "ok") {
            echo "<script> 
             $('#formNuevoConductor').each(function(){
                                 this.reset();					
                            });
                  Swal.fire({
                      title: '¡El conductor ha sido agregado correctamente!',
                      text: '...',
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Cerrar'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        
                        window.location = 'conductores';
                      }
                      if(window.history.replaceState){
                          window.history.replaceState(null,null, window.location.href);
                          }
                    })</script>";
         }
      }
   }

   public static function ctrEditarConductor()
   {
      if (!empty($_POST['editarDNI'])) {
         date_default_timezone_set('America/Lima');
         $datos = array(
            "id" => $_POST['idConductorEditar'],
            'tipdoc' => $_POST['editarTipoDoc'],
            "numdoc" => $_POST['editarDNI'],
            "nombres"   => mb_strtoupper($_POST['editarNombre'], 'UTF-8'),
            "apellidos" => mb_strtoupper($_POST['editarApellidos'], 'UTF-8'),
            "numbrevete" => $_POST['editarNumBrevete'],
            'numplaca' => $_POST['editarNumPlaca'],
            'marca_vehiculo' => $_POST['editarMarcaVehiculo']
         );
         $respuesta = ModeloConductores::mdlEditarConductor($datos);
         if ($respuesta == "ok") {
            echo "<script> 
             $('#formEditarConductor').each(function(){
                                 this.reset();					
                            });
                  Swal.fire({
                      title: '¡Datos del conductor han sido actualizados correctamente!',
                      text: '...',
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Cerrar'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        window.location = 'conductores';
                      }
                      if(window.history.replaceState){
                        window.history.replaceState(null,null, window.location.href);
                      }
                    })</script>";
         }
      }
   }

   public static function ctrBucarConductor($valor)
   {
      $respuesta = ModeloConductores::mdlBuscarConductor($valor);
      return $respuesta;
   }

   public static function ctrBucarConductorId($idConductor)
   {
      $respuesta = ModeloConductores::mdlMostrarConductorId($idConductor);
      return $respuesta;
   }

   public static function ctrEliminarConductor($datos)
   {
      if (isset($datos)) {
         $respuesta = ModeloConductores::mdlEliminarConductor($datos);
         if ($respuesta == 'ok') {
            echo "success";
         } else {
            echo "error";
         }
      }
   }
}
