<?php

use Controladores\ControladorInventarios;
use Controladores\ControladorProductos;
use Controladores\ControladorSucursal;

$sucursalA = ControladorSucursal::ctrSucursal();
?>

<div class="content-wrapper panel-medio-principal">

    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Inventario</button>
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
                <h3 class="box-title">Administración de inventario</h3>

                <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarAjusteInventario"><i class="fas fa-plus-square"></i>Ajustes de inventario <i class="fa fa-th"></i>
                </button>


            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">
                <!-- row fechas -->
                <form id="printInv" name="printC" method="post" action="vistas/print/inventario/" target="_blank">
                    <div class="row fechas-reportes">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaInicial" name="fechaInicial" placeholder="Fecha Inicial..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadInventarios(1)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaFinal" name="fechaFinal" placeholder="<?php echo date("d/m/Y"); ?>" style="width:100%" onchange="loadInventarios(1)" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="contenedor-busqueda">
                        <div class="input-group-search">

                            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadInventarios(1)">
                                <!-- <option value="5">5</option> -->
                                <option value="10"">10</option>
                <option value=" 20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <?php
                            if ($_SESSION['perfil'] == 'Administrador') {
                                echo '
                    <select class="form-control select2" name="selectSucursal" id="selectSucursal" style="margin-left: 4px;" onchange="loadInventarios(1)">';
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
                    <input type="hidden" name="selectSucursal" id="selectSucursal" value="' . $sucursal['id'] . '">';
                            }
                            ?>
                            <div class="input-search">
                                <input type="search" class="search" id="searchInventarios" name="searchInventarios" placeholder="Buscar por nombre o código de barra" onkeyup="loadInventarios(1)">
                                <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="flex btn-rd-inventarios" style="text-align: center;">
                        <button class="btn btn-danger btn-pdf"><i class="fa fa-file-pdf fa-lg"></i></button>

                        <a class="btn btn-success btn-excel" href=""><i class="fa fa-file-excel fa-lg"></i></a>

                        <span class="btn btn-primary btn-print" data-toggle="modal" data-target="#modalAgregarAjusteInventario"><i class="fa fa-cog fa-lg"></i></span>

                    </div>
                </form>
                <div class="pruebas-inv"></div>
                <div class="table-responsive">
                    <!-- table-bordered table-striped  -->
                    <table class="table  dt-responsive tablaInventarios tbl-t" width="100%">

                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th>Producto</th>
                                <th>Movimiento</th>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <!-- <th>Acciones</th> -->
                            </tr>
                        </thead>

                        <?php
                        $listar = new ControladorInventarios;
                        $listar->ctrListarInventarios();
                        ?>

                    </table>


                </div>

            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>

</div>

<!-- MODAL AGREGAR CATEGORIAS-->
<!-- Modal -->
<div id="modalAgregarAjusteInventario" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <form role="form" method="post" id="formSalidaEntrada" class="select2-st">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AJUSTE DE INVENTARIO</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">
                    <?php
                    if ($sucursalA['activo'] == 'n') {
                        echo "
                            <h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3>";
                        // exit();
                    } else {
                    ?>
                        <div class="box-body">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-comments"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-number">INGRESE UN NÚMERO NEGATIVO EN EL CAMPO CANTIDAD SI DESEA DESCONTAR</span>
                                    <span class="info-box-text">POR EJEMPLO -10</span>

                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        ......................................
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- ENTRADA PARA EL NOMBRE -->
                            <div id="respuestaAjax"></div>
                            <div class="form-group select">

                                <label>BUSCAR EL PRODUCTO</label>
                                <select class="form-control selectidproducto" style="width: 100%;" id="idproducto">
                                    <option value="">BUSCAR EL PRODUCTO</option>

                                    </option> <?php
                                                $item = null;
                                                $valor = null;
                                                $idsucursal = $_SESSION['id_sucursal'];
                                                $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);


                                                foreach ($productos as $k => $v) {

                                                    echo '
                                <option value="' . $v['id'] . '">' . $v['descripcion'] . '</option>
                                ';
                                                }

                                                ?>



                                </select>
                            </div>
                            <div class="form-group">

                                <div class="input-group">

                                    <input type="number" class="form-control" name="cantidadModificar" id="cantidadModificar" placeholder="Ingresar cantidad" required>

                                </div>

                            </div>



                        </div>
                    <?php
                    }
                    ?>
                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btn-procesar-cambio">Procesar</button>

                </div>

                <?php

                // $crearCategoria = new ControladorCategorias();
                // $crearCategoria->ctrCrearCategoria();

                ?>

            </form>


        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type=search]').forEach(node => node.addEventListener('keypress', e => {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        }))
    });
</script>