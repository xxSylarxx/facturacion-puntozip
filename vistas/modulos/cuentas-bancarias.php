<?php

use Controladores\ControladorCuentasBanco;

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
                        <button class=""><i class="fas fa-file-invoice"></i> Cuentas</button>
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
                <h3 class="box-title">Administración de cuentas bancarias</h3>

                <?php
                if ($_SESSION['perfil'] == 'Administrador') {
                ?>
                    <button class="btn btn-success  pull-right btn-radius btn-no-user" data-toggle="modal" data-target="#modalAgregarBanco"><i class="fas fa-plus-square"></i>Nueva cuenta <i class="fas fa-user-plus"></i>
                    </button>
                <?php } ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body table-proveedores">
                <!-- table-bordered table-striped  -->
                <!-- table-bordered table-striped  -->
<table class="table  dt-responsive tablas tbl-t" width="100%">

<thead>
    <tr>
        <th style="width:10px;">#</th>
        <th>MONEDA</th>
        <th>TIPO</th>
        <th>BANCO</th>
        <th>TITULAR</th>
        <th>NÚMERO</th>
        <th>CCI</th>
        <th>DESCRIPCIÓN</th>
    </tr>
</thead>

<tbody>
    <?php
    $item = null;
    $valor = null;
    $cuentasBanco = ControladorCuentasBanco::ctrMostrarCuentasBanco($item, $valor);

    foreach ($cuentasBanco as $key => $value) :
        if($value['tipocuenta'] == 'cuenta_corriente'){
            $tipocuenta = 'CUENTA CORRIENTE';
        }
        if($value['tipocuenta'] == 'cuenta_ahorros'){
            $tipocuenta = 'CUENTA DE AHORROS';
        }
        if($value['tipocuenta'] == 'cuenta_detracciones'){
            $tipocuenta = 'CUENTA DE DETRACCIONES';
        }
        
    ?>
        <tr>
            <td><?php echo ++$key; ?></td>
            <td><?php echo $value['moneda'] ?></td>
            <td><?php echo $tipocuenta; ?></td>
            <td><?php echo $value['nombrebanco']; ?></td>
            <td><?php echo $value['titular']; ?></td>
            <td><?php echo $value['numerocuenta']; ?></td>
            <td><?php echo $value['numerocci']; ?></td>
            <td><?php echo $value['descripcion']; ?></td>

            <td>
                <div class="btn-group">

                    <button class="btn btn-warning btnEditarCuentaBancoItem" idCuentaB="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#modalEditarCuentaBanco"><i class="fas fa-user-edit"></i></button>
                    <?php
                    if ($_SESSION['perfil'] == 'Administrador') {
                    ?>
                        <button class="btn btn-danger btnEliminarCuentaBanco" idCuentaB="<?php echo $value['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
    <div class="resultadoCrearBanco"></div>
    <?php  ?>
    <!-- <button type="button" class="btn btn-primary printsave">Print</button>
<div class="printerhere" width="100%" style=""></div> -->
    <!-- <embed class="printerhere" src="" type="application/pdf" width="100%" height="600px" class="printerhere" /> -->

</div>

<!-- MODAL AGREGAR PROVEEDOR -->
<!-- Modal -->
<div id="modalAgregarBanco" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formNuevaCuentaBanco" class="">

                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR CUENTA BANCARIA</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Moneda</label>
                                    <div class="form-group">                                        
                                        <select name="monedacuenta" id="monedacuenta" class="form-control" style="width: 100%;">
                                            <option value="PEN">SOLES</option>
                                            <option value="USD">DÓLARES</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label for="">Tipo de cuenta</label>
                                    <div class="form-group">

                                        <select name="tipocuenta" id="tipocuenta" class="form-control" style="width: 100%;">
                                            <<option value="cuenta_corriente">CUENTA CORRIENTE</option>
                                            <option value="cuenta_ahorros">CUENTA DE AHORROS</option>
                                            <option value="cuenta_detracciones">CUENTA DE DETRACCIONES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="nombrebanco" id="nombrebanco" placeholder="Nombre del banco..." required>

                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="titular" id="titular" placeholder="Titular" required>

                                </div>

                                <div class="form-group">

                                    <input type="text" class="form-control " name="numerocuenta" id="numerocuenta" placeholder="Número de cuenta">

                                </div>
                                
                                <div class="form-group">

                                    <input type="text" class="form-control " name="numerocci" id="numerocci" placeholder="Número de CCI">

                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="cuentadescripcion" id="cuentadescripcion" placeholder="Descripción adicional(Opcional)">

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

                            <button class="btn btn-primary btn-agm btnCrearCuentaBanco">Guardar</button>

                        </div>



            </form>


        </div>
    </div>
</div>

<!-- MODAL EDITAR PROVEEDOR -->
<div id="modalEditarCuentaBanco" class="modal fade modal-forms" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <form role="form" id="formEditarCuentaBanco">

            <input type="hidden" name="idCuentaBanco" id="idCuentaBanco">
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">EDITAR CUENTA DE BANCO</h4>

                </div>

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Moneda</label>
                                    <div class="form-group">                                        
                                        <select name="emonedacuenta" id="emonedacuenta" class="form-control" style="width: 100%;">
                                        <option value="PEN">SOLES</option>
                                        <option value="USD">DÓLARES</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label for="">Tipo de cuenta</label>
                                    <div class="form-group">

                                        <select name="etipocuenta" id="etipocuenta" class="form-control" style="width: 100%;">
                                            <option value="cuenta_corriente">CUENTA CORRIENTE</option>
                                            <option value="cuenta_ahorros">CUENTA DE AHORROS</option>
                                            <option value="cuenta_detracciones">CUENTA DE DETRACCIONES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="enombrebanco" id="enombrebanco" placeholder="Nombre del banco..." required>

                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="etitular" id="etitular" placeholder="Titular" required>

                                </div>

                                <div class="form-group">

                                    <input type="text" class="form-control " name="enumerocuenta" id="enumerocuenta" placeholder="Número de cuenta">

                                </div>
                                
                                <div class="form-group">

                                    <input type="text" class="form-control " name="enumerocci" id="enumerocci" placeholder="Número de CCI">

                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control " name="ecuentadescripcion" id="ecuentadescripcion" placeholder="Descripción adicional(Opcional)">

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

                        <button type="submit" class="btn btn-primary btn-agm btnEditarCuentaBanco">Modificar Cuenta</button>

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