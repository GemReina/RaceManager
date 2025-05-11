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
  capcalera("Cursa tancada"); 
 
  $iniciReal = $_POST["data"] . " " . $_POST["hora"] . ":00" ;
  $sentenciaSQL = "UPDATE Curses
				   SET iniciReal=TO_DATE(:iniciReal, 'YYYY-MM-DD hh24:mi:ss')
				   WHERE codi=:codi";
  $comanda = oci_parse($conn, $sentenciaSQL);
  oci_bind_by_name($comanda, ":iniciReal", $iniciReal);
  oci_bind_by_name($comanda, ":codi", $_POST["cursa"]);
  $exit = oci_execute($comanda); 
  if ($exit){
      $consulta="SELECT nom FROM Curses WHERE codi=:cursa";
	  $comanda = oci_parse($conn, $consulta);
	  oci_bind_by_name($comanda,":cursa",$_POST["cursa"]);
	  $exit = oci_execute($comanda);
	  $fila= oci_fetch_array($comanda);
	  echo "<p>La data d'inici real de la cursa " . $fila['NOM'] . " ha estat establerta pel dia " . $_POST["data"] . " a l'hora " . $_POST["hora"] . ".</p>";
	  echo "<p>Aquesta cursa ha quedat tancada i ja no s'hi podran afegir més participants.</p>";
	  
  } else{
      mostraErrorExecucio($comanda);
  }
  
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
