<?php
session_start();

require_once("../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorSucursal;



class DataTablesGuias
{

  public function dtaListarGuias()
  {

    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
    if ($action == 'ajax') {
      // escaping, additionally removing everything that could be (html/javascript-) code
      $sucursal = ControladorSucursal::ctrSucursal();
      // RUTAS DE CDR Y XML 
      $ruta_xml = "xml";
      $ruta_cdr = "cdr";


      $searchGuias = $_GET['searchGuias'];
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
        $btnXml =  '<a href="./api/' . $ruta_xml . '/' . $v['nombrexml'] . '" target="_blank" class="xml"  id="xml" idComp="' . $v['id'] . '" ></a>';
        $botonEstadoCdr = '<a href="./api/' . $ruta_cdr . '/' . $v['xmlbase64'] . '" target="_blank" class="cdr"  id="cdr" idComp="' . $v['id'] . '" ></a>';

        //  ESTADO SUNAT ===================
        if ($v['feestado'] == '1') {
          $botonEstado = "<button class='s-success'></button>";
        } else {
          $botonEstado = '<button class="anulado"></button>';
        }
        if ($v['feestado'] == '2') {
          $botonEstado = "<button class='s-rechazo'></button>";
        }
        if ($v['feestado'] == '3') {
          $botonEstado = "<button class='s-getcdr' id='getcdr-guia' idGuia='" . $v['id'] . "'></button>";
        }
        if ($v['feestado'] == '') {
          $botonEstado = "<button class='s-getcdr' id='getcdr-guia' idGuia='" . $v['id'] . "'></button>";
        }
        $tablaGuias = '<tr>
                <td>' . ++$k . '</td>
                <td>' . $v['fecha_emision'] . '</td>
                <td>' . $v['serie'] . '-' . $v['correlativo'] . '</td>
                <td>' . $nombreRazon . '<br>' . $doc . '</td>
                <td>' . $v['comp_ref'] . '</td>
                <td>
                <div class="contenedor-print-comprobantes">
                  <form id="printC" name="printC" method="post" action="vistas/print/printguia/" target="_blank">
                    <input type="hidden" id="idCo" name="idCo" value="' . $v['id'] . '">
                    <button class="printA4"  id="printA4" idComp="' . $v['id'] . '" ></button>
                  </form>
                </div>
                </td>';
        if ($v['borrador'] == 'N') {
          $tablaGuias .= '
                <td>
                <div class="contenedor-print-comprobantes" estadocdr' . $v['id'] . '>
                ' . $btnXml . '
               </div></td>
                <td>
                <div class="contenedor-print-comprobantes" estadocdr' . $v['id'] . '>
                ' . $botonEstadoCdr . '
               </div>
               </td>
                <td><div class="contenedor-print-comprobantes" estadocdr' . $v['id'] . '> ' . $botonEstado . ' </div></td>';
        } else {
          $tablaGuias .= '
          <td class="text-center">-</td>
          <td class="text-center">-</td>
          <td><div class="contenedor-print-comprobantes" estadocdr' . $v['id'] . '> ' . $botonEstado . ' </div></td>
          ';
          $tablaGuias .= '<td>
            <form id="guiaEditar" name="guiaEditar" method="post" action="crear-guia">
                <input type="hidden" id="id_guia_form" name="id_guia_edit" value="' . $v['id'] . '">
                <button type="submit" class="btn btn-primary">Editar</button>
            </form>
          </td></tr>';
        }
        echo $tablaGuias;
      }
    }
    $paginador = new Paginacion();
    $paginador = $paginador->paginarGuias($reload, $page, $tpages, $adjacents);
    echo "<tr>
                <td colspan='8' style='text-align:center;'>" . $paginador . "</td>
                </tr>";
  }
}


if (isset($_POST['lig']) || $_POST['lig'] = 'lig') {
  $guias = new DataTablesGuias();
  $guias->dtaListarGuias();
}
