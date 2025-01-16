<?php

use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorProductos;
use Controladores\ControladorCategorias;
use Controladores\ControladorProveedores;
use Controladores\ControladorSunat;

$empresa_igv = ControladorEmpresa::ctrEmisor();
$sucursal = ControladorSucursal::ctrSucursal();

?>
<div class="content-wrapper panel-medio-principal">

  <input type="hidden" id="empresa_igv" name="empresa_igv" value="<?php echo $empresa_igv['afectoigv'] ?>">
  <div style="padding:5px"></div>
  <section class="container-fluid">
    <section class="content-header dashboard-header">

      <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
        <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

          <div class="col-md-3 hidden-sm hidden-xs">
            <button class=""><i class="fas fa-box-open"></i> Productos o Servicios</button>
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
        <h3 class="box-title">Administración de productos</h3>

        <?php
        if ($sucursal['activo'] == 's') {


        ?>
          <button class="btn btn-success pull-right btn-radius nuevo-producto-s" data-toggle="modal" data-target="#modalAgregarProducto"><i class="fas fa-plus-square"></i>Nuevo producto o servicio <i class="fa fa-th"></i>
          </button>

        <?php
        }
        ?>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-user">
        <!-- table-bordered table-striped  -->

        <div class="contenedor-busqueda">
          <div class="input-group-search">

            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadProductos(1)">
              <option value="5">5</option>
              <option value="10"">10</option>
                <option value=" 20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="activos" name="activos" onchange="loadProductos(1)">
              <option value="s">Activos</option>
              <option value="n"">inactivos</option>
             
            </select>                
                  <?php
                  if ($_SESSION['perfil'] == 'Administrador') {
                    echo '
                    <select class="form-control select2" name="selectSucursal" id="selectSucursal" style="margin-left: 14px;" onchange="loadProductos(1)">
                    <option value="">MOSTRAR EN TODOS LOS ALMACENES</option>';
                    $item = null;
                    $valor = null;
                    $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
                    foreach ($sucursal as $k => $v) {
                      echo '<option value="' . $v['id'] . '">' . $v['nombre_sucursal'] . ' - Sede: ' . $v['direccion'] . '</option>';
                    }
                    echo '</select>';
                  } else {

                    $sucursal = ControladorSucursal::ctrSucursal();

                    echo '
                    <input type="hidden" id="selectSucursal" value="' . $sucursal['id'] . '">';
                  }
                  ?>



                
         
          <div class=" input-search">
                <input type="search" class="search" id="searchProducto" name="searchProducto" placeholder="Buscar..." onkeyup="loadProductos(1)">

                <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <!-- table-bordered table-striped  -->
        <table class="table  dt-responsive tablaProductos tbl-t" width="100%">

          <thead>

            <tr>
              <th style="width:10px;">#</th>
              <th>Imagen</th>
              <th>Código</th>
              <!-- <th>Serie</th> -->
              <th>Descripción</th>
              <th>Categoría</th>
              <th>Stock</th>
              <th>Precio venta</th>
              <th>Fecha add</th>
              <th width="135">Acciones</th>
              <th width="62">Activo</th>
            </tr>
          </thead>
          <?php

          $listaProductos = new ControladorProductos();
          $listaProductos->ctrListarProductos();

          ?>

        </table>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box-body  chart-responsive-mas-vendidos">
            <!-- <div class="chart" id="revenue-chart" style="height: 300px;"></div> -->
          </div>
        </div>
      </div>

    </div>

</div>
<!-- BOX FIN -->
<!-- /.box-footer -->
</section>

</div>

