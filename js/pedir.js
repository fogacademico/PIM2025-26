const botones = document.querySelectorAll(".item button");
const listaPedido = document.getElementById("listaPedido");
const totalSpan = document.getElementById("total");
const botonContinuar = document.getElementById("continuarPedido");

let pedido = {};
let total = 0;

botones.forEach(boton => {
  boton.addEventListener("click", () => {
    const nombre = boton.dataset.nombre;
    const precio = Number(boton.dataset.precio);

    if (pedido[nombre]) {
      pedido[nombre].cantidad++;
    } else {
      pedido[nombre] = {
        precio: precio,
        cantidad: 1
      };
    }

    total += precio;
    actualizarPedido();
  });
});

function actualizarPedido() {
  listaPedido.innerHTML = "";

  for (const item in pedido) {
    const li = document.createElement("li");
    li.textContent = `${pedido[item].cantidad} x ${item}`;
    listaPedido.appendChild(li);
  }

  totalSpan.textContent = total + "€";
}

// Guardar pedido y pasar a la siguiente página
botonContinuar.addEventListener("click", () => {
  if (Object.keys(pedido).length === 0) {
    alert("No has añadido ninguna pizza 🍕");
    return;
  }

  localStorage.setItem("pedido", JSON.stringify(pedido));
  localStorage.setItem("total", total);

  window.location.href = "seleccion.html";
});
