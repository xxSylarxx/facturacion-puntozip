<?php
$dominio = $_SERVER['HTTP_HOST'];
$uri = rtrim($_SERVER['REQUEST_URI'], '/');

use Controladores\ControladorProductos;
use Controladores\ControladorClientes;
use Controladores\ControladorConductores;
use Controladores\ControladorGuiaRemision;

$dataCliente = ControladorClientes::ctrBucarClienteId($guia['id_cliente']);
$tipoVehiculo = ControladorGuiaRemision::ctrMostrarTiposVehiculo('tipo_vehiculo', null, null);
$conductor = ControladorConductores::ctrBucarConductorId($guia['id_conductor']);
$dataUbigeo = ControladorClientes::ctrBuscarUbigeoMejorado();
if ($conductor == false) {
    $conductor = [];
}
$tipoVehiculoDes = '';
foreach ($tipoVehiculo as $value) {
    if ($value['id'] == $guia['tipovehiculo']) {
        $tipoVehiculoDes = $value['descripcion'];
        break;
    }
}

$tipoTraslado = ControladorGuiaRemision::ctrMostrarTraslado('motivo_traslado', 'codigo', $guia['cod_traslado']);
$trasladoDesc = '';
if (is_array($tipoTraslado)) {
    $trasladoDesc = $tipoTraslado['descripcion'];
}
/* var_dump($sucursal);
exit(); */
?>
<style>
    #tabla-fechas,
    #tabla-cabecera,
    #tabla-llegada,
    #tabla-destinatario {
        position: relative;
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    #tabla-cabecera {
        text-align: center;
    }

    #tabla-fechas th,
    #tabla-fechas td {
        border: 1px solid black;
        padding: 3px 2px;
        border-radius: 2px;
        width: 120px;
        text-align: center;
    }

    #ruc-emisor {
        position: relative;
        border: 1px solid black;
        border-radius: 20px;
        text-align: center;
        vertical-align: top;
        width: 100%;
        left: -120px;
        padding: 1px 5px 12px 5px;
    }

    #tabla-llegada th,
    #tabla-llegada td {
        border: 1px solid black;
        padding: 3px 8px;
        border-radius: 2px;
    }

    #tabla-destinatario th,
    #tabla-destinatario td {
        border: 1px solid black;
        padding: 3px 8px;
        border-radius: 2px;
    }

    #tabla-productos {
        position: relative;
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    #tabla-productos th {
        background-color: rgb(230, 230, 230);
        border: 1px solid black;
        padding: 4px 8px;
    }

    #tabla-productos td {
        border: 1px solid black;
        padding: 3px 8px;
    }

    #tabla-firmas {
        position: relative;
        font-size: 10px;
        left: 15px;
    }

    #pie {
        position: relative;
        width: 60%;
        left: -56px;
        text-align: center;
        margin-top: 32px;
    }
