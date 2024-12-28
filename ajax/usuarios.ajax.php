<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorUsuarios;
use Modelos\ModeloUsuarios;

class AjaxUsuarios
{
    //LOGIN USUARIOS
    public $ingUsuario;
    public $ingPassword;
    public function ajaxLogin()
    {
        $user = $this->ingUsuario;
        $pass = $this->ingPassword;
        // $recaptcha = $_POST["recaptcha"];
        @$token = $_POST["token"];
        @$conectar = $_POST["conectar"];
        $respuesta = ControladorUsuarios::ctrIngresoUsuario($user, $pass, $token, $conectar);
    }
    //AGREGAR USUARIOS
    public function ajaxAgregarUsuario()
    {

        if (isset($_POST["nuevoNombre"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"])) {

                $ruta = "";

                if (isset($_FILES["nuevaFoto"]["tmp_name"])) {

                    if (($_FILES["nuevaFoto"]["type"] == "image/jpeg") ||
                        ($_FILES["nuevaFoto"]["type"] == "image/jpg") ||
                        ($_FILES["nuevaFoto"]["type"] == "image/png")
                    ) {

                        //CARPETA DONDE SE GUARDARÁ LA IMAGEN

                        $directorio = "../vistas/img/usuarios/" . $_POST['nuevoUsuario'];
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0755, true);
                        }
                        $nombre_img = $_FILES['nuevaFoto']['name'];

                        $ruta = "vistas/img/usuarios/" . $_POST['nuevoUsuario'] . "/" . $nombre_img;

                        move_uploaded_file($_FILES['nuevaFoto']['tmp_name'], $directorio . "/" . $nombre_img);
                    }
                }

                $encriptar = crypt($_POST['nuevoPassword'], '$2a$07$usesomesillystringforsalt$');
                $datos = array(
                    'nombre' => $_POST["nuevoNombre"],
                    'usuario' => $_POST["nuevoUsuario"],
                    'password' => $encriptar,
                    'perfil' => $_POST["nuevoPerfil"],
                    'dni' => $_POST["nuevoDni"],
                    'email' => $_POST["nuevoEmail"],
                    "foto" => $ruta,
                    "id_sucursal" => $_POST['nuevaSucursal'],

                );

                $respuesta = ControladorUsuarios::ctrCrearUsuario($datos);
                echo $respuesta;
            } else {
                echo "<script>
                    Swal.fire({
                        title: '¡La contraseña no puede ir vacío o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                       // window.location = 'usuarios';
                        }
                    })</script>";
            }
        }
    }
    // EDITAR USARIO|
    public $idUsuario;

    public function ajaxEditarUsuario()
    {
        $item = 'id';
        $valor = $this->idUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
    }
    // ACTIVAR USUARIO
    public $activarUsuario;
    public $activarId;

    public function ajaxActivarUsuario()
    {

        $tabla = 'usuarios';
        $item1 = 'estado';
        $valor1 = $this->activarUsuario;
        $item2 = 'id';
        $valor2 = $this->activarId;

        $respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);
        echo $respuesta;
    }
    // VALIDAR NO REPETIR USUARIO
    public $validarUsuario;
    public function ajaxValidarUsuario()
    {

        $item = 'usuario';
        $valor = $this->validarUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
    }
    public $dni;
    public function ajaxBuscarDni()
    {
        $dni = $this->dni;
        $respuesta = ControladorUsuarios::ctrBuscarDni($dni);
    }

    public function ajaxCerrarSesion()
    {
        $item = 'id';
        $valor = $_SESSION['id'];
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        if ($respuesta['estado'] != 1) {
            echo 'ok';
        }
    }

    //ROLES DE USUARIOS
    public function ajaxCrearRol()
    {

        $datos = array(
            'rol' => trim($_POST['rolUsuario']),
        );

        $respuesta = ControladorUsuarios::ctrCrearRol($datos);
        echo $respuesta;
    }
    public function ajaxCrearAccesos()
    {

        $idRol = $_POST['idRol'];
        $datos = $_POST['accesoroles'];


        $respuesta = ControladorUsuarios::ctrCrearAccesos($datos, $idRol);
        echo $respuesta;
    }
    public function ajaxMostrarRoles()
    {
        $item = 'rol';
        $valor = $_POST['roldeusuario'];
        $respuesta = ControladorUsuarios::ctrMostrarRoles($item, $valor);
        echo json_encode($respuesta);
    }
    public function ajaxMostrarAccesosEdicion()
    {

        $item = 'id_rol';
        $valor = $_POST['rolid'];

        $accesos = ControladorUsuarios::ctrMostrarAccesosid($item, $valor);
        echo '<div class="custom-control custom-checkbox contenedor-roles-ch">';

        foreach ($accesos as $k => $a) {
            // echo $a['id_rol'];
            if ($a['activo'] == 's') {

                echo '<label class="custom-control-label  btn-editar-roles-acceso" idAcceso="' . $a['id'] . '" for="accesoroles' . $a['id'] . '"><input type="checkbox" class="custom-control-input" id="accesoroles' . $a['id'] . '" name="accesoroles[]" value="' . $a['linkacceso'] . '" checked>
                                               ' . $a['nombreacceso'] . '</label>';
            }
            if ($a['activo'] == 'n') {


                echo '<label class="custom-control-label btn-editar-roles-acceso" idAcceso="' . $a['id'] . '" for="accesoroles' . $a['id'] . '"><input type="checkbox" class="custom-control-input" id="accesoroles' . $a['id'] . '" name="accesoroles[]" value="' . $a['linkacceso'] . '">
                                            ' . $a['nombreacceso'] . '</label>';
            }
        }
        echo '</div>';
    }

    public function ajaxEditarAccesos()
    {

        $item = 'id';
        $valor = $_POST['idAcceso'];
        $datos = array(
            'activo' => $_POST['activo']
        );
        $respuesta = ControladorUsuarios::ctrEditarAccesos($item, $valor, $datos);
        echo $respuesta;
    }
    public function ajaxEliminarRoles()
    {

        $valor = $_POST['idRoldelete'];

        $roles = ControladorUsuarios::ctrEliminarRol($valor);
        echo $roles;
        $accesos = ControladorUsuarios::ctrEliminarAccesos($valor);
    }

    public function ajaxNuevoLink()
    {
        if (isset($_POST['nuevoacceso']) && strlen($_POST['nuevoacceso']) >= 3) {

            $item = 'id_rol';
            $valor = $_POST['idrol'];
            $valor2 = $_POST['nuevolink'];
            $respuestaAccesos = ControladorUsuarios::ctrMostrarAccesos($item, $valor,  $valor2);

            if ($respuestaAccesos) {
                echo  'ok';
            } else {
                $datos = array(
                    'id' => $_POST['idrol'],
                    'nuevoacceso' => trim(strtoupper($_POST['nuevoacceso'])),
                    'nuevolink' => trim(strtolower($_POST['nuevolink']))
                );
                $respuesta = ControladorUsuarios::ctrCrearLinkAccesos($datos);
            }
        } else {
            echo 'error';
        }
    }
}
//OBJETO LOGIN USUARIOS
if (isset($_POST['ingUsuario'])) {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $login = new ajaxUsuarios();
    $login->ingUsuario = test_input($_POST['ingUsuario']);
    $login->ingPassword = test_input($_POST['ingPassword']);
    $login->ajaxLogin();
}
// OBJETO AGREGAR USUARIO
if (isset($_POST['nuevoUsuario'])) {
    $nuevo = new AjaxUsuarios();
    $nuevo->ajaxAgregarUsuario();
}
// OBJETO EDITAR USUARIO
if (isset($_POST['idUsuario'])) {

    $editar = new AjaxUsuarios();
    $editar->idUsuario = $_POST['idUsuario'];
    $editar->ajaxEditarUsuario();
}
// OBJETO ACTIVAR USUARIO
if (isset($_POST['activarUsuario'])) {

    $activarUsuario = new AjaxUsuarios();
    $activarUsuario->activarId = $_POST['activarId'];
    $activarUsuario->activarUsuario = $_POST['activarUsuario'];
    $activarUsuario->ajaxActivarUsuario();
}
// OBJETO VALIDAR NO REPETIR USUARIO
if (isset($_POST['validarUsuario']) && !empty($_POST['validarUsuario'])) {
    $validarUsuario = new AjaxUsuarios();
    $validarUsuario->validarUsuario = $_POST['validarUsuario'];
    $validarUsuario->ajaxValidarUsuario();
}
// OBJETO VALIDAR NO REPETIR USUARIO
if (isset($_POST['dni'])) {
    $objDni = new AjaxUsuarios();
    $objDni->dni = $_POST['dni'];
    $objDni->ajaxBuscarDni();
}


if (isset($_POST['cerrarS'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxCerrarSesion();
}
if (isset($_POST['rolUsuario'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxCrearRol();
}
if (isset($_POST['accesoroles'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxCrearAccesos();
}
if (isset($_POST['roldeusuario'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxMostrarRoles();
}
if (isset($_POST['rolid'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxMostrarAccesosEdicion();
}
if (isset($_POST['idAcceso'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxEditarAccesos();
}
if (isset($_POST['idRoldelete'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxEliminarRoles();
}
if (isset($_POST['nuevoacceso'])) {
    $objCerras = new AjaxUsuarios();
    $objCerras->ajaxNuevoLink();
}





// SELECT empleados.nombre AS nombre_empleado, departamentos.nombre AS nombre_departamento, salarios.salario
// FROM empleados
// INNER JOIN departamentos ON empleados.departamento_id = departamentos.id
// INNER JOIN salarios ON empleados.id = salarios.empleado_id;
