// $(document).ready(function() {

// LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
function loadProductosV(page) {
  let searchProductoV = $("#searchProductoV").val();
  let selectnum = $("#selectnum").val();
  let categorias = $("#categorias").val();
  let selectSucursal = $("#idcSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchProductoV: searchProductoV,
    selectnum: selectnum,
    categorias: categorias,
    dpv: "dpv",
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
      $(".reloadc").hide();
      $(".body-productos-ventas").html(data);
      $(".tablaVentas .super-contenedor-precios").hide();
    },
  });
}
loadProductosV(1);
$(document).on("click", ".btn-agregar-carrito", function (e) {
  $(".contenedor-items").fadeIn(5);
  $(".tablaVentas thead").fadeIn(5);
  $(".contenedor-productos-ventas").fadeIn(5);
  $(".pagination").fadeIn(5);
  $("#modalProductosVenta .modal-body .row").fadeIn(5);
  loadProductosV(1);
  cerrarSession();
});

//AGREGAR PRODUCTOS AL CARRO CON ESCANER
$(document).on("change", "#searchpc", function (e) {
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

      if (data && codigobarra.length > 5 && $("#searchpc").val() != "") {
        var idProducto = data["id"];
        let descripcionProducto = data["descripcion"];
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let tipo_desc = $("input[name=tipo_desc]:checked").val();
        let moneda = $("#moneda").val();

        let cantidad = 1;
        let descuento_item = 0;
        let precio_unitario = data["precio_unitario"];
        let valor_unitario = data["valor_unitario"];

        let icbper = $(".icbper" + idProducto).val();
        let tipo_cambio = $("#tipo_cambio").val();
        if (afectoigv == "s") {
          var tipo_afectacion = data["codigoafectacion"];
          var igv = data["igv"];
        } else {
          var tipo_afectacion = 20;
          var igv = 0;
        }
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
          idSucursal: idSucursal,
        };

        $.ajax({
          method: "POST",
          url: "ajax/ventas.ajax.php",
          data: datos,
          success: function (respuesta) {
            $(".nuevoProducto table #itemsP").html(respuesta);
            $(".super-contenedor-precios").hide();

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
            vuelto();
            loadDetraccion();
          },
        });
        $("#searchpc").val("");
        $("#searchpc").focus();
      } else {
        $("#searchpc").val("");
        $("#searchpc").focus();
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

// AGREGAR PRODUCTOS AL CARRO
$(document).on("click", ".agregarProducto", function () {
  var descripcionProducto = $(this).attr("descripcionP");
  var idProducto = $(this).attr("idProducto");
  var countStock = $(".stock" + idProducto).attr("stock");
  var ruta = $('#ruta_comprobante').val();
  
  // console.log(countStock);
  var datos = { idProducto: idProducto };
  $.ajax({
    method: "POST",
    url: "ajax/productos.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (respuestas) {
      let stockProducto = respuestas["stock"];
      let totalStock = countStock - 1;
      if (totalStock <= 20) {
        $(".stock" + idProducto)
          .removeClass("btn-primary")
          .addClass("btn-dangerstock");
      }
      if (totalStock <= 0) {
        $(".stock" + idProducto).html(0);
        $(".stock" + idProducto).attr("stock", 0);
        $(".stock" + idProducto)
          .removeClass("btn-primary")
          .addClass("btn-dangerstock");
      } else {
        $(".stock" + idProducto).html(totalStock);
        $(".stock" + idProducto).attr("stock", totalStock);
      }
      if(ruta != 'crear-cotizacion'){
      if (Number(totalStock) >= 0 && respuestas["stock"] > 0) {
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let tipo_desc = $("input[name=tipo_desc]:checked").val();
        let moneda = $("#moneda").val();
        let cantidad = $("#cantidad" + idProducto).val();
        let descuento_item = $(".descuento_item" + idProducto).val();
        let tipo_afectacion = $(".tipo_afectacion" + idProducto).val();
        let precio_unitario = $(".precio_unitario" + idProducto).val();
        let valor_unitario = $(".valor_unitario" + idProducto).val();
        let igv = $(".igv" + idProducto).val();
        let icbper = $(".icbper" + idProducto).val();
        let tipo_cambio = $("#tipo_cambio").val();
        var idSucursal = $("#idcSucursal").val();

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
          idSucursal: idSucursal,
        };

        $.ajax({
          method: "POST",
          url: "ajax/ventas.ajax.php",
          data: datos,
          success: function (respuesta) {
            $(".nuevoProducto table #itemsP").html(respuesta);
            $(".super-contenedor-precios").hide();

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
            vuelto();
            loadDetraccion();
          },
        });
      } else {
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
    }else{
      let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let tipo_desc = $("input[name=tipo_desc]:checked").val();
        let moneda = $("#moneda").val();
        let cantidad = $("#cantidad" + idProducto).val();
        let descuento_item = $(".descuento_item" + idProducto).val();
        let tipo_afectacion = $(".tipo_afectacion" + idProducto).val();
        let precio_unitario = $(".precio_unitario" + idProducto).val();
        let valor_unitario = $(".valor_unitario" + idProducto).val();
        let igv = $(".igv" + idProducto).val();
        let icbper = $(".icbper" + idProducto).val();
        let tipo_cambio = $("#tipo_cambio").val();
        var idSucursal = $("#idcSucursal").val();

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
          idSucursal: idSucursal,
        };

        $.ajax({
          method: "POST",
          url: "ajax/ventas.ajax.php",
          data: datos,
          success: function (respuesta) {
            $(".nuevoProducto table #itemsP").html(respuesta);
            $(".super-contenedor-precios").hide();

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
            vuelto();
            loadDetraccion();
          },
        });
    }
    },
  });
});

