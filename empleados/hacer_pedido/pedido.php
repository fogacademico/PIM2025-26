<?php 
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/jwt/include_jwt.php");

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
    <p>Pizzas para todos los gustos</p>
  </header>

  <div class="container">
    <h2>Datos Personales</h2>
    <form id="formDatos" action="seleccion.php" method="post">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="domicilio">
      <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
      <input type="tel" id="number" name="tlf" placeholder="Teléfono" min="1" max="999999999">
      <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pizza a nombre de...">
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
  inicioHtml("Customizza. Pedido", ["../../style/domicilio.css"]);
  echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
  ?>
  <header>
    <h1>Customizza</h1>
    <p>Pizzas para todos los gustos</p>
  </header>
  <h1>Hacer pedido</h1>
    <form action="seleccion.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="recoger">
      <label for="nombre_cliente">Nombre:</label>
      <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Pizza a nombre de... " />
      <input type="submit" value="Continuar">
    </form>
    <footer>
    <p>Placeholderdelfooter</p>
  </footer>
  <?php
  finHtml();
}




?>