document.addEventListener("DOMContentLoaded", () => {

  // FALTA POR HACER:

  // - Todo lo de los idiomas.

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

  let botonEnviarPedido = document.getElementById("operacion");
  let enviarPedido = document.getElementById("enviar-pedido");

  // JSON del pedido que se devolverá a servidor
  let detallesPedido = {
    productos : {},
    pizzas : {}
  };

  // Poco elegante, lo sé
  let detallesPedidoVacios = {
    productos : {},
    pizzas : {}
  };

  // Funciones
  function ocultarMasas(){
    optionsMasas.forEach((masa) => masa.classList.toggle("ocultar"));
  };

  function incluirProducto(productTag){ // Meterle idiomas
      let idProd = productTag.id;
      idProd = idProd.slice(9);
      detallesPedido['productos'][idProd] = {
        cantidad: productTag.value,
        precio_ud: productTag.dataset.precioprod
      };
      if (localStorage.getItem("language") === "en"){
        detallesPedido['productos'][idProd]["nombre"] = productTag.dataset.nombreproden;
      }
      else {detallesPedido['productos'][idProd]["nombre"] = productTag.dataset.nombreprod;};
  };

  function incluirPizza(listaIngr){ // Meterle idiomas
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
        precioIng: x.dataset.precioingr
      };
      if (localStorage.getItem("language") === "en"){
        detallesPedido['pizzas'][i]["ingredientes"][x.value]["nombre"] = x.dataset.nombreingren;
      }
      else {detallesPedido['pizzas'][i]["ingredientes"][x.value]["nombre"] = x.dataset.nombreingr;};
    });  
  };

  function eliminarDetallePedido(productTag){
    console.log(productTag);
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
      detallePedido.classList.add(`escogido-${clave}`);
      detallePedido.setAttribute("data-tipo", "producto");
      textoDetalle.setAttribute("data-i18n", `producto_${clave}`);
      botonDetalle.classList.add("btn-borrar-detalle")
      textoDetalle.textContent = `${valor.nombre}`;
      botonDetalle.textContent = "X";
      detallePedido.appendChild(textoDetalle);
      detallePedido.appendChild(document.createTextNode(` x ${valor.cantidad} = ${valor.precio_ud * valor.cantidad} Euros. `));
      detallePedido.appendChild(botonDetalle);
      panelSeleccionados.appendChild(detallePedido); 
    });
    let arrayPizzas = Object.entries(detallesPedido.pizzas);
    arrayPizzas.forEach((x) => {
      let [clavePizza, datosDetalle] = x;
      let cantidadDetalle = datosDetalle.cantidad;
      ingredientesDetalle = datosDetalle.ingredientes;
      let detallePedido = document.createElement("li");
      let botonDetalle = document.createElement("span");
      detallePedido.classList.add(`escogido-${clavePizza}`);
      detallePedido.setAttribute("data-tipo", "pizza");
      botonDetalle.classList.add("btn-borrar-detalle");
      detallePedido.appendChild(document.createTextNode("Pizza: ("));
      let precioDetalle = 0;
      Object.entries(ingredientesDetalle).forEach((y) => {
        let [claveIngr, datosIngr] = y;
        let textoDetalle = document.createElement("span");
        textoDetalle.setAttribute("data-i18n", `ingr_${claveIngr}`);
        textoDetalle.textContent = `${datosIngr.nombre} - `;
        detallePedido.appendChild(textoDetalle);
        detallePedido.appendChild(document.createTextNode(` - `));
        precioDetalle += parseFloat(datosIngr.precioIng);
      });
      botonDetalle.textContent = "X";
      detallePedido.appendChild(document.createTextNode(`) - ${precioDetalle} x ${cantidadDetalle} =  
        ${precioDetalle * cantidadDetalle} Euros`));
      detallePedido.appendChild(botonDetalle);
      panelSeleccionados.appendChild(detallePedido); 
    });
    let currentLang = "";
    if (localStorage.getItem("language")){currentLang = localStorage.getItem("language");} 
    else {currentLang = "es"};
    applyLanguage(currentLang);
  };

  function calcularPrecioPedido(){
    let precioTotal = 0;
    let arrayProductos = Object.values(detallesPedido.productos);
    arrayProductos.forEach((x) => {precioTotal += (x.precio_ud * x.cantidad);});
    let arrayPizzas = Object.values(detallesPedido.pizzas);
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
  panelSeleccionados.addEventListener("click", function(e, inputDetalle = e.target.parentNode){
    if (e.target.className === "btn-borrar-detalle"){
      eliminarDetallePedido(inputDetalle);
      mostrarDetallesPedido();
      muestraDelPrecio.textContent = calcularPrecioPedido();
      envioJson.value = JSON.stringify(detallesPedido);
    };
  });

  // Comprueba si el pedido está vacío y si no lo está, permite el envío
  botonEnviarPedido.addEventListener("click", function(e){
    e.preventDefault();
    if (envioJson.value === "" || envioJson.value === "{\"productos\":{},\"pizzas\":{}}"){
      alert("No se pueden enviar pedidos vacíos. \nEmpty orders are not allowed.");
    }
    else {
      enviarPedido.submit();
    };
  })

});