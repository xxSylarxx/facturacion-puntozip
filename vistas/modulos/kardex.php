<?php

use Controladores\ControladorInventarios;
use Controladores\ControladorProductos;
use Controladores\ControladorSucursal;
?>

<div class="content-wrapper panel-medio-principal">

    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Kardex</button>
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
                <h3 class="box-title">ADMINISTRACIÓN DE KARDEX</h3>

                <!-- <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarAjusteInventario"><i class="fas fa-plus-square"></i>Ajustes de inventario <i class="fa fa-th"></i>
                </button> -->


            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">
                <!-- row fechas -->
                <form id="printKardex" class="select2-st" name="printKardex" method="post" action="vistas/print/kardex/" target="_blank">
                    <div class="row fechas-reportes">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaInicial" name="fechaInicial" placeholder="Fecha Inicial..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadKardex(1)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="fechareportes" id="fechaFinal" name="fechaFinal" placeholder="<?php echo date("d/m/Y"); ?>" style="width:100%" onchange="loadKardex(1)" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="contenedor-busqueda">
                        <div class="input-group-searchkardex">

                            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadKardex(1)">
                                <!-- <option value="5">5</option> -->
                                <option value="10"">10</option>
                <option value=" 20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <?php
                            if ($_SESSION['perfil'] == 'Administrador') {
                                echo '
                    <select class="form-control select2" name="idkSucursal" id="idkSucursal" style="margin-left: 4px;" onchange="loadKardex(1)">
                    <option value="">SUCURSAL</option>';
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
                    <input type="hidden" name="idkSucursal" id="idkSucursal" value="' . $sucursal['id'] . '">';
                            }
                            ?>
                            <div class="kardex-contenedor">
                                <div class="form-group busca-pro-kardex select">

                                    <!-- <label>BUSCAR EL PRODUCTO</label> -->
                                    <select class="form-control select2" style="width: 100%;" name="idproducto" id="idproducto" onchange="loadKardex(1)">
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
                            </div>
                        </div>
                    </div>
                    <div class="flex btn-rd-kardex" style="text-align: center;">
                        <button class="btn btn-danger btn-pdf"><i class="fa fa-file-pdf fa-lg"></i></button>

                        <a class="btn btn-success btn-excel" href=""><i class="fa fa-file-excel fa-lg"></i></a>

                        <span class="btn btn-primary btn-print" data-toggle="modal" data-target="#modalAgregarAjusteInventario"><i class="fa fa-cog fa-lg"></i></span>

                    </div>
                </form>
                <div class="pruebas-inv"></div>
                <div class="table-responsive">
                    <!-- table-bordered table-striped  -->
                    <table class="table  dt-responsive tabla-kardex tbl-t" width="100%" style="margin-top:10px;">

                        <thead>

                            <!-- <th style="width:10px;">#</th> -->
                            <tr>
                                <th colspan="6" style="background:#3F51B5; color:#fff; padding:12px; font-size: 1.2em !important; letter-spacing: 2px !important;">TARJETA KARDEX</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="background:#1E88E5; color:#fff; padding:12px; margin-top:10px;">FECHA Y Documento / Descripción Mvto.</th>
                                <th colspan="4" style="background:#81C784; color:#fff; padding:12px;">UNIDADES</th>
                            </tr>
                            <tr>
                                <th>FECHA</th>
                                <th>MOVIMIENTO</th>
                                <th>INV. INICIAL</th>
                                <th>ENTRADA</th>
                                <th>SALIDA</th>
                                <th>INV. FINAL</th>
                                <!-- <th>Acciones</th> -->
                            </tr>
                        </thead>

                        <?php
                        $listar = new ControladorInventarios;
                        $listar->ctrListarKardex();
                        ?>



                    </table>


                </div>

            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>

</div>