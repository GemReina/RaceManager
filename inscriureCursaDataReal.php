#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Inscriure a tots els participants d'una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
	$cursa=$_POST['cursa'];
	$consulta="SELECT nom FROM Curses WHERE codi=:cursa";
	$comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda,":cursa",$cursa);
	$exit = oci_execute($comanda);
	$fila= oci_fetch_array($comanda);
	capcalera("Afegir la data i la hora del inici de la cursa: " . $fila['NOM']);	
?>
 
  <form action="inscriureCursaDataReal_BD.php" method="post">
  <p><label>Data: </label><input type="date" name="data"> </p>
  <p><label>Hora: </label><input type="time" name="hora"> </p>
 
<?php 
    echo '<p><input type = "hidden" name="cursa" value="'.$cursa.'"></p>';
?>      

  <p><label>&nbsp;</label><input type = "submit" value="Confirmar"></p><br>
  </form>
  
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
