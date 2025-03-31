$(".pos-contenedoor").hide();
$("#icbperLineaPos").hide();
$(".btn-agregar-ps-pos").hide();
window.addEventListener("load", function () {
  let ruta = $("#ruta_comprobante").val();
  var codigo = $(".tipo_comprobante").val();
  $(".factura-pos").addClass("factura");
  $("body").addClass("sidebar-collapse");
  if (ruta == "pos") {
    $(".main-sidebar").hide();
    $(".seccion-fechas-moneda-correlativo").hide();
    $(".seccion-coreo-cuotas").hide();
    $(".contenedor-cuotas").hide();
    $(".seccion-observacion").hide();
    $(".main-header").hide();
    $(".main-footer").hide();
    $(".seccion-sucursal").hide();
    $(".seccion-detra-selva").hide();
    $(".seccion-detra-s").hide();
    $(".pos-contenedoor").show();
    $(".btn-agregar-ps-pos").addClass("ocultar-canasta");
    $(".enviar-c").addClass("activar-envio-sunat");

    $("#icbperLineaPos").val(0);
    let datos = { idSerie: codigo };
    $.ajax({
      method: "POST",
      url: "ajax/pos.ajax.php",
      data: datos,
      beforeSend: function () {},
      success: function (data) {
        // console.log(data);
        $("#serie").html(data);
      },
    });
  } else {
    $(".main-sidebar").show();
    $("body").removeClass("sidebar-collapse");
  }

  if (codigo == "01") {
    $("#tipoDoc").val(6);
  }
  if (codigo == "02" || codigo == "03") {
    $("#tipoDoc").val(1);
  }
});

// LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
function loadProductosPos(page) {
  let searchProductoV = $("#searchProductoV").val();
  let selectnum = $("#selectnum").val();
  let categorias = $("#categorias").val();
  let idSucursal = $("#idcSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchProductoV: searchProductoV,
    selectnum: selectnum,
    categorias: categorias,
    dppos: "dppos",
    idSucursal: idSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTablesPos.php",
     method: 'GET',
    data: parametros,
    beforeSend: function () {
      $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
    },

    success: function (data) {
      $(".reload-all").hide();
      $(".productos-pos").html(data);
      $(".tablaVentas .super-contenedor-precios").hide();
    },
  });
}
loadProductosPos(1);

// AGREGAR PRODUCTO O SERVICIO AL CARRO POS
$("#formitemsPos").on("click", ".btn-agregar-item-pos", function (e) {
  e.preventDefault();
  agregarProductosCarrito();
  vuelto();
});

