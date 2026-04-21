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

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
  if (isset($_GET['changestate']) &&  $_GET['changestate'] > -1){
        inicioHtml("Customizza Empleados. Cambiar estado de pedido", ["../../style/historial.css"]);

        echo "<header><h1>Bienvenido/a/e, $nombre_usuario</h1>";
        echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6></header>";
        echo "<div class='container'><main><h2>Últimos pedidos</h2>";

        ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
          <input type="hidden" name="id-pedido" id="id-pedido" value="<?= $_GET['changestate'] ?>">
          <p>¿Qué desea hacer con este pedido?</p>
          <h5>Sin estado: Pedido pagado o del que se espera pago inminente.</h6>
          <h5>Cancelado: Pedido cancelado.</h6>
          <h5>Impagado: Pedido no pagado. El cliente ha hecho un simpa o nos lo ha dejado a deber.</h6>
          <select id="estado-pedido" name="estado-pedido" size="3">
            <option value="sin-estado" selected>Sin estado</option>
            <option value="cancelado">Cancelar</option>
            <option value="impagado">Declarar como impagado</option>
          </select><br/>
          <input class='btn-terminar' type="submit" value="Cambiar Estado">
        </form>
        <h4>-<a class='opcion-navegacion' href="cambiar-estado.php">Atrás</a></h4>
        </main>
        </div>
        
        <?php

        echo "<h4><a href='cambiar-estado.php'>Atrás</a></h4>";
  }
  else {
    inicioHtml("Customizza Empleados. Cambiar estado de pedido", ["../../style/historial.css"]);

    echo "<header><h1>Bienvenido/a/e, $nombre_usuario</h1>";
    echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6></header>";
    echo "<div class='container'><main><h2>Últimos pedidos</h2>";

    function verPedidosModificables ($key, $marker = false,){
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
          echo "<tr><td><a href='cambiar-estado.php?changestate={$row['id_pedido']}'>{$row['id_pedido']}</a></td>";
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
    <h4>-<a class='opcion-navegacion' href="cambiar-estado.php?show=3days">Ver los pedidos de los últimos 3 días</a></h4>
    <h4>-<a class='opcion-navegacion' href="cambiar-estado.php">Ver los pedidos del mes</a></h4>
    <h4>-<a class='opcion-navegacion' href="cambiar-estado.php?show=all">Ver todos los pedidos</a></h4>
    <h4>-<a class='opcion-navegacion' href="../lobby.php">Atrás</a></h4>
    </main>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && $_GET['show'] === "3days"){
      $three_days_ago = date("Y-m-d H:i:s", (time() - (3 * 24 * 60 * 60)));
      verPedidosModificables($db_key, $three_days_ago);
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && $_GET['show'] === "all"){verPedidosModificables($db_key);}
    else {
      $a_month_ago = date("Y-m-d H:i:s", (time() - (31 * 24 * 60 * 60)));
      verPedidosModificables($db_key, $a_month_ago);};

    echo "</div>";
    finHtml();
  }
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $estados = ["sin-estado", "cancelado", "impagado"];
  $datos = [];
  $datos['id-pedido'] = filter_input(INPUT_POST, "id-pedido", FILTER_VALIDATE_INT);
  $datos['estado-pedido'] = in_array($_POST['estado-pedido'], $estados) ? $_POST['estado-pedido'] : false;

  if (in_array(false, $datos)){
    inicioHtml("Customizza Empleados. Cambiar estado de pedido", ["../../style/historial.css"]);
    echo "<div class='container'><h3>Error en la operación de cambiar estado de pedido. Pedido inexistente o estado erróneo.</h3>";
    echo "<h4><a href='cambiar-estado.php'>Volver atrás</a></h4></div>";
    finHtml();
  }
  else {
    if ($datos['estado-pedido'] === "sin-estado"){$datos['estado-pedido'] = "";};
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "UPDATE pedido SET estado = :estadopedido WHERE id_pedido = :idpedido";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":estadopedido", $datos['estado-pedido']);
      $stmt->bindValue(":idpedido", $datos['id-pedido']);
      $stmt->execute();
    }
    catch(PDOException $pdoe){mostrarError($pdoe);}
    finally {
      $pdo = null;
      $stmt = null;
    }
    inicioHtml("Customizza Empleados. Cambiar estado de pedido", ["../../style/historial.css"]);
    echo "<div class='container'><h3>Pedido {$datos['id-pedido']} modificado con éxito.</h3>";
    echo "<h4><a class='btn-terminar' href='cambiar-estado.php'>Volver atrás</a></h4></div>";
    finHtml();
  }

}
?>
