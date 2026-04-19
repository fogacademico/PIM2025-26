document.addEventListener("DOMContentLoaded", () => {

  let formMesas = document.getElementById("formDatos_mesa");
  let botonFormMesas = document.getElementById("operacion");

  let tipoReserva1 = document.getElementById("tipo_reserva1");
  let tipoReserva2 = document.getElementById("tipo_reserva2");
  let comensales = document.getElementById("elegir_comensales");

  botonFormMesas.addEventListener("click", function(e){
    e.preventDefault();
    if (!comensales.value || comensales.value < comensales.min || comensales.value > comensales.max){
      let alertaEs = "Debes elegir un número válido de comensales.";
      let alertaEn = "You must choose a valid number of guests."
      alert(`${alertaEs}\n${alertaEn}`);
    }
    else {
      formMesas.submit();
    };
  });
  
});