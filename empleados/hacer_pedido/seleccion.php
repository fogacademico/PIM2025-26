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

$datos_pedido = [];
$catalogo_productos = [];
$catalogo_ing = [];

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
};

// Obtenemos los datos de los productos
try {
$pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
$sentence = "SELECT * FROM producto";
$stmt = $pdo->query($sentence);
$stmt->execute();
$filas = $stmt->fetchAll();
foreach($filas as $fila){
  $catalogo_productos[$fila['id_prod']] = ["nombre" => $fila['nombre'], "precio_ud" => $fila['precio_ud']];
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
  $catalogo_ing[$fila['id_ing']] = ["nombre_ing" => $fila['nombre_ing'], "precio_ing" => $fila['precio_ing'], 
  "aptoparaceliacos_ing" => $fila['aptoparaceliacos_ing']];
};
}
catch(PDOException $pdoe){mostrarError($pdoe);}
finally {
  $pdo = null;
  $stmt = null;
}

inicioHtml("Customizza. Selección", ["../../style/seleccion.css"]);
echo "<h1>Bienvenido/a/e, $nombre_usuario</h1>";
echo "<h6>(Rango: $rango. Hora de inicio de sesión: $hora. Cuenta: $nombre_cuenta)</h6>";
?>
<main>
  <div class="zona-menus">
<button id="boton-prod">Ver menú de productos</button>
<button id="boton-pizza">Ver menú de pizzas</button><br/>
<div id="menu-prod" class="ocultar">
  <fieldset id="lista-prod">
    <legend>Productos</legend>
    <?php
    foreach ($catalogo_productos as $clave => $valor){
      if ($clave != 1){
        echo "<div><label for='producto-{$clave}'>{$valor['nombre']} </label>";
        echo "<input type='number' id='producto-{$clave}' name='producto-{$clave}' data-nombreprod='{$valor['nombre']}'
        data-precioprod='{$valor['precio_ud']}' min='1' value='1' class='input-producto'
        ><button id='btn-prod-$clave' class='boton-producto'>Añadir al pedido</button></div><br/>";
      };
    };
    ?>
  </fieldset>
  </div>
<form id="menu-pizza" class="ver-menu">
  <fieldset>
    <legend>Pizzas</legend>
    <h5>Masa</h5>
    <p>Elige tu masa</p>
    <input type='checkbox' id='tipo-masa-sin-gluten' name='tipo-masa'> Mostrar masas sin gluten<br/>
    <?php
    foreach ($catalogo_ing as $clave => $valor){
      if ($clave <= $masas){
        if ($clave === 1){
          echo "<select name='masas' id='masas'>";
          echo "<option value='sin-masa'>------</option>";
        };
        $tiene_gluten = $valor['aptoparaceliacos_ing'] ? "true" : "false";
        $display_masa = $tiene_gluten === "false" ? "" : "ocultar";
        echo "<option id='ingr-{$clave}' name='masa' data-nombreingr='{$valor['nombre_ing']}'
        data-precioingr='{$valor['precio_ing']}' value='$clave' data-gluten='$tiene_gluten'
        class='tipo-masa $display_masa'>{$valor['nombre_ing']}</option>";
        if ($clave === $masas){
          echo "</select>";
        };
      }
      else if ($clave > $masas && $clave <= $masas + $bases){
        if ($clave === ($masas + 1)){
          echo "<h5>Base</h5>";
          echo "<p>Elegir al menos una.</p>";
        };
      if ($valor['nombre_ing'] === "Mozzarella"){
          echo "<input type='checkbox' id='ingr-{$clave}' name='bases' data-nombreingr='{$valor['nombre_ing']}'
          data-precioingr='{$valor['precio_ing']}' value='$clave' checked>";
          echo "<label for='ingr-{$clave}'>{$valor['nombre_ing']} <label><br/>";
        }
      else {
        echo "<input type='checkbox' id='ingr-{$clave}' name='bases' data-nombreingr='{$valor['nombre_ing']}'
        data-precioingr='{$valor['precio_ing']}' value='$clave'>";
        echo "<label for='ingr-{$clave}'>{$valor['nombre_ing']} <label><br/>"; 
        };
      }
      else {
          if ($clave === ($masas + $bases + 1)){
          echo "<h5>Ingredientes</h5>";
          echo "<p>Elegir máximo 5.</p>";
          };
          echo "<input type='checkbox' id='ingr-{$clave}' name='toppings' data-nombreingr='{$valor['nombre_ing']}'
          data-precioingr='{$valor['precio_ing']}' value='$clave'>";
          echo "<label for='ingr-{$clave}'>{$valor['nombre_ing']} <label><br/>"; 
        };
    };
    echo "<br/><label for='producto-{$clave}'>Cantidad de pizzas de este tipo: <label>";
    echo "<input type='number' id='pizza-cantidad' name='pizza-cantidad' min='1' value='1'>
    <button id='btn-confirmar-pizza'>Añadir pizza al pedido</button><br/>";
    ?>
  </fieldset>
</form>
</div>
<div class="gestion-seleccion">
  <p>Esto sirve pa confirmar la selección.</p><br/>
  <ul id="panel-seleccionados" class="panel-seleccionados"></ul>
  <p>Total: <span id="precio-pedido">0</span> Euros.</p>
  <form action="./confirmacion.php" method="POST">
    <input type="hidden" id="detalles-pedido" name="detalles-pedido" value="">
    <?php 
    foreach ($datos_pedido as $clave => $valor){
      echo "<input type='hidden' id='$clave' name='$clave' value='$valor'>";
    };
    ?>
    <input type="submit" id="operacion" name="operacion" value="Confirmar selección">
  </form>
</div>
</main>
<p><a href="../lobby.php">Volver al menú principal.</a></p>
<script src="../../js/seleccion.js"></script>
<?php
finHtml();

?>