// ELIMINAR TODOS LOS ITEMS DEL CARRO
$(".formVenta").on("click", "button.btnEliminarCarro", function () {
  let eliminarCarro = "eliminarCarro";
  let datos = { eliminarCarro: eliminarCarro };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProducto table #itemsP").html("");
      $(".totales").html("");
      $(".totales").html(`       
                <tr class="op-subt">
                <td>SubTotal</td><td>0.00</td>
                </tr>
                <tr class="op-gravadas">
                <td>Op.Gravadas</td><td>0.00</td>
                </tr>
                <tr class="op-exoneradas">
                <td>Op.Exoneradas</td><td>0.00</td>
                </tr>
                <tr class="op-inafectas">
                <td>Op.Inafectas</td><td>0.00</td>
                </tr>   
                <tr class="op-gratuitas">
                <td>Op.gratuitas</td><td>0.00</td>
                </tr>         
                <tr class="op-descuento">
                <td>Descuento</td><td>0.00</td>
                </tr>
                <tr class="icbper">
                <td>ICBPER</td><td>0.00</td>
                </tr>
                <tr class="op-igv">
                <td>IGV(18%)</td><td>0.00</td>
                </tr>
                
                <tr class="op-total">
                <td>Total</td><td>0.00</td>
                </tr>
                
                
                `);
      $(".op-subt").hide();
      $(".op-gravadas").hide();
      $(".op-exoneradas").hide();
      $(".op-inafectas").hide();
      $("#totalOperacion").val("");
      $("#monto_pagado").val("");
      $("#searchpc").focus();
      $("#searchpcPos").focus();
      vuelto();
      loadDetraccion();
    },
  });
});

// ELIMINAR ITEM DEL CARRO
$(".formVenta").on("click", "button.btnEliminarItemCarro", function () {
  let idEliminarCarro = $(this).attr("itemEliminar");
  let descuentoGlobal = $("#descuentoGlobal").val();
  let descuentoGlobalP = $("#descuentoGlobalP").val();
  let tipo_desc = $("input[name=tipo_desc]:checked").val();
  let moneda = $("#moneda").val();
  //let cantidad = $("#cantidad"+idProducto).val();
  let tipo_cambio = $("#tipo_cambio").val();
  var idSucursal = $("#idcSucursal").val();
  let datos = {
    idEliminarCarro: idEliminarCarro,
    moneda: moneda,
    descuentoGlobal: descuentoGlobal,
    descuentoGlobalP: descuentoGlobalP,
    tipo_desc: tipo_desc,
    tipo_cambio: tipo_cambio,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".id-eliminar" + idEliminarCarro).fadeOut(500, function () {
        $(".nuevoProducto table #itemsP").html(respuesta);

        LoadDescuento();
      });
      $("#totalOperacion").val("");
      vuelto();
      loadDetraccion();
    },
  });
});
// EDITAR CANTIDAD DEL ITEM DEL CARRO
$(document).on("change", ".formVenta .cantidaditem", function () {
  let idproductoServicio = $(this).attr("idproductoServicio");
  var idSucursal = $("#idcSucursal").val();
  let cantidaditem = $("#cantidaditem" + idproductoServicio).val();

  validarStock(idproductoServicio, cantidaditem);

  let datos = {
    idproductoServicio: idproductoServicio,
    cantidaditem: cantidaditem,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
      $(".nuevoProducto table #itemsP").html(respuesta);

      // LoadDescuento();
      vuelto();
      loadDetraccion();
    },
  });
});

