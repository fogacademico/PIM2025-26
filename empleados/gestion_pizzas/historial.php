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


inicioHtmlAutoReload("Historial de pedidos", 60, []);

echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
echo "<h5>(Esta página se autorecarga cada minuto)</h5>";
echo "<p>Pulsa en el número identificador del pedido para ver los detalles de este.</p>";
echo "<h2>Últimos pedidos</h2>";

$db_key = obtenerClavesBD();

function verPedidos ($key, $marker = false,){
  try {
    $pdo = new PDO($key[0], $key[1], $key[2], $key[3]);
    $sentence = "SELECT * FROM pedido ";
    
    if ($marker) {
      $sentence .= "WHERE fecha > :fecha ";
    }

    $sentence .= "ORDER BY fecha DESC";

    if ($marker) {
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":fecha", $marker);
    }
    else {
      $stmt = $pdo->query($sentence);
    }
    
    $stmt -> execute();
    $filas = $stmt->fetchAll();
    echo "<table border='1'><tr><th>ID</th><th>Nombre cuenta</th><th>Nombre cliente</th><th>Precio total</th>";
    echo "<th>Fecha</th><th>Estado</th></tr>";
    foreach ($filas as $row){
      echo "<tr><td><a href='detalles-pedido.php?npedido={$row['id_pedido']}'>{$row['id_pedido']}</a></td>";
      echo "<td>{$row['nombre_cuenta']}</td><td>{$row['nombre_cliente']}</td>";
      echo "<td>{$row['precio_total']}</td><td>{$row['fecha']}</td><td>{$row['estado']}</td></tr>";
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
<h4><a href="historial.php?show=3days">Ver los pedidos de los últimos 3 días</a></h4>
<h4><a href="historial.php">Ver los pedidos del mes</a></h4>
<h4><a href="historial.php?show=all">Ver todos los pedidos</a></h4>
<h4><a href="../lobby.php">Atrás</a></h4>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && $_GET['show'] === "3days"){
  $three_days_ago = date("Y-m-d H:i:s", (time() - (3 * 24 * 60 * 60)));
  verPedidos($db_key, $three_days_ago);
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && $_GET['show'] === "all"){verPedidos($db_key);}
else {
  $a_month_ago = date("Y-m-d H:i:s", (time() - (31 * 24 * 60 * 60)));
  verPedidos($db_key, $a_month_ago);};


finHtml();

?>