</style>
<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm">
    <page_header>
    </page_header>
    <page_footer>
    </page_footer>
    <div>
        <table id="tabla-cabecera">
            <tr>
                <td style="width: 440px; text-align: center;">
                    <img src="<?php
                                $logo =  (isset($emisor['logo'])) ? dirname(__FILE__) . '/../img/logo/puntozipsac.png' : '';
                                echo $logo;
                                ?>" style="width: 150px;">
                    <p style="font-size: 14px; margin-bottom: -6px; font-weight: bold;"><?php echo $sucursal['nombre_sucursal'] ?></p>
                    <p style="font-size: 13px;"><?php echo $sucursal['direccion'] . ' ' . $sucursal['distrito'] . ' - ' . $sucursal['provincia'] . ' - ' . $sucursal['departamento']; ?></p>
                </td>
                <td style="width: 260px;">
                    <div id="ruc-emisor">
                        <p style="font-size: 15px; font-weight: bold; margin-bottom: 0px;">R.U.C. <?= $sucursal['codigo']; ?></p>
                        <p style="font-size: 15px; font-weight: bold; line-height: 23px;">GUÍA DE REMISIÓN REMITENTE<br>ELECTRÓNICA</p>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -5px;"><?php echo $guia['serie'] . ' - ' . str_pad($guia['correlativo'], 8, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </td>
            </tr>
        </table>
        <table id="tabla-fechas">
            <tr>
                <th>FECHA DE EMISIÓN</th>
                <th>FECHA DE TRASLADO</th>
                <th>N° DE FACTURA</th>
            </tr>
            <tr>
                <td>
                    <?php
                    $fechaEmision = $guia['fecha_emision'];
                    $fechaE = DateTime::createFromFormat('Y-m-d', $fechaEmision);
                    $fechaEmision = $fechaE->format('d/m/Y');
                    echo $fechaEmision;
                    ?>
                </td>
                <td>
                    <?php
                    $fechaTraslado = $guia['fechaTraslado'];
                    $fechaT = DateTime::createFromFormat('Y-m-d', $fechaTraslado);
                    $fechaTraslado = $fechaT->format('d/m/Y');
                    echo $fechaTraslado;
                    ?>
                </td>
                <td></td>
            </tr>
        </table>
        <br>
        <table id="tabla-llegada">
            <tr>
                <th width="322">DIRECCIÓN DE PARTIDA</th>
                <th width="322">DIRECCIÓN DE LLEGADA</th>
            </tr>
            <tr>
                <td width="322"><?php echo $guia['direccionPartida'] . ' ' . $dataUbigeo[$guia['ubigeoPartida']]['nombre_distrito'] . ' - ' . $dataUbigeo[$guia['ubigeoPartida']]['nombre_provincia'] . ' - ' . $dataUbigeo[$guia['ubigeoPartida']]['name']; ?></td>
                <td width="322"><?php echo $guia['direccionLlegada'] . ' ' . $dataUbigeo[$guia['ubigeoLlegada']]['nombre_distrito'] . ' - ' . $dataUbigeo[$guia['ubigeoLlegada']]['nombre_provincia'] . ' - ' . $dataUbigeo[$guia['ubigeoLlegada']]['name']; ?></td>
            </tr>
        </table>
        <br>
        <table id="tabla-destinatario">
            <tr>
                <th width="203">DESTINATARIO</th>
                <th width="203">UNIDAD DE TRANSPORTE Y CONDUCTOR</th>
                <th width="203">TRANSPORTISTA</th>
            </tr>
            <tr>
                <td width="203">
                    NOMBRE: <b><?php echo $dataCliente['razon_social'] ?></b><br>
                    DIRECCIÓN: <b><?php echo $dataCliente['direccion'] ?></b><br>
                    N° RUC: <b><?php echo $dataCliente['ruc'] ?></b>
                </td>
                <td width="203">
                    TIPO VEHICULO Y PLACA: <b><?php echo $tipoVehiculoDes . ' / ' . $guia['transp_placa'] ?></b><br>
                    LICENCIA DE CONDUCIR: <b><?php echo isset($conductor['numbrevete']) ? $conductor['numbrevete'] : '' ?></b>
                </td>
                <td width="203">
                    NOMBRE O RAZON SOCIAL <b><?php echo $guia['transp_nombreRazon'] ?></b><br>
                    N° R.U.C.: <b><?php echo $guia['transp_numDoc'] ?></b><br>
                    CHOFER: <b><?php echo $guia['transp_nombreRazon'] ?></b>
                </td>
            </tr>
        </table>
        <p style="font-size: 11px;"><b>MOTIVO DE TRASLADO:</b> <?php echo $guia['cod_traslado'] . ' ' . $trasladoDesc ?></p>
        <div style="width: 100%; border-radius: 4px; border: 1px solid black; padding: 4px 6px; font-size: 8px;">
            <table>
                <tr>
                    <td>01 VENTA</td>
                    <td>13 OTROS</td>
                </tr>
                <tr>
                    <td>02 COMPRA</td>
                    <td>14 VENTA SUJETA A CONFIRMACION DEL COMPRADOR</td>
                </tr>
                <tr>
                    <td>04 TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA</td>
                    <td>17 TRASLADO DE BIENES PARA TRANSFORMACIÓN</td>
                </tr>
                <tr>
                    <td>08 IMPORTACIÓN</td>
                    <td>18 TRASLADO EMISOR ITINERANTE CP</td>
                </tr>
                <tr>
                    <td>08 EXPORTACION</td>
                    <td>19 TRASLADO A ZONA PRIMARIA</td>
                </tr>
            </table>
        </div>
        <p style="font-size: 11px;"><b>REMITIMOS A UD.(ES) EN BUENAS CONDICIONES LO SIGUIENTE</b></p>
        <div>
            <table id="tabla-productos">
                <tr>
                    <th width="50" style="text-align: center;">CÓDIGO</th>
                    <th width="40" style="text-align: center;">CANTIDAD</th>
                    <th width="40" style="text-align: center;">UNIDAD</th>
                    <th width="445" style="text-align: center;">PRODUCTO</th>
                </tr>
                <?php
                $series = array();
                foreach ($detalle as $key => $fila) {
                ?>
                    <tr>
                        <td width="50" style="text-align: center; vertical-align: middle;"><?php echo $fila['codigo']; ?></td>
                        <td width="40" style="text-align: center; vertical-align: middle;"><?php echo $fila['cantidad']; ?></td>
                        <td width="40" style="text-align: center; vertical-align: middle;"><?php echo $fila['codunidad']; ?></td>
                        <td width="445">
                            <b>SERVICIO : <?php echo $fila['servicio']; ?></b><br>
                            <?php echo $fila['categoria_des']; ?> :
                            <?php echo $fila['descripcion'] . ' ' . $fila['caracteristica'] . ' - COLOR: (' . $fila['color'] . ') - PO: (' . $fila['PO'] . ') - PARTIDA: (' . $fila['partida'] . ') - ' . $fila['adicional'] . ' - PESO: (' . $fila['peso'] . ') - BULTOS: (' . $fila['bultos'] . ')'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="4">Peso Bruto (KGM): <?php echo $guia['pesoTotal'] ?></td>
                </tr>
                <tr>
                    <td colspan="4">Numero de Bultos: <?php echo $guia['numBultos'] ?></td>
                </tr>
            </table>
        </div>
        <p style="font-size: 11px; margin-bottom: 5px;"><b>INFORMACIÓN ADICIONAL</b></p>
        <div style="width: 100%; border-radius: 3px; border: 1px solid black; padding: 6px; font-size: 11px;">
            <?php echo $guia['observacion']; ?>
        </div>
        <br>
        <br><br>
        <div>
            <table id="tabla-firmas">
                <tr>
                    <td style="text-align: center; width: 200px;">
                        <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                            TRANSPORTADO POR
                        </div>
                    </td>
                    <td style="text-align: center; width: 200px;">
                        <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                            <?php echo $sucursal['nombre_sucursal'] ?>
                        </div>
                    </td>
                    <td style="text-align: center; width: 200px;">
                        <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                            RECIBI CONFORME
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="pie">
            <br><br>
            <div class="bar-code">
                <qrcode class="barcode" value="<?php echo $dominio; ?>/vistas/print/printguia/?idCo=<?php echo $guia['id'] ?>" style="width: 20mm; background-color: white; color: #000; border: none; padding:none"></qrcode>
            </div>
            <div style="font-size: 10px; text-align: center;">
                <p style="line-height: 12px;">
                    Representación impresa de la Guía de Remisión Remitente Electrónica.
                </p>
            </div>
        </div>
    </div>
</page>