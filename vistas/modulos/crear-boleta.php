<?php

use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorProductos;
use Controladores\ControladorCategorias;
use Controladores\ControladorSunat;
use Controladores\ControladorCuentasBanco;

$emisor = ControladorEmpresa::ctrEmisor();
$sucursal = ControladorSucursal::ctrSucursal();
$id_sucursal = isset($_POST['idSucursal']) ? $_POST['idSucursal']
  : $sucursal['id'];
?>
<div class="content-wrapper panel-medio-principal">
  <input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $id_sucursal ?> ">
  <div style="padding:5px"></div>
  <section class="container-fluid">
    <section class="content-header dashboard-header">
      <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
        <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px;  border-radius:10px;">

          <div class="col-md-3 hidden-sm hidden-xs">
            <button class=""><i class="fas fa-file-invoice"></i> Nueva boleta</button>
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

      <div class="box-header" style="border: 0px; padding-top:5px;">
        <!-- <h3 class="box-title">Crear venta</h3> -->
        <div class="contenedor-btn-sucursal">

        </div>
        <div class="col-md-6 row-sucursal">
          <?php
          if ($_SESSION['perfil'] == 'Administrador') {
            echo '
                    <select class="form-control select2" name="idcSucursal" id="idcSucursal" style="width: 100%;">';

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
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <!-- FORMULARIO -->
          <div class="col-lg-12 col-xs-12">

            <div class="box box-success" style="border-top: 0px;">

              <?php
              $numCorrelativo = '';
              if (isset($_POST['numCorrelativo'])) {
                $numCorrelativo = $_POST['numCorrelativo'];
                $numCorrelativo;
              }
              ?>

              <form role="form" method="post" class="formVenta" id="formVenta">

                <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"] ?>">
                <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
                <input type="hidden" class="" id="fecha" name="fecha" value="<?php echo date("Y-m-d") ?>">
                <input type="hidden" class="" id="serieCorrelativo" name="serieCorrelativo" value="<?php echo $numCorrelativo; ?>">

                <input type="hidden" id="afectoigv" name="afectoigv" value="<?php echo $emisor['afectoigv'] ?>">
                <input type="hidden" id="icbper" name="icbper" value="<?php echo $emisor['icbper'] ?>">

                <div class="box-body" style="border: 0px; padding-top:0px; ">

                  <!-- PRIMERA ENTRADA FORM -->
                  <div class="box" style="border: 0px; padding-top:0px;">

                    <!-- ENTRADA TIPO MONEDA-->
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-money-bill"></i></span>
                          <select class="form-control" id="moneda" name="moneda">
                            <option value="PEN">Soles (S/)</option>
                            <option value="USD">Dólares Americanos ($)</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <!-- ENTRADA SERIE-->
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">

                          <span class="input-group-addon"><i class="fa fa-key"></i></span>

                          <select class="form-control" name="serie" id="serie" value="">
                            <?php
                            if ($_GET["ruta"] == "crear-boleta") {

                              $valor = "03";
                              $id_sucursal = $id_sucursal;
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
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type="text" class="form-control" name="fechaDoc" id="fechaDoc" value="<?php echo date("d/m/Y") ?>" required>
                        </div>
                      </div>
                    </div>

                    <!-- ENTRADA FECHA VENCIMIENTO-->
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type="text" class="form-control" name="fechaVence" id="fechaVence" value="<?php echo date("d/m/Y") ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-1 col-xs-6">
                      <input type="checkbox" data-toggle="toggle" data-on="<i class=' fa fa-eye'></i>" data-off="<i class=' fa fa-eye-slash'></i>" data-onstyle="primary" data-offstyle="danger" id="sucursalbtnof" name="sucursalbtnof" data-size="mini" data-width="60" value="on">
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-xs-6">
                        <input type="hidden" class="form-control" id="correlativo">
                      </div>
                    </div>
                    <!-- ENTRADA CLIENTE -->
                    <div class="row">
                      <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Cliente:</legend>

                      <div class="col-md-3">
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

                                echo "<script>$('#tipoDoc').val(1);</script>";

                                echo '<option value=' . $value['codigo'] . '>' . $value['descripcion'] . '</option>';
                              }
                              ?>
                            </select>

                          </div>
                        </div>
                      </div>

                      <!-- ENTRADA DOCUMENTO -->
                      <div class="col-md-4">
                        <div id="reloadC"></div>
                        <div class="form-group">
                          <div class="input-group">

                            <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">
                            <span class="input-group-addon btn buscarRuc"><i class="fa fa-search"></i></span>
                            <div id="reloadC"></div>
                            <div class="resultadoCliente" idCliente=""><a href="#" class="btn-add"></a></div>
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA RESULTADO DOCUMENTO -->
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <input type="text" class="form-control" id="razon_social"" name=" razon_social" placeholder="Ingrese nombre o razón social...">
                            <!-- <span class="input-group-addon"></span>  -->
                          </div>
                        </div>
                      </div>

                    </div>
                    <!-- ENTRADA CLIENTE 2 -->
                    <div class="row">
                      <!-- ENTRADA DOCUMENTO -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <input type="text" class="form-control" id="direccion"" name=" direccion" placeholder="Ingrese la dirección...">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>

                      <!-- ENTRADA DOCUMENTO -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <input type="text" class="form-control" id="ubigeo"" name=" ubigeo" placeholder="Ingrese codigo de ubigeo...">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA RESULTADO DOCUMENTO -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <input type="text" class="form-control" id="celular"" name=" celular" placeholder="Ingrese su número de celular...">
                            <!-- <span class="input-group-addon"></span>  -->
                          </div>
                        </div>
                      </div>

                    </div>
                    <!-- ENTRADA PARA ENVÍO DE EMAIL =============== -->
                    <div class="row" style="margin-bottom: 7px; padding-bottom: 4px;">
                      <div class="col-md-6">


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
                      <div class="col-md-3">
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
                    <div class="col-lg-12 col-xs-12">
                      <div class="row nuevoProducto">

                        <div class="flex">

                          <i class="fas fa-barcode fa-lg"></i>
                          <input type="search" id="searchpc" name="searchpc" placeholder="Escanear código">

                          <?php if ($_SESSION['perfil'] == 'Administrador') : ?>
                            <button type="button" class="btn btn-success btn-radius pull-right btnproser" data-toggle="modal" data-target="#modalAgregarProducto"></i>Nuevo producto o servicio <i class="fa fa-th"></i>
                            </button>
                          <?php endif ?>

                          <button type="button" class="btn btn-primary pull-right btn-agregar-carrito" data-toggle="modal" data-target="#modalProductosVenta"><i class="fas fa-cart-plus fa-lg"></i> Agregar Productos o Servicios</button>

                        </div>
                        <div class="table-responsive items-c">
                          <!-- BOTÓN PARA AGREGAR PRODUCTO-->
                          <table class="table tabla-items">
                            <thead>
                              <tr>
                                <th>Código</th>
                                <th>Cantidad</th>
                                <th>Uni/medida</th>
                                <th>Descripción</th>
                                <th>Precio unitario</th>
                                <th>Valor unitario</th>
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
                          <div class="col-md-6 col-sm-12">
                            <table class="table tabla-descuentos" style="border:0px">
                              <thead>
                                <tr>

                                  <th>DESCUENTO</th>
                                </tr>
                              </thead>
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
                                        <input type="number" class="form-control" min="0" placeholder="0.00" id="descuentoGlobalP"" name=" descuentoGlobalP" value="0" placeholder="Ingrese descuento...">
                                        <!-- <i class="fas fa-percent"></i> -->
                                        <input type="number" class="form-control" min="0" placeholder="0" id="descuentoGlobal"" name=" descuentoGlobal" value="0" placeholder=" Ingrese descuento..." style="display: none">

                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                <!-- MÉTODO DE PAGO ========] -->
                                <tr>
                                  <th style="">MÉTODO DE PAGO:</th>

                                </tr>

                                <tr>
                                  <td>
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
                                  <th>OBSERVACIONES</th>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <div class="form-group">
                                      <div class="input-group">
                                        <span class="input-group-addon"><i class="far fa-comment-dots"></i></span>
                                        <textarea class="form-control" name="comentario" id="comentario" cols="50" rows="4"></textarea>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <!-- SELVA===================================================== -->
                                    <div class="contenedor-selva load-detracciones-servicios-selva">


                                    </div>
                          </div>
                          </td>
                          </tr>
                          <!-- FIN COMENTARIO======= -->
                          </tbody>
                          </table>


                        </div>
                        <!-- FIN DESCUENTO GLOBAL -->

                        <!-- //ENTRADA DE REMUMMEN TOTALES  -->
                        <div class="col-md-6 col-sm-12">
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
                      </div>
                    </div>
