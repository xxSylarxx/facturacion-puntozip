<?php

use Controladores\ControladorCategorias;
use Controladores\ControladorUsuarios;

?>

<div class="content-wrapper panel-medio-principal">

    <div style="padding:5px"></div>
    <section class="container-fluid">
        <section class="content-header dashboard-header">
            <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
                <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

                    <div class="col-md-3 hidden-sm hidden-xs">
                        <button class=""><i class="fas fa-file-invoice"></i> Categoría</button>
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
                <h3 class="box-title">Administración accesos</h3>




            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">

                <form role="form" method="post" id="frmAccesosLink">



                    <div class="box-body">

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div id="respuestaAjax"></div>
                        <div class="kardex-contenedor">
                            <div class="form-group busca-pro-kardex select">

                                <!-- <label>BUSCAR EL PRODUCTO</label> -->
                                <select class="form-control select2" style="width: 100%;" name="idrol" id="idrol">
                                    <option value="">BUSCAR EL ROL</option>

                                    </option> <?php
                                                $item = null;
                                                $valor = null;
                                                $roles = ControladorUsuarios::ctrMostrarRoles($item, $valor);

                                                foreach ($roles as $k => $v) {

                                                    echo '
                                                        <option value="' . $v['id'] . '">' . $v['rol'] . '</option>
                                                        ';
                                                }

                                                ?>



                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- <input type="text" id="idrol" name="idrol" class="form-control" placeholder=""> -->

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                                <input type="text" class="form-control" name="nuevoacceso" id="nuevoacceso" placeholder="Ingresar nombre" required>

                            </div>

                        </div>
                        <div class="form-group">

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                                <input type="text" class="form-control" name="nuevolink" id="nuevolink" placeholder="Ingresar link" required>

                            </div>

                        </div>





                        <button type="submit" class="btn btn-primary pull-right" id="btmNuevoAcesos">Guardar Acceso</button>
                </form>
            </div>





        </div>

</div>
<!-- BOX FIN -->
<!-- /.box-footer -->
</section>

</div>

