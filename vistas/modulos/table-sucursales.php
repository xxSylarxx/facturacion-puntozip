<?php
session_start();
require_once "../../vendor/autoload.php";

use Controladores\ControladorSucursal;

?>
<!-- table-bordered table-striped  -->
<table class="table  dt-responsive tablas tbl-t" width="100%">

    <thead>
        <tr>
            <th style="width:10px;">#</th>
            <th>CÓDIGO</th>
            <th>NOMBRE</th>
            <th>ESTADO</th>
            <th>Acciones</th>
        </tr>
    </thead>


    <tbody>
        <?php
        $item = null;
        $valor = null;
        $sucursales = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
        // <button class="btn btn-danger btnEliminarSucursal" idSucursal="' . $value['id'] . '"><i class="fas fa-trash-alt"></i></button>
        foreach ($sucursales as $key => $value) {
            $check = $value['activo'] == 's' ? 'checked' : '';
            echo '<tr>
                    <td>' . ++$key . '</td>
                    <td>' . $value['codigo'] . '</td>
                    <td>' . $value['nombre_sucursal'] .
            '</td>
                    <td>
                    <script>
                $(function() {
        $(".activeS' . $value['id'] . '").bootstrapToggle();
    })
               </script>';
            if ($value['codigo'] == '0000') {
            } else {
                echo '<input type="checkbox" data-toggle="toggle" data-on="Activado" data-off="Desactivado" data-onstyle="primary" data-offstyle="danger" idSucursal="' . $value['id'] . '" id="activoS" class="activeS' . $value['id'] . '" name="activoS" value="s" data-size="small" data-width="110" ' . $check . '>';
            }
                    echo '</td>
                    <td>
                    <button class="btn btn-warning btnEditarSucursal" idSucursal="' . $value['id'] . '"   data-toggle="modal" data-target="#modalEditarSucursal"><i class="fas fa-user-edit"></i></button> 
               
                
                    </td>
                    </tr>';
        }
        ?>
    </tbody>

</table>




<script>
    $('.tablas').DataTable({
        "language": {

            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "&rsaquo;",
                "sPrevious": "&lsaquo;"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }

        }

    });
</script>
</script>