function placeholderProducto() {
  $("#nuevoCodigo").prop("placeholder", "Escanear o generar código");
}
placeholderProducto();
//GENERAR CÓDIGO PRODUCTO
$(".bar-code-btn").on("click", function () {
  let codigobr = "0";
  let datos = { codigobr: codigobr };
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      //    //("respuesta", respuesta);
      if (respuesta) {
        $("#nuevoCodigo").val(respuesta);
      }
    },
  });
});

//VALIDAR CÓDIGO PRODUCTO
$(document).on("change", "#nuevoCodigo, #editarCodigo", function () {
  let codigobarra = $(this).val();
  var idSucursal = $("#nuevaSucursal").val();
  console.log(idSucursal);
  // return false;
  if (codigobarra != "") {
    let datos = { codigoValidar: codigobarra, idSucursal: idSucursal };
    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        //    //("respuesta", respuesta);
        console.log(respuesta);

        if (respuesta) {
          $("#nuevoCodigo").val("");
          $("#nuevaDescripcion").focus();
          $("#editarCodigo").val("");
          $(".modal-body .box-body")
            .parent()
            .before(
              '<div class="alert alert-warning" style="display:none;">Este código  ya existe!</div>'
            );
          $(".alert").show(500, function () {
            $(this)
              .delay(3000)
              .hide(500, function () {
                $(".alert").remove();
              });
          });
        }
      },
    });
  }
});

$(document).on("click", ".btn-nuevo-producto", function (e) {
  e.preventDefault();
  let datos = $("#formProductos").serialize();
  let foto = new FormData($("#formProductos")[0]);

  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: (datos, foto),
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      // $(".reload-all")
      //   .fadeIn(50)
      //   .html("<img src='vistas/img/reload.svg' width='80px'>");
    },
    success: function (respuesta) {
      console.log(respuesta);
      $(".reload-all").fadeOut(50);

      if (respuesta == "ok") {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 5500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "success",
          title: `<h5>AGREGADO CORRÉCTAMENTE</h5>`,
          html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-shopping-cart"></i> SE AGREGÓ EL PRODUCTO O SERVICIO</div`,
        });
        loadProductos(1);
        $("#formProductos").each(function () {
          this.reset();
        });
        $("#agregarSerie").bootstrapToggle("off");
        $("#agregarSerie").prop("checked", false);
        $(".eliminar_item").remove();
      } else {
        $(".respuesta-agregar").html(respuesta);
      }
    },
  });
});
//  GENERAR CÓDIGO DEL PRODUCTO
// $("#nuevaCategoria").change(function() {
//     let idCategoria = $(this).val();
//     let datos = new FormData();
//         datos.append('idCategoria',idCategoria);
//         $.ajax({
//             url: 'ajax/productos.ajax.php',
//             method: 'POST',
//             data: datos,
//             cache: false,
//             contentType: false,
//             processData: false,
//             dataType: 'json',

//             success: function(respuesta){

//                 if(!respuesta){

//                     let nuevoCodigo = idCategoria+'01';
//                     $("#nuevoCodigo").val(nuevoCodigo);

//                 }else{

//                     let nuevoCodigo = (Number(respuesta["codigo"]) + 1);
//                     $("#nuevoCodigo").val(nuevoCodigo);
//                 }