function agregarProductosCarrito() {
  var idProducto = $("#idProducto").val();
  var moneda = $("#monedaPos").val();
  var tipocambio = $("#tipo_cambio").val();
  var tipo_desc = $("input[name=tipo_desc]:checked").val();
  var descuentoGlobal = $("#descuentoGlobalpos").val();
  var descuentoGlobalP = $("#descuentoGlobalPpos").val();
  var editar_item = $("#editar_item").val();
  var cantidaditem = $("#cantidaditempos" + idProducto).val();
  var idSucursal = $("#idcSucursal").val();
  var descripcionProducto = $(".descripcion-de-items").html();

  var formData = new FormData(document.getElementById("formitemsPos"));
  formData.append("moneda", moneda);
  formData.append("tipo_cambio", tipocambio);
  formData.append("tipo_desc", tipo_desc);
  formData.append("descuentoGlobal", descuentoGlobal);
  formData.append("descuentoGlobalP", descuentoGlobalP);
  formData.append("cantidaditem", cantidaditem);
  formData.append("editar_item", editar_item);
  formData.append("idSucursal", idSucursal);

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function () {},
    success: function (data) {
      $(".nuevoProducto table #itemsP").html(data);
      $("#modalEditarItemsPos").modal("hide");
      $("#cantidad" + idProducto).val(1);
      const Toast = Swal.mixin({
        toast: true,
        position: "top-start",
        allowOutsideClick: true,
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
      vuelto();
      loadDetraccion();
    },
  });
}
//CARGAR CARRO
function loadCarritoPos() {
  let loadCarrito = "loadCarrito";
  let moneda = $("#monedaPos").val();
  let tipo_cambio = $("#tipo_cambio").val();
  let tipo_desc = $(".tipo_desc").val();
  let descuentoGlobal = $("#descuentoGlobalpos").val();
  let descuentoGlobalP = $("#descuentoGlobalPpos").val();
  let idSucursal = $("#idcSucursal").val();
  let datos = {
    loadCarrito: loadCarrito,
    moneda: moneda,
    tipo_cambio: tipo_cambio,
    tipo_desc: tipo_desc,
    descuentoGlobal: descuentoGlobal,
    descuentoGlobalP: descuentoGlobalP,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProducto table #itemsP").html(respuesta);
      vuelto();
      loadDetraccion();
    },
  });
}
$(document).on("click", ".btn-pos-mas", function (e) {
  let idpros = $(this).attr("idpros");
  let cantidad1 = $("#cantidad" + idpros).val();
  let idSucursal = $("#idcSucursal").val();
  let cantidad = Number(cantidad1);
  $("#cantidad" + idpros)
    .val(cantidad + 1)
    .change();

  if (!$.isNumeric(cantidad) && cantidad < 1) {
    //dni = dni.substr(0,(dni.length -1));
    $("#cantidad" + idpros).val(1);
  }

  let cantidad2 = $("#cantidad" + idpros).val();

  $("#cantidadPos").val(cantidad2);
  $("#idProducto").val(idpros);

  $("#cantidaditem" + idpros)
    .val(cantidad2 - cantidad)
    .change();

  datos = { idProServ: idpros, idSucursal: idSucursal };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $(".descripcion-de-items").html(data["descripcion"]);
      $("#tipoigvLineaPos").val(data["codigoafectacion"]);
      $("#precioUnitarioLineaPos").val(data["precio_unitario"]);
      $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
      $("#igvLineaPos").val(data["igv"]);
      $(".precio_normal").val(data["precio_unitario"]);
      $(".precio_pormayor").val(data["precio_pormayor"]);
      $("#btnPrecioNormal").html(
        "PRECIO NORMAL </br>" + data["precio_unitario"]
      );
      $("#btnPrecioporMayor").html(
        "PRECIO POR MAYOR </br>" + data["precio_pormayor"]
      );

      // agregarProductosCarrito();
    },
  });
});
$(document).on("click", ".btn-pos-menos", function (e) {
  var idpros = $(this).attr("idpros");
  var idSucursal = $("#idcSucursal").val();
  let cantidad1 = $("#cantidad" + idpros).val();
  let cantidad = Number(cantidad1);
  $("#cantidad" + idpros)
    .val(cantidad - 1)
    .change();
  if (cantidad1 == 1) {
    $("#cantidad" + idpros).val(1);
  }
  let cantidad2 = $("#cantidad" + idpros).val();

  $("#cantidadPos").val(cantidad2);
  $("#idProducto").val(idpros);

  $("#cantidaditem" + idpros)
    .val(cantidad2)
    .change();

  datos = { idProServ: idpros, idSucursal: idSucursal };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $(".descripcion-de-items").html(data["descripcion"]);
      $("#tipoigvLineaPos").val(data["codigoafectacion"]);
      $("#precioUnitarioLineaPos").val(data["precio_unitario"]);
      $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
      $("#igvLineaPos").val(data["igv"]);
      $(".precio_normal").val(data["precio_unitario"]);
      $(".precio_pormayor").val(data["precio_pormayor"]);
      $("#btnPrecioNormal").html(
        "PRECIO NORMAL </br>" + data["precio_unitario"]
      );
      $("#btnPrecioporMayor").html(
        "PRECIO POR MAYOR </br>" + data["precio_pormayor"]
      );

      // agregarProductosCarrito();
    },
  });
});
$(document).on("change", ".cantidad-number", function (e) {
  var idpros = $(this).attr("idProServ");
  $("#idProducto").val(idpros);
  var idSucursal = $("#idcSucursal").val();
  let cantidadItem = $("#cantidad" + idpros).val();
  if (cantidadItem == 0) {
    var cantidad = $("#cantidadPos").val();
  } else {
    cantidad = cantidadItem;
  }

  $("#cantidad" + idpros).val(cantidad);
  $("#cantidadPos").val(cantidad);
  $("#idProducto").val(idpros);
  datos = { idProServ: idpros, idSucursal: idSucursal };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $(".descripcion-de-items").html(data["descripcion"]);
      $("#tipoigvLineaPos").val(data["codigoafectacion"]);
      $("#precioUnitarioLineaPos").val(data["precio_unitario"]);
      $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
      $("#igvLineaPos").val(data["igv"]);
      $(".precio_normal").val(data["precio_unitario"]);
      $(".precio_pormayor").val(data["precio_pormayor"]);
      $("#btnPrecioNormal").html(
        "PRECIO NORMAL </br>" + data["precio_unitario"]
      );
      $("#btnPrecioporMayor").html(
        "PRECIO POR MAYOR </br>" + data["precio_pormayor"]
      );
      vuelto();
      loadDetraccion()
      // agregarProductosCarrito();
    },
  });
});

