$(".tablaVentas").on("click", "#getcdr1, #getcdr2, #getcdr3", function (e) {
  e.preventDefault();
  let idVenta = $(this).attr("idVenta");
  var activo = $("#active").attr("idP");
  localStorage.setItem("idComprobante", idVenta);
  let datos = { idVenta: idVenta };

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      // console.log(respuesta);
      Swal.fire({
        title: "",
        text: "¡Gracias!",
        icon: "success",
        html: '<div id="successCO"></div>',
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      $(".reload-all").fadeOut(50);
      $("#successCO").html(respuesta);
      loadVentas(activo);
      loadComrobantesNoEnviados();
    },
  });
});

$(".tablaVentas").on("click", "#bajaDoc", function (e) {
  Swal.fire({
    title: "¿Estás seguro de anular el comprobante?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, guardar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      var idComprobante = $(this).attr("idDoc");
      var activo = $("#active").attr("idP");
      let datos = { idComprobante: idComprobante };

      $.ajax({
        method: "POST",
        url: "ajax/envio-sunat.ajax.php",
        data: datos,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          $(".reload-all").hide();
          Swal.fire({
            title: "COMUNICACIÓN DE BAJA",
            text: "",
            icon: "info",
            html: `<div id="successCO">${respuesta}</div>`,
            showCancelButton: true,
            showConfirmButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });

          $("#successCO").html(respuesta);
          // loadVentas(activo);
        },
      });
    }
  });
});
$(".tablaVentas").on("click", "#bajaDocNotaC", function (e) {
  Swal.fire({
    title: "¿Estás seguro de anular el comprobante?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, guardar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      var idComprobantenota = $(this).attr("idDoc");
      var activo = $("#active").attr("idP");
      let datos = { idComprobantenota: idComprobantenota };

      $.ajax({
        method: "POST",
        url: "ajax/envio-sunat.ajax.php",
        data: datos,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
          $(".reload-all").hide();
          Swal.fire({
            title: "COMUNICACIÓN DE BAJA",
            text: "",
            icon: "info",
            html: `<div id="successCO">${respuesta}</div>`,
            showCancelButton: true,
            showConfirmButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });

          $("#successCO").html(respuesta);
          // loadVentas(activo);
        },
      });
    }
  });
});

$(document).on("click", "#btnConsultarSunat", function (e) {
  let numeroComprobante = $(this).attr("idcomprobanteC");
  let tipo = $(this).attr("tipo");
  var activo = $("#active").attr("idP");

  let datos = { numeroComprobante: numeroComprobante, tipo: tipo };

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    dataType: 'json',
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      if(respuesta['codigoMsg'] == '0001'){
        loadVentas(activo);
        loadComrobantesNoEnviados();
      }
      $(".reload-all").hide();
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        // width: 600,
        // padding: '3em',
        showConfirmButton: false,
        timer: 10000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Toast.fire({
        text: "LA COMPROBACIÓN ES DIRECTO CON SUNAT",
        icon: "info",
        title: `<h4>${respuesta['msgConsulta']}</h4>`,
        html: ``,
      });
    },
  });
});

$("#btnConsulta").on("click", function (e) {
  e.preventDefault();
  let datos = $("#formConsulta").serialize();
  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      $("#resultConsulta").html(respuesta);
    },
  });
});

$(document).on("click", ".bajadacomp", function (e) {
  e.preventDefault();
  let idventabaja = $(this).attr("idcomp");
  var activo = $("#active").attr("idP");
  let datos = { idventabaja: idventabaja };
  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      // console.log(respuesta);
      Swal.fire({
        title: "COMUNICACIÓN DE BAJA",
        text: "",
        icon: "success",
        html: `<div id="successCO">${respuesta}</div>`,
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      loadVentas(activo);
    },
  });
});
$(document).on("click", ".bajadacompNota", function (e) {
  e.preventDefault();
  let idventabajaNota = $(this).attr("idcomp");
  var activo = $("#active").attr("idP");
  let datos = { idventabajaNota: idventabajaNota };
  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      // console.log(respuesta);
      Swal.fire({
        title: "COMUNICACIÓN DE BAJA",
        text: "",
        icon: "success",
        html: `<div id="successCO">${respuesta}</div>`,
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      loadVentas(activo);
    },
  });
});

$(document).on("click", "#btncdrFB", function (e) {
  e.preventDefault();
  var idComprobantecdr = localStorage.getItem("idComprobante");
  var activo = $("#active").attr("idP");
  // console.log(idComprobantecdr);
  var datos = { idComprobantecdr: idComprobantecdr };

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      // console.log(respuesta);
      Swal.fire({
        title: "COMPROBANTE CDR",
        text: "",
        icon: "success",
        html: `<div id="successCO">${respuesta}</div>`,
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      loadVentas(activo);
    },
  });
});
$(document).on("click", "#btnObtenercdrFB", function (e) {
  e.preventDefault();
  var idComprobantecdr = $(this).attr("idcomprobanteC");
  var activo = $("#active").attr("idP");
  // console.log(idComprobantecdr);
  var datos = { idComprobantecdr: idComprobantecdr };

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      // console.log(respuesta);
      Swal.fire({
        title: "COMPROBANTE CDR",
        text: "",
        icon: "success",
        html: `<div id="successCO">${respuesta}</div>`,
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cerrar",
      });
      loadVentas(activo);
    },
  });
});

$("#goConsult").click(function () {
  // e.preventDefault();
  let idCompro = 250;
  let datos = { idcompro: idCompro };
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
      fetch("http://localhost/aPrintPos/ticket3.php/", requestOptions);
      // fetch("http://localhost/impresiontermica/print/", requestOptions)
      // .then((response) => response.text())
      // .then((result) => console.log(result))
      // .catch((error) => console.log("error", error));
    },
  });
});

function envioAutomaticoSunat(){
  let envioAutomatico = 'envioAutomatico';
  let datos = { envioAutomatico: envioAutomatico };

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function () {
      $(".reload-all")
        .fadeIn(50)
        .html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function (respuesta) {
      $(".reload-all").hide();
      if(respuesta){
      $(".resultado-envios-sunat").slideUp(300).html(respuesta).fadeIn(200, function(){
        $(".resultado-envios-sunat").delay(5000).fadeOut(500);
      });
      loadVentas(1);
      loadComrobantesNoEnviados();
      }else{
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
          },
        });
        Toast.fire({
          text: "",
          icon: "info",
          title: `<h5>NO HAY COMPROBANTES PENDIENTES DE ENVÍO</h5>`,
          html: ``,
        });
      }
    }
  })
}
$(document).on('click', '.btn-succes-envia', function(){
  envioAutomaticoSunat();
  
})