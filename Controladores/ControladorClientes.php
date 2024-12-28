<?php

namespace Controladores;

use Modelos\ModeloClientes;

class ControladorClientes
{

    // MOSTRAR CLIENTES
    public static  function ctrMostrarClientes($item, $valor)
    {

        $tabla = "clientes";
        $respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);
        return $respuesta;
    }


    // CREAR CLIENTE
    public  static function ctrCrearCliente($datosPost)
    {


        if (isset($_POST['nuevonumdoc']) && isset($_POST['nuevonombrerazon'])) {



            if ($_POST['nuevotipodoc'] == 1 && (strlen($_POST['nuevonumdoc']) < 8 || strlen($_POST['nuevonumdoc']) > 8)) {

                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Corregir...',
                text: '¡Debes ingresar un D.N.I. válido!'
                //footer: '<a href>Why do I have this issue?</a>'
            })
            </script>";
                exit();
            }


            if ($_POST['nuevotipodoc'] == 6 && (strlen($_POST['nuevonumdoc']) < 11  || strlen($_POST['nuevonumdoc']) > 11)) {

                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '¡Debes ingresar un R.U.C. válido!'
                //footer: '<a href>Why do I have this issue?</a>'
            })
            </script>";
                exit();
            }

            if (empty($_POST['nuevonombrerazon']) && strlen($_POST['nuevonombrerazon'] < 4)) {

                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `¡El campo nombre o razón social debe ser llenado según e tipo de documento que elijas`
                
            })
            </script>";
                exit();
            }


            $textodoc = $_POST['nuevotipodoc'] == 1 ? 'DNI' : 'RUC';
            $numdocu = $_POST['nuevotipodoc'] == 1 ? 'documento' : 'ruc';
            $item = $numdocu;
            $valor = $_POST['nuevonumdoc'];
            $vercliente = ControladorClientes::ctrMostrarClientes($item, $valor);

            if (!empty($vercliente)) {
                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `¡El cliente con el {$textodoc} {$_POST['nuevonumdoc']} ya está registrado!`
                
            })
            $('#nuevonumdoc').val('');
            </script>";
                exit();
            }










            if ($_POST['nuevotipodoc'] == 6) {
                $datos = array(
                    "nombre" => '',
                    "documento" => '',
                    "email" => $_POST['nuevoEmail'],
                    "telefono" => $_POST['nuevoTelefono'],
                    "direccion" => $_POST['nuevoDireccion'],
                    "ruc" => $_POST['nuevonumdoc'],
                    "razon_social" => $_POST['nuevonombrerazon'],
                    "fecha_nacimiento" => null
                );
            } else {
                $datos = array(
                    "nombre" => $_POST['nuevonombrerazon'],
                    "documento" => $_POST['nuevonumdoc'],
                    "email" => $_POST['nuevoEmail'],
                    "telefono" => $_POST['nuevoTelefono'],
                    "direccion" => $_POST['nuevoDireccion'],
                    "ruc" => '',
                    "razon_social" => '',
                    "fecha_nacimiento" => null
                );
            }

            // return false;

            $tabla = 'clientes';
            $respuesta = ModeloClientes::mdlCrearCliente($tabla, $datos);


            if ($respuesta == "ok") {
                return 'ok';
            }
        }
    }
    // EDITAR CLIENTE
    public static function ctrEditarCliente($datosPost)


    {
        if (isset($_POST['editarnumdoc']) && isset($_POST['editarnombrerazon'])) {

            $textodoc = $_POST['editartipodoc'] == 1 ? 'DNI' : 'RUC';
            $numdocu = $_POST['editartipodoc'] == 1 ? 'documento' : 'ruc';
            $item = $numdocu;
            $valor = $_POST['editarnumdoc'];
            $vercliente = ControladorClientes::ctrMostrarClientes($item, $valor);

            //     if (!empty($vercliente)) {
            //         echo "<script>
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Oops...',
            //     text: `¡El cliente con el {$textodoc} {$_POST['editarnumdoc']} ya está registrado!`

            // })
            // $('#editarnumdoc').val('');
            // </script>";
            //         exit();
            //     }



            if ($_POST['editartipodoc'] == 1 && (strlen($_POST['editarnumdoc']) < 8 || strlen($_POST['editarnumdoc']) > 8)) {

                echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Corregir...',
            text: '¡Debes ingresar un D.N.I. válido!'
            //footer: '<a href>Why do I have this issue?</a>'
        })
        </script>";
                exit();
            }

            if ($_POST['editartipodoc'] == 6 && (strlen($_POST['editarnumdoc']) < 11  || strlen($_POST['editarnumdoc']) > 11)) {

                echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Corregir...',
            text: '¡Debes ingresar un R.U.C. válido!'
            //footer: '<a href>Why do I have this issue?</a>'
        })
        </script>";
                exit();
            }

            if (empty($_POST['editarnombrerazon']) && strlen($_POST['editarnombrerazon'] < 4)) {

                echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Corregir...',
            text: `¡El campo nombre o razón social debe ser llenado`
            
        })
        </script>";
                exit();
            }



            if ($_POST['editartipodoc'] == 6) {
                $datos = array(
                    "id" => $_POST['id'],
                    "nombre" => '',
                    "documento" => '',
                    "email" => $_POST['editarEmail'],
                    "telefono" => $_POST['editarTelefono'],
                    "direccion" => $_POST['editarDireccion'],
                    "ruc" => $_POST['editarnumdoc'],
                    "razon_social" => $_POST['editarnombrerazon'],
                    "fecha_nacimiento" => null
                );
            } else {
                $datos = array(
                    "id" => $_POST['id'],
                    "nombre" => $_POST['editarnombrerazon'],
                    "documento" => $_POST['editarnumdoc'],
                    "email" => $_POST['editarEmail'],
                    "telefono" => $_POST['editarTelefono'],
                    "direccion" => $_POST['editarDireccion'],
                    "ruc" => '',
                    "razon_social" => '',
                    "fecha_nacimiento" => null
                );
            }

            // return false;

            $tabla = 'clientes';
            $respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);


            if ($respuesta == "ok") {
                return 'ok';
            }
        }
    }

    // ELIMINAR CLIENTE
    public static function ctrEliminarCliente($datos)
    {
        if (isset($datos)) {
            $tabla = "clientes";
            $respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);
            if ($respuesta == 'ok') {
                echo "success";
            } else {
                echo "error";
            }
        }
    }
    // LISTAR CLIENTES CON BUSCADOR
    public  function ctrListarClient()
    {

        $respuesta = ModeloClientes::mdlListarClientes();
        echo $respuesta;
    }
    // BUSCAR RUC Y DNI SUNAT - RENIEC
    public static  function ctrBuscarRuc($numDoc, $tipoDoc)
    {

        $respuesta = ModeloClientes::mdlBuscarRuc($numDoc, $tipoDoc);
        return $respuesta;
    }

    public static function ctrBucarCliente($valor)
    {
        $tabla = "clientes";
        $respuesta = ModeloClientes::mdlBuscarCliente($tabla, $valor);
        return $respuesta;
    }

    public static function ctrBuscarUbigeo()
    {
        $respuesta = ModeloClientes::mdlBuscarUbigeo();
        return $respuesta;
    }  
    public static function ctrBuscarUbigeoNombre($tabla, $item, $valor)
    {
        $respuesta = ModeloClientes::mdlBuscarUbigeoNombres($tabla, $item, $valor);
        return $respuesta;
    }

    // ACTIVAR Y DESACTIVAR  PRODUCTOS
    public static function ctrActivaDesactivaCliente($datos)
    {
        $tabla = "clientes";
        $respuesta = ModeloClientes::mdlActivaDesactivaCliente($tabla, $datos);
        return $respuesta;
    }
}
