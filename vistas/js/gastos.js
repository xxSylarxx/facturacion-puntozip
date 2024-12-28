$(document).on("click", "#guardarGasto", function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  let datos = $("#formGastos").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/gasto.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      console.log(respuesta);
      if (respuesta == "ok") {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
            $("#modalGastos").modal("hide");
            $("#formGastos input").val("");
            $("#descripciongasto").val("");
            loadGastos(1);
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡Gasto ingresado con éxito!!</h4>`,
          html: ``,
        });
      }
    },
  });
});

function loadGastos(page) {
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
  let searchR = $("#searchGastos").val();
  let selectnum = $("#selectnum").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchR: searchR,
    selectnum: selectnum,
    gastosdt: "gastosdt",
    fechaini: fechaini,
    fechafin: fechafin,
  };

  $.ajax({
    url: "vistas/tables/dataTablesGastos.php",
    // method: 'GET',
    data: parametros,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      $(".reload-all").fadeOut(50);
      $(".body-gastos").html(data);
    },
  });
}
loadGastos(1);

$(document).on("click", ".btn-eliminar-gasto", function (e) {
  let idgastodelete = $(this).attr("idGasto");
  let datos = { idgastodelete: idgastodelete };
  $.ajax({
    method: "POST",
    url: "ajax/gasto.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      if (respuesta == "ok") {
        $(".iddelete" + idgastodelete).fadeOut(500);
      }
    },
  });
});
