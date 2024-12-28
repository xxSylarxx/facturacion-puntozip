<?php

use Controladores\ControladorSucursal;
use Controladores\ControladorProductos;
use Controladores\ControladorCategorias;
use Controladores\ControladorSunat;
use Controladores\ControladorCuentasBanco;

$item = null;
$valor = null;
$categorias =  ControladorCategorias::ctrMostrarCategorias($item, $valor);

$sucursal = ControladorSucursal::ctrSucursal();
?>
<div class="content-wraper pos-contenedoor">
    <button class="inicio-pos"><i class="fas fa-home fa-lg bg-menu"></i></button>
    <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"] ?>">
    <section class="container-fluid pos-contenedor-fluido">
        <!-- BOX INI -->
        <div class="box rounded rounded-pos ">

            <div class="container-comprobants-pos">
                <input type="radio" class="tipo_comprobante" name="tipo_comprobante" id="option1" autocomplete="off" checked value="01">
                <label class="btn factura-pos" for="option1">FACTURA</label>

                <input type="radio" class="tipo_comprobante" name="tipo_comprobante" id="option2" autocomplete="off" value="03">
                <label class="btn boleta-pos" for="option2">BOLETA</label>

                <input type="radio" class="tipo_comprobante" name="tipo_comprobante" id="option3" autocomplete="off" value="02">
                <label class="btn nota-pos" for="option3">NOTA VENTA</label>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <!-- FORMULARIO -->

                    <div class="col-md-7 col-xs-12 super-contenedor-productos-pos">

                        <div class="row contenedor-productos-ventas">
                            <div class="col-lg-12 ">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" id="selectnum" name="selectnum" onchange="loadProductosPos(1)">
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control select2" id="categorias" name="categorias" onchange="loadProductosPos(1)">
                                            <option value="">Categoría</option>
                                            <?php
                                            foreach ($categorias as $k => $v) {
                                                echo '<option value="' . $v['id'] . '">' . $v['categoria'] . '</option>';
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-7">
                                    <div class="form-group">

                                        <input type="search" class="form-control" id="searchProductoV" name="searchProductoV" placeholder="Buscar producto o servicio..." onkeyup="loadProductosPos(1)" style="width:100%">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="productos-pos"></div>
                        <!-- </form> -->
                    </div>

                    <div class="col-md-5 col-xs-12 super-contenedor-formulario-pos">

                        <div class="box box-success contenedor-totales-ventas-pos" style="border-top: 0px;">
                            <div class="box-header" style="border: 0px; padding:0px;">
                                <div class="col-md-12 row-sucursal seccion-sucursal">
                                    <?php
                                    if ($_SESSION['perfil'] == 'Administrador') {
                                        echo '
                    <select class="form-control select2" name="idcSucursal" id="idcSucursal" style="width: 100%;" onchange="loadProductosPos(1)">';

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
                    <input type="hidden" name="idcSucursal" id="idcSucursal" value="' . $sucursal['id'] . '">';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                            $numCorrelativo = '';
                            if (isset($_POST['numCorrelativo'])) {
                                $numCorrelativo = $_POST['numCorrelativo'];
                                $numCorrelativo;
                            }
                            ?>

                            <form role="form" method="post" class="formVenta" id="formVentaPos" enctype="multipart/form-data">

                                <div class="super-contenedor-herramientas-pos">
                                    <label for="btnMenuHerramientas" class="btn-h-pos"><i class="fa fa-cog"></i></label>
                                    <input type="checkbox" id="btnMenuHerramientas">
                                    <div class="contenedor-herramientas-pos">


                                        <input type="radio" class="herramientas_pos" name="herramientas_pos" id="optionh1" autocomplete="off" checked value="01">
                                        <label class="btn btn-secondary" for="optionh1"><i class="fas fa-money-bill-wave"></i> MONEDA</label>

                                        <input type="radio" class="herramientas_pos" name="herramientas_pos" id="optionh2" autocomplete="off" value="02">
                                        <label class="btn btn-secondary" for="optionh2"><i class="far fa-paper-plane"></i> ENVÍO EMAIL</label>

                                        <input type="radio" class="herramientas_pos" name="herramientas_pos" id="optionh3" autocomplete="off" value="03">
                                        <label class="btn btn-secondary" for="optionh3"><i class="fas fa-comment-dots"></i> OBSERVACIONES</label>


                                        <input type="radio" class="herramientas_pos" name="herramientas_pos" id="optionh4" autocomplete="off" value="04">
                                        <label class="btn btn-secondary" for="optionh4"><i class="fas fa-warehouse"></i> SUCURSALES</label>
                                        
                                        <input type="radio" class="herramientas_pos" name="herramientas_pos" id="optionh5" autocomplete="off" value="05">
                                        <label class="btn btn-secondary" for="optionh5"><i class="fas fa-calculator"></i> DETRACCIONES</label>

                                        <input type="radio" class="herramientas_pos   envio-sunat-pos" name="envioSunat" id="firmar" value="firmar">

                                        <label class="btn btn-secondary firmar-c" for="firmar"><i class="fa fa-print"></i> SOLO FIRMAR E IMPRIMIR</label>

                                        <input type="radio" class="herramientas_pos   envio-sunat-pos" name="envioSunat" id="enviar" value="enviar" checked>
                                        <label class="btn btn-secondary enviar-c" for="enviar"><i class="fas fa-signal"></i> ENVIAR A SUNAT</label>
                                        <label for="">
                                            <button class="btn btn-danger btn-menup dia" onclick="changep(1)" idp="claro"></button>
                                            <button class="btn btn-danger btn-menup noche" onclick="changep(2)" idp="oscuro"></button>
                                        </label>
                                    </div>
                                </div>




                                <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"] ?>">
                                <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
                                <input type="hidden" class="" id="fecha" name="fecha" value="<?php echo date("Y-m-d") ?>">
                                <input type="hidden" class="" id="serieCorrelativo" name="serieCorrelativo" value="<?php echo $numCorrelativo; ?>">

                                <input type="hidden" id="afectoigv" name="afectoigv" value="<?php echo $emisor['afectoigv'] ?>">
                                <input type="hidden" id="icbper" name="icbper" value="<?php echo $emisor['icbper'] ?>">

                                <div class="box-body" style="border: 0px; padding-top:0px; ">

                                    <!-- PRIMERA ENTRADA FORM -->
                                    <div class="box" style="border: 0px; padding-top:0px;">
                                        <div class="row seccion-fechas-moneda-correlativo">
                                            <!-- ENTRADA TIPO MONEDA-->
                                            <div class="col-md-4 col-xs-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-money-bill"></i></span>
                                                        <select class="form-control" id="monedaPos" name="monedaPos">
                                                            <option value="PEN">Soles (S/)</option>
                                                            <option value="USD">Dólares Americanos ($)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ENTRADA SERIE-->
                                            <div class="col-md-4 col-xs-6">
                                                <div class="form-group">
                                                    <div class="input-group">

                                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>

                                                        <select class="form-control" name="serie" id="serie" value="">
                                                            <?php
                                                            if ($_GET["ruta"] == "pos") {

                                                                $valor = "03";
                                                                $id_sucursal = $sucursal['id'];
                                                                $serieComprobante = ControladorSunat::ctrMostrarSerie($valor, $id_sucursal);
                                                                foreach ($serieComprobante as $key => $value) {
                                                                    echo '<option value=' . $value['id'] . ' id="idSerie">' . $value['serie'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ENTRADA FECHA DOC-->
                                            <div class="col-md-4 col-xs-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        <input type="text" class="form-control" name="fechaDoc" id="fechaDoc" value="<?php echo date("d/m/Y") ?>" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ENTRADA FECHA VENCIMIENTO-->
                                            <!-- <div class="col-md-3 col-xs-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> -->
                                            <input type="hidden" class="form-control" name="fechaVence" id="fechaVence" value="<?php echo date("d/m/Y") ?>" required>
                                            <!-- </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-6">
                                                <input type="hidden" class="form-control" id="correlativo">
                                            </div>
                                        </div>
                                        <!-- ENTRADA CLIENTE -->
                                        <div class="row">
                                            <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Cliente:</legend>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <!-- ID CLIENTE -->
                                                        <input type="hidden" name="idCliente" id="idCliente">

                                                        <span class="input-group-addon"><i class="fas fa-id-card"></i></span>

                                                        <select class="form-control" name="tipoDoc" id="tipoDoc">
                                                            <?php
                                                            $item = null;
                                                            $valor = null;
                                                            $tipoDocumento = ControladorSunat::ctrMostrarTipoDocumento($item, $valor);
                                                            foreach ($tipoDocumento as $key => $value) {

                                                                echo "<script></script>";

                                                                echo '<option value=' . $value['codigo'] . '>' . $value['descripcion'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ENTRADA DOCUMENTO -->
                                            <div class="col-md-6">
                                                <div id="reloadC"></div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div id="rucActivo"></div>
                                                        <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">
                                                        <span class="input-group-addon btn buscarRuc"><i class="fa fa-search"></i></span>
                                                        <div id="reloadC"></div>
                                                        <div class="resultadoCliente" idCliente=""><a href="#" class="btn-add"></a></div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <!-- ENTRADA CLIENTE 2 -->
                                        <div class="row">
                                            <!-- ENTRADA RESULTADO DOCUMENTO -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-group-adddon">
                                                        <input type="text" class="form-control" id="razon_social"" name=" razon_social" placeholder="Ingrese nombre o razón social...">
                                                        <!-- <span class="input-group-addon"></span>  -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ENTRADA DOCUMENTO -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-group-adddon">
                                                        <input type="text" class="form-control" id="direccion"" name=" direccion" placeholder="Ingrese la dirección...">
                                                        <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ENTRADA DOCUMENTO -->
                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group-adddon"> -->
                                            <input type="hidden" class="form-control" id="ubigeo"" name=" ubigeo" placeholder="Ingrese codigo de ubigeo...">
                                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                                            <!-- </div>
                                                </div>
                                            </div> -->
                                            <!-- ENTRADA RESULTADO DOCUMENTO -->
                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group-adddon"> -->
                                            <input type="hidden" class="form-control" id="celular"" name=" celular" placeholder="Ingrese su número de celular...">
                                            <!-- <span class="input-group-addon"></span>  -->
                                            <!-- </div>
                                                </div>
                                            </div> -->

                                        </div>
                                        <!-- ENTRADA PARA ENVÍO DE EMAIL =============== -->
                                        <div class="row seccion-coreo-cuotas" style="margin-bottom: 7px; padding-bottom: 4px;">
                                            <div class="col-md-12">


                                                <label style="border-style:none;" id="emailtext" for=""> ¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?</label>

                                                <div class="modo-contenedor-email">
                                                    <label for="si" id="sie">Sí</label>
                                                    <input type="radio" class="modoemail" id="si" name="modoemail" value="s">
                                                    <label for="no" id="noe">No</label>
                                                    <input type="radio" class="modoemail" id="no" name="modoemail" value="n" checked="checked">
                                                </div>


                                                <div class="email-colunma" style="margin-top:5px;">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-at"></i></span>
                                                            <input type="email" class="form-control" id="email"" name=" email" placeholder="Ingrese el correo del cliente...">

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- CONTADO O CRÉDITO======================> -->
                                            <div class="col-md-3 contenedor-cuotas">
                                                <div class="form-group">
                                                    <label for="" id="emailtext">¿Contado o Crédito?</label>
                                                    <select class="form-control" id="tipopago" name="tipopago">
                                                        <option value="Contado">Contado</option>
                                                        <option value="Credito">Crédito</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="col-md-3 contenedor-cuotas">
                                                <div class="cuotas-float">
                                                    <div class="form-group">
                                                        <label for="" id="emailtext">¿Número de cuotas?</label>
                                                        <select class="form-control" name="numcuotas" id="numcuotas">
                                                            <?php
                                                            for ($i = 1; $i <= 10; $i++) :
                                                                echo '<option value=' . $i . '>' . $i . '</option>';
                                                            endfor;
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="pago-cuotas">
                                                        <div class="form-group">
                                                            <input type="hidden" name="totalOperacion" id="totalOperacion" value="">
                                                            <input type="text" class="form-control" id="fecha_cuota" name="fecha_cuota[]" placeholder="Fecha cuota 1">

                                                        </div>
                                                        <div class="form-group" style="margin-top: -12px;">
                                                            <input type="number" class="form-control" id="cuotas" name="cuotas[]" placeholder="Monto cuota 1">
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-danger btn-xs salir-cuotas">Salir</button>
                                                </div>

                                            </div>
                                            <!-- FIN CONTADO O CRÉDITO==================> -->
                                        </div>

                                        <!-- FIN ENTRADA PARA ENVÍO DE EMAIL =============== -->
                                        <!-- ENTRADA PARA AGREGAR PRODUCTOS -->

                                        <div class="row nuevoProducto">

                                            <div class="flex">

                                                <i class="fas fa-barcode fa-lg"></i>
                                                <input type="search" id="searchpcPos" name="searchpcPos" placeholder="Escanear código">



                                            </div>
                                            <div class="table-responsive items-c">
                                                <!-- BOTÓN PARA AGREGAR PRODUCTO-->
                                                <table class="table tabla-items">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Cantidad</th>
                                                            <!-- <th>Uni/medida</th> -->
                                                            <th>Descripción</th>
                                                            <th>Precio unitario</th>
                                                            <!-- <th>Valor unitario</th> -->
                                                            <th>Sub.Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itemsP">

                                                    </tbody>

                                                </table>
                                            </div>
                                            <!-- FIN ENTRADA AGREGAR PRODUCTOS  -->


                                            <div class="box">

                                                <!-- DESCUENTO GLOBAL| -->
                                                <div class="col-md-5 col-sm-12">
                                                    <table class="table tabla-descuentos" style="border:0px">

                                                        <tbody>
                                                            <tr>


                                                                <td>
                                                                    <div class="box">
                                                                        <div class="col col-lg-12 col-sm-12 col-xs-12">
                                                                            <div class="contenedor-tipo-descuento">
                                                                                <label for="porcen" id="por" class="">%</label>
                                                                                <input type="radio" id="porcen" class="tipo_desc" name="tipo_desc" value="%" checked>
                                                                                <label for="soles" id="sol" class="">S/</label>
                                                                                <input type="radio" id="soles" class="tipo_desc" name="tipo_desc" value="S/">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="input-group">

                                                                            <span class="input-group-addon"><i class="fas fa-percent descicono"></i></span>
                                                                            <input type="number" class="form-control" min="0" placeholder="0.00" id="descuentoGlobalPpos"" name=" descuentoGlobalPpos" value="0" placeholder="Ingrese descuento...">
                                                                            <!-- <i class="fas fa-percent"></i> -->
                                                                            <input type="number" class="form-control" min="0" placeholder="0" id="descuentoGlobalpos"" name=" descuentoGlobalpos" value="0" placeholder=" Ingrese descuento..." style="display: none">

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- MÉTODO DE PAGO ========] -->


                                                            <tr>
                                                                <td>
                                                                    <legend class="text-bold" style="margin-left:15px; font-size:1.2em; letter-spacing: 1px;">MÉTODO DE PAGO:</legend>
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fas fa-money-bill-wave"></i></span>
                                                                            <select style="width: 100%;" class="form-control" id="metodopago" name="metodopago">
                                                                                <option value="009">En efectivo</option>
                                                                                <option value="001">Depósito en cuenta</option>
                                                                                <option value="005">Tarjeta de débito</option>
                                                                                <option value="006">Tarjeta de crédito</option>
                                                                                <option value="003">Transferencia bancaria</option>
                                                                                <option value="002">Giro</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- FIN MÉTODO DE PAGO ======== -->
                                                            <!-- COMENTARIO=========== -->


                                                            <tr>


                                                </div>
                                                </td>
                                                </tr>
                                                <!-- FIN COMENTARIO======= -->
                                                </tbody>
                                                </table>


                                            </div>
                                            <!-- FIN DESCUENTO GLOBAL -->

                                            <!-- //ENTRADA DE REMUMMEN TOTALES  -->
                                            <div class="col-md-7 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table  tabla-totales">

                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>RESUMEN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="totales">
                                                            <tr class="op-subt">
                                                                <td>SubTotal</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-descuento-item">
                                                                <td>Descuento pot item (-)</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-gravadas">
                                                                <td>Op.Gravadas</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-exoneradas">
                                                                <td>Op.Exoneradas</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-inafectas">
                                                                <td>Op.Inafectas</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-gratuitas">
                                                                <td>Op.gratuitas</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-descuento">
                                                                <td>Descuento (-)</td>
                                                                <td>0</td>
                                                            </tr>
                                                            <tr class="icbper">
                                                                <td>ICBPER</td>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr class="op-igv">
                                                                <td>IGV(18%)</td>
                                                                <td>0.00</td>
                                                            </tr>

                                                            <tr class="op-total">
                                                                <td>Total</td>
                                                                <td>0.00</td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>



                                            </div>
                                            <!-- FIN TOTALES====== -->
                                            <!-- // FIN ENTRADA DE REMUMMEN TOTALES  -->
                                            <div class="col-lg-12">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="input-group-adddon">
                                                            <input type="text" class="form-control" id="monto_pagado" name=" monto_pagado" placeholder="MONTO RECIBIDO">
                                                            <!-- <span class="input-group-addon"></span>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="input-group-adddon">
                                                            <input type="text" class="form-control" id="total_vuelto"" name=" total_vuelto" placeholder="VUELTO" readonly>
                                                            <!-- <span class="input-group-addon"></span>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row seccion-observacion">
                                            <div class="col-md-12">
                                                <label for="">OBSERVACIONES</label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="far fa-comment-dots"></i></span>
                                                        <textarea class="form-control" name="comentario" id="comentario" cols="50" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                   
                                    

                                 
                                        <hr>

                                    </div>

                                </div>
                    <div class="seccion-detra-selva">
                        <div class="contenedor-selva load-detracciones-servicios-selva">

                        </div>
                            <!-- DETRACCIONES=============================== -->
                            <div class="col-md-12 col-lg-12 contenedor-detracciones">
                                <div class="detracciones-msg">
                                Por indicaciones de la SUNAT la Detracción se envía en Soles.
                                </div>                                                                                                      
                            <div class="col-md-12">
                              <div class="col-md-9">
                                <label for="">Tipo detracción <span>*</span></label>
                                <div class="form-group">
                                  
                            <select class="form-control select2" id="tipodetraccion" name="tipodetraccion" style="width: 100%;">
                             
                             
                            <option value="">Elegir</option>
                              <?php
                              $item = null;
                              $valor = null;
                              $detracciones = ControladorSunat::ctrMostrarTipoDetraccion($item, $valor);
                              foreach($detracciones as $k => $value){
                                if($value['porcentaje'] > 0){
                                  $porcentaje = '('.$value['porcentaje'].'%)';
                                }else{
                                  $porcentaje = '';
                                }
                              echo '<option value="'.$value['codigo'].'">['.$value['codigo'].'] '.$value['descripcion'].' '.$porcentaje.'</option>';
                              }
                              
                              ?>
                            
                          </select>
                                  
                                </div>
                              </div>
                              <div class="col-md-3">
                              <label for="">Porcentaje detracción</label>
                                <div class="form-group">
                                  <input type="number" min="0" max="100" class="form-control" name="pordetraccion" id="pordetraccion">
                                </div>
                              </div>
                              </div>
                              <div class="col-md-12">
                              <div class="col-md-9">
                              <label for="">Medio de pago <span>*</span></label>
                                <div class="form-group">
                              <select  class="form-control tipo_pago_detraccion select select2-hidden-accessiblev" name="tipo_pago_detraccion" id="tipo_pago_detraccion" tabindex="-1" aria-hidden="true" style="width: 100%;">
                              <option value="">Elegir</option>
                                <?php
                            $item = null;
                            $valor = null;
                            $respuesta = ControladorCuentasBanco::ctrMostrarMediodePago($item, $valor);
                          
                                foreach($respuesta as $k => $value){
                                  echo '<option value="'.$value['codigo'].'">['.$value['codigo'].'] '.$value['descripcion'].'</option>';
                                }
                                ?>
                                

                            </select>
                              </div>
                              </div>
                              <div class="col-md-3">
                              <label for="">Total detracción S/</label>
                                <div class="form-group">
                                  <input type="text" class="form-control" name="totaldetraccion" id="totaldetraccion" readonly>
                                </div>
                              </div>
                              </div>
                              <div class="col-md-12">
                              <div class="col-md-9">
                                <label for="">Cuenta Banco de la Nación <span>*</span></label>
                                <div class="form-group conte-cuentadetraccion">
                                  
                            <select class="form-control select2" id="cuentadetraccion" name="cuentadetraccion" style="width: 100%;">
                            <option value="">Seleccione una Cuenta de Detracción</option>
                            <?php
                            $item = null;
                            $valor = null;
                            $respuesta = ControladorCuentasBanco::ctrMostrarCuentasBanco($item, $valor);
                           foreach($respuesta as $k => $value){
                            if($value['tipocuenta'] == 'cuenta_detracciones'){
                              echo ' <option value="'.$value['id'].'">'.$value['numerocuenta']. ' - '.$value['nombrebanco'].'</option>';
                            }
                           }
                           
                           ?>
                           
                          </select>
                                  
                                </div>
                              </div>
                            
                              </div>

                              <div class="msg-sunat-detraccion">
                              <i class="fas fa-comment-dots"></i> Operacion Sujeta a Detracción: Debe existir al menos un artículo sujeto a detracción. Escoger el mayor porcentaje en caso exista más de uno<br>Resolución 183-204 SUNAT/15.08.2004.
                              </div>
                            </div>
                            <!-- FIN DETRACCIONES=============================== -->
                        </div>
                        </div>
                                <div class="box">
                                    <div class="col-xs-12 radio-envio-pos">
                                        <!-- <div class="col-md-6 col-xs-12">
                                            <input type="radio" name="envioSunat" id="firmar" value="firmar">
                                            <label for="firmar">Solo Firmar e Imprimir</label>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="radio" name="envioSunat" id="enviar" value="enviar" checked>
                                            <label for="enviar">Eviar a SUNAT</label>
                                        </div> -->
                                        <input type="radio" name="envioSunat" id="no" value="no">
                                    </div>

                                </div>
                                <div class="box-footer contenedor-btns-carrito">
                                    <button type="button" class="btnGuardarVentaPos"><i class="far fa-save"></i></button>

                                    <!-- BOTÓN PARA ELIMINAR CARRO-->
                                    <button type="button" class="btnEliminarCarro"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>

            </div>

        </div>
        <!-- BOX FIN -->
        <!-- /.box-footer -->
    </section>

</div>


<!-- MODAL AGREGAR USUARIO -->
<!-- Modal -->
<div id="modalEditarItemsPos" class="modal fade modal-forms" role="dialog">
    <div class="modal-dialog  ">

        <!-- Modal content-->
        <div class="modal-content">


            <form role="form" id="formitemsPos" class="form-items-pos">

                <input type="hidden" name="editar_item" id="editar_item" value="">
                <input type="hidden" name="cantidadPos" id="cantidadPos" value="1">
                <input type="hidden" name="idProducto" id="idProducto">
                <input type="hidden" name="modo_icbper" id="modo_icbper" class="modo-icbper" value="n">
                <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

                <!-- <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">AGREGAR USUARIO</h4>

                </div> -->

                <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

                <div class="modal-body">

                    <div class="box-body">
                        <div class="descripcion-de-items"></div>
                        <div class="col-md-12">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="">TIPO IGV</label>
                                        <select class="form-control" name="tipoigvLineaPos" id="tipoigvLineaPos">
                                            <?php
                                            $item = null;
                                            $valor = null;
                                            $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                                            foreach ($unidad_medida as $k => $value) {


                                                echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                                            }
                                            ?>
                                            <option value=""></option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DESCUENTO</label>
                                        <input type="text" class="form-control " name="descuentoLineaPos" id="descuentoLineaPos" value="0.00">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">PRECIO UNITARIO</label>
                                        <input type="text" class="form-control " name="precioUnitarioLineaPos" id="precioUnitarioLineaPos">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">SUB TOTAL</label>
                                        <input type="text" class="form-control " name="subTotalLineaPos" id="subTotalLineaPos" readonly>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">VALOR UNITARIO</label>
                                        <input type="text" class="form-control " name="valorUnitarioLineaPos" id="valorUnitarioLineaPos">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">IGV DE LA LINEA</label>
                                        <input type="text" class="form-control " name="igvLineaPos" id="igvLineaPos" readonly>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="btn-group btngroup-precio-mayor-normal" role="group" aria-label="Basic example">
                                            <input type="hidden" class="precio_normal pre-css" name="" value="" readonly="">
                                            <input type="hidden" class="precio_pormayor pre-css" name="" value="" readonly="">

                                            <button type="button" id="btnPrecioNormal" class="btn btn-success" idproducto="34">PRECIO NORMAL <br></button>
                                            <button type="button" id="btnPrecioporMayor" class="btn btn-info" idproducto="34">PRECIO POR MAYOR <br></button>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">TOTAL</label>
                                        <input type="text" class="form-control " name="totalLineaPos" id="totalLineaPos" readonly>

                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="">IMPUESTO A LA BOLSA PLÁSTICA</label>
                                    <div class="col-md-6 contenedor-icbper-linea">

                                        <div class="icbper-linea">
                                            <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="primary" data-offstyle="danger" id="icbperPos" name="icbperPos" value="si" data-size="small" data-width="80">
                                        </div>

                                        <input type="text" class="form-control " name="icbperLineaPos" id="icbperLineaPos" value="<?php echo $emisor['icbper']; ?>">



                                    </div>
                                </div>
                            </div>


                            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->


                        </div>

                    </div>

                </div>

                <!--=====================================
        PIE DEL MODAL
        ======================================-->

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

                    <button type="submit" class="btn btn-primary btn-agm btn-agregar-item-pos"><i class="fas fa-cart-plus fa-lg"></i></button>

                </div>

                <?php

                // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

                ?>

            </form>


        </div>
    </div>
</div>