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

inicioHtml("Customizza Empleados. Ordenar una pizza", ["../../style/index.css"]);
echo "<header><h1>Bienvenido/a/e, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
?>
<body>
    <img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <main>
    <h2 data-i18n="question1">¿Qué deseas hacer?</h2>

    <div class="buttons">
      <button data-i18n="delivery" onclick="window.location.href='./pedido.php?tipo=domicilio'">Pedir a domicilio</button> <!-- Domicilio-->
      <button data-i18n="takeaway" onclick="window.location.href='./pedido.php?tipo=recoger'">Pedir para recoger en local</button> <!--Recoger (Lleva a pedido luego)-->
      <button data-i18n="book_table" onclick="window.location.href='./pedido.php?tipo=mesa'">Reservar mesa</button> <!--Elegir mesa-->
    </div>
    <p><a class="goback_button goback_employees" data-i18n="goback_button" href="../lobby.php">Volver atrás</a></p>
  </main>
  <footer>
    <p data-i18n="footer"> 2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>
  <script src="../../js/lang/lang-index.js"></script>

</body>
</html>


