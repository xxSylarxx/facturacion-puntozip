<?php
$colores = array('#E8EAF6', '#FAFAFA');
$fecha_apertura = $arqueoCaja['fecha_apertura'] != null ? date_format(date_create($arqueoCaja['fecha_apertura']), 'd/m/Y  H:i:s') : null;
$fecha_cierre = $arqueoCaja['fecha_cierre'] != null ? date_format(date_create($arqueoCaja['fecha_cierre']), 'd/m/Y  H:i:s') : null;
?>
<style>
    #tabla-cabecera,
    #tabla-cliente,
    #tabla-items,
    #tabla-totales,
    .tabla-importes,
    .tabla-comprobantes,
    .tabla-datosuc,
    .tabla-observacion {
        position: relative;
        width: 100%;
        border-collapse: collapse;

    }

    #tabla-cabecera {
        text-align: center;
        letter-spacing: 0.5px;
        /* color: #000; */
    }

    #tabla-cabecera h3 {
        font-size: 16px;
        margin-bottom: 1px;
        /* color: #000; */
    }

    .ruc-emisor {
        position: relative;
        border: 1px solid #666;
        border-radius: 20px;
        text-align: center;
        padding: 5px;

    }

    .ruc-emisor h4 {
        color: #2196F3;
        margin: 5px;
        font-size: large;
        /* background: #666; */
    }

    .ruc-emisor h3 {
        color: #444;
        margin: 5px;
    }

    .v5 {
        width: 5%;
    }

    .v8 {
        width: 8%;
    }

    .v10 {
        width: 10%;
    }

    .v12 {
        width: 12%;
    }

    .v14 {
        width: 14%;
    }

    .v15 {
        width: 15%;
    }

    .v17 {
        width: 17%;
    }

    .v20 {
        width: 20%;
    }

    .v25 {
        width: 25%;
    }

    .v30 {
        width: 30%;
    }

    .v35 {
        width: 35%;
    }

    .v40 {
        width: 40%;
    }

    .v45 {
        width: 45%;
    }

    .v50 {
        width: 50%;
    }

    .v55 {
        width: 55%;
    }

    .v60 {
        width: 60%;
    }

    .v65 {
        width: 65%;
    }

    .v70 {
        width: 70%;
    }

    .v75 {
        width: 75%;
    }

    .v80 {
        width: 80%;
    }

    .v90 {
        width: 90%;
    }

    .v100 {
        width: 100%;
    }

    .tabla-comprobantes,

    .tabla-header {
        position: relative;
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .tabla-header td {
        padding: 10px;
    }

    .tabla-header h3 {
        color: #555;
        letter-spacing: 2px;
    }

    .tabla-comprobantes,
    .tabla-datosuc {
        margin-top: 10px;
    }

    .tabla-datosuc td {
        border: 1px solid #ccc;
        padding: 10px;
    }

    .tabla-comprobantes th {
        padding: 10px;
        /* border: 1px solid #ccc; */
        text-transform: uppercase;
        text-align: center;
    }

    .tabla-comprobantes td {
        padding: 10px;
        /* border: 1px solid #ccc; */
        color: #000;
    }

    .direccion {
        font-size: 10px;
    }

    .center {
        text-align: center;
    }

    .text-rigth {
        text-align: right;
    }

    .titulo {
        font-size: 16px;
        color: #333;
        /* background-color: #F5F5F5; */
    }

    .fecha {
        font-size: 15px;
        color: #333;
        /* background-color: #F5F5F5; */
    }

    .totales {
        font-weight: 600;
        font-size: 14px;
        background-color: #F5F5F5;
    }

    .fa {
        background-color: #DAFFC7;
    }

    .bo {
        background-color: #CCDCFF;
    }

    .bgtitulo {
        background-color: #FFFF;
    }

    .bgth {
        background-color: #2C3E50;
    }

    .bgth2 {
        background-color: #424242;
    }

    .bgth3 {
        background-color: #616161;
    }

    .cw {
        color: #FFFFFF;
    }

    .raso {
        font-size: 2em;
    }

    .tabla-comprobantes td {
        text-align: center;
        font-size: 14px;
    }
</style>
<page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm">
    <page_header>


    </page_header>
    <page_footer>

    </page_footer>
    <!-- CABECERA COMPROBANTE=================== -->
    <table id="tabla-cabecera">
        <tr>
            <!-- LOGO================== -->
            <?php
            if ($emisor['logo'] != '' || $emisor['logo'] != null) {
            ?>
                <td class="v25">

                    <img src="<?php

                                $logo =  (isset($emisor['logo'])) ? dirname(__FILE__) . '/../../img/logo/' . $emisor['logo'] : '';
                                echo $logo;
                                ?>" class="v100">
                </td>


            <?php
            } else {
                echo '<td class="v25"></td>';
            }
            ?>
            <!--FIN LOGO================== -->
            <td class="v45">
                <h3 class="rasoO"><?php echo $emisor['nombre_comercial']; ?></h3>
                <label class="direccion"><?php echo $emisor['direccion']; ?></label>
                <br>
                <span class="direccion">Telf. <?php echo $emisor['telefono']; ?></span>

            </td>
            <td class="v30" style="text-align: left">
                <div class="ruc-emisor v100">
                    <h4>MOVIMIENTO DE CAJA</h4>
                    <h3><?php echo $caja['nombre'] . ' ' . $caja['numero_caja'] ?></h3>
                    <h3><?php echo 'N°. ' . $caja['id'] ?></h3>
                </div>
            </td>
        </tr>

    </table>
    <!--FIN CABECERA COMPROBANTE=================== -->
    <table class="tabla-datosuc v100">
        <tr class="v100">

            <td class="v25 text-rigth">FECHA APERTURA: </td>
            <td class="v25"><?php echo $fecha_apertura ?></td>
            <td class="v25 text-rigth">FECHA DE CIERRE: </td>
            <td class="v25"><?php echo $fecha_cierre ?></td>

        </tr>
        <tr class="v100">

            <td class="v25 text-rigth">RESPONSABLE: </td>
            <td class="v75" colspan="3"><?php echo strtoupper($usuario['nombre']) ?></td>
        </tr>
    </table>
    <table class="tabla-comprobantes">




        <tr class="bgth3 cw v100">
            <!-- <th>#</th> -->
            <!-- <th>CAJA</th>
            <th>FECHA DE APERTURA</th>
            <th>FECHA DE CIERRE</th> -->
            <th>MONTO DE APERTURA</th>
            <th>INGRESOS</th>
            <th>EGRESOS</th>
            <th>GASTOS</th>
            <th>SALDO FINAL</th>
        </tr>
        <?php




        echo '<tr>
                    
                  
                    <td class="v20">
                     ' . $arqueoCaja['monto_inicial'] . '
                     </td>
                     <td class="v20">
                     ' . number_format(($arqueoCaja['monto_final'] - $arqueoCaja['monto_inicial']) + $arqueoCaja['gastos'], 2) . '
                     </td>
                     <td class="v20">
                     ' . number_format($arqueoCaja['egresos'] + $arqueoCaja['gastos'], 2) . '
                     </td>
                     <td class="v20">
                     ' . number_format($arqueoCaja['gastos'], 2) . '
                     </td>
                     <td class="v20">
                      ' . number_format($arqueoCaja['monto_final'], 2) . '
                      </td>
                     </tr>';



        ?>




    </table>
</page>