$(document).on("click", ".foto-pos", function (e) {
  $(this).val("");
});

function cambioFotoPos(id) {
  let imagen = $("#fotoPos" + id).prop("files")[0];
  let nombreimagen = imagen["name"];
  $("#formFotoPos").prop("id", id);
  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $("#fotoPos" + id).val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen debe ser jpeg o png!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
    $("#fotoPos" + id).val("");
  } else if (imagen["size"] > 2000000) {
    $("#fotoPos" + id).val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen no debe pesar más de 2mb!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
    $("#fotoPos" + id).val("");
  } else {
    let codigo = $("#codigo" + id).val();
    let datosImagen = new FileReader();
    datosImagen.readAsDataURL(imagen);
    $(datosImagen).on("load", function (event) {
      let rutaImagen = event.target.result;
      $("#imagenPos" + id).attr("src", rutaImagen);

      var formData = new FormData();
      var files = $("#fotoPos" + id).prop("files")[0];
      formData.append("file", files);
      formData.append("idpro", id);
      formData.append("codPro", codigo);

      $.ajax({
        method: "POST",
        url: "ajax/pos.ajax.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          //   $(".reload-all")
          //     .fadeIn(50)
          //     .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          console.log(respuesta);
          $(".reload-all").hide();
          if (respuesta) {
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
              title: ``,
              html: ``,
            });
          }
        },
      });
    });
  }
}

