$(".table-cajas").load("vistas/modulos/table-cajas.php");
$(document).on("click", "#guardarCaja", function (e) {
  e.preventDefault();
  let datos = $("#formCaja").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    success: function (respuesta) {
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
            $("#modalAgregarCaja").modal("hide");
            $("#formCaja input").val("");
            $(".table-cajas").load("vistas/modulos/table-cajas.php");
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡Caja ingresada con éxito!!</h4>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("click", "#editarCaja", function (e) {
  e.preventDefault();
  let datos = $("#formEditarCaja").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
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
            $("#modalEditarCaja").modal("hide");
            $("#formEditarCaja input").val("");
            $(".table-cajas").load("vistas/modulos/table-cajas.php");
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡Caja editada con éxito!!</h4>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("click", ".btnEditarCaja", function (e) {
  e.preventDefault();
  let idCaja = $(this).attr("idCaja");
  let datos = { idCaja: idCaja };

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#idCajae").val(respuesta["id"]);
        $("#editarnombre").val(respuesta["nombre"]);
        $("#editarnumero").val(respuesta["numero_caja"]);
        $("#cajaactiva").val(respuesta["activo"]);
      }
    },
  });
});

//=================================================================================
//ARQUEO DE CAJAS===================================================
$(document).on("click", "#guardarAperturaCaja", function (e) {
  e.preventDefault();
  let datos = $("#formAperturaCaja").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    success: function (respuesta) {
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
            $("#modalAperturaCaja").modal("hide");
            $("#formAperturaCaja input").val("");
            loadArqueoCajas(1);
            window.location = "arqueo-caja";
          },
        });
        Toast.fire({
          icon: "success",
          title: `<h4>¡Caja aperturada con éxito!!</h4>`,
          html: ``,
        });
      } else {
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
            //   $("#modalAperturaCaja").modal("hide");
            $("#formAperturaCaja #montoapertura").val("");
            $("#formAperturaCaja select").val("");
            $(".table-cajas").load("vistas/modulos/table-cajas.php");
          },
        });
        Toast.fire({
          icon: "error",
          title: `<h4>${respuesta}</h4>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("change", "#cajaid", function (e) {
  let idcajaabierta = $(this).val();

  let datos = { idcajaabierta: idcajaabierta };

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
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
            $("#modalAperturaCaja").modal("hide");
            $("#formAperturaCaja #montoapertura").val("");
            $("#formAperturaCaja select").val("");
            loadArqueoCajas(1);
          },
        });
        Toast.fire({
          icon: "error",
          title: `<h4>LA CAJA YA ESTÁ APERTURADA</h4>`,
          html: ``,
        });
      }
    },
  });
});

function loadArqueoCajas(page) {
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
  let searchR = $("#idproducto").val();
  let selectnum = $("#selectnum").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchR: searchR,
    selectnum: selectnum,
    acajas: "acajas",
    fechaini: fechaini,
    fechafin: fechafin,
  };

  $.ajax({
    url: "vistas/tables/dataTablesArqueoCajas.php",
    // method: 'GET',
    data: parametros,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (data) {
      $(".reload-all").fadeOut(50);
      $(".body-arqueocajas").html(data);
    },
  });
}
loadArqueoCajas(1);

$(document).on("click", ".btn-datos-cierre", function (e) {
  let idCierreCaja = $(this).attr("idArqueo");
  let datos = { idCierreCaja: idCierreCaja };

  $.ajax({
    method: "POST",
    url: "ajax/caja.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      //   console.log(respuesta);
      $("#idCajaCierre").val(respuesta["id"]);
      $("#numcaja").val(respuesta["caja"]);
      $("#monto_inicial").val(respuesta["monto_inicial"]);
      $("#monto_final").val(respuesta["monto_final"]);
      $("#monto_ventas").val(respuesta["monto_ventas"]);
      $("#total_ventas").val(respuesta["total_ventas"]);
      $("#egresos").val(respuesta["egresos"]);
      $("#gastos").val(respuesta["gastos"]);
      $("#nombreu").val(respuesta["usuario"]);
      $("#idusuariocaja").val(respuesta["id_usuario"]);
    },
  });
});

$(document).on("click", "#cerrarCaja", function (e) {
  e.preventDefault();
  let datos = $("#formCierreCaja").serialize();
  Swal.fire({
    title: "¿Estás seguro de cerrar caja?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, cerrar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        method: "POST",
        url: "ajax/caja.ajax.php",
        data: datos,
        success: function (respuesta) {
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
                $("#modalCierreCaja").modal("hide");
                $("#formCierreCaja input").val("");
                loadArqueoCajas(1);
                window.location = "arqueo-caja";
              },
            });
            Toast.fire({
              icon: "success",
              title: `<h4>¡Caja cerrada con éxito!</h4>`,
              html: ``,
            });
          } else {
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
                //   $("#modalAperturaCaja").modal("hide");
                $("#formAperturaCaja #montoapertura").val("");
                $("#formAperturaCaja select").val("");
              },
            });
            Toast.fire({
              icon: "error",
              title: `<h4>${respuesta}</h4>`,
              html: ``,
            });
          }
        },
      });
    }
  });
});

window.addEventListener("load", function () {
  let rutaComprobante = $("#ruta_comprobante").val();
  let caja = $("#abrirCaja").val();
  let cajaAC = $("#cajaAC").val();
  if (caja == "n") {
    return false;
  }

  if (caja == "s" && cajaAC == "1") {
  } else {
    if (
      rutaComprobante == "crear-factura" ||
      rutaComprobante == "crear-boleta" ||
      rutaComprobante == "crear-nota" ||
      rutaComprobante == "pos"
    ) {
      $(".panel-medio")
        .first()
        .html("")
        .html(
          `
        <div style="text-align: center;" class="alert alert-informe">PARA PODER HACER VENTAS DEBE APERTURAR CAJA<br><i class="fas fa-cash-register fa-lg"></i><br/> <a class="link-danger" href="arqueo-caja">Ir a apertura</a></div>
    `
        );
    }
    if (rutaComprobante == "pos") {
      $(".super-contenedor")
        .first()
        .html("")
        .html(
          `
        <div style="text-align: center;" class=" alert-informe">PARA PODER HACER VENTAS DEBE APERTURAR CAJA<br><i class="fas fa-cash-register fa-lg"></i><br/> <a class="link-danger" href="arqueo-caja">Ir a apertura</a></div>
    `
        );
      $(".main-footer").hide();
    }
  }
});
