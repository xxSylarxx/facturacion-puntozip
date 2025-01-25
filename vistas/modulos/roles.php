<?php
// session_start();

use Controladores\ControladorUsuarios;

$item = 'rol';
$valor = $_SESSION['perfil'];
$roles = ControladorUsuarios::ctrMostrarRoles($item, $valor);
?>
<div class="content-wrapper panel-medio-principal">

    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px;  border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Roles</button>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12 btns-dash">

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
                <h3 class="box-title">Administración de roles</h3>

                <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarRol"><i class="fas fa-plus-square"></i>Nuevo rol <i class="fas fa-user-plus"></i>
                </button>


            </div>

            <!-- /.box-header -->
            <div class="box-body table-user">

                <!-- table-bordered table-striped  -->
                <div class="table-responsive contenido-roles">


                </div>


            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>

</div>

<!-- MODAL AGREGAR ROLES-->
<!-- Modal -->
<div id="modalAgregarRol" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formRol" class="form-insertaUser">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR ROL</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <!-- ENTRADA PARA EL NOMBRE -->
                            <div id="respuestaAjax"></div>

                            <div class="form-group">

                                <input type="text" class="form-control " name="rolUsuario" id="rolUsuario" placeholder="Ingresar Rol" required>

                            </div>





                        </div>

                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger  pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btnrol">Guardar</button>

                </div>

                <?php

                // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

                ?>

            </form>


        </div>
    </div>
</div>


