$(".resultadoProveedor").hide();
$(".resultadoSerie").hide();
$("#rucActivo").hide();

$(document).on("keyup", "#nuevoDNI", function () {
  var ruc = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(ruc)) {
    //dni = dni.substr(0,(dni.length -1));
    ruc = ruc.replace(/[^0-9]/g, "");
    $("#nuevoDNI").val(ruc);
  }
});

$(document).on("change", "#nuevoDNI", function () {
  var ruc = $(this).val();
  let rucCliente = $(this).val();
  var tipoDoc = $("#nuevoTipoDoc").val();
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
        if (tipoDoc == 1) {
          $("#nuevoNombre").val(respuesta["nombres"]);
          $("#nuevoApellidos").val(respuesta["apellidos"]);
        }
      }
    },
  });
});

$("#formNuevoConductor").submit(function (e) {
  e.preventDefault();
  datos = $("#formNuevoConductor").serialize();
  $.ajax({
    method: "POST",
    url: "ajax/conductores.ajax.php",
    data: datos,
    beforeSend: function () { },
    success: function (data) {
      $(".resultadoCrearConductor").html(data);
    },
  });
});

$("#formEditarConductor").submit(function (e) {
  e.preventDefault();
  datos = $("#formEditarConductor").serialize();
  $.ajax({
    method: "POST",
    url: "ajax/conductores.ajax.php",
    data: datos,
    beforeSend: function () { },
    success: function (data) {
      $(".resultadoCrearConductor").html(data);
    },
  });
});

$("tbody").on("click", ".btnEditarConductorItem", function (e) {
  e.preventDefault();
  let idConductor = $(this).attr("idConductor");
  datos = { idConductor };
  $.ajax({
    method: "POST",
    url: "ajax/conductores.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $("#editarTipoDoc").val(data["tipdoc"]);
      $("#idConductorEditar").val(data["id"]);
      $("#editarNombre").val(data["nombres"]);
      $("#editarApellidos").val(data["apellidos"]);
      $("#editarDNI").val(data["numdoc"]);
      $("#editarNumBrevete").val(data["numbrevete"]);
      $("#editarNumPlaca").val(data["numplaca"]);
      $("#editarMarcaVehiculo").val(data["marca_vehiculo"]);
    },
  });
});

$(document).on("click", ".btnEliminarConductor", function () {
  let idConductor = $(this).attr("idConductor");
  let datos = new FormData();
  datos.append("idEliminarConductor", idConductor);
  Swal.fire({
    title: "¿Estás seguro de eliminar este conductor?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminarlo!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "ajax/conductores.ajax.php",
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
              title: "El conductor ha sido eliminado",
              showConfirmButton: false,
              timer: 1500,
            });
            setTimeout(() => {
              window.location.reload()
            }, 2000);
          } else {
            Swal.fire({
              position: "top-end",
              icon: "error",
              title: "" + respuesta,
              showConfirmButton: false,
              timer: 5500,
            });
          }
        }, //succes
      });
    }
  });
});