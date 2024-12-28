$(document).ready(function () {
  var rurg = $("#ruta_comprobante").val();
  if (rurg == "crear-guia") {
    document.getElementById("modalidadTraslado").value = "02";
    document.getElementById("tipoDocTransporte").value = "1";
  }
});

$(".resultado-ubigeos-partida").hide();
$(".resultado-ubigeos-llegada").hide();
$(".resultado-serie").hide();
// LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
function loadProductosG(page) {
  let searchProductoG = $("#searchProductoG").val();
  let selectnum = $("#selectnum").val();
  let selectSucursal = $("#idcSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchProductoG: searchProductoG,
    selectnum: selectnum,
    dpg: "dpg",
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTables.php",
    // method: 'GET',
    data: parametros,
    // cache: false,
    // contentType: false,
    // processData: false,
    beforeSend: function () {
      //   $("#modalProductosVenta").append(loadcar);
    },
    success: function (data) {
      // console.log(data);
      $(".reloadc").hide();
      $(".body-productos-guia").html(data);
    },
  });
}
loadProductosG(1);

// AGREGAR PRODUCTOS AL CARRO
$(document).on("click", "button.agregarProductoGuia", function () {
  let descripcionProducto = $(this).attr("descripcionP");
  let idProducto = $(this).attr("idProducto");
  let cantidad = $("#cantidad" + idProducto).val();
  let idSucursal = $("#idcSucursal").val();

  let datos = {
    idProducto: idProducto,
    cantidad: cantidad,
    idSucursal: idSucursal,
  };

  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProducto table #itemsPG").html(respuesta);

      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        // width: 600,
        // padding: '3em',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Toast.fire({
        icon: "success",
        title: `<h5>Se ha agregado al carrito</h5>`,
        html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-shopping-cart"></i> ${descripcionProducto}</div`,
      });
      // comillas invertidas  (``);
      $(".contenedor-items").fadeIn(200);
      $(".tablaVentas thead").fadeIn(200);
    },
  });
});

// ELIMINAR ITEM DEL CARRO
$(document).on("click", "button.btnEliminarItemCarroG", function () {
  let idEliminarCarro = $(this).attr("itemEliminar");

  let datos = {
    idEliminarCarro: idEliminarCarro,
  };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".id-eliminar" + idEliminarCarro).fadeOut(500, function () {
        $(".nuevoProducto table #itemsPG").html(respuesta);
      });
    },
  });
});