<!-- MODAL AGREGAR ACCESOS A ROLES -->
<!-- Modal -->
<div id="modalAgregarAccesoRol" class="modal fade modal-forms fullscreen-modal" role=" dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formAccesoRol" class="form-insertaAcceso">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR ROL</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <!-- ENTRADA PARA EL NOMBRE -->
                            <div id="respuestaAjax"></div>

                            <div class="">
                                <div class="custom-control custom-checkbox contenedor-roles-ch">
                                    <input type="hidden" name="idRol" id="idRol" class="form-control">
                                    <label class="custom-control-label rol-total" for="todosroles"><input type="checkbox" class="custom-control-input todosroles" id="todosroles">
                                        Todos</label>
                                    <label class="custom-control-label" for="accesoroles"><input type="checkbox" class="custom-control-input" id="accesoroles" name="accesoroles[]" value="inicio">
                                        Inicio</label>

                                    <label class="custom-control-label" for="1accesoroles"><input type="checkbox" class="custom-control-input" id="1accesoroles" name="accesoroles[]" value="usuarios">
                                        Usuarios</label>
                                    <label class="custom-control-label" for="accesoroles2"> <input type="checkbox" class="custom-control-input" id="accesoroles2" name="accesoroles[]" value="categorias">
                                        Categorias</label>
                                    <label class="custom-control-label" for="accesoroles3"><input type="checkbox" class="custom-control-input" id="accesoroles3" name="accesoroles[]" value="productos">
                                        Productos</label>
                                    <label class="custom-control-label" for="accesoroles4"><input type="checkbox" class="custom-control-input" id="accesoroles4" name="accesoroles[]" value="clientes">
                                        Clientes</label>
                                    <label class="custom-control-label" for="accesoroles5"><input type="checkbox" class="custom-control-input" id="accesoroles5" name="accesoroles[]" value="ventas">
                                        Ventas</label>
                                    <label class="custom-control-label" for="accesoroles6"><input type="checkbox" class="custom-control-input" id="accesoroles6" name="accesoroles[]" value="crear-factura">
                                        Crear factura</label>
                                    <label class="custom-control-label" for="accesoroles7"><input type="checkbox" class="custom-control-input" id="accesoroles7" name="accesoroles[]" value="crear-boleta">
                                        Crear boleta</label>
                                    <label class="custom-control-label" for="accesoroles8"><input type="checkbox" class="custom-control-input" id="accesoroles8" name="accesoroles[]" value="crear-nota">
                                        Crear nota</label>
                                    <label class="custom-control-label" for="accesoroles9"><input type="checkbox" class="custom-control-input" id="accesoroles9" name="accesoroles[]" value="nota-credito">
                                        Crear nota créito</label>
                                    <label class="custom-control-label" for="accesoroles10"><input type="checkbox" class="custom-control-input" id="accesoroles10" name="accesoroles[]" value="nota-debito">
                                        Crear nota débito</label>
                                    <label class="custom-control-label" for="accesoroles28"><input type="checkbox" class="custom-control-input" id="accesoroles28" name="accesoroles[]" value="crear-cotizacion">
                                        Crear cotización</label>
                                    <label class="custom-control-label" for="accesoroles11"><input type="checkbox" class="custom-control-input" id="accesoroles11" name="accesoroles[]" value="crear-guia">
                                        Crear guía</label>
                                    <label class="custom-control-label" for="accesoroles12"><input type="checkbox" class="custom-control-input" id="accesoroles12" name="accesoroles[]" value="nueva-compra">
                                        Compras</label>
                                    <label class="custom-control-label" for="accesoroles13"><input type="checkbox" class="custom-control-input" id="accesoroles13" name="accesoroles[]" value="reportes">
                                        Reportes</label>
                                    <label class="custom-control-label" for="accesoroles14"><input type="checkbox" class="custom-control-input" id="accesoroles14" name="accesoroles[]" value="empresa">
                                        Configurar empresa</label>
                                    <label class="custom-control-label" for="accesoroles15"><input type="checkbox" class="custom-control-input" id="accesoroles15" name="accesoroles[]" value="resumen-diario">
                                        Resúmen diario</label>
                                    <label class="custom-control-label" for="accesoroles16"><input type="checkbox" class="custom-control-input" id="accesoroles16" name="accesoroles[]" value="reporte-ventas">
                                        Reporte de ventas</label>
                                    <label class="custom-control-label" for="accesoroles17"><input type="checkbox" class="custom-control-input" id="accesoroles17" name="accesoroles[]" value="reporte-compras">
                                        Reporte de compras</label>
                                    <label class="custom-control-label" for="accesoroles18"><input type="checkbox" class="custom-control-input" id="accesoroles18" name="accesoroles[]" value="ver-guias">
                                        Listar guías</label>
                                    <label class="custom-control-label" for="accesoroles19"><input type="checkbox" class="custom-control-input" id="accesoroles19" name="accesoroles[]" value="listar-cotizaciones">
                                        Listar cotizaciones</label>
                                    <label class="custom-control-label" for="accesoroles20"><input type="checkbox" class="custom-control-input" id="accesoroles20" name="accesoroles[]" value="consulta-comprobante">
                                        Consultar comprobantes SUNAT</label>

                                    <label class="custom-control-label" for="accesoroles22"><input type="checkbox" class="custom-control-input" id="accesoroles22" name="accesoroles[]" value="unidad-medida">
                                        Administrar unidades de medida</label>
                                    <label class="custom-control-label" for="accesoroles23"><input type="checkbox" class="custom-control-input" id="accesoroles23" name="accesoroles[]" value="roles">
                                        Administrar roles</label>
                                    <label class="custom-control-label" for="accesoroles24"><input type="checkbox" class="custom-control-input" id="accesoroles24" name="accesoroles[]" value="inventario">
                                        Administrar inventario</label>
                                    <label class="custom-control-label" for="accesoroles24"><input type="checkbox" class="custom-control-input" id="accesoroles24" name="accesoroles[]" value="kardex">
                                        Administrar kardex</label>
                                    <label class="custom-control-label" for="accesoroles25"><input type="checkbox" class="custom-control-input" id="accesoroles25" name="accesoroles[]" value="cajas">
                                        Administrar cajas</label>
                                    <label class="custom-control-label" for="accesoroles26"><input type="checkbox" class="custom-control-input" id="accesoroles26" name="accesoroles[]" value="arqueo-caja">
                                        Arqueo caja</label>
                                    <label class="custom-control-label" for="accesoroles27"><input type="checkbox" class="custom-control-input" id="accesoroles27" name="accesoroles[]" value="gastos">
                                        Gastos</label>
                                    <label class="custom-control-label" for="proveedores28"><input type="checkbox" class="custom-control-input" id="proveedores28" name="accesoroles[]" value="proveedores">
                                        Proveedores</label>
                                    <label class="custom-control-label" for="pos29"><input type="checkbox" class="custom-control-input" id="pos29" name="accesoroles[]" value="pos">
                                        POS</label>
                                    <label class="custom-control-label" for="pos30"><input type="checkbox" class="custom-control-input" id="pos30" name="accesoroles[]" value="sucursales">
                                        Acceso Roles</label>
                                    <label class="custom-control-label" for="pos30"><input type="checkbox" class="custom-control-input" id="pos30" name="accesoroles[]" value="ver-guias-retorno">
                                        Guias Retorno</label>
                                    <label class="custom-control-label" for="pos31"><input type="checkbox" class="custom-control-input" id="pos31" name="accesoroles[]" value="accesos">
                                        ACCESOS</label>

                                    <label class="custom-control-label" for="pos32"><input type="checkbox" class="custom-control-input" id="pos32" name="accesoroles[]" value="cambio-precio">
                                        CAMBIAR PRECIO</label>

                                    <label class="custom-control-label" for="pos33"><input type="checkbox" class="custom-control-input" id="pos33" name="accesoroles[]" value="cuentas-bancarias">
                                        CUENTAS BANCARIAS</label>
                                    <label class="custom-control-label" for="pos34"><input type="checkbox" class="custom-control-input" id="pos34" name="accesoroles[]" value="conductores">
                                        CONDUCTORES</label>
                                </div>


                            </div>





                        </div>

                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger btn-amg pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i>Salir</button>

                    <button type="submit" class="btn btn-primary btnrol btn-agm">Guardar</button>

                </div>

                <?php

                // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

                ?>

            </form>


        </div>
    </div>