//             }
//         })
// })
// AGREGANDO PRECIO DE VENTA
function changePrecios() {
  let precio_unitario = $("#nuevoPrecioUnitario").val();
  let tipo_afectacion = $("#tipo_afectacion").val();

  let datos = {
    precio_unitario: precio_unitario,
    tipo_afectacion: tipo_afectacion,
  };
  $.ajax({
    url: "ajax/redondeos.ajax.php",
    method: "POST",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $("#nuevoValorUnitario").val(respuesta["valor_unitario"]);
      $("#nuevoigv").val(respuesta["igv_producto"]);
      $("#nuevoPrecioCompra").val(respuesta["precio_compra"]);
    },
  });
}
$("#nuevoPrecioUnitario").on("keyup", function () {
  changePrecios();
});
$("#tipo_afectacion").on("change", function () {
  changePrecios();
});
// AGREGANDO PRECIO DE VENTA EDITAR
function changeEditarPrecios() {
  let precio_unitario = $("#editarPrecioUnitario").val();
  let tipo_afectacion = $("#editarAfectacion").val();
  ////(tipo_afectacion);
  let datos = {
    precio_unitario: precio_unitario,
    tipo_afectacion: tipo_afectacion,
  };
  $.ajax({
    url: "ajax/redondeos.ajax.php",
    method: "POST",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      $("#editarValorUnitario").val(respuesta["valor_unitario"]);
      $("#editarigv").val(respuesta["igv_producto"]);
      $("#editarPrecioCompra").val(respuesta["precio_compra"]);
    },
  });
}
$("#editarPrecioUnitario").on("keyup", function () {
  changeEditarPrecios();
});
$("#editarAfectacion").on("change", function () {
  changeEditarPrecios();
});

// $('.porcentaje').on('ifUnchecked', function (){
//     $("#nuevoPrecioVenta").prop('readonly', false);
//     $("#editarPrecioVenta").prop('readonly', false);
// })
// $('.porcentaje').on('ifChecked', function (){
//     $("#nuevoPrecioVenta").prop('readonly', true);
//     $("#editarPrecioVenta").prop('readonly', true);
// })

// SUBIENDO LA FOTO DEL PRODUCTO
$(".nuevaImagen").change(function () {
  let imagen = this.files[0];

  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".nuevaImagen").val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen debe ser jpeg o png!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaImagen").val("");
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
    });
  }
});
// EDITAR PRODUCTO
$(document).on("click", ".btnEditarProducto", function () {
  let idProducto = $(this).attr("idProducto");
  let id_sucursal = $("#selectSucursal").val();
  // console.log(idProducto, id_sucursal);
  let datos = new FormData();
  datos.append("idProducto", idProducto);
  datos.append("idsucursal", id_sucursal);
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",

    success: function (respuesta) {
      // console.log(respuesta);
      // //(respuesta)
      $("#editarid").val(respuesta["id"]);
      $("#editarCodigo").val(respuesta["codigo"]);
      $("#editarSerie").val(respuesta["serie"]);
      $("#editarDescripcion").val(respuesta["descripcion"]);
      $("#editarCaracteristica").val(respuesta["caracteristica"]);
      $("#editarStock").val(respuesta["stock"]);
      $("#editarPrecioCompra").val(respuesta["precio_compra"]);
      $("#editarPrecioUnitario").val(respuesta["precio_unitario"]);
      $("#editarValorUnitario").val(respuesta["valor_unitario"]);
      $("#editarPrecioMayor").val(respuesta["precio_pormayor"]);
      $("#editarigv").val(respuesta["igv"]);
      $("#editarSucursal").val(respuesta["id_sucursal"]);
      if (respuesta['id_proveedor']) {
        $("#itemEditarProveedor").val(respuesta["id_proveedor"]).trigger('change');
      }
      let combo = document.getElementById("editarSucursal");
      let idubigeo = combo.options[combo.selectedIndex].text;
      $("#select2-editarSucursal-container").html(idubigeo);
      if (respuesta["imagen"] != "") {
        $("#imagenActual").val(respuesta["imagen"]);
        $(".previsualizar").attr("src", respuesta["imagen"]);
      }

      let datosCategoria = new FormData();
      datosCategoria.append("idCategoria", respuesta["id_categoria"]);

      $.ajax({
        url: "ajax/categorias.ajax.php",
        method: "POST",
        data: datosCategoria,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuestac) {
          if (respuestac) {
            $("#editarCategoria").val(respuestac["id"]);
            $("#editarCategoria").html(respuestac["categoria"]);
          } else {
            $("#editarCategoria").val("");
            $("#editarCategoria").html("");
          }
        },
      });
      let datosCodigoAfectacion = new FormData();
      datosCodigoAfectacion.append(
        "codigoAfectacion",
        respuesta["codigoafectacion"]
      );
      $.ajax({
        url: "ajax/sunat.ajax.php",
        method: "POST",
        data: datosCodigoAfectacion,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (afectacion) {
          if (afectacion != false) {
            $("#editarAfectacion").val(afectacion["codigo"]);
          }
        },
      });
      let datosUnidad = new FormData();
      datosUnidad.append("codUnidad", respuesta["codunidad"]);

      $.ajax({
        url: "ajax/sunat.ajax.php",
        method: "POST",
        data: datosUnidad,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (unidad) {
          ////(unidad)
          if (unidad != false) {
            $("#editarUnidadMedida").val(unidad["codigo"]);
          }
        },
      });
    },
  });
});

