function loadInventarios(page) {
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
  let searchR = $("#searchInventarios").val();
  let selectnum = $("#selectnum").val();
  let selectSucursal = $("#selectSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchR: searchR,
    selectnum: selectnum,
    dtainventarios: "dtainventarios",
    fechaini: fechaini,
    fechafin: fechafin,
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTablesInventarios.php",
    // method: 'GET',
    data: parametros,
    beforeSend: function () {
      //   $(".reload-all")
      //     .fadeIn(50)
      //     .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      // console.log(data);
      $(".reload-all").fadeOut(50);
      $(".body-inventarios").html(data);
    },
  });
}
loadInventarios(1);

function loadKardex(page) {
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
  let searchR = $("#idproducto").val();
  let selectnum = $("#selectnum").val();
  let selectSucursal = $("#idkSucursal").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchR: searchR,
    selectnum: selectnum,
    dtakardex: "dtakardex",
    fechaini: fechaini,
    fechafin: fechafin,
    selectSucursal: selectSucursal,
  };

  $.ajax({
    url: "vistas/tables/dataTablesInventarios.php",
    // method: 'GET',
    data: parametros,
    beforeSend: function () {
      //   $(".reload-all")
      //     .fadeIn(50)
      //     .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      $(".reload-all").fadeOut(50);
      $(".body-kardex").html(data);
    },
  });
}
// loadKardex(1);

$(document).on("change", "#searchInventarios", function (e) {
  excelReportInventario();
});
$(document).on("change", "#printKardex #idproducto", function (e) {
  excelReportKardex();
});

$(document).on("click", ".btn-procesar-cambio", function (e) {
  e.preventDefault();
  let idproducto = $("#idproducto").val();
  let cantidad = $("#cantidadModificar").val();

  let datos = { idproducto: idproducto, cantidad: cantidad };

  $.ajax({
    method: "POST",
    url: "ajax/inventario.ajax.php",
    data: datos,
    success: function (data) {
      // console.log(data);
      if (data == "ok") {
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
            $("#modalAgregarAjusteInventario").modal("hide");
            $("#idproducto").val("");
            $("#cantidadModificar").val("");
            $("#select2-idproducto-container").html("BUSCAR EL PRODUCTO");
            $("#select2-idproducto-container").attr(
              "title",
              "BUSCAR EL PRODUCTO"
            );
          },
        });

        Toast.fire({
          icon: "success",
          title: `<h4>EL AJUSTE FUE REALIZADO CON ÉXITO!</h4>`,
          html: ``,
        });
      } else {
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
          icon: "warning",
          title: `<h4>INGRESE DATOS CORRÉCTOS!</h4>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("change", "#idkSucursal", function () {
  let id_sucursal = $(this).val();
  console.log(id_sucursal);
  let data = { id_sucursal: id_sucursal };

  url: "ajax/inventario.ajax.php",
    $.ajax({
      type: "POST",
      url: "ajax/inventario.ajax.php",
      data: data,
      success: function (response) {
       $("#idproducto").html(response);
      },
    });
});
