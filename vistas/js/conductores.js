$(".resultadoProveedor").hide();
$(".resultadoSerie").hide();
$("#rucActivo").hide();

$(".btnCrearConductor").one("click", function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    datos = $("#formNuevoConductor").serialize();
    console.log('conductor');
  });