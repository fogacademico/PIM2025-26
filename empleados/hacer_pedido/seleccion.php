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

$datos_pedido = [];
$catalogo_productos = [];
$catalogo_ing = [];
$tipos_pedido = ["recoger", "domicilio", "mesa"];

// Datos de los ingredientes
$masas = 18;
$bases = 3;

$db_key = obtenerClavesBD();

// Recogemos los datos del formulario anterior
if (isset($_POST['tipo_pedido']) && $_POST['tipo_pedido'] === "recoger"){
  $datos_pedido['tipo_pedido'] = $_POST['tipo_pedido'];
  $datos_pedido['nombre_cliente'] = filter_input(INPUT_POST, "nombre_cliente", FILTER_SANITIZE_SPECIAL_CHARS);
}
else if (isset($_POST['tipo_pedido']) && $_POST['tipo_pedido'] === "domicilio"){
  $datos_pedido['tipo_pedido'] = $_POST['tipo_pedido'];
  $datos_pedido['direccion'] = filter_input(INPUT_POST, "direccion", FILTER_SANITIZE_SPECIAL_CHARS);
  $datos_pedido['tlf'] = filter_input(INPUT_POST, "tlf", FILTER_VALIDATE_INT);
  $datos_pedido['nombre_cliente'] = filter_input(INPUT_POST, "nombre_cliente", FILTER_SANITIZE_SPECIAL_CHARS);
}
else if (isset($_POST['tipo_pedido']) && $_POST['tipo_pedido'] === "mesa"){
  $datos_pedido['tipo_pedido'] = $_POST['tipo_pedido'];
  $datos_pedido['nombre_cliente'] = filter_input(INPUT_POST, "nombre_cliente", FILTER_SANITIZE_SPECIAL_CHARS);

  $fecha_ahora = time() - 60; // Intento permitir así que se puedan hacer reservas para la hora actual, pero no para antes.

  $fecha = DateTime::createFromFormat("Y-m-d H:i:s", $_POST['fecha']) ? $_POST['fecha'] : "";
  $datos_pedido['fecha'] = strtotime($fecha) > $fecha_ahora ? $fecha : false;

  $datos_pedido['comensales'] = isset($_POST['comensales']) && intval($_POST['comensales']) > 0 ? 
  intval($_POST['comensales']) : false;
};

// Obtenemos los datos de los productos
try {
$pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
$sentence = "SELECT * FROM producto";
$stmt = $pdo->query($sentence);
$stmt->execute();
$filas = $stmt->fetchAll();
foreach($filas as $fila){
  $catalogo_productos[$fila['id_prod']] = ["nombre" => $fila['nombre'], "nombre_en" => $fila['nombre_en'], "precio_ud" => $fila['precio_ud']];
};
}
catch(PDOException $pdoe){mostrarError($pdoe);}
finally {
  $pdo = null;
  $stmt = null;
}

// Obtenemos los datos de los ingredientes
try {
$pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
$sentence = "SELECT * FROM ingrediente";
$stmt = $pdo->query($sentence);
$stmt->execute();
$filas = $stmt->fetchAll();
foreach($filas as $fila){
  $catalogo_ing[$fila['id_ing']] = ["nombre_ing" => $fila['nombre_ing'], "nombre_ing_en" => $fila['nombre_ing_en'], "precio_ing" => $fila['precio_ing'], 
  "aptoparaceliacos_ing" => $fila['aptoparaceliacos_ing']];
};
}
catch(PDOException $pdoe){mostrarError($pdoe);}
finally {
  $pdo = null;
  $stmt = null;
}

inicioHtml("Customizza. Selección", ["../../style/seleccion.css"]);
?>
<p>
  <span data-i18n="ordertype_title">Tipo de pedido: </span>
<?php
if (isset($datos_pedido['tipo_pedido']) && in_array($datos_pedido['tipo_pedido'], $tipos_pedido)){
  echo "<span data-i18n='order_type_{$datos_pedido['tipo_pedido']}'>{$datos_pedido['tipo_pedido']}</span>";
}
else {
  echo "<span data-i18n='order_type_error'>ERROR. Se ha perdido 
      el dato del tipo del pedido. Por favor vuelva atrás.</span>";
};
?>
</p>
<img id="boton-es" src="../../imgs/banderaesp.png" class="boton-idioma">
<img id="boton-en" src="../../imgs/banderauk.jpg" class="boton-idioma">
<p><a data-i18n="goback_button" href="<?= 
(isset($datos_pedido['tipo_pedido']) && $datos_pedido['tipo_pedido'] && in_array($datos_pedido['tipo_pedido'], $tipos_pedido)) ? 
"pedido.php?tipo={$datos_pedido['tipo_pedido']}" : "../lobby.php"
?>">Volver atrás</a></p>
<main>
  <div class="zona-menus">
