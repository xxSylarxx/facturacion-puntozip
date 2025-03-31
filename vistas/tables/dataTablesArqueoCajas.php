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
use Controladores\ControladorUsuarios;


class DataTablesArqueoCajas
{

    // DATA_TABLE INVENTARIOS
    public  function dtaArqueoCajas()
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
            // escaping, additionally removing everything that could be (html/javascript-) code
            $fechaini = $_GET['fechaini'];
            $fechafin = $_GET['fechafin'];
            $fechai = str_replace('/', '-', $fechaini);
            $fechaInicial = date('Y-m-d', strtotime($fechai));

            $fechaf = str_replace('/', '-', $fechafin);
            $fechaFinal = date('Y-m-d', strtotime($fechaf));

            $searchArqueoCajas = @$_GET['searchR'];
            $selectnum = $_GET['selectnum'];
            $aColumns = array('descripcion', "codigo", "accion"); //Columnas de busqueda
            $tabla1 = "arqueo_cajas";
            $tabla2 = "cajas";
            $tabla3 = "usuarios";
            $sWhere = "";
            // if (isset($searchArqueoCajas) && !empty($searchArqueoCajas) && empty($fechafin)) {
            //     $sWhere = "WHERE (";
            //     for ($i = 0; $i < count($aColumns); $i++) {
            //         $sWhere .= $aColumns[$i] . " LIKE '%" . $searchArqueoCajas . "%' OR ";
            //     }
            //     $sWhere = substr_replace($sWhere, "", -3);
            //     $sWhere .= ')';
            // }
            // if (empty($fechafin) && empty($searchArqueoCajas)) {
            //     $sWhere = "";
            // }

            if (empty($fechafin) && empty($searchArqueoCajas)) {

                $sWhere = "WHERE estado = 1";
            }
            if (!empty($fechafin) && empty($searchArqueoCajas)) {

                $sWhere = "WHERE fecha_apertura BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
            }

            if (!empty($searchArqueoCajas)  && !empty($fechafin)) {

                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= "fecha_apertura BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchArqueoCajas . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
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
            $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $tabla1 a INNER JOIN $tabla2 c ON a.id_caja=c.id $sWhere");
            $totalRegistros = $totalRegistros->fetch()['numrows'];
            $tpages = ceil($totalRegistros / $per_page);
            $reload = './index.php';
            //main query to fetch the data
            $pdo =  Conexion::conectar();

            $registros = $pdo->prepare("SELECT a.*, a.id  as idarc, c.* FROM $tabla1 a INNER JOIN $tabla2 c ON a.id_caja = c.id $sWhere ORDER BY a.id DESC LIMIT $offset,$per_page");


            $registros->execute();

            $registros = $registros->fetchall();

            if ($totalRegistros > 0) {
                foreach ($registros as $k => $v) {
                    $item = 'id';
                    $valor = $v['id_usuario'];
                    $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                    if ($_SESSION['perfil'] == 'Administrador') {
                        $fechaCierre = $v['fecha_cierre'] == null ? '' : date_format(date_create($v['fecha_cierre']), 'd-m-Y  H:i:s');


                        $estatus = $v['estado'] == 1 ? '<button class="btn btn-success btn-xs center-block">Abierta</button>' : '<button class="btn btn-danger btn-xs center-block">Cerrada</button>';

                        if ($v['estado'] == 1) {
                            $btn = '<button class="btn btn-danger center-block btn-datos-cierre" idArqueo="' . $v['idarc'] . '" data-toggle="modal" data-target="#modalCierreCaja"><i class="fas fa-lock fa-lg"></i></button>';
                        } else {
                            $btn = '<form id="printInv" name="printC" method="post" action="vistas/print/reportcaja/"  target="_blank">
                        <input type="hidden" name="idArqueo" id="idArqueo" value=" ' . $v['idarc'] . ' ">
                        <button class="btn btn-success center-block" idArqueo="' . $v['idarc'] . '"><i class="fas fa-print fa-lg"></i></button>
                        </form>';
                        }

                        echo '
               <tr>
               <td>' . ++$k . '</td>
               <td>' . $v['nombre'] . ' ' . $v['numero_caja'] . '</td>
               <td>' . $usuarios['nombre'] . '</td>
               <td>' . date_format(date_create($v['fecha_apertura']), 'd-m-Y  H:i:s') . '</td>
               <td>' . $fechaCierre . '</td>
               <td>' . $v['monto_inicial'] . '</td>
               <td>' . $v['monto_final'] . '</td>
               <td>' . $v['total_ventas'] . '</td>
               <td>' . $estatus . '</td>
               <td>' . $btn . '</td>
               
               </tr>
               
               
               ';
                    } else {
                        if ($v['id_usuario'] == $_SESSION['id']) {
                            $fechaCierre = $v['fecha_cierre'] == null ? '' : date_format(date_create($v['fecha_cierre']), 'd-m-Y  H:i:s');


                            $estatus = $v['estado'] == 1 ? '<button class="btn btn-success btn-xs center-block">Abierta</button>' : '<button class="btn btn-danger btn-xs center-block">Cerrada</button>';

                            if ($v['estado'] == 1) {
                                $btn = '<button class="btn btn-danger center-block btn-datos-cierre" idArqueo="' . $v['idarc'] . '" data-toggle="modal" data-target="#modalCierreCaja"><i class="fas fa-lock fa-lg"></i></button>';
                            } else {
                                $btn = '<form id="printInv" name="printC" method="post" action="vistas/print/reportcaja/"  target="_blank">
                        <input type="hidden" name="idArqueo" id="idArqueo" value=" ' . $v['idarc'] . ' ">
                        <button class="btn btn-success center-block" idArqueo="' . $v['idarc'] . '"><i class="fas fa-print fa-lg"></i></button>
                        </form>';
                            }

                            echo '
               <tr>
               <td>' . ++$k . '</td>
               <td>' . $v['nombre'] . ' ' . $v['numero_caja'] . '</td>
               <td>' . $usuarios['nombre'] . '</td>
               <td>' . date_format(date_create($v['fecha_apertura']), 'd-m-Y  H:i:s') . '</td>
               <td>' . $fechaCierre . '</td>
               <td>' . $v['monto_inicial'] . '</td>
               <td>' . $v['monto_final'] . '</td>
               <td>' . $v['total_ventas'] . '</td>
               <td>' . $estatus . '</td>
               <td>' . $btn . '</td>
               
               </tr>
               
               
               ';
                        }
                    }

                    $tot = $k;
                }
                $paginador = new Paginacion();
                $paginador = $paginador->paginarArqueoCajas($reload, $page, $tpages, $adjacents);


                echo "<tr>                
                      <td colspan='10' style='text-align:center;'>" . $paginador . "</td>
                     </tr>";
                echo "<tr>                
                      <td colspan='10' style='text-align:center;'>Mostrando " . $tot . ' registros de ' . $totalRegistros . "</td>
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



if (isset($_REQUEST['acajas'])) {
    if ($_REQUEST['acajas'] == "acajas") {
        $dataReportes = new DataTablesArqueoCajas();
        $dataReportes->dtaArqueoCajas();
    }
}