$(document).on("change", "#icbperPos", function (e) {
  let icbper = $("input[name=icbperPos]:checked").val();
  if (icbper == "si") {
    $("#icbperLineaPos").show();
    $("#modo_icbper").val("s");
  } else {
    $("#icbperLineaPos").hide();
    $("#modo_icbper").val("n");
    $("#icbperLineaPos").val(0);
  }
});
$(document).on("click", ".btn-edit-pos", function (e) {
  let idProServ = $(this).attr("idProPos");
  let cantidadItem = $("#cantidad" + idProServ).val();
  $("#editar_item").val("edita");
  var idSucursal = $("#idcSucursal").val();
  if (cantidadItem == 0) {
    var cantidad = $("#cantidadPos").val();
  } else {
    cantidad = cantidadItem;
  }

  $("#cantidad" + idProServ).val(cantidad);
  $("#cantidadPos").val(cantidad);
  $("#idProducto").val(idProServ);
  console.log(cantidad);
  datos = { idProServ: idProServ, idSucursal: idSucursal };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $(".descripcion-de-items").html(data["descripcion"]);
      $("#tipoigvLineaPos").val(data["codigoafectacion"]);
      $("#precioUnitarioLineaPos").val(data["precio_unitario"]);
      $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
      $(".precio_normal").val(data["precio_unitario"]);
      $(".precio_pormayor").val(data["precio_pormayor"]);
      $("#btnPrecioNormal").html(
        "PRECIO NORMAL </br>" + data["precio_unitario"]
      );
      $("#btnPrecioporMayor").html(
        "PRECIO POR MAYOR </br>" + data["precio_pormayor"]
      );

      changePriceItem(idProServ);
    },
  });
});
$(document).on(
  "keyup",
  "#descuentoLineaPos,#precioUnitarioLineaPos",
  function (e) {
    let id = $("#idProducto").val();
    let descuento = $("#descuentoLineaPos").val();
    if (descuento == "") {
      $("#descuentoLineaPos").val("0.00");
    }
    changePriceItem(id);
  }
);
$(document).on("change", "#icbperPos", function (e) {
  let id = $("#idProducto").val();
  changePriceItem(id);
});
$(document).on("change", "#icbperPos, #tipoigvLineaPos", function (e) {
  let id = $("#idProducto").val();
  changePriceItem(id);
});
$(document).on("click", "#btnPrecioNormal", function (e) {
  let id = $("#idProducto").val();
  //   let preciopormayor = $('#btnPrecioporMayor').attr("precioMayor");
  let precioNormal = $(".precio_normal").val();
  console.log(precioNormal);
  $("#precioUnitarioLineaPos").val(precioNormal);

  changePriceItem(id);
});
$(document).on("click", "#btnPrecioporMayor", function (e) {
  let id = $("#idProducto").val();
  //   let preciopormayor = $('#btnPrecioporMayor').attr("precioMayor");
  let preciopormayor = $(".precio_pormayor").val();
  console.log(preciopormayor);
  $("#precioUnitarioLineaPos").val(preciopormayor);

  changePriceItem(id);
});
$("#formVentaPos #monedaPos").change(function () {
  let moneda = $(this).val();
  cambioDolar = $("#tipo_cambio").val();

  if (cambioDolar > 0) {
    loadCarritoPos();
  } else {
    if (moneda == "USD") {
      let icono = "warning";
      let texto = $("#tipo_cambio").val();
      codhtml = `<h4>No se encontró un tipo de cambio</h4>`;
      informacionModal(icono, texto, codhtml, 2500);
    }
  }
});
$(document).on("click", ".tipo_comprobante", function (e) {
  var codigo = $(this).val();

  let ruta = $("#ruta_comprobante").val();
  let idSucursal = $("#idcSucursal").val();
  let datos2 = { idSucursal: idSucursal, ruta: ruta, tipocomp: codigo };
  $.ajax({
    url: "ajax/sunat.ajax.php",
    method: "POST",
    data: datos2,
    success: function (respuesta) {
      console.log(respuesta);
      $("#serie").html(respuesta);
    },
  });
  if (codigo == "01") {
    $("#tipoDoc").val(6);
    $(".factura-pos").addClass("factura");
    $(".boleta-pos").removeClass("boleta");
    $(".nota-pos").removeClass("nota");
    $(".radio-envio-pos #no").prop("checked", false);
    // $(".radio-envio-pos").show();
    $(".contenedor-herramientas-pos #enviar").prop("checked", true);
    $(".enviar-c").addClass("activar-envio-sunat");
    $(".firmar-c").removeClass("activar-envio-sunat");
    $("#razon_social").val("");
    $("#docIdentidad").val("");
    $("#direccion").val("");
    $("#celular").val("");
    $("#ubigeo").val("");
    $("#idCliente").val("");
    $("#docIdentidad").focus();
  }
  if (codigo == "03") {
    $("#tipoDoc").val(0);

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

    $(".boleta-pos").addClass("boleta");
    $(".factura-pos").removeClass("factura");
    $(".nota-pos").removeClass("nota");
    $(".radio-envio-pos #no").prop("checked", false);
    // $(".radio-envio-pos").show();
    $(".contenedor-herramientas-pos #enviar").prop("checked", true);
    $(".enviar-c").addClass("activar-envio-sunat");
    $(".firmar-c").removeClass("activar-envio-sunat");
  }
  if (codigo == "02") {
    $("#tipoDoc").val(0);

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

    $(".nota-pos").addClass("nota");
    $(".factura-pos").removeClass("factura");
    $(".boleta-pos").removeClass("boleta");

    $(".radio-envio-pos #no").prop("checked", true);
    $(".radio-envio-pos").hide();
    $(".enviar-c").removeClass("activar-envio-sunat");
  }
  let datos = { idSerie: codigo };
  // $.ajax({
  //   method: "POST",
  //   url: "ajax/pos.ajax.php",
  //   data: datos,
  //   beforeSend: function () {},
  //   success: function (data) {
  //     console.log(data);
  //     $("#serie").html(data);
  //   },
  // });
});

