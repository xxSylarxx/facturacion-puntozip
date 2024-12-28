$(".resultadoCliente").hide();
$(".resultadoSerie").hide();
$("#rucActivo").hide();

// SOLO INGRESAR NUMEROS CAMPO RUC-DNI

$(document).on("keyup", "#nuevonumdoc", function () {
  var ruc = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(ruc)) {
    //dni = dni.substr(0,(dni.length -1));
    ruc = ruc.replace(/[^0-9]/g, "");
    $("#nuevonumdoc").val(ruc);
  }
});
$(document).on("change", "#nuevonumdoc", function () {
  var ruc = $(this).val();

  let rucCliente = $(this).val();
  var tipoDoc = $("#nuevotipodoc").val();
  let datos = { rucCliente: rucCliente, tipoDoc: tipoDoc };
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      // console.log(respuesta);
      if (respuesta != "error") {
        if (respuesta["ruc"].length > 8) {
          $("#nuevonumdoc").val(respuesta["ruc"]);
          $("#nuevoEmail").val(respuesta["email"]);
          $("#nuevoTelefono").val(respuesta["telefono"]);
          $("#nuevoDireccion").val(respuesta["direccion"]);
          $("#nuevonombrerazon").val(respuesta["razon_social"]);
        }
        if (tipoDoc == 1) {
          $("#nuevonumdoc").val(respuesta["ruc"]);
          $("#nuevoEmail").val(respuesta["email"]);
          $("#nuevoTelefono").val(respuesta["telefono"]);
          $("#nuevoDireccion").val(respuesta["direccion"]);
          $("#nuevonombrerazon").val(respuesta["razon_social"]);
        }
      }
    },
  });
});
// SOLO INGRESAR NUMEROS CAMPO RUC-DNI

$(document).on("keyup", "#editarnumdoc", function () {
  var ruc = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(ruc)) {
    //dni = dni.substr(0,(dni.length -1));
    ruc = ruc.replace(/[^0-9]/g, "");
    $("#editarnumdoc").val(ruc);
  }
});
// AGREGAR CLIENTE
$(document).on("click", ".btn-agregar-cliente", function (e) {
  e.preventDefault();
  let datos = $("#frmAgregarClientes").serialize();
  console.log(datos);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      if (respuesta == "ok") {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 7000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
            loadInventarios(1);
            loadKardex(1);
            //   $("#modalAgregarAjusteInventario").modal("hide");
            //   $("#idproducto").val("");
            //   $("#cantidadModificar").val("");
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡¡Cliente ingresado con éxito!!!</h4>`,
          html: ``,
        });
        $("#frmAgregarClientes input").val("");
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.href);

          $("#modalAgregarCliente").on("hidden", function () {
            loadClientes(1);
          });
        }
      } else {
        $(".resultadoCrearCliente").html(respuesta);
      }
    },
  });
});

$(document).on("click", ".btn-editar-cliente", function (e) {
  e.preventDefault();
  var activo = $("#active").attr("idP");
  let datos = $("#frmEditarClientes").serialize();
  console.log(datos);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
      if (respuesta == "ok") {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 7000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
            loadInventarios(1);
            loadKardex(1);
            //   $("#modalAgregarAjusteInventario").modal("hide");
            //   $("#idproducto").val("");
            //   $("#cantidadModificar").val("");
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡Datos del cliente editados con éxito!!</h4>`,
          html: ``,
        });

        // $("#frmAgregarClientes input").val("");
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.href);
        }

        $("#modalEditarCliente").modal("hide");
        loadClientes(activo);
      } else {
        $(".resultadoCrearCliente").html(respuesta);
      }
    },
  });
});
$(document).on("click", ".btnEditarCliente", function () {
  let idCliente = $(this).attr("idCliente");
  var dniruc = $("#editarDI").val();
  let datos = new FormData();
  datos.append("idCliente", idCliente);
  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta["ruc"] != "") {
        $("#id").val(respuesta["id"]);
        $("#editarnumdoc").val(respuesta["ruc"]);
        $("#editarEmail").val(respuesta["email"]);
        $("#editarTelefono").val(respuesta["telefono"]);
        $("#editarDireccion").val(respuesta["direccion"]);
        $("#editarnombrerazon").val(respuesta["razon_social"]);
        $("#editartipodoc").val(6);
      }
      if (respuesta["documento"] != "") {
        $("#id").val(respuesta["id"]);
        $("#editarnumdoc").val(respuesta["documento"]);
        $("#editarEmail").val(respuesta["email"]);
        $("#editarTelefono").val(respuesta["telefono"]);
        $("#editarDireccion").val(respuesta["direccion"]);
        $("#editarnombrerazon").val(respuesta["nombre"]);
        $("#editartipodoc").val(1);
      }
    },
  });
});
// $(document).on("change", "#editartipodoc", function () {
//   let idCliente = $('.btnEditarCliente').attr("idCliente");
//   var dniruc = $("#editartipodoc").val();
//   let datos = new FormData();
//   datos.append("idCliente", idCliente);
//   $.ajax({
//     url: "ajax/clientes.ajax.php",
//     method: "POST",
//     data: datos,
//     cache: false,
//     contentType: false,
//     processData: false,
//     dataType: "json",
//     success: function (respuesta) {
//       if(dniruc = 6){
//         $("#id").val(respuesta["id"]);
//         $("#editarnumdoc").val(respuesta["ruc"]);
//         $("#editarEmail").val(respuesta["email"]);
//         $("#editarTelefono").val(respuesta["telefono"]);
//         $("#editarDireccion").val(respuesta["direccion"]);
//         $("#editarnombrerazon").val(respuesta["razon_social"]);
//       }
//       if(dniruc == 1){
//          $("#id").val(respuesta["id"]);
//       $("#editarnumdoc").val(respuesta["documento"]);
//       $("#editarEmail").val(respuesta["email"]);
//       $("#editarTelefono").val(respuesta["telefono"]);
//       $("#editarDireccion").val(respuesta["direccion"]);
//       $("#editarnombrerazon").val(respuesta["nombre"]);
//       }