$(document).on("change", "#modalidadTraslado", function () {
  var motivo = $(this).val();
  //(motivo);
  if (motivo == "01") {
    document.getElementById("tipoDocTransporte").value = "6";
    $("#formGuia .docTransporte").html(
      `N° RUC Empresa Transporte <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Razón Social <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia #docTransporte").val("");
    $("#formGuia #nombreRazon").val("");
    $("#formGuia #apellidosRazon").val("");
    $(".placa-v #placa").val("");
    $(".placa-v").hide();
    $(".input-group-c-apellidos").hide();
    $(".nombre-razon").removeClass("col-md-4");
    $(".nombre-razon").addClass("col-md-6");
    $("#tipoVehiculo").prop("disabled", true);
  } else {
    document.getElementById("tipoDocTransporte").value = "1";
    $("#formGuia .docTransporte").html(
      `N° DNI Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia #docTransporte").val("");
    $("#formGuia #nombreRazon").val("");
    $(".placa-v").show();
    $(".input-group-c-apellidos").show();
    $(".nombre-razon").removeClass("col-md-6");
    $(".nombre-razon").addClass("col-md-4");
    $("#tipoVehiculo").prop("disabled", false);
  }
});
$(document).on("change", "#tipoDocTransporte", function () {
  var tipoDoc = $(this).val();

  if (tipoDoc != 1 || tipoDoc != 6) {
    $("#formGuia .docTransporte").html(
      `N° Doc Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
  }
  if (tipoDoc == 1) {
    $("#formGuia .docTransporte").html(
      `N° DNI Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
  }
  if (tipoDoc == 6) {
    $("#formGuia .docTransporte").html(
      `N° RUC Empresa Transporte <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Razón Social <span style="color:red; border-style: none !important;">*</span>`
    );
  }
});
$(document).on("change", "#tipoVehiculo", function () {
  tipoVehiculo = $(this).val();
  if (tipoVehiculo != "otros") {
    $(".datos-del-transporte").hide();
  } else {
    $(".datos-del-transporte").show();
  }
});
$(document).on("click", ".btnGuardarGuia", function (e) {
  // let dataForm = $("#formGuia").serialize();
  var idSucursal = $("#idcSucursal").val();
  var dataForm = new FormData(document.getElementById("formGuia"));
  dataForm.append("idSucursal", idSucursal);
  Swal.fire({
    title: "¿Estás seguro en guardar el comprobante?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, guardar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        method: "POST",
        url: "ajax/crear-guia.ajax.php",
        data: dataForm,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (data) {
          console.log(data);
          Swal.fire({
            icon: "success",
            title: "",
            text: "",
            html: '<div id="successG"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });
          $(".reload-all").fadeOut(50);
          $("#successG").html(data);
        },
      });
    }
  });
});

$(document).on("keyup", "#ubigeoPartida", function (e) {
  let ubigeopartida = $(this).val();
  let datos = { ubigeopartida: ubigeopartida };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      if (ubigeopartida == "") {
        $(".resultado-ubigeos-partida").hide();
      } else {
        $(".resultado-ubigeos-partida").show().html(data);
      }
    },
  });
});
$(document).on("keyup", "#ubigeoLlegada", function (e) {
  let ubigeollegada = $(this).val();
  let datos = { ubigeollegada: ubigeollegada };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      if (ubigeollegada == "") {
        $(".resultado-ubigeos-llegada").hide();
      } else {
        $(".resultado-ubigeos-llegada").show().html(data);
      }
    },
  });
});
$(document).on("click", ".btn-ubigeo-partida", function (e) {
  e.preventDefault();
  let codUbigeo = $(this).attr("idUbigeo");
  let datos = { codUbigeo: codUbigeo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#ubigeoPartida").val(data["id"]);
      $(".resultado-ubigeos-partida").hide();
    },
  });
});
$(document).on("click", ".btn-ubigeo-llegada", function (e) {
  e.preventDefault();
  let codUbigeo = $(this).attr("idUbigeo");
  let datos = { codUbigeo: codUbigeo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#ubigeoLlegada").val(data["id"]);
      $(".resultado-ubigeos-llegada").hide();
    },
  });
});
$(".buscarDniRuc").on("click", function () {
  let rucCliente = $("#docTransporte").val();
  let tipoDoc = $("#tipoDocTransporte ").val();
  let datos = { rucCliente: rucCliente, tipoDoc: tipoDoc };
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {
      if (rucCliente != "") {
        $("#reloadCG").show(5).html("<img src='vistas/img/reload.svg'> ");
        document.getElementById("reloadCG").style.visibility = "visible";
      }
    },
    success: function (respuesta) {
      if (respuesta != "error") {
        $("#reloadCG").hide();

        if (tipoDoc == 6) {
          $("#docTransporte").val(respuesta["ruc"]);
          $("#nombreRazon").val(respuesta["razon_social"]);
        } else {
          $("#docTransporte").val(respuesta["ruc"]);
          $("#nombreRazon").val(respuesta["nombres"]);
          $("#apellidosRazon").val(respuesta["apellidos"]);
        }

        document.getElementById("reloadC").style.visibility = "hidden";
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "El DNI/RUC no se encuentra",
          showConfirmButton: false,
          timer: 2500,
        });
      }
    },
  });
});

$(document).on("keyup", "#serieCorrelativoReferencial", function (e) {
  let serieCorrelativo = $(this).val();
  let idSucursal = $("#idcSucursal").val();
  let datos = { serieCorrelativo: serieCorrelativo, idSucursal: idSucursal };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      $(".resultado-serie").show().html(respuesta);
    },
  });
});
$(document).on("click", ".btn-serie-correlativo", function (e) {
  e.preventDefault();
  let numCorrelativo = $(this).attr("numCorrelativo");
  let datos = { numCorrelativo: numCorrelativo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      $(".nuevoProducto .table #itemsPG").html(respuesta);
      $(".resultado-serie").hide();
      $("#serieCorrelativoReferencial").val(numCorrelativo);
    },
  });
});

function loadGuiasR(page) {
  var searchGuias = $("#searchGuias").val();
  var selectnum = $("#selectnum").val();
  var fechaInicial = $("#fechaInicial").val();
  var fechaFinal = $("#fechaFinal").val();
  let selectSucursal = $("#selectSucursal").val();
  var parametros = {
    action: "ajax",
    page: page,
    searchGuias: searchGuias,
    selectnum: selectnum,
    lig: "lig",
    fechaInicial: fechaInicial,
    fechaFinal: fechaFinal,
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTables-guias.php",
    // method: 'GET',
    data: parametros,

    beforeSend: function () {
      //   $("#modalProductosVenta").append(loadcar);
    },
    success: function (data) {
      //   $(".reloadc").hide();
      $(".body-listaguias").html(data);
    },
  });
}
function loadGuiasRetorno(page) {
  var searchGuiasRe = $("#searchGuiasR").val();
  var selectnum = $("#selectnum").val();
  var fechaInicial = $("#fechaInicial").val();
  var fechaFinal = $("#fechaFinal").val();
  let selectSucursal = $("#selectSucursal").val();
  var parametros = {
    action: "ajax",
    page: page,
    searchGuiasRe: searchGuiasRe,
    selectnum: selectnum,
    ligr: "ligr",
    fechaInicial: fechaInicial,
    fechaFinal: fechaFinal,
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTables-guias-retorno.php",
    // method: 'GET',
    data: parametros,

    beforeSend: function () {
      //   $("#modalProductosVenta").append(loadcar);
    },
    success: function (data) {
      //   $(".reloadc").hide();
      $(".body-listaguias-retorno").html(data);
    },
  });
}
loadGuiasR(1);
loadGuiasRetorno(1);

