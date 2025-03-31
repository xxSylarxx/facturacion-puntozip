<?php

use Controladores\ControladorCaja;
use Controladores\ControladorEmpresa;
use Controladores\Emisor;

$item = null;
$valor = null;
$cajas = ControladorCaja::ctrMostrarCajas($item, $valor);
$item = 'id_usuario';
$valor = $_SESSION['id'];
$arqueocajas = ControladorCaja::ctrMostrarArqueoCajasid($item, $valor);

$emisor = ControladorEmpresa::ctrEmisor();
?>

<div class="content-wrapper panel-medio-principal">


    <div style="padding:5px"></div>

    <section class="container-fluid">

        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""> <i class="fas fa-cash-register"></i> Arqueo Caja</button>
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
        <?php
        if ($emisor['caja'] == 'n') {
            echo '<div style="text-align: center;" class="alert alert-informe">PARA PODER VER ESTE MÓDULO DEBE ACTIVAR CAJA EN ADMINISTRACIÓN<br><i class="fas fa-cash-register fa-lg"></i></div>';
        } else {

        ?>
            <!-- BOX INI -->
            <div class="box rounded">

                <div class="box-header ">
                    <h3 class="box-title">Apertura y Cierre de Caja</h3>

                    <button class="btn btn-success  pull-right btn-radius btn-apertura-caja-modal" data-toggle="modal" data-target="#modalAperturaCaja"><i class="fas fa-plus-square"></i>Apertura de caja <i class="fa fa-th"></i>
                    </button>


                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form id="printInv" name="printC" method="post" action="vistas/print/arqueocaja/" target="_blank">
                        <div class="row fechas-reportes">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" class="fechareportes" id="fechaInicial" name="fechaInicial" placeholder="Fecha Inicial..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadArqueoCajas(1)">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" class="fechareportes" id="fechaFinal" name="fechaFinal" value="<?php echo date("d/m/Y"); ?>" placeholder="<?php echo date("d/m/Y"); ?>" style="width:100%" onchange="loadArqueoCajas(1)" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="contenedor-busqueda">
                            <div class="input-group-search">

                                <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadArqueoCajas(1)">
                                    <!-- <option value="5">5</option> -->
                                    <option value="10"">10</option>
                <option value=" 20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <div class="input-search">
                                    <input type="hidden" class="search" id="searchInventarios" name="searchInventarios" placeholder="Buscar..." onkeyup="">
                                    <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="flex btn-rd-inventarios" style="text-align: center;">
                            <button class="btn btn-danger btn-pdf"><i class="fa fa-file-pdf fa-lg"></i></button>

                            <!-- <a class="btn btn-success btn-excel" href=""><i class="fa fa-file-excel fa-lg"></i></a> -->

                            <span class="btn btn-primary btn-print" data-toggle="modal" data-target="#modalAperturaCaja"><i class="fa fa-cog fa-lg"></i></span>

                        </div>
                    </form>

                    <div class="table-responsive">
                        <!-- table-bordered table-striped  -->
                        <table class="table  dt-responsive tablaInventarios tbl-t" width="100%">

                            <thead>
                                <tr>
                                    <th style="width:10px;">#</th>
                                    <th>CAJA</th>
                                    <th>ENCARGADO</th>
                                    <th>FECHA APERTURA</th>
                                    <th>FECHA CIERRE</th>
                                    <th>MONTO INICIAL</th>
                                    <th>MONTO FINAL</th>
                                    <th>TOTAL VENTAS</th>
                                    <th>ESTATUS</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <?php

                            if ($sucursal['activo'] == 'n') {
                                echo "
                            <h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3>";
                                // exit();
                            } else {

                                $listar = new ControladorCaja();
                                $listar->ctrListarArqueoCajas();
                            }
                            ?>

                        </table>


                    </div>



                </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
        <?php } ?>
    </section>

</div>

