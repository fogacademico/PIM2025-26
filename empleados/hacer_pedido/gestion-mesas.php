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
$nombre_cuenta = $usuario['nombre_cuenta'];

$tipos_pedido_validos = ["mesa"];

if ($_SERVER['REQUEST_METHOD'] === "POST"){

  $hora_recibida = strlen($_POST['elegir_hora']) === 5 ? $_POST['elegir_hora'] . ":00" : $_POST['elegir_hora'];

  $fecha_recibida = $_POST['fecha_elegida'] . " " . $hora_recibida;
  $fecha_ahora = time() - 60; // Intento permitir así que se puedan hacer reservas para la hora actual, pero no para antes.

  $fecha = DateTime::createFromFormat("Y-m-d H:i:s", $fecha_recibida) ? $fecha_recibida : "";
  $fecha = strtotime($fecha) > $fecha_ahora ? $fecha : false;

  $comensales = isset($_POST['elegir_comensales']) && intval($_POST['elegir_comensales']) > 0 ? 
  intval($_POST['elegir_comensales']) : false;

  $nombre_cliente = (isset($_POST['nombre_cliente']) && $_POST['nombre_cliente']) ? 
  filter_input(INPUT_POST, 'nombre_cliente', FILTER_SANITIZE_SPECIAL_CHARS) : null; 

  $tipo_pedido = (isset($_POST['tipo_pedido']) && $_POST['tipo_pedido'] && 
  in_array($_POST['tipo_pedido'], $tipos_pedido_validos)) ? $_POST['tipo_pedido'] : false;
  $nombre_cliente = (isset($_POST['nombre_cliente']) && $_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : null;

  if (!$tipo_pedido){
    inicioHtml("Error al enviar pedido", []);
    echo "<h2 data-i18n='error_msg4'>Tipo de pedido no especificado o incorrecto. 
    No sabemos si vienes tú a por la pizza o te la llevamos nosotros.</h2>";
    echo "<p><a href='../lobby.php' data-i18n='go_back'>Volver al menú principal.</a></p>";
    echo "<script src='../js/lang/lang-finalpedido.js'></script>";
    finHtml();
  }
  else if (!$comensales){
    inicioHtml("Error al enviar pedido", []);
    echo "<h2 data-i18n='error_msg6'>No se ha recibido ningún número de comensales o se ha recibido uno incorrecto.</h2>";
    echo "<p><a href='../lobby.php' data-i18n='go_back'>Volver al menú principal.</a></p>";
    echo "<script src='../js/lang/lang-finalpedido.js'></script>";
    finHtml();
  }
  else if (!$fecha){
    inicioHtml("Error al enviar pedido", ["../../style/lobby.css"]);
    echo "<div class='container'>";
    echo "<h2 data-i18n='error_msg7'>No se ha recibido ninguna hora para reservar mesa o se ha recibido una incorrecta.</h2>";
    echo "<p>-<a class='opcion-navegacion' href='../lobby.php' data-i18n='go_back'>Volver al menú principal.</a></p></div>";
    echo "<script src='../js/lang/lang-finalpedido.js'></script>";
    finHtml();
  }
  else if (isset($_POST['tipo_reserva']) && $_POST['tipo_reserva'] === "pedir_luego"){

    // ENVÍO DE UN PEDIDO QUE SÓLO CONTIENE UNA RESERVA DE MESA

    $db_key = obtenerClavesBD();
    $lista_errores = [];

    // ID del producto llamado "Reserva de mesa"
    $id_prod = 1;

    if (!ubicarEnMesas($fecha, $comensales)){
     inicioHtml("Customizza. Mesas no disponibles", ["../../style/lobby.css"]);
     ?>
     <img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
     <img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
     <div class='container'>
     <p><?= $fecha ?></p>
     <p data-i18n='error_msg8'>No hay mesas suficientes a esa hora para tu grupo. Por favor, elija otra hora.</p>
     <p>-<a class="opcion-navegacion" data-i18n='goback_tables' href='pedido.php?tipo=mesa'>Volver al menú de selección de mesa.</a></p>
     <script src="../../js/lang/lang-finalpedido.js"></script>
    </div>
     <?php
     finHtml(); 
    }
    else {
      // AÑADIR PEDIDO
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "INSERT INTO pedido (nombre_cliente, nombre_cuenta, precio_total, fecha, estado) ";
      $sentence .= "VALUES (:nombrecliente, :nombrecuenta, :preciototal, :fecha, :estado)";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":nombrecliente", $nombre_cliente);
      $stmt->bindValue(":nombrecuenta", $nombre_cuenta);
      $stmt->bindValue(":preciototal", 0);
      $stmt->bindValue(":fecha", date("Y-m-d H:i:s", time()));
      $stmt->bindValue(":estado", null);
      $stmt->execute();
    }
    catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
    finally {
      $pdo = null;
      $stmt = null;
    };

    // ALMACENAR ID_PEDIDO PARA USARLA LUEGO
    $ultimo_pedido = "";
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT id_pedido FROM pedido ORDER BY id_pedido DESC LIMIT 1";
      $stmt = $pdo->query($sentence);
      $stmt -> execute();
      $fila = $stmt->fetch();
      $ultimo_pedido = $fila['id_pedido'];
    }
    catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
    finally {
      $pdo = null;
      $stmt = null;
    };

    // AÑADIR LINEA DE PEDIDO PARA PRODUCTO
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO linea (id_pedido, cantidad) ";
        $sentence .= "VALUES (:ultimopedido, :cantidad)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":ultimopedido", $ultimo_pedido);
        $stmt->bindValue(":cantidad", 1);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // AÑADIR FILA DE LINEA-PRODUCTO
      $datos_ultima_linea = obtenerDatosUltimaLinea();
      $ultima_linea = $datos_ultima_linea[0];
      $cantidad_ultima_linea = $datos_ultima_linea[1];

      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO linea_producto (id_linea, id_prod) ";
        $sentence .= "VALUES (:ultimalinea, :idprod)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":idprod", $id_prod);
        $stmt->bindValue(":ultimalinea", $ultima_linea);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // ACTUALIZAR LINEA CON EL PRECIO TOTAL DE SU PRODUCTO (PRECIO_UD * CANTIDAD)
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "UPDATE linea SET precio_total = ";
        $sentence .= "(:cantidadultimalinea * (SELECT precio_ud FROM producto WHERE id_prod = :idprod)) ";
        $sentence .= "WHERE id_linea = :ultimalinea";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":cantidadultimalinea", $cantidad_ultima_linea);
        $stmt->bindValue(":idprod", $id_prod);
        $stmt->bindValue(":ultimalinea", $ultima_linea);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // INSERTAR TIPO DE PEDIDO: MESA
      $mesas = ubicarEnMesas($fecha, $comensales);
      $lista_errores = asignarMesas($ultimo_pedido, $fecha, $mesas, $comensales, $lista_errores);

      $_SESSION['npedido'] = $ultimo_pedido;
      $_SESSION['errores-intro-pedido'] = $lista_errores; 

      $_SESSION['detalles-pedido'] = [
        "productos" => [["cantidad" => 1, "precio_ud" => 0.00, "nombre" => "Reserva de mesa"]],
        "pizzas" => [],
      ];

      header("Location: finalpedido.php");
    };

    }
  else if (isset($_POST['tipo_reserva']) && $_POST['tipo_reserva'] === "pedir_ya"){
    inicioHtml("Customizza", []);
    ?>
    <form id="datos-mesas" action="./seleccion.php" method="POST">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="<?= $tipo_pedido ?>">
      <input type="hidden" id="fecha" name="fecha" value="<?= $fecha ?>">
      <input type="hidden" id="comensales" name="comensales" value="<?= $comensales ?>">
      <?php if ($nombre_cliente){?>
      <input type="hidden" id="nombre_cliente" name="nombre_cliente" value="<?= $nombre_cliente ?>">
      <?php }; ?>
    </form>
    <script src="../../js/gestion-mesas.js"></script>
    <?php
    finHtml();
  }
  else {
    echo "<p>Error no identificado</p>";
    foreach($_POST as $clave => $valor){
      echo "<p>$clave -> $valor</p>";
    };
  };
  
}
else if ($_SERVER['REQUEST_METHOD'] === "GET"){header("Location: ./pedido.php?tipo=mesa");}