<?php
session_start();
require_once "../../vendor/autoload.php";

use Controladores\ControladorCaja;

?>

<!-- table-bordered table-striped  -->
<table class="table  dt-responsive tablas tbl-t" width="100%">

    <thead>
        <tr>
            <th style="width:10px;">#</th>
            <th>Nombre Caja</th>
            <th>Número</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $item = null;
        $valor = null;
        $cajas = ControladorCaja::ctrMostrarCajas($item, $valor);

        foreach ($cajas as $key => $value) :
            // if($value['activo'] == 1):
        ?>

            <tr>
                <td class="numeracion"><?php echo ++$key; ?></td>
                <td class="text-uppercase"><?php echo $value['nombre']; ?></td>
                <td class="text-uppercase"><?php echo $value['numero_caja']; ?></td>
                <td>
                    <?php 
                    if ($value['activo']== 1){
                        echo '<button class="btn btn-success btn-xs">Activo</button>';
                    }else{
                        echo '<button class="btn btn-danger btn-xs">Inactivo</button>';
                    }
                    ?>
                </td>
                <td>
                    <div class="btn-group">

                        <button class="btn btn-warning btnEditarCaja" idCaja=" <?php echo $value['id']; ?>" data-toggle="modal" data-target="#modalEditarCaja"><i class="fas fa-user-edit"></i></button>

                        <?php if ($_SESSION['perfil'] == 'Administrador') : ?>
                            <button class="btn btn-danger btnEliminarCaja" idCaja="<?php echo $value['id']; ?>"><i class="fas fa-trash-alt"></i></button>

                        <?php endif; ?>

                    </div>


                </td>

            </tr>

        <?php
        // endif;
        endforeach;

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