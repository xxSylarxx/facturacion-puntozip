<?php
session_start();
require_once("../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorNotaCredito;
use Controladores\ControladorNotaDebito;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorSucursal;
use Controladores\ControladorSunat;
use Controladores\ControladorReportes;


class DataTablesInventarios
{

    // DATA_TABLE INVENTARIOS
    public  function dtaInventarios()
    {
        if (isset($_SESSION['id_sucursal'])) {
        } else {
            session_destroy();
            echo "<script>
                window.location = 'ingreso';
                </script>";
        }
        $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
        if ($action == 'ajax') {
            $sucursal = ControladorSucursal::ctrSucursal();
            // escaping, additionally removing everything that could be (html/javascript-) code
            if (!isset($_GET['fechafin'])) {
                exit();
            }
            @$fechaini = $_GET['fechaini'];
            @$fechafin = $_GET['fechafin'];
            $fechai = str_replace('/', '-', $fechaini);
            $fechaInicial = date('Y-m-d', strtotime($fechai));

            $fechaf = str_replace('/', '-', $fechafin);
            $fechaFinal = date('Y-m-d', strtotime($fechaf));
            @$selectSucursal = $_GET['selectSucursal'];
            $searchInventario = @$_GET['searchR'];
            @$selectnum = $_GET['selectnum'];
            $aColumns = array('descripcion', "codigo", "accion"); //Columnas de busqueda
            $tabla1 = "inventario";
            $tabla2 = "productos";
            $tabla3 = "usuarios";
            $sWhere = "";
            if ($_SESSION['perfil'] == 'Administrador') {
                if (isset($selectSucursal) && !empty($selectSucursal)) {
                    $id_sucursal = "i.id_sucursal =  $selectSucursal  AND";
                } else {
                    $id_sucursal = '';
                }
            } else {
                $id_sucursal = "i.id_sucursal = " . $sucursal['id'] . " AND";
            }
            if (isset($searchInventario) && !empty($searchInventario) && empty($fechafin)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchInventario . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            if (empty($fechafin) && empty($searchInventario)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchInventario . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }

            if (!empty($fechafin) && empty($searchInventario)) {

                $sWhere = "WHERE $id_sucursal fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
            }

            if (!empty($searchInventario)  && !empty($fechafin)) {

                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= "$id_sucursal fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchInventario . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            //pagination variables
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            include_once 'pagination-inventarios.php';
            //include pagination file
            $per_page = $selectnum; //how much records you want to show
            $adjacents  = 4; //gap between pages after number of adjacents
            $offset = ($page - 1) * $per_page;

            //Count the total number of row in your table*/
            $pdo =  Conexion::conectar();
            $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto=p.id $sWhere");
            $totalRegistros = $totalRegistros->fetch()['numrows'];
            @$tpages = ceil($totalRegistros / $per_page);
            $reload = './index.php';
            //main query to fetch the data
            $pdo =  Conexion::conectar();

            $registros = $pdo->prepare("SELECT i.id, i.id_sucursal, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ORDER BY i.id DESC LIMIT $offset,$per_page");


            $registros->execute();

            $registros = $registros->fetchall();

            if ($totalRegistros > 0) {
                foreach ($registros as $k => $v) {

                    echo '
               <tr>
               <td>' . ++$k . '</td>
               <td>' . $v['descripcion'] . '</td>
               <td>' . $v['movimiento'] . '</td>
               <td>' . date_format(date_create($v['fecha']), 'd/m/Y  H:i:s') . '</td>
               <td>' . $v['cantidad'] . '</td>
               
               </tr>
               
               
               ';
                    $tot = $k;
                }
                $paginador = new PaginacionInv();
                $paginador = $paginador->paginarInventarios($reload, $page, $tpages, $adjacents);


                echo "<tr>                
                      <td colspan='5' style='text-align:center;'>" . $paginador . "</td>
                     </tr>";
                echo "<tr>                
                      <td colspan='5' style='text-align:center;'>Mostrando " . $tot . ' registros de ' . $totalRegistros . "</td>
                     </tr>";
            } else {


                echo "<tr>   

              <td colspan='10' style='text-align:center;'> <div class='result-report'></div></td>
             </tr>";

                echo "<script>
                  $('.result-report').html(`<i class='fas fa-times'></i> NO SE HA ENCONTRADO RESULTADOS`).fadeIn(500, function(){
                    $('.result-report').delay(5000).fadeOut(500);
                  });                        
               </script>";

                echo "<script>
               $('.t-nc, .t-f, .t-b, .t-nv').html(`S/ 0.00`);
               </script>";
            }
        }
    }


    // DATA_TABLE INVENTARIOS
    public  function dtaKardex()
    {
        if (isset($_SESSION['id_sucursal'])) {
        } else {
            session_destroy();
            echo "<script>
                window.location = 'ingreso';
                </script>";
        }
        $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
        if ($action == 'ajax') {

            $sucursal = ControladorSucursal::ctrSucursal();
            // escaping, additionally removing everything that could be (html/javascript-) code
            $fechaini = $_GET['fechaini'];
            $fechafin = $_GET['fechafin'];
            $fechai = str_replace('/', '-', $fechaini);
            $fechaInicial = date('Y-m-d', strtotime($fechai));
            $aColumns = array('descripcion', "codigo", "accion"); //Columnas de busqueda
            $fechaf = str_replace('/', '-', $fechafin);
            $fechaFinal = date('Y-m-d', strtotime($fechaf));
            $selectSucursal = $_REQUEST['selectSucursal'];
            $searchkardex = @$_GET['searchR'];
            $selectnum = $_GET['selectnum'];
            // $aColumns = array('descripcion', "codigo", "accion", "id"); //Columnas de busqueda
            $tabla1 = "inventario";
            $tabla2 = "productos";
            $tabla3 = "usuarios";
            $sWhere = "";
            if ($_SESSION['perfil'] == 'Administrador') {
                if (isset($selectSucursal) && !empty($selectSucursal)) {
                    $id_sucursal = "i.id_sucursal =  $selectSucursal  AND";
                } else {
                    $id_sucursal = '';
                }
            } else {
                $id_sucursal = "i.id_sucursal = " . $sucursal['id'] . " AND";
            }


            if (isset($searchkardex) && !empty($searchkardex) && empty($fechafin)) {

                $sWhere = "WHERE $id_sucursal  p.id = '$searchkardex'";
            }

            if (isset($searchkardex) && !empty($searchkardex) && !empty($fechafin)) {

                $sWhere = "WHERE $id_sucursal p.id = '$searchkardex' AND fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
            }
            if (empty($fechafin) && empty($searchkardex)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchkardex . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }

            if (!empty($fechafin) && empty($searchkardex)) {

                $sWhere = "WHERE $id_sucursal fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
            }


            //pagination variables
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            include_once 'pagination-inventarios.php';
            //include pagination file
            $per_page = $selectnum; //how much records you want to show
            $adjacents  = 4; //gap between pages after number of adjacents
            $offset = ($page - 1) * $per_page;

            //Count the total number of row in your table*/
            $pdo =  Conexion::conectar();
            $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto=p.id $sWhere");
            $totalRegistros = $totalRegistros->fetch()['numrows'];
            $tpages = ceil($totalRegistros / $per_page);
            $reload = './index.php';
            //main query to fetch the data
            $pdo =  Conexion::conectar();

            $registros = $pdo->prepare("SELECT i.id,i.id_sucursal, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id as idproducto FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ORDER BY i.id DESC LIMIT $offset,$per_page");


            $registros->execute();

            $registros = $registros->fetchall();

            if ($totalRegistros > 0) {
                foreach ($registros as $k => $v) {
                    $entrada = 0;
                    $salida = 0;

                    $entrada = $v['accion'] == 'entrada' ? $v['cantidad'] : 0;
                    $salida = $v['accion'] == 'salida' ? $v['cantidad'] : 0;


                    $stok_actual = $v['accion'] == 'entrada' ? $v['stock_actual'] - $entrada : $v['stock_actual'] + $salida;

                    echo '
                 <tr>
                 <td class="">' . date_format(date_create($v['fecha']), 'd/m/Y H:i:s') . '</td>
                 <td class="">' . $v['movimiento'] . '</td>
                 <td class="">' . $stok_actual . '</td>
                 <td class="">' . $entrada . '</td>
                 <td class="">' . $salida . '</td>
                 <td class="">' . $v['stock_actual'] . '</td>
                 </tr>
                
                ';
                    $tot = $k;
                }

                $paginador = new PaginacionInv();
                $paginador = $paginador->paginarKardex($reload, $page, $tpages, $adjacents);


                echo "<tr>                
                      <td colspan='5' style='text-align:center;'>" . $paginador . "</td>
                     </tr>";
                echo "<tr>                
                      <td colspan='5' style='text-align:center;'>Mostrando " . ++$tot . ' registros de ' . $totalRegistros . "</td>
                     </tr>";
            } else {


                echo "<tr>   

              <td colspan='10' style='text-align:center;'> <div class='result-report'></div></td>
             </tr>";

                echo "<script>
                  $('.result-report').html(`<i class='fas fa-times'></i> NO SE HA ENCONTRADO RESULTADOS`).fadeIn(500, function(){
                    $('.result-report').delay(5000).fadeOut(500);
                  });                        
               </script>";

                echo "<script>
               $('.t-nc, .t-f, .t-b, .t-nv').html(`S/ 0.00`);
               </script>";
            }
        }
    }
}



if (isset($_REQUEST['dtainventarios'])) {
    if ($_REQUEST['dtainventarios'] == "dtainventarios") {
        $dataReportes = new DataTablesInventarios();
        $dataReportes->dtaInventarios();
    }
}
if (isset($_REQUEST['dtakardex'])) {
    if ($_REQUEST['dtakardex'] == "dtakardex") {
        $dataReportes = new DataTablesInventarios();
        $dataReportes->dtaKardex();
    }
}
