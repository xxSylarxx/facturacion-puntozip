<?php
require_once "../../vendor/autoload.php";

use Controladores\ControladorProductos;

$item = 'id_producto';
$valor = $_REQUEST['idproductoS'];
$seriesProducto = ControladorProductos::ctrMostrarSeriesProductosGuias($item, $valor);

$item = 'id';
$valor = $valor = $_REQUEST['idproductoS'];;
$producto = ControladorProductos::ctrMostrarTodosProductos($item, $valor, null);

?>
<input type="hidden" name="codproducto" id="codproducto" value="<?php echo $producto['codigo']; ?>">
<div class="nombre-producto-contenedor">
    <?php echo $producto['descripcion']; ?>
</div>
<div class="input-group-series">
<?php
foreach ($seriesProducto as $k => $serie) {
    
?>
<div class="inputs-series-guias" id="eliminar_seccion_serie<?php echo $serie['id'] ?>">
    
    <input type="text" id="serieG<?php echo $serie['id'] ?>" name="serieG" idSerie="<?php echo $serie['id'] ?>" value="<?php echo $serie['serie'] ?>" style="margin-top: 5px;" readonly>
   
    <span class="btn btn-primary btn-primary-serie btn-add-serie-guia" idSerie="<?php echo $serie['id'] ?>" idProducto="<?php echo $serie['id_producto'] ?>" style="position:absolute; right:0px; top:-0px !important; color:#fff;"><i class="fas fa-plus"></i>
    </span>
</div>


<?php
}
?>
</div>
<script>
    function toggleOK() {
        $("[name='Seriesdisponibles']").bootstrapToggle();
    }
    toggleOK();
</script>

