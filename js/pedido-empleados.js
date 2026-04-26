document.addEventListener("DOMContentLoaded", () => {

  let formMesas = document.getElementById("formDatos_mesa");
  let botonFormMesas = document.getElementById("operacion");

  let tipoReserva1 = document.getElementById("tipo_reserva1");
  let tipoReserva2 = document.getElementById("tipo_reserva2");
  let comensales = document.getElementById("elegir_comensales");

  botonFormMesas.addEventListener("click", function(e){
    e.preventDefault();
    if (!tipoReserva1.checked && !tipoReserva2.checked){
      let alertaEs = "Debes elegir si quieres hacer tú pedido ahora o en el restaurante.";
      let alertaEn = "You must choose whether to make your order here or in the restaurant."
      alert(`${alertaEs}\n${alertaEn}`);
    }
    else if (!comensales.value || parseInt(comensales.value) < parseInt(comensales.min) || 
      parseInt(comensales.value) > parseInt(comensales.max)){
      let alertaEs = "Debes elegir un número válido de comensales.";
      let alertaEn = "You must choose a valid number of guests."
      alert(`${alertaEs}\n${alertaEn}`);
    }
    else {
      formMesas.submit();
    };
  });
  
});