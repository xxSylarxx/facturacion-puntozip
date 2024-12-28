function checketSunat() {
  $(".radio-envio #firmar").prop("checked", false);
  $(".radio-envio #enviar").prop("checked", true);
}
checketSunat();

function loadModo() {
  let datos = { pp: "pp" };

  $.ajax({
    method: "POST",
    url: "ajax/empresa.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      if (respuesta == "s") {
        $("#sim").addClass("prod");
        $("#sim").html("Producción");
        $("#nom").html("||");
        $("#sim").removeClass("alterno");
        $("#nom").addClass("alterno");
        $(".btn-modo-sistema").html("Producción");
        $(".btn-modo-sistema").removeClass("btnprueba");
        $(".btn-modo-sistema").addClass("btnprod");
        // AQUÍ CAMBIO============]
        // $(".mno").val("");

        $(".modo-contenedor #si").prop("checked", true);
      } else {
        $("#nom").addClass("prueba");
        $("#nom").html("Pruebas");
        $("#sim").html("||");
        $("#sim").addClass("alterno");
        $("#nom").removeClass("alterno");
        $(".btn-modo-sistema").html("Pruebas");
        $(".btn-modo-sistema").removeClass("btnprod");
        $(".btn-modo-sistema").addClass("btnprueba");
      }
    },
  });
}
loadModo();

$(".modo").change(function () {
  let id_sucursal = $("#id_sucursal").val();
  let modo = $(".modo:checked").val();
  let datos = { id_sucursal: id_sucursal, modo: modo };

  if (modo == "s") {
    $(".modo-contenedor #no").prop("checked", true);
    Swal.fire({
      title: "PONTE EN CONTACTO CON EL ADMINISTRADOR PARA LA CONFIGURACIÓN.",
      text: "Debemos configurar sus datos de su empresa y su certificado digital",
      icon: "info",
      html: `<b>Al  pasar a producción sus pruebas se harán en un sistema externo!`,
      footer: "¡Recuerda que debe de estar bien configurado para producción!",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, contactar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "POST",
          url: "ajax/empresa.ajax.php",
          data: datos,
          beforeSend: function () {},
          success: function (respuesta) {
            if (respuesta == "ok") {
            }
          },
        });
        Swal.fire({
          title: "",
          text: "",
          showConfirmButton: false,
          html: `<a href="https://apifacturacion.com" target="_blank">Api Facturación</a>`,
          footer:
            '<a href="https://apifacturacion.com" target="_blank">Api Facturación</a>',
        });
        $("#sim").addClass("prod");
        $("#sim").html("Producción");
        $("#nom").html("||");
        $("#sim").removeClass("alterno");
        $("#nom").addClass("alterno");
        $(".btn-modo-sistema").html("Producción");
        $(".btn-modo-sistema").removeClass("btnprueba");
        $(".btn-modo-sistema").addClass("btnprod");
        loadModo();
      }
    });
  }

  if (modo == "n") {
    $("#nom").addClass("prueba");
    $("#nom").html("Pruebas");
    $("#sim").html("||");
    $("#sim").addClass("alterno");
    $("#nom").removeClass("alterno");
    $(".btn-modo-sistema").html("Pruebas");
    $(".btn-modo-sistema").removeClass("btnprod");
    $(".btn-modo-sistema").addClass("btnprueba");
    $.ajax({
      method: "POST",
      url: "ajax/empresa.ajax.php",
      data: datos,
      beforeSend: function () {},
      success: function (respuesta) {
        //    //(respuesta)
        if (respuesta == "ok") {
        }
      },
    });
  }
  if (modo == "") {
    $(".modo-contenedor #si").prop("checked", true);
    Swal.fire({
      title: "Se le informa que ya está en producción",
      icon: "warning",
      html: `<p>por ese motivo se le redireccionará a un sistema externo para sus pruebas</p>
                <b>COPIE ESTOS DATOS 
                <br>Usuarios: demo <br>Contraseña: pruebas`,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, redireccionar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "https://sistem.apifacturacion.com";
      }
    });
  }
});

$("#formConsulta").on("change", "#tipocomprobante", function () {
  let tipo = $(this).val();

  if (tipo == "01") {
    $("#seriec").val("F001");
  }
  if (tipo == "03") {
    $("#seriec").val("B001");
  }
  if (tipo == "07") {
    $("#seriec").val("FN01");
  }
  if (tipo == "08") {
    $("#seriec").val("FD01");
  }
});
function modoIgv() {
  let modo_igv = $("#empresa_igv").val();

  if (modo_igv == "n") {
    $("#tipo_afectacion").val(20);
    $("#afectoigv").val("n");
  }
  if (modo_igv == "s") {
    $("#tipo_afectacion").val(10);
    $("#afectoigv").val("s");
  }
}
modoIgv();

