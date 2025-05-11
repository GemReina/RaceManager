#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Menú</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';
  iniciaSessio();
  // emmagatzem usuari i password en una sessió per tenir-los disponibles 
  if (!empty($_POST['username'])) { // Hem arribat des de login.html
    $_SESSION['usuari'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    // ara comprovem usuari i password intentant establir connexió amb Oracle    
    connecta($conn);
   }
?>
  <h1>Exemple PHP+SQL Oracle+HTML curs 23/24</h1>
  <h2>Operacions disponibles</h2>
  <p> <a class="menu" href="mostrarPersonatges.php">a) Mostrar personatges</a></p>
  <p> <a class="menu" href="afegirPersonatge.php">b) Donar d'alta un personatge</a></p>
  <p> <a class="menu" href="inscriureCursa.php">c) Inscriure en una cursa</a></p>
  <p> <a class="menu" href="entrarTemps.php">d) Entrar temps de tots els participants</a></p>
  <p> <a class="menu" href="mostrarPersonatgesCursa.php">e) Consultar els participants d'una cursa</a></p>
  <?php peu("Tornar a la pàgina de login","practicaPHP.html");?>
</body>
</html>
