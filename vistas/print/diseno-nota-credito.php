<?php

//Consultar los datos necesarios para mostrar en el PDF - FIN
if ($nota['codmoneda'] == 'PEN') {
    $tipoMoneda = 'S/ ';
    $nombreMoneda = 'SOLES';
} else {
    $tipoMoneda = '$USD ';
    $nombreMoneda = 'DÓLARES AMERICANOS';
}
// if($nota['codmoneda'] == 'PEN'){
//     $tipoMoneda = 'S/';
//     $nombre
// }else {
//     $tipoMoneda = '$USD';
// }
if ($nota['tipocomp_ref'] == '01') {
    $rucdni = $cliente['ruc'];
    $razons_nombre = $cliente['razon_social'];
    $tipodoccliente = 6;
    $nombre_comprobante = $tipo_comprobante['descripcion'] . ' ' . 'ELECTRÓNICA';
}
if ($nota['tipocomp_ref'] == '03') {
   
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
    
    $nombre_comprobante = $tipo_comprobante['descripcion'] . ' ' . 'ELECTRÓNICA';
}
if ($nota['tipocomp_ref'] == '02') {
    $rucdni = $cliente['documento'];
    $razons_nombre = $cliente['nombre'];
    $tipodoccliente = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'] . ' ' . 'DE VENTA';
}
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

    #tabla-cliente td {
        border: 0.5px solid #333;
        padding: 7px;
        text-align: left;
        font-size: 12px;
        padding-left: 10px;
        letter-spacing: 1px;
    }

    #tabla-totales td {
        padding: 7px;
        text-align: left;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding-left: 10px;
    }

    .tabla-importes td {
        border: 0.5px solid #333;
        padding: 7px;
        text-align: left;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding-left: 10px;
    }

    #tabla-cliente {
        margin-top: 10px;
    }

    #tabla-items {
        margin-top: 10px;
    }

    #tabla-items th {
        border: 0.5px solid #333;
        padding: 6px;
        text-align: center;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding-left: 10px;
    }

    #tabla-items td {
        border: 0.5px solid #333;
        padding: 6px;
        text-align: center;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding-left: 10px;
    }

    .ruc-emisor {
        position: relative;
        border: 1px solid #666;
        border-radius: 20px;
        text-align: center;
        vertical-align: top;

    }

    .ruc-emisor h4 {
        color: #444;
    }


    .v5 {
        width: 5%;
    }

    .v10 {
        width: 10%;
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

    .direccion {
        font-size: 10px;
    }

    .total-letras {
        width: 100%;
        border: 0.5px solid #333;
        font-size: 9px;
        text-align: left;
        border-radius: 10px;
        padding: 10px;
        margin-top: 5px;
        padding-left: 10px;
    }

    .tabla-observacion {
        position: relative;
        margin-top: 5px;


    }

    .tabla-observacion td {
        position: relative;
        vertical-align: baseline;


    }

    .tabla-tipo-pago {
        width: 70%;
        border-collapse: collapse;
    }

    .tabla-tipo-pago td {
        border-bottom: 0.5px solid #333;


    }

    .col {
        background-color: #999;
    }

    .pie-pag {
        padding: 10px;
        font-size: 12px;
        border: 0.5px solid #333;
        margin-top: 10px;
        border-radius: 10px;
    }

    .b-l {
        border-radius: 7px 0px 0px 0px;
    }

    .b-r {
        border-radius: 0px 7px 0px 0px;
    }

    .mayu {
        text-transform: uppercase;
    }

    .anulado-print {
        position: absolute;
        top: 30%;
        left: 23%;
        color: #FF7979;
        font-size: 30px;
        text-align: center;
        font-weight: bold
    }

    /*
    .descripcion-productos tr{
        border-bottom: 1px solid black;
    }
*/
</style>
<page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm">
    <page_header>


    </page_header>
    <page_footer>

    </page_footer>
    <?php if ($nota['anulado'] == 's') { ?>
        <div class="anulado-print">
            ANULADO<br>
            <span>
                <?php
                echo "Ticket de baja: " . $ticket['ticket'];
                ?>
            </span>

        </div>
    <?php } ?>
    <!-- CABECERA COMPROBANTE=================== -->
    <table id="tabla-cabecera">
        <tr>
            <!-- LOGO================== -->
            <?php
            if ($emisor['logo'] != '' || $emisor['logo'] != null) {
            ?>
                <td class="v25">

                    <img src="<?php

                                $logo =  (isset($emisor['logo'])) ? dirname(__FILE__) . '/../img/logo/' . $emisor['logo'] : '';
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
                    <h4>R.U.C. <?php echo $emisor['ruc']; ?></h4>
                    <h4><?php echo $nombre_comprobante; ?></h4>
                    <h4><?php echo $nota['serie'] . ' - ' . str_pad($nota['correlativo'], 6, "0", STR_PAD_LEFT); ?></h4>
                </div>
            </td>
        </tr>

    </table>
    <!--FIN CABECERA COMPROBANTE=================== -->

    <!--CLIENTE COMPROBANTE=================== -->
    <table id="tabla-cliente">
        <tr>
            <td class="v25 b-l">FECHA DE EMISIÓN:</td>
            <td class="v25">
                <?php
                echo  date("d/m/Y", strtotime($nota['fecha_emision']));
                ?>
            </td>

        </tr>
        <tr>
            <td class="v25">CLIENTE:</td>
            <td class="v75"><?php echo $razons_nombre ?></td>

        </tr>
        <tr>
            <td class="v25">R.U.C/D.N.I:</td>
            <td class="v75"><?php echo $rucdni ?></td>

        </tr>
        <tr>
            <td class="v25">DIRECCIÓN:</td>
            <td class="v75"><?php echo $cliente['direccion'] ?></td>

        </tr>
        <tr>
            <td class="v25">TIPO DE MONEDA:</td>
            <td class="v75"><?php echo $nombreMoneda ?></td>

        </tr>

    </table>
    <!--FIN CLIENTE COMPROBANTE=================== -->



    <!-- ITEMS COMPROBANTE=================== -->
    <table id="tabla-items">
        <tr class="">
            <th class="v10 b-l">ITEM</th>
            <th class="v10">CANTIDAD</th>
            <th class="v40">DESCRIPCIÓN</th>
            <th class="v20">VALOR U.</th>
            <th class="v20 b-r">IMPORTE</th>
        </tr>
        <?php
        $descuentosItems = 0.00;
        foreach ($detalle as $key => $fila) {

            $importe_total = ($fila['valor_unitario'] > 0) ? $fila['importe_total'] : 0.00;

        ?>
            <tr>
                <td class="v10"><?php echo ++$key ?></td>
                <td class="v10"><?php echo   $fila['cantidad']; ?></td>
                <td class="v40"><?php echo $fila['descripcion']; ?></td>
                <td class="v20"><?php echo  $tipoMoneda . ' ' . number_format($fila['valor_unitario'],2); ?></td>
                <td class="v20"><?php echo   $tipoMoneda . ' ' . number_format($importe_total,2); ?></td>
            </tr>

        <?php
           
        }
        
        ?>

    </table>
    <!-- FIN ITEMS COMPROBANTE=================== -->

    <table id="tabla-totales">

        <tr>
            <td class="v65" style="padding: 0px;">
                <table class="tabla-observacion">
                    <tr>
                        <td class="v30">

                            <?php
                            $ruc = $emisor['ruc'];
                            $tipo_documento = $nota['tipocomp'];
                            $serie = $nota['serie'];
                            $correlativo = $nota['correlativo'];
                            $igv = $nota['igv'];
                            $total = $nota['total'];
                            $fecha = $nota['fecha_emision'];
                            $tipodoccliente = 1;
                            $nro_doc_cliente = $rucdni;

                            ?>
                            <qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 26mm; background-color: white; color: #000; border: none; padding:none"></qrcode>

                        </td>

                        <td class="v70">

                            <b>Observación:</b><br>
                            Consulte su documento electrónico en https://www.sunat.gob.pe
                        </td>

                    </tr>

                </table>

       

            </td>

            <td class="v35">
                <table class="tabla-importes">
                    <tr>
                        <td class="v50 b-l">GRAVADAS:</td>
                        <td class="v50 b-r"><?php echo $tipoMoneda . number_format($nota['op_gravadas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>EXONERADAS:</td>
                        <td><?php echo $tipoMoneda . number_format($nota['op_exoneradas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>INAFECTAS:</td>
                        <td><?php echo $tipoMoneda . number_format($nota['op_inafectas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>GRATUITAS:</td>
                        <td><?php echo $tipoMoneda . number_format($nota['op_gratuitas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>DESCUENTO(-):</td>
                        <td><?php echo  $tipoMoneda . number_format($nota['descuento'], 2); ?></td>
                    </tr>
                   
                    <tr>
                        <td>IGV(18%):</td>
                        <td><?php echo  $tipoMoneda . number_format($nota['igv'],2); ?></td>
                    </tr>
                    <tr>
                        <td>TOTAL:</td>
                        <td><?php echo  $tipoMoneda . number_format($nota['total'],2); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="width:100mm; padding:5px;padding-left: 16px; font-size:10px">
        <?php
        if ($nota['tipocomp_ref'] == "01") {
            $nombreComprobante = "FACTURA";
        }
        if ($nota['tipocomp_ref'] == "03") {
            $nombreComprobante = "BOLETA";
        }
        echo 'Motivo de emisión: ' . $motivoNota['codigo'] . ' - ' . $motivoNota['descripcion'] . '<br>';
        echo 'Documento relacionado: ' . $nombreComprobante . ' ' . $nota['serie_ref'] . '-' . str_pad($nota['correlativo_ref'], 6, '0', STR_PAD_LEFT);

        ?>
    </div>


    <!--   <barcode dimension="1D" type="EAN13" value="45" label="label" style="width:30mm; height:6mm; color: #770000; font-size: 4mm"></barcode> -->

</page>