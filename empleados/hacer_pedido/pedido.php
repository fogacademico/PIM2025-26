<?php 
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

function comprobarJWT(){

    if (!isset($_COOKIE['jwt'])){header("Location: ../login.php?operacion=logout");};

    $jwt = $_COOKIE['jwt'];
    $usuario = verificarJWT($jwt);
    if (!$usuario){header("Location: ../login.php?operacion=logout");};
    return $usuario;
};

$usuario = comprobarJWT();
regularCambiosUsuario($usuario);
$hora = $_SESSION['hora'];
$nombre_cuenta = $usuario['nombre_cuenta'];
$rango = $usuario['rango'];
$nombre_usuario = $usuario['nombre_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "domicilio"){
  inicioHtml("Customizza. Pedido", ["../../style/domicilio.css"]);
  echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
  ?>
  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <div class="container">
    <h2 data-i18n="form_title_delivery">Información de la entrega</h2>
    <form id="formDatos" action="seleccion.php" method="post">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="domicilio">
      <!--<input type="hidden" id="form_idioma" name="form_idioma" value="es">-->
      <input data-i18n-placeholder="address" type="text" id="direccion" name="direccion" placeholder="Dirección" required>
      <input data-i18n-placeholder="phone" type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999">
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de...">
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
  </div>
  <p><a data-i18n="goback_button" href="../lobby.php">Volver atrás</a></p>
  <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../../js/lang/lang-pedido.js"></script>
  <?php
  finHtml();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['tipo'] === "recoger"){
  inicioHtml("Customizza. Pedido", ["../../style/domicilio.css"]);
  echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
  ?>
  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
  </header>
  <h2 data-i18n="form_title_takeaway">Hacer pedido</h2>
    <form action="seleccion.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="recoger">
      <!--<input type="hidden" id="form_idioma" name="form_idioma" value="es">-->
      <label data-i18n="name_title1" for="nombre_cliente">Nombre:</label>
      <input data-i18n-placeholder="customer_name" type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pedido a nombre de..." required>
      <input data-i18n-value="submit_button" type="submit" value="Continuar">
    </form>
    <p><a data-i18n="goback_button" href="../lobby.php">Volver atrás</a></p>
    <footer>
    <p data-i18n="footer">2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../../js/lang/lang-pedido.js"></script>
  <?php
  finHtml();
}




?>