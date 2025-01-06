<?php

use Controladores\ControladorSunat;
use Controladores\ControladorGuiaRemision;
use Controladores\ControladorClientes;
use Controladores\ControladorConductores;
use Controladores\ControladorSucursal;

$sucursal = ControladorSucursal::ctrSucursal();
$id_sucursal = isset($_POST['idSucursal']) ? $_POST['idSucursal'] : $sucursal['id'];
?>
<div class="content-wrapper panel-medio-principal">
  <?php
  // $emisor = ControladorEmpresa::ctrEmisor();
  ?>
  <div style="padding:5px"></div>
  <section class="container-fluid">
    <section class="content-header dashboard-header">
      <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
        <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px;  border-radius:10px;">

          <div class="col-md-3 hidden-sm hidden-xs">
            <button class=""><i class="fas fa-file-invoice"></i> Nueva Guía de Remisión</button>
          </div>
          <div class="col-lg-9 col-md-12 col-sm-12 btns-dash">

          </div>
        </div>
      </div>
    </section>
  </section>



  <!-- <section class="content"> -->
  <section class="container-fluid panel-medio">
    <input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $id_sucursal ?> ">
    <!-- BOX INI -->
    <div class="box rounded">

      <div class="box-header" style="border: 0px; padding-top:5px;">
        <!-- <h3 class="box-title">Crear venta</h3> -->
        <div class="col-md-6 row-sucursal">
          <?php
          if ($_SESSION['perfil'] == 'Administrador') {
            echo '
                    <select class="form-control select2" name="idcSucursal" id="idcSucursal" style="width: 100%;" onchange="loadProductosG(1)">';

            $item = null;
            $valor = null;
            $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
            foreach ($sucursal as $k => $v) {
              echo '<option value="' . $v['id'] . '" data-direccion="' . $v['direccion'] . '" data-ubigeo="' . $v['ubigeo'] . '">' . $v['nombre_sucursal'] . ' - Sede: ' . $v['direccion'] . '</option>';
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



              <form role="form" method="post" class="formGuia" id="formGuia">
                <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"] ?>">


                <div class="box-body" style="border: 0px; padding-top:0px; ">

                  <!-- PRIMERA ENTRADA FORM -->
                  <div class="box" style="border: 0px; padding-top:0px;">

                    <!-- ENTRADA TIPO MONEDA-->
                    <div class="col-md-4 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-money-bill"></i></span>
                          <select class="form-control" id="tipocomp" name="tipocomp">
                            <option value="09">GUÍA DE REMISIÓN</option>

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
                            if ($_GET["ruta"] == "crear-guia") {

                              $valor = "09";
                              $id_sucursal = $id_sucursal;
                              $serieComprobante = ControladorSunat::ctrMostrarSerie($valor, $id_sucursal);
                              foreach ($serieComprobante as $key => $value) {
                                echo '<option value=' . $value['id'] . '>' . $value['serie'] . '</option>';
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
                          <input type="text" class="form-control" name="fechaEmision" id="fechaEmision" value="<?php echo date("d/m/Y") ?>" required>
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
                      <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                          <h3>1</h3>
                        </label> Cliente</legend>

                      <div class="col-md-3">

                        <div class="form-group">
                          <div class="kardex-contenedor">
                            <input type="hidden" name="tipoDocTransporte" id="tipoDocTransporte" value="1">
                            <div class="form-group busca-pro-kardex select">
                              <input type="hidden" name="idCliente" id="idCliente">
                              <label for="">Buscar cliente:</label>
                              <select class="form-control select2" style="width: 100%;" name="listaClientes" id="listaClientes">
                                <option value="">SELECCIONAR CLIENTE</option>
                                <?php
                                $clientes = ControladorClientes::ctrMostrarClientes(null, null);
                                foreach ($clientes as $v) {
                                  echo '<option value="' . $v['id'] . '" data-ruc="' . $v['ruc'] . '" data-razonsocial="' . $v['razon_social'] . '">' . $v['ruc'] . ' - ' . $v['razon_social'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- ENTRADA DOCUMENTO -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="" class="tipoDocTransporte">N° Documento<span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                          <div class="input-group">
                            <div id="rucActivo"></div>

                            <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">
                            <span class="input-group-addon btn" style="pointer-events: none;"><i class="fa fa-search"></i></span>
                            <!-- <div id="reloadC"></div>
                            <div class="resultadoCliente" idCliente=""><a href="#" class="btn-add"></a></div> -->
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA RESULTADO DOCUMENTO -->
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="" class="tipoDocTransporte">Nombre o RS <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="razon_social" name=" razon_social" placeholder="Ingrese nombre o razón social...">
                            <!-- <span class="input-group-addon"></span>  -->
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- ENTRADA CLIENTE -->
                    <div class="row">
                      <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                          <h3>2</h3>
                        </label> Datos de Traslado:</legend>

                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group">
                            <label for="">Motivo del Traslado <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <select class="form-control" name="motivoTraslado" id="motivoTraslado">
                              <?php
                              $tabla = 'motivo_traslado';
                              $item = null;
                              $valor = null;
                              $tipoDocumento = ControladorGuiaRemision::ctrMostrarTraslado($tabla, $item, $valor);
                              foreach ($tipoDocumento as $key => $value) {

                                echo '<option value=' . $value['codigo'] . '>' . $value['descripcion'] . '</option>';
                              }
                              ?>
                            </select>

                          </div>
                        </div>
                      </div>

                      <!-- ENTRADA DOCUMENTO -->
                      <div class="col-md-3">

                        <div class="form-group">
                          <div class="input-group">
                            <label for="">Modalidad del Traslado <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <select class="form-control" name="modalidadTraslado" id="modalidadTraslado">
                              <?php

                              $tabla = 'modalidad_transporte';
                              $item = null;
                              $valor = null;
                              $tipoDocumento = ControladorGuiaRemision::ctrMostrarTraslado($tabla, $item, $valor);
                              foreach ($tipoDocumento as $key => $value) {

                                echo '<option value=' . $value['codigo'] . '>' . $value['descripcion'] . '</option>';
                              }
                              ?>
                            </select>

                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA RESULTADO DOCUMENTO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Tipo de vehículo <span style="color:white; border-style: none !important; font-size:20px;">*</span></label>
                            <select class="form-control" name="tipoVehiculo" id="tipoVehiculo">
                              <option value="">-Seleccione-</option>
                              <?php

                              $tabla = 'tipo_vehiculo';
                              $item = null;
                              $valor = null;
                              $tipoVehiculo = ControladorGuiaRemision::ctrMostrarTiposVehiculo($tabla, $item, $valor);
                              foreach ($tipoVehiculo as $key => $value) {
                                echo '<option value=' . $value['id'] . '>' . $value['descripcion'] . '</option>';
                              }
                              ?>
                            </select>

                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA RESULTADO DOCUMENTO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Fecha Inicial de Traslado <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="fechaInicialTraslado" name="fechaInicialTraslado" value="<?php echo date("d/m/Y") ?>">

                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      <!-- ENTRADA PESO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Peso bruto (KGM) <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="number" class="form-control" id="pesoBruto" name="pesoBruto">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA PESO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Número de bultos <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="number" class="form-control" id="numeroBultos" name="numeroBultos">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA PESO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Número de contenedor</label>
                            <input type="number" class="form-control" id="numeroContenedor" name="numeroContenedor">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>
                      <!-- ENTRADA PESO -->
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-adddon">
                            <label for="">Código de puerto</label>
                            <input type="text" class="form-control" id="codigoPuerto" name="codigoPuerto">
                            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                          </div>
                        </div>
                      </div>

                    </div>


                    <div class="row datos-del-transporte">
                      <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                          <h3>3</h3>
                        </label> Datos del transporte:</legend>

                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="kardex-contenedor">
                            <input type="hidden" name="tipoDocTransporte" id="tipoDocTransporte" value="1">
                            <div class="form-group busca-pro-kardex select">
                              <label for="">Conductor encargado:</label>
                              <select class="form-control select2" style="width: 100%;" name="listConductores" id="listConductores">
                                <option value="">BUSCAR CONDUCTOR</option>
                                <?php
                                $conductores = ControladorConductores::ctrMostrarConductores(null, null);
                                foreach ($conductores as $v) {
                                  echo '<option value="' . $v['id'] . '" data-nombre="' . $v['nombres'] . '" data-apellidos="' . $v['apellidos'] . '" data-placa="' . $v['numplaca'] . '" data-brevete="' . $v['numbrevete'] . '" data-numdoc="' . $v['numdoc'] . '">' . $v['numdoc'] . ' - ' . $v['apellidos'] . ', ' . $v['nombres'] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <div class="input-group-">
                            <label for="" class="nombreRazon">N° DNI del conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="docTransporte" name="docTransporte" placeholder="Ingrese número de documento...">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 nombre-razon">
                        <div class="form-group">
                          <div class="input-group-">
                            <label for="" class="nombreRazon">Nombre Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="nombreRazon" name="nombreRazon" placeholder="Nombre o Razón Social">

                          </div>
                          <div class="input-group-c-apellidos">
                            <label for="" class="apellidosRazon">Apellidos Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="apellidosRazon" name="apellidosRazon" placeholder="Apellidos">
                          </div>
                        </div>

                      </div>

                      <div class="col-md-2 placa-v">
                        <div class="form-group">
                          <div class="input-group-">
                            <label for="" class="placa">N° Placa Vehículo <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>
                            <input type="text" class="form-control" id="placa" name="placa" placeholder="Número de placa">

                            <div class="input-group-c-apellidos">
                              <label for="" class="apellidosRazon">Num. Brevete <span style="color:red; border-style: none !important; font-size:20px;">*</span></label>
                              <input type="text" class="form-control" id="numBrevete" name="numBrevete" placeholder="Num. brevete">
                            </div>

                          </div>
                        </div>
                      </div>

                    </div>
                    <!-- <div class="row">
                                    <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Datos del Conductor:</legend>
                                  
                                    <div class="col-md-3">
                                    <div class="form-group">
                                   <div class="input-group"> 
                                    <label for="" class="tipoDocConductor">Tipo Documento <span style="color:red; border-style: none !important; font-size:20px;">*</span> </label>     
                                      <select class="form-control" name="tipoDocConductor" id="tipoDocConductor">
                                        <?php
                                        $item = null;
                                        $valor = null;
                                        $tipoDocumento = ControladorSunat::ctrMostrarTipoDocumento($item, $valor);
                                        foreach ($tipoDocumento as $key => $value) {

                                          echo '<option value=' . $value['codigo'] . '>' . $value['descripcion'] . '</option>';
                                        }
                                        ?>
                                      </select>
                                      
                                      </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group"> 
                                        <label for="" class="docConductor">N° DNI Conductor <span style="color:red; border-style: none !important;">*</span> </label>  
                                    <div class="input-group">
                                 
                                    <input type="text" class="form-control" id="docConductor" name="docConductor" placeholder="Ingrese número de documento...">
                                    <span class="input-group-addon btn buscarDniRuc"><i class="fa fa-search"></i></span> 
                                    <div id="reloadCG"></div>
                                  
                                    </div>
                                    </div>
                                    </div>
                                                                    
                                    <div class="col-md-6 nombre-razon">
                                    <div class="form-group">
                                    <div class="input-group-">
                                    <label for="" class="nombreRazonConductor">Nombre Conductor <span style="color:red; border-style: none !important;">*</span> </label>  
                                    <input type="text" class="form-control" id="nombreRazonConductor" name="nombreRazonConductor" placeholder="Nombre o Razón Social">                               
                                    <div id="reloadC"></div>
                                  
                                    </div>
                                    </div>
                                    </div>
                                   
                                   
                                 </div> -->

                    <div class="row">
                      <div class="col-md-6">
                        <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                            <h3>4</h3>
                          </label> Punto de Partida:</legend>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">

                              <label for="" class="placa">Dirección <span style="color:red; border-style: none !important; font-size: 20px;">*</span> </label>
                              <input type="text" class="form-control" id="direccionPartida" name="direccionPartida" placeholder="Dirección de Partida">

                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="kardex-contenedor">
                                <div class="form-group busca-pro-kardex select">
                                  <label for="" class="placa">Ubigeo de partida<span style="color:red; border-style: none !important; font-size: 20px;">*</span> </label>
                                  <!-- <label>BUSCAR EL PRODUCTO</label> -->
                                  <select class="form-control select2" style="width: 100%;" name="ubigeoPartida" id="ubigeoPartida">
                                    <option value="">BUSCAR EL UBIGEO PARTIDA</option>

                                    </option>
                                    <?php

                                    $productos = ControladorClientes::ctrBuscarUbigeo();

                                    foreach ($productos as $k => $v) {

                                      echo '<option value="' . $v['ubigeo'] . '">' . $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name'] . '</option>
                                                        ';
                                    }

                                    ?>
                                  </select>
                                </div>
                              </div>
                              <!-- <label for="" class=" placa">Ubigeo <span style="color:red; border-style: none !important; font-size: 20px;">*</span> </label>
                                    <input type="text" class="form-control" id="ubigeoPartida" name="ubigeoPartida">

                                    <div class="resultado-ubigeos-partida"></div> -->
                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                            <h3>5</h3>
                          </label> Punto de LLegada:</legend>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="input-group-">

                                <label for="" class="placa">Dirección <span style="color:red; border-style: none !important; font-size: 20px;">*</span> </label>
                                <input type="text" class="form-control" id="direccionLlegada" name="direccionLlegada" placeholder="Dirección de LLegada">

                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="kardex-contenedor">
                                <div class="form-group busca-pro-kardex select">
                                  <label for="" class="placa">Ubigeo de llegada<span style="color:red; border-style: none !important; font-size: 20px;">*</span> </label>
                                  <!-- <label>BUSCAR EL PRODUCTO</label> -->
                                  <select class="form-control select2" style="width: 100%;" name="ubigeoLlegada" id="ubigeoLlegada">
                                    <option value="">BUSCAR EL UBIGEO LLEGADA</option>

                                    </option>
                                    <?php

                                    $productos = ControladorClientes::ctrBuscarUbigeo();

                                    foreach ($productos as $k => $v) {

                                      echo '<option value="' . $v['ubigeo'] . '">' . $v['ubigeo'] . ' ' . $v['nombre_distrito'] . ' - ' . $v['nombre_provincia'] . ' - ' . $v['name'] . '</option>
                                                        ';
                                    }

                                    ?>
                                  </select>
                                </div>
                              </div>

                              <!-- <input type=" text" class="form-control" id="ubigeoLlegada" name="ubigeoLlegada">
                                    <div class="resultado-ubigeos-llegada"></div> -->

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                          <h3>6</h3>
                        </label> Documento de referencia:</legend>

                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="input-group">
                            <label for="">Serie Correlativo (F001-2)</label>
                            <input type="text" class="form-control" name="serieCorrelativoReferencial" id="serieCorrelativoReferencial">
                            <div class="resultado-serie"></div>


                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- FIN ENTRADA PARA ENVÍO DE EMAIL =============== -->
                  <!-- ENTRADA PARA AGREGAR PRODUCTOS -->
                  <div class="col-lg-12 col-xs-12">
                    <div class="row nuevoProducto">

                      <div class="flex">
                        <button type="button" class="btn btn-primary pull-right btn-agregar-carrito" data-toggle="modal" data-target="#modalProductosGuia"><i class="fas fa-cart-plus fa-lg"></i> Agregar Productos o Servicios</button>

                      </div>
                      <div class="table-responsive items-c">
                        <!-- BOTÓN PARA AGREGAR PRODUCTO-->
                        <table class="table tabla-items">
                          <thead>
                            <tr>
                              <th>Código</th>
                              <th>Descripción</th>
                              <th style="max-width: 130px; width: 130px;">Color</th>
                              <th style="max-width: 150px; width: 150px;">UND.M</th>
                              <th style="max-width: 120px; width: 120px;">P.O</th>
                              <th>Partida</th>
                              <th style="max-width: 100px; width: 100px;">Cantidad</th>
                              <th style="max-width: 130px; width: 130px;">Bultos</th>
                              <th style="max-width: 130px; width: 130px;">Peso</th>
                              <th style="max-width: 110px; width: 110px;">Opciones</th>
                            </tr>
                          </thead>
                          <tbody id="itemsPG">
                            <tr>
                              <td>
                                <?php
                                echo "<div class='resubi'></div>";
                                // $item = 'nombre_distrito';
                                // $valor = 'RIOJA';
                                // $respuesta = ControladorGuiaRemision::ctrMostrarUbigeo($item, $valor);
                                // var_dump($respuesta);
                                ?>
                              </td>
                            </tr>
                          </tbody>

                        </table>
                      </div>
                      <!-- FIN ENTRADA AGREGAR PRODUCTOS  -->


                      <div class="box">

                        <!-- DESCUENTO GLOBAL| -->
                        <div class="col-md-12 col-sm-12">
                          <table class="table" style="border:0px">
                            <thead>

                            </thead>
                            <tbody>

                              <!-- MÉTODO DE PAGO ========] -->

                              <!-- FIN MÉTODO DE PAGO ======== -->
                              <!-- COMENTARIO=========== -->
                              <tr>
                                <th>SERIES DE LOS PRODUCTOS</th>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <div>
                                    <div class="table-responsive">
                                      <!-- table-bordered table-striped  -->
                                      <table class="table  dt-responsive tablaSeries tbl-t" width="100%">
                                        <thead>
                                          <tr>
                                            <th>COD. PRODUCTO</th>
                                            <th>SERIE</th>
                                          </tr>
                                        </thead>
                                        <tbody class="series-guia">

                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <th> Información adicional para SUNAT</th>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="far fa-comment-dots"></i></span>
                                      <textarea class="form-control" name="comentario" id="comentario" cols="50" rows="4" placeholder="Escribe aquí una observacion" maxlength="250"></textarea>
                                    </div>
                                  </div>
                                </td>
                              </tr>


                              <!-- FIN COMENTARIO======= -->
                            </tbody>

                          </table>


                        </div>
                        <!-- FIN DESCUENTO GLOBAL -->



                        <hr>

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
                        <!-- <div class="col-md-4 col-xs-12">
                      <input type="radio" name="envioSunat" id="firmar" value="firmar" checked>
                      <label for="firmar">Solo Firmar e Imprimir</label>
                    </div> -->
                        <div class="col-md-4  col-xs-12">
                          <input type="radio" name="envioSunat" id="no" value="no" checked>
                          <label for="no">Solo Guardar Guía</label>
                        </div>
                        <div class="col-md-4 col-xs-12">
                          <input type="radio" name="envioSunat" id="enviar" value="enviar">
                          <label for="enviar">Enviar a SUNAT ahora mismo</label>
                        </div>
                      </div>

                    </div>


                    <div class="box-footer contenedor-btns-carrito">

                      <button type="button" class="btnGuardarGuia"><i class="far fa-save"></i></button>
                      <div class='muestras'></div>
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

<!-- Modal AGGREGAR PRODUCTOS -->
<div class="modal fade bd-example-modal-lg" id="modalProductosGuia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-body contenedor-pro">
        <!-- <div class="modal-header bg-info">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cart-plus"></i> Productos y servicios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>           -->
        <div class="col-12 ">

          <!-- SE INCLUYE LA TABLA PRODUCTOS PARA EL CARRITO -->
          <!-- <div id="productosCarrito">

                  </div> -->

          <?php

          include_once "table-productos-guia.php";

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
<!-- FIN MODAL            -->


<div id="modalEditarProductoSeries" class="modal fade modal-forms" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">

        <div class="box-body">

          <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
          <div role="tabpanel">
            <ul class="nav nav-tabs">
              <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">SERIES DISPONIBLES</a>
              </li>


            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
                <form action="" id="uSeries" name="uSeries">
                  <input type="hidden" id="idproductoS" name="idproductoS">
                  <div class="contenido-inputs-guias">

                  </div>


                </form>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>