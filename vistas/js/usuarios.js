//   cerrarSession();

function cerrarSession() {
  let datos = { cerrarS: "cerrarS" };
  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      if (respuesta == "ok") {
        window.location = "salir";
      }
    },
  });
}
//LOGIN USUARIOS
$("#logUser").click(function (e) {
  e.preventDefault();
  var conectar = $("#conectado").val();
  var clavePublica = $("#cpublica").val();

  if (conectar == "ok") {
    grecaptcha.ready(function () {
      grecaptcha
        .execute(clavePublica, {
          action: "validarUsuario",
        })
        .then(function (token) {
          $("#form-login").prepend(
            '<input type="hidden" name="token" id="token" value="' +
              token +
              '" >'
          );
          $("#form-login").prepend(
            '<input type="hidden" name="action" id="action" value="validarUsuario" >'
          );

          var token = $("#token").val();
          var action = $("#action").val();
          var ingUsuario = $("#ingUsuario").val();
          var ingPassword = $("#ingPassword").val();

          let datos = {
            ingUsuario: ingUsuario,
            ingPassword: ingPassword,
            token: token,
            conectar: conectar,
          };

          $.ajax({
            url: "ajax/usuarios.ajax.php",
            method: "POST",
            data: datos,

            beforeSend: function () {},
            success: function (respuesta) {
              //(respuesta);
              $("#resultLogin")
                .html(respuesta)
                .show(500, function () {
                  $(this).delay(3000).hide(500);
                });
            },
          });
        });
    }); //FIN RECAPTCHA
  } else {
    var ingUsuario = $("#ingUsuario").val();
    var ingPassword = $("#ingPassword").val();

    let datos = {
      ingUsuario: ingUsuario,
      ingPassword: ingPassword,
      conectar: conectar,
    };

    $.ajax({
      url: "ajax/usuarios.ajax.php",
      method: "POST",
      data: datos,

      beforeSend: function () {},
      success: function (respuesta) {
        //(respuesta);
        $("#resultLogin")
          .html(respuesta)
          .show(500, function () {
            $(this).delay(3000).hide(500);
          });
      },
    });
  }
});
$(document).keypress(function (e) {
  $user = $("#ingUsuario").val();
  if (e.which == 13 && $user != "") {
    var conectar = $("#conectado").val();
    var clavePublica = $("#cpublica").val();

    if (conectar == "ok") {
      grecaptcha.ready(function () {
        grecaptcha
          .execute(clavePublica, {
            action: "validarUsuario",
          })
          .then(function (token) {
            $("#form-login").prepend(
              '<input type="hidden" name="token" id="token" value="' +
                token +
                '" >'
            );
            $("#form-login").prepend(
              '<input type="hidden" name="action" id="action" value="validarUsuario" >'
            );

            var token = $("#token").val();
            var action = $("#action").val();
            var ingUsuario = $("#ingUsuario").val();
            var ingPassword = $("#ingPassword").val();

            let datos = {
              ingUsuario: ingUsuario,
              ingPassword: ingPassword,
              token: token,
              conectar: conectar,
            };

            $.ajax({
              url: "ajax/usuarios.ajax.php",
              method: "POST",
              data: datos,

              beforeSend: function () {},
              success: function (respuesta) {
                //(respuesta);
                $("#resultLogin")
                  .html(respuesta)
                  .show(500, function () {
                    $(this).delay(3000).hide(500);
                  });
              },
            });
          });
      }); //FIN RECAPTCHA
    } else {
      var ingUsuario = $("#ingUsuario").val();
      var ingPassword = $("#ingPassword").val();

      let datos = {
        ingUsuario: ingUsuario,
        ingPassword: ingPassword,
        conectar: conectar,
      };

      $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,

        beforeSend: function () {},
        success: function (respuesta) {
          //(respuesta);
          $("#resultLogin")
            .html(respuesta)
            .show(500, function () {
              $(this).delay(3000).hide(500);
            });
        },
      });
    }
  }
});
// SUBIENDO LA FOTO DEL USUARIO
$(".nuevaFoto").change(function () {
  let imagen = this.files[0];

  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".nuevaFoto").val("");
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "La imagen debe ser jpeg o png!",
      //footer: '<a href>Why do I have this issue?</a>'
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaFoto").val("");
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

// Agregar USUARIO
$(".form-inserta").submit(function (e) {
  e.preventDefault();
  let datos = $(this).serialize();
  let formd = new FormData($("form.form-inserta")[0]);
  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: (datos, formd),
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {
      $(".resultados").html(respuesta);
    },
  });
});
// EDITTAR USUARIO
$(document).on("click", ".btnEditarUsuario", function () {
  var idUsuario = $(this).attr("idUsuario");
  ////(idUsuario);
  var datos = new FormData();
  datos.append("idUsuario", idUsuario);
  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarNombre").val(respuesta["nombre"]);
      $("#editarUsuario").val(respuesta["usuario"]);
      $("#editarPerfil").html(respuesta["perfil"]);
      $("#editarPerfil").val(respuesta["perfil"]);
      $("#editarDni").val(respuesta["dni"]);
      $("#editarEmail").val(respuesta["email"]);
      $("#passwordActual").val(respuesta["password"]);
      $("#fotoActual").val(respuesta["foto"]);
      $("#editarSucursal").val(respuesta["id_sucursal"]);

      if (respuesta["foto"] != "") {
        $(".previsualizar").attr("src", respuesta["foto"]);
      }
    },
  });
});
// ACTIVAR USUARIO|
$(document).on("change", "#usuarioEstado", function () {
  let idUsuario = $(this).attr("idUsuario");
  if ($(this).is(":checked")) {
    estadoUsuario = 1;
  } else {
    estadoUsuario = 0;
  }

  let datos = { activarId: idUsuario, activarUsuario: estadoUsuario };

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    success: function (respuesta) {
      if (window.matchMedia("(max-width:767px)").matches) {
      }
    },
  });
  if (estadoUsuario == 0) {
    $(this).removeClass("btn-success");
    $(this).addClass("btn-danger");
    $(this).html("desactivado");
    $(this).attr("estadoUsuario", 1);
  } else {
    $(this).removeClass("btn-danger");
    $(this).addClass("btn-success");
    $(this).html("Activado");
    $(this).attr("estadoUsuario", 0);
  }
});
$(document).on("click", ".btn-no-user", function (e) {
  e.preventDefault();
  $(".alert").remove();
  $("#nuevoUsuario").val("");
});
// VALIDAR NO REPETIR USUARIO