//     },
//   });
// });
// ELIMINAR CLIENTE
$(document).on("click", ".btnEliminarCliente", function () {
  let idCliente = $(this).attr("idCliente");
  let datos = new FormData();
  datos.append("idEliminarCliente", idCliente);

  Swal.fire({
    title: "¿Estás seguro de eliminar este cliente?",
    text: "¡Si no lo está puede  cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminarlo!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "ajax/clientes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
          if (respuesta == "success") {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "El cliente ha sido eliminado",
              showConfirmButton: false,
              timer: 1500,
            });

            $(".contenedorcli-o" + idCliente).fadeOut(1500, function () {
              loadClientes(1);
            });
          } else{
            Swal.fire({
              position: "top-end",
              icon: "error",
              title: ""+respuesta,
              showConfirmButton: false,
              timer: 5500,
            });
          }
        }, //succes
      });
    }
  });
});
// LISTAR CLIENTES CON BUSCADOR
let perfilOcultoc = $("#perfilOcultoc").val();

function loadClientes(page) {
  let search = $("#search").val();
  let selectnum = $("#selectnum").val();
  let activos = $("#activos").val();
  let parametros = {
    action: "ajax",
    page: page,
    search: search,
    selectnum: selectnum,
    dc: "dc",
    perfilOcultoc: perfilOcultoc,
    activos: activos,
  };

  $.ajax({
    url: "vistas/tables/dataTables.php",
    // method: 'GET',
    data: parametros,
    // cache: false,
    // contentType: false,
    // processData: false,
    beforeSend: function () {
      //  $("body").append(loadcl);
    },
    success: function (data) {
      $(".reloadcl").hide();
      $(".body-clientes").html(data);
    },
  });
}

loadClientes(1);

