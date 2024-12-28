$(document).ready(function () {
  $(".connection").hide();

  // OBTENER EL CORRELATIVO DE LOS COMPROBANTES
  $("#serie").on("change", function () {
    let idSerie = $("#serie").val();
    let datos = { idSerie: idSerie };
    $.ajax({
      url: "ajax/sunat.ajax.php",
      method: "POST",
      data: datos,
      success: function (respuesta) {
        $("#correlativo").val(respuesta);
      },
    });
  });
  // CARGAR EL CORRELATIVO DE LOS COMPROBANTES
  function Correlativo() {
    let idSerie = $("#serie").val();
    let datos = { idSerie: idSerie };
    $.ajax({
      url: "ajax/sunat.ajax.php",
      method: "POST",
      data: datos,
      success: function (respuesta) {
        $("#correlativo").val(respuesta);
      },
    });
  }
  Correlativo();

  // OBTENER SERIE CORRELATIVO
  $(document).on("keyup", "#serieNumero", function () {
    let serieCorrelativo = $("#serieNumero").val();
    let tipoComprobante = $("#tipoComprobante").val();
    let idSucursal = $("#idcSucursal").val();
    let datos = {
      serieCorrelativo: serieCorrelativo,
      tipoComprobante: tipoComprobante,
      idSucursal: idSucursal,
    };
    $.ajax({
      url: "ajax/sunat.ajax.php",
      method: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        if (
          respuesta != false &&
          respuesta != null &&
          serieCorrelativo.length > 3
        ) {
          $(".resultadoSerie").show();
          if (tipoComprobante == "01") {
            $(".resultadoSerie a").html(
              respuesta["serie_correlativo"] + " (FACTURA)"
            );
            $(".resultadoSerie").attr(
              "serieCorrelativo",
              respuesta["serie_correlativo"]
            );
          }
          if (tipoComprobante == "03") {
            $(".resultadoSerie a").html(
              respuesta["serie_correlativo"] + " (BOLETA DE VENTA)"
            );
            $(".resultadoSerie").attr(
              "serieCorrelativo",
              respuesta["serie_correlativo"]
            );
          }
        } else {
          $(".resultadoSerie").hide();
        }
      },
    });
  });
});
// COMPROBAR CONEXIÓN
// function Connection(){
//     let conexion = "conexion";
//     let datos = {"conexion": conexion};
//     $.ajax({
//         url: "ajax/sunat.ajax.php",
//         method: "POST",
//         data : datos,
//         success: function(respuesta){

//             if(respuesta != 1){
//            $(".connection").html(`<div><i class="fas fa-wifi"></i> NO HAY CONEXIÓN</div>`).fadeIn(500);
//            $(".connection").addClass('connsi');
//             }else{
//             $(".connsi").html(`<div><i class="fas fa-wifi"></i> SE HA RESTAURADO SU CONEXIÓN</div>`).fadeIn(500, function(){
//                 $(this).delay(9000).fadeOut(200);

//             });
//             }
//         }
//     })

// }
//  Connection();
function eliminarCarro() {
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
                <td>DESCUENTO (-)</td><td>0.00</td>
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
      $(".op-gratuitas").hide();
      $(".icbper").hide();
      $(".op-exoneradas").hide();
      $(".op-inafectas").hide();
      $("#totalOperacion").val("");
      $("#monto_pagado").val("");
      $("#searchpc").focus();
      $("#searchpcPos").focus();
      vuelto();
    },
  });
}
$(document).on("change", "#idcSucursal", function () {
  let idSucursal = $(this).val();
  let ruta = $("#ruta_comprobante").val();
  $("#id_sucursal").val(idSucursal);
  let tipocomp = $("input:radio[name=tipo_comprobante]:checked").val();
  // console.log(tipocomp);
  let datos = { idSucursal: idSucursal, ruta: ruta, tipocomp: tipocomp };
  $.ajax({
    url: "ajax/sunat.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
      $("#serie").html(respuesta);
    },
  });
  eliminarCarro();
});
