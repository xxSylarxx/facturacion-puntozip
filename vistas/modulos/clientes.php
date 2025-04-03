<?php

use Controladores\ControladorClientes;
?>
<div class="content-wrapper panel-medio-principal">

  <div style="padding:5px"></div>
  <section class="container-fluid">
    <section class="content-header dashboard-header">

      <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
        <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

          <div class="col-md-3 hidden-sm hidden-xs">
            <button class=""><i class="fas fa-users"></i> Clientes</button>
          </div>
          <div class="col-lg-9 col-md-12 col-sm-12 btns-dash">
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
        <h3 class="box-title">Administración de clientes</h3>

        <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarCliente"><i class="fas fa-plus-square"></i>Nuevo cliente <i class="fa fa-th"></i>
        </button>


      </div>
      <!-- /.box-header -->
      <div class="box-body table-user">

        <div class="contenedor-busqueda">
          <div class="input-group-search">

            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadClientes(1)">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="activos" name="activos" onchange="loadClientes(1)">
              <option value="s">Activos</option>
              <option value="n">inactivos</option>
             
            </select>
            <div class=" input-search">
                <input type="search" class="search" id="search" name="search" placeholder="Buscar..." onkeyup="loadClientes(1)" style="width: 100%;">

          </div>
        </div>
      </div>
      <input type="hidden" id="perfilOcultoc" value="<?php echo $_SESSION['perfil'] ?>">
      <!-- table-bordered table-striped  -->
      <div class="table-responsive">
        <table class="table  dt-responsive tabla-clientes tbl-t" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Nombre</th>
              <th>RUC/DNI</th>
              <th>Email</th>
              <th>Telefono</th>
              <th>Direccion</th>
              <th>Ingreso al sistema</th>
              <th width="100px">Acciones</th>
              <th>Activo</th>
            </tr>
          </thead>


          <?php
          $clientesTable = new ControladorClientes();
          $clientesTable->ctrListarClient();

          ?>




        </table>

      </div>


      <div class="row">
        <div class="col-md-12">
          <div class="box-body chart-responsive chart-responsive-compradores">
            <!-- <div class="chart" id="revenue-chart" style="height: 300px;"></div> -->
          </div>
        </div>



      </div>

    </div>
    <!-- BOX FIN -->
    <!-- /.box-footer -->
  </section>

</div>

<!-- MODAL AGREGAR CLIENTE-->
<!-- Modal -->
<div id="modalEditarCliente" class="modal fade modal-forms fullscreen-modalB" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form role="form" method="post" name="frmClientes" id="frmEditarClientes">
        <input type="hidden" name="id" id="id">

        <!--=====================================
CABEZA DEL MODAL
======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Cliente</h4>

        </div>

        <!--=====================================
CUERPO DEL MODAL
======================================-->

        <div class="modal-body">

          <div class="box-body">

            <div class="row">
              <div class="col-md-6">

                <div class="form-group">


                  <select name="editartipodoc" id="editartipodoc" class="form-control">
                    <option value="1">DNI</option>
                    <option value="6">RUC</option>
                  </select>

                </div>


              </div>
              <div class="col-md-6">
                <!-- ENTRADA PARA EL DNI -->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-address-card""></i></span> 

            <input type=" number" maxlength="11" class="form-control " name="editarnumdoc" id="editarnumdoc" placeholder=" Ingresar DNI">

                  </div>

                </div>
              </div>
            </div>

            <!-- /=============================================================== -->

            <div class="row">
              <div class="col-md-12">
                <!-- ENTRADA PARA EL NOMBRE -->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>



                    <input type="text" class="form-control " name="editarnombrerazon" id="editarnombrerazon" placeholder="Ingresar nombre">

                  </div>

                </div>
              </div>


            </div>

            <!-- ================================================================================= -->
            <div class="row">
              <div class="col-md-12">
                <!-- ENTRADA PARA RAZÓN SOCIAL -->

              </div>
            </div>



            <!-- ENTRADA PARA EL EMAIL -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-at"></i></span>

                <input type="email" class="form-control " name="editarEmail" id="editarEmail" placeholder="Ingresar email">

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span>

                <input type="text" class="form-control " name="editarTelefono" id="editarTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "999999999"' data-mask>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span>

                <input type="text" class="form-control " name="editarDireccion" id="editarDireccion" placeholder="Ingresar dirección">

              </div>

            </div>

            <div class="form-group">
              <select class="form-control select2" style="width: 100%; height: 60px;" name="editarClienteUbigeo" id="editarClienteUbigeo">
                <option value="">-SELECCIONAR UBIGEO-</option>
                <?php
                $listUbigeo = ControladorClientes::ctrBuscarUbigeo();
                foreach ($listUbigeo as $v) { ?>
                  <option value="<?php echo $v['ubigeo'] ?>"><?php echo $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name']; ?></option>
                <?php } ?>
              </select>
            </div>

            <!-- ENTRADA PARA FECHA NACIMIENTO -->
            <!-- <div class="form-group">

      <div class="input-group">

        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

        <input type="text" class="form-control " name="editarFechaNacimiento" id="editarFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask>

      </div>

    </div> -->



          </div>

        </div>

        <!--=====================================
