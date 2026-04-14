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

inicioHtml("Customizza Empleados. Ordenar una pizza", []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customizza Emplados</title>
  <link rel="stylesheet" href="../../style/css2.css" />

</head>
<body>

  <header>
    <h1>Customizza empleados</h1>
    <h1>Bienvenido/a/e, <?= $nombre_usuario ?></h1>
    <h6>(Rango: <?= $rango ?>. Hora de inicio de sesión: <?= $hora ?>. Cuenta: <?= $nombre_cuenta ?>)</h6>
  </header>

  <main>
    <h2>¿Qué deseas hacer?</h2>

    <div class="buttons">
      <button onclick="window.location.href='pedido.php?tipo=domicilio'">Pedir a domicilio</button> <!-- Domicilio-->
      <button onclick="window.location.href='pedido.php?tipo=recoger'">Pedir para entregar en local</button> <!--Recoger (Lleva a pedido luego)-->
      <button onclick="window.location.href='./clientes/mesa.html'">Reservar mesa</button> <!--Elegir mesa-->
    </div>
  </main>

  <footer>
    <p>Placeholderdelfooter</p>
  </footer>

</body>
</html>