</div>


<!-- MODAL AGREGAR ACCESOS A ROLES -->
<!-- Modal -->
<div id="modalAgregarEditarRol" class="modal fade modal-forms fullscreen-modal" role=" dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formEditarAcceso" class="form-editar-acceso">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">EDITAR ACCESOS</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <!-- ENTRADA PARA EL NOMBRE -->
                            <div id="respuestaAjax" class=""></div>

                            <div class="editar-roles">
                                <div class="custom-control custom-checkbox contenedor-roles-ch">
                                    <input type="hidden" name="idRole" id="idRole" class="form-control">
                                    <!-- <label class="custom-control-label rol-total" for="todosroles"><input type="checkbox" class="custom-control-input todosroles" id="todosroles">
                                        Todos</label> -->

                                    <?php


                                    $item = 'id_rol';
                                    $valor = $roles['id'];

                                    $accesos = ControladorUsuarios::ctrMostrarAccesosid($item, $valor);
                                    foreach ($accesos as $k => $a) {
                                        // echo $a['id_rol'];
                                        if ($a['activo'] == 's') {

                                    ?>
                                            <label class="custom-control-label" for="accesoroles<?php echo $a['id'] ?>"><input type="checkbox" class="custom-control-input" id="accesoroles<?php echo $a['id'] ?>" name="accesoroles[]" value="<?php echo $a['linkacceso'] ?>" checked="checked">
                                                <?php echo $a['nombreacceso'] ?></label>

                                        <?php
                                        }
                                        if ($a['activo'] == 'n') {
                                             //echo $a['id_rol'];


                                        ?>
                                            <label class="custom-control-label" for="accesoroles<?php echo $a['id'] ?>"><input type="checkbox" class="custom-control-input" id="accesoroles<?php echo $a['id'] ?>" name="accesoroles[]" value="<?php echo $a['linkacceso'] ?>">
                                                <?php echo $a['nombreacceso'] ?></label>

                                    <?php
                                        }
                                    }

                                    ?>
                                </div>


                            </div>





                        </div>

                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <!-- <button type="submit" class="btn btn-primary btnrol">Guardar</button> -->

                </div>

                <?php

                // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

                ?>

            </form>


        </div>
    </div>
</div>

<script>


</script>