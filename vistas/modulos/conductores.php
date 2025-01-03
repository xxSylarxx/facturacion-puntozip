<?php

use Controladores\ControladorConductores;
use Controladores\ControladorProveedores;
use Controladores\ControladorClientes;
use Controladores\Controlador;

?>
<div class="content-wrapper panel-medio-principal">
    <?php



    ?>
    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Conductores</button>
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
                <h3 class="box-title">Administración de Conductores</h3>

                <?php
                if ($_SESSION['perfil'] == 'Administrador') {
                ?>
                    <button class="btn btn-success  pull-right btn-radius btn-no-user" data-toggle="modal" data-target="#modalAgregarProveedor"><i class="fas fa-plus-square"></i>Nuevo conductor <i class="fas fa-user-plus"></i>
                    </button>
                <?php } ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body table-proveedores">
                <!-- table-bordered table-striped  -->
                <table class="table  dt-responsive tablas tbl-t" width="100%">

                    <thead>
                        <tr>
                            <th style="width:10px;">#</th>
                            <th>RUC/DNI</th>
                            <th>APELLIDOS Y NOMBRES</th>
                            <th>N° BREVETE</th>
                            <th>COD. PLACA</th>
                            <th>MARCA VEHÍCULO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $conductores = ControladorConductores::ctrMostrarConductores($item, $valor);

                        foreach ($conductores as $key => $value) :
                            $rucdni = $value['numdoc'];
                            $nombreComp = $value['apellidos'] . ', ' . $value['nombres'];
                        ?>
                            <tr>
                                <td><?php echo ++$key; ?></td>
                                <td><?php echo $rucdni; ?></td>
                                <td><?php echo $nombreComp; ?></td>
                                <td><?php echo $value['numbrevete']; ?></td>
                                <td><?php echo $value['numplaca']; ?></td>
                                <td><?php echo $value['marca_vehiculo']; ?></td>
                                <td>
                                    <div class="btn-group">

                                        <button class="btn btn-warning btnEditarConductorItem" idConductor="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#modalEditarConductor"><i class="fas fa-user-edit"></i></button>
                                        <?php
                                        if ($_SESSION['perfil'] == 'Administrador') {
                                        ?>
                                            <button class="btn btn-danger btnEliminarConductor" idProveedor="<?php echo $value['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                                        <?php } ?>

                                    </div>


                                </td>

                            </tr>

                        <?php
                        endforeach;

                        ?>
                    </tbody>

                </table>




            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>
    <div class="resultadoCrearConductor"></div>
    <?php  ?>
    <!-- <button type="button" class="btn btn-primary printsave">Print</button>
<div class="printerhere" width="100%" style=""></div> -->
    <!-- <embed class="printerhere" src="" type="application/pdf" width="100%" height="600px" class="printerhere" /> -->

</div>

<!-- MODAL AGREGAR PROVEEDOR -->
<!-- Modal -->
<div id="modalAgregarConductor" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formNuevoConductor" class="">
                <input type="hidden" id="insertaConductor" name="insertaConductor" value="insertaConductor">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR CONDUCTOR</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <select name="nuevoTipoDoc" id="nuevoTipoDoc" class="form-control">
                                            <option value="0">SIN DOCUMENTO</option>
                                            <option value="1">DNI</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control " name="nuevoDNI" id="nuevoDNI" placeholder="Ingresar D.N.I" required>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="nuevoNombre" id="nuevoNombre" placeholder="Ingresar Nombres" required>

                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="nuevoApellidos" id="nuevoApellidos" placeholder="Ingresar Apellidos" required>

                            </div>

                            <div class="row">
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="nuevoNumBrevete" id="nuevoNumBrevete" placeholder="Ingresar Num. brevete" required>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="nuevoNumPlaca" id="nuevoNumPlaca" placeholder="Ingresar Num. placa" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="nuevoMarcaVehiculo" id="nuevoMarcaVehiculo" placeholder="Ingresar Marca de vehículo">
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

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btnCrearConductor">Guardar</button>

                </div>



            </form>


        </div>
    </div>
</div>

<!-- MODAL EDITAR PROVEEDOR -->
<div id="modalEditarConductor" class="modal fade modal-forms" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <form role="form" id="formEditarConductor">
                <input type="hidden" name="idConductorEditar" id="idConductorEditar">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">EDITAR DATOS CONDUCTOR</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <select name="editarTipoDoc" id="editarTipoDoc" class="form-control">
                                            <option value="0">SIN DOCUMENTO</option>
                                            <option value="1">DNI</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control " name="editarDNI" id="editarDNI" placeholder="Ingresar D.N.I" required>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="editarNombre" id="editarNombre" placeholder="Ingresar Nombres" required>

                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="editarApellidos" id="editarApellidos" placeholder="Ingresar Apellidos" required>

                            </div>

                            <div class="row">
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="editarNumBrevete" id="editarNumBrevete" placeholder="Ingresar Num. brevete" required>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="editarNumPlaca" id="editarNumPlaca" placeholder="Ingresar Num. placa" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="editarCelular" id="editarCelular" placeholder="Ingresar número de célular">
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control " name="editarMarcaVehiculo" id="editarMarcaVehiculo" placeholder="Ingresar Marca de vehículo">
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

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btnEditarConductor">Modificar Conductor</button>

                </div>

                <?php

                // $editarUsuario = new ControladorUsuarios();
                // $editarUsuario->ctrEditarUsuario();

                ?>

            </form>

        </div>

    </div>

</div>

<?php
// $borrarUsuarios =  ControladorUsuarios::ctrBorrarUsuario();


?>

<div class="resultados"></div>