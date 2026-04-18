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

if (!in_array($rango, ["admin", "superadmin"])){
  header("Location: lobby.php");
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "CrearUsuario"){

  $datos = [];
  $datos['nombre_cuenta'] = filter_input(INPUT_POST, "nombre_cuenta", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos['nombre_usuario'] = filter_input(INPUT_POST, "nombre_usuario", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos['contrasenna'] = $_POST['contrasenna'];
  if (!in_array($_POST['rango'], ['admin', 'employee'])){
    inicioHtml("Customizza Admins. Crear cuenta", []);
    echo "<h2>Alguien ha intentado crear un tipo de cuenta inexistente o prohibido. La operación de creación 
    de usuario se ha cancelado.</h2>";
  }
  else if (!$_POST['nombre_cuenta'] || esUsuario($_POST['nombre_cuenta'])){
    inicioHtml("Customizza Admins. Crear cuenta", []);
    echo "<h2>Nombre de cuenta inválido. Puede que ya exista una cuenta con ese nombre.</h2>";
  }
  else {
    $datos['rango'] = $_POST['rango'];

    $db_key = obtenerClavesBD();
    
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentencebase = "INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango) ";
      $pwd = password_hash($datos['contrasenna'], PASSWORD_DEFAULT);
      $sentence = $sentencebase . "VALUES ('" . $datos['nombre_cuenta'] . "', '" . $pwd . "', '" . $datos['nombre_usuario'];
      $sentence .= "', '" . $datos['rango'] . "' )"; 
      $stmt = $pdo->query($sentence);
      // $stmt -> execute();
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }
    inicioHtml("Customizza Admins. Crear cuenta", []);
    echo "<h1>Usuario " . $datos['nombre_cuenta'] . " añadido con éxito</h1>";
  }
  echo "<h4><a href='create-acc.php'>Volver al menú de Crear Cuenta</a></h4>";
  finHtml();
}

else {
  inicioHtml("Customizza Admins. Crear cuenta", []);
  echo "<h1>Bienvenido/a/e a las opciones exclusivas para admins, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora)</h6>";
  ?>
  <h2>Menú de creación de cuenta</h2>
  <p> Recuerda que no pueden usarse los caracteres &lt; , &gt; , &quot; , &apos; , &amp; ni el espacio en blanco.</p>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <fieldset class="login">
      <input type="text" name="nombre_cuenta" id="nombre_cuenta" placeholder="Nombre de la cuenta">
      <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de la persona usuaria">
      <input type="password" name="contrasenna" id="contrasenna" placeholder="Contraseña">
      <select name="rango" id="rango">
        <option value="employee">Empleado</option>
        <option value="admin">Administrador</option>
      </select>
      <input type="submit" name="operation" id="operation" value="CrearUsuario">
    </fieldset>
  </form>
  <h4><a href="adminzone.php">Volver al menú para administradores</a></h4>
  <?php
  finHtml();
}


?>