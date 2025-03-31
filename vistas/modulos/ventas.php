<?php

use Controladores\ControladorVentas;
use Controladores\ControladorSucursal;

if (isset($_POST['noenviados'])) {
  $noEnviados = $_POST['noenviados'];
} else {
  $noEnviados = '';
}
?>
<div class="content-wrapper panel-medio-principal">
  <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"] ?>">
  <input type="hidden" name="noenvi" id="noenvi" value="<?php echo  $noEnviados ?>">
  <input type="hidden" name="fechanoenviados" id="fechanoenviados" value="<?php echo  date('Y-m-d') ?>">
  <div style="padding:5px"></div>
  <section class="container-fluid">
    <section class="content-header dashboard-header">
      <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
        <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

          <div class="col-md-3 hidden-sm hidden-xs">
            <button class=""><i class="fas fa-file-invoice"></i> Ventas</button>
          </div>
          <div class="col-md-9  col-sm-12 btns-dash">
            <a href="crear-factura" class="btn pull-right" style="margin-left:10px"><i class="fas fa-file-invoice"></i> Emitir factura</a>
            <a href="crear-boleta" class="btn pull-right"><i class="fas fa-file-invoice"> </i> Emitir boleta</a>
          </div>
        </div>
      </div>
    </section>
  </section>



  <!-- <section class="content"> -->
  <section class="container-fluid panel-medio">
    <!-- BOX INI -->
    <div class="box rounded">

      <div class="box-header ">
        <h3 class="box-title">Administración de ventas</h3>

        <!-- DATARANGEPICKER  -->
         <button class=" pull-center btn-succes-envia"><i class="fas fa-file-import"> </i> ENVÍO MASIVO  <i class="fas fa-angle-double-right fa-lg"></i></button>
        <button type="button" class="btn btn-primary pull-right btn-rango-fechas" id="daterange-btn">
          <span>
            <i class="fa fa-calendar"></i> Rango de fecha <i class="fa fa-caret-down"></i>
          </span>
          
        </button>
        <input type="hidden" id="fechaInicial" name="fechaInicial" value="">
        <input type="hidden" id="fechaFinal" name="fechaFinal" value="">


      </div>
      <!-- /.box-header -->
      <div class="box-body table-user">
        <div class="contenedor-estados-sunat">
          <div class="sunat-estado">
            <label class="estadosunat"></label>Estado SUNAT:
          </div>
          <div><label class="aceptado"></label>Aceptado</div>
          <div><label class="rechazado"></label>Rechazado</div>
          <div><label class="noenviado"></label>Pendiente de envío</div>
          <div><label class="baja"></label>Comunicación de baja(Anulado)</div>
        </div>
        <!-- <div class="contenedor-busqueda"> -->

        <div class="row" style="margin-top: 7px">
          <div class="col-lg-12">


            <div class="contenedor-busqueda">
              <div class="input-group-search">

                <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadVentas(1)">
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <?php
                if ($_SESSION['perfil'] == 'Administrador') {
                  echo '
              <select class="form-control select2" name="selectSucursal" id="selectSucursal" style="margin-left: 4px;" onchange="loadVentas(1)">
                <option value="">SUCURSAL</option>';
                  $item = null;
                  $valor = null;
                  $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
                  foreach ($sucursal as $k => $v) {
                    echo '<option value="' . $v['id'] . '">' . $v['nombre_sucursal'] . ' - Sede: ' . $v['direccion'] . '</option>';
                  }
                  echo '
              </select>';
                } else {

                  $sucursal = ControladorSucursal::ctrSucursal();

                  echo '
              <input type="hidden" name="selectSucursal" id="selectSucursal" value="' . $sucursal['id'] . '">';
                }
                ?>

                <div class="input-search">
                  <input type="search" class="search" id="searchVentas" name="searchVentas" placeholder="Buscar..." onkeyup="loadVentas(1)">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- </div> -->

      <!-- table-bordered table-striped  -->
      <div class="table-responsive">
        <table class="table table-bordered dt-responsive tablaVentas" width="100%">

          <thead>

            <tr>
              <th style="width:10px;">#</th>
              <th>FECHA EMISIÓN</th>
              <th>COMPROBANTE</th>
              <th>CLIENTE</th>
              <!-- <th>I.G.V.</th> -->
              <th>TOTAL</th>
              <th width="118px">PDF</th>
              <th>XML</th>
              <th>CDR</th>
              <th>SUNAT</th>
              <th></th>
            </tr>
          </thead>
          <?php

          $ventas = new ControladorVentas();
          $ventas->ctrListarVentas();


          ?>

        </table>
      </div>
    </div>

</div>

<!-- BOX FIN -->
<!-- /.box-footer -->
</section>

</div>



<!-- MODAL EDITAR CLIENTE -->
<div id="modalEditarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" id="editarCategoria" name="editarCategoria" value="" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar cliente</button>

        </div>

        <?php

        // $editarUsuario = new ControladorUsuarios();
        // $editarUsuario -> ctrEditarUsuario();

        ?>

      </form>

    </div>

  </div>

</div>