<button data-i18n="products_menu_button" id="boton-prod">Ver menú de productos</button>
<button data-i18n="pizzas_menu_button"id="boton-pizza">Ver menú de pizzas</button><br/>
<div id="menu-prod" class="ocultar">
  <fieldset id="lista-prod">
    <legend data-i18n="products_title">Productos</legend>
    <?php
    foreach ($catalogo_productos as $clave => $valor){
      if ($clave != 1){
          echo "<div><label for='producto_{$clave}' data-i18n='producto_{$clave}'>{$valor['nombre']} </label>";
          echo "<input type='number' id='producto_{$clave}' name='producto_{$clave}' data-nombreprod='{$valor['nombre']}'
          data-nombreproden='{$valor['nombre_en']}' data-precioprod='{$valor['precio_ud']}' min='1' value='1' 
          class='input-producto' 
          ><button id='btn-prod-$clave' class='boton-producto' data-i18n='add_product' >Añadir al pedido</button></div><br/>";
      };
    };
    ?>
  </fieldset>
  </div>
<form id="menu-pizza" class="ver-menu">
  <fieldset>
    <legend data-i18n="pizzas_title">Pizzas</legend>
    <h5 data-i18n="dough_title">Masa</h5>
    <p data-i18n="dough_desc">Elige tu masa</p>
    <input type='checkbox' id='tipo-masa-sin-gluten' name='tipo-masa'> 
    <span data-i18n="glutenfree_dough">Mostrar masas sin gluten</span><br/>
    <?php
    foreach ($catalogo_ing as $clave => $valor){
      if ($clave <= $masas){
        if ($clave === 1){
          echo "<select name='masas' id='masas'>";
          echo "<option value='sin-masa'>------</option>";
        };
        $tiene_gluten = $valor['aptoparaceliacos_ing'] ? "true" : "false";
        $display_masa = $tiene_gluten === "false" ? "" : "ocultar";
        echo "<option id='ingr_{$clave}' name='masa' data-nombreingr='{$valor["nombre_ing"]}' 
        data-nombreingren='{$valor["nombre_ing_en"]}'
        data-precioingr='{$valor['precio_ing']}' value='$clave' data-gluten='$tiene_gluten'
        class='input-ing tipo-masa $display_masa' data-i18n='ingr_{$clave}'>{$valor["nombre_ing"]}</option>";
        if ($clave === $masas){
          echo "</select>";
        };
      }
      else if ($clave > $masas && $clave <= $masas + $bases){
        if ($clave === ($masas + 1)){
          echo "<h5 data-i18n='base_ingr_title' >Base</h5>";
          echo "<p data-i18n='base_ingr_desc' >Elegir al menos una.</p>";
        };
      if ($valor["nombre_ing"] === "Mozzarella"){
          echo "<input type='checkbox' id='ingr_{$clave}' name='bases' data-nombreingr='{$valor["nombre_ing"]}'
          data-nombreingren='{$valor["nombre_ing_en"]}' data-precioingr='{$valor['precio_ing']}' value='$clave'
          class='input-ing' checked>";
          echo "<label for='ingr_{$clave}' data-i18n='ingr_{$clave}'>{$valor["nombre_ing"]} </label><br/>";
        }
      else {
        echo "<input type='checkbox' id='ingr_{$clave}' name='bases' data-nombreingr='{$valor["nombre_ing"]}'
        data-nombreingren='{$valor["nombre_ing_en"]}' data-precioingr='{$valor['precio_ing']}'
        class='input-ing' value='$clave'>";
        echo "<label for='ingr_{$clave}' data-i18n='ingr_{$clave}'>{$valor["nombre_ing"]} </label><br/>"; 
        };
      }
      else {
          if ($clave === ($masas + $bases + 1)){
          echo "<h5 data-i18n='toppings_title'>Ingredientes</h5>";
          echo "<p data-i18n='toppings_desc'>Elegir máximo 5.</p>";
          };
          echo "<input type='checkbox' id='ingr_{$clave}' name='toppings' data-nombreingr='{$valor["nombre_ing"]}'
          data-nombreingren='{$valor["nombre_ing_en"]}' data-precioingr='{$valor['precio_ing']}'
          class='input-ing' value='$clave'>";
          echo "<label for='ingr_{$clave}' data-i18n='ingr_{$clave}'>{$valor["nombre_ing"]} </label><br/>"; 
        };
    };
    echo "<br/><label for='producto-{$clave}' data-i18n='pizza_amount'>Cantidad de pizzas de este tipo: </label>";
    echo "<input type='number' id='pizza-cantidad' name='pizza-cantidad' min='1' value='1'>
    <button data-i18n='add_pizza' id='btn-confirmar-pizza'>Añadir pizza al pedido</button><br/>";
    ?>
  </fieldset>
</form>
</div>
<div class="gestion-seleccion">
  <h3 data-i18n="order_title">PEDIDO:</h3><br/>
  <ul id="panel-seleccionados" class="panel-seleccionados"></ul>
  <p><span data-i18n="order_import_title">Total: </span><span id="precio-pedido">0</span> Euros.</p>
  <form action="./confirmacion.php" method="POST" id="enviar-pedido">
    <input type="hidden" id="detalles-pedido" name="detalles-pedido" value="">
    <?php 
    foreach ($datos_pedido as $clave => $valor){
      echo "<input type='hidden' id='$clave' name='$clave' value='$valor'>";
    };
    ?>
    <input type="submit" data-i18n-value="send_order" id="operacion" name="operacion" value="Confirmar selección y enviar">
  </form>
</div>
</main>
<script src="../../js/seleccion.js"></script>
<script src="../../js/lang/lang-seleccion.js"></script>
<?php
finHtml();

?>