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
$nombre_cuenta = $usuario['nombre_cuenta'];
$hora = $_SESSION['hora'];
$rango = $usuario['rango'];
$nombre_usuario = $usuario['nombre_usuario'];

$npedido = $_SESSION['npedido'];

$listaErrores = $_SESSION['errores-intro-pedido'];

$_SESSION['detalles-pedido'] = null;
$_SESSION['npedido'] = null;
$_SESSION['errores-intro-pedido'] = null;

inicioHtml("Customizza. Fin pedido", []);
echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";

if (isset($listaErrores) && sizeof($listaErrores) > 0){
  echo "<h4>Ha habido errores a la hora de introducir pedidos. Revisa la lista de pedidos y comprueba si
  tu pedido se ha enviado correctamente o si tiene algún fallo.</h4>";
  echo "<h4>El número del pedido afectado es el $npedido</h4>";
  foreach ($listaErrores as $error_detectado){
    echo $error_detectado;
  };
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

?>
<p><a href="../lobby.php">Volver al menú principal.</a></p>
<?php
finHtml();
?>