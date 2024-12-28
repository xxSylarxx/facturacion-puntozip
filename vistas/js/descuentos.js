function changePrice(idProducto) {
  // let idProducto = $('.number').attr('idProducto');
  let cantidad = $("#cantidad" + idProducto).val();
  let valor_unitario = $(".valor_unitario" + idProducto).val();
  let precio_unitario = $(".precio_unitario" + idProducto).val();
  let descuento_item = $(".descuento_item" + idProducto).val();
  let tipo_afectacion = $(".tipo_afectacion" + idProducto).val();
  let modoIcbper = $(".modo-icbper" + idProducto).val();
  let datos = {
    idProducto: idProducto,
    cantidad: cantidad,
    valor_unitario: valor_unitario,
    precio_unitario: precio_unitario,
    descuento_item: descuento_item,
    tipo_afectacion: tipo_afectacion,
    modoIcbper: modoIcbper,
  };

  $.ajax({
    method: "POST",
    url: "ajax/descuentos_items.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $(".valor_unitario" + idProducto).val(data["valor_unitario"]);
      $(".subtotal" + idProducto).val(data["subtotal"]);
      $(".igv" + idProducto).val(data["igv"]);
      $(".total" + idProducto).val(data["total"]);
      $(".icbper" + idProducto).val(data["icbper"]);
    },
  });
}
function changePriceItem(idProducto) {
  // let idProducto = $('.number').attr('idProducto');
  let cantidad = $("#cantidadPos").val();
  let valor_unitario = $("#valorUnitarioLineaPos").val();
  let precio_unitario = $("#precioUnitarioLineaPos").val();
  let descuento_item = $("#descuentoLineaPos").val();
  let tipo_afectacion = $("#tipoigvLineaPos").val();
  let modoIcbper = $("#modo_icbper").val();
  let datos = {
    idProducto: idProducto,
    cantidad: cantidad,
    valor_unitario: valor_unitario,
    precio_unitario: precio_unitario,
    descuento_item: descuento_item,
    tipo_afectacion: tipo_afectacion,
    modoIcbper: modoIcbper,
  };

  $.ajax({
    method: "POST",
    url: "ajax/descuentos_items.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      $("#valorUnitarioLineaPos").val(data["valor_unitario"]);
      $("#subTotalLineaPos").val(data["subtotal"]);
      $("#igvLineaPos").val(data["igv"]);
      $("#totalLineaPos").val(data["total"]);
      $("#icbperLineaPos").val(data["icbper"]);
    },
  });
}
$(document).on("keyup", ".number", function () {
  let idProducto = $(this).attr("idProducto");
  changePrice(idProducto);
});
$(document).on("change", ".number", function () {
  let idProducto = $(this).attr("idProducto");
  changePrice(idProducto);
});
$(document).on("keyup", "#descuento_item", function () {
  let idProducto = $(this).attr("idProducto");
  changePrice(idProducto);
});
$(document).on("change", "#tipoAfectacion", function () {
  let idProducto = $(this).attr("idProducto");
  changePrice(idProducto);
});
$(document).on("keyup", "#precio_unitario", function () {
  let idProducto = $(this).attr("idProducto");
  changePrice(idProducto);
});
$(document).on("click", ".vermasProductos", function (e) {
  
  let idProducto = $(this).attr("idProducto");
  $(".super-cont-precios" + idProducto).fadeIn(5);
  $(".contenedor-items").fadeOut(5);
  $(".tablaVentas thead").fadeOut(5);
  $(".contenedor-productos-ventas").fadeOut(5);
  $(".pagination").fadeOut(5);
  $("#modalProductosVenta .modal-body .row").fadeOut(5);
});
$(document).on("click", ".closeModalProductos", function (e) {
   
  let idProducto = $(this).attr("idProducto");
  $(".super-cont-precios" + idProducto).fadeOut(5);
  $(".contenedor-items").fadeIn(5);
  $(".tablaVentas thead").fadeIn(5);
  $(".contenedor-productos-ventas").fadeIn(5);
  $(".pagination").fadeIn(5);
  $("#modalProductosVenta .modal-body .row").fadeIn(5);

});

function changePriceCompra(codigoProduct) {
  // let codigoProduct = $('.number').attr('codigoProduct');
  let cantidad = $("#cantidad").val();
  let valor_unitario = $("#valor_unitario").val();
  let precio_unitario = $("#precio_unitario").val();
  let descuento_item = $("#descuento_item").val();
  let tipo_afectacion = $("#tipo_afectacion").val();
  let modoIcbper = $("#icbper").val();
  let datos = {
    codigoProduct: codigoProduct,
    cantidad: cantidad,
    valor_unitario: valor_unitario,
    precio_unitario: precio_unitario,
    tipo_afectacion: tipo_afectacion,
    descuento_item: descuento_item,
    modoIcbper: modoIcbper,
  };

  $.ajax({
    method: "POST",
    url: "ajax/redondeos-compras.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      //    $("#precio_unitario").val(data['precio_unitario']);
      $("#valor_unitario").val(data["valor_unitario"]);
      $("#subtotal").val(data["subtotal"]);
      $("#igv").val(data["igv"]);
      $("#total").val(data["total"]);
      //    $(".icbper"+idProducto).val(data['icbper']);
    },
  });
}
// $("#formItems").on('keyup', '#valor_unitario', function(){
//     let codigoProduct = $("#codigo").val();
//     //(codigoProduct);
//     changePriceCompra(codigoProduct);
// })
$("#formItems").on("keyup", "#precio_unitario", function () {
  let codigoProduct = $("#codigo").val();
  //(codigoProduct);
  changePriceCompra(codigoProduct);
});
$("#formItems").on("keyup", "#cantidad", function () {
  let codigoProduct = $("#codigo").val();
  //(codigoProduct);
  changePriceCompra(codigoProduct);
});
$("#formItems").on("change", "#cantidad", function () {
  let codigoProduct = $("#codigo").val();
  //(codigoProduct);
  changePriceCompra(codigoProduct);
});
$("#formItems").on("change", "#tipo_afectacion", function () {
  let codigoProduct = $("#codigo").val();
  //(codigoProduct);
  changePriceCompra(codigoProduct);
});
$("#formItems").on("keyup", "#descuento_item", function () {
  let codigoProduct = $("#codigo").val();
  //(codigoProduct);
  changePriceCompra(codigoProduct);
});

$(document).on("click", "#btnPrecioNormal", function (e) {
  let idProducto = $(this).attr("idProducto");
  //   let preciopormayor = $('#btnPrecioporMayor').attr("precioMayor");
  let precioNormal = $(".precio_normal" + idProducto).val();
  console.log(precioNormal);
  $(".precio_unitario" + idProducto).val(precioNormal);

  changePrice(idProducto);
});
$(document).on("click", "#btnPrecioporMayor", function (e) {
  let idProducto = $(this).attr("idProducto");
  //   let preciopormayor = $('#btnPrecioporMayor').attr("precioMayor");
  let preciopormayor = $(".precio_pormayor" + idProducto).val();
  console.log(preciopormayor);
  $(".precio_unitario" + idProducto).val(preciopormayor);

  changePrice(idProducto);
});