<!-- MODAL AGREGAR CATEGORIAS-->
<!-- Modal -->
<div id="modalAperturaCaja" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <form role="form" id="formAperturaCaja">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class=" modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"> <i class="fas fa-cash-register fa-lg"></i> Apertura de caja</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">
                    <?php
                    if ($sucursal['activo'] == 'n') {
                        echo "
                            <h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3>";
                        // exit();
                    } else {
                    ?>
                        <div class="box-body contenedor-arqueocaja">
                            <?php
                            if ($arqueocajas) {

                                echo '<div class="alert alert-danger" role="alert">YA HAY UNA CAJA APERTURADA</div>';
                            } else {

                            ?>
                                <!-- ENTRADA PARA EL NOMBRE -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">


                                            <select class="form-control" name="cajaid" id="cajaid">
                                                <option value="">ESCOGER CAJA</option>
                                                <?php
                                                foreach ($cajas as $k => $value) {
                                                    $item = null;
                                                    $valor = null;
                                                    $cajaac = ControladorCaja::ctrMostrarArqueoCajas($item, $valor);
                                                    if ($value['activo'] == 1) {

                                                        echo '<option class="opt' . $value['id'] . '" value="' . $value['id'] . '">' . $value['nombre'] . ' - ' . $value['numero_caja'] . '</option>';
                                                    }
                                                    foreach ($cajaac as $c => $caja) {
                                                        if ($caja['estado'] == 1) {
                                                            echo '<script>
                                                $(".opt' . $caja["id_caja"] . '").hide();
                                            </script>';
                                                        }
                                                    }
                                                }
                                                ?>

                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">


                                            <input type="text" class="form-control" name="fechaaperturacaja" id="fechaaperturacaja" value="<?php echo date("d/m/Y"); ?>" placeholder="" readonly>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">


                                            <input type="number" class="form-control" name="montoapertura" id="montoapertura" placeholder="Ingresar el monto de apertura" required>

                                        </div>
                                    </div>


                                </div>

                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button class="btn btn-primary btn-agm" id="guardarAperturaCaja">Guardar <i class="fas fa-cash-register"></i></button>

                </div>



            </form>


        </div>
    </div>
</div>

<!-- MODAL EDITAR CATEGORIA -->
<div id="modalCierreCaja" class="modal fade modal-forms" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <form role="form" id="formCierreCaja">
                <input type="hidden" name="idCajaCierre" id="idCajaCierre">
                <input type="hidden" name="idusuariocaja" id="idusuariocaja">
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"><i class="fas fa-cash-register fa-lg"></i> Cierre de caja</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">
                    <?php
                    if ($sucursal['activo'] == 'n') {
                        echo "
                            <h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3>";
                        // exit();
                    } else {
                    ?>
                        <div class="box-body">

                            <!-- ENTRADA PARA EL NOMBRE -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="numcaja">CAJA</label>
                                        <input type="text" class="form-control" name="numcaja" id="numcaja" required readonly>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="nombreu">ENCARGADO</label>
                                        <input type="text" class="form-control" name="nombreu" id="nombreu" value="" readonly>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">MONTO INICIAL</label>
                                        <input type="number" class="form-control" name="monto_inicial" id="monto_inicial" placeholder="Ingresar número" required readonly>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">MONTO FINAL</label>
                                        <input type="number" class="form-control" name="monto_final" id="monto_final" placeholder="Ingresar número" required readonly>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">MONTO DE VENTAS</label>
                                        <input type="number" class="form-control" name="monto_ventas" id="monto_ventas" placeholder="Ingresar número" required readonly readonly>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">TOTAL DE VENTAS</label>
                                        <input type="number" class="form-control" name="total_ventas" id="total_ventas" placeholder="Ingresar número" required readonly readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">EGRESOS</label>
                                        <input type="number" class="form-control" name="egresos" id="egresos" placeholder="Ingresar número" required readonly>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="monto_inicial">GASTOS</label>
                                        <input type="number" class="form-control" name="gastos" id="gastos" placeholder="Ingresar número" required readonly>

                                    </div>
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

                    <button class="btn btn-primary btn-agm" id="cerrarCaja">Cerrar Caja <i class="fas fa-cash-register"></i></button>

                </div>



            </form>

        </div>

    </div>

</div>

<?php


?>