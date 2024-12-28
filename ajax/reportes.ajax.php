<?php

class AjaxReportes
{

   public function ajaxDescargaReporteVentasExcel()
   {
      $fechaini = $_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = $_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      echo '
    <a class="btn btn-success" href="vistas/modulos/descarga_reporte_ventas.php?reporte=reporte&fechainicial=' . $fechaInicial . '&fechafinal=' . $fechaFinal . '"><i class="fa fa-file-excel fa-lg"></i></a>';
   }


   public function ajaxDescargaReporteComprasExcel()
   {
      $fechaini = $_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = $_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      echo '
    <a class="btn btn-success" href="vistas/modulos/descarga_reporte_compras.php?reporte=reporte&fechainicial=' . $fechaInicial . '&fechafinal=' . $fechaFinal . '"><i class="fa fa-file-excel fa-lg"></i></a>';
   }
   public function ajaxDescargaReporteInventarioExcel()
   {
      $searchInventario = @$_POST['searchInventarios'];
      $fechaini = @$_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = @$_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      $selectSucursal = $_POST['selectSucursal'];
      if (@$_POST["fechaFinal"] == '') {
         $fechaFinal = '';
      } else {
         $fechafin = $_POST["fechaFinal"];
         $fechafin2 = str_replace('/', '-', $fechafin);
         $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      }
      echo 'vistas/modulos/descarga_reporte_inventario.php?reporte=reporte&fechaInicial=' . $fechaInicial . '&fechaFinal=' . $fechaFinal . '&searchInventarios=' . $searchInventario . '&selectSucursal='.$selectSucursal.'';
   }
   public function ajaxDescargaReporteKardexExcel()
   {
   
      $idproducto = @$_POST['idproducto'];
      $fechaini = @$_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = @$_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      if (@$_POST["fechaFinal"] == '') {
         $fechaFinal = '';
      } else {
         $fechafin = $_POST["fechaFinal"];
         $fechafin2 = str_replace('/', '-', $fechafin);
         $fechaFinal = date('Y-m-d', strtotime($fechafin2));
      }

      echo 'vistas/modulos/descarga_reporte_kardex.php?reporte=reporte&fechaInicial=' . $fechaInicial . '&fechaFinal=' . $fechaFinal . '&idproducto=' . $idproducto . '';
   
}
}
if (isset($_POST['excelVentas'])) {
   $objDescarcar = new AjaxReportes();
   $objDescarcar->ajaxDescargaReporteVentasExcel();
}
if (isset($_POST['excelCompras'])) {
   $objDescarcarCompras = new AjaxReportes();
   $objDescarcarCompras->ajaxDescargaReporteComprasExcel();
}
if (isset($_POST['excelInventario'])) {
   $objDescarcarCompras = new AjaxReportes();
   $objDescarcarCompras->ajaxDescargaReporteInventarioExcel();
}
if (isset($_POST['excelKardex'])) {
   $objDescarcarCompras = new AjaxReportes();
   $objDescarcarCompras->ajaxDescargaReporteKardexExcel();
}
