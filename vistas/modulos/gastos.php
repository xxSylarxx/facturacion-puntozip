<?php

use Controladores\ControladorGastos;

// $item = null;
// $valor = null;
// $cajas = ControladorCaja::ctrMostrarCajas($item, $valor);
// $item = 'id_usuario';
// $valor = $_SESSION['id'];
// $arqueocajas = ControladorCaja::ctrMostrarArqueoCajasid($item, $valor);
?>

<div class="content-wrapper panel-medio-principal">

    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""> <i class="fas fa-cash-register"></i> Gastos</button>
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
                <h3 class="box-title"> Administrar Gastos</h3>

                <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalGastos"><i class="fas fa-plus-square"></i>Nuevo Gasto <i class="fa fa-th"></i>
                </button>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form id="printInv" name="printC" method="post" action="vistas/print/reportegastos/" target="_blank">
                    <div class="row fechas-reportes">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaInicial" name="fechaInicial" placeholder="Fecha Inicial..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadGastos(1)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaFinal" name="fechaFinal" value="<?php echo date("d/m/Y"); ?>" placeholder="<?php echo date("d/m/Y"); ?>" style="width:100%" onchange="loadGastos(1)" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="contenedor-busqueda">
                        <div class="input-group-search">

                            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadGastos(1)">
                                <!-- <option value="5">5</option> -->
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>

                            <div class="input-search">
                                <input type="search" class="search" id="searchGastos" name="searchGastos" placeholder="Buscar..." onkeyup="loadGastos(1)">
                                <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="flex btn-rd-inventarios" style="text-align: center;">
                        <button class="btn btn-danger btn-pdf"><i class="fa fa-file-pdf fa-lg"></i></button>

                        <!-- <a class="btn btn-success btn-excel" href=""><i class="fa fa-file-excel fa-lg"></i></a> -->

                        <span class="btn btn-primary btn-print"  data-toggle="modal" data-target="#modalGastos"><i class="fa fa-cog fa-lg"></i></span>

                    </div>
                </form>

                <div class="table-responsive">
                    <!-- table-bordered table-striped  -->
                    <table class="table  dt-responsive tablaInventarios tbl-t" width="100%">

                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th>FECHA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>MONTO</th>
                                <th>ESTATUS</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <?php
                        $listar = new ControladorGastos();
                        $listar->ctrListarGastos();
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
<div id="modalGastos" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <form role="form" id="formGastos" enctype="multipart/form-data">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class=" modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"> <i class="fas fa-money-bill-wave"></i> Gasto</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">

                        <?php


                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">


                                    <input type="number" class="form-control" name="montogasto" id="montogasto" placeholder="Ingresar el monto" required>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <textarea class="form-control" name="descripciongasto" id="descripciongasto" cols="83" rows="4" placeholder="Descripción..."></textarea>

                                </div>
                            </div>
                        </div>

                        <?php

                        ?>
                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button class="btn btn-primary btn-agm" id="guardarGasto">Guardar <i class="fas fa-money-bill-wave"></i></button>

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
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title"><i class="fas fa-cash-register fa-lg"></i> Cerrar caja</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="numcaja">CAJA</label>
                                    <input type="text" class="form-control" name="numcaja" id="numcaja" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="nombreu">ENCARGADO</label>
                                    <input type="text" class="form-control" name="nombreu" id="nombreu" value="<?php echo $_SESSION['perfil']; ?>" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">MONTO INICIAL</label>
                                    <input type="number" class="form-control" name="monto_inicial" id="monto_inicial" placeholder="Ingresar número" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">MONTO FINAL</label>
                                    <input type="number" class="form-control" name="monto_final" id="monto_final" placeholder="Ingresar número" required>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">MONTO DE VENTAS</label>
                                    <input type="number" class="form-control" name="monto_ventas" id="monto_ventas" placeholder="Ingresar número" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">TOTAL DE VENTAS</label>
                                    <input type="number" class="form-control" name="total_ventas" id="total_ventas" placeholder="Ingresar número" required>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">EGRESOS</label>
                                    <input type="number" class="form-control" name="egresos" id="egresos" placeholder="Ingresar número" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="monto_inicial">GASTOS</label>
                                    <input type="number" class="form-control" name="gastos" id="gastos" placeholder="Ingresar número" required>

                                </div>
                            </div>
                        </div>


                    </div>

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