// ELIMINAR PRODUCTO
$(document).on("click", ".btnEliminarProducto", function () {
  let idProducto = $(this).attr("idProducto");
  let codigo = $(this).attr("codigo");
  let imagen = $(this).attr("imagen");
  var activo = $("#active").attr("idP");
  // DATOS PARA ENIAR POR POST
  let datos = new FormData();
  datos.append("idEliminarProducto", idProducto);
  datos.append("codigo", codigo);
  datos.append("imagen", imagen);
  // datos = {"idProducto":idProducto, "codigo":codigo, "imagen":imagen};
  Swal.fire({
    title: "¿Estás seguro de eliminar este producto?",
    text: "¡Si no lo está puede  cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminarlo!",
  }).then((result) => {
    if (result.isConfirmed) {
      // window.location =
      //   "index.php?ruta=productos&idProducto=" +
      //   idProducto +
      //   "&codigo=" +
      //   codigo +
      //   "&imagen=" +
      //   imagen;
      $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta == "success") {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "El producto o servicio ha sido eliminado",
              showConfirmButton: false,
              timer: 1500,
            });
            $(".contenedorpro-o" + idProducto).fadeOut(1500, function () {
              loadProductos(activo);
            });
          } else {
            Swal.fire({
              position: "top-end",
              icon: "error",
              title: "" + respuesta,
              showConfirmButton: false,
              timer: 5500,
            });
          }
        },
      });
    }
  });
});
// LISTAR PRODUCTOS CON BUSCADOR
let perfilOculto = $("#perfilOculto").val();
function loadProductos(page) {
  let searchProducto = $("#searchProducto").val();
  let selectnum = $("#selectnum").val();
  let activos = $("#activos").val();
  let selectSucursal = $("#selectSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchProducto: searchProducto,
    selectnum: selectnum,
    dp: "dp",
    perfilOculto: perfilOculto,
    activos: activos,
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
      //  $("body").append(loadp);
    },
    success: function (data) {
      $(".reloadp").hide();
      $(".body-productos").html(data);
    },
  });
}

loadProductos(1);

// });

$(document).on("click", ".siu", function () {
  let id = $(this).attr("idm");

  let modo = $(".unidad-me .unidadmedida").val();
  let datos = { idUnidad: id, modo: modo };

  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    beforeSend: function () { },
    success: function (respuesta) {
      if (modo == "s") {
        $(".unidad-me #sie" + id).addClass("unidadsi");
        $(".unidad-me #sie" + id).html("Sí");
        $(".unidad-me #noe" + id).html("||");
        $(".unidad-me #sie" + id).removeClass("alterno2");
        $(".unidad-me #noe" + id).addClass("alterno2");
      }
    },
  });
});

$(document).on("click", ".nou", function () {
  let id = $(this).attr("idm");
  let modo = $(".unidadmedidan").val();
  let datos = { idUnidad: id, modo: modo };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    beforeSend: function () { },
    success: function (respuesta) {
      if (modo == "n") {
        $(".unidad-me #noe" + id).addClass("unidadno");
        $(".unidad-me #noe" + id).html("No");
        $(".unidad-me #sie" + id).html("||");
        $(".unidad-me #sie" + id).addClass("alterno2");
        $(".unidad-me #noe" + id).removeClass("alterno2");
      }
    },
  });
});