$(".formVenta").on("blur", ".cantidaditem", function () {
  let idproductoServicio = $(this).attr("idproductoServicio");
  var idSucursal = $("#idcSucursal").val();

  let cantidaditem = $("#cantidaditem" + idproductoServicio).val();

  let datos = {
    idproductoServicio: idproductoServicio,
    cantidaditem: cantidaditem,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
      $(".nuevoProducto table #itemsP").html(respuesta);

      // LoadDescuento();
      vuelto();
      loadDetraccion();
    },
  });
});

//CARGAR CARRO
function loadCarrito() {
  let loadCarrito = "loadCarrito";
  let moneda = $("#moneda").val();
  let tipo_cambio = $("#tipo_cambio").val();
  let descuentoGlobal = $("#descuentoGlobal").val();
  let descuentoGlobalP = $("#descuentoGlobalP").val();
  var idSucursal = $("#idcSucursal").val();
  let datos = {
    loadCarrito: loadCarrito,
    moneda: moneda,
    tipo_cambio: tipo_cambio,
    descuentoGlobal: descuentoGlobal,
    idSucursal: idSucursal,
  };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProducto table #itemsP").html(respuesta);
      vuelto();
      loadDetraccion();
    },
  });
}
// CARGAR CARRO
loadCarrito();

// SOLO INGRESAR NUMEROS CAMPO RUC-DNI
// $('#docIdentidad').keyup(function() {
//     var ruc = $(this).val();

//     //this.value = (this.value + '').replace(/[^0-9]/g, '');
//     if(!$.isNumeric(ruc)) {
//         //dni = dni.substr(0,(dni.length -1));
//         ruc = ruc.replace(/[^0-9]/g, '');
//         $('#docIdentidad').val(ruc);
//     }

// });

/*================================================================
    GUARDAR VENTA
    ===================================================================*/
$(".btnGuardarVenta").on("click", function () {
  $("#searchpc").focus();
  //let guardarVenta = "guardarVenta";
  var docIdentidad = $("#docIdentidad").val();
  let numcuotas = $("#numcuotas").val();
  let tipopago = $("#tipopago").val();
  if (tipopago == "Credito") {
    let fechac = $("#fecha_cuota").val();

    let cuotac = $("#cuotas").val();
    if (fechac == "") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "¡Debes ingresar todas las fechas para las cuotas!",
        //footer: '<a href>Why do I have this issue?</a>'
      });
      exit();
    }
    if (cuotac == "") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "¡Debes ingresar todos los montos!",
        //footer: '<a href>Why do I have this issue?</a>'
      });
      exit();
    }
    for (let i = 2; i <= numcuotas; i++) {
      let fecha = $("#fecha_cuota" + i).val();
      let cuota = $("#cuotas" + i).val();
      if (fecha == "") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "¡Debes ingresar todas las fechas para las cuotas!",
          //footer: '<a href>Why do I have this issue?</a>'
        });
        exit();
      }
      if (cuota == "") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "¡Debes ingresar todos los montos!",
          //footer: '<a href>Why do I have this issue?</a>'
        });
        exit();
      }
    }
  }

  // let dataForm = $("#formVenta").serialize();
  var idSucursal = $("#id_sucursal").val();
  var valor_sucursal = $("#valor_sucursal").val();
  // var idAlmacen = $("#idcSucursal").val();
  var dataForm = new FormData(document.getElementById("formVenta"));
  dataForm.append("idSucursal", idSucursal);
  dataForm.append("valor_sucursal", valor_sucursal);
  // dataForm.append("idAlmacen", idAlmacen);
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
        url: "./ajax/ventas.ajax.php",
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
          loadCarrito();
          $("#successCO").html(respuesta);
          $("#rucActivo").hide();

          enviarReporteVenta();
          loadComrobantesNoEnviados();
        },
      });
    }
  });
});
$(document).on("click", "#printA4", function (e) {
  document.getElementById("a4").checked = true;
});
$(document).on("click", "#printT", function (e) {
  document.getElementById("tk").checked = true;
});