$(document).on("change", "#nuevoUsuario", function () {
  $(".alert").remove();

  let validarUsuario = $(this).val();
  // let datos = new FormData();
  datos = {
    validarUsuario: validarUsuario,
  };

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    // cache: false,
    // contentType: false,
    // processData: false,
    dataType: "json",
    success: function (respuesta) {
      //    //("respuesta", respuesta);
      // console.log(respuesta);

      if (respuesta) {
        $("#nuevoUsuario").val("");
        $("#nuevoUsuario")
          .parent()
          .before(
            '<div class="alert alert-warning" style="display:none;">Este usuario ya existe!</div>'
          );
        $(".alert").show(500, function () {
          $(this).delay(3000).hide(500);
        });
      }
    },
  });
});
// ELIMINAR USUARIO
$(document).on("click", ".btnEliminarUsuarioNOELIMINAR", function () {
  let idUsuario = $(this).attr("idUsuario");
  let fotoUsuario = $(this).attr("fotoUsuario");
  let usuario = $(this).attr("usuario");

  Swal.fire({
    title: "¿Estás seguro de eliminar este usuario?",
    text: "¡Si no lo está puede  cancelar la acción!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminarlo!",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location =
        "index.php?ruta=usuarios&idUsuario=" +
        idUsuario +
        "&usuario=" +
        usuario +
        "&fotoUsuario=" +
        fotoUsuario;
      //   Swal.fire(
      //     'Deleted!',
      //     'Your file has been deleted.',
      //     'success'
      //   )
    }
  });
});
// BUSCAR DNI RENIEC
$(".form-inserta").on("change", "#nuevoDni", function (e) {
  e.preventDefault();
  let dni = $(this).val();
  let datos = { dni: dni };
  $.ajax({
    method: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      $("#nuevoDni").val(respuesta["dni"]);
      $("#nuevoNombre").val(respuesta["nombre"]);
    },
  });
});
// SOLO INGRESAR NUMEROS CAMPO DNI
$("#nuevoDni").keyup(function () {
  var ruc = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(ruc)) {
    //dni = dni.substr(0,(dni.length -1));
    ruc = ruc.replace(/[^0-9]/g, "");
    $("#nuevoDni").val(ruc);
  }
});

//=======================================================================================
//ROLES DE USUARIO
$(".form-insertaUser").submit(function (e) {
  e.preventDefault();
  let datos = $(this).serialize();
  noRepetirRol();
  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    success: function (respuesta) {
      if (respuesta == "ok") {
        $("input").prop("checked", true);
        $("#rolUsuario").val("");
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 15500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "success",
          title: `<h5>Se han agregado elrol</h5>`,
          html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-user"></i> ROL AGREGADO CORRECTAMENTE</div`,
        });

        $(".contenido-roles").load("vistas/modulos/contenidoroles.php");
      }
    },
  });
});
// document.getElementById("formRol").addEventListener("submit", function (event) {
//   event.preventDefault(); // Evitamos que el formulario se envíe de forma tradicional

//   const formData = new FormData(event.target); // Obtenemos los datos del formulario
//   const url = "ajax/usuarios.ajax.php"; // Reemplaza esto con la URL del script PHP en tu servidor

//   fetch(url, {
//     method: "POST",
//     body: formData,
//   })
//     .then((response) => response.text())
//     .then((data) => {
//       console.log(data);
//       if (data == "ok") {
//         $(".contenido-roles").load("vistas/modulos/contenidoroles.php");
//         $("input").prop("checked", true);
//         $("#rolUsuario").val("");
//         const Toast = Swal.mixin({
//           toast: true,
//           position: "top-end",
//           // width: 600,
//           // padding: '3em',
//           showConfirmButton: false,
//           timer: 15500,
//           timerProgressBar: true,
//           didOpen: (toast) => {
//             toast.addEventListener("mouseenter", Swal.stopTimer);
//             toast.addEventListener("mouseleave", Swal.resumeTimer);
//           },
//         });

