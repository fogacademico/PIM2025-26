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


inicioHtml("Lista de ingredientes", []);

echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";

$db_key = obtenerClavesBD();

function verIngredientes ($key, $marker = false){
  try {
    $pdo = new PDO($key[0], $key[1], $key[2], $key[3]);
    $sentence = "SELECT * FROM ingrediente ";
    
    if ($marker) {
      $sentence .= "WHERE aptoparaceliacos_ing = :aptoparaceliacos_ing ";
    }

    $sentence .= "ORDER BY nombre_ing";

    if ($marker) {
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":aptoparaceliacos_ing", $marker);
    }
    else {
      $stmt = $pdo->query($sentence);
    }
    
    $stmt -> execute();
    $filas = $stmt->fetchAll();
    echo "<table border='1'><tr><th>ID</th><th>Ingrediente</th><th>Precio</th><th>Apto para celiacos</th></tr>";
    foreach ($filas as $row){
      echo "<tr><td>{$row['id_ing']}</td><td>{$row['nombre_ing']}</td><td>{$row['precio_ing']}</td>";
      echo "<td>" . ($row['aptoparaceliacos_ing'] ? "Sí" : "No") . "</td></tr>";
    };
    echo "</table>";
    finHtml();
  }
  catch(PDOException $pdoe) {mostrarError($pdoe);} 
  finally {
    $pdo = null;
    $stmt = null;
  }
};

?>
<h4><a href="ingredientes.php?show=intolerance">Ver los ingredientes aptos para celiacos</a></h4>
<h4><a href="ingredientes.php">Ver todos los ingredientes</a></h4>
<h4><a href="../lobby.php">Atrás</a></h4>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && $_GET['show'] === "intolerance"){verIngredientes($db_key, 1);}
else {verIngredientes($db_key);};


finHtml();

?>