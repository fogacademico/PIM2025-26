const form = document.getElementById("pizzaForm");

const listaPedido = document.getElementById("listaPedido");
const resumenTamano = document.getElementById("resumenTamano");
const resumenMasa = document.getElementById("resumenMasa");
const resumenIngredientes = document.getElementById("resumenIngredientes");
const totalPedido = document.getElementById("totalPedido");

let total = 0;

// precios base
const precios = {
  tamaño: {
    "Pequeña": 6,
    "Mediana": 8,
    "Familiar": 10
  },
  ingrediente: 1
};

form.addEventListener("submit", function(e) {
  e.preventDefault();

  // Obtener tamaño
  const tamano = document.querySelector('input[name="tamano"]:checked')?.value;

  // Obtener masa
  const masaTipo = document.querySelector('input[name="masa_tipo"]:checked')?.value;
  const masaEspecial = document.querySelector('input[name="masa_especial"]:checked')?.value;

  // Ingredientes
  const ingredientes = [...document.querySelectorAll('input[name="ingredientes"]:checked')]
    .map(i => i.value);

  if (!tamano || !masaTipo || !masaEspecial) {
    alert("Selecciona todos los campos obligatorios");
    return;
  }

  // Precio
  let precioPizza = precios.tamaño[tamano] || 0;
  precioPizza += ingredientes.length * precios.ingrediente;

  total += precioPizza;

  // 🛒 Añadir a lista
  const li = document.createElement("li");
  li.textContent = `${tamano} (${masaTipo}, ${masaEspecial}) - ${precioPizza}€`;
  listaPedido.appendChild(li);

  // 📋 Resumen
  resumenTamano.textContent = tamano;
  resumenMasa.textContent = `${masaTipo} / ${masaEspecial}`;

  resumenIngredientes.innerHTML = "";
  ingredientes.forEach(ing => {
    const li = document.createElement("li");
    li.textContent = ing;
    resumenIngredientes.appendChild(li);
  });

  totalPedido.textContent = total + "€";

  // Reset formulario
  form.reset();
});