$("#formGuia").on("keyup", "#placa", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9]/g, "");
    $("#placa").val(placa);
  }
});

$("#formGuia").on("keyup", "#numBrevete", function () {
  var numBrevete = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(numBrevete)) {
    //dni = dni.substr(0,(dni.length -1));
    numBrevete = numBrevete.replace(/[^a-zA-Z0-9]/g, "");
    $("#numBrevete").val(numBrevete);
  }
});
$("#formGuia").on("keyup", "#direccionPartida", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9 ]/g, "");
    $("#direccionPartida").val(placa);
  }
});
$("#formGuia").on("keyup", "#direccionLlegada", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9 ]/g, "");
    $("#direccionLlegada").val(placa);
  }
});

$(document).on("click", ".btn-retornar-almacen", function (e) {
  let idGuia = $(this).attr("idGuia");
  var activo = $("#active").attr("idP");
  Swal.fire({
    title: "¿Estás seguro en actualizar stock?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, actualizar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let datos = { idGuia: idGuia };
      $.ajax({
        method: "POST",
        url: "ajax/crear-guia.ajax.php",
        data: datos,
        success: function (respuesta) {
          if (respuesta) {
            Swal.fire({
              title: "STOCK RETORNADO!",
              text: "Stock actualizado corréctamente!",
              icon: "success",
            });
            loadGuiasRetorno(activo);
          }
        },
      });
    }
  });
});

$(document).on("click", "#getcdr-guia", function () {
  var idgetGuia = $(this).attr("idGuia");
  var activo = $("#active").attr("idP");
  console.log(idgetGuia);
  let datos = { idgetGuia: idgetGuia };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      console.log(respuesta);
      Swal.fire({
        icon: "success",
        title: "",
        text: "",
        html: '<div id="successG"></div>',
        showCancelButton: true,
        showConfirmButton: false,
        allowOutsideClick: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      $(".reload-all").fadeOut(50);
      $("#successG").html(respuesta);
      loadGuiasR(activo);
    },
  });
});

$(document).on("click", ".btnAgregarSerieG", function (e) {
  var idproductoS = $(this).attr("idProductoG");
  $("#idproductoS").val(idproductoS);
  var data = { idproductoS: idproductoS };
  $.ajax({
    method: "POST",
    url: "vistas/modulos/productos-guias-series.php",
    data: data,
    success: function (datos) {
      $(".contenido-inputs-guias").html(datos);
      $("#idproductoS").val(idproductoS);
    },
  });
});


// AGREGAR PRODUCTOS AL CARRO
$(document).on("click", ".btn-add-serie-guia", function () {
  let idserie = $(this).attr("idSerie");
  let serie = $("#serieG" +idserie).val();
  let codproducto = $("#codproducto").val();
  let idproductoS = $(this).attr("idProducto");
  plus2++;
  $(".series-guia").append(`
    
    <tr class="eliminar_item" id="itemserie${plus2}" >
      <td>${codproducto}</td>
      <td>
      <div class="input-group" style="margin: 0 auto;">
     <input type="hidden" name="idserie[]" id="idserie[]" value="${idserie}">
    <input type="text" class="form-control" name="serieg[]" id="serieg"  value="${serie}" placeholder="INGRESE LA SERIE" readonly>
      <span class="btn btn-danger btn-xs btn-remove-add-serie-agregar" idre="${plus2}" style="position:absolute; right:-35px; top:-0px !important; color:#fff;" idSerie="${idserie}"><i class="fas fa-trash-alt"></i></span>
    </div>
    </td>
     </tr>
    `);


   
      var activoserie = "n";
    
    var data = { idSerie: idserie, activoserie: activoserie };
    $.ajax({
      method: "POST",
      url: "ajax/productos.ajax.php",
      data: data,
      success: function (datos) {
        if(datos == 'ok'){
    var data = { idproductoS: idproductoS };
    $.ajax({
      method: "POST",
      url: "vistas/modulos/productos-guias-series.php",
      data: data,
      success: function (datos) {
        $(".contenido-inputs-guias").html(datos);
        $("#idproductoS").val(idproductoS);
      },
        });
      }
     
}
});
});

$(document).on("click", ".btn-remove-add-serie-agregar", function (e) {
  let idserie = $(this).attr("idSerie");
  let num = $(this).attr("idre");

  $(this).parent().remove();
  $("#itemserie" + num).remove();
  plus--;


  var activoserie = "s";
    
  var data = { idSerie: idserie, activoserie: activoserie };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: data,
    success: function (datos) {
      if(datos == 'ok'){

      }
    }

});
});