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

$tipos_pedido_validos = ["domicilio", "recoger"];
$detalles_pedido_vacios = ["productos" => [], "pizzas" => []];

function calcularTotalPedido($datos_pedido){
  $total_ped = 0;
  foreach($datos_pedido["productos"] as $prod){
    $total_prod = $prod["precio_ud"] * $prod["cantidad"];
    $total_ped += $total_prod;
  };
  foreach($datos_pedido["pizzas"] as $piz){
    $precio_piz = 0;
    foreach($piz["ingredientes"] as $piz_ingr){
      $precio_piz += $piz_ingr["precioIng"];
    };
    $total_piz = $precio_piz * $piz['cantidad'];
    $total_ped += $total_piz;
  };
  return $total_ped;
};

  $datos_del_json = $_POST['detalles-pedido'];
  $datos_del_json = json_decode($datos_del_json, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
  $_SESSION['detalles-pedido'] = $datos_del_json; // Para transportarlos luego íntegros.


if ($_SERVER['REQUEST_METHOD'] === "POST"){

  if (!isset($datos_del_json) || !$datos_del_json || $datos_del_json === $detalles_pedido_vacios){
    header("Location: seleccion.php");
  }

  if (!isset($_POST['tipo_pedido']) && !in_array($_POST['tipo_pedido'], $tipos_pedido_validos)){
    inicioHtml("Customizza. Confirmar pedido", []);
    echo "<p data-i18n='error_wrong_order_type'>Tipo de pedido no válido. Quizá se deba a un fallo de conexión.</p>";
    echo "<p><a href='../lobby.php' data-i18n='back_to_menu'>Volver al menú principal.</a></p>";
    echo "<script src='../../js/lang/lang-confirmacion.js'></script>";
    finHtml(); 
  }
  else {
    $tipo_pedido = $_POST['tipo_pedido'];
    if (isset($_POST['nombre_cliente'])){
      $nombre_cliente = filter_input(INPUT_POST, "nombre_cliente", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    };
    if (isset($_POST['direccion'])){
      $direccion = filter_input(INPUT_POST, "direccion", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    };
    if (isset($_POST['tlf'])){
      $tlf = filter_input(INPUT_POST, "tlf", FILTER_VALIDATE_INT) ? $_POST['tlf'] : null;};

    inicioHtml("Customizza. Confirmar pedido", []);

  echo "<h4 data-i18n='order_title'>Pedido registrado:</h4>";
  echo "<h5 data-i18n='products_title'>Productos:</h5>";
  foreach($datos_del_json["productos"] as $prod){
    $total_prod = $prod["precio_ud"] * $prod["cantidad"];
    echo "<p>- {$prod["nombre"]}. {$prod["precio_ud"]} x {$prod["cantidad"]} = $total_prod Euros.</p>";
  };
   echo "<h5 data-i18n='pizzas_title'>Pizzas:</h5>";
  foreach($datos_del_json["pizzas"] as $piz){
    $precio_piz = 0;
    $lista_ingr = "";
    foreach($piz["ingredientes"] as $piz_ingr){
      $precio_piz += $piz_ingr["precioIng"];
      $lista_ingr .= "{$piz_ingr["nombre"]} - ";
    };
    $total_piz = $precio_piz * $piz['cantidad'];
    echo "<p>- Pizza ($lista_ingr). $precio_piz x {$piz["cantidad"]} = $total_piz Euros.</p>";
  };

  echo "<h4><span data-i18n='import_title'>TOTAL PEDIDO: </span>" . calcularTotalPedido($datos_del_json) . " Euros.</h4>";

  echo "<h3 data-i18n='navigation'>¿Qué quieres hacer con tu pedido?</h3>";
    ?>
    <form method="POST" action="enviar-pedido.php">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="<?= $tipo_pedido ?>">

      <?php
      if (isset($nombre_cliente)){
        echo "<input type='hidden' id='nombre_cliente' name='nombre_cliente' value='$nombre_cliente'>";
      };
      if ($tipo_pedido === "domicilio"){
        echo "<input type='hidden' id='direccion' name='direccion' value='$direccion'>";
        if (isset($tlf)){
        echo "<input type='hidden' id='tlf' name='tlf' value='$tlf'>";
        };
      };
      ?>
      <input type="submit" id="operacion" name="operacion"  data-i18n-value="send_order" value="Confirmar pedido">
    </form>
      <form method="POST" action="seleccion.php">
      <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="<?= $tipo_pedido ?>">

      <?php
      if (isset($nombre_cliente)){
        echo "<input type='hidden' id='nombre_cliente' name='nombre_cliente' value='$nombre_cliente'>";
      };
      if ($tipo_pedido === "domicilio"){
        echo "<input type='hidden' id='direccion' name='direccion' value='$direccion'>";
        if (isset($tlf)){
        echo "<input type='hidden' id='tlf' name='tlf' value='$tlf'>";
        };
      };
      ?>
      <input type="submit" id="operacion" name="operacion" data-i18n-value="back_to_selection" value="Cambiar pedido">
    </form>
    <script src="../../js/lang/lang-confirmacion.js"></script>
    <?php
    finHtml();
    };
  }
else {header("Location: ../lobby.php");};

?>