<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
session_start();

// Función que crea al usuario superadministrador.
function establecerUsuariosBase($key){

  /* 
  DATOS DEL SUPERADMINISTRADOR. Cambiarlos AQUÍ si es necesario
  */
  $superadmin_account = "pizzaiolo99"; // Nombre de la cuenta del superadmin.
  $superadmin_password = '45ni4em5wolla67'; // Contraseña del superadmin. 

  // Creación del superusuario.
  try {
    $pdo = new PDO($key[0], $key[1], $key[2], $key[3]);
    $sentencebase = "INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango) ";
    $pwd = password_hash($superadmin_password, PASSWORD_DEFAULT);
    $sentence1 = $sentencebase . "VALUES (:nombrecuenta, :contrasenna, 'Super Admin', 'superadmin' )"; 
    $stmt = $pdo->prepare($sentence1);
    $stmt->bindValue(":nombrecuenta", $superadmin_account);
    $stmt->bindValue(":contrasenna", $pwd);
    $stmt -> execute();
  }
  catch(PDOException $pdoe) {mostrarError($pdoe);} 
  finally {
    $pdo = null;
    $stmt = null;
  }
};

function recuperarUsuarios($key){
  $resultado = [];
  try {
    $pdo = new PDO($key[0], $key[1], $key[2], $key[3]);
    $stmt = $pdo->query("SELECT * FROM usuario ");
    $stmt -> execute();
    $filas = $stmt->fetchAll();
    foreach ($filas as $row){
      $resultado[$row['nombre_cuenta']] = ['contrasenna' => $row['contrasenna'], 
                                          'rango' => $row['rango'], 
                                          'nombre_usuario' => $row['nombre_usuario']];
    };
  }
  catch(PDOException $pdoe) {mostrarError($pdoe);} 
  finally {
    $pdo = null;
    $stmt = null;
    return $resultado;
  }
};

$redir = "../login.php"; 

$_SESSION['errores'] = [];

if ($_SERVER['REQUEST_METHOD'] === "GET"){
  header("Location: $redir");
  exit(1);
}; 

$db_key = obtenerClavesBD();

$usuarios = recuperarUsuarios($db_key);
if ($usuarios === []){
  establecerUsuariosBase($db_key);
  $usuarios = recuperarUsuarios($db_key);
};

$datos = [];
$datos['username'] = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
$datos['pwd'] = $_POST['pwd'];

if (!array_key_exists($datos['username'], $usuarios)){
  array_push($_SESSION['errores'], "ERROR: Se ha introducido un nombre de usuario inexistente.");
  header("Location: $redir");
  exit(2);
}
else if (!password_verify($datos['pwd'], $usuarios[$datos['username']]['contrasenna'])){
  array_push($_SESSION['errores'], "ERROR: Contraseña incorrecta.");
  header("Location: $redir");
  exit(3);
}
else {
  $payload = ["nombre_cuenta" => $datos["username"], "rango" => $usuarios[$datos['username']]['rango'], 
  "nombre_usuario" => $usuarios[$datos['username']]['nombre_usuario']];

  $jwt = generarJWT($payload);
  setCookie("jwt", $jwt, time() + 60*60, "/"); 

  $_SESSION['hora'] = date('d-m-Y h:i', time());
  header("Location: ../lobby.php"); 
};
?>