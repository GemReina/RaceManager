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
  capcalera("Inscriure participants a la cursa: " . $fila['NOM']);	
  
  //Comprovem si ambdos valors tenen el mateix usuari propietari.
  $consulta="SELECT usuari AS USUARI FROM Personatges WHERE alias=:personatge";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda,":personatge",$_POST['personatge']);
  $exit = oci_execute($comanda);
  $filaUsuari= oci_fetch_array($comanda);
  
  $consulta="SELECT propietari AS PROPIETARI FROM Vehicles WHERE codi=:vehicle";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda,":vehicle",$_POST['vehicle']);
  $exit = oci_execute($comanda);
  $filaPropietari= oci_fetch_array($comanda);
  
  if($filaPropietari['PROPIETARI'] !== $filaUsuari['USUARI']){
	  echo "<p>No s'ha pogut afegir el participant perquè el propietari del vehicle y el propietari del personatge son usuaris diferents.</p>\n";
  } else {
	  //Comprovem si l'usuari corresponent ja participa a la carrera.
	  $consulta="SELECT p.usuari
				 FROM ParticipantsCurses pc JOIN Personatges p ON pc.personatge=p.alias
				 WHERE p.usuari=:usuari AND pc.cursa=:cursa";
	  $comanda = oci_parse($conn, $consulta);
	  oci_bind_by_name($comanda,":usuari",$filaUsuari['USUARI']);
	  oci_bind_by_name($comanda,":cursa",$_POST['cursa']);
	  $exit = oci_execute($comanda);
	  $fila= oci_fetch_array($comanda);
	  if($fila){
		echo "<p>No s'ha pogut afegir el participant perquè l'usuari " . $filaUsuari['USUARI'] . " ja participa en aquesta carrera.</p>\n";
	  } else {
		  //Comproven si l'usuari té suficient saldo.
		  $consulta="SELECT saldo AS SALDO FROM Usuaris WHERE alias=:usuari";
		  $comanda = oci_parse($conn, $consulta);
		  oci_bind_by_name($comanda,":usuari",$filaUsuari['USUARI']);
		  $exit = oci_execute($comanda);
		  $filaSaldo= oci_fetch_array($comanda);
		  
		  $consulta="SELECT inscripcio AS INSCRIPCIO FROM Curses WHERE codi=:cursa";
		  $comanda = oci_parse($conn, $consulta);
		  oci_bind_by_name($comanda,":cursa",$_POST['cursa']);
		  $exit = oci_execute($comanda);
		  $filaInscripcio= oci_fetch_array($comanda);
		  
		  if($filaSaldo['SALDO']  < $filaInscripcio['INSCRIPCIO']){
			  echo "<p>No s'ha pogut afegir el participant perquè l'usuari " . $filaUsuari['USUARI'] . " no té suficient saldo per fer la inscripció.</p>\n";
		  } else {
			  //Afegim el participant a la base de dades
			  oci_free_statement($comanda);
			  $sentenciaSQL = "INSERT INTO ParticipantsCurses (cursa, vehicle, personatge) 
							  VALUES (:cursa, :vehicle, :personatge)";
			  $comanda = oci_parse($conn, $sentenciaSQL);
			  oci_bind_by_name($comanda, ":cursa", $cursa);
			  oci_bind_by_name($comanda, ":vehicle", $_POST['vehicle']);
			  oci_bind_by_name($comanda, ":personatge", $_POST['personatge']);
			  $exit = oci_execute($comanda); 
			  if ($exit) {
				  echo "<p>Inscripció del personatge " . $_POST['personatge'] . " amb vehicle " . $_POST['vehicle'] . " guardada a la base de dades.</p>\n";
			  } else {
				  mostraErrorExecucio($comanda);
			  }
		  }
	  }
  }
  
?>
  <form action="inscriureCursaParticipants.php" method="post">
<?php echo '<p><input type = "hidden" name="cursa" value="'.$cursa.'"></p>'; ?>
	<p><label>&nbsp;<input type="submit" value="Inscriure un altre personatge"></p><br>
  </form>
  
  <form action="inscriureCursaDataReal.php" method="post">
<?php echo '<p><input type = "hidden" name="cursa" value="'.$cursa.'"></p>'; ?>
	<p><label>&nbsp;<input type="submit" value="Fixar una data i tancar la carrera"></p><br>
  </form>

<?php
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