//   REENVIAR REPORTES AL CORREO===========================
function enviarReporteVenta() {
  let enviarporemail = $(".modoemail:checked").val();
  let idCo = $("#idCo").val();
  let sendemail = $("#email").val();
  var ruta = $("#ruta_comprobante").val();
  let datos = { idCo: idCo, sendemail: sendemail };

  if (enviarporemail == "s") {
    if (ruta == "crear-cotizacion") {
      var urle = "vistas/print/sendCotizacion.php";
    } else {
      var urle = "vistas/print/send.php";
    }
    $.ajax({
      method: "POST",
      url: urle,
      data: datos,
      beforeSend: function () {
        $("#successemail")
          .fadeIn(20)
          .html("<img src='vistas/img/reload.svg' width='60px'> ");
      },
      success: function (enviarReport) {
        console.log(enviarReport);
        if (enviarReport == "ok") {
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
            icon: "success",
            title: `<h5>COMPROBANTE ENVIADO A: ${sendemail}</h5>`,
          });
          //    $("#successemail").html("ENVIADO CORRECTAMENTE AL CORREO: " + sendemail);
          $(".modo-contenedor-email #si").prop("checked", false);
          $(".modo-contenedor-email #no").prop("checked", "checked");
          $("#email").val("");
          loadEmaiChange();
        } else {
        }
      },
    });
  }
}
//DESCUENTO GLOBAL
// $(document).on('keyup change', "#descuentoGlobal,#descuentoGlobalP", function(){
//     let descontar = "descontar";
//     let descuentoGlobal = $("#descuentoGlobal").val();
//     let descuentoGlobalP = $("#descuentoGlobalP").val();
//     let moneda = $("#moneda").val();
//     let tipo_desc = $('input[name=tipo_desc]:checked').val();
//     let tipo_cambio = $("#tipo_cambio").val();
//     let datos = {"descuentoGlobal":descuentoGlobal, "descontar":descontar, "moneda":moneda, "tipo_desc":tipo_desc, "descuentoGlobalP":descuentoGlobalP, "tipo_cambio":tipo_cambio};
//     $.ajax({
//         method: "POST",
//         url: "ajax/ventas.ajax.php",
//         data: datos,
//         //dataType: "json",
//         success: function(respuesta){

//             $('.nuevoProducto table #itemsP').html(respuesta);

//         }
//     })
// });
$(document).on("keyup", "#descuentoGlobal", function () {
  LoadDescuento();
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalP").val(0);
  }
});
$(document).on("change", "#descuentoGlobal", function () {
  LoadDescuento();
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobalP").val(0);
  }
});
$(document).on("keyup", "#descuentoGlobalP", function () {
  LoadDescuento();
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobal").val(0);
  }
});
$(document).on("change", "#descuentoGlobalP", function () {
  LoadDescuento();
  let valor = $(this).val();
  if (valor == 0) {
    $("#descuentoGlobal").val(0);
  }
});
// CARGAR EL DESCUENTO
function LoadDescuento() {
  let descontar = "descontar";
  let descuentoGlobal = $("#descuentoGlobal").val();
  let descuentoGlobalP = $("#descuentoGlobalP").val();
  let moneda = $("#moneda").val();
  let tipo_cambio = $("#tipo_cambio").val();
  let tipo_desc = $("input[name=tipo_desc]:checked").val();
  var idSucursal = $("#idcSucursal").val();
  if ($("#descuentoGlobal").val() == "" || $("#descuentoGlobalP").val() == "") {
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
    url: "ajax/ventas.ajax.php",
    data: datos,
    //dataType: "json",
    success: function (respuesta) {
      $(".nuevoProducto table #itemsP").html(respuesta);
      vuelto();
      loadDetraccion();
    },
  });
}
// LISTAR VENTAS BOLETAS FACTURAS
function loadVentas(page) {
  let searchVentas = $("#searchVentas").val();
  let selectnum = $("#selectnum").val();
  let fechaInicial = $("#fechaInicial").val();
  let fechaFinal = $("#fechaFinal").val();
  let selectSucursal = $("#selectSucursal").val();
  let fechanoe = $("#fechanoenviados").val();
  let noenviados = $("#noenvi").val();

  let parametros = {
    action: "ajax",
    page: page,
    searchVentas: searchVentas,
    selectnum: selectnum,
    dv: "dv",
    fechaInicial: fechaInicial,
    fechaFinal: fechaFinal,
    selectSucursal: selectSucursal,
    fechanoe: fechanoe,
    noenviados: noenviados,
  };

  $.ajax({
    url: "vistas/tables/dataTables.php",
    method: 'GET',
    data: parametros,
    // cache: false,
    // contentType: false,
    // processData: false,
    
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      // console.log(data);
      $(".reload-all").hide();
    
      $(".body-ventas").html(data);
     
    }, error:function(data){
       var error500 = data.status;
       return error500;
    }
  });
};


