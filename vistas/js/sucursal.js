$(".table-sucursales").load("vistas/modulos/table-sucursales.php");
$(document).on("change", "#formSucursal #ubigeo", function () {
  let idubigeo = $(this).val();

  let datos = { idubigeo: idubigeo };
  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    dataType: "json",
    success: function (response) {
      $("#departamento").val(response["departamento"]);
      $("#provincia").val(response["provincia"]);
      $("#distrito").val(response["distrito"]);
    },
  });
});
$(document).on("change", "#formEditarSucursal #eubigeo", function () {
  let idubigeo = $(this).val();

  let datos = { idubigeo: idubigeo };
  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    dataType: "json",
    success: function (response) {
      $("#edepartamento").val(response["departamento"]);
      $("#eprovincia").val(response["provincia"]);
      $("#edistrito").val(response["distrito"]);
    },
  });
});
$(document).on("click", "#formSucursal .btnGuardarSucursal", function (e) {
  e.preventDefault();
  // let datos = new FormData(document.getElementById('formSucursal'));
  let datos = $("#formSucursal").serialize();

  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    success: function (response) {
      console.log(response);
      if (response == "ok") {
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
          title: `<h5>DATOS AGREGADOS CORRÉCTAMENTE</h5>`,
          html: ``,
        });
        $("#formSucursal").each(function () {
          this.reset();
        });
      } else {
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
          icon: "error",
          title: `${response}</h5>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("click", ".btnEditarSucursal", function () {
  let idSucursal = $(this).attr("idSucursal");
  console.log(idSucursal);
  let datos = { idSucursal: idSucursal };
  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    dataType: "json",
    success: function (response) {
      //  console.log(response);
      if (response["codigo"] == "0000") {
        $("#ecodigo").attr("readonly", true);
        Swal.fire({
          title: "PRINCIPAL!",
          text: "Las actualizaciones se harán en configuración de empresa!",
          icon: "info",
        });
        // $("#modalEditarSucursal").modal("hide");
      } else {
        // $("#ecodigo").attr("readonly", false);
        $("#modalEditarSucursal .box-body").show(100);
      }

      $("#eidsucursal").val(response["id"]);
      $("#ecodigo").val(response["codigo"]);
      $("#enombre").val(response["nombre_sucursal"]);
      $("#edireccion").val(response["direccion"]);
      $("#eubigeo").val(response["ubigeo"]);
      $("#edepartamento").val(response["departamento"]);
      $("#eprovincia").val(response["provincia"]);
      $("#edistrito").val(response["distrito"]);
      $("#etelefono").val(response["telefono"]);
      $("#ecorreo").val(response["correo"]);
      let combo = document.getElementById("eubigeo");
      let idubigeo = combo.options[combo.selectedIndex].text;
      $("#select2-eubigeo-container").html(idubigeo);
    },
  });
});

$(document).on("click", ".btnEditarSucursales", function (e) {
  e.preventDefault();
  let datos = $("#formEditarSucursal").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    success: function (response) {
      console.log(response);
      if (response == "ok") {
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
          title: `<h5>DATOS EDITADOS CORRÉCTAMENTE</h5>`,
          html: ``,
        });
        $(".table-sucursales").load("vistas/modulos/table-sucursales.php");
        $("#modalEditarSucursal").modal("hide");
      } else {
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
          icon: "error",
          title: `${response}</h5>`,
          html: ``,
        });
      }
    },
  });
});

$(document).on("change", "#activoS", function () {
  let idSu = $(this).attr("idSucursal");

  let ok = this.checked;

  if (ok == true) {
    var result = "s";
  } else {
    var result = "n";
  }
  let datos = { idSu: idSu, result: result };
  $.ajax({
    type: "POST",
    url: "ajax/sucursal.ajax.php",
    data: datos,
    success: function (response) {
      //  $(".table-sucursales").load("vistas/modulos/table-sucursales.php");
    },
  });
});

$("#sucursalbtnof").on('change', function() {
  let sucursal = $("input[name=sucursalbtnof]:checked").val();
  if(sucursal == 'on'){
    $(".row-sucursal").fadeIn();
  }else{
    $(".row-sucursal").fadeOut();
  }
});


