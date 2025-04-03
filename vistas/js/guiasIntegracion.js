// http://localhost:8000/api/v1/guias-system/guias
// http://host.docker.internal:8000/api/v1/guias-system/guias
$(document).ready(function () {
  function cargarGuias() {
    let datos = { 'guiasPorEmitir': 'guiasPorEmitir' }
    $.ajax({
      url: "ajax/empresa.ajax.php",
      method: "POST",
      data: datos,
      success: function (data) {
        var guiasMenu = $(".guiasIntegracion-menu");
        guiasMenu.empty(); // Limpiar el contenido anterior
        let dataGuias = JSON.parse(data);
        dataGuias?.forEach(function (guia) {
          var listItem = `
            <li class="guiasIntegracion-item">
              <a href="crear-guia?idGuiaIntegracion=${guia.id}" class="guias-link">
                <i class="fa-regular fa-file-lines guiasIntegracion-icon"></i>
                <span class="guiasIntegracion-item-text">${guia.id} - ${guia.proveedor_descripcion}</span>
              </a>
            </li>
            `;
          guiasMenu.append(listItem);
        });
        $(".guiasIntegracion-recepcion").text(dataGuias.length);
      },
      error: function (error) {
        console.error("Error al obtener las guías:", error);
      },
    });
  }

  cargarGuias();

  setInterval(cargarGuias, 60000);
});
