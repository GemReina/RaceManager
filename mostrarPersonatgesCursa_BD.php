#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Mostrar els personatges participants d'una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);

  $codicursa = $_POST["cursa"];
  
  // Obtenim la data de inici de la cursa desde la base de dades
  $consultaDataInici = "SELECT iniciReal
							FROM Curses
							WHERE codi = :codicursa";
  $comandaDataInici = oci_parse($conn, $consultaDataInici);
  oci_bind_by_name($comandaDataInici, ':codicursa', $codicursa);
  
  if (!$comandaDataInici) { 
    mostraErrorParser($consultaDataInici);
  }
  $exitDataInici = oci_execute($comandaDataInici);
  if (!$exitDataInici) { 
    mostraErrorExecucio($comandaDataInici);
  }
  
  $rowDataInici = oci_fetch_array($comandaDataInici, OCI_ASSOC);
  $iniciReal = strtotime($rowDataInici['INICIREAL']); // Convertim a format de temps UNIX
  
  oci_free_statement($comandaDataInici);
  
  // Determinem si la cursa ja s'ha fet
  $cursaFeta = (time() > $iniciReal);

  if ($cursaFeta) {
	capcalera("Classificació de la cursa");
  
    $consulta="SELECT pc.personatge, pc.vehicle, v.descripcio, nvl(TO_CHAR(pc.temps),'ABANDONAT') AS temps
	             FROM ParticipantsCurses pc JOIN Vehicles v ON v.codi=pc.vehicle
				 WHERE pc.cursa=:codicursa
				 ORDER BY pc.temps ASC";
  } else {
	capcalera("Llistat de participants");
  
    $consulta="SELECT pc.personatge, pc.vehicle, v.descripcio 
	             FROM ParticipantsCurses pc JOIN Vehicles v ON v.codi=pc.vehicle
				 WHERE pc.cursa=:codicursa"; 
  }
  
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda, ':codicursa', $codicursa);
  if (!$comanda) { mostraErrorParser($consulta);} // mostrem error i avortem
  $exit=oci_execute($comanda);
  if (!$exit) { mostraErrorExecucio($comanda);} // mostrem error i avortem
  $numColumnes=oci_num_fields($comanda);
  // mostrem les capceleres
  echo "<table>\n";
  echo "  <tr>";
  for ($i=1;$i<=$numColumnes; $i++) {
    echo "<th>".htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
  }
  echo "</tr>\n";
  // recorrem les files
  while (($row = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "  <tr>";
	foreach ($row as $element) {
	echo "<td>".($element !== null ?
	             htmlentities($element, ENT_QUOTES) : 
			     "&nbsp;") . "</td>";
	}
	echo "</tr>\n";
  }
  echo "</table>\n";

  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
