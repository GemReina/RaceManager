#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
   <title>Exemple PHP: mostrar error d'execució </title>
   <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
   include 'funcions.php';     
   iniciaSessio();
   capcalera("Error amb l'execució de la comanda");
   echo "<p>Oracle informa del següent error:<p>\n";
   echo "<p>Codi error: <tt>" . $_SESSION['ErrorCodi'] . "</tt></p>\n";
   echo "<p>Missatge error: <tt>" . $_SESSION['ErrorMissatge'] . "</tt></p>\n";
   echo "<p>Sentència que ha provocat aquest error: </p>\n<hr>\n";
   echo "<p><tt>" . $_SESSION['ErrorSentencia'] . "</tt></p>\n<hr>\n";
   echo "<p>Posició error: <tt>" . $_SESSION['ErrorOffset'] . "</tt></p>\n";
   peu("Tornar al menú principal","menu.php");
?>
</body>
</html>
