<?php

// Función que devuelve las claves de la BD.
function obtenerClavesBD(){
  /* 
  DATOS DE LA BASE DEL ACCESO A LA BASE DE DATOS. Cambiarlos AQUÍ si es necesario
  */
  $usuario_db = "root"; // Nombre de la conexión con la base de datos
  $clave_db = "password"; // Contraseña de la conexión con la base de datos
  $host_db = "localhost"; // Host de la base de datos.
  $puerto_db = "3306"; // Puerto de la base de datos
  $nombre_db = "pizzeria"; // Nombre de la base de datos a la que nos vamos a conectar
  

  $dsn_db = "mysql:host=" . $host_db . ";port=" . $puerto_db . ";dbname=" . $nombre_db . ";charset=utf8mb4";
  $opciones_db = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ];
  $db_keys = [$dsn_db, $usuario_db, $clave_db, $opciones_db];
  return $db_keys;
};

// POLITICA DE MESAS
function obtenerDatosMesas(){
  $datos_mesas = [
    "cantidad_mesas" => 10,
    "comensales_por_mesa" => 6,
    "duracion_uso_mesa" => 60 // Estimada. En minutos.
  ];
  return $datos_mesas;
};


function inicioHtml(string $titulo = "Sin título", array $estilos = [], $lang = "es") { ?>
  <!DOCTYPE html>
  <html lang="<?= $lang ?>">
    <head>
      <title><?=$titulo?></title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
      <meta charset="utf-8"/>
<?php
      foreach($estilos as $hoja) {
        echo "<link type='text/css' rel='stylesheet' href='$hoja'>";
      }
?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body>
<?php
}

function inicioHtmlAutoReload(string $titulo = "Sin título", int $segundos = 60, array $estilos = [], 
$lang = "es") { ?>
  <!DOCTYPE html>
  <html lang="<?= $lang ?>">
    <head>
      <title><?=$titulo?></title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
      <meta charset="utf-8"/>
      <meta http-equiv="refresh" content="<?= $segundos ?>" >
<?php
      foreach($estilos as $hoja) {
        echo "<link type='text/css' rel='stylesheet' href='$hoja'>";
      }
?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body>
<?php
}


function finHtml() {
  echo <<<FIN
  </body>
  </html>
  FIN;
}

function mostrarError(Exception $e): void {
  echo <<<ERROR
  <h3>Error de la aplicación</h3>
  <table>
    <tbody>
      <tr><th>Código de error</th><td>{$e->getCode()}</td></tr>
      <tr><th>Mensaje de error</th><td>{$e->getMessage()}</td></tr>
      <tr><th>Archivo</th><td>{$e->getFile()}</td></tr>
      <tr><th>Línea</th><td>{$e->getLine()}</td></tr>
  ERROR;
}

function apuntarError(Exception $e, $listaErrores){
  $error = <<<ERROR
  <table>
    <tbody>
      <tr><th>Código de error</th><td>{$e->getCode()}</td></tr>
      <tr><th>Mensaje de error</th><td>{$e->getMessage()}</td></tr>
      <tr><th>Archivo</th><td>{$e->getFile()}</td></tr>
      <tr><th>Línea</th><td>{$e->getLine()}</td></tr>
  ERROR;
  array_push($listaErrores, $error);
  return $listaErrores;
};

function hayOtrasCuentas($cuenta = ""){
  $db_key = obtenerClavesBD();
  $resultado = "";
  try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT COUNT(*) AS 'otros_usuarios' FROM usuario WHERE rango != 'superadmin'";
    if ($cuenta != "" && $cuenta != 'pizzaiolo99'){
      $sentence .= "AND nombre_cuenta != '$cuenta' ";
    };
    $sentence .= "GROUP BY nombre_cuenta ";
    $stmt = $pdo->query($sentence);
    $stmt->execute();
    $fila = $stmt->fetch();
    if ($fila){$resultado = $fila['otros_usuarios'];}
    else {$resultado = 0;};
    
  }
  catch (PDOException $pdoe){mostrarError($pdoe);}
  finally {
    $pdo = null;
    $stmt = null;
    return $resultado;
  };
}

