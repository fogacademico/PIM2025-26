<?php 
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

function comprobarJWT(){

    if (!isset($_COOKIE['jwt'])){header("Location: login.php?operacion=logout");};

    $jwt = $_COOKIE['jwt'];
    $usuario = verificarJWT($jwt);
    if (!$usuario){header("Location: login.php?operacion=logout");};
    return $usuario;
};

$usuario = comprobarJWT();
regularCambiosUsuario($usuario);
$hora = $_SESSION['hora'];
$nombre_cuenta = $usuario['nombre_cuenta'];
$rango = $usuario['rango'];
$nombre_usuario = $usuario['nombre_usuario'];


inicioHtml("Customizza Lobby", []);

echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";

?>
<h4><a href="./gestion_pizzas/historial.php">Ver pedidos</a></h4>
<h4><a href="./hacer_pedido/ordenar.php">Ordenar pedido</a></h4>
<h4><a href="./gestion_pizzas/cambiar-estado.php">Cambiar estado de un pedido (Normal, cancelado o impagado)</a></h4>
<h4><a href="./gestion_pizzas/ingredientes.php">Ver ingredientes</a></h4>
<h4><a href="./gestion_pizzas/mesas.php">Ver mesas ocupadas</a></h4>
<?php
if (in_array($rango, ["admin", "superadmin"])){
  echo "<h4><a href='./adminzone/adminzone.php'>Zona sólo para administradores</a></h4>";
}
?>
<h4><a href="login.php?operacion=logout">Cerrar sesión</a></h4>
<?php


finHtml();

?>