<!-- DETRACCIONES=============================== -->
<div class="col-md-12 col-lg-8 contenedor-detracciones">
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
                    <!-- <hr> -->

                    <!-- MÉTODO DE PAGO -->
                    <!-- <div class="row">
                                  <div class="col-xs-6">
                                  <div class="input-group">
                                    <select class="form-control rounded" id="nuevoMetodoPago" name="nuevoMetodoPago">
                                        <option value="">Seleccione método de pago</option>
                                        <option value="">Efectivo</option>
                                        <option value="">Tarjeta Crédito</option>
                                        <option value="">Tarjeta Débito</option>
                                    </select>
                                </div>
                                  </div> -->
                    <!-- 
                                  <div class="col-xs-6">
                                  <div class="input-group">
                                  <input type="text" class="form-control " id="nuevoCodigoTransaccion" name="nuevoCodigoTransaccion" placeholder="Código transacción">
                                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                </div>
                                  </div> -->

                    <!-- </div> -->


                  </div>

                </div>
                <div class="box">
                  <div class="col-xs-12 radio-envio">
                    <div class="col-md-6 col-xs-12">
                      <input type="radio" name="envioSunat" id="firmar" value="firmar" checked>
                      <label for="firmar">Solo Firmar e Imprimir</label>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <input type="radio" name="envioSunat" id="enviar" value="enviar">
                      <label for="enviar">Eviar a SUNAT ahora mismo</label>
                    </div>
                    <!-- <div class="col-md-4  col-xs-12">
                      <input type="radio"  name="envioSunat" id="no" value="no">
                      <label for="no">Solo Guardar Venta</label>
                    </div> -->
                  </div>

                </div>
                <div class="box-footer contenedor-btns-carrito">
                  <button type="button" class="btnGuardarVenta"><i class="far fa-save"></i></button>

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






