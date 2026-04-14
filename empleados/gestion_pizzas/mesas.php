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


inicioHtml("Mesas reservadas", []);

echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
echo "<h5>(Esta página NO se autorecarga)</h5>";
echo "<h2>MESAS OCUPADAS</h2>";
echo "<h4><a href='mesas.php'>Refrescar</a></h4>";
echo "<h4><a href='../lobby.php'>Atrás</a></h4>";

$db_key = obtenerClavesBD();
// TO FIX. DEFINIR UNA POLITICA DE RESERVAS CLARA
try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT me.id_pedido, me.nmesa, me.hora_reserva, me.comensales FROM mesa as me ";
    $sentence .= "INNER JOIN pedido as pe on pe.id_pedido = me.id_pedido ";
    $sentence .= "WHERE me.hora_reserva > :hora AND (pe.estado IS null OR pe.estado != 'cancelado')";
    $stmt = $pdo->prepare($sentence);
    $stmt -> bindValue(":hora", date("Y-m-d H:i:s", (time() - (60 * 60))));
    $stmt -> execute();
    $filas = $stmt->fetchAll();
    echo "<table border='1'><tr><th>ID</th><th>Numero Mesa</th><th>Hora reserva</th><th>Nº Comensales</th>";
    echo "</tr>";
    foreach ($filas as $row){
      echo "<tr><td><a href='detalles-pedido.php?npedido={$row['id_pedido']}'>{$row['id_pedido']}</a></td>";
      echo "<td>{$row['nmesa']}</td><td>{$row['hora_reserva']}</td>";
      echo "<td>{$row['comensales']}</td></tr>";
    };
    echo "</table>";
    finHtml();
  }
  catch(PDOException $pdoe) {mostrarError($pdoe);} 
  finally {
    $pdo = null;
    $stmt = null;
  }

?>
<?php

finHtml();

?>