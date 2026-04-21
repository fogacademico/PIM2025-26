<?php 
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

$errors = [];

if (isset($_SESSION['errores']) && $_SESSION['errores'] != []){
  foreach ($_SESSION['errores'] as $error){
    $errors[] = $error;
  };
};

$operation = "";
if (isset($_GET['operacion'])){
  $operation = filter_input(INPUT_GET, "operacion", FILTER_SANITIZE_SPECIAL_CHARS);
};

if ($operation === "logout"){
  $idSession = session_name();
  $paramCookie = session_get_cookie_params();
  setcookie($idSession, "", time() - 100, $paramCookie['path'], $paramCookie['domain'], 
  $paramCookie['secure'], $paramCookie['httponly']);

  setcookie("jwt", "", time() - 100, "/");
  session_unset();
  session_destroy();
  session_start();
};

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
  inicioHtml("Customizza. Login para empleados", ["../style/index.css"]);

foreach ($errors as $error){
  echo "<h4 class='error-mostrado'>$error</h4>";
};

if (isset($_SESSION['errores'])){
  $_SESSION['errores'] = [];
};
?>
<p><a class="goback_button" href="../index.php">Volver a la pantalla principal</a></p>
<div class="container">
<h2>Acceso de empleados a Customizza</h2>
<form class="login" action="./autenticacion/aut.php" method="POST">
  <input type="text" name="username" id="username" placeholder="Usuario">
  <input type="password" name="pwd" id="pwd" placeholder="Contraseña">
  <input type="submit" name="operation" id="operation" value="Entrar">
</form>
</div>

<?php 
finHtml();

};

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $redir = $_SERVER['PHP_SELF'];
  header("Location: $redir");
};
?>