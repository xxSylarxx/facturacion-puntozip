<!-- <div id="back"></div> -->
<?php

use Controladores\ControladorUsuarios;
use Controladores\ControladorEmpresa;
use Conect\Conexion;
//EN CASO NO EXISTA UNA BSE DE DATOS IMPORTANTE
$stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'sucursales'");
$stmt->execute();
if ($stmt->rowCount() == 0) {
  // include "modulos/inicio.php";
  echo '<button id="actualizarBaseD" class="btn btn-primary bt btn-radius pull-center" style="font-size:1em !important;"><i class="fas fa-database fa-lg"></i> ACTUALIZAR BASE DE DATOS</button>';
} else {
  $item = 'rol';
  $valor = 'principal';
  $emisor = ControladorEmpresa::ctrEmisorConexion($item, $valor);
  $respuesta = ControladorUsuarios::ctrConn();
  if ($emisor['conexion'] == 's' && strlen($emisor['clavePublica']) < 15 || strlen($emisor['clavePrivada']) < 15 && ($emisor['clavePublica'] == '' || $emisor['clavePublica'] == null)) {
    @$change = ControladorEmpresa::ctrCambiarSeguridad();
  }
?>
  <div class="log-cont">

    <div class="login-box">
      <div class="button-container">
        <a  class="btn1" id="button1">PUNTOZIP 1</a>
        <a  class="btn2" id="button2">PUNTOZIP 2</a>
      </div>

      <!-- <div class="login-logo">
   <img src="vistas/img/plantilla/logo-blanco-bloque.png" class="img-responsive" alt="" style="padding: 30px 100px 0px 100px">
  </div> -->
      <!-- /.login-logo -->
      <div class="login-box-body">

        <div class="logo-empresa">
          <?php $rand = rand(22, 99999); ?>
          <img src="vistas/img/logo/<?php echo $emisor['logo']; ?>?n='<?php echo $rand; ?>" alt="">
        </div>

        <p class="login-box-msg" style="display: none"></p>

        <form method="post" class="login-u" id="form-login">
          <input type="hidden" id="conectado" name="conectado" value="<?php echo $respuesta; ?>">
          <input type="hidden" id="cpublica" value="<?php echo @$emisor['clavePublica']; ?>">
          <div class="form-group has-feedback">
            <span class="fas fa-user form-control-icon ico1"></span>
            <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" id="ingUsuario" autocomplete="off">
          </div>
          <div class="form-group has-feedback" style="margin:0px !important">
            <span class="fas fa-key form-control-icon ico2"></span>
            <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" id="ingPassword">
            <!-- <div class="g-recaptcha" id="idrecaptcha" data-sitekey="6Lf4WrAZAAAAANIZPtMaCIhXbbgFoVnfNs_u8Ryo"></div> -->
          </div>

          <div class="row">


            <!-- /.col -->
            <div class="content-fluid">
              <button type="button" class="btn-flat" id="logUser">Ingresar al sistema <i class="fas fa-angle-double-right fa-lg"></i></button>
            </div>
            <!-- /.col -->
          </div>

          <?php

          // $login = new ControladorUsuarios();
          // $login->ctrIngresoUsuario();

          ?>
          <div id="resultLogin" style="display: none;"></div>
        </form>
        <!-- <div class="social-auth-links text-center">
      <p></p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i>Ingresa usando 
        Facebook</a>
      
    </div> -->
        <!-- /.social-auth-links -->
        <!-- <div class="link-recuperar">

    <a href="#">¿Olvidaste tu contraseña?</a><br>
    ¿No tienes cuenta?<a href="#" class="text-center"> Regístrate</a>

    </div>     -->

        <div class="verifica-sunat"><img src="vistas/img/verificacion.png" alt=""></div>
      </div>
      <!-- /.login-box-body -->
    </div>
  </div>
  <div id="fondP">
    <div class="fnd"></div>
  </div>
  <?php if ($emisor['conexion'] == 's') { ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $emisor['clavePublica']; ?>"></script>
<?php }
} ?>