// BUSCAR RUC EMPRESA SUNAT
$(".ruc-empresa").on("change", function () {
  let rucEmpresa = $(this).val();

  let datos = { rucEmpresa: rucEmpresa };
  $.ajax({
    method: "POST",
    url: "ajax/empresa.ajax.php",
    data: datos,

    dataType: "json",
    beforeSend: function () {
      $("#reloadC").show(5).html("<img src='vistas/img/reload.svg'> ");
      //document.getElementById('reloadC').style.visibility = "visible";
    },
    success: function (respuesta) {
      // console.log(respuesta);
      if (respuesta != "error") {
        $("#reloadC").hide();
        //   var json = eval(respuesta);
        $("#ruc").val(respuesta["ruc"]);
        $("#razon_sociale").val(respuesta["razon_social"]);
        //   $('#direccion').val(respuesta['direccion']);
        $("#direccione").val(
          respuesta["direccion"] +
            " " +
            respuesta["departamento"] +
            " - " +
            respuesta["provincia"] +
            " - " +
            respuesta["distrito"]
        );
        $("#ubigeoe").val(respuesta["ubigeo"]);
        $("#estado").val(respuesta["estado"]);
        $("#condicion").val(respuesta["condicion"]);
        $("#departamento").val(respuesta["departamento"]);
        $("#provincia").val(respuesta["provincia"]);
        $("#distrito").val(respuesta["distrito"]);
        // document.getElementById('reloadC').style.visibility = "hidden";

        //   if(respuesta['estado'] == 'ACTIVO'){

        //     $('.resultadoCliente').html(respuesta['estado']).addClass('activo');
        //     $('.resultadoCliente').removeClass('noactivo');
        //     }else{

        //   $('.resultadoCliente').html(respuesta['estado']).addClass('noactivo');
        //     $('.resultadoCliente').removeClass('activo');
        //     }
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "El RUC no se encuentra",
          showConfirmButton: false,
          timer: 2500,
        });
      }
    },
  });
});
// SUBIENDO EL LOGO
$(".nuevoLogo").change(function () {
  let imagen = this.files[0];
  let nombreimagen = imagen["name"];
  let pos = nombreimagen.lastIndexOf(".");
  let ext = nombreimagen.substring(pos);
  let nombre_nuevo = "logo" + ext;

  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".nuevoLogo").val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen debe ser jpeg o png!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevoLogo").val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen no debe pesar más de 2mb!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
  } else {
    let datosImagen = new FileReader();
    datosImagen.readAsDataURL(imagen);
    $(datosImagen).on("load", function (event) {
      let rutaImagen = event.target.result;
      $(".previsualizar").attr("src", rutaImagen);

      let datos = $("#formLogo").serialize();
      var formData = new FormData($("#formLogo")[0]);

      $.ajax({
        method: "POST",
        url: "ajax/empresa.ajax.php",
        data: (datos, formData),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          $(".reload-all").hide();
          if (respuesta) {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Logo cambiado corréctamente",
              showConfirmButton: false,
              timer: 1500,
            });
            $("#logoActual").val(nombre_nuevo);
            $("#logoBD").val(nombre_nuevo);
          }
        },
      });
    });
  }
});

// CAMBIAR LOGO ==============
$("#editarLogo").change(function (e) {
  e.preventDefault();
});

// CARGAR TLS O SSL=====================
function loadTipoSeguridadEmail() {
  let seguridad = $(".seguridad-envio").attr("idSeguridad");
  let tipoEnvio = $(".tipo-envio").attr("idtipoEnvio");
  let tipoimpresion = $(".tipoimpresion").attr("idTipoImpresion");
  if (seguridad == "tls") {
    $(".seguridad-envio").val("tls");
  }
  if (seguridad == "ssl") {
    $(".seguridad-envio").val("ssl");
  }
  if (tipoEnvio == "smtp") {
    $(".tipo-envio").val("smtp");
  }
  if (tipoEnvio == "mail") {
    $(".tipo-envio").val("mail");
  }
  if (tipoimpresion == "normal") {
    $(".tipoimpresion").val("normal");
  }
  if (tipoimpresion == "rapido") {
    $(".tipoimpresion").val("rapido");
  }
}
loadTipoSeguridadEmail();
// CARGAR SEGURIDAD RECAPTCHA=====================
function loadRecaptcha() {
  let seguridad = $(".rseguridad").attr("idSeguridad");
  if (seguridad == "s") {
    $(".rseguridad").val("s");
  }
  if (seguridad == "n") {
    $(".rseguridad").val("n");
  }
}
loadRecaptcha();

