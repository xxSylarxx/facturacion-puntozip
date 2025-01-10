<?php
session_start();

use Controladores\ControladorEmpresa;
use Controladores\ControladorUsuarios;
use Controladores\ControladorCaja;
use Conect\Conexion;
use Controladores\ControladorSucursal;

unset($_SESSION['carrito']);
unset($_SESSION['carritoC']);
unset($_SESSION['carritoG']);
//EN CASO NO EXISTA UNA BSE DE DATOS IMPORTANTE



$valor_sucursal = '';
if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
  $item = 'id';
  $valor = $_SESSION['id_sucursal'];
  $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
  $valor_sucursal = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  // if ($sucursal['activo'] == 'n') {
  //   echo "<h3>USTED NO PERTENECE A NINGUNA SUCURSAL/ALMACÉN, COMUNÍQUESE CON EL ADMINISTRADOR PARA INTEGRARLE A UNA</h3>";
  //   exit();
  // }
  $emisor = ControladorEmpresa::ctrEmisor();
}
$tiem = time();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?php
          $title = (isset($emisor['nombre_comercial'])) ? $emisor['nombre_comercial'] : 'SISTEMA DE FACTURACIÓN';
          echo $title;
          ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" sizes="76x76" href="vistas/img/logo/<?php $logo = (isset($emisor['logo'])) ? $emisor['logo'] : 'logo.png';
                                                                        echo $logo;
                                                                        ?>">
  <!-- Compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link href="vistas/pack/bower_components/toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

  <link rel="stylesheet" href="vistas/pack/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="vistas/pack/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/pack/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="vistas/pack/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="vistas/pack/bower_components/select2/dist/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vistas/pack/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="vistas/pack/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">
  <!-- DATERANGEPICKER -->

  <!-- DATEPICKER -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- DASHBOPARD -->
  <link rel="stylesheet" href="vistas/pack/bower_components/morris.js/morris.css">

  <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="vistas/pack/bower_components/fontawesome-free/css/all.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet"> -->
 
  <!-- <link rel="stylesheet" href="vistas/pack/dist/css/skins/_all-skins.min.css"> -->
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="vistas/pack/plugins/iCheck/all.css">
  <!-- Latest compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->


  <link rel="stylesheet" href="vistas/css/<?php $plantilla = (isset($emisor['plantilla'])) ? $emisor['plantilla'] : 'plantilla.css';
                                          echo $plantilla;
                                          ?>" id="css">
  <link rel="stylesheet" href="vistas/css/carrito.css?q=<?php echo $tiem; ?>">
  <link rel="stylesheet" href="vistas/css/form.css?q=<?php echo $tiem; ?>">
  <link rel="stylesheet" href="vistas/css/pos.css?q=<?php echo $tiem; ?>">

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- daterangepicer -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap-daterangepicker/daterangepicker.css">

  <!-- jQuery 3 -->
  <script src="vistas/pack/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="vistas/pack/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script src="vistas/pack/bower_components/select2/dist/js/select2.full.min.js"></script>
  <script src="vistas/pack/bower_components/toggle/js/bootstrap-toggle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="vistas/pack/dist/js/adminlte.js"></script>
  <!-- DataTables -->
  <script src="vistas/pack/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script>

  <!-- SlimScroll -->
  <script src="vistas/pack/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="vistas/pack/bower_components/fastclick/lib/fastclick.js"></script>

  <!-- AdminLTE for demo purposes -->
  <!-- <script src="vistas/pack/dist/js/demo.js"></script> -->
  <!-- iCheck 1.0.1 -->
  <script src="vistas/pack/plugins/iCheck/icheck.min.js"></script>

  <!-- sweet alert -->
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
  <script src="vistas/pack/plugins/sweetalert/sweetalert2.js"></script>

  <!-- Compiled and minified JavaScript -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> -->
  <!-- InputMask -->
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.extensions.js"></script>

  <!-- Latest compiled and minified JavaScript -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->

  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->



  <!-- daterangepicker -->
  <script src="vistas/pack/bower_components/moment/min/moment.min.js"></script>
  <script src="vistas/pack/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- DTEPICKER -->
  <script src="vistas/pack/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="vistas/pack/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>

  <!-- Morris.js charts -->
  <script src="vistas/pack/bower_components/raphael/raphael.min.js"></script>
  <script src="vistas/pack/bower_components/morris.js/morris.min.js"></script>


  <script src="vistas/pack/bower_components/chart.js/Chart.min.js"></script>
  <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script> -->

</head>

