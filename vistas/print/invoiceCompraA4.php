<?php
//Consultar los datos necesarios para mostrar en el PDF - FIN
if ($compra['codmoneda'] == 'PEN') {
    $tipoMoneda = 'S/ ';
    $nombreMoneda = 'SOLES';
} else {
    $tipoMoneda = '$USD ';
    $nombreMoneda = 'DÓLARES';
}
if ($compra['tipocomp'] == '01') {
    $rucdni = $emisor['ruc'];
    $razons_nombre = $emisor['nombre_comercial'];
    $tipodocemisor = 6;
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if ($compra['tipocomp'] == '03') {
    $rucdni = $emisor['ruc'];
    $razons_nombre = $emisor['nombre_comercial'];
    $tipodocemisor = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'] . ' ' . 'DE VENTA';
}
if ($compra['tipocomp'] == '07') {
    $rucdni = $emisor['ruc'];
    $razons_nombre = $emisor['nombre_comercial'];
    $tipodocemisor = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if ($compra['tipocomp'] == '08') {
    $rucdni = $emisor['ruc'];
    $razons_nombre = $emisor['nombre_comercial'];
    $tipodocemisor = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'];
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
        color: #333;
    }

    #tabla-cabecera h3 {
        font-size: 16px;
        margin-bottom: 1px;
        color: #444;
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
        font-size: 9px;
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

    .anulacion {
        font-size: 40px;
        color: #FF7979;
        text-align: center;
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

            <!--FIN LOGO================== -->
            <td class="v65">
                <h3><?php echo $proveedor['razon_social']; ?></h3>
                <label class="direccion"><?php echo $proveedor['direccion']; ?></label>

            </td>
            <td class="v30" style="text-align: left">
                <div class="ruc-emisor v100">
                    <h4>R.U.C. <?php echo $proveedor['ruc']; ?></h4>
                    <h4><?php echo $nombre_comprobante; ?></h4>
                    <h4><?php echo $compra['serie'] . ' - ' . str_pad($compra['correlativo'], 6, "0", STR_PAD_LEFT); ?></h4>
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
                echo  date("d/m/Y", strtotime($compra['fecha_emision']));
                ?>
            </td>
            <td class="v25">MEDIO DE PAGO:</td>
            <td class="v25 b-r"><?php
                                if (isset($metodo_pago['descripcion'])) {
                                    echo $metodo_pago['descripcion'];
                                }
                                ?></td>
        </tr>
        <tr>
            <td class="v25">CLIENTE:</td>
            <td class="v75" colspan="3"><?php echo $razons_nombre ?></td>

        </tr>
        <tr>
            <td class="v25">R.U.C/D.N.I:</td>
            <td class="v75" colspan="3"><?php echo $rucdni ?></td>

        </tr>
        <tr>
            <td class="v25">DIRECCIÓN:</td>
            <td class="v75" colspan="3"><?php echo $emisor['direccion'] ?></td>

        </tr>

    </table>
    <!--FIN CLIENTE COMPROBANTE=================== -->
    <?php
    if ($compra['anulado'] == 's') {
        echo '<div id="" class="anulacion">COMPRA ANULADA</div>';
    }
    ?>

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
            $descuentosItems += $fila['descuento'];
        }
        $descuentosItems;

        $descuentos = round($compra['descuento'] + $descuentosItems, 2);

        ?>

    </table>
    <!-- FIN ITEMS COMPROBANTE=================== -->
    <div class="total-letras">
        <?php
        echo 'SON: ' . CantidadEnLetra($compra['total'], $nombreMoneda);
        ?>
    </div>


    <table id="tabla-totales">

        <tr>
            <td class="v65" style="padding: 0px;">
                <table class="tabla-observacion">
                    <tr>
                        <td class="v30">


                        </td>

                        <td class="v70">


                        </td>

                    </tr>

                </table>

                <table class="tabla-tipo-pago">
                    <tr>
                        <td colspan="2">
                            <b>Información Adicional</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            LEYENDA
                        </td>
                    </tr>
                    <tr>
                        <td class="v45">FORMA DE PAGO:</td>
                        <td class="v55">Al contado</td>
                    </tr>

                </table>

            </td>

            <td class="v35">
                <table class="tabla-importes">
                    <tr>
                        <td class="v50 b-l">GRAVADAS:</td>
                        <td class="v50 b-r"><?php echo $tipoMoneda . number_format($compra['op_gravadas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>EXONERADAS:</td>
                        <td><?php echo $tipoMoneda . number_format($compra['op_exoneradas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>INAFECTAS:</td>
                        <td><?php echo $tipoMoneda . number_format($compra['op_inafectas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>GRATUITAS:</td>
                        <td><?php echo $tipoMoneda . number_format($compra['op_gratuitas'],2); ?></td>
                    </tr>
                    <tr>
                        <td>DESCUENTO(-):</td>
                        <td><?php echo  $tipoMoneda . number_format(($descuentos), 2); ?></td>
                    </tr>
                    <tr>
                        <td>ICBPER:</td>
                        <td><?php echo  $tipoMoneda . number_format($compra['icbper'],2); ?></td>
                    </tr>
                    <tr>
                        <td>IGV(18%):</td>
                        <td><?php echo  $tipoMoneda . number_format($compra['igv'],2); ?></td>
                    </tr>
                    <tr>
                        <td>TOTAL:</td>
                        <td><?php echo  $tipoMoneda . number_format($compra['total'],2); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</page>