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
$nombre_cuenta = $usuario['nombre_cuenta'];
$hora = $_SESSION['hora'];
$rango = $usuario['rango'];
$nombre_usuario = $usuario['nombre_usuario'];

$npedido = $_SESSION['npedido'];

$listaErrores = $_SESSION['errores-intro-pedido'];

$_SESSION['detalles-pedido'] = null;
$_SESSION['errores-intro-pedido'] = null;

inicioHtml("Customizza. Fin pedido", ["../../style/css2.css"]);
echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
if (isset($listaErrores) && sizeof($listaErrores) > 0){
  echo "<h4 data-i18n='error_msg1'>Ha habido un error al enviar tu pedido. Llama por teléfono a Customizza o contacta con un empleado
  de la pizzería para comprobar si tu pedido se ha recibido correctamente o no.</h4>";
  echo "<h4 data-i18n='error_msg2'>El número del pedido potencialmente afectado es el</h4>";
  echo "<h1>$npedido</h1>";
  echo "<h5><span data-i18n='phone_text'>Teléfono de Customizza: </span>957420111</h5>";
}
else {
  ?>
  <img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
  <img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
  <h3 data-i18n="success_msg1">Lograste enviar un pedido.</h3>
  <h2 data-i18n="success_msg2">Tu número de tu último pedido es el</h2>
  <h1><?= $npedido ?></h1>
  <p data-i18n="order_info1">Un encargo promedio suele tardar entre 5 y 15 minutos en prepararse para recoger en local y entre
    25 y 40 minutos en prepararse y entregarse a domicilio.<p>
  <p data-i18n="order_info2">Customizza se reserva el derecho de no aceptar pedidos muy grandes. Si disponemos de un número de teléfono,
    te avisaremos por llamada. Las cancelaciones se hacen por teléfono o en persona.<p>
  <p><span data-i18n='phone_text'>Teléfono de Customizza: </span>957420111<p>
  <?php
};
echo "<p><a href='../lobby.php' data-i18n='go_back'>Volver al menú principal.</a></p>";
echo "<script src='../../js/lang/lang-finalpedido.js'></script>";
finHtml();
?>