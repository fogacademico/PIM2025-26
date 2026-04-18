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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "EliminarUsuario"){

  $datos = [];
  $datos['nombre_cuenta'] = filter_input(INPUT_POST, "nombre_cuenta", FILTER_SANITIZE_SPECIAL_CHARS);

  // It could be a nice idea to have a SQL query to check if the deleted account is by any means a superadmin
  // But I have other priorities now.
  if (esSuperAdmin($datos['nombre_cuenta'])){header("Location: adminzone.php");};

  inicioHtml("Customizza Admins. Eliminar cuenta", []);
  echo "<h1>¿Está usted seguro de que quiere eliminar la cuenta ' {$datos['nombre_cuenta']} '? Esta operación no se puede deshacer</h1>";
  ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <fieldset>
      <input type="hidden" id="nombre_cuenta" name="nombre_cuenta" value="<?= $datos['nombre_cuenta'] ?>">
      <input type="submit" name="operation" id="operation" value="ConfirmarEliminacion">
    </fieldset>
  </form>
  <h3><a href='eliminate-acc.php'>Volver atrás</a></h3>
  <?php
  finHtml();
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "ConfirmarEliminacion") {
  $datos = [];
  $datos['nombre_cuenta'] = filter_input(INPUT_POST, "nombre_cuenta", FILTER_SANITIZE_SPECIAL_CHARS);

  $db_key = obtenerClavesBD();
  
    try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "DELETE FROM usuario WHERE nombre_cuenta = :nombre_cuenta ";
    $stmt = $pdo->prepare($sentence);
    $stmt->bindValue(":nombre_cuenta", $datos['nombre_cuenta']);
    $stmt -> execute();
  }
  catch(PDOException $pdoe) {mostrarError($pdoe);} 
  finally {
    $pdo = null;
    $stmt = null;
  }

  inicioHtml("Customizza Admins. Eliminar cuenta", []);
  echo "<h1>La cuenta ' {$datos['nombre_cuenta']} ' ha sido eliminada con éxito.</h1>";
  echo "<h3><a href='adminzone.php'>Volver al menú para administradores</a></h3>";
  finHtml();
}
else {
  inicioHtml("Customizza Admins. Eliminar cuenta", []);
  echo "<h1>Bienvenido/a/e a las opciones exclusivas para admins, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora)</h6>";
  ?>
  <h2>Menú de eliminación de cuentas</h2>
  <p> Seleccione una cuenta que quiera eliminar </p>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <fieldset> 
      <select name="nombre_cuenta" id="nombre_cuenta">
        <?php
        $db_key = obtenerClavesBD();
        try {
          $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
          $sentence = "SELECT nombre_cuenta, nombre_usuario FROM usuario WHERE rango = 'employee' OR rango = 'admin'";
          $sentence.=" AND nombre_cuenta != :nombre_cuenta ORDER BY rango DESC";
          $stmt = $pdo->prepare($sentence);
          $stmt->bindValue(":nombre_cuenta", $nombre_cuenta);
          $stmt -> execute();
          $filas = $stmt->fetchAll();
          foreach ($filas as $row){
            echo "<option value='{$row['nombre_cuenta']}'>{$row['nombre_cuenta']}( {$row['nombre_usuario']} )</option>";
          };
        }
        catch(PDOException $pdoe) {mostrarError($pdoe);} 
        finally {
          $pdo = null;
          $stmt = null;
        }
      ?>
      </select>
      <input type="submit" name="operation" id="operation" value="EliminarUsuario">
    </fieldset>
  </form>
  <h4><a href="adminzone.php">Volver al menú para administradores</a></h4>
  <?php
  finHtml();
}


?>