$(document).on("click", ".herramientas_pos", function (e) {
  var codigo = $(this).val();
  if (codigo == "01") {
    $(".seccion-fechas-moneda-correlativo").toggle();
    $(".seccion-coreo-cuotas").hide();
    $(".seccion-observacion").hide();
    $(".seccion-sucursal").hide();
    $(".seccion-detra-selva").hide();
  }
  if (codigo == "02") {
    $(".seccion-coreo-cuotas").toggle();
    $(".seccion-fechas-moneda-correlativo").hide();
    $(".seccion-observacion").hide();
    $(".seccion-sucursal").hide();
    $(".seccion-detra-selva").hide();
  }
  if (codigo == "03") {
    $(".seccion-observacion").toggle();
    $(".seccion-coreo-cuotas").hide();
    $(".seccion-fechas-moneda-correlativo").hide();
    $(".seccion-sucursal").hide();
    $(".seccion-detra-selva").hide();
  }

  if (codigo == "04") {
    $(".seccion-sucursal").toggle();
    $(".seccion-observacion").hide();
    $(".seccion-coreo-cuotas").hide();
    $(".seccion-fechas-moneda-correlativo").hide();
    $(".seccion-detra-selva").hide();
  }
  
  if (codigo == "05") {
    $(".seccion-detra-selva").toggle();
    $(".seccion-detra-s").hide();
    $("..seccion-sucursal").hide();
    $(".seccion-observacion").hide();
    $(".seccion-coreo-cuotas").hide();
    $(".seccion-fechas-moneda-correlativo").hide();
  }
});

$(document).on("keyup", "#descuentoGlobalpos", function () {
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalPpos").val(0);
  }
  LoadDescuentoPos();
});
$(document).on("change", "#descuentoGlobalpos", function () {
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalPpos").val(0);
  }
  LoadDescuentoPos();
});
$(document).on("keyup", "#descuentoGlobalPpos", function () {
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalpos").val(0);
  }
  LoadDescuentoPos();
});
$(document).on("change", "#descuentoGlobalPpos", function () {
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalpos").val(0);
  }
  LoadDescuentoPos();
});

$("#sol").on("click", function () {
  $("#por").addClass("off");
  $("#por").removeClass("on");
  $("#sol").removeClass("off");
  $("#sol").addClass("on");
  $("#descuentoGlobalpos").show();
  $("#descuentoGlobalPpos").hide();
  $(".ico-desc").html("");
  $(".ico-desc").addClass("fa-money");
});
$("#por").on("click", function () {
  $("#sol").removeClass("on");
  $("#sol").addClass("off");
  $("#por").removeClass("off");
  $("#por").addClass("on-por");
  $("#descuentoGlobalpos").hide();
  $("#descuentoGlobalPpos").show();
  $(".ico-desc").html("%");
  $(".ico-desc").removeClass("fa-money");
});

// $("#formVentaPos").on("change", ".cantidaditempos", function () {
//   agregarProductosCarrito();
//   // LoadDescuento();
//   vuelto();
// });
// ELIMINAR ITEM DEL CARRO
$("#formVentaPos").on("click", "button.btnEliminarItemCarroPos", function () {
  let idEliminarCarro = $(this).attr("itemEliminar");
  let descuentoGlobal = $("#descuentoGlobalpos").val();
  let descuentoGlobalP = $("#descuentoGlobalPpos").val();
  let tipo_desc = $("input[name=tipo_desc]:checked").val();
  let moneda = $("#monedaPos").val();
  var idSucursal = $("#idcSucursal").val();
  //let cantidad = $("#cantidad"+idProducto).val();
  let tipo_cambio = $("#tipo_cambio").val();
  let datos = {
    idEliminarCarroPos: idEliminarCarro,
    moneda: moneda,
    descuentoGlobal: descuentoGlobal,
    descuentoGlobalP: descuentoGlobalP,
    tipo_desc: tipo_desc,
    tipo_cambio: tipo_cambio,
    idSucursal: idSucursal,
  };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".id-eliminar" + idEliminarCarro).fadeOut(500, function () {
        $(".nuevoProducto table #itemsP").html(respuesta);
        LoadDescuentoPos();
      });
      $("#totalOperacion").val("");
      vuelto();
      loadDetraccion()
    },
  });
});