loadVentas(1);

  



$("#sol").on("click", function () {
  $("#por").addClass("off");
  $("#por").removeClass("on");
  $("#sol").removeClass("off");
  $("#sol").addClass("on");
  $("#descuentoGlobal").show();
  $("#descuentoGlobalP").hide();
  $(".ico-desc").html("");
  $(".ico-desc").addClass("fa-money");
});
$("#por").on("click", function () {
  $("#sol").removeClass("on");
  $("#sol").addClass("off");
  $("#por").removeClass("off");
  $("#por").addClass("on-por");
  $("#descuentoGlobal").hide();
  $("#descuentoGlobalP").show();
  $(".ico-desc").html("%");
  $(".ico-desc").removeClass("fa-money");
});

function tipoCambio() {
  let fecha = $("#fecha").val();
  let dato = { tipo_cambio: "tipo_cambio", fecha: fecha };
  // console.log(fecha);
  $.ajax({
    url: "Controladores/tipo-cambio.php",
    method: "POST",
    data: dato,
    dataType: "json",
    success: function (datos) {
      // console.log(datos)
      $("#tipo_cambio").val(datos["venta"]);
      $("#tipocambio").html(
        "TC - Venta: " + datos["venta"] + " Compra: " + datos["compra"]
      );
    },
  });
}
tipoCambio();

$("#moneda").change(function () {
  loadCarrito();
});

$(".tablaVentas").on("click", ".printA4", function (e) {
  let idComp = $(this).attr("idComp");

  $(".a4" + idComp).prop("checked", true);
  $(".tk" + idComp).prop("checked", false);
});
$(".tablaVentas").on("click", ".printT", function (e) {
  let idComp = $(this).attr("idComp");
  $(".tk" + idComp).prop("checked", true);
  $(".a4" + idComp).prop("checked", false);
});

//IMPRIMIR TICKET RÁPIDO
$(document).on("click", ".btnTicket", function (e) {
  let idVenta = $("#idCo").val();
  let datos = { idVenta: idVenta };
  let urlc = "vistas/print/printPos.php";

  $.ajax({
    method: "POST",
    url: urlc,
    data: datos,

    beforeSend: function () {},
    success: function (respuesta) {
      // console.log(respuesta);

      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
      myHeaders.append("Cookie", "PHPSESSID=1jftjr16pe38dcche116fpmj8c");
      var token = "838d56d106314d55c395bc779769bae7";

      var urlencoded = new URLSearchParams();
      urlencoded.append("token", "838d56d106314d55c395bc779769bae7");
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: respuesta,
        redirect: "follow",
      };
      fetch("http://localhost/aPrintPos/print/", requestOptions)
        // fetch("http://localhost/impresiontermica/print/", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
    },
  });
});

//Date range as a button
// BUSCAR POR FECHAS
// ======================================
$("#daterange-btn").daterangepicker(
  {
    locale: {
      format: "YYYY-MM-DD",
      separator: " - ",
      applyLabel: "Guardar",
      cancelLabel: "Cancelar",
      fromLabel: "Desde",
      toLabel: "Hasta",
      customRangeLabel: "Personalizar",
      daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      monthNames: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Setiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],
    },

    ranges: {
      Hoy: [moment(), moment()],
      Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Últimos 7 Díass": [moment().subtract(6, "days"), moment()],
      "Últimos 30 Días": [moment().subtract(29, "days"), moment()],
      "Este mes": [moment().startOf("month"), moment().endOf("month")],
      "Último mes": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },

    startDate: moment(),
    endDate: moment(),
  },
  function (start, end) {
    $("#daterange-btn span").html(
      start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
    );
    let fechaInicial = start.format("YYYY-MM-DD");
    let fechaFinal = end.format("YYYY-MM-DD");

    $("#fechaInicial").val(fechaInicial);
    $("#fechaFinal").val(fechaFinal);
    loadVentas(1);
    loadGuiasR(1);
    loadCotizaciones(1);
  }
);
$(".daterangepicker .ranges li").on("click", function () {
  let fechaHoy = $(".daterangepicker .active").val();

  $("#fechaInicial").val(fechaHoy);
  $("#fechaFinal").val(fechaHoy);

  loadVentas(1);
  loadGuiasR(1);
});
// FIN BUSCAR POR FECHAS
// ======================================
// })
// $(".btn-agregar-carrito").on('click', function(){
//     $.ajax({
//         url : "vistas/modulos/table-productos.php",
//         success : function(respuesta){
//             $("#productosCarrito").html(respuesta);
//                 loadProductosV(1);
//     }
//     })

