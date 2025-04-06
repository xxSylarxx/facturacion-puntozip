 <?php

  use Controladores\ControladorEmpresa;

  $emisor = ControladorEmpresa::ctrEmisor();

  ?>

 <style>
  .navbar-nav>.guiasIntegracion-notifications-menu>.dropdown-menu {
   width: 300px;
  }
   .navbar-nav>.guiasIntegracion-notifications-menu>.dropdown-menu>li.header {
     border-top-left-radius: 4px;
     border-top-right-radius: 4px;
     border-bottom-right-radius: 0;
     border-bottom-left-radius: 0;
     background-color: #ffffff;
     padding: 7px 10px;
     border-bottom: 1px solid #f4f4f4;
     color: #444444;
     font-size: 14px;
   }

   .navbar-nav>.guiasIntegracion-notifications-menu>.dropdown-menu>li .menu {
     max-height: 200px;
     margin: 0;
     padding: 0;
     list-style: none;
     overflow-x: hidden;
   }

   .navbar-nav>.guiasIntegracion-notifications-menu>.dropdown-menu>li .menu>li>a:hover {
     background: #f4f4f4;
     text-decoration: none;
   }

   .nav .guiasIntegracion-notifications-menu.open>a,
   .nav .guiasIntegracion-notifications-menu.open>a:focus,
   .nav .guiasIntegracion-notifications-menu.open>a:hover {
     background-color: #0c75ff !important;
     z-index: 0;
   }

   .label-guiasIntegracion-recepcion {
     background-color: #f39c12 !important;
   }

   .guiasIntegracion-item {
    padding: 1em;
   }
 </style>

 <header class="main-header cabecera-m">

   <!-- Logo -->
   <a href="inicio" class="logo">
     <!-- mini logo for sidebar mini 50x50 pixels -->
     <!-- <span class="logo-mini"><b>B</b>MM</span> -->
     <span class="logo-mini"><img src="vistas/img/logo/<?php echo $emisor['logo'] ?>" alt="" width="50px"></span>
     <!-- logo for regular state and mobile devices -->
     <span class="logo-lg"><b><?php echo $emisor['nombre_comercial'] ?></b></span>
   </a>
   <!-- Header Navbar: style can be found in header.less -->
   <nav class="navbar navbar-static-top cabecera-m">
     <!-- Sidebar toggle button-->
     <button class="btn btn-success  btn-menup" data-toggle="push-menu" role="button">
       <i class="fas fa-align-justify fa-lg"></i>
     </button>
     <button class="btn-modo-sistema">PRUEBAS</button>

     <button class="btn btn-danger btn-menup dia" onclick="changep(1)" idp="claro"></button>
     <button class="btn btn-danger btn-menup noche" onclick="changep(2)" idp="oscuro"></button>

     <button class="btn btn-primary" id="tipocambio"></button>
     <div class="navbar-custom-menu">
       <ul class="nav navbar-nav">

         <!--  notification ===================== -->
         <li class="dropdown notifications-menu">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <i class="far fa-bell"></i>
             <span class="label label-warning no-enviados"></span>
           </a>
           <ul class="dropdown-menu">
             <li class="header no-enviados-text"></li>
             <li>
               <!-- inner menu: contains the actual data -->
               <ul class="menu">
                 <li>
                   <a href="#">
                     <i class="fa fa-users text-aqua "></i>
                     <span class="no-enviados-items"></span>
                   </a>
                 </li>
               </ul>
             </li>
             <li class="footer">
               <form class="form" action="ventas" method="post">
                 <input type="hidden" id="noenviados" name="noenviados" value="no">

                 <button class="btn-co-no-enviados"><i class="fas fa-receipt"> </i> <span> VER TODOS</span></button>

               </form>

               <!-- <a href="ventas">Ver todos</a> -->
             </li>
           </ul>
         </li>
         <!-- fin notification ===================== -->


         <!-- ==================================================== -->
         <li class="dropdown guiasIntegracion-notifications-menu">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <i class="fa-solid fa-file-import"></i>
             <span class="label guiasIntegracion-label-warning guiasIntegracion-recepcion label-guiasIntegracion-recepcion">
             </span>
           </a>
           <ul class="dropdown-menu guiasIntegracion-dropdown-menu">
             <li class="header guiasIntegracion-no-enviados-text">
               Guías por emitir
             </li>
             <li>
               <ul class="menu guiasIntegracion-menu">
                 <!-- Aquí se cargarán las guías mediante AJAX -->
               </ul>
             </li>
           </ul>
         </li>
         <!-- ==================================================== -->



         <!-- User Account: style can be found in dropdown.less -->
         <li class="dropdown user user-menu">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <!-- <img src="vistas/img/man_default.svg" class="user-image" alt="User Image"> -->
             <?php if ($_SESSION['foto'] != '') {
                echo '<img src="' . $_SESSION['foto'] . '" class="user-image" alt="User Image">';
              } else {
                echo '<img src="vistas/img/man_default.svg" class="user-image" alt="User Image">';
              }
              ?>
             <span class="hidden-xs">Usuario</span>
           </a>
           <ul class="dropdown-menu menu-user" style="width: 200px; color:black;">

             <!-- Menu Body -->
             <li class="">
               <a href="usuarios">
                 <i class="fas fa-user fa-lg" style="color:royalblue"> </i> <span class="mg-menu">Mi perfil</span>

               </a>
             </li>
             <li class="">
               <a href="empresa">
                 <i class="fas fa-cog  fa-lg" style="color:royalblue"> </i> <span class="mg-menu">Configurar empresa</span>

               </a>
             </li>
             <!-- Menu Footer-->
             <li class="">
               <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->

               <a href="salir" class="">
                 <i class="fas fa-sign-out-alt fa-lg" style="color:tomato"></i><span class="mg-menu"> Salir </span>
               </a>

             </li>
           </ul>
         </li>
         <!-- Control Sidebar Toggle Button -->
         <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
       </ul>
     </div>
   </nav>

   <script src="https://kit.fontawesome.com/760e3cbc52.js" crossorigin="anonymous"></script>
 </header>