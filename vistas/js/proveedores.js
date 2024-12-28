$(".resultadoProveedor").hide();
$(".resultadoSerie").hide();
$("#rucActivo").hide();

// BUSCAR RUC O DNI DE LA BASE DE DATOS SI NO SE ENCUENTRA PASA A BUSCAR EN LAS APIS
$(".buscarRucP").on("click", function () {
  let numDocumento = $("#docIdentidad").val();
  let tipoDocu = $("#tipoDoc").val();
  let datos = { numDocumentoP: numDocumento };
  ////(numDocumento)
  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    dataType: "json",

    beforeSend: function () {
      $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
    },
    success: function (respuesta) {
      if (respuesta != false) {
        if (numDocumento.length == 8 && tipoDocu == 1) {
          $("#razon_social").val(respuesta["nombre"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ruc']);
          $("#celular").val(respuesta["telefono"]);
          $("#idProveedor").val(respuesta["id"]);
          $("#reloadC").hide();
        }
        if (numDocumento.length > 8 && tipoDocu == 6) {
          $("#razon_social").val(respuesta["razon_social"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ubigeo']);
          $("#celular").val(respuesta["telefono"]);
          $("#idProveedor").val(respuesta["id"]);
          $("#reloadC").hide();
        }
      } else {
        $("#idProveedor").val("");
        let rucProveedor = $("#docIdentidad").val();
        let tipoDoc = $("#tipoDoc").val();
        let datos = { rucProveedor: rucProveedor, tipoDoc: tipoDoc };
        $.ajax({
          method: "POST",
          url: "ajax/proveedores.ajax.php",
          data: datos,
          dataType: "json",
          beforeSend: function () {
            if (rucProveedor != "") {
              $("#reloadC").show(5).html("<img src='vistas/img/reload.svg'> ");
              document.getElementById("reloadC").style.visibility = "visible";
            }
          },
          success: function (respuesta) {
            if (respuesta != "error") {
              $("#reloadC").hide();
              //   var json = eval(respuesta);
              $("#docIdentidad").val(respuesta["ruc"]);
              $("#razon_social").val(respuesta["razon_social"]);
              $("#direccion").val(respuesta["direccion"]);
              $("#ubigeo").val(respuesta["ubigeo"]);
              document.getElementById("reloadC").style.visibility = "hidden";
              $("#celular").val("");

              if (respuesta["estado"] == "ACTIVO") {
                $("#rucActivo")
                  .show()
                  .css("background", "#59C345")
                  .html(respuesta["estado"]);
              } else {
                if (tipoDocu == "06") {
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
    },
    success: function (respuesta) {
      ////(respuesta)
      if (respuesta != false) {
        if (tipoDocu == 0) {
          $("#razon_social").val(respuesta["nombre"]);
          $("#docIdentidad").val(respuesta["documento"]);
          $("#direccion").val(respuesta["direccion"]);
          //$('#ubigeo').val(respuesta['ruc']);
          $("#celular").val(respuesta["telefono"]);
          $("#idProveedor").val(respuesta["id"]);
          $("#reloadC").hide();
        } else {
          $("#reloadC").hide();
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

// BUSCAR CLIENTE PARA COMPROBANTE|
$("#docIdentidad").keyup(function () {
  let numeroDoc = $(this).val();
  let tipoDocumento = $("#tipoDoc").val();
  let datos = { numeroDocP: numeroDoc, tipoDocumentoP: tipoDocumento };
  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    // dataType: "json",

    beforeSend: function () {
      //$("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
    },
    success: function (respuesta) {
      if (respuesta != false) {
        if (numeroDoc != "" && numeroDoc.length > 3) {
          $(".resultadoProveedor").show();

          $(".resultadoProveedor").html(respuesta);
        } else {
          $(".resultadoProveedor").hide();
        }
      } else {
        $(".resultadoProveedor").hide();
      }
    },
  });
});

// AGREGAR CLIENTE A LOS INPUTS
$(document).on("click", ".btn-add-p", function (e) {
  e.preventDefault();
  let tipoDocumento = $("#tipoDoc").val();
  let idProveedor = $(this).attr("idProveedor");
  let datos = new FormData();
  datos.append("idProveedor", idProveedor);
  $.ajax({
    url: "ajax/proveedores.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      //(respuesta);
      if (tipoDocumento == 1) {
        $("#idProveedor").val(respuesta["id"]);
        $("#razon_social").val(respuesta["nombre"]);
        $("#docIdentidad").val(respuesta["documento"]);
        $("#direccion").val(respuesta["direccion"]);
        $("#ubigeo").val(respuesta["ubigeo"]);
        $("#celular").val(respuesta["telefono"]);
        $("#email").val(respuesta["email"]);
        $(".resultadoProveedor").hide();
        $("#reloadC").hide();
      } else {
        $("#idProveedor").val(respuesta["id"]);
        $("#razon_social").val(respuesta["razon_social"]);
        $("#docIdentidad").val(respuesta["ruc"]);
        $("#direccion").val(respuesta["direccion"]);
        $("#ubigeo").val(respuesta["ubigeo"]);
        $("#celular").val(respuesta["telefono"]);
        $("#email").val(respuesta["email"]);
        $(".resultadoProveedor").hide();
        $("#reloadC").hide();
      }
    },
  });
});

$("#nuevotipodoc").on("change", function (e) {
  let tipoDoc = $(this).val();
  if (tipoDoc == 1) {
    $("#nuevoRuc").val("");
    $("#nuevoRuc").prop("placeholder", "Ingrese D.N.I.");
    $("#nuevaRazon").prop("placeholder", "Ingresar Nombre Completo.");
  }
  if (tipoDoc == 6) {
    $("#nuevoRuc").val("");
    $("#nuevoRuc").prop("placeholder", "Ingrese R.U.C.");
    $("#nuevaRazon").prop("placeholder", "Ingresar Razón Social.");
  }
});
// BUSCAR RUC O DNI DE LA BASE DE DATOS SI NO SE ENCUENTRA PASA A BUSCAR EN LAS APIS
$(document).on("change", "#nuevoRuc", function () {
  let numDocumento = $("#nuevoRuc").val();
  let datos = { numDocumentoP: numDocumento };
  ////(numDocumento)
  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    dataType: "json",

    beforeSend: function () {},
    success: function (respuesta) {
      $(".reload-all").hide();
      if (respuesta == false) {
        let rucProveedor = $("#nuevoRuc").val();
        let tipoDoc = $("#nuevotipodoc").val();
        let datos = { rucProveedor: rucProveedor, tipoDoc: tipoDoc };
        $.ajax({
          method: "POST",
          url: "ajax/proveedores.ajax.php",
          data: datos,
          dataType: "json",
          beforeSend: function () {
            $(".reload-all")
              .fadeIn(50)
              .html("<img src='vistas/img/reload.svg' width='80px'> ");
          },
          success: function (respuesta) {
            $(".reload-all").hide();
            if (respuesta != "error") {
              if (tipoDoc == 6) {
                $("#nuevaRazon").val(respuesta["razon_social"]);
              }
              if (tipoDoc == 1) {
                $("#nuevaRazon").val(respuesta["razon_social"]);
              }
              $("#nuevaDireccion").val(respuesta["direccion"]);
              $("#nuevoUbigeo").val(respuesta["ubigeo"]);
              let combo = document.getElementById("nuevoUbigeo");
              let ubigeo = combo.options[combo.selectedIndex].text;
              $("#select2-nuevoUbigeo-container").html(ubigeo);
            }
          },
        });
      } else {
        $("#nuevoRuc").val("");
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "EL PROVEEDOR YA ESTÁ REGISTRADO",
          showConfirmButton: false,
          timer: 2500,
        });
        $;
      }
    },
  });
});

$(".btnCrearProveedor").one("click", function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  datos = $("#formNuevoProveedor").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      //   console.log(data);
      $(".resultadoCrearProveedor").html(data);
    },
  });
});
$("tbody").on("click", ".btnEditarProveedorItem", function (e) {
  e.preventDefault();
  // e.stopImmediatePropagation();
  let idProveedor = $(this).attr("idProveedor");
  datos = { idProveedor: idProveedor };
  console.log(idProveedor);
  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      console.log(data);
      $("#idProveedorEditar").val(data["id"]);
      if (data["ruc"] != "") {
        $("#editarRuc").val(data["ruc"]);
        $("#editarRazon").val(data["razon_social"]);
      } else {
        $("#editarRuc").val(data["documento"]);
        $("#editarRazon").val(data["nombre"]);
      }

      $("#editarDireccion").val(data["direccion"]);
      $("#editarEmail").val(data["email"]);
      $("#editarUbigeo").val(data["ubigeo"]);
      $("#editarTelefono").val(data["telefono"]);

      let combo = document.getElementById("editarUbigeo");

      let ubigeo = combo.options[combo.selectedIndex].text;
      $("#select2-editarUbigeo-container").html(ubigeo);
    },
  });
});
$(document).one("click", ".btnEditarProveedor", function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  datos = $("#formEditarProveedor").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/proveedores.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      //   console.log(data);
      $(".resultadoCrearProveedor").html(data);
    },
  });
});
