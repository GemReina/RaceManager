#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: error de login</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <p class="capcalera">Error de connexió</p>
  <?php
  include 'funcions.php';     
	iniciaSessio();
  echo "<p>No m'he pogut connectar a la Base de Dades.</p> \n";
  echo "<p>Repassa usuari/password. He rebut (" . 
        $_SESSION['usuari'] . "/" . $_SESSION['password'] . ")</p>\n";
  peu("Tornar a la pàgina de login","login.html");
  ?>
</body>
</html>
