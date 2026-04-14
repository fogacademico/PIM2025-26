document.addEventListener("DOMContentLoaded", () => {

  // FALTA POR HACER:
  // - Si el cliente avanza a confirmacion.php y no está conforme con su pedido, 
  // que pueda ir atrás sin perderlo todo.

  // - Todo lo de los idiomas.
  // - Conexión con base de datos.

  // Variables
  let maximoToppings = 5;

  let botonTipoMasa = document.getElementById("tipo-masa-sin-gluten");
  let selectMasas = document.getElementById("masas");
  let optionsMasas = selectMasas.childNodes;
  let formBases = document.getElementsByName("bases");
  let formToppings = document.getElementsByName("toppings");
  let cantidadPizza = document.getElementById("pizza-cantidad");

  let botonMenuProd = document.getElementById("boton-prod");
  let botonMenuPizza = document.getElementById("boton-pizza");
  let menuProd = document.getElementById("menu-prod");
  let menuPizza = document.getElementById("menu-pizza");
  let botonConfirmarPizza = document.getElementById("btn-confirmar-pizza");

  let panelSeleccionados = document.getElementById("panel-seleccionados");
  let listaProductos = document.getElementById("lista-prod");

  let muestraDelPrecio = document.getElementById("precio-pedido");

  let envioJson = document.getElementById("detalles-pedido");

  // JSON del pedido que se devolverá a servidor
  let detallesPedido = {
    productos : {},
    pizzas : {}
  };

  // Funciones
  function ocultarMasas(){
    optionsMasas.forEach((masa) => masa.classList.toggle("ocultar"));
  };

  function incluirProducto(productTag){
      let idProd = productTag.id;
      idProd = idProd.slice(9);
      detallesPedido['productos'][idProd] = {
        nombre: productTag.dataset.nombreprod,
        cantidad: productTag.value,
        precio_ud: productTag.dataset.precioprod
      };
  };

  function incluirPizza(listaIngr){
    let pizzaIds = Object.keys(detallesPedido.pizzas);
    let interruptor = true;
    let i = 0;
    while (interruptor){
          i++;
      if (!pizzaIds.includes(i.toString())){
        interruptor = false;
      };
    };
    detallesPedido['pizzas'][i] = {
      cantidad: cantidadPizza.value,
      ingredientes: {}
    };
    listaIngr.forEach((x) => {
      detallesPedido['pizzas'][i]['ingredientes'][x.value] = 
      {
        nombre: x.dataset.nombreingr,
        precioIng: x.dataset.precioingr
      };
    });  
  };

  function eliminarDetallePedido(productTag){
    let idDetalle = productTag.className;
    idDetalle = idDetalle.slice(9);
    let tipoDetalle = productTag.dataset.tipo;
    if (tipoDetalle == "producto"){delete detallesPedido["productos"][idDetalle];}
    else if (tipoDetalle == "pizza"){delete detallesPedido["pizzas"][idDetalle];} 
  };

  function mostrarDetallesPedido(){
    panelSeleccionados.innerHTML = "";
    let arrayProductos = Object.entries(detallesPedido.productos);
    arrayProductos.forEach((x) => {
      let [clave, valor] = x;
      let detallePedido = document.createElement("li");
      let textoDetalle = document.createElement("span");
      let botonDetalle = document.createElement("span");
      textoDetalle.classList.add(`escogido-${clave}`);
      textoDetalle.setAttribute("data-tipo", "producto");
      botonDetalle.classList.add("btn-borrar-detalle");
      textoDetalle.textContent = `${valor.nombre} x ${valor.cantidad} = ${valor.precio_ud * valor.cantidad} Euros. `;
      botonDetalle.textContent = "X";
      detallePedido.appendChild(textoDetalle);
      detallePedido.appendChild(botonDetalle);
      panelSeleccionados.appendChild(detallePedido); 
    });
    let arrayPizzas = Object.entries(detallesPedido.pizzas);
    arrayPizzas.forEach((x) => {
      let [clavePizza, datosDetalle] = x;
      let cantidadDetalle = datosDetalle.cantidad;
      ingredientesDetalle = datosDetalle.ingredientes;
      let detallePedido = document.createElement("li");
      let textoDetalle = document.createElement("span");
      let botonDetalle = document.createElement("span");
      textoDetalle.classList.add(`escogido-${clavePizza}`);
      textoDetalle.setAttribute("data-tipo", "pizza");
      botonDetalle.classList.add("btn-borrar-detalle");
      let contenidoDetalle = "Pizza (";
      let precioDetalle = 0;
      Object.values(ingredientesDetalle).forEach((y) => {
        contenidoDetalle += `${y.nombre} - `;
        precioDetalle += parseFloat(y.precioIng);
      });
      contenidoDetalle += `) - ${precioDetalle} x ${cantidadDetalle} =  ${precioDetalle * cantidadDetalle} Euros`;
      textoDetalle.textContent = contenidoDetalle;
      botonDetalle.textContent = "X";
      detallePedido.appendChild(textoDetalle);
      detallePedido.appendChild(botonDetalle);
      panelSeleccionados.appendChild(detallePedido); 
    });
  };

  function calcularPrecioPedido(){
    let precioTotal = 0;
    let arrayProductos = Object.values(detallesPedido.productos);
    arrayProductos.forEach((x) => {precioTotal += (x.precio_ud * x.cantidad);});
    let arrayPizzas = Object.values(detallesPedido.pizzas);
    console.log(arrayPizzas);
    arrayPizzas.forEach((x) => {
      let npizzas = parseFloat(x.cantidad);
      let precioUnaPizza = 0;
      Object.values(x.ingredientes).forEach((y) => {
        precioUnaPizza += parseFloat(y.precioIng);
      });
      precioTotal += (npizzas * precioUnaPizza);
    });
    return precioTotal;
  };

  // Gestión de eventos
  botonTipoMasa.addEventListener("change", function(e){
    ocultarMasas();
  });

  botonMenuProd.addEventListener("click", function(e){
    e.preventDefault();
    menuProd.className = "ver-menu";
    menuPizza.className = "ocultar";
  });

  botonMenuPizza.addEventListener("click", function(e){
    e.preventDefault();
    menuProd.className = "ocultar";
    menuPizza.className = "ver-menu";
  });

  // Añadir pizza
  botonConfirmarPizza.addEventListener("click", function(e){
    e.preventDefault();
    if (false){}
    else {
      let masas = [];
      optionsMasas.forEach((x) => {
        if (x.selected){
          masas.push(x);
        };
      });
      let bases = [];
      formBases.forEach((x) => {
        if (x.checked){
          bases.push(x);
        };
      });
      let toppings = [];
      formToppings.forEach((x) => {
        if (x.checked){
          toppings.push(x);
        };
      });
      if (masas.length < 1 || masas[0].value == ['sin-masa']){
        alert(`Debe escogerse al menos una masa.`);
      }
      if (masas.length > 1){
        alert(`El sistema ha registrado que se ha intentado pedir una pizza con varias masas.`);
      }
      else if (bases.length < 1){
        alert(`Debe escogerse al menos una base.`);
      }
      else if (toppings.length > maximoToppings){
        alert(`No pueden elegirse más de ${maximoToppings} ingredientes (sin contar las bases).`);
      }
      else {
        ingredientesPizza = masas.concat(bases, toppings);
        incluirPizza(ingredientesPizza);
        mostrarDetallesPedido();
        muestraDelPrecio.textContent = calcularPrecioPedido();
        envioJson.value = JSON.stringify(detallesPedido);
        cantidadPizza.value = 1;
      };
    };
  });

  // Actualiza el JSON que se enviará por php y muestra su contenido (los detalles del pedido)
  listaProductos.addEventListener("click", function(e, inputProducto = e.target.parentNode.childNodes[1]){
    e.preventDefault();
    if (e.target.className === "boton-producto"){
      incluirProducto(inputProducto);
      mostrarDetallesPedido();
      muestraDelPrecio.textContent = calcularPrecioPedido();
      envioJson.value = JSON.stringify(detallesPedido);
    };
  });

  // Elimina un detalle del pedido y deja de mostrarlo
  panelSeleccionados.addEventListener("click", function(e, inputDetalle = e.target.parentNode.childNodes[0]){
    if (e.target.className === "btn-borrar-detalle"){
      eliminarDetallePedido(inputDetalle);
      mostrarDetallesPedido();
      muestraDelPrecio.textContent = calcularPrecioPedido();
      envioJson.value = JSON.stringify(detallesPedido);
    };
  });

});