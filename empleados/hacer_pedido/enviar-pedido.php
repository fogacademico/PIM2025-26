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

$tipos_pedido_validos = ["domicilio", "recoger"];

date_default_timezone_set("Europe/Madrid");

// Para poder revisar errores
$listaErrores = [];

if ($_SERVER['REQUEST_METHOD'] === "POST"){
  if (!isset($_SESSION['detalles-pedido']) || gettype($_SESSION['detalles-pedido']) != "array" 
  || !json_encode($_SESSION['detalles-pedido'])){
    inicioHtml("Error al enviar pedido", []);
    echo "<h2>No se ha recibido ningún pedido detallado.</h2>";
    echo "<p><a href='../lobby.php'>Volver al menú principal.</a></p>";
    finHtml();
  }
  else if (!isset($_POST['tipo_pedido']) || !in_array($_POST['tipo_pedido'], $tipos_pedido_validos)){
    inicioHtml("Error al enviar pedido", []);
    echo "<h2>Tipo de pedido no especificado o incorrecto. 
    No sabemos si vienes tú a por la pizza o te la llevamos nosotros.</h2>";
    echo "<p><a href='../lobby.php'>Volver al menú principal.</a></p>";
    finHtml();
  }
  else if ($_POST['tipo_pedido'] === "domicilio" && 
  (!isset($_POST['direccion']) || !$_POST['direccion'] || gettype($_POST['direccion']) != "string")){
    inicioHtml("Error al enviar pedido", []);
    echo "<h2>Domicilio no especificado en un pedido a domicilio.</h2>";
    echo "<p><a href='../lobby.php'>Volver al menú principal.</a></p>";
    finHtml();
  }


  // TODO: PROBABLEMENTE QUEDEN MÁS ERRORES  IMPORTANTES POR GESTIONAR. COMO DATOS INTRODUCIDOS INCORRECTOS


  else {
    $detalles_pedido = $_SESSION['detalles-pedido'];
    $tipo_pedido = $_POST['tipo_pedido'];

    $nombre_cuenta = $usuario['nombre_cuenta'];
    $nombre_cliente = null;
    if (isset($_POST['nombre_cliente'])){
      $nombre_cliente = filter_input(INPUT_POST, "nombre_cliente", FILTER_SANITIZE_SPECIAL_CHARS);
    };

    if ($tipo_pedido === "domicilio"){
      $direccion = filter_input(INPUT_POST, "direccion", FILTER_SANITIZE_SPECIAL_CHARS);
      $tlf = null;
      if (isset($_POST['tlf'])){
        $tlf = filter_input(INPUT_POST, "tlf", FILTER_VALIDATE_INT) ? $_POST['tlf'] : null;
      };
    };

    // ENVÍO DEL PEDIDO
    $db_key = obtenerClavesBD();

    // AÑADIR PEDIDO
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "INSERT INTO pedido (nombre_cliente, nombre_cuenta, precio_total, fecha, estado) ";
      $sentence .= "VALUES (:nombrecliente, :nombrecuenta, :preciototal, :fecha, :estado)";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":nombrecliente", $nombre_cliente);
      $stmt->bindValue(":nombrecuenta", $nombre_cuenta);
      $stmt->bindValue(":preciototal", null);
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

    // ----------------------------------------------------------------------------------------------
    // Por cada producto:
    foreach($detalles_pedido['productos'] as $claveprod => $producto){
      $id_prod = intval($claveprod);
      $cantidad = intval($producto['cantidad']);
      
      // AÑADIR LINEA DE PEDIDO PARA PRODUCTO
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO linea (id_pedido, cantidad) ";
        $sentence .= "VALUES (:ultimopedido, :cantidad)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":ultimopedido", $ultimo_pedido);
        $stmt->bindValue(":cantidad", $cantidad);
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
    };

    // --------------------------------------------------------------------------------------------------
    // Por cada pizza:
    foreach($detalles_pedido['pizzas'] as $pizza){

      $cantidad = intval($pizza['cantidad']);
      $ingredientes_en_bruto = array_keys($pizza['ingredientes']);
      $ing_seleccionados = [];
      foreach($ingredientes_en_bruto as $valor_bruto){$ing_seleccionados[] = intval($valor_bruto);};


      // CREAR LINEA DE PEDIDO PARA PIZZA
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO linea (id_pedido, cantidad) ";
        $sentence .= "VALUES ( :ultimopedido , :cantidad)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":ultimopedido", $ultimo_pedido);
        $stmt->bindValue(":cantidad", $cantidad);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      $datos_ultima_linea = obtenerDatosUltimaLinea();
      $ultima_linea = $datos_ultima_linea[0];
      $cantidad_ultima_linea = $datos_ultima_linea[1];

      // CREAR PIZZA
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO pizza (precio) VALUES (0) ";
        $stmt = $pdo->query($sentence);
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // OBTENER ID ULTIMA PIZZA
      $ultima_pizza = "";
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "SELECT id_pizza FROM pizza ORDER BY id_pizza DESC LIMIT 1";
        $stmt = $pdo->query($sentence);
        $stmt -> execute();
        $filas = $stmt -> fetchAll();
        $ultima_pizza = $filas[0]['id_pizza'];
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // AÑADIR FILA DE PIZZA-INGREDIENTE
      foreach ($ing_seleccionados as $ing){
        try {
          $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
          $sentence = "INSERT INTO pizza_ingrediente (id_pizza, id_ing) ";
          $sentence .= "VALUES (:idpizza, :ingrediente)";
          $stmt = $pdo->prepare($sentence);
          $stmt->bindValue(":idpizza", $ultima_pizza);
          $stmt->bindValue(":ingrediente", $ing);
          $stmt -> execute();
        }
        catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
        finally {
          $pdo = null;
          $stmt = null;
        }
      };

      // AÑADIR FILA DE LINEA-PIZZA
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO linea_pizza (id_linea, id_pizza) ";
        $sentence .= "VALUES ( :idlinea, :idpizza)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":idlinea", $ultima_linea);
        $stmt->bindValue(":idpizza", $ultima_pizza);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // MODIFICAR PIZZA PARA AÑADIRLE EL PRECIO
      $preciopizza = 0;
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "SELECT SUM(precio_ing) AS preciopizza FROM ingrediente INNER JOIN pizza_ingrediente ON ingrediente.id_ing = 
        pizza_ingrediente.id_ing WHERE id_pizza = :idp ";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":idp", $ultima_pizza);
        $stmt -> execute();
        $fila = $stmt->fetch();
        $preciopizza = $fila['preciopizza'];
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "UPDATE pizza SET precio = ";
        $sentence .= ":preciopizza ";
        $sentence .= "WHERE id_pizza = :idp";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":preciopizza", $preciopizza);
        $stmt->bindValue(":idp", $ultima_pizza);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };

      // ACTUALIZAR LA LINEA DONDE ESTÁ REGISTRADA LA PIZZA
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "UPDATE linea SET precio_total = ";
        $sentence .= "(:cantidad * (SELECT precio FROM pizza WHERE id_pizza = :idpizza)) ";
        $sentence .= "WHERE id_linea = :idlinea";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":cantidad", $cantidad_ultima_linea);
        $stmt->bindValue(":idpizza", $ultima_pizza);
        $stmt->bindValue(":idlinea", $ultima_linea);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };
    };

    // -----------------------

    // METERLE PRECIO TOTAL AL PEDIDO
    $preciopedido = 0;
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT SUM(precio_total) AS preciopedido FROM linea WHERE id_pedido = :ultimopedido";
      $stmt = $pdo->prepare($sentence);
      $stmt -> bindValue(":ultimopedido", $ultimo_pedido);
      $stmt -> execute();
      $fila = $stmt->fetch();
      $preciopedido = $fila['preciopedido'];
    }
    catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
    finally {
      $pdo = null;
      $stmt = null;
    };

    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "UPDATE pedido SET precio_total = :preciopedido ";
      $sentence .= "WHERE id_pedido = :ultimopedido";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":preciopedido", $preciopedido);
      $stmt->bindValue(":ultimopedido", $ultimo_pedido);
      $stmt -> execute();
    }
    catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
    finally {
      $pdo = null;
      $stmt = null;
    };

    if ($tipo_pedido === "recoger"){

      // INSERTAR TIPO DE PEDIDO: RECOGER 
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO recoger (id_pedido) ";
        $sentence .= "VALUES (:ultimopedido)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":ultimopedido", $ultimo_pedido);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };
    }
    else if ($tipo_pedido === "domicilio"){
      // INSERTAR TIPO DE PEDIDO: DOMICILIO
      try {
        $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
        $sentence = "INSERT INTO domicilio (id_pedido, direccion, tlf) ";
        $sentence .= "VALUES (:ultimopedido, :direccion, :tlf)";
        $stmt = $pdo->prepare($sentence);
        $stmt->bindValue(":ultimopedido", $ultimo_pedido);
        $stmt->bindValue(":direccion", $direccion);
        $stmt->bindValue(":tlf", $tlf);
        $stmt -> execute();
      }
      catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
      finally {
        $pdo = null;
        $stmt = null;
      };
    }
    else if ($tipo_pedido === "mesa"){
      /*
// INSERTAR TIPO DE PEDIDO: MESA
    
    try { // TO FIX. DEFINIR UNA POLITICA DE RESERVAS CLARA
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $hora_reserva = date('Y-m-d H:i:s', time()); // ELIMINAR ESTA LÍNEA (?)
      $sentence = "INSERT INTO mesa (id_pedido, nmesa, hora_reserva, comensales) ";
      $sentence .= "VALUES (:ultimopedido, 21, :hora, 4)";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":ultimopedido", $ultimo_pedido);
      $stmt->bindValue(":hora", $hora_reserva);
      $stmt -> execute();
    }
    catch(PDOException $pdoe) {$listaErrores = apuntarError($pdoe, $listaErrores);} 
    finally {
      $pdo = null;
      $stmt = null;
    }

*/    
    };
    $_SESSION['npedido'] = $ultimo_pedido;
    $_SESSION['errores-intro-pedido'] = $listaErrores;
    // echo "<p>A ver qué ha fallado.</p>";
    header("Location: finalpedido.php");

  };
}
else {header("Location: ../lobby.php");};
?>
