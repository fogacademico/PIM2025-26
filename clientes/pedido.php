<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

date_default_timezone_set("Europe/Madrid");

$max_comensales = (obtenerDatosMesas())["comensales_por_mesa"] * (obtenerDatosMesas())["cantidad_mesas"];
$fecha_actual = date("Y-m-d", time());
$hora_actual = date("H:i:s", time());


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
      <!--<input type="hidden" id="form_idioma" name="form_idioma" value="es">-->
      <input data-i18n-placeholder="address" type="text" id="direccion" name="direccion" placeholder="Dirección" required>
      <input data-i18n-placeholder="phone" type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999" required>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
  </div>
  <p><a data-i18n="goback_button" href="./ordenar.php">Volver atrás</a></p>
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
      <!--<input type="hidden" id="form_idioma" name="form_idioma" value="es">-->
      <label data-i18n="name_title1" for="nombre_cliente">Nombre:</label>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
    <p><a data-i18n="goback_button" href="./ordenar.php">Volver atrás</a></p>
    <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../js/lang/lang-pedido.js"></script>
  <?php
  finHtml();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "mesa"){
  inicioHtml("Customizza. Pedido", ["../style/domicilio.css"]);
  ?>
  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <div class="container">
    <h2 data-i18n="form_title_tables">Menú de reserva de mesas</h2>
    <form id="formDatos_mesa" action="gestion-mesas.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="mesa">
      <input type="hidden" id="tipo_reserva1" name="tipo_reserva" class="input-radio" value="pedir_luego">
      <span data-i18n="booking_date">Elige fecha: </span>
      <input type="date" id="fecha_elegida" name="fecha_elegida" value="<?= $fecha_actual ?>">
      <span data-i18n="booking_hour">Elige hora: </span>
      <input type="time" id="elegir_hora" name="elegir_hora" value="<?= $hora_actual ?>"><br/>
      <span data-i18n="guests">Elige número de comensales: </span>
      <input required type="number" id="elegir_comensales" name="elegir_comensales" 
      min="1" max="<?= $max_comensales ?>" value="1" required><br/>
      <input data-i18n-placeholder="phone" type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999" required>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input type="submit" id="operacion" name="operacion" data-i18n-value="submit_button" value="Continuar">
    </form>
  </div>
  <p><a data-i18n="goback_button" href="./index.php">Volver atrás</a></p>
  <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../js/lang/lang-pedido.js"></script>
  <script src="../js/pedido-clientes.js"></script>
  <?php
  finHtml();
}




?>