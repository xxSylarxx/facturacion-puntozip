// http://localhost:8000/api/v1/guias-system/guias
// http://host.docker.internal:8000/api/v1/guias-system/guias
$(document).ready(function () {
  function cargarGuias() {
    $.ajax({
      url: "http://89.117.145.178:8081/api/v1/sistema-guias/puntozip",
      method: "GET",
      success: function (data) {
        console.log(data);
        var guiasMenu = $(".guiasIntegracion-menu");
        guiasMenu.empty(); // Limpiar el contenido anterior

        data?.forEach(function (guia) {
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

        // Actualizar el contador
        $(".guiasIntegracion-recepcion").text(data.length);
      },
      error: function (error) {
        console.error("Error al obtener las guías:", error);
      },
    });
  }

  cargarGuias();

  setInterval(cargarGuias, 60000);
});