// BUSCAR RUC O DNI DE LA BASE DE DATOS SI NO SE ENCUENTRA PASA A BUSCAR EN LAS APIS
$(".buscarRuc").on("click", function () {
  cerrarSession();
  let numDocumento = $("#docIdentidad").val();
  var tipoDocu = $("#tipoDoc").val();
  let datos = { numDocumento: numDocumento };
  ////(numDocumento)
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",

    beforeSend: function () {
      $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
    },
    success: function (respuesta) {
      $("#reloadC").hide();
      if (respuesta != false) {
        if (numDocumento.length == 8 && tipoDocu == 1) {
          $("#razon_social").val(respuesta["nombre"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ruc']);
          $("#celular").val(respuesta["telefono"]);
          $("#idCliente").val(respuesta["id"]);
          $("#reloadC").hide();
          $("#searchpc").focus();
          $("#searchpcPos").focus();
        }
        if (numDocumento.length > 8 && tipoDocu == 6) {
          $("#razon_social").val(respuesta["razon_social"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ubigeo']);
          $("#celular").val(respuesta["telefono"]);
          $("#idCliente").val(respuesta["id"]);
          $("#reloadC").hide();
          $("#searchpc").focus();
          $("#searchpcPos").focus();
        }
      } else {
        $("#idCliente").val("");
        let rucCliente = $("#docIdentidad").val();
        let tipoDoc = $("#tipoDoc").val();
        let datos = { rucCliente: rucCliente, tipoDoc: tipoDoc };
        $.ajax({
          method: "POST",
          url: "ajax/clientes.ajax.php",
          data: datos,
          dataType: "json",
          beforeSend: function () {
            if (rucCliente != "") {
              $("#reloadC").show(5).html("<img src='vistas/img/reload.svg'> ");
              document.getElementById("reloadC").style.visibility = "visible";
            }
          },
          success: function (respuesta) {
            $("#reloadC").hide();
            if (respuesta != "error") {
              $("#reloadC").hide();
              //   var json = eval(respuesta);
              $("#docIdentidad").val(respuesta["ruc"]);
              $("#razon_social").val(respuesta["razon_social"]);
              $("#direccion").val(respuesta["direccion"]);
              $("#ubigeo").val(respuesta["ubigeo"]);
              document.getElementById("reloadC").style.visibility = "hidden";
              $("#celular").val("");
              $("#searchpc").focus();
              $("#searchpcPos").focus();
              if (respuesta["estado"] == "ACTIVO") {
                $("#rucActivo").hide();
                $("#rucActivo")
                  .show()
                  .css("background", "#59C345")
                  .html(respuesta["estado"]);
              } else {
                if (tipoDoc == "6") {
                  $("#rucActivo").hide();
                  $("#docIdentidad").val("");
                  $("#rucActivo")
                    .show()
                    .css("background", "#DC5858")
                    .html(respuesta["estado"]);
                }
              }
            } else {
              Swal.fire({
                position: "top-end",
                icon: "error",
                title: "El DNI/RUC no se encuentra",
                showConfirmButton: false,
                timer: 2500,
              });

              $("#reloadC").hide();
              $("#razon_social").val("");
              $("#direccion").val("");
              $("#ubigeo").val("");
              $("#celular").val("");
            }
          },
        });
      }
    },
  });
});
// TIPO DOCUMENTO 0000000 PARA BOLETAS SIN DOCUMENTO
$("#tipoDoc").on("change", function () {
  cerrarSession();
  let numDocumento = "00000000";
  let tipoDocu = $("#tipoDoc").val();
  let datos = { numDocumento: "00000000" };
  ////(numDocumento)
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",

    beforeSend: function () {
      $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
      document.getElementById("reloadC").style.visibility = "visible";
    },
    success: function (respuesta) {
      ////(respuesta)
      $("#reloadC").hide();
      if (respuesta != false) {
        if (tipoDocu == 0) {
          $("#razon_social").val(respuesta["nombre"]);
          $("#docIdentidad").val(respuesta["documento"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ruc']);
          $("#celular").val(respuesta["telefono"]);
          $("#idCliente").val(respuesta["id"]);
          document.getElementById("reloadC").style.visibility = "hidden";
          // $("#reloadC").hide();
          $("#searchpc").val("");
          $("#searchpcPos").val("");
          $("#searchpc").focus();
          $("#searchpcPos").focus();
        } else {
          document.getElementById("reloadC").style.visibility = "hidden";
          $("#razon_social").val("");
          $("#docIdentidad").val("");
          $("#direccion").val("");
          $("#celular").val("");
          $("#ubigeo").val("");
          $("#docIdentidad").focus();
        }
      }
    },
  });
});
window.addEventListener("load", function () {
 
  let ruta = $("#ruta_comprobante").val();

  if (ruta == "crear-boleta") { 
    $("#tipoDoc").val(0)
    let tipoDocu = $("#tipoDoc").val();
  let datos = { numDocumento: "00000000" };
  ////(numDocumento)
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",

    beforeSend: function () {
      $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
      document.getElementById("reloadC").style.visibility = "visible";
    },
    success: function (respuesta) {
      ////(respuesta)
      $("#reloadC").hide();
      if (respuesta != false) {
        if (tipoDocu == 0) {
          $("#razon_social").val(respuesta["nombre"]);
          $("#docIdentidad").val(respuesta["documento"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ruc']);
          $("#celular").val(respuesta["telefono"]);
          $("#idCliente").val(respuesta["id"]);
          document.getElementById("reloadC").style.visibility = "hidden";
          // $("#reloadC").hide();
          $("#searchpc").val("");
          $("#searchpcPos").val("");
          $("#searchpc").focus();
          $("#searchpcPos").focus();
        } 
      }
    },
  });
  }
});
// BUSCAR CLIENTE PARA COMPROBANTE|
$("#docIdentidad").keyup(function () {
  cerrarSession();
  let numeroDoc = $(this).val();
  let tipoDocumento = $("#tipoDoc").val();
  let datos = { numeroDoc: numeroDoc, tipoDocumento: tipoDocumento };
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    // dataType: "json",

    beforeSend: function () {
      //$("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
    },
    success: function (respuesta) {
      if (respuesta != false) {
        if (numeroDoc != "" && numeroDoc.length > 0) {
          $(".resultadoCliente").show();

          $(".resultadoCliente").html(respuesta);
        } else {
          $(".resultadoCliente").hide();
        }
      } else {
        $(".resultadoCliente").hide();
      }
    },
  });
});

