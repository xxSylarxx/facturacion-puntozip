$(".p-productos").hide();
$("#formCompra").on("change", "#tipoComprobante", function () {
  let tipoComprobante = $(this).val();
  //(tipoComprobante);
  let data = { tipoComprobante: tipoComprobante };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: data,
    beforeSend: function () {},
    success: function (respuesta) {
      $(".contenedor-notascd").html(respuesta);
    },
  });
});

$("#formItems").on("click", "#btnAddItem", function (e) {
  e.preventDefault();
  // let dataForm = $("#formItems").serialize();
  var idSucursal = $("#id_sucursal").val();
  var moneda = $("#moneda").val();
  var tipo_cambio = $("#tipo_cambio").val();
  var dataForm = new FormData(document.getElementById("formItems"));
  dataForm.append("idSucursal", idSucursal);
  dataForm.append("moneda", moneda);
  dataForm.append("tipo_cambio", tipo_cambio);
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: dataForm,
    contentType: false,
    processData: false,
    beforeSend: function () {},
    success: function (respuesta) {
      //(respuesta);
      $(".nuevoProductoC table #itemsP").html(respuesta);
      $("#formItems").each(function () {
        this.reset();
      });
      $("#formItems #descripcion").focus();
    },
  });
});
// ELIMINAR ITEM DEL CARRO
$(".formCompra").on("click", "button.btnEliminarItemCarroC", function () {
  let idEliminarCarroC = $(this).attr("itemEliminar");
  //(idEliminarCarroC);
  let datos = { idEliminarCarroC: idEliminarCarroC };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".id-eliminar" + idEliminarCarroC).fadeOut(500, function () {
        $(".nuevoProductoC table #itemsP").html(respuesta);
      });
      //(respuesta);
    },
  });
});
// ELIMINAR TODOS LOS ITEMS DEL CARRO
$(".formCompra").on("click", "button.btnEliminarCarro", function () {
  let eliminarCarro = "eliminarCarro";
  let datos = { eliminarCarro: eliminarCarro };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProductoC table #itemsP").html("");
      $(".totales").html(`
            <tr class="">
            <td>SubTotal</td><td><input type="text" class="" name="subtotalc" id="subtotalc" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.Gravadas</td><td><input type="text" class="" name="op_gravadas" id="op_gravadas" value="0.00" /></td></tr>
             </tr>
            <tr class="">
            <td>Op.Exoneradas</td><td><input type="text" class="" name="op_exoneradas" id="op_exoneradas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.Inafectas</td><td><input type="text" class="" name="op_inafectas" id="op_inafectas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.gratuitas</td><td><input type="text" class="" name="op_gratuitas" id="op_gratuitas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Descuento (-)</td><td><input type="text" class="" name="descuento" id="descuento"value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>ICBPER</td><td><input type="text" class="" name="icbper" id="icbper" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>IGV(18%)</td><td><input type="text" class="" name="igvc" id="igvc" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Total</td><td><input type="text" class="" name="totalc" id="totalc" value="0.00" /></td></tr>
            </tr>
            
            
            `);
    },
  });
});
$(".formCompra").on("keyup", "#descuentoGlobalC", function (e) {
  let descuentoGlobalC = $(this).val();
  let data = { descuentoGlobalC: descuentoGlobalC, descontarG: "descontarG" };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: data,
    beforeSend: function () {},
    success: function (respuesta) {
      //(data);
      $(".nuevoProductoC table #itemsP").html(respuesta);
    },
  });
});
$(".formCompra").on("click", ".btnGuardarCompra", function (e) {
  e.preventDefault();
  // let dataForm = $("#formCompra").serialize();
  var idSucursal = $("#id_sucursal").val();
  var dataForm = new FormData(document.getElementById("formCompra"));
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
        url: "ajax/compras.ajax.php",
        data: dataForm,
        contentType: false,
        processData: false,
        beforeSend: function () {},
        success: function (respuesta) {
          console.log(respuesta);
          Swal.fire({
            title: "",
            text: "¡Gracias!",
            icon: "success",
            html: '<div id="successCompra"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });
          $("#successCompra").html(respuesta);
        },
      });
    }
  });
});
$("#formItems").on("change", "#codigo", function () {
  let codigo = $(this).val();
  // //(codigo);
});

