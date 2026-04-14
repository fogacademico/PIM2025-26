<?php 
session_start();
// QUITAR REFERENCIAS A LA CARPETA PIM EN VERSIONES POSTERIORES
require_once($_SERVER['DOCUMENT_ROOT'] . "/pim/include/funciones.php");

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
  inicioHtml("Customizza. Login para empleados", ["../style/login.css"]);

foreach ($errors as $error){
  echo "<h4>$error</h4>";
};

if (isset($_SESSION['errores'])){
  $_SESSION['errores'] = [];
};
?>

<h2>Acceso de empleados a Customizza</h2>
<form action="./autenticacion/aut.php" method="POST">
  <fieldset class="login">
    <input type="text" name="username" id="username" placeholder="Usuario">
    <input type="password" name="pwd" id="pwd" placeholder="Contraseña">
    <input type="submit" name="operation" id="operation" value="Entrar">
  </fieldset>
</form>
<p><a href="../index.php">Volver a la pantalla principal</a></p>

<?php 
finHtml();

};

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $redir = $_SERVER['PHP_SELF'];
  header("Location: $redir");
};
?>