// AGREGAR CLIENTE A LOS INPUTS
$(document).on("click", ".btn-add", function (e) {
  e.preventDefault();
  cerrarSession();
  let tipoDocumento = $("#tipoDoc").val();
  let idCliente = $(this).attr("idCliente");
  let datos = new FormData();
  datos.append("idCliente", idCliente);
  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (tipoDocumento == 1) {
        $("#idCliente").val(respuesta["id"]);
        $("#razon_social").val(respuesta["nombre"]);
        $("#docIdentidad").val(respuesta["documento"]);
        $("#direccion").val(respuesta["direccion"]);
        $("#ubigeo").val(respuesta["ubigeo"]);
        $("#celular").val(respuesta["telefono"]);
        $("#email").val(respuesta["email"]);
        $(".resultadoCliente").hide();
        $("#searchpc").focus();
        $("#searchpcPos").focus();
      } else {
        $("#idCliente").val(respuesta["id"]);
        $("#razon_social").val(respuesta["razon_social"]);
        $("#docIdentidad").val(respuesta["ruc"]);
        $("#direccion").val(respuesta["direccion"]);
        $("#ubigeo").val(respuesta["ubigeo"]);
        $("#celular").val(respuesta["telefono"]);
        $("#email").val(respuesta["email"]);
        $(".resultadoCliente").hide();
        $("#searchpc").focus();
        $("#searchpcPos").focus();
      }
    },
  });
});
$(".modoemail").change(function () {
  let modo = $(".modoemail:checked").val();

  if (modo == "s") {
    $("#sie").addClass("emailsi");
    $("#sie").html("Sí");
    $("#noe").html("||");
    $("#sie").removeClass("alterno");
    $("#noe").addClass("alterno");
    $(".email-colunma").show(500);
  } else {
    $("#noe").addClass("emailno");
    $("#noe").html("No");
    $("#sie").html("||");
    $("#sie").addClass("alterno");
    $("#noe").removeClass("alterno");
    $(".email-colunma").hide(200);
  }
});
function loadEmaiChange() {
  let modo = $(".modoemail:checked").val();

  if (modo == "s") {
    $("#sie").addClass("emailsi");
    $("#sie").html("Sí");
    $("#noe").html("||");
    $("#sie").removeClass("alterno");
    $("#noe").addClass("alterno");
  } else {
    $("#noe").addClass("emailno");
    $("#noe").html("No");
    $("#sie").html("||");
    $("#sie").addClass("alterno");
    $("#noe").removeClass("alterno");
    $(".email-colunma").hide();
  }
}
loadEmaiChange();
function loadF() {
  var ruraf = $("#ruta_comprobante").val();
  if (ruraf == "crear-factura") {
    $("#tipoDoc").val(6);
  }
}

loadF();

$(document).on("click", ".btn-desactivar-cli", function () {
  var id_clientea = $(this).attr("idcliente");
  var activos = $(this).attr("activar");
  var activo = $("#active").attr("idP");
  if (activos == "n") {
    $("#idp" + id_clientea).attr("activar", "s");
    $("#idp" + id_clientea).removeClass("activarpro");
    $("#idp" + id_clientea).addClass("desactivarpro");
    $("#idp" + id_clientea).html("Inactivo");
  } else {
    $("#idp" + id_clientea).attr("activar", "n");
    $("#idp" + id_clientea).removeClass("desactivarpro");
    $("#idp" + id_clientea).addClass("activarpro");
    $("#idp" + id_clientea).html("Activo");
  }

  console.log(activos + id_clientea);
  let datos = { id_clientea: id_clientea, activos: activos };

  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    success: function (data) {
      $(".contenedorcli-o" + id_clientea).fadeOut(500, function () {
        loadClientes(activo);
      });
    },
  });
});