// function loadEmaiChange(){
//     let modo = $(".unidadmedida:checked").val();

//      if(modo == "s"){

//         $("#sie").addClass("emailsi");
//         $("#sie").html("Sí");
//         $("#noe").html("||");
//         $("#sie").removeClass("alterno");
//         $("#noe").addClass("alterno");

//         }else{
//             $("#noe").addClass("emailno");
//             $("#noe").html("No");
//             $("#sie").html("||");
//             $("#sie").addClass("alterno");
//             $("#noe").removeClass("alterno");
//             $('.email-colunma').hide();
//         }
//     };

$(document).on("change keyup", ".cantidad-stock", function () {
  let cantidad = $(this).val();
  let idProducto = $(this).attr("idProducto");
  var ruta = $('#ruta_comprobante').val();
  if (ruta != 'crear-cotizacion') {
    let datos = { idProducto: idProducto };
    $.ajax({
      method: "POST",
      url: "ajax/productos.ajax.php",
      data: datos,
      dataType: "json",
      beforeSend: function () { },
      success: function (respuesta) {
        let stockProducto = respuesta["stock"];
        let totalStock = stockProducto - cantidad;
        $(".stock" + idProducto).html(totalStock);

        if (Number(totalStock) <= 20) {
          $(".stock" + idProducto)
            .removeClass("btn-primary")
            .addClass("btn-dangerstock");

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
            title: `<h4>El stock de este producto está llegando a su límite</h4>`,
          });
        } else {
          $(".stock" + idProducto)
            .removeClass("btn-dangerstock")
            .addClass("btn-primary");
        }
        if (Number(totalStock) == 0 || cantidad >= Number(stockProducto)) {
          $("#cantidad" + idProducto).val(stockProducto);
          $(".stock" + idProducto).html(0);

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
        }
        // if(cantidad < 1 || cantidad == ''){
        //     $("#cantidad"+idProducto).val(1);
        // }
      },
    });
  }
});

function validarStock(idProducto, cantidad) {
  let datos = { idProducto: idProducto };

  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () { },
    success: function (respuesta) {
      let stockProducto = respuesta["stock"];

      if (cantidad > Number(stockProducto)) {
        $("#cantidaditem" + idProducto).val(stockProducto);
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

        return false;
      }
    },
  });
}
function validarStockPos(idProducto, cantidad) {
  let datos = { idProducto: idProducto };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () { },
    success: function (respuesta) {
      let stockProducto = respuesta["stock"];

      if (cantidad > Number(stockProducto)) {
        $("#cantidaditempos" + idProducto).val(stockProducto);
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

        return false;
      }
    },
  });
}

// REPONER STOCK ======================
$(document).on("click", ".btn-reponer-stock", function () {
  let idVenta = $(this).attr("idVenta");
});
// $(document).on("keyup", "#nuevoCodigo", function (e) {
//   e.preventDefault();
//   $("#nuevoCodigo").focus();
// });

// document.addEventListener('DOMContentLoaded', () => {
//   document.querySelectorAll('input[type=search]').forEach( node => node.addEventListener('keypress', e => {
//     if(e.keyCode == 13) {
//       e.preventDefault();
//     }
//   }))
// });

