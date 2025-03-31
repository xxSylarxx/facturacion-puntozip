<?php
require_once "../vendor/autoload.php";



use Controladores\ControladorClientes;
use Controladores\ControladorSucursal;

class AjaxSucursal
{



    public function ajaxUbigeos()
    {

        $tabla = 'ubigeo_distrito';
        $item = "id";
        $valor = $_POST['idubigeo'];
        $distrito = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);

        $tabla = 'ubigeo_provincia';
        $item = "id";
        $valor = $distrito['province_id'];
        $provincia = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);

        $tabla = 'ubigeo_departamento';
        $item = "id";
        $valor = $distrito['department_id'];
        $departamento = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);

        $datos = array(
            'departamento' => $departamento['name'],
            'provincia' => $provincia['nombre_provincia'],
            'distrito' => $distrito['nombre_distrito']
        );

        echo json_encode($datos);
    }

    public function ajaxGuardarSucursal()
    {

        $datos = $_POST;


        if ((empty($datos['codigo']) && strlen($datos['codigo'] <= 3))  || (empty($datos['nombre']) && strlen($datos['nombre'] < 6)) || (empty($datos['direccion']) && strlen($datos['direccion'] < 8)) || (empty($datos['ubigeo']) && strlen($datos['ubigeo'] < 5)) || (empty($datos['telefono']) && strlen($datos['telefono'] < 9)) || (empty($datos['correo']) && strlen($datos['correo'] < 6))) {
            echo "TODOS LOS CAMPOS SON OBLIGATORIOS";
            exit();
        }

        $item = 'codigo';
        $valor = $datos['codigo'];
        $codigo = ControladorSucursal::ctrSucursalPrincipal($item, $valor);

        if (!empty($codigo)) {
            echo "EL CÓDIGO  SUCURSAL DE SUNAT YA SE ENCUENTRA EN USO";
            exit();
        }
        $item = 'serie';
        $valor = $datos['serie'][0];
        $series = ControladorSucursal::ctrMostrarSerie($item, $valor);
        if (!empty($series)) {
            echo "LAS SERIES YA STÁN EN USO";
            exit();
        }


        foreach ($datos['serie'] as $value) {
            if (empty($value) && strlen($value < 5)) {
                echo "INGRSE TODAS LAS SERIES Y QUE NO SEAN MENORES A 5 CARACTERES";
                exit();
            }
        }
        foreach ($datos['ccorrelativo'] as $values) {
            if ($values == '') {
                echo "INGRSE TODAS LOS CORRELATIVOS";
                exit();
            }
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = mb_strtoupper($data);
            return $data;
        }
        $datosS = array(
            'id_empresa' => $datos['id_empresa'],
            'codigo' => $datos['codigo'],
            'nombre_sucursal' => test_input($datos['nombre']),
            'direccion' => test_input($datos['direccion']),
            'pais' => test_input($datos['pais']),
            'departamento' => test_input($datos['departamento']),
            'provincia' => test_input($datos['provincia']),
            'distrito' => test_input($datos['distrito']),
            'ubigeo' => test_input($datos['ubigeo']),
            'telefono' => test_input($datos['telefono']),
            'correo' => test_input($datos['correo']),
        );

        // var_dump($datosS);
        // exit();

        $respuesta = ControladorSucursal::ctrCrearSucursal($datosS);


        $serie = ControladorSucursal::ctrCrearSerie($datos);
        echo $respuesta;
    }

    public function ajaxTraerSucursal()
    {


        $item = 'id';
        $valor = $_POST['idSucursal'];
        $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
        echo json_encode($sucursal, JSON_UNESCAPED_UNICODE);
    }

    public function ajaxEditarSucursal()
    {

        $datosA = $_POST;
        $datosU = array_map('mb_strtoupper', $datosA);
        $datos = array_map('trim', $datosU);
        if ((empty($datos['ecodigo']) && strlen($datos['ecodigo'] <= 3))  || (empty($datos['enombre']) && strlen($datos['enombre'] < 6)) || (empty($datos['edireccion']) && strlen($datos['edireccion'] < 8)) || (empty($datos['eubigeo']) && strlen($datos['eubigeo'] < 5)) || (empty($datos['etelefono']) && strlen($datos['etelefono'] < 9)) || (empty($datos['ecorreo']) && strlen($datos['ecorreo'] < 6))) {
            echo "TODOS LOS CAMPOS SON OBLIGATORIOS";
            exit();
        }


        $respuesta = ControladorSucursal::ctrEditarSucursales($datos);
        echo $respuesta;
    }

    public function ajaxActivarSucursal()
    {

        $datos = array(
            'id' => $_POST['idSu'],
            'activo' => $_POST['result']
        );

        $resultado = ControladorSucursal::ctrActivarSucursal($datos);
        echo $resultado;
    }

   
}

if (isset($_POST['idubigeo'])) {

    $objCodigo = new AjaxSucursal();
    $objCodigo->ajaxUbigeos();
}

if (isset($_POST['id_empresa'])) {

    $objCodigo = new AjaxSucursal();
    $objCodigo->ajaxGuardarSucursal();
}
if (isset($_POST['idSucursal'])) {

    $objCodigo = new AjaxSucursal();
    $objCodigo->ajaxTraerSucursal();
}
if (isset($_POST['eidsucursal'])) {

    $objCodigo = new AjaxSucursal();
    $objCodigo->ajaxEditarSucursal();
}
if (isset($_POST['idSu'])) {

    $objCodigo = new AjaxSucursal();
    $objCodigo->ajaxActivarSucursal();
}
