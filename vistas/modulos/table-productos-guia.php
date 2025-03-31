<?php

use Controladores\ControladorProductos;
use Controladores\ControladorSucursal;

$sucursal = ControladorSucursal::ctrSucursal();
?>
<div class="contenedor-busqueda">
      <div class="input-group-search">
            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadProductosG(1)">
                  <option value="5">5</option>
                  <option value="10"">10</option>
                <option value=" 20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
            </select>
            <div class="input-search">
                  <input type="search" class="search" id="searchProductoG" name="searchProductoG" placeholder="Buscar..." onkeyup="loadProductosG(1)" style="width:100%">
            </div>
      </div>
</div>
<div class="table-responsive">
      <table class="table table-bordered tablaGuia tabla-add-guia">
            <?php
            if ($sucursal['activo'] == 'n') {
                  echo "<tr><td colspan='10' style='text-align:center;'><h3>LA SUCURSAL | ALMACÉN FUE DESACTIVADO POR EL ADMINISTRADOR</h3></td></tr>";
                  exit();
            }
            ?>
            <thead>

                  <tr>
                        <th style="width:10px">#</th>
                        <!-- <th>Imagen</th> -->
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Unidad M</th>
                        <th>Cantidad</th>
                        <th class="btn-prod"></th>

                  </tr>
            </thead>
            <?php

            $listaProductos = new ControladorProductos();
            $listaProductos->ctrListarProductosGuia();

            ?>


      </table>
</div>