//         Toast.fire({
//           icon: "success",
//           title: `<h5>Se han agregado el rol</h5>`,
//           html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-user"></i> ROL AGREGADO CORRECTAMENTE</div`,
//         });
//         // Aquí puedes manejar la respuesta del servidor, por ejemplo, mostrar un mensaje de éxito
//         // document.getElementById("resultado").textContent = data;
//       }
//     })
//     .catch((error) => {
//       // Manejo de errores en caso de que haya algún problema con el envío del formulario
//       console.error("Error al enviar el formulario:", error);
//       document.getElementById("resultado").textContent =
//         "Error al enviar el formulario";
//     });
// });

$(document).on("click", ".btn-agregar-accesos", function (e) {
  var idRol = $(this).attr("idRol");
  $("#idRol").val(idRol);
  $("input").prop("checked", true);
  agregarAccesosRoles();
});
//ROLES DE USUARIO
function agregarAccesosRoles() {
  // e.preventDefault();
  let datos = $("#formAccesoRol").serialize();

  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    success: function (respuesta) {
      // if ($("input.acces-roles").prop("checked")) {
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        // width: 600,
        // padding: '3em',
        showConfirmButton: false,
        timer: 15500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Toast.fire({
        icon: "success",
        title: `<h5>Se han agregado los acceos</h5>`,
        html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-user"></i> ACCESOS AGREGADOS CORRECTAMENTE</div`,
      });
      $("input").prop("checked", false);
      $(".contenido-roles").load("vistas/modulos/contenidoroles.php");
    },
  });
}
$(".contenido-roles").load("vistas/modulos/contenidoroles.php");

function noRepetirRol() {
  let roldeusuario = $("#rolUsuario").val();
  let datos = { roldeusuario: roldeusuario };

  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      if (respuesta) {
        $("#rolUsuario").val("");
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          // width: 600,
          // padding: '3em',
          showConfirmButton: false,
          timer: 15500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "warning",
          title: `<h3>EL ROL YA ESISTE!</h3>`,
          html: `<div style="font-size: 1.5em; color: red;"><i class="fas fa-user"></i>EL ROL ${roldeusuario} YA EXISTENTE</div`,
        });
      }
    },
  });
}

$("#rolUsuario").keyup(function (e) {
  noRepetirRol();
});
$(document).on("click", ".rol-total", function (e) {
  if ($("input.todosroles").prop("checked")) {
    $("input").prop("checked", true);
  } else {
    $("input").prop("checked", false);
  }
});

$(document).on("click", ".btn-editar-accesos", function (e) {
  let rolid = $(this).attr("idRol");
  let datos = { rolid: rolid };
  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".editar-roles").html(respuesta);
    },
  });
});

// EDITAR ROLES
$(document).on("click", ".btn-editar-roles-acceso", function (e) {
  let idAcceso = $(this).attr("idAcceso");
  if ($("input#accesoroles" + idAcceso).prop("checked")) {
    var activo = "s";
    $("input#accesoroles" + idAcceso).prop("checked", true);
  } else {
    var activo = "n";
    $("input#accesoroles" + idAcceso).prop("checked", false);
  }
  // console.log(activo);
  let datos = { idAcceso: idAcceso, activo: activo };
  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    success: function (respuesta) {
      // console.log(respuesta);
    },
  });
});

// ELIMINAR ROLES
$(document).on("click", ".btn-eliminar-rol", function (e) {
  let idRoldelete = $(this).attr("idRole");

  let datos = { idRoldelete: idRoldelete };
  Swal.fire({
    title: "¿Estás seguro de eliminar el rol?",
    text: "¡Se eliminará el rol y todos sus accesos!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "ajax/usuarios.ajax.php",
        data: datos,
        success: function (respuesta) {
          if (respuesta == "ok") {
            $(".contenido-roles").load("vistas/modulos/contenidoroles.php");
          }
        },
      });
    }
  });
});

$(document).on("click", "#btmNuevoAcesos", function (e) {
  e.preventDefault();

  let datos = $("#frmAccesosLink").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/usuarios.ajax.php",
    data: datos,
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta != "ok" && respuesta != "error") {
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
            $("#nuevoacceso").val("");
            $("#nuevolink").val("");
          },
        });

        Toast.fire({
          icon: "success",
          title: `<h4>DATOS INGESADOS!</h4>`,
          html: ``,
        });
      } else {
        if (respuesta == "error") {
          var error = "LLENE TODOS LOS CAMPOS";
        } else {
          var error = "EL LINK INGRESADO PARA ESTE ROL YA EXISTE!";
        }
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
            $("#nuevolink").val("");
            $("#nuevolink").focus();
          },
        });

        Toast.fire({
          icon: "error",
          title: `<h4>${error}</h4>`,
          html: ``,
        });
      }
    },
  });
});
