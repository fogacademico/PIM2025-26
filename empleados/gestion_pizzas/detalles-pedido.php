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

date_default_timezone_set("Europe/Madrid");
$db_key = obtenerClavesBD();


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['npedido']) && $_GET['npedido'] > -1){
  $npedido = filter_input(INPUT_GET, 'npedido', FILTER_VALIDATE_INT);
  inicioHtml("Customizza. Detalles pedido $npedido", []);
  try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT * FROM pedido WHERE id_pedido = :npedido";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":npedido", $npedido);
      $stmt -> execute();
      $fila = $stmt->fetch();
      echo "<p>Pedido {$fila['id_pedido']}. Fecha de encargo {$fila['fecha']}. Estado {$fila['estado']}<br>";
      echo "Encargado por {$fila['nombre_cuenta']}: {$fila['nombre_cliente']} </p>";
      echo "<h4><a href='historial.php'>Volver atrás</a></h4>";
      echo "<h2>PRECIO TOTAL: {$fila['precio_total']} Euros</h2>";
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }

  // MOSTRAR TIPO DE PEDIDO CON SUS DETALLES
  $tipo_pedido = obtenerTipoPedido($npedido);
  echo "<p>Tipo de pedido: $tipo_pedido</p>";
  if ($tipo_pedido === "A domicilio"){
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT direccion FROM domicilio WHERE id_pedido = :npedido";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":npedido", $npedido);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      echo "<p>Para la dirección: {$filas[0]['direccion']}</p>";
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }
  }
  else if ($tipo_pedido === "Mesa"){
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT nmesa, hora_reserva, comensales FROM mesa WHERE id_pedido = :npedido";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":npedido", $npedido);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      foreach($filas as $fila){
        echo "<p>Mesa: {$fila['nmesa']}. Fecha y hora: {$fila['hora_reserva']}. Comensales: {$fila['comensales']}.</p>";
      }
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }
  };

  // MOSTRAR PRODUCTOS
  try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT li.cantidad, li.precio_total, pr.nombre, pr.precio_ud FROM pedido AS pe ";
      $sentence .= "INNER JOIN linea li ON li.id_pedido = pe.id_pedido ";
      $sentence .= "INNER JOIN linea_producto AS lipr ON li.id_linea = lipr.id_linea ";
      $sentence .= "INNER JOIN producto AS pr ON lipr.id_prod = pr.id_prod WHERE pe.id_pedido = :npedido;";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":npedido", $npedido);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      if (sizeof($filas) > 0){
        echo "<h5>PRODUCTOS:</h5>";
        foreach ($filas as $row){
          echo "<p>{$row['nombre']} || {$row['precio_ud']} x {$row['cantidad']} = {$row['precio_total']} Euros</p>";
        }
      }
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }

    // OBTENEMOS LOS IDS DE LAS PIZZAS ENCARGADAS
    $pizzas_encargadas = [];
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT lipz.id_pizza ";
      $sentence .= "FROM linea_pizza AS lipz INNER JOIN linea as li ON lipz.id_linea = li.id_linea ";
      $sentence .= "WHERE li.id_pedido = :npedido";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":npedido", $npedido);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      foreach ($filas as $row){
        $pizzas_encargadas[] = $row['id_pizza'];
      }
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
    }

    foreach ($pizzas_encargadas as $pizza_a_mostrar){
      try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT pz.id_pizza, li.cantidad, pz.precio, li.precio_total ";
      $sentence .= "FROM linea as li INNER JOIN linea_pizza AS lipz ON li.id_linea = lipz.id_linea ";
      $sentence .= "INNER JOIN pizza AS pz ON lipz.id_pizza = pz.id_pizza ";
      $sentence .= "WHERE pz.id_pizza = :pizza_a_mostrar ";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":pizza_a_mostrar", $pizza_a_mostrar);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      echo "<h5>PIZZA:</h5>";
      echo "<p>{$filas[0]['cantidad']} pizza(s) a {$filas[0]['precio']} euros la unidad. ";
      echo "SUBTOTAL: {$filas[0]['precio_total']} euros.</p>";
      }
      catch(PDOException $pdoe) {mostrarError($pdoe);} 
      finally {
        $pdo = null;
        $stmt = null;
      }

      try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT ing.nombre_ing ";
      $sentence .= "FROM pizza AS pz INNER JOIN pizza_ingrediente AS pzin ON pz.id_pizza = pzin.id_pizza ";
      $sentence .= "INNER JOIN ingrediente AS ing ON pzin.id_ing = ing.id_ing ";
      $sentence .= "WHERE pz.id_pizza = :pizza_a_mostrar ";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":pizza_a_mostrar", $pizza_a_mostrar);
      $stmt -> execute();
      $filas = $stmt->fetchAll();
      foreach ($filas as $row){echo "<p> - {$row['nombre_ing']}</p>";}
      }
      catch(PDOException $pdoe) {mostrarError($pdoe);} 
      finally {
        $pdo = null;
        $stmt = null;
      }

    }
}
else {
  echo "<h2>ERROR: SE HA INTENTADO CONSULTAR UN PEDIDO INEXISTENTE</h2>";
  echo "<h4><a href='historial.php'>Volver atrás</a></h4>";
};

?>