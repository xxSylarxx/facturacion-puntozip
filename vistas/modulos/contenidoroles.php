<?php
session_start();
require_once "../../vendor/autoload.php";

use Controladores\ControladorUsuarios;

$item = 'rol';
$valor = $_SESSION['perfil'];
$roles = ControladorUsuarios::ctrMostrarRoles($item, $valor);
?>
<table class="table  dt-responsive tablas unidad-me" width="100%">

  <thead>
    <tr>
      <th style="width:10px;">#</th>
      <th>ROL DE USUARIO</th>
      <th>ACCIONES</th>
    </tr>
  </thead class="contenido-roles">


  <tbody>

    <?php
    $item = null;
    $valor = null;
    $rolesUsuario = ControladorUsuarios::ctrMostrarRoles($item, $valor);



    foreach ($rolesUsuario as $k => $v) {
      $item = 'id_rol';
      $valor = $v['id'];

      $accesos = ControladorUsuarios::ctrMostrarAccesosid($item, $valor);
      foreach ($accesos as $i => $a) {
        // echo $a['id_rol'];
      }
      // var_dump($accesos);

      echo ' <tr>
                        <td>' . ++$k . '</td>
                        <td>' . $v['rol'] . '</td>
                        <td>';
      if ($v['id'] == @$a['id_rol']) {

        if ($_SESSION['perfil'] == 'Administrador' && $v['rol'] == 'Administrador') {
          echo '<button class="btn btn-warning btn-editar-accesos" idRol="' . $v['id'] . '" data-toggle="modal" data-target="#modalAgregarEditarRol">Editar accesos</button>';
        }

        if ($v['rol'] == 'Administrador') {
        } else {
          echo '<button class="btn btn-warning btn-editar-accesos" idRol="' . $v['id'] . '" data-toggle="modal" data-target="#modalAgregarEditarRol">Editar accesos</button>';
          echo '<button class="btn btn-danger btn-eliminar-rol" idRole="' . $v['id'] . '" >Eliminar</button>';
        }
      } else {
        echo '<button class="btn btn-primary btn-agregar-accesos" idRol="' . $v['id'] . '" >Agregar accesos</button>';
        if ($v['rol'] == 'Administrador') {
        } else {
          echo '<button class="btn btn-danger btn-eliminar-rol" idRole="' . $v['id'] . '">Eliminar</button>';
        }
      }
      echo '</td>
                       
                    </tr>';
    };
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