function loadCajaSN() {
  let caja = $(".caja-ac").attr("cajaid");
  if (caja == "s") {
    $(".caja-ac").val("s");
  }
  if (caja == "n") {
    $(".caja-ac").val("n");
  }
}
function loadAlmacen() {
  let almacen = $("#multialmacen").attr("idalmacen");
  if (almacen == "s") {
    $("#multialmacen").val("s");
  }
  if (almacen == "n") {
    $("#multialmacen").val("n");
  }
}
loadCajaSN();
loadAlmacen();
// ELIMINAR LOGO ===============
$(".eliminar-logo").on("click", function (e) {
  e.preventDefault();
  let idEmpresa = $(this).attr("idEmpresa");
  let logo = $("#logoActual").val();

  let datos = { idEmpresa: idEmpresa, logo: logo };
  Swal.fire({
    title: "¿Estás seguro de eliminar el logo?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        method: "POST",
        url: "ajax/empresa.ajax.php",
        data: datos,

        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          $(".reload-all").hide();
          if (respuesta == "ok") {
            let ruta = "vistas/img/logo/default/logo.svg";
            $(".previsualizar").attr("src", ruta);
            $("#logoActual").val("");
            $("#logoBD").val("");
          }
        },
      });
    }
  });
});

// CAMBIAR PLANTILLA=============
function changep(a) {
  if (a == 1) {
    $("#css").attr("href", "vistas/css/plantilla.css");
    let plantilla = "plantilla.css";
    let datos = { plantilla: plantilla };
    $.ajax({
      method: "POST",
      url: "ajax/empresa.ajax.php",
      data: datos,

      beforeSend: function () {
        $(".reload-all")
          .fadeIn(50)
          .html("<img src='vistas/img/reload.svg' width='80px'> ");
      },
      success: function (respuesta) {
        $(".reload-all").hide();
        if (respuesta == "ok") {
        } else {
        }
      },
    });
  }
  if (a == 2) {
    $("#css").attr("href", "vistas/css/plantilla2.css");
    let plantilla = "plantilla2.css";

    let datos = { plantilla: plantilla };
    $.ajax({
      method: "POST",
      url: "ajax/empresa.ajax.php",
      data: datos,

      beforeSend: function () {
        $(".reload-all")
          .fadeIn(50)
          .html("<img src='vistas/img/reload.svg' width='80px'> ");
      },
      success: function (respuesta) {
        $(".reload-all").hide();
        if (respuesta == "ok") {
        } else {
        }
      },
    });
  }
}

$(".printsave").on("click", function () {
  $.ajax({
    url: "vistas/print/printA.php",
    contentType: "application/pdf",

    beforeSend: function () {},
    success: function (respuesta) {
      $(".printerhere").html(respuesta);
    },
  });
});

$(".bienes-selva").on("change", "#bienesSelva", function () {
  let bienesSelva = $("input[name=bienesSelva]:checked").val();
  let id_empresa = $("#id_empresa").val();

  if (bienesSelva != "s") {
    bienesSelva = "n";
  }
  let datos = { bienesSelva: bienesSelva, id_empresa: id_empresa };

  $.ajax({
    method: "POST",
    url: "ajax/empresa.ajax.php",
    data: datos,
    success: function (data) {},
  });
});

$(".servicios-selva").on("change", "#serviciosSelva", function () {
  let serviciosSelva = $("input[name=serviciosSelva]:checked").val();
  let id_empresa = $("#id_empresa").val();

  if (serviciosSelva != "s") {
    serviciosSelva = "n";
  }
  let datos = { serviciosSelva: serviciosSelva, id_empresa: id_empresa };

  $.ajax({
    method: "POST",
    url: "ajax/empresa.ajax.php",
    data: datos,
    success: function (data) {},
  });
});

$("#actualizarBaseD").click(function (e) {
  e.preventDefault();
  let datos = { updateBd: "updateBd" };

  $.ajax({
    method: "POST",
    url: "ajax/bd.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      if (data != "ok") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Actualizaciones",
          html: `<div style="color: green;">${data}</div>`,
          showConfirmButton: false,
          timer: 3500,
        });
        $(".reload-all")
          .hide(500)
          .delay(3500)
          .show(500, function () {
            window.location = "inicio";
          });
      } else {
        $(".reload-all").hide();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "La base de datos se encuentra actualizada",
          showConfirmButton: false,
          timer: 4500,
        });
      }
    },
  });
});

$(document).on("click", "#backup", function (e) {
  e.preventDefault();
  $.ajax({
    method: "POST",
    url: "ajax/bk/bk.php",
    // data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      console.log(data);
      $(".reload-all").fadeOut(50);
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        // width: 600,
        // padding: '3em',
        showConfirmButton: false,
        timer: 20000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Toast.fire({
        icon: "success",
        title: `<h5><i class="fas fa-database"></i> COPIA DE SEGURIDAD</h5>`,
        html: `<div style="font-size: 1.5em; color: #2B5DD2; overflow-y: auto;">  ${data}</div`,
      });
    },
  });
});
function backup() {
  $.ajax({
    method: "POST",
    url: "ajax/bk/bk.php",
    // data: datos,
    beforeSend: function () {},
    success: function (data) {
      // console.log(data);
    },
  });
}
$(document).on("click", "#formSucursal .select2-container", function(){
  $('body').removeClass('modal-open');
  $('input[type=search]').focus();
 
})