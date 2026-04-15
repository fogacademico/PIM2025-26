<?php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customizza</title>
  <link rel="stylesheet" href="./style/css2.css" />

</head>
<body>

  <header>
    <h1>Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="./imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="./imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <main>
    <h2 data-i18n="question1">¿Qué deseas hacer?</h2>

    <div class="buttons">
      <button data-i18n="delivery" onclick="window.location.href='./clientes/pedido.php?tipo=domicilio'">Pedir a domicilio</button> <!-- Domicilio-->
      <button data-i18n="takeaway" onclick="window.location.href='./clientes/pedido.php?tipo=recoger'">Pedir para recoger en local</button> <!--Recoger (Lleva a pedido luego)-->
      <button data-i18n="book_table" onclick="window.location.href='./clientes/mesa.html'">Reservar mesa</button> <!--Elegir mesa-->
    </div>

    <div class="login">
      <h4><a href="./empleados/login.php" data-i18n="login_employees">Acceso para trabajadores</a></h4>
    </div>
  </main>

  <footer>
    <p data-i18n="footer"> 2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>

  <script src="./js/lang/lang-index.js"></script>

</body>
</html>
