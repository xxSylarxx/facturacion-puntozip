<?php
$colores = array('#E8EAF6', '#FAFAFA');
?>
<style>
    #tabla-cabecera,
    #tabla-cliente,
    #tabla-items,
    #tabla-totales,
    .tabla-importes,
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

    .v14 {
        width: 14%;
    }

    .v15 {
        width: 15%;
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

    .tabla-comprobantes {
        margin-top: 10px;
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
                <h3><?php echo $emisor['nombre_comercial']; ?></h3>
                <label class="direccion"><?php echo $sucursal['direccion']; ?></label>
                <br>
                <span class="direccion">Telf. <?php echo $sucursal['telefono']; ?></span>

            </td>
            <td class="v30" style="text-align: left">
                <div class="ruc-emisor v100">
                    <h4>KARDEX</h4>
                    <h3>FECHA Y HORA</h3>
                    <h3><?php echo date("d/m/Y H:i:s") ?></h3>
                </div>
            </td>
        </tr>

    </table>
    <!--FIN CABECERA COMPROBANTE=================== -->
    <table class="tabla-comprobantes">
        <tr>

            <th colspan="6" style="background:#3F51B5; color:#fff;" class="titulo bgth2 cw">KARDEX</th>
        </tr>


        <tr>
            <th colspan="2" class="" style="background:#1E88E5; color:#fff;">
                FECHA Y DOCU/ DESC MVTO.
            </th>
            <th colspan="4" class="v40" style="background:#81C784; color:#fff;">
                UNIDADES
            </th>
        </tr>



        <tr class="">
            <!-- <th>#</th> -->
            <th>FECHA</th>
            <th>MOVIMIENTO</th>
            <th>INV. INICIAL</th>
            <th>ENTRADA</th>
            <th>SALIDA</th>
            <th>INV. FINAL</th>
        </tr>
        <?php
        foreach ($registros as $k => $v) {
            $entrada = 0;
            $salida = 0;

            $entrada = $v['accion'] == 'entrada' ? $v['cantidad'] : 0;
            $salida = $v['accion'] == 'salida' ? $v['cantidad'] : 0;


            $stok_actual = $v['accion'] == 'entrada' ? $v['stock_actual'] - $entrada : $v['stock_actual'] + $salida;
            $color = $k % 2 == 0 ? $colores[0] : $colores[1];

            echo '<tr s style="background: ' . $color . ';">
                    
                    <td class="v20">
                    ' . date_format(date_create($v['fecha']), 'd/m/Y  H:i:s') . '
                    </td>
                    <td class="v30">
                     ' . $v['movimiento'] . '
                     </td>
                    <td class="v20">
                     ' . $stok_actual . '
                     </td>
                    <td class="v10">
                     ' . $entrada . '
                     </td>
                    <td class="v10">
                     ' . $salida . '
                     </td>
                    <td class="v10">
                     ' . $v['stock_actual'] . '
                     </td>
                     </tr>';
        }


        ?>




    </table>
</page>