PIE DEL MODAL
======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary btn-agm btn-editar-cliente">Editar cliente</button>

        </div>

        <?php

        // $editarCliente = new ControladorClientes();
        // $editarCliente->ctrEditarCliente();

        ?>

      </form>


    </div>
  </div>
</div>



<!-- fullscreen-modal -->
<!-- MODAL EDITAR CLIENTE-->
<!-- Modal -->
<div id="modalAgregarCliente" class="modal fade modal-forms" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" name="frmClientes" id="frmAgregarClientes">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Nuevo Cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <div class="row">
              <div class="col-md-6">

                <div class="form-group">


                  <select name="nuevotipodoc" id="nuevotipodoc" class="form-control">
                    <option value="1">DNI</option>
                    <option value="6">RUC</option>
                  </select>

                </div>


              </div>
              <div class="col-md-6">
                <!-- ENTRADA PARA EL DNI -->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-address-card"></i></span> 

                    <input type=" number" maxlength="11" class="form-control " name="nuevonumdoc" id="nuevonumdoc" placeholder=" Ingresar número">

                  </div>

                </div>
              </div>
            </div>

            <!-- /=============================================================== -->

            <div class="row">
              <div class="col-md-12">
                <!-- ENTRADA PARA EL NOMBRE -->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>



                    <input type="text" class="form-control " name="nuevonombrerazon" id="nuevonombrerazon" placeholder="Ingresar nombre">

                  </div>

                </div>
              </div>


            </div>

            <!-- ================================================================================= -->
            <div class="row">
              <div class="col-md-12">
                <!-- ENTRADA PARA RAZÓN SOCIAL -->

              </div>
            </div>



            <!-- ENTRADA PARA EL EMAIL -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-at"></i></span>

                <input type="email" class="form-control " name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email">

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span>

                <input type="text" class="form-control " name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "999999999"' data-mask>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span>

                <input type="text" class="form-control " name="nuevoDireccion" id="nuevoDireccion" placeholder="Ingresar dirección">

              </div>

            </div>

            <div class="form-group">
              <select class="form-control select2" style="width: 100%; height: 60px;" name="nuevoclienteUbigeo" id="nuevoclienteUbigeo">
                <option value="">-SELECCIONAR UBIGEO-</option>
                <?php
                $listUbigeo = ControladorClientes::ctrBuscarUbigeo();
                foreach ($listUbigeo as $v) { ?>
                  <option value="<?php echo $v['ubigeo'] ?>"><?php echo $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name']; ?></option>
                <?php } ?>
              </select>
            </div>
            <!-- ENTRADA PARA FECHA NACIMIENTO -->
            <!-- <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

                <input type="text" class="form-control " name="editarFechaNacimiento" id="editarFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask>

              </div>

            </div> -->



          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary btn-agm btn-agregar-cliente">Guardar cliente</button>

        </div>

        <?php

        // $editarCliente = new ControladorClientes();
        // $editarCliente->ctrEditarCliente();

        ?>

      </form>


    </div>
  </div>
</div>

<div class="resultadoCrearCliente"></div>