//     // $("#productosCarrito").load('vistas/modulos/table-productos.php');

// })
function loadComrobantesNoEnviados() {
  let noEnviados = "noEnviados";
  let datos = { noEnviados: noEnviados };
  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    //dataType: "json",
    success: function (respuesta) {
      if (respuesta > 1) {
        $(".no-enviados").html(respuesta);
        $(".no-enviados-text").html(
          "Tienes <b>" + respuesta + "</b> comprobantes no enviados"
        );
        $(".no-enviados-items").html("No se olvide");
      } else if (respuesta == 1) {
        $(".no-enviados").html(respuesta);
        $(".no-enviados-text").html(
          "Tienes <b>" + respuesta + "</b> comprobante no enviado"
        );
        $(".no-enviados-items").html("No se olvide");
      } else {
        $(".no-enviados").html(respuesta);
        $(".no-enviados-text").html("No tienes comprobantes pendientes");
        $(".no-enviados-items").html("Todo está muy bien");
      }
    },
  });
}
loadComrobantesNoEnviados();

//ICEBEPER===============================================================================================
$(document).on("click", ".btn-icb-si", function () {
  let id = $(this).attr("idProducto");
  let modo = $(this).attr("val");
  let cantidad = $("#cantidad" + id).val();
  let icbper = $("#icbper").val();

  if (modo == "s") {
    $("#s" + id).addClass("icbsi");
    $("#s" + id).html("Sí");
    $("#n" + id).html("||");
    $("#s" + id).removeClass("alterno");
    $("#n" + id).addClass("alterno");

    $(".icbper" + id).val(icbper * cantidad);
    $(".modo-icbper" + id).val("s");
  }
});

$(document).on("click", ".btn-icb-no", function () {
  let id = $(this).attr("idProducto");
  let modo = $(this).attr("val");

  if (modo == "n") {
    $("#n" + id).addClass("icbno");
    $("#n" + id).html("No");
    $("#s" + id).html("||");
    $("#s" + id).addClass("alterno");
    $("#n" + id).removeClass("alterno");

    $(".icbper" + id).val("");
    $(".modo-icbper" + id).val("n");
  }
});

$(document).on("click", ".anular-nota", function () {
  let idventa = $(this).attr("idComp");
  let datos = { idventa: idventa };
  Swal.fire({
    title: "¿Estás seguro en anular el comprobante?",
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
        url: "ajax/ventas.ajax.php",
        data: datos,
        beforeSend: function () {},
        success: function (datos) {
          if (datos == "ok") {
            Swal.fire({
              icon: "success",
              title: "Ok...",
              text: "¡El comprobante ha sido anulado!",
              //footer: '<a href>Why do I have this issue?</a>'
            });
            loadReportes(1);
          }
        },
      });
    }
  });
});
function roundToTwo(num) {
  return +(Math.round(num + "e+2") + "e-2");
}
$("#monto_pagado").on("keyup", function (e) {
  vuelto();
});
function vuelto() {
  let montopagado = $("#monto_pagado").val();
  let totales = $("#totalOperacion").val();

  if (totales > 0 && $("#monto_pagado").val() != "") {
    let vuelto = montopagado - totales;
    $("#total_vuelto").val(roundToTwo(vuelto));
  } else {
    $("#total_vuelto").val("");
  }
}
$(document).on("click", ".swal2-actions swal2-cancel", function (e) {
  e.preventDefault();
  $("#searchpc").focus();
  $("#searchpcPos").focus();
});
$(document).on("click", ".swal2-container", function (e) {
  $("#searchpc").focus();
  $("#searchpcPos").focus();
});