$(document).on("click", ".btn-agregar-ps-pos", function (e) {
  var idProducto = $(this).attr("idImagen");
  $("#idProducto").val(idProducto);
  var idSucursal = $("#idcSucursal").val();
  var cantidadItem = $("#cantidad" + idProducto).val();
  var cantidadItempos = $("#cantidaditempos" + idProducto).val();

  if (cantidadItem == 0) {
    var cantidad = $("#cantidadPos").val();
  } else {
    var cantidad = cantidadItem;
  }
  if (cantidadItempos == null) {
    cantidadItempos = cantidadItem;
  } else {
    cantidadItempos = Number(cantidadItempos) + Number(cantidadItem);
  }
  console.log(cantidadItempos);
  $("#cantidad" + idProducto).val(cantidad);
  $("#cantidadPos").val(cantidad);
  $("#idProducto").val(idProducto);
  $("#editar_item").val("");
  var datos = { idProServ: idProducto, idSucursal: idSucursal };
  let datos2 = { idProducto: idProducto };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos2,
    dataType: "json",
    beforeSend: function () {},
    success: function (respuesta) {
      let stockProducto = respuesta["stock"];

      if (cantidadItempos > Number(stockProducto)) {
        $("#cantidaditempos" + idProducto).val(stockProducto);
        // $("#btnAgregarPos" + idProducto).addClass("deshabilitar");
        // $(".btnGuardarVentaPos").attr("disabled", true);
        $("#imagenPos" + idProducto)
          .parent()
          .children("#btnAgregarPos" + idProducto)
          .after(`<div class="sin-stock">SIN STOCK</div>`);

        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "warning",
          title: `<h4>El stock de este producto llegó a su límite</h4>`,
        });
        // loadCarritoPos();
      } else {
        $(".btnGuardarVentaPos").attr("disabled", false);
        $.ajax({
          method: "POST",
          url: "ajax/pos.ajax.php",
          data: datos,
          dataType: "json",
          success: function (data) {
            $(".descripcion-de-items").html(data["descripcion"]);
            $("#tipoigvLineaPos").val(data["codigoafectacion"]);
            $("#precioUnitarioLineaPos").val(data["precio_unitario"]);
            $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
            $("#igvLineaPos").val(data["igv"]);
            $(".precio_normal").val(data["precio_unitario"]);
            $(".precio_pormayor").val(data["precio_pormayor"]);
            $("#btnPrecioNormal").html(
              "PRECIO NORMAL </br>" + data["precio_unitario"]
            );
            $("#btnPrecioporMayor").html(
              "PRECIO POR MAYOR </br>" + data["precio_pormayor"]
            );

            agregarProductosCarrito();
          },
        });
      }
    },
  });
});
$(document).on("mouseenter", ".btn-agregar-ps-pos", function (e) {
  let idProducto = $(this).attr("idImagen");
  $("#btnAgregarPos" + idProducto).hide();
  $("#btnAgregarPos" + idProducto)
    .addClass("ver-canasta")
    .fadeIn(400);

  $("#btnAgregarPos" + idProducto).removeClass("ocultar-canasta");
});
$(document).on("mouseleave", ".btn-agregar-ps-pos", function (e) {
  let idProducto = $(this).attr("idImagen");
  $("#btnAgregarPos" + idProducto).removeClass("ver-canasta");
  $("#btnAgregarPos" + idProducto)
    .addClass("ocultar-canasta")
    .fadeIn();
});

// EDITAR CANTIDAD DEL ITEM DEL CARRO
$(document).on("change", ".formVenta .cantidaditempos", function () {
  let idproductoServicio = $(this).attr("idproductoServicio");
  let cantidaditem = $("#cantidaditempos" + idproductoServicio).val();
  var idSucursal = $("#idcSucursal").val();
  // console.log(cantidaditem);
  let datos2 = { idProducto: idproductoServicio };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos2,
    dataType: "json",
    beforeSend: function () {},
    success: function (respuesta) {
      let stockProducto = respuesta["stock"];

      if (cantidaditem > Number(stockProducto)) {
        $("#cantidaditempos" + idproductoServicio).val(stockProducto);
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "warning",
          title: `<h4>El stock de este producto llegó a su límite</h4>`,
        });
      } else {
        var datos = {
          idproductoServicio: idproductoServicio,
          cantidaditem: cantidaditem,
          idSucursal: idSucursal,
        };
        $.ajax({
          method: "POST",
          url: "ajax/pos.ajax.php",
          data: datos,
          success: function (respuesta) {
            // console.log(respuesta);
            $(".nuevoProducto table #itemsP").html(respuesta);

            LoadDescuentoPos();
            vuelto();
            loadDetraccion()
          },
        });
      }
    },
  });
});