$(document).on("click", ".btnCodigoBarra", function (e) {
  e.preventDefault();
  var codigobarra = $(this).attr("codigo");
  var datos = { codigobarra: codigobarra };
  $.ajax({
    method: "POST",
    url: "ajax/barcode.ajax.php",
    data: datos,
    beforeSend: function () { },
    success: function (respuesta) {
      $(".reload-all").hide();
      Swal.fire({
        title: "",
        text: "",
        icon: "",
        html: `<div id="successCO">${respuesta}</div>`,
        showCancelButton: false,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
    },
  });
});

$(document).on("click", ".btn-desactivar", function () {
  var id_productoa = $(this).attr("idproducto");
  var activos = $(this).attr("activar");
  var activo = $("#active").attr("idP");
  if (activos == "n") {
    $("#idp" + id_productoa).attr("activar", "s");
    $("#idp" + id_productoa).removeClass("activarpro");
    $("#idp" + id_productoa).addClass("desactivarpro");
    $("#idp" + id_productoa).html("Inactivo");
  } else {
    $("#idp" + id_productoa).attr("activar", "n");
    $("#idp" + id_productoa).removeClass("desactivarpro");
    $("#idp" + id_productoa).addClass("activarpro");
    $("#idp" + id_productoa).html("Activo");
  }

  console.log(activos + id_productoa);
  let datos = { id_productoa: id_productoa, activos: activos };

  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    success: function (data) {
      $(".contenedorpro-o" + id_productoa).fadeOut(500, function () {
        loadProductos(activo);
      });
    },
  });
});

$(document).on("change", "#selectSucursal", function () {
  let idSucursalpro = $(this).val();

  let datos = { idSucursalpro: idSucursalpro };
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      console.log(respuesta);
      $(".selectidproducto").html(respuesta);
    },
  });
});

$(document).on("change", "#agregarSerie", function () {
  var addserie = $("[name=agregarSerie]:checked").val();
  if (addserie == "si") {
    $(".series-productos").fadeIn(10);
  } else {
    addserie = "no";
    $(".series-productos").fadeOut(10);
    $(".eliminar_item").remove();
  }
});

var plus = 1;
$(document).on("click", ".btn-add-serie", function () {
  plus++;
  $(".series-productos").append(`
    <div class="input-group eliminar_item" id="itemserie${plus}">
    <input type="text" class="form-control" name="seriep[]" id="seriep[]" placeholder="INGRESE LA SERIE">
      <span class="btn btn-danger btn-remove-serie" idre="${plus}" style="position:absolute; right:-37px; top:-0px !important; color:#fff;"><i class="far fa-minus-square "></i></span>
    </div>
    `);
});
var plus2 = 1;
$(document).on("click", ".btn-add-serie-agregar", function () {
  plus2++;
  $(".series-productos-agregar").append(`
    <div class="input-group eliminar_item" id="itemserie${plus2}"  style="margin: 0 auto;">
    <input type="text" class="form-control" name="seriepn[]" id="seriepn[]" placeholder="INGRESE LA SERIE">
      <span class="btn btn-danger btn-remove-serie-agregar" idre="${plus2}" style="position:absolute; right:-40px; top:-0px !important; color:#fff;"><i class="far fa-minus-square "></i></span>
    </div>
    `);
});

$(document).on("click", ".btn-remove-serie", function (e) {
  let num = $(this).attr("idre");

  $(this).parent().remove();
  $("#itemserie" + num).remove();
  plus--;
});
$(document).on("click", ".btn-remove-serie-agregar", function (e) {
  let num = $(this).attr("idre");

  $(this).parent().remove();
  $("#itemserie" + num).remove();
  plus--;
});
$(document).on("click", ".nuevo-producto-s", function (e) {
  $("#agregarSerie").bootstrapToggle("off");
  $("#agregarSerie").prop("checked", false);
  $(".eliminar_item").remove();
});

$(document).on("click", ".btnActualizarSeries", function (e) {
  var idproductoS = $(this).attr("idProducto");
  $(".eliminar_item").remove();
  $("#idproductoS").val(idproductoS);
  var data = { idproductoS: idproductoS };
  $.ajax({
    method: "POST",
    url: "vistas/modulos/productos-series.php",
    data: data,
    success: function (datos) {
      $(".contenido-inputs").html(datos);
      $("#idproductoSnuevo").val(idproductoS);
    },
  });
});

