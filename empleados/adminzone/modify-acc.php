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

if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "Modificar Usuario") ||
    ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['operation']) && $_GET['operation'] === "micuenta")){

  $datos = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "Modificar Usuario"){
    $datos['nombre_cuenta'] = filter_input(INPUT_POST, "nombre_cuenta", FILTER_SANITIZE_SPECIAL_CHARS);

    $db_key = obtenerClavesBD();

    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT nombre_usuario, rango FROM usuario WHERE nombre_cuenta = :nombre_cuenta ";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":nombre_cuenta", $datos['nombre_cuenta']);
      $stmt -> execute();
      $filas = $stmt -> fetchAll();
      $datos['nombre_usuario'] = $filas[0]['nombre_usuario'];
      $datos['rango'] = $filas[0]['rango'];
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }
  }
  else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['operation']) && $_GET['operation'] === "micuenta"){
    if ($rango != "admin"){
      header("Location: adminzone.php");
    }

    $datos['nombre_cuenta'] = $nombre_cuenta;
    $datos['nombre_usuario'] = $nombre_usuario;
    $datos['rango'] = $rango;
  }

  inicioHtml("Customiza Admins", ["../../style/lobby.css"]);
  echo "<header><h3>Nombre actual de la cuenta a modificar: {$datos['nombre_cuenta']}</h3>";
  echo "<h3>Nombre actual de la persona responsable de la cuenta a modificar: {$datos['nombre_usuario']}</h3>";
  $lista_rangos = ['employee' => 'Empleado', 'admin' => 'Administrador', 'superadmin' => 'Super Adminsitrador'];
  echo "<h3>Rango actual de la cuenta a modificar: {$lista_rangos[$datos['rango']]}</h3></header>";
  echo "<div class='container'>";

  ?>
  <h2>Menú de modificación de cuenta</h2>
  <p> Recuerda que no pueden usarse los caracteres &lt; , &gt; , &quot; , &apos; , &amp; ni el espacio en blanco para 
    los nombres de cuenta ni las contraseñas.</p>
  <p> Los cambios al nombre de la persona usuaria no serán visibles para el usuario hasta que cierre sesión. </p>
  <p> Deja en blanco los aspectos que no quieras modificar </p>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <fieldset class="login">
      <input type="text" name="nombre_cuenta" id="nombre_cuenta" placeholder="Nombre de la cuenta">
      <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de la persona usuaria">
      <input type="password" name="contrasenna" id="contrasenna" placeholder="Contraseña">
      <input type="hidden" name="cuenta_antigua" id="cuenta_antigua" value="<?= $datos['nombre_cuenta'] ?>">
      <select name="rango" id="rango">
        <option value="">---</option>
        <option value="employee">Empleado</option>
        <option value="admin">Administrador</option>
      </select>
      <input type="submit" name="operation" id="operation" value="Aplicar Modificaciones">
    </fieldset>
  </form>
  <h4>-<a class='opcion-navegacion' href="../lobby.php">Volver al menú principal</a></h4>
  </div>
  <?php

  finHtml();
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operation']) && $_POST['operation'] === "Aplicar Modificaciones") {
  $datos = [];
  $datos['nombre_cuenta'] = filter_input(INPUT_POST, "nombre_cuenta", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos['nombre_usuario'] = filter_input(INPUT_POST, "nombre_usuario", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos['cuenta_antigua'] = filter_input(INPUT_POST, "cuenta_antigua", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos['contrasenna'] = $_POST['contrasenna'];
  $datos['rango'] = $_POST['rango'];

  if (!in_array($datos['rango'], ['admin', 'employee', ''])){
    inicioHtml("Customizza Admins. Modificar usuario", ["../../style/lobby.css"]);
    echo "<div class='container'>";
    echo "<h2>Alguien ha intentado modificar un usuario con un tipo de cuenta inexistente o prohibido. 
    La operación de modificación de usuario se ha cancelado.</h2>";
    echo "<h4>-<a class='opcion-navegacion' href='adminzone.php'>Volver al menú para administradores</a></h4></div>";
    finHtml();
  }
  else if ($datos['nombre_cuenta'] === "" && $datos['nombre_usuario'] === "" && $datos['contrasenna'] === ""
     && $datos['rango'] === ""){
      inicioHtml("Customizza Admins. Modificar usuario", ["../../style/lobby.css"]);
      echo "<div class='container'>";
      echo "<h2>No se ha producido ningún cambio en el perfil " . $datos['cuenta_antigua'] . ".</h2>";
      echo "<h4>-<a class='opcion-navegacion' href='adminzone.php'>Volver al menú para administradores</a></h4></div>";
      finHtml();
     }
  else if ($datos['nombre_cuenta'] != $datos['cuenta_antigua'] && esUsuario($datos['nombre_cuenta']) ){
      inicioHtml("Customizza Admins. Modificar usuario", ["../../style/lobby.css"]);
      echo "<div class='container'>";
      echo "<h2>Se ha intentado usar un nombre de cuenta ya empleado por otro usuario.</h2>";
      echo "<h4>-<a class='opcion-navegacion' href='adminzone.php'>Volver al menú para administradores</a></h4></div>";
      finHtml();
     }
  else {
    $db_key = obtenerClavesBD();

    try {
      $cambios = [];

      foreach ($datos as $clave => $valor){
        if ($clave != "cuenta_antigua" && $valor != ""){
          $cambios[] = [$clave, $valor];
        }
      }
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "UPDATE usuario SET ";
      for ($i=0; $i<sizeof($cambios); $i++){
        $sentence .= "{$cambios[$i][0]} = :{$cambios[$i][0]}";
        if ($i < (sizeof($cambios) - 1)){$sentence .= ", ";} 
        else {$sentence .= " ";};
      };
      $sentence.= "WHERE nombre_cuenta = :antiguo ";
      $stmt = $pdo->prepare($sentence);
      for ($i=0; $i<sizeof($cambios); $i++){
        if ($cambios[$i][0] === "contrasenna"){$cambios[$i][1] = password_hash($cambios[$i][1], PASSWORD_DEFAULT);};
        $stmt->bindValue(":{$cambios[$i][0]}", $cambios[$i][1]);
      };
      $stmt->bindValue(":antiguo", $datos['cuenta_antigua']);
      $stmt -> execute();
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }
    inicioHtml("Customizza Admins. Modificar usuario", ["../../style/lobby.css"]);
    echo "<div class='container'>";
    echo "<h1>Usuario modificado con éxito</h1>";
    echo "<h4>-<a class='opcion-navegacion' href='adminzone.php'>Volver al menú para administradores</a></h4></div>";
    finHtml();
  }
}
else {
  inicioHtml("Customizza Admins. Modificar usuario", ["../../style/lobby.css"]);
  echo "<header><h1>Bienvenido/a/e a las opciones exclusivas para admins, $nombre_usuario</h1>";
  echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora)</h6></header>";
  ?>
  <div class="container">
  <h2>Menú de modificación de cuentas</h2>
  <p> Seleccione una cuenta que quiera modificar</p>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
      <select name="nombre_cuenta" id="nombre_cuenta">
        <?php
        $db_key = obtenerClavesBD();
          try {
          $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
          $sentence = "SELECT nombre_cuenta, nombre_usuario FROM usuario WHERE nombre_cuenta != :nombre_cuenta ";
          $sentence.=" AND (rango = 'employee' OR rango = 'admin') ORDER BY rango DESC";
          $stmt = $pdo->prepare($sentence);
          $stmt -> bindValue(":nombre_cuenta", $nombre_cuenta);
          $stmt -> execute();
          $filas = $stmt->fetchAll();
          foreach ($filas as $row){
            echo "<option value='{$row['nombre_cuenta']}'>{$row['nombre_cuenta']} ({$row['nombre_usuario']})</option>";
          };
        }
        catch(PDOException $pdoe) {mostrarError($pdoe);} 
        finally {
          $pdo = null;
          $stmt = null;
        }
      ?>
      </select>
      <input type="submit" name="operation" id="operation" value="Modificar Usuario">
  </form>
  <h4>-<a class='opcion-navegacion' href="adminzone.php">Volver al menú para administradores</a></h4>
  </div>
  <?php
  finHtml();
}

?>