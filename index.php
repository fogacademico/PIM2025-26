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
    <p>Pizzas para todos los gustos</p>
  </header>

  <main>
    <h2>¿Qué deseas hacer?</h2>

    <div class="buttons">
      <button onclick="window.location.href='./clientes/pedido.php?tipo=domicilio'">Pedir a domicilio</button> <!-- Domicilio-->
      <button onclick="window.location.href='./clientes/pedido.php?tipo=recoger'">Pedir para recoger en local</button> <!--Recoger (Lleva a pedido luego)-->
      <button onclick="window.location.href='./clientes/mesa.html'">Reservar mesa</button> <!--Elegir mesa-->
    </div>

    <div class="login">
      <h4><a href="./empleados/login.php">Login trabajadores</a></h4>
    </div>
  </main>

  <footer>
    <p>Placeholderdelfooter</p>
  </footer>

</body>
</html>
