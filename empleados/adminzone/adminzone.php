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
$otras_cuentas = hayOtrasCuentas($nombre_cuenta);

if (!in_array($rango, ["admin", "superadmin"])){
  header("Location: lobby.php");
};
inicioHtml("Customizza Admins", []);
echo "<h1>Bienvenido/a/e a las opciones exclusivas para admins, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
?>
<h4><a href="create-acc.php">Crear una cuenta</a></h4>
<?php
  if ($rango === "admin"){
    echo "<h4><a href='modify-acc.php?operation=micuenta'>Modificar mi cuenta</a></h4>";
  }
  if ($otras_cuentas > 0){
    echo "<h4><a href='modify-acc.php'>Modificar la cuenta de un empleado</a></h4>";
    echo "<h4><a href='eliminate-acc.php'>Eliminar una cuenta</a></h4>";
  }
?>
<h4><a href="../lobby.php">Volver al menú principal</a></h4>
<?php
finHtml();
?>



