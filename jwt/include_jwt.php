<?php
function generarJWT(array $usuario): string {
  $cabecera = json_encode(['alg' => "HS256", 'typ' => "JWT"]);
  $payload = json_encode($usuario);

  // Convertir la cabecera y el payload en base64
  $cabecera64 = base64_encode($cabecera);
  $payload64 = base64_encode($payload);

  $cabecera64F = str_replace(["+","/","="], ["-", "_",""], $cabecera64);
  $payload64F = str_replace(["+", "/", "="], ["-","_", ""], $payload64);

  $clave = leerClave();

  $firma = hash_hmac("sha256", $cabecera64F . "." . $payload64F, $clave, true);
  $firma64 = base64_encode($firma);
  $firma64F = str_replace(["+", "/", "="], ["-", "_", ""], $firma64);

  $jwt = $cabecera64F . "." . $payload64F . "." . $firma64F;

  return $jwt;

} 

function verificarJWT(string $jwt): bool | array {

  // 1º Descomponer el JWT en sus tres partes
  $partes = explode(".", $jwt);
  if( count($partes) != 3 ) return false;

  // 2º Obtenemos cada elemento por separado
  list($cabecera64F, $payload64F, $firma64F) = $partes;

  // 3º Obtener la clave y volver a generar la firma
  $clave = leerClave();
  $firma = hash_hmac("sha256", $cabecera64F . "." . $payload64F, $clave, true);
  $firma64 = base64_encode($firma);
  $firma64N = str_replace(["+", "/", "="], ["-", "_", ""], $firma64);

  // 4º Si no coinciden la firma del JWT y la que he generado
  // el JWT no es válido y se devuelve false
  if( $firma64F != $firma64N ) return false;

  // 5º En este punto, JWT es válido, hay que devolver el payload
  $payload64 = str_replace(["-", "_", ""], ["+", "/", "="], $payload64F);
  $payloadJSON = base64_decode($payload64);
  return json_decode($payloadJSON, true);
}

function leerClave(): string {
  if( file_exists($_SERVER['DOCUMENT_ROOT']. "/jwt/clave.txt") ) {
    $pf = fopen($_SERVER['DOCUMENT_ROOT']. "/jwt/clave.txt", "r");
    $clave = fgets($pf);
  }
  else {
    $clave = "abc123";
  }
  return $clave;
}
?>