<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/include/funciones.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "domicilio"){
  inicioHtml("Customizza. Pedido", ["../style/domicilio.css"]);
  ?>
  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <div class="container">
    <h2 data-i18n="form_title_delivery">Información de la entrega</h2>
    <form id="formDatos" action="seleccion.php" method="post">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="domicilio">
      <input type="hidden" id="form_idioma" name="form_idioma" value="es">
      <input data-i18n-placeholder="address" type="text" id="direccion" name="direccion" placeholder="Dirección" required>
      <input data-i18n-placeholder="phone" type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999" required>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
  </div>

  <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../js/lang/lang-pedido.js"></script>
  <?php
  finHtml();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "recoger"){
  inicioHtml("Customizza. Pedido", ["../style/domicilio.css"]);
  ?>
  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../imgs/banderauk.jpg" class="boton-idioma">
  </header>
  <h2 data-i18n="form_title_takeaway">Hacer pedido</h2>
    <form action="seleccion.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="recoger">
      <input type="hidden" id="form_idioma" name="form_idioma" value="es">
      <label data-i18n="name_title1" for="nombre_cliente">Nombre:</label>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
    <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../js/lang/lang-pedido.js"></script>
  <?php
  finHtml();
}




?>