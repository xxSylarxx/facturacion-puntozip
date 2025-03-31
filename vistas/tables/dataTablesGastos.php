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


class DataTablesGastos
{

    // DATA_TABLE INVENTARIOS
    public  function dtaGastos()
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

            $searchGastos = @$_GET['searchR'];
            $selectnum = $_GET['selectnum'];
            $aColumns = array('descripcion', "monto"); //Columnas de busqueda
            $tabla1 = "gastos";
            $tabla2 = "usuarios";
            $sWhere = "";
            if (isset($searchGastos) && !empty($searchGastos) && empty($fechafin)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . $searchGastos . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            if (empty($fechafin) && empty($searchGastos)) {
                $sWhere = "";
            }

            if (!empty($fechafin) && empty($searchGastos)) {

                $sWhere = "WHERE g.fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
            }

            if (!empty($searchGastos)  && !empty($fechafin)) {

                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= "g.fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchGastos . "%' OR ";
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
            $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $tabla1 g INNER JOIN $tabla2 u ON g.id_usuario=u.id $sWhere");
            $totalRegistros = $totalRegistros->fetch()['numrows'];
            $tpages = ceil($totalRegistros / $per_page);
            $reload = './index.php';
            //main query to fetch the data
            $pdo =  Conexion::conectar();

            $registros = $pdo->prepare("SELECT g.*, g.id as idgasto, g.fecha as fechag, u.* FROM $tabla1 g INNER JOIN $tabla2 u ON g.id_usuario = u.id $sWhere ORDER BY g.id DESC LIMIT $offset,$per_page");


            $registros->execute();

            $registros = $registros->fetchall();

            if ($totalRegistros > 0) {
                foreach ($registros as $k => $v) {


                    echo '
               <tr class="iddelete' . $v['idgasto'] . '">
               <td>' . ++$k . '</td>
               <td>' . date_format(date_create($v['fechag']), 'd-m-Y  H:i:s') . '</td>
               <td>' . $v['descripcion'] . '</td>
               <td>' . $v['monto'] . '</td>
               <td></td>
               <td>';
                    if ($_SESSION['perfil'] == 'Administrador') {

                        echo '<button class="btn btn-danger btn-sm center-block btn-eliminar-gasto" idGasto="' . $v['idgasto'] . '"><i class="fas fa-trash fa-lg"></i></button>';
                    } else {
                        echo '<button class="btn btn-danger btn-sm center-block"><i class="fas fa-trash fa-lg"></i></button>';
                    }


                    echo '</td>
               
               </tr>';


                    $tot = $k;
                }
                $paginador = new Paginacion();
                $paginador = $paginador->paginarGastos($reload, $page, $tpages, $adjacents);


                echo "<tr>                
                      <td colspan='6' style='text-align:center;'>" . $paginador . "</td>
                     </tr>";
                echo "<tr>                
                      <td colspan='6' style='text-align:center;'>Mostrando " . $tot . ' registros de ' . $totalRegistros . "</td>
                     </tr>";
            } else {


                echo "<tr>   

              <td colspan='6' style='text-align:center;'> <div class='result-report'></div></td>
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



if (isset($_REQUEST['gastosdt'])) {
    if ($_REQUEST['gastosdt'] == "gastosdt") {
        $dataReportes = new DataTablesGastos();
        $dataReportes->dtaGastos();
    }
}
