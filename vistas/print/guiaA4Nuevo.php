<?php

use Controladores\ControladorProductos;
use Controladores\ControladorClientes;
use Controladores\ControladorConductores;
use Controladores\ControladorGuiaRemision;

$dataCliente = ControladorClientes::ctrBucarClienteId($guia['id_cliente']);
$tipoVehiculo = ControladorGuiaRemision::ctrMostrarTiposVehiculo('tipo_vehiculo', null, null);
$tipoVehiculoDes = '';
foreach ($tipoVehiculo as $value) {
    if ($value['id'] == $guia['tipovehiculo']) {
        $tipoVehiculoDes = $value['descripcion'];
        break;
    }
}
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
        padding: 4px 2px;
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
        padding: 4px 8px;
        border-radius: 2px;
    }

    #tabla-destinatario th,
    #tabla-destinatario td {
        border: 1px solid black;
        padding: 4px 8px;
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
        padding: 4px 8px;
    }

    #tabla-firmas {
        position: relative;
        font-size: 10px;
        left: 15px;
    }

    #pie {
        position: relative;
        width: 72%;
        left: -155px;
        text-align: center;
        margin-top: 42px;
    }
</style>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
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
                    <p style="font-size: 14px; margin-bottom: -6px; font-weight: bold;">PUNTOZIP S.A.C</p>
                    <p style="font-size: 13px;">CAL. SAN AURELIO NRO. 266 COO. SANTA LUISA</p>
                </td>
                <td style="width: 260px;">
                    <div id="ruc-emisor">
                        <p style="font-size: 15px; font-weight: bold; margin-bottom: 0px;">R.U.C. 20505573162</p>
                        <p style="font-size: 15px; font-weight: bold; line-height: 23px;">GUÍA DE REMISIÓN REMITENTE<br>ELECTRÓNICA</p>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -5px;"><?php echo $guia['serie'] . '-' . str_pad($guia['correlativo'], 8, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </td>
            </tr>
        </table>
        <br>
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
                <td width="322"><?php echo $guia['direccionPartida'] ?></td>
                <td width="322"><?php echo $guia['direccionLlegada'] ?></td>
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
                    LICENCIA DE CONDUCIR: <b><?php echo '4242344554' ?></b>
                </td>
                <td width="203">
                    NOMBRE O RAZON SOCIAL <b><?php echo $guia['transp_nombreRazon'] ?></b><br>
                    N° R.U.C.: <b><?php echo $guia['transp_numDoc'] ?></b><br>
                    CHOFER: <b><?php echo $guia['transp_nombreRazon'] ?></b>
                </td>
            </tr>
        </table>
        <p style="font-size: 11px;"><b>MOTIVO DE TRASLADO:</b> 17 TRASLADO DE BIENES PARA TRANSFORMACIÓN</p>
        <div style="width: 100%; border-radius: 4px; border: 1px solid black; padding: 6px; font-size: 9px;">
            <table>
                <tr>
                    <td>01 Venta</td>
                    <td>13 Otros</td>
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
                    /* $descripcion = json_decode($guia['descripcion']);
                    $itemd = $key;
                    $idSeries = json_decode($guia['series']); */
                ?>
                    <tr>
                        <td width="50" style="text-align: center; vertical-align: middle;"><?php echo $fila['codigo']; ?></td>
                        <td width="40" style="text-align: center; vertical-align: middle;"><?php echo $fila['cantidad']; ?></td>
                        <td width="40" style="text-align: center; vertical-align: middle;"><?php echo $fila['codunidad']; ?></td>
                        <td width="445"><?php echo $fila['descripcion'] . ' COLOR: ' . $fila['color'] . ' - PESO: ' . $fila['peso'] . ' - BULTOS: ' . $fila['bultos'] . ' - PARTIDA: ' . $fila['partida']; ?></td>
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
            <?php echo $guia['observacion'] ?>
        </div>
        <br>
        <br><br>
        <table id="tabla-firmas">
            <tr>
                <td style="text-align: center; width: 200px;">
                    <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                        TRANSPORTADO POR
                    </div>
                </td>
                <td style="text-align: center; width: 200px;">
                    <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                        PUNTOZIP S.A.C.
                    </div>
                </td>
                <td style="text-align: center; width: 200px;">
                    <div style="width: 150px; border-top: 1px solid black; font-weight: bold; padding-top: 5px;">
                        RECIBI CONFORME
                    </div>
                </td>
            </tr>
        </table>
        <br><br><br><br>
        <div id="pie">
            <div class="bar-code">
                <qrcode class="barcode" value="https://puntozip.riaac.com/vistas/print/printguia/?idCo=<?php echo $guia['id'] ?>" style="width: 26mm; background-color: white; color: #000; border: none; padding:none"></qrcode>
            </div>
            <div style="font-size: 10px; text-align: center;">
                <p style="line-height: 12px;">
                    Representación impresa de la Guía de Remisión Remitente Electrónica, para consultar el documento visité <b>http://www.grupovisualcont.com/</b>
                    Autorizado mendiante la Resolución de Intendencia Nro. 0340050007579
                    <b>Resumen: ---</b>
                </p>
            </div>
        </div>
    </div>
</page>