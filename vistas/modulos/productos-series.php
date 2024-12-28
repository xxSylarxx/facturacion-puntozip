<?php
require_once "../../vendor/autoload.php";

use Controladores\ControladorProductos;

$item = 'id_producto';
$valor = $_REQUEST['idproductoS'];
$seriesProducto = ControladorProductos::ctrMostrarSeriesProductosActualizar($item, $valor);

$item = 'id';
$valor = $valor = $_REQUEST['idproductoS'];;
$producto = ControladorProductos::ctrMostrarTodosProductos($item, $valor, null);

?>
<div class="nombre-producto-contenedor">
    <?php echo $producto['descripcion']; ?>
</div>
<?php
foreach ($seriesProducto as $k => $serie) {
    $readonly = $serie['disponible'] == 'n'? 'readonly': '';
?>
<div class="inputs-series" id="eliminar_seccion_serie<?php echo $serie['id'] ?>">
    <input type="text" id="serieA" name="serieA" idSerie="<?php echo $serie['id'] ?>" value="<?php echo $serie['serie'] ?>" style="margin-top: 5px;" <?php echo $readonly ?>><br/>

    <input type="checkbox" data-toggle="toggle" data-on="Disponible" data-off="No disponible" data-onstyle="primary" data-offstyle="danger" id="Seriesdisponibles" name="Seriesdisponibles" data-size="mini" data-width="120" data-height="20" idSerie="<?php echo $serie['id'] ?>" idProducto="<?php echo $serie['id_producto'] ?>" value="<?php echo $serie['disponible'] ?>" " <?php if ($serie['disponible'] == 'n') {                                                                                              } else { ?>checked <?php } ?>>
    <button class="btn btn-danger btn-xs btn-eliminar-serie-bd" idSerie="<?php echo $serie['id'] ?>"><i class="fas fa-trash-alt"></i>
    </button>
    <br/>

</div>

<?php
}
?>
<script>
    function toggleOK() {
        $("[name='Seriesdisponibles']").bootstrapToggle();
    }
    toggleOK();
</script>