// CARGAR EL DESCUENTO
function LoadDescuentoPos() {
  let descontar = "descontar";
  let descuentoGlobal = $("#descuentoGlobalpos").val();
  let descuentoGlobalP = $("#descuentoGlobalPpos").val();
  let moneda = $("#monedaPos").val();
  let tipo_cambio = $("#tipo_cambio").val();
  let tipo_desc = $("input[name=tipo_desc]:checked").val();
  var idSucursal = $("#idcSucursal").val();
  if (
    $("#descuentoGlobalpos").val() == "" ||
    $("#descuentoGlobalPpos").val() == ""
  ) {
    descuentoGlobal = 0;
    descuentoGlobalP = 0;
  }

  let datos = {
    descuentoGlobal: descuentoGlobal,
    descuentoGlobalP: descuentoGlobalP,
    descontar: descontar,
    moneda: moneda,
    tipo_desc: tipo_desc,
    tipo_cambio: tipo_cambio,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    //dataType: "json",
    success: function (respuesta) {
      $(".nuevoProducto table #itemsP").html(respuesta);
      vuelto();
      loadDetraccion()
    },
  });
}

function informacionModal(icono, texto, codhtml, tiempo) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    // width: 600,
    // padding: '3em',
    showConfirmButton: false,
    timer: tiempo,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: icono,
    title: `<h5>${texto}</h5>`,
    html: codhtml,
  });
}
// =====================================================
//AGREGAR PRODUCTOS AL CARRO CON ESCANER
$(document).on("change", "#searchpcPos", function (e) {
  var codigobarra = $(this).val();
  var afectoigv = $("#afectoigv").val();
  var idSucursal = $("#idcSucursal").val();
  let datos = { codigobarra: codigobarra, idSucursal: idSucursal };
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: datos,
    dataType: "json",
    success: function (data) {
      // console.log(data);
      if (data && codigobarra.length > 5 && $("#searchpcPos").val() != "") {
        var idProducto = data["id"];
        let descripcionProducto = data["descripcion"];
        let descuentoGlobal = $("#descuentoGlobalpos").val();
        let descuentoGlobalP = $("#descuentoGlobalPpos").val();
        let tipo_desc = $("input[name=tipo_desc]:checked").val();
        let moneda = $("#monedaPos").val();
        let cantidad = 1;
        let descuento_item = 0;
        let precio_unitario = data["precio_unitario"];
        let valor_unitario = data["valor_unitario"];

        let icbper = 0;
        let tipo_cambio = $("#tipo_cambio").val();
        if (afectoigv == "s") {
          var tipo_afectacion = data["codigoafectacion"];
          var igv = data["igv"];
        } else {
          var tipo_afectacion = 20;
          var igv = 0;
        }
        let escaner = "escaner";
        ////(idProducto);
        let datos = {
          idProducto: idProducto,
          descuentoGlobal: descuentoGlobal,
          cantidad: cantidad,
          moneda: moneda,
          tipo_desc: tipo_desc,
          descuentoGlobalP: descuentoGlobalP,
          tipo_cambio: tipo_cambio,
          descuento_item: descuento_item,
          tipo_afectacion: tipo_afectacion,
          precio_unitario: precio_unitario,
          valor_unitario: valor_unitario,
          igv: igv,
          icbper: icbper,
          escanear: escaner,
          idSucursal: idSucursal,
        };

        $.ajax({
          method: "POST",
          url: "ajax/pos.ajax.php",
          data: datos,
          success: function (respuesta) {
            console.log(respuesta);
            $(".nuevoProducto table #itemsP").html(respuesta);
            $(".super-contenedor-precios").hide();

            const Toast = Swal.mixin({
              toast: true,
              position: "top-start",
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
            vuelto();
            loadDetraccion()
          },
        });
        $("#searchpcPos").val("");
        $("#searchpcPos").focus();
      } else {
        $("#searchpcPos").val("");
        $("#searchpcPos").focus();
        Swal.fire({
          position: "top-end",
          icon: "warning",
          title: `<h3>EL PRODUCTO O SERVICIO NO SE ENCUENTRA</h3>`,
          html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-shopping-cart"></i> 0</div`,
          showConfirmButton: false,
          timer: 2500,
        });
      }
    },
  });
});
/*================================================================
    GUARDAR VENTA
    ===================================================================*/