$(document).on("change", ".preciounitarioco", function (e) {
  var idproductoServicio = $(this).attr("idproductoServicio");
  let precio_unitario = $(this).val();
  var idSucursal = $("#idcSucursal").val();
  let editar_item = "edita";
  let cantidad = $("#cantidaditem" + idproductoServicio).val();
  datos = {
    idproductoPS: idproductoServicio,
    precio_unitariocomp: precio_unitario,
    cantidaditem: cantidad,
    editar_item: editar_item,
    idSucursal: idSucursal,
  };

  $.ajax({
    method: "POST",
    url: "ajax/ventas.ajax.php",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
      $(".nuevoProducto table #itemsP").html(respuesta);

      vuelto();
      loadDetraccion();
    },
  });
});

function noEnviadosd() {
  var contar = parseInt($(".no-enviados").text());

  if (contar > 0) {
    // $(".no-enviados").addClass("fake");
    $(".no-enviados").hide();
    $(".no-enviados").addClass("rojo");
    $(".no-enviados").fadeIn(500, function () {
      $(this).fadeOut(500);
    });
    $(".btn-co-no-enviados").show(1);
  } else {
    $(".no-enviados").show();
    $(".no-enviados").removeClass("rojo");
    $(".btn-co-no-enviados").hide(1);
  }
}

setInterval(noEnviadosd, 1000);

$(document).on("click", ".tipo_desc", function () {
  var tipo_desc = $("input[name=tipo_desc]:checked").val();

  console.log(tipo_desc);
  if (tipo_desc == "%") {
    $(".descicono").removeClass("fa-money-bill-wave");
    $(".descicono").addClass("fa-percent");
  } else {
    $(".descicono").removeClass("fa-percent");
    $(".descicono").addClass("fa-money-bill-wave");
  }
});

// CARGA AL CARRITO DESDE VENTAS -BOLETAS

// window.addEventListener("load", function () {
//   let rutaComprobante = $("#ruta_comprobante").val();
//   if (rutaComprobante == "ventas") {
//    numNoEnviados();

//   }
// });

function detraccionesBienesSelva() {
  $.ajax({
    url: "vistas/modulos/contenedor-detra-selva.php",
    success: function (respuesta) {
  $(".load-detracciones-servicios-selva").html(respuesta);
  $(".contenedor-detracciones").fadeOut(200);
}
})
  }
detraccionesBienesSelva();

$(document).on("change", "#detraccion", function () {
  var detraccion = $("input[name=detraccion]:checked").val();
  if (detraccion == "si") {
    $(".contenedor-detracciones").fadeIn(200);
  } else {
    $(".contenedor-detracciones").fadeOut(200);
    $("#tipodetraccion").val(null).trigger('change');    
    $("#tipo_pago_detraccion").val(null).trigger('change');    
    $("#cuentadetraccion").val(null).trigger('change');
    $("#pordetraccion").val("");
    $("#totaldetraccion").val("");
  }
});

$(document).on("change", "#tipodetraccion", function () {
  var coddetraccion = $(this).val();
  let totales = $("#totalOperacion").val();
  var datos = { coddetraccion: coddetraccion, totales: totales };

  $.ajax({
    method: "POST",
    url: "ajax/sunat.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      $("#pordetraccion").val(respuesta["porcentaje"]);
      $("#totaldetraccion").val(respuesta["totaldetraccion"]);
    },
  });
});

function loadDetraccion() {
  var detraccion = $("input[name=detraccion]:checked").val();
if(detraccion == 'si') {
 
  var coddetraccion = $("#tipodetraccion").val();
  let totales = $("#totalOperacion").val();
  var datos = { coddetraccion: coddetraccion, totales: totales };

  $.ajax({
    method: "POST",
    url: "ajax/sunat.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $("#pordetraccion").val(respuesta["porcentaje"]);
      $("#totaldetraccion").val(respuesta["totaldetraccion"]);
    },
  });
}
}

$(document).on('keyup', '#pordetraccion', function(){
  let porcentajeDetraccion = $(this).val();
  let totales = $("#totalOperacion").val();
  var datos = { porcentajeDetraccion: porcentajeDetraccion, totales: totales };

  $.ajax({
    method: "POST",
    url: "ajax/sunat.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      // $("#pordetraccion").val(respuesta["porcentaje"]);
      $("#totaldetraccion").val(respuesta["totaldetraccion"]);
    },
  });
})