function esUsuario($perfil){
    $db_key = obtenerClavesBD();
    try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT nombre_cuenta FROM usuario WHERE nombre_cuenta = :nc";
    $stmt = $pdo->prepare($sentence);
    $stmt->bindValue(":nc", $perfil);
    $stmt->execute();
    $fila = $stmt->fetch();
  }
  catch(PDOException $pdoe){mostrarError($pdoe);}
  finally {
    $pdo = null;
    $stmt = null;
    if ($fila && $fila['nombre_cuenta']){return true;}
    else {return false;};
  }
  }

  function esSuperAdmin($perfil){
    $db_key = obtenerClavesBD();
    try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT nombre_cuenta FROM usuario WHERE nombre_cuenta = :nc AND rango = 'superadmin'";
    $stmt = $pdo->prepare($sentence);
    $stmt->bindValue(":nc", $perfil);
    $stmt->execute();
    $fila = $stmt->fetch();
  }
  catch(PDOException $pdoe){mostrarError($pdoe);}
  finally {
    $pdo = null;
    $stmt = null;
    if ($fila && $fila['nombre_cuenta']){return true;}
    else {return false;};
  }
  }

  // Comprueba si la sesión tiene un nombre de cuenta ya no existente o si el usuario ha sufrido un cambio de rol.
// En este caso, cierra la sesión.
function regularCambiosUsuario($sesion_actual){

  if (!esUsuario($sesion_actual['nombre_cuenta'])){
    $_SESSION['errores'][] = "Se han cambiado datos identificativos de su usuario que han obligado a cerrar su sesión.";
    header("Location: login.php?operacion=logout");
  }
  else {
    $rango_sesion = $sesion_actual['rango'];
    $nc_sesion = $sesion_actual['nombre_cuenta'];
    $db_key = obtenerClavesBD();

    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT rango FROM usuario WHERE nombre_cuenta = :nc && rango = :rango";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":nc", $nc_sesion);
      $stmt->bindValue(":rango", $rango_sesion);
      $stmt->execute();
      $fila = $stmt->fetch();
    }
    catch (PDOException $pdoe){mostrarError($pdoe);}
    finally {
      $pdo = null;
      $stmt = null;
      if (!$fila || !$fila['rango']){
        $_SESSION['errores'][] = "Se han cambiado datos identificativos de su usuario que han obligado a cerrar su sesión.";
        header("Location: login.php?operacion=logout");
      };
    }
  };
};

function obtenerDatosUltimaLinea(){
    $db_key = obtenerClavesBD();
    $ultima_linea = "";
    $cantidad_ultima_linea = "";
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT id_linea, cantidad FROM linea ORDER BY id_linea DESC LIMIT 1";
      $stmt = $pdo->query($sentence);
      $stmt -> execute();
      $filas = $stmt -> fetchAll();
      $ultima_linea = $filas[0]['id_linea'];
      $cantidad_ultima_linea = $filas[0]['cantidad'];
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
      return [$ultima_linea, $cantidad_ultima_linea];
    }
}

function obtenerTipoPedido($idpedido){
    $db_key = obtenerClavesBD();
    try {
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "SELECT me.id_pedido AS pmesa, rec.id_pedido AS precoger, dom.id_pedido AS pdomicilio ";
      $sentence .= "FROM pedido AS pe LEFT JOIN mesa AS me ON pe.id_pedido = me.id_pedido ";
      $sentence .= "LEFT JOIN recoger AS rec ON pe.id_pedido = rec.id_pedido ";
      $sentence .= "LEFT JOIN domicilio AS dom ON pe.id_pedido = dom.id_pedido ";
      $sentence .= "WHERE pe.id_pedido = :idpedido";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":idpedido", $idpedido);
      $stmt -> execute();
      $filas = $stmt -> fetchAll();
      $tipo_pedido = "ERROR. Pedido sin tipo.";
      if ($filas[0]["pmesa"]){$tipo_pedido = "Mesa";}
      else if ($filas[0]["precoger"]){$tipo_pedido = "Recoger";}
      else if ($filas[0]["pdomicilio"]){$tipo_pedido = "A domicilio";}
    }
    catch(PDOException $pdoe) {mostrarError($pdoe);} 
    finally {
      $pdo = null;
      $stmt = null;
      return $tipo_pedido;
    }
}

/* 
FUNCIONES DE GESTIÓN DE RESERVA DE MESAS 
*/

