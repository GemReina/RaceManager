#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
   <title>Exemple PHP: mostrar error parser </title>
    <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
   include 'funcions.php';     
   iniciaSessio();
   capcalera("Error de parser SQL");
   echo "<p>Oracle informa d'un error al passar pel parser la següent comanda:<p>";
   echo "<hr>\n";
   echo "<p><tt>" . $_SESSION['ErrorParser'] . "</tt></p>\n";
   echo "<hr>";
   peu("Tornar al menú principal","menu.php");
?>
</body>
</html>
