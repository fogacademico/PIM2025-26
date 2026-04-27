document.addEventListener("DOMContentLoaded", () => {

  let formMesas = document.getElementById("formDatos_mesa");
  let botonFormMesas = document.getElementById("operacion");

  let tipoReserva1 = document.getElementById("tipo_reserva1");
  let tipoReserva2 = document.getElementById("tipo_reserva2");
  let comensales = document.getElementById("elegir_comensales");
  let tlf = document.getElementById("tlf");
  let nombreCliente = document.getElementById("nombre_cliente");

  botonFormMesas.addEventListener("click", function(e){
    e.preventDefault();
    if (!comensales.value || parseInt(comensales.value) < parseInt(comensales.min) || 
      parseInt(comensales.value) > parseInt(comensales.max)){
      let alertaEs = "Debes elegir un número válido de comensales.";
      let alertaEn = "You must choose a valid number of guests."
      alert(`${alertaEs}\n${alertaEn}`);
    }
    else if (tlf.value === 0 || tlf.value === "" || nombreCliente.value === ""){
     alert("Deben introducirse todos los datos.\nAll of the fields are required.");
    }
    else {
      formMesas.submit();
    };
  });
  
});