// ESTA FUNCION NO ESTÁ EN USO ACTUALMENTE
function avisaSiMesaLibre($nmesa){ // TO FIX. DEFINIR UNA POLITICA DE RESERVAS CLARA
  $status = true;
  $hora_reserva = "ERROR. La función que avisa si las mesas están libres está funcionando mal.";
  $hora_max_reserva = date("Y-m-d H:i:s", (time() - (60 * 60)));
  $db_key = obtenerClavesBD();
  try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT me.id_pedido, me.nmesa, me.hora_reserva, me.comensales FROM mesa as me ";
    $sentence .= "INNER JOIN pedido as pe on pe.id_pedido = me.id_pedido ";
    $sentence .= "WHERE me.hora_reserva > :hora AND me.nmesa = :nmesa AND (pe.estado IS null OR pe.estado != 'cancelado')";
    $stmt = $pdo->prepare($sentence);
    $stmt -> bindValue(":nmesa", $nmesa);
    $stmt -> bindValue(":hora", $hora_max_reserva);
    $stmt -> execute();
    $fila = $stmt->fetch();
    if (sizeof($fila) > 0){
      $status = false;
      $hora_reserva = $fila['hora_reserva'];
    };
  }
  catch(PDOException $pdoe){mostrarError($pdoe);}
  finally {
    $pdo = null;
    $stmt = null;
    return [$status, $hora_reserva];
  };
};

function ubicarEnMesas($fecha, $ncomensales){
  $politica_mesas = obtenerDatosMesas();
  $margen_reserva = date('Y-m-d H:i:s', strtotime($fecha) - ($politica_mesas['duracion_uso_mesa'] * 60));
  $mesas_existentes = range(1, 10);
  $mesas_ocupadas = [];
  $db_key = obtenerClavesBD();
  try {
    $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
    $sentence = "SELECT me.nmesa FROM mesa AS me INNER JOIN pedido AS pe ON me.id_pedido = pe.id_pedido ";
    $sentence .= "WHERE pe.estado != 'cancelado' AND me.hora_reserva <= :fecha_r AND me.hora_reserva >= :margen_r";
    $stmt = $pdo->prepare($sentence);
    $stmt->bindValue(":fecha_r", $fecha);
    $stmt->bindValue(":margen_r", $margen_reserva);
    $stmt->execute();
    $filas = $stmt->fetchAll();
    foreach ($filas as $fila){$mesas_ocupadas[] = $fila['nmesa'];};
  }
  catch (PDOException $pdoe){apuntarError($pdoe, []);}
  finally {
    $pdo = null;
    $stmt = null;
    if (array_diff($mesas_existentes, $mesas_ocupadas) === []){return false;}
    else {
      $mesas_disponibles = array_diff($mesas_existentes, $mesas_ocupadas);
      $mesas_requeridas = intdiv($ncomensales, $politica_mesas['comensales_por_mesa']) + 
      ($ncomensales % $politica_mesas['comensales_por_mesa'] ? 1 : 0);
      if (sizeof($mesas_disponibles) < $mesas_requeridas){return false;}
      else {
        $mesas_asignables = [];
        for($i=0;$i<$mesas_requeridas;$i++){$mesas_asignables[] = $mesas_disponibles[$i];};
        return $mesas_asignables;
      };
    };
  };
};

function asignarMesas($npedido, $fecha, $lista_mesas, $ncomensales, $errores = []){
  $index = 0;
  $guests_left = $ncomensales;
  $politica_mesas = obtenerDatosMesas();
  $max_comensales = $politica_mesas['comensales_por_mesa'];
  $db_key = obtenerClavesBD();
  while($guests_left > 0){
    $comensales_a_asignar = $guests_left > $max_comensales ? $max_comensales : $guests_left;
    $mesa_a_asignar = $lista_mesas[$index];

    try{
      $pdo = new PDO($db_key[0], $db_key[1], $db_key[2], $db_key[3]);
      $sentence = "INSERT INTO mesa (id_pedido, nmesa, hora_reserva, comensales) ";
      $sentence .= "VALUES(:npedido, :mesa_asignada, :fecha, :comensales_asignados)";
      $stmt = $pdo->prepare($sentence);
      $stmt->bindValue(":npedido", $npedido);
      $stmt->bindValue(":mesa_asignada", $mesa_a_asignar);
      $stmt->bindValue(":fecha", $fecha);
      $stmt->bindValue(":comensales_asignados", $comensales_a_asignar);
      $stmt->execute();
    }
    catch(PDOException $pdoe){apuntarError($pdoe, $errores);}
    finally {
      $pdo = null;
      $stmt = null;
    };

    $index += 1;
    $guests_left -= $max_comensales;
  };
};

?>