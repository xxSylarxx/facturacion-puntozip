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
use Controladores\ControladorProdutos;


class DataTablesPos
{



    // DATA_TABLE LISTA LOS PRODUCTOS PARA AGREGAR AL CARRITO
    public  function dtaProductosVentasPos()
    {
        $sucursal = ControladorSucursal::ctrSucursal();
        $id_sucursal = $sucursal['id'];
        $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
        if ($action == 'ajax') {
            $sucursal = ControladorSucursal::ctrSucursal();
            // escaping, additionally removing everything that could be (html/javascript-) code
            $searchProducto = $_GET['searchProductoV'];
            $selectnum = $_GET['selectnum'];
            $categorias = $_GET['categorias'];
            $selectSucursal = $_GET['idSucursal'];
            $aColumns = array('codigo', 'serie', 'descripcion'); //Columnas de busqueda
            $sTable = 'productos';
            $sWhere = "";

            if ($_SESSION['perfil'] == 'Administrador') {
                if (isset($selectSucursal) && !empty($selectSucursal)) {
                    $id_sucursal = "id_sucursal =  $selectSucursal  AND";
                } else {
                    $id_sucursal = '';
                }
            } else {
                $id_sucursal = "id_sucursal = " . $sucursal['id'] . " AND";
            }


            if (isset($searchProducto)) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= "$id_sucursal  activo <> 'n' AND " . $aColumns[$i] . " LIKE '%" . $searchProducto . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            if (isset($categorias) and $categorias != NULL) {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($aColumns); $i++) {
                    $sWhere .= "$id_sucursal activo <> 'n' AND id_categoria = '$categorias' AND " . $aColumns[$i] . " LIKE '%" . $searchProducto . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }
            //pagination variables
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            include_once 'pagination-pos.php';
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
            $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page");
            $registros->execute();

            $registros = $registros->fetchall();

            if ($sucursal['activo'] == 'n') {
                echo "<h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3>";
                exit();
            }
            $tiem = time();
            foreach ($registros as $key => $value) :
                $stockactual = $value['stock'] == 0 ? '<div class="sin-stock">SIN STOCK</div>' : '';
                echo '<div class="productos-pos-venta">
           
               <img class="img-ps" id="imagenPos' . $value['id'] . '" src="' . $value['imagen'] . '?q=' . $tiem . '" alt="' . $value['descripcion'] . '"  ><div class="btn-agregar-ps-pos" idImagen="' . $value['id'] . '" id="btnAgregarPos' . $value['id'] . '"><i class="fas fa-cart-plus fa-lg"></i></div></img>
               ' . $stockactual . '
               <div  class="descripcion-pos">' . $value['descripcion'] . '</div>
               <div class="precio-uni-pos">
               <div class="precio-unitario">S/ ' . number_format($value['precio_unitario'], 2) . '</div>
               </div>
               <div class="form-group btn-mas-menos">
               <div class="input-group">
               <span class="input-group-addon btn-pos-menos" idpros="' . $value['id'] . '">-</span>
               <input type="number" class="form-control cantidad-number" idProServ="' . $value['id'] . '" id="cantidad' . $value['id'] . '" min="1" value="1">
                <span class="input-group-addon btn-pos-mas" idpros="' . $value['id'] . '">+</i></span>
                </div>
                </div>
             
                <form  method="POST"  class="formFotoPos" id="formFotoPos' . $value['id'] . '" name="formFotoPos' . $value['id'] . '"enctype="multipart/form-data">
                  <div class="form-group contenedor-menu-pos">
                  
                <label for="fotoPos' . $value['id'] . '" class="fas fa-camera foto-pos" idProPos="' . $value['id'] . '" ></label>
                <input type="file"  class="fotoPos" name="fotoPos' . $value['id'] . '" id="fotoPos' . $value['id'] . '" onchange="cambioFotoPos(' . $value['id'] . ')">                
                <i class="fas fa-pencil-alt edit-pos btn-edit-pos" idProPos="' . $value['id'] . '"   data-toggle="modal" data-target="#modalEditarItemsPos"></i>
                <input type="hidden" name="codigo" id="codigo' . $value['id'] . '" value="' . $value['codigo'] . '">
                </div>
                </form>
               
               </div>';


            endforeach;


            $paginador = new PaginacionPos();
            $paginador = $paginador->paginarProductosVentas($reload, $page, $tpages, $adjacents);
            echo "<p><div class='paginador-pos'  style='text-align:center;'>" . $paginador . "</div></p>";
        }
    }
}


// if (isset($_REQUEST['dc'])) {
//     if ($_REQUEST['dc'] == "dc") {
//         $dataClientes = new DataTables();
//         $dataClientes->dtaClientes();
//     }
// }
if (isset($_REQUEST['dppos'])) {
    if ($_REQUEST['dppos'] == "dppos") {
        $dataProductos = new DataTablesPos();
        $dataProductos->dtaProductosVentasPos();
    }
}