$(".btnGuardarVentaPos").on("click", function () {
  $("#searchpcPos").focus();

  // let dataForm = $("#formVentaPos").serialize();
  var idSucursal = $("#idcSucursal").val();
  var valor_sucursal = $("#valor_sucursal").val();
  var dataForm = new FormData(document.getElementById("formVentaPos"));
  dataForm.append("idSucursal", idSucursal);
  dataForm.append("valor_sucursal", valor_sucursal);

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
        url: "./ajax/pos.ajax.php",
        type: "POST",
        data: dataForm,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          // console.log(respuesta);
          // loadCarrito();
          backup();
          Swal.fire({
            title: "",
            text: "¡Gracias!",
            icon: "success",
            html: '<div id="successCO"></div><div id="successemail"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });
          $(".reload-all").fadeOut(50);
          loadCarritoPos();
          $("#successCO").html(respuesta);
          $("#rucActivo").hide();

          enviarReporteVenta();
          loadComrobantesNoEnviados();
        },
      });
    }
  });
});

$(".inicio-pos").click(function (e) {
  window.location = "inicio";
});

$(document).on("click", ".btn-h-pos", function (e) {
  if ($("#btnMenuHerramientas").is(":checked")) {
    $(".btn-h-pos i").prop("class", "fa fa-cog");
    $(".btn-h-pos i").css("padding-left", "7px");
    $(".contenedor-herramientas-pos").css("transform", "translateX(100%)");
  } else {
    $(".btn-h-pos i").prop("class", "fas fa-times");
    $(".btn-h-pos i").css("padding-left", "10px");
  }
});

$(document).on("change", ".envio-sunat-pos", function (e) {
  let respuesta = $(this).val();

  if (respuesta == "enviar") {
    $(".enviar-c").addClass("activar-envio-sunat");
    $(".firmar-c").removeClass("activar-envio-sunat");
  } else {
    $(".firmar-c").addClass("activar-envio-sunat");
    $(".enviar-c").removeClass("activar-envio-sunat");
  }
});
$(".btn-menup ").click(function (e) {
  e.preventDefault();
});

$(document).on("click", "body", function (e) {
  e.stopPropagation();

  $(".contenedor-herramientas-pos").css("transform", "translateX(100%)");
  $(".btn-h-pos i").prop("class", "fa fa-cog");
  $(".btn-h-pos i").css("padding-left", "7px");
  $("#btnMenuHerramientas").prop("checked", false);
  $(".contenedor-print-comprobantes .tasks-menu").removeClass('open');
});

$(document).on("click", ".contenedor-herramientas-pos", function (event) {
  event.stopPropagation();
  // $("#btnMenuHerramientas").prop("checked", true);
});
$(document).on("click", "#btnMenuHerramientas, .btn-h-pos", function (event) {
  event.stopPropagation();
  if ($("#btnMenuHerramientas").is(":checked")) {
    $(".contenedor-herramientas-pos").css("transform", "translateX(0%)");
    $(".btn-h-pos i").prop("class", "fa fa-times");
    $(".btn-h-pos i").css("padding-left", "10px");
  } else {
    $(".contenedor-herramientas-pos").css("transform", "translateX(100%)");
    $(".btn-h-pos i").prop("class", "fa fa-cog");
    $(".btn-h-pos i").css("padding-left", "7px");
  }
});

$(document).on("change", ".preciounitariopos", function (e) {
  var idproductoServicio = $(this).attr("idproductoServicio");
  let precio_unitario = $(this).val();
  $("#editar_item").val("edita");
  var idSucursal = $("#idcSucursal").val();
  let cantidad = $("#cantidaditempos" + idproductoServicio).val();
  let editar_item = $("#editar_item").val();
  datos = {
    idproductoPS: idproductoServicio,
    precio_unitario: precio_unitario,
    cantidaditem: cantidad,
    editar_item: editar_item,
    idSucursal: idSucursal,
  };

  $.ajax({
    method: "POST",
    url: "ajax/pos.ajax.php",
    data: datos,
    success: function (respuesta) {
      console.log(respuesta);
      $(".nuevoProducto table #itemsP").html(respuesta);

      vuelto();
      loadDetraccion()
      $(".preciounitariopos" + idproductoServicio).focus();
    },
  });
});