<body class="hold-transition skin-blue sidebar-mini">


  <?php

  if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
    // $tiempoSesion = 20 * 60000;



    //      </script>";
    echo '<div class="reload-all" id="reload-all"></div>';
    echo '<div class="box super-contenedor">';
    
    
    //EN CASO NO EXISTA UNA BSE DE DATOS IMPORTANTE
    $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'roles'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
      // include "modulos/inicio.php";
      echo '<button id="actualizarBaseD" class="btn btn-primary bt btn-radius pull-center" style="font-size:1em !important;"><i class="fas fa-database fa-lg"></i> ACTUALIZAR BASE DE DATOS</button>';
    } else {

      /*=============================================
    CABEZOTE
    =============================================*/

      include "modulos/header.php";

      /*=============================================
    MENU
    =============================================*/

      include_once "modulos/menu.php";

      /*=============================================
    CONTENIDO
    =============================================*/

      if (isset($_GET["ruta"])) {
        $item = 'rol';
        $valor = @$_SESSION['perfil'];
        $roles = ControladorUsuarios::ctrMostrarRoles($item, $valor);

        $item = 'id_rol';
        $valor = $roles['id'];
        $valor2 = $_GET['ruta'];
        $accesos = ControladorUsuarios::ctrMostrarAccesos($item, $valor,  $valor2);


        // var_dump($v);
        if (
          (@$accesos['activo'] == 's' && $_GET["ruta"] == @$accesos['linkacceso'])  ||
          $_GET["ruta"] == 'salir'

        ) {

          include "modulos/" . $_GET["ruta"] . ".php";
        } else {
          if ($_GET["ruta"] == @$accesos['linkacceso']) {
            include "modulos/acceso-restringido.php";
          } else {
            include "modulos/404.php";
          }
        }
      } else {

        include "modulos/inicio.php";
      }

      /*=============================================
    FOOTER
    =============================================*/



      include "modulos/footer.php";
    }
   
    echo '<div class="resultado-envios-sunat"></div>';
    echo '</div>';
  } else {

    include "modulos/login.php";
  }
  $tiempo = time();
  ?>


  <div class="connection"></div>
  <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
  <input type="hidden" class="" id="fecha" name="fecha" value="<?php echo date("Y-m-d") ?>">
  <!-- End custom js for this page-->
  <script src="vistas/js/plantilla.js"></script>
  <script src="vistas/js/usuarios.js"></script>
  <script src="vistas/js/categorias.js"></script>
  <script src="vistas/js/productos.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/proveedores.js"></script>
  <script src="vistas/js/clientes.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/sunat.js"></script>
  <script src="vistas/js/ventas.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/nota-credito.js"></script>
  <script src="vistas/js/nota-debito.js"></script>
  <script src="vistas/js/envio-sunat.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/resumen-diario.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/reportes.js"></script>
  <script src="vistas/js/empresa.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/descuentos.js"></script>
  <script src="vistas/js/compras.js"></script>
  <script src="vistas/js/proveedores.js"></script>
  <script src="vistas/js/conductores.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/guia.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/cuotas.js"></script>
  <script src="vistas/js/cotizacion.js"></script>
  <script src="vistas/js/inventario.js"></script>
  <script src="vistas/js/gastos.js"></script>
  <script src="vistas/js/caja.js"></script>
  <script src="vistas/js/pos.js"></script>
  <script src="vistas/js/sucursal.js"></script>
  <script src="vistas/js/bancos.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Invocamos cada 5 segundos ;)
      const milisegundos = 60 * 5000;
      setInterval(function() {
        // No esperamos la respuesta de la petición porque no nos importa
        fetch("vistas/modulos/sesion.php");
      }, milisegundos);
    });
    $(document).ready(function() {
      $(".reload-all").hide();
     

    })

    function change(a) {
      var css = document.getElementById("css");
      if (a == 1)
        css.setAttribute("href", "vistas/css/plantilla.css");
      if (a == 2)
        css.setAttribute("href", "vistas/css/plantilla2.css");
    }
    loadGuiasR(1)

    $(document).on('click', ".reload-all", function() {
      $(".reload-all").hide();
    })
  </script>

  <?php
  $item = 'id_usuario';
  $valor = @$_SESSION['id'];
  $arqueocajas = ControladorCaja::ctrMostrarArqueoCajasid($item, $valor);
  if ($arqueocajas) {
    $valorapertura = 1;
  } else {
    $valorapertura = 0;
  }
  ?>
  <input type="hidden" name="abrirCaja" id="abrirCaja" value="<?php echo $emisor['caja'] ?>">
  <input type="hidden" name="cajaAC" id="cajaAC" value="<?php echo $valorapertura ?>">
  <input type="hidden" name="valor_sucursal" id="valor_sucursal" value="<?php echo $valor_sucursal ?>">
</body>

</html>