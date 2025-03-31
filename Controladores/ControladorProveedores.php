<?php

namespace Controladores;

use Modelos\ModeloProveedores;

class ControladorProveedores
{

   // MOSTRAR PROVEEDORES
   public static  function ctrMostrarProveedores($item, $valor)
   {

      $tabla = "proveedores";
      $respuesta = ModeloProveedores::mdlMostrarProveedores($tabla, $item, $valor);
      return $respuesta;
   }

   public static function ctrGuardarProveedor($datos)
   {
      $tabla = "proveedores";

      $respuesta = ModeloProveedores::mdlGuardarProveedor($tabla, $datos);
      return $respuesta;
   }
   public static function ctrGuardarNuevoProveedor()
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
         $respuesta = ModeloProveedores::mdlGuardarProveedor($tabla, $datos);
         if ($respuesta == "ok") {

            echo "<script> 
            $('#formNuevoProveedor').each(function(){
                            	this.reset();					
                           });
                 Swal.fire({
                     title: '¡El proveedor ha sido agregado correctamente!',
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
         $respuesta = ModeloProveedores::mdlEditarProveedor($tabla, $datos);
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
   // BUSCAR RUC Y DNI SUNAT - RENIEC
   //  public static  function ctrBuscarRuc($numDoc,$tipoDoc){

   //      $respuesta = ModeloProveedores::mdlBuscarRuc($numDoc,$tipoDoc);
   //      return $respuesta;

   //  }

   public static function ctrBucarProveedor($valor)
   {
      $tabla = "proveedores";
      $respuesta = ModeloProveedores::mdlBuscarProveedor($tabla, $valor);
      return $respuesta;
   }
}
