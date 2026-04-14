<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/include/funciones.php");

$npedido = $_SESSION['npedido'];
$listaErrores = $_SESSION['errores-intro-pedido'];

$_SESSION['detalles-pedido'] = null;
$_SESSION['npedido'] = null;
$_SESSION['errores-intro-pedido'] = null;

inicioHtml("Customizza. Fin pedido", []);
if (isset($listaErrores) && sizeof($listaErrores) > 0){
  echo "<h4>Ha habido un error al enviar tu pedido. Llama por teléfono a Customizza o contacta con un empleado
  de la pizzería para comprobar si tu pedido se ha recibido correctamente o no.</h4>";
  echo "<h4>El número del pedido afectado es el</h4>";
  echo "<h1>$npedido</h1>";
}
else {
  ?>
  <h3>Lograste enviar un pedido.</h3>
  <h2>Tu número de pedido es el</h2>
  <h1><?= $npedido ?></h1>
  <p>Un encargo promedio suele tardar entre 5 y 15 minutos en prepararse para recoger en local y entre
    25 y 40 minutos en prepararse y entregarse a domicilio.<p>
  <p>Customizza se reserva el derecho de no aceptar pedidos muy grandes. Si disponemos de un número de teléfono,
    te avisaremos por llamada. Las cancelaciones se hacen por teléfono o en persona.<p>
  <p>Teléfono de Customizza: 957420111<p>
  <?php
};
echo "<p><a href='../index.php'>Volver al menú principal.</a></p>";
finHtml();
?>