<!-- MODAL AGREGAR CLIENTE-->
<!-- Modal -->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DNI -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-address-card""></i></span> 

                <input type=" number" maxlength="8" class="form-control input-lg" name="nuevoDni" id="nuevoDni" placeholder="Ingresar DNI" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-at"></i></span>

                <input type="email" class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "(999) 999-999-999"' data-mask required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
            <!-- ENTRADA PARA FECHA NACIMIENTO -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" id="nuevaFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>



          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

        <?php

        //  $crearCliente = new ControladorClientes();
        //  $crearCliente -> ctrCrearCliente();

        ?>

      </form>


    </div>
  </div>
</div>



<!-- Modal AGGREGAR PRODUCTOS -->
<div class="modal fade bd-example-modal-lg" id="modalProductosVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header bg-info">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cart-plus"></i> Productos y servicios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">
        <div class="col-12">


          <!-- SE INCLUYE LA TABLA PRODUCTOS PARA EL CARRITO -->

          <?php

          include_once "table-productos.php";

          ?>



        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Cerrar</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>

    </div>
  </div>
</div>

<!-- MODAL AGREGAR PRODUCTO-->
<!-- Modal -->
<div id="modalAgregarProducto" class="modal fade modal-forms fullscreen-modal">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="formProductos" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <!-- <h4 class="modal-title">NUENO PRODUCTO O SERVICIO</h4> -->

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <div class="col-md-8">
              <!-- PRIMERA SECCIÓN============= -->
              <div class="row">
                <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->
                <div class="col-md-12">
                  <div class="form-group">
                    <select class="select2" name="nuevaSucursal" id="nuevaSucursal" style="width: 100%">

                      <?php
                      if ($_SESSION['perfil'] == 'Administrador') {
                        echo '<option value="">SELECCIONE UN ALMACEN</option>';
                        $item = null;
                        $valor = null;
                        $sucursal = ControladorSucursal::ctrSucursalPrincipal($item, $valor);
                        foreach ($sucursal as $k => $v) {
                          echo '<option value="' . $v['id'] . '">' . $v['nombre_sucursal'] . ' - : ' . $v['direccion'] . '</option>';
                        }
                        // echo '<option value="todos">TODOS</option>';
                      } else {

                        $sucursal = ControladorSucursal::ctrSucursal();

                        echo '<option value="' . $sucursal['id'] . '">' . $sucursal['nombre_sucursal'] . ' - : ' . $sucursal['direccion'] . '</option>';
                      }


                      ?>



                    </select>

                  </div>
                </div>
              </div>
              <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">


                    <select class="form-control" name="nuevaCategoria" id="nuevaCategoria" required>

                      <option value="">Selecionar categoría</option>
                      <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                      foreach ($categorias as $k => $value) :

                        echo '<option value="' . $value['id'] . '">' . $value['categoria'] . '</option>';

                      endforeach;
                      ?>


                    </select>

                  </div>
                </div>


                <!-- ENTRADA PARA DEL CÓDIGO -->
                <div class="col-md-6">
                  <div class="form-group product">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-barcode fa-lg"></i></span>

                      <input type="text" class="form-control" name="nuevoCodigo" id="nuevoCodigo" placeholder="Código" autocomplete="off">

                      <span class="input-group-addon bar-code-btn"><i class="fas fa-sync-alt"></i></span>
                    </div>
                  </div>
                </div>
                <!-- ENTRADA PARA DEL CÓDIGO -->
                <!-- <div class="col-md-4">
                  <div class="form-group"> -->

                <input type="hidden" class="form-control" name="nuevaSerie" id="nuevaSerie" placeholder="Serie del producto">


                <!-- </div>
                </div> -->
              </div>

              <!-- FIN PRIMERA SECCIÓN======== -->
              <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
              <div class="row">

                <div class="col-md-6">
                  <div class="form-group">


                    <select class="form-control" name="tipo_afectacion" id="tipo_afectacion">
                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                      foreach ($unidad_medida as $k => $value) {


                        echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                      }
                      ?>
                    </select>


                  </div>
                </div>
                <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
                <div class="col-md-6">
                  <div class="form-group">



                    <select class="form-control" name="unidad" id="unidad">
                      <?php
                      $item = null;
                      $valor = null;
                      $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
                      foreach ($unidad_medida as $k => $value) {

                        if ($value['activo'] == 's') {

                          echo "<option value='" . $value['codigo'] . "'>" . $value['descripcion'] . "</option>";
                        }
                      }
                      ?>
                    </select>

                  </div>

                </div>

              </div>

              <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row">
                <div class="col-md-9">
                  <div class="form-group">

                    <input type="text" class="form-control" name="nuevaDescripcion" id="nuevaDescripcion" placeholder="Ingresar descripción" required>

                  </div>
                </div>

                <!-- ENTRADA PARA STOCK -->
                <div class="col-md-3">
                  <div class="form-group">

                    <input type="number" class="form-control" min="0" name="nuevoStock" id="nuevoStock" placeholder="Ingresar stock" required>

                  </div>
                </div>
              </div>
              <!-- ENTRADA PARA PRECIO VENTA-->
              <div class="row">
                <div class="col-md-6" style="">
                  <div class="form-group">


                    <input type="text" class="form-control" name="nuevoPrecioUnitario" id="nuevoPrecioUnitario" placeholder="Ingresar precio unitario" step="any" required>

                  </div>
                </div>

                <!-- ENTRADA PARA PRECIO COMPRA -->
                <div class="col-md-6">

                  <div class="form-group">


                    <input type="text" class="form-control" name="nuevoValorUnitario" id="nuevoValorUnitario" placeholder="Valor unitario" step="any" readonly required>

                  </div>
                </div>
              </div>

              <!-- CHECKBOX PARA PORCENTAJE -->
              <div class="row">
                <div class="col-md-4">

                  <div class="form-group">


                    <input type="text" class="form-control" name="nuevoigv" id="nuevoigv" placeholder="IGV 18%" readonly>


                  </div>
                </div>
                <div class="col-md-4">

                  <div class="form-group">


                    <input type="text" class="form-control" name="nuevoPrecioMayor" id="nuevoPrecioMayor" placeholder="Precio por mayor">


                  </div>
                </div>

                <!-- ENTRADA IGV  -->
                <div class="col-md-4">
                  <div class="form-group">


                    <input type="number" class="form-control" name="nuevoPrecioCompra" id="nuevoPrecioCompra" placeholder="Precio compra">


                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">



              <!-- ENTRADA PARA SUBIR FOTO -->

              <div class="img-contenedor">


                <label for="nuevaImagen"></label>
                <input type="file" class="nuevaImagen" name="nuevaImagen" id="nuevaImagen">

                <p class="help-block">Peso máximo de la imagen 2MB</p>

                <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">

              </div>



            </div>
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button class="btn btn-primary btn-agm btn-nuevo-producto">Guardar producto</button>

        </div>

        <?php

        // $crearProducto = new ControladorProductos();
        // $crearProducto-> ctrCrearProducto();

        ?>

      </form>


    </div>
  </div>
</div>


<div class="respuesta-agregar"></div>
<!-- FIN MODAL            -->
<div id="reloadFull" style="display: none;"></div>