<!-- MODAL AGREGAR PRODUCTO-->
<!-- Modal -->
<div id="modalAgregarProducto" class="modal fade modal-forms fullscreen-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="formProductos" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <!-- <h4 class="modal-title">NUENO PRODUCTO O SERVICIO</h4> -->

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <div class="col-md-8">
              <!-- PRIMERA SECCIÓN============= -->
              <div class="row">
                <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->
                <div class="col-md-12">
                  <div class="form-group">
                    <select class="select2" name="nuevaSucursal" id="nuevaSucursal" style="width: 100%">

                      <?php
                      if ($_SESSION['perfil'] == 'Administrador') {
                        echo '<option value="">SELECCIONE UN ALMACEN</option>';
                        $item = null;
                        $valor = null;
                        $sucursal = ControladorSucursal::ctrSucursalPrincipal($item, $valor);
                        foreach ($sucursal as $k => $v) {
                          echo '<option value="' . $v['id'] . '">' . $v['nombre_sucursal'] . ' - : ' . $v['direccion'] . '</option>';
                        }
                        // echo '<option value="todos">TODOS</option>';
                      } else {

                        $sucursal = ControladorSucursal::ctrSucursal();

                        echo '<option value="' . $sucursal['id'] . '">' . $sucursal['nombre_sucursal'] . ' - : ' . $sucursal['direccion'] . '</option>';
                      }


                      ?>



                    </select>

                  </div>
                </div>
              </div>
              <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">


                    <select class="form-control" name="nuevaCategoria" id="nuevaCategoria" required>

                      <option value="">Selecionar categoría</option>
                      <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                      foreach ($categorias as $k => $value) :

                        echo '<option value="' . $value['id'] . '">' . $value['categoria'] . '</option>';

                      endforeach;
                      ?>


                    </select>

                  </div>
                </div>


                <!-- ENTRADA PARA DEL CÓDIGO -->
                <div class="col-md-6">
                  <div class="form-group product">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-barcode fa-lg"></i></span>

                      <input type="text" class="form-control" name="nuevoCodigo" id="nuevoCodigo" placeholder="Código" autocomplete="off">

                      <span class="input-group-addon bar-code-btn"><i class="fas fa-sync-alt"></i></span>
                    </div>
                  </div>
                </div>
                <!-- ENTRADA PARA DEL CÓDIGO -->
                <!-- <div class="col-md-4">
                  <div class="form-group"> -->

                <input type="hidden" class="form-control" name="nuevaSerie" id="nuevaSerie" placeholder="Serie del producto">


                <!-- </div>
                </div> -->
              </div>

              <!-- FIN PRIMERA SECCIÓN======== -->
              <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
              <div class="row">

                <div class="col-md-6">
                  <div class="form-group">


                    <select class="form-control" name="tipo_afectacion" id="tipo_afectacion">
                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                      foreach ($unidad_medida as $k => $value) {


                        echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                      }
                      ?>
                    </select>


                  </div>
                </div>
                <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
                <div class="col-md-6">
                  <div class="form-group">



                    <select class="form-control" name="unidad" id="unidad">
                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
                      foreach ($unidad_medida as $k => $value) {

                        if ($value['activo'] == 's') {

                          echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                        }
                      }
                      ?>
                    </select>

                  </div>

                </div>

              </div>
              <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row">
                <div class="col-md-9">
                  <div class="form-group">
                    <input type="text" class="form-control" name="nuevaDescripcion" id="nuevaDescripcion" placeholder="Ingresar descripción" required>
                  </div>
                </div>

                <!-- ENTRADA PARA STOCK -->
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="number" class="form-control" min="0" name="nuevoStock" id="nuevoStock" placeholder="Ingresar stock" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <textarea class="form-control" name="nuevaCaracteristica" id="nuevaCaracteristica" placeholder="Ingresar característica" rows="3" style="font-size: 18px;"></textarea>
                  </div>
                </div>
              </div>

              <!-- ENTRADA PARA PRECIO VENTA-->
              <!-- CHECKBOX PARA PORCENTAJE -->
            </div>
            <div class="col-md-4">



              <!-- ENTRADA PARA SUBIR FOTO -->

              <div class="img-contenedor">


                <label for="nuevaImagen"></label>
                <input type="file" class="nuevaImagen" name="nuevaImagen" id="nuevaImagen">

                <p class="help-block">Peso máximo de la imagen 2MB</p>

                <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">

              </div>
              <div class="modo-contenedor-series">
                <label for="">¿EL PRODUCTO CUENTA CON SERIES?</label>
                <input type="checkbox" data-toggle="toggle" data-on="AGREGA SERIES" data-off="SIN SERIES" data-onstyle="primary" data-offstyle="danger" id="agregarSerie" name="agregarSerie" data-size="small" data-width="135" value="si">

                <div class="series-productos">
                  <div class="input-group">
                    <input type="text" class="form-control" name="seriep[]" id="seriep[]" placeholder="INGRESE LA SERIE">
                    <span class="btn btn-primary btn-add-serie" style="position:absolute; right:-37px; top:-0px !important; color:#fff;"><i class="fas fa-plus"></i></span>
                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>



        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary btn-agm btn-nuevo-producto">Guardar producto</button>

        </div>

        <?php

        // $crearProducto = new ControladorProductos();
        // $crearProducto-> ctrCrearProducto();

        ?>

      </form>


    </div>
  </div>
</div>

<!-- MODAL EDITAR PRODUCTO-->
<!-- Modal -->
<div id="modalEditarProducto" class="modal fade modal-forms  fullscreen-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" id="formEditarProducto" method="post" enctype="multipart/form-data">

        <input type="hidden" name="editarid" id="editarid">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">EDITAR PRODUCTO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6">
                  <!-- ENTRADA PARA LA DESCRIPCIÓN -->
                  <div class="form-group">

                    <select class="form-control " name="editarCategoria" required>
                      <option value="" id="editarCategoria"></option>
                      <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                      foreach ($categorias as $k => $value) :


                        echo '<option value="' . $value['id'] . '">' . $value['categoria'] . '</option>';




                      endforeach;

                      ?>


                    </select>
                  </div>
                </div>
                <!-- ENTRADA PARA DEL CÓDIGO -->
                <div class="col-md-6">
                  <div class="form-group product">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-barcode fa-lg"></i></span>
                      <input type="text" class="form-control " name="editarCodigo" id="editarCodigo">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA PARA DEL CÓDIGO -->
                <!-- <div class="col-md-4">
                  <div class="form-group"> -->


                <input type="hidden" class="form-control " name="editarSerie" id="editarSerie">

                <!-- </div>
                </div> -->
              </div>


              <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">

                    <select class="form-control" name="editarAfectacion" id="editarAfectacion">

                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                      foreach ($unidad_medida as $k => $value) {


                        echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                      }
                      ?>
                    </select>

                  </div>
                </div>

                <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
                <div class="col-md-6">
                  <div class="form-group">

                    <select class="form-control" name="editarUnidadMedida" id="editarUnidadMedida">
                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
                      foreach ($unidad_medida as $k => $value) {
                        if ($value['activo'] == 's') {
                          echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                        }
                      }
                      ?>
                    </select>

                  </div>
                </div>
              </div>

              <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row">
                <div class="col-md-9">
                  <div class="form-group">

                    <input type="text" class="form-control " name="editarDescripcion" id="editarDescripcion" required>

                  </div>
                </div>

                <!-- ENTRADA PARA STOCK -->
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="number" class="form-control " min="0" name="editarStock" id="editarStock" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <textarea class="form-control" name="editarCaracteristica" id="editarCaracteristica" placeholder="Ingresar característica" rows="3" style="font-size: 18px;"></textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->
                <div class="col-md-12">
                  <label for="">ALMACÉN:</label>
                  <div class="form-group">

                    <select class="form-control select2" name="editarSucursal" id="editarSucursal" style="width: 100%">
                      <?php
                      if ($_SESSION['perfil'] == 'Administrador') {
                        // echo '<option value="">SELECCIONE UN ALMACEN</option>';
                        $item = null;
                        $valor = null;
                        $sucursal = ControladorSucursal::ctrSucursalPrincipal($item, $valor);
                        foreach ($sucursal as $k => $v) {
                          echo '<option value="' . $v['id'] . '">' . $v['nombre_sucursal'] . ' - : ' . $v['direccion'] . '</option>';
                        }
                      } else {

                        $sucursal = ControladorSucursal::ctrSucursal();

                        echo '<option value="' . $sucursal['id'] . '">' . $sucursal['nombre_sucursal'] . ' - : ' . $sucursal['direccion'] . '</option>';
                      }
                      ?>



                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">

              <div class="img-contenedor">

                <label for="editarImagen"></label>
                <input type="file" class="nuevaImagen" name="editarImagen" id="editarImagen">

                <p class="help-block">Peso máximo de la imagen 2MB</p>

                <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">
                <input type="hidden" name="imagenActual" id="imagenActual">
              </div>



            </div>
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <label class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</label>

          <button type="submit" class="btn btn-primary btn-agm">Guardar cambios</button>

        </div>

        <?php

        $editarProducto = new ControladorProductos();
        $editarProducto->ctrEditarProducto();

        ?>

      </form>


    </div>
  </div>
</div>


<div id="modalEditarProductoSeries" class="modal fade modal-forms" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">

        <div class="box-body">

          <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
          <div role="tabpanel">
            <ul class="nav nav-tabs">
              <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">ACTUALIZAR SERIES</a>
              </li>
              <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">AGREGAR NUEVAS SERIES</a>
              </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
                <form action="" id="uSeries" name="uSeries">
                  <input type="hidden" id="idproductoS" name="idproductoS">
                  <div class="contenido-inputs">

                  </div>


                </form>
              </div>
              <div role="tabpanel" class="tab-pane" id="profile">
                <form action="" id="aSeries" name="aSeries">
                  <input type="hidden" id="idproductoSnuevo" name="idproductoSnuevo">
                  <div class="series-productos-agregar">
                    <div class="input-group" style="margin: 0 auto;">
                      <input type="text" class="form-control" name="seriepn[]" id="seriepn" placeholder="INGRESE NUEVA SERIE">
                      <span class="btn btn-primary btn-add-serie-agregar" style="position:absolute; right:-40px; top:-0px !important; color:#fff;"><i class="fas fa-plus"></i></span>
                    </div>
                  </div>
                  <div class="modal-footer">

                    <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btn-nueva-serie">Guardar</button>

                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="respuesta-agregar"></div>
<!-- <div id="resultados"></div> -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=text]').forEach(node => node.addEventListener('keypress', e => {
      if (e.keyCode == 13) {
        e.preventDefault();
      }
    }))
  });
</script>
<?php
