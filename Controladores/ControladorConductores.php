<?php

namespace Controladores;

use Modelos\ModeloConductores;

class ControladorConductores
{
    public static function ctrMostrarConductores($item, $valor) {
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
       if (!empty($_POST['nuevoRuc']) && !empty($_POST['nuevaRazon'])) {
          $tabla = "proveedores";
          date_default_timezone_set('America/Lima');
          $datos = array(
             "docIdentidad" => $_POST['nuevoRuc'],
             "razon_social" => $_POST['nuevaRazon'],
             "direccion" => $_POST['nuevaDireccion'],
             "email" => $_POST['nuevoEmail'],
             "ubigeo" => $_POST['nuevoUbigeo'],
             'telefono' => $_POST['nuevoTelefono'],
             'fecha_registro' => date("Y-m-d H:i:s")
          );
          $respuesta = ModeloConductores::mdlGuardarConductor($tabla, $datos);
          if ($respuesta == "ok") {
             echo "<script> 
             $('#formNuevoProveedor').each(function(){
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
    public static function ctrEditarProveedor()
    {
       if (isset($_POST['editarRuc']) && isset($_POST['editarRazon'])) {
 
 
          $tabla = "proveedores";
          date_default_timezone_set('America/Lima');
          $datos = array(
             "id" => $_POST['idProveedorEditar'],
             "docIdentidad" => $_POST['editarRuc'],
             "razon_social" => $_POST['editarRazon'],
             "direccion" => $_POST['editarDireccion'],
             "email" => $_POST['editarEmail'],
             "ubigeo" => $_POST['editarUbigeo'],
             'telefono' => $_POST['editarTelefono'],
             'fecha_registro' => date("Y-m-d H:i:s")
 
 
          );
          $respuesta = ModeloConductores::mdlEditarConductor($datos);
          if ($respuesta == "ok") {
 
             echo "<script> 
             $('#formEditarProveedor').each(function(){
                                 this.reset();					
                            });
                  Swal.fire({
                      title: '¡Datos del proveedor han sido actualiados correctamente!',
                      text: '...',
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Cerrar'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        
                        window.location = 'proveedores';
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
}