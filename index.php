<?php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customizza</title>
  <link rel="stylesheet" href="./style/index.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>

  <header>
    <h1 class="main-title">Customizza</h1>
    <p data-i18n="slogan">Pizzas para todos los gustos</p>
    <img id="boton-es" src="./imgs/banderaesp.png" class="boton-idioma">
    <img id="boton-en" src="./imgs/banderauk.jpg" class="boton-idioma">
  </header>

  <main>
    <h2 class="question1" data-i18n="question1">¿Qué deseas hacer?</h2>

    <div class="buttons">
      <button data-i18n="delivery" onclick="window.location.href='./clientes/pedido.php?tipo=domicilio'">Pedir a domicilio</button> <!-- Domicilio-->
      <button data-i18n="takeaway" onclick="window.location.href='./clientes/pedido.php?tipo=recoger'">Pedir para recoger en local</button> <!--Recoger (Lleva a pedido luego)-->
      <button data-i18n="book_table" onclick="window.location.href='./clientes/pedido.php?tipo=mesa'">Reservar mesa</button> <!--Elegir mesa-->
    </div>
  </main>

  <footer>
    <p><a class="login" href="./empleados/login.php" data-i18n="login_employees">Acceso para trabajadores</a></p>
    <p data-i18n="footer"> 2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web</p>
  </footer>

  <script src="./js/lang/lang-index.js"></script>

</body>
</html>
