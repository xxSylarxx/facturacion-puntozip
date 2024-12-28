$(".btnCrearCuentaBanco").on("click", function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  datos = $("#formNuevaCuentaBanco").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/cuentas-banco.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      $(".resultadoCrearBanco").html(data);
    },
  });
});
$("tbody").on("click", ".btnEditarCuentaBancoItem", function (e) {
  e.preventDefault();
  // e.stopImmediatePropagation();
  let idCuentaB = $(this).attr("idCuentaB");
  datos = { idCuentaB: idCuentaB};
  console.log(idCuentaB);
  $.ajax({
    method: "POST",
    url: "ajax/cuentas-banco.ajax.php",
    data: datos,
    dataType: "json",
    success: function (data) {
      console.log(data);   

      $("#idCuentaBanco").val(data["id"]);
      $("#emonedacuenta").val(data["moneda"]).trigger('change');
      $("#etipocuenta").val(data["tipocuenta"]).trigger('change');
      $("#enombrebanco").val(data["nombrebanco"]);
      $("#etitular").val(data["titular"]);
      $("#etitular").val(data["titular"]);
      $("#enumerocuenta").val(data["numerocuenta"]);
      $("#enumerocci").val(data["numerocci"]);
      $("#edescripcion").val(data["descripcion"]);
    },
  });
});
$(document).one("click", ".btnEditarCuentaBanco", function (e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  datos = $("#formEditarCuentaBanco").serialize();

  $.ajax({
    method: "POST",
    url: "ajax/cuentas-banco.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      //   console.log(data);
      $(".resultadoCrearBanco").html(data);
    },
  });
});