$(".tabla-reportes").on("click", ".btn-anular-compra", function () {
  let idCompra = $(this).attr("idCompra");
  var activo = $("#active").attr("idP");
  let datos = { idCompra: idCompra };
  Swal.fire({
    title: "¿Estás seguro de anular este comprobante?",
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
        url: "ajax/compras.ajax.php",
        data: datos,
        beforeSend: function () {},
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta == "ok") {
            Swal.fire({
              title: "",
              text: "¡Comproante anulado corrréctamete!",
              icon: "success",
              showCancelButton: true,
              showConfirmButton: false,
              allowOutsideClick: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              cancelButtonText: "Cerrar",
            });
            loadReportesCompras(activo);
          } else {
            Swal.fire({
              title: "",
              text: "¡No se ha podido anular el comprobante!",
              icon: "error",
              showCancelButton: true,
              showConfirmButton: false,
              allowOutsideClick: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              cancelButtonText: "Cerrar",
            });
          }
        },
      });
    }
  });
});
// IMPRIMIR EN MODAL
$(".tabla-reportes").on("click", ".btn-print-compra", function (e) {
  let idCompra = $(this).attr("idCompra");
  let datos = { idCompra: idCompra };
  $.ajax({
    method: "POST",
    url: "vistas/print/printCompra.php",
    data: datos,

    beforeSend: function () {},
    success: function (respuesta) {
      $(".printerhere").html(respuesta);
    },
  });
});

$("#formItems").on("keyup", "#descripcion", function (e) {
  let buscarP = $(this).val();
  var idSucursal = $("#id_sucursal").val();
  let datos = {
    buscarP: buscarP,
    buscarProducto: "buscarProducto",
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      $("#formItems .p-productos").fadeIn(300).html(respuesta);
    },
  });
});
$(document).on("click", "#formItems .btn-add-item", function (e) {
  e.preventDefault();
  let idProducto = $(this).attr("idProducto");
  var idSucursal = $("#id_sucursal").val();
  let datos = { idProducto: idProducto, idSucursal:idSucursal };
  $.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (respuesta) {
      $("#formItems #idProductoc").val(respuesta["id"]);
      $("#formItems #descripcion").val(respuesta["descripcion"]);
      $("#formItems #codigo").val(respuesta["codigo"]);
      $("#formItems #precio_unitario").val(respuesta["precio_unitario"]);
      $("#formItems #valor_unitario").val(respuesta["valor_unitario"]);
      changePriceCompra(respuesta["codigo"]);
      $(".p-productos").hide();
    },
  });
});

function loadReportesCompras(page) {
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
  let tipocomp = $(".comp:checked").val();
  let searchRC = $("#searchReportes").val();
  let selectnum = $("#selectnum").val();
  let selectSucursal = $("#selectSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchRC: searchRC,
    selectnum: selectnum,
    reportesCompras: "reportesCompras",
    tipocomp: tipocomp,
    fechaini: fechaini,
    fechafin: fechafin,
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTablesReportesCompras.php",
    // method: 'GET',
    data: parametros,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      $(".reload-all").fadeOut(50);
      $(".body-reporte-compras").html(data);
    },
  });
}
loadReportesCompras(1);
$(document).on("click", ".btn-reporte-pdf-compras", function () {
  let tipocomp = $(".comp:checked").val();
  let fechaInicial = $("#fechaInicial").val();
  let fechaFinal = $("#fechaFinal").val();
  let selectSucursal = $("#selectSucursal").val();
  let datos = {
    fechaInicial: fechaInicial,
    fechaFinal: fechaFinal,
    tipocomp: tipocomp,
    selectSucursal: selectSucursal,
  };
  $.ajax({
    method: "POST",
    url: "vistas/print/reportescompras/index.php",
    data: datos,
    success: function (respuesta) {
      $(".printerhere").html(respuesta);
    },
  });
});

