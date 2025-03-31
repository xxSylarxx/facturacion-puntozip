$("#themecolor").load("vistas/modulos/theme.php");

$(document).on("click", "#themego", function () {
  var theme = $(this).attr("teme");
  // console.log(theme);

  localStorage.setItem("theme", theme);
  var gettheme = localStorage.getItem("theme");
  document.documentElement.setAttribute("data-theme", gettheme);
});

const tema = localStorage.getItem("theme");

document.documentElement.setAttribute("data-theme", tema);