$(document).on("click", ".btn-nueva-serie", function (e) {
  e.preventDefault();
  var idproductoS = $("#idproductoSnuevo").val();
  var data = $("#aSeries").serialize();
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: data,
    success: function (datos) {
      if (datos != 'error') {
        $(".eliminar_item").remove();
        $('#aSeries').each(function () {
          this.reset();
        });
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
          title: `<div style="font-size: 1.5em; color: #2B5DD2;">SERIES INGRESADAS CON ÉXITO</div`,
          html: ``,
        });
        var data = { idproductoS: idproductoS };
        $.ajax({
          method: "POST",
          url: "vistas/modulos/productos-series.php",
          data: data,
          success: function (datos) {
            $(".contenido-inputs").html(datos);
          },
        });
      } else {
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
          icon: "error",
          title: `<div style="font-size: 1.5em; color: #2B5DD2;">LLENE LAS SERIES</div`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("change", "#Seriesdisponibles", function () {
  var idSerie = $(this).attr("idSerie");
  var activos = $(this).val();
  var idproductoS = $(this).attr("idProducto");
  if (activos == "s") {
    var activoserie = "n";
  } else {
    var activoserie = "s";
  }
  var data = { idSerie: idSerie, activoserie: activoserie };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: data,
    success: function (datos) {
      if (datos == 'ok') {
        var data = { idproductoS: idproductoS };
        $.ajax({
          method: "POST",
          url: "vistas/modulos/productos-series.php",
          data: data,
          success: function (datos) {
            $(".contenido-inputs").html(datos);
          },
        });
      }
    },
  });
});


$(document).on("change", "#serieA", function () {
  var idSerie = $(this).attr("idSerie");
  var numserie = $(this).val();

  var data = { idSerie: idSerie, numserie: numserie };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: data,
    success: function (datos) {
      if (datos == 'ok') {
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
          title: `<div style="font-size: 1.5em; color: #2B5DD2;">LA SERIE FUE ACTUALIZADA</div`,
          html: ``,
        });
      }
    },
  });
});

$(document).on('click', '.btn-eliminar-serie-bd', function (e) {
  e.preventDefault();
  var idSerieEliminar = $(this).attr('idSerie');
  var data = { idSerieEliminar: idSerieEliminar };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: data,
    success: function (datos) {
      if (datos == 'ok') {
        $("#eliminar_seccion_serie" + idSerieEliminar).fadeOut(500);
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
          title: `<div style="font-size: 1.5em; color: #2B5DD2;">LA SERIE FUE ELIMINADA CON ÉXITO</div`,
          html: ``,
        });
      }
    }
  })
})


function calcularPesoTotal() {
  let suma = 0;
  $('.tabla-items .input-peso').each(function () {
    let valor = parseFloat($(this).val());
    if (!isNaN(valor)) {
      suma += valor;
    }
  });
  $('#pesoBruto').val(suma);
}

function calcularBultosTotal() {
  let suma = 0;
  $('.tabla-items .input-bultos').each(function () {
    let valor = parseFloat($(this).val());
    if (!isNaN(valor)) {
      suma += valor;
    }
  });
  $('#numeroBultos').val(suma);
}

function actualizarGuiaProductos(idCar, idProducto_update, campo, valor) {
  let data = {
    idCar,
    idProducto_update,
    campo,
    valor
  }
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: data,
    success: function (datos) {
      console.log(datos);
    }
  });
}

$('body').on('keyup change', '.input-peso', function () {
  const idCar = $(this).attr('idcar');
  const idProducto = $(this).attr('cod');
  const valor = $(this).val();
  calcularPesoTotal();
  actualizarGuiaProductos(idCar, idProducto, 'peso', valor);
});

$('body').on('keyup change', '.input-bultos', function () {
  const idCar = $(this).attr('idcar');
  const idProducto = $(this).attr('cod');
  const valor = $(this).val();
  calcularBultosTotal();
  actualizarGuiaProductos(idCar, idProducto, 'bultos', valor);
});

$('body').on('change', '.input-prod', function () {
  const idCar = $(this).attr('idcar');
  const idProducto = $(this).attr('cod');
  const campo = $(this).attr('campo');
  const valor = $(this).val();
  actualizarGuiaProductos(idCar, idProducto, campo, valor);
});

$(document).ready(function () {
  setTimeout(() => {
    calcularPesoTotal();
  }, 200);
});