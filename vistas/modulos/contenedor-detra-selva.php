<?php
session_start();
require_once("../../vendor/autoload.php");

use Controladores\ControladorEmpresa;

$emisor = ControladorEmpresa::ctrEmisor();
?>


<div class="modo-contenedor-selva">
    <label for="">¿Venta Sujeta a Detracción?</label>
    <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="primary" data-offstyle="danger" id="detraccion" name="detraccion" data-size="small" data-width="80" value="si">
</div>

<div class="modo-contenedor-selva seccion-detra-s">
    <label for="">¿Bienes Región Selva?</label>
    <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="primary" data-offstyle="danger" id="bienesSelva" name="bienesSelva" value="si" data-size="small" data-width="80" <?php if ($emisor['bienesSelva'] == 'n') {
                                                                                                                                                                                                            } else { ?>checked <?php } ?>>
</div>


<div class="modo-contenedor-selva seccion-detra-s">
    <label for="">¿Servicios Región Selva?</label>
    <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="primary" data-offstyle="danger" id="serviciosSelva" name="serviciosSelva" value="si" data-size="small" data-width="80" <?php if ($emisor['serviciosSelva'] == 'n') {
                                                                                                                                                                                                                } else { ?>checked <?php } ?>>
</div>
<script>
    function toggleOK() {
        $("[name='bienesSelva']").bootstrapToggle();
        $("[name='serviciosSelva']").bootstrapToggle();
        $("[name='detraccion']").bootstrapToggle();
    }
    toggleOK();
</script>