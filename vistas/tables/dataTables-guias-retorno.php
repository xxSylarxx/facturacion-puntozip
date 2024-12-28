<?php
session_start();

require_once("../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorSucursal;



class DataTablesGuiasRetorno
{


    public function dtaListarGuiasRetorno()
    {

        $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
        if ($action == 'ajax') {
            // escaping, additionally removing everything that could be (html/javascript-) code
            $sucursal = ControladorSucursal::ctrSucursal();
            // RUTAS DE CDR Y XML 
            $ruta_xml = "xml";
            $ruta_cdr = "cdr";


            $searchGuias = $_GET['searchGuiasRe'];
            $selectnum = $_GET['selectnum'];
            $fechaInicial = $_GET['fechaInicial'];
            $fechaFinal = $_GET['fechaFinal'];
            $selectSucursal = $_GET['selectSucursal'];
            $aColumns = array('serie', 'correlativo'); //Columnas de busqueda
            $sTable = 'guia';
            $sWhere = "";


            if ($_SESSION['perfil'] == 'Administrador') {
                if (
                    isset($selectSucursal) && !empty($selectSucursal)
                ) {
                    $id_sucursal = "id_sucursal =  $selectSucursal  AND";
                } else {
                    $id_sucursal = '';
                }
            } else {
                $id_sucursal = "id_sucursal = " . $sucursal['id'] . " AND";
            }

            if (isset($searchGuias)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchGuias . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            if ($fechaInicial != null && $fechaFinal != null) {
                if ($fechaInicial == $fechaFinal) {
                    $sWhere = "WHERE $id_sucursal fecha_emision LIKE '%$fechaFinal%'";
                }
                if ($fechaInicial != $fechaFinal) {
                    $sWhere = "WHERE $id_sucursal fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
                }
            }
            //pagination variables
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            include_once 'pagination.php';
            //include pagination file
            $per_page = $selectnum; //how much records you want to show
            $adjacents  = 4; //gap between pages after number of adjacents
            $offset = ($page - 1) * $per_page;

            //Count the total number of row in your table*/
            $pdo =  Conexion::conectar();
            $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable  $sWhere");
            $totalRegistros = $totalRegistros->fetch()['numrows'];
            $tpages = ceil($totalRegistros / $per_page);
            $reload = './index.php';
            //main query to fetch the data
            $pdo =  Conexion::conectar();
            $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere ORDER BY id DESC LIMIT $offset,$per_page");
            $registros->execute();

            $registros = $registros->fetchall();

            foreach ($registros as $k => $v) {
                $tabla = 'ubigeo_distrito';
                $item = "id";
                $valor = $v['ubigeoPartida'];
                $distritoPartida = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);

                $tabla = 'ubigeo_provincia';
                $item = "id";
                $valor = $distritoPartida['province_id'];
                $provinciaPartida = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);


                $tabla = 'ubigeo_distrito';
                $item = "id";
                $valor = $v['ubigeoLlegada'];
                $distritoLlegada = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);

                $tabla = 'ubigeo_provincia';
                $item = "id";
                $valor = $distritoLlegada['province_id'];
                $provinciaLlegada = ControladorClientes::ctrBuscarUbigeoNombre($tabla, $item, $valor);



                $item = 'id';
                $valor = $v['id_cliente'];
                $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);
                if ($v['cli_tipodoc'] == 1 && $v['cli_tipodoc'] != 2) {
                    $nombreRazon = $cliente['nombre'];
                    $doc = $cliente['documento'];
                } else {
                    $nombreRazon = $cliente['razon_social'];
                    $doc = $cliente['ruc'];
                }
                if ($provinciaPartida['id'] == '1501') {
                    echo '<tr>
                <td>' . ++$k . '</td>
                <td>' . $v['fecha_emision'] . '</td>
                <td>' . $v['serie'] . '-' . $v['correlativo'] . '</td>
                <td>' . $nombreRazon . '<br>' . $doc . '</td>
                <td>
                ' . $provinciaPartida['nombre_provincia'] . ' - ' . $distritoPartida['nombre_distrito'] . '
               </td>
               <td>
                ' . $provinciaLlegada['nombre_provincia'] . ' - ' . $distritoLlegada['nombre_distrito'] . '
               </td>
               <td style="text-align:center;">';
                    if ($v['retorno'] == 'n') {
                        echo '<button type="button" idGuia="' . $v['id'] . '" class="btn-retornar-almacen">RETORNAR A ALMACÉN</button>';
                    } else {
                        echo '<button type="button" class="btn-retornarnado">RETORNADO</button>';
                    }

                    echo '</td>
                </tr>';
                }
            }
        }

        $paginador = new Paginacion();
        $paginador = $paginador->paginarGuiasRetorno($reload, $page, $tpages, $adjacents);
        echo "<tr>
                <td colspan='8' style='text-align:center;'>" . $paginador . "</td>
                </tr>";
    }
}



if (isset($_POST['ligr']) || $_POST['ligr'] = 'ligr') {
    $guias = new DataTablesGuiasRetorno();
    $guias->dtaListarGuiasRetorno();
}
