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
	$cursa = $_POST['cursa'];
	$consulta="SELECT nom FROM Curses WHERE codi=:cursa";
    $comanda = oci_parse($conn, $consulta);
    oci_bind_by_name($comanda,":cursa",$cursa);
    $exit = oci_execute($comanda);
    $fila= oci_fetch_array($comanda);
    capcalera("Inscriure participants a la cursa: " . $fila['NOM']);
?>

<!--Escollim el personatge-->
	<form action="inscriureCursaParticipants_BD.php" method="post">	
      <label>Personatge: </label> <select name="personatge">
	  <option value="">--sense especificar--</option>
	  
<?php 
    $personatge = "SELECT alias AS NOM FROM Personatges ORDER BY NOM";
    $comanda = oci_parse($conn, $personatge);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['NOM'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "</select></p>";
?>

<!--Escollim el vehicle-->	
      <label>Vehicle: </label> <select name="vehicle">
	  <option value="">--sense especificar--</option>
<?php
    $vehicle = "SELECT codi AS CODI FROM Vehicles ORDER BY CODI";
    $comanda = oci_parse($conn, $vehicle);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['CODI'] . "\">" . $fila['CODI'] . "</option>\n";
    }	
    echo "</select></p>";
	
	echo '<p><input type = "hidden" name="cursa" value="'.$cursa.'"></p>';
	
?>	  

	<p><label>&nbsp;<input type="submit" value="Inscriure"></p><br>
  </form>

<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>