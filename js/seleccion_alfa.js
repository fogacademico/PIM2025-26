document.addEventListener("DOMContentLoaded", () => {
  const listaPizzas = document.getElementById("lista-pizzas");
  const totalSpan = document.getElementById("total");
  const botonContinuar = document.getElementById("continuar");

  const pedido = JSON.parse(localStorage.getItem("pedido"));
  const total = Number(localStorage.getItem("total"));

  if (!pedido || Object.keys(pedido).length === 0) {
    alert("No hay pedido activo");
    window.location.href = "index.html";
    return;
  }

  // Mostrar pizzas
  for (const nombre in pedido) {
    const li = document.createElement("li");
    li.textContent = `${pedido[nombre].cantidad} x ${nombre}`;
    listaPizzas.appendChild(li);
  }

  // Mostrar total
  totalSpan.textContent = total;

  // Continuar a pago.html
  botonContinuar.addEventListener("click", () => {
    const tipoPedido = document.querySelector(
      'input[name="pedido"]:checked'
    ).value;

    localStorage.setItem("tipoPedido", tipoPedido);

    window.location.href = "pago.html";
  });
});
