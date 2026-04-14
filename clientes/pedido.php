<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/include/funciones.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "domicilio"){
  inicioHtml("Customizza. Pedido", ["../style/domicilio.css"]);
  ?>
  <header>
    <h1>Customizza</h1>
    <p>Pizzas para todos los gustos</p>
  </header>

  <div class="container">
    <h2>Datos Personales</h2>
    <form id="formDatos" action="seleccion.php" method="post">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="domicilio">
      <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
      <input type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999" required>
      <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pizza a nombre de..." required>
      <input type="submit" value="Continuar">
    </form>
  </div>

  <footer>
    <p>Placeholderdelfooter</p>
  </footer>
  <?php
  finHtml();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "recoger"){
  inicioHtml("Customizza. Pedido", ["../style/domicilio.css"]);
  ?>
  <header>
    <h1>Customizza</h1>
    <p>Pizzas para todos los gustos</p>
  </header>
  <h1>Hacer pedido</h1>
    <form action="seleccion.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="recoger">
      <label for="nombre_cliente">Nombre:</label>
      <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pizza a nombre de... " required/>
      <input type="submit" value="Continuar">
    </form>
    <footer>
    <p>Placeholderdelfooter</p>
  </footer>
  <?php
  finHtml();
}




?>