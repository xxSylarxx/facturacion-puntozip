<?php

use Controladores\ControladorProveedores;
use Controladores\ControladorClientes;

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
                        <button class=""><i class="fas fa-file-invoice"></i> Proveedores</button>
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
                <h3 class="box-title">Administración de Proveedores</h3>

                <?php
                if ($_SESSION['perfil'] == 'Administrador') {
                ?>
                    <button class="btn btn-success  pull-right btn-radius btn-no-user" data-toggle="modal" data-target="#modalAgregarProveedor"><i class="fas fa-plus-square"></i>Nuevo proveedor <i class="fas fa-user-plus"></i>
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
                            <th>Nombre o Razón Social</th>
                            <th>DIRECCIÓN</th>
                            <th>EMAIL</th>
                            <th>N° CELULAR</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $proveedores = ControladorProveedores::ctrMostrarProveedores($item, $valor);

                        foreach ($proveedores as $key => $value) :
                            $rucdni = $value['ruc'] != null ? $value['ruc'] : $value['documento'];
                            $nombrerazon = $value['nombre'] != null ? $value['nombre'] : $value['razon_social'];
                        ?>
                            <tr>
                                <td><?php echo ++$key; ?></td>
                                <td><?php echo $rucdni; ?></td>
                                <td><?php echo $nombrerazon; ?></td>
                                <td><?php echo $value['direccion']; ?></td>
                                <td><?php echo $value['email']; ?></td>
                                <td><?php echo $value['telefono']; ?></td>

                                <td>
                                    <div class="btn-group">

                                        <button class="btn btn-warning btnEditarProveedorItem" idProveedor="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#modalEditarProveedor"><i class="fas fa-user-edit"></i></button>
                                        <?php
                                        if ($_SESSION['perfil'] == 'Administrador') {
                                        ?>
                                            <button class="btn btn-danger btnEliminarProveedor" idProveedor="<?php echo $value['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
    <div class="resultadoCrearProveedor"></div>
    <?php  ?>
    <!-- <button type="button" class="btn btn-primary printsave">Print</button>
<div class="printerhere" width="100%" style=""></div> -->
    <!-- <embed class="printerhere" src="" type="application/pdf" width="100%" height="600px" class="printerhere" /> -->

</div>

<!-- MODAL AGREGAR PROVEEDOR -->
<!-- Modal -->
<div id="modalAgregarProveedor" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formNuevoProveedor" class="">
                <input type="hidden" id="insertaProveedor" name="insertaProveedor" value="insertaProveedor">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR PROVEEDOR</h4>

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

                                        <select name="nuevotipodoc" id="nuevotipodoc" class="form-control">
                                            <option value="6">RUC</option>
                                            <option value="1">DNI</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control " name="nuevoRuc" id="nuevoRuc" placeholder="Ingresar R.U.C." required>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="nuevaRazon" id="nuevaRazon" placeholder="Ingresar Razón Social" required>

                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>

                            </div>

                            <div class="form-group">

                                <input type="text" class="form-control " name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar correo electrónico">

                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <!-- <input type="text" class="form-control " name="nuevoUbigeo" id="nuevoUbigeo" placeholder="Ingresar Ubigeo"> -->
                                        <select class="form-control select2" style="width: 100%;" name="nuevoUbigeo" id="nuevoUbigeo" ">
                                    <option value="">BUSCAR UBIGEO</option>
                                    <?php

                                    $ubigeos = ControladorClientes::ctrBuscarUbigeo();

                                    foreach ($ubigeos as $k => $v) {

                                        echo '<option value="' . $v['ubigeo'] . '">' . $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name'] . '</option>
                                                        ';
                                    }

                                    ?>
                                  </select>

                                    </div>
                                </div>
                                <div class=" col-md-6">
                                            <!-- ENTRADA PARA LA CONTRASEÑA -->
                                            <div class="form-group">

                                                <input type="text" class="form-control " name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar Celular">

                                            </div>
                                    </div>
                                </div>

                                <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

                                <div class="form-group">


                                </div>
                            </div>

                        </div>

                    </div>

                    <!--=====================================
        PIE DEL MODAL
        ======================================-->

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                        <button class="btn btn-primary btn-agm btnCrearProveedor">Guardar</button>

                    </div>



            </form>


        </div>
    </div>
</div>

<!-- MODAL EDITAR PROVEEDOR -->
<div id="modalEditarProveedor" class="modal fade modal-forms" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <form role="form" id="formEditarProveedor">
                <input type="hidden" name="idProveedorEditar" id="idProveedorEditar">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">EDITAR PROVEEDEOR</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <!-- ENTRADA PARA EL NOMBRE -->

                            <div class="form-group">

                                <input type="text" class="form-control " name="editarRuc" id="editarRuc" placeholder="Ingresar R.U.C." required>

                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="editarRazon" id="editarRazon" placeholder="Ingresar Razón Social" required>

                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control " name="editarDireccion" id="editarDireccion" placeholder="Ingresar dirección" required>

                            </div>

                            <div class="form-group">

                                <input type="text" class="form-control " name="editarEmail" id="editarEmail" placeholder="Ingresar correo electrónico">

                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <!-- <input type="text" class="form-control " name="editarUbigeo" id="editarUbigeo" placeholder="Ingresar Ubigeo"> -->
                                        <select class="form-control select2" style="width: 100%;" name="editarUbigeo" id="editarUbigeo" ">
                                    <option value="">BUSCAR UBIGEO</option>
                                    <?php

                                    $ubigeos = ControladorClientes::ctrBuscarUbigeo();

                                    foreach ($ubigeos as $k => $v) {

                                        echo '<option value="' . $v['ubigeo'] . '">' . $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name'] . '</option>
                                                        ';
                                    }

                                    ?>
                                  </select>

                                    </div>
                                </div>
                                <div class=" col-md-6">
                                            <!-- ENTRADA PARA LA CONTRASEÑA -->
                                            <div class="form-group">

                                                <input type="text" class="form-control " name="editarTelefono" id="editarTelefono" placeholder="Ingresar Celular">

                                            </div>
                                    </div>
                                </div>

                                <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

                                <div class="form-group">


                                </div>
                            </div>

                        </div>

                    </div>

                    <!--=====================================
        PIE DEL MODAL
        ======================================-->

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                        <button type="submit" class="btn btn-primary btn-agm btnEditarProveedor">Modificar Proveedor</button>

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