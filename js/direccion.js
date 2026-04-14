document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.getElementById("formDatos");

  formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    alert("Datos enviados correctamente");

    window.location.href = "gracias.html";
  });
});
