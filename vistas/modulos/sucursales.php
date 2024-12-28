<?php

use Controladores\ControladorEmpresa;

use Controladores\ControladorClientes;

$emisor = ControladorEmpresa::ctrEmisor();
?>
<div class="content-wrapper panel-medio-principal">


    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Sucursales</button>
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
                <h3 class="box-title">Administración de sucursales</h3>

                <?php
                if ($_SESSION['perfil'] == 'Administrador') {
                ?>
                    <button class="btn btn-success  pull-right btn-radius btn-no-user" data-toggle="modal" data-target="#modalAgregarSucursal"><i class="fas fa-plus-square"></i>Nueva sucursal<i class="fas fa-user-plus"></i>
                    </button>
                <?php } ?>

            </div>

            <!-- /.box-header -->
            <div class="box-body table-sucursales">




            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>

    <!-- <button type="button" class="btn btn-primary printsave">Print</button>
<div class="printerhere" width="100%" style=""></div> -->
    <!-- <embed class="printerhere" src="" type="application/pdf" width="100%" height="600px" class="printerhere" /> -->

</div>

<!-- MODAL AGREGAR SUCURSAL -->
<!-- Modal -->
<div id="modalAgregarSucursal" class="modal fade modal-forms fullscreen-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formSucursal" class="form-inserta">
                <input type="hidden" name="id_empresa" id="id_empresa" value="<?php echo $emisor['id']; ?>">
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR SUCURSAL</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="advertencia-sucursal">TODOS LOS CAMPOS SON OBLIGATORIOS</div>
                        <div class="col-md-7">
                            <!-- ENTRADA PARA EL NOMBRE -->

                            <div id="respuestaAjax"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Código Sucursal SUNAT</label>
                                        <input type="text" class="form-control " name="codigo" id="codigo" placeholder="Ingresar código" required>

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label for="">Nombre Sucursal || Almacén</label>
                                    <div class="form-group">

                                        <input type="text" class="form-control " name="nombre" id="nombre" placeholder="Ingresar nombre Sucursal/Almacén" required>

                                    </div>
                                </div>
                            </div>
                            <!-- ENTRADA PARA EL USUARIO -->
                            <div class="box">
                                <div class="form-group">

                                    <input type="text" class="form-control " name="direccion" id="direccion" placeholder="Ingresar direccion" required>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control ubigeo-sucursal" name="ubigeo" id="ubigeo" style="width: 100%;">
                                            <option value="">INGRESE UBIGEO</option>
                                            <?php
                                            $ubigeos = ControladorClientes::ctrBuscarUbigeo();
                                            foreach ($ubigeos as $key => $val) {


                                                echo '<option value="' . $val['ubigeo'] . '">' . $val['ubigeo'] . ' ' . $val['nombre_distrito'] . ' - ' . $val['nombre_provincia'] . ' - ' . $val['name'] . '</option>';
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="pais" id="pais" value="PE">
                                    <div class="form-group">

                                        <input type="text" class="form-control" name="departamento" id="departamento" placeholder="Departamento" readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="provincia" id="provincia" placeholder="Provincia" readonly>

                                </div>

                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="pais" id="pais" value="PE">
                                    <div class="form-group">

                                        <input type="text" class="form-control" name="distrito" id="distrito" placeholder="Distrito" readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="ingrese teléfono">

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control" name="correo" id="correo" placeholder="Ingrese correo electrónico">

                                    </div>
                                </div>
                            </div>

                            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->



                            <div class="info-series">SE INFORMA QUE LAS SERIES Y CORRELATIVOS SON ÚNICOS PARA CADA SUCURSAL Y NO DEBEN REPETIRSE, cambie el número 1 de cada serie por el número 2, por ejemplo: si va a crear otra sucursal debe cambiar la serie al número 2 (F002, B002, FN02...) </div>
                        </div>
                        <div class="col-md-5">
                            <!-- ENTRADA PARA SUBIR FOTO -->

                            <table class="series-sucursales">
                                <thead>
                                    <tr>
                                        <th>TIPO COMPROBANTE</th>
                                        <th>SERIE</th>
                                        <th>CORRELATIVO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Factura Electrónica</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="01">
                                        <td><input type="text" name="serie[]" id="serie" value="F001"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Boleta Electrónica</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="03">
                                        <td><input type="text" name="serie[]" id="serie" value="B001"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Nota de crédito modifica Factura</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="07">
                                        <td><input type="text" name="serie[]" id="serie" value="FN01"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Nota de Crédito modifica una Boleta</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="07">
                                        <td><input type="text" name="serie[]" id="serie" value="BN01"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Nota de Débito modifica una Factura</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="08">
                                        <td><input type="text" name="serie[]" id="serie" value="FD01"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Nota de Débito modifica una Boleta</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="08">
                                        <td><input type="text" name="serie[]" id="serie" value="BD01"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Guía de Remisión</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="09">
                                        <td><input type="text" name="serie[]" id="serie" value="T001"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Nota de venta</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="02">
                                        <td><input type="text" name="serie[]" id="serie" value="N001"></td>
                                        <td><input type="number" min="0" max="10" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Cotizaciones</td>
                                        <input type="hidden" name="tipocomp[]" id="tipocomp" value="00">
                                        <td><input type="text" name="serie[]" id="serie" value="C001"></td>
                                        <td><input type="text" name="ccorrelativo[]" id="ccorrelativo" value="0"></td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btnGuardarSucursal">Guardar</button>

                </div>

                <?php

                // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

                ?>

            </form>


        </div>
    </div>
</div>




<div id="modalEditarSucursal" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formEditarSucursal" class="form-inserta">
                <input type="hidden" name="eidsucursal" id="eidsucursal" value="">
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">EDITAR SUCURSAL</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="advertencia-sucursal">TODOS LOS CAMPOS SON OBLIGATORIOS</div>
                        <div class="col-md-12">
                            <!-- ENTRADA PARA EL NOMBRE -->

                            <div id="respuestaAjax"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Código Sucursal SUNAT</label>
                                        <input type="text" class="form-control " name="ecodigo" id="ecodigo" placeholder="Ingresar código sucursal SUNAT" required>

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label for="">Nombre Sucursal || Almacén</label>
                                    <div class="form-group">

                                        <input type="text" class="form-control " name="enombre" id="enombre" placeholder="Ingresar nombre Sucursal/Almacén" required>

                                    </div>
                                </div>
                            </div>
                            <!-- ENTRADA PARA EL USUARIO -->
                            <div class="box">
                                <div class="form-group">

                                    <input type="text" class="form-control " name="edireccion" id="edireccion" placeholder="Ingresar direccion" required>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="select2" name="eubigeo" id="eubigeo" style="width: 100%;">
                                            <option value="">INGRESE UBIGEO</option>
                                            <?php
                                            $ubigeos = ControladorClientes::ctrBuscarUbigeo();
                                            foreach ($ubigeos as $key => $v) {


                                                echo '<option value="' . $v['ubigeo'] . '">' . $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name'] . '</option>
                                                        ';
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="epais" id="epais" value="PE">
                                    <div class="form-group">

                                        <input type="text" class="form-control" name="edepartamento" id="edepartamento" placeholder="Departamento" readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="eprovincia" id="eprovincia" placeholder="Provincia" readonly>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control" name="edistrito" id="edistrito" placeholder="Distrito" readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="etelefono" id="etelefono" placeholder="ingrese teléfono">

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <input type="text" class="form-control" name="ecorreo" id="ecorreo" placeholder="Ingrese correo electrónico">

                                    </div>
                                </div>
                            </div>

                            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->


                            <!-- 
                            <div class="info-series">SE INFORMA QUE LAS SERIES Y CORRELATIVOS SON ÚNICOS PARA CADA SUCURSAL Y NO DEBEN REPETIRSE</div> -->
                        </div>

                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btnEditarSucursales">EDITAR</button>

                </div>



            </form>


        </div>
    </div>
</div>