#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Entrar els temps dels personatges d'una cursa</title>
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
  $nomcursa=$fila['NOM'];
  unset ($_POST['cursa']); // per poder recorrer $_POST amb un foreach per els temps
  capcalera("Temps de la cursa ".$nomcursa." enregistrats"); 
  oci_free_statement($comanda);
  $actualitzacio="UPDATE ParticipantsCurses
					SET temps=:temps
					WHERE personatge=:personatge AND cursa=:cursa";
  $comanda = oci_parse($conn, $actualitzacio);
  oci_bind_by_name($comanda,":cursa",$cursa);
  foreach($_POST AS $clau => $valor) {
    oci_bind_by_name($comanda,":temps",$valor);
    oci_bind_by_name($comanda,":personatge",$clau);
    oci_execute($comanda); // no fem control d'errors
  }
  
  $consultaMillorTemps = "SELECT MIN(temps) AS millorTemps
							  FROM ParticipantsCurses
							  WHERE cursa=:cursa";
  $comandaMillorTemps = oci_parse($conn, $consultaMillorTemps);
  oci_bind_by_name($comandaMillorTemps, ":cursa", $cursa);
  oci_execute($comandaMillorTemps);
  $filaMillorTemps = oci_fetch_array($comandaMillorTemps, OCI_ASSOC);
  $millorTemps = $filaMillorTemps['MILLORTEMPS'];
  
  $actualitzarMillorTemps = "UPDATE Curses SET millorTemps = :millorTemps WHERE codi = :cursa";
  $comandaMillorTemps = oci_parse($conn, $actualitzarMillorTemps);
  oci_bind_by_name($comandaMillorTemps, ":millorTemps", $millorTemps);
  oci_bind_by_name($comandaMillorTemps, ":cursa", $cursa);
  oci_execute($comandaMillorTemps);
  
  
  echo "<p>Temps de la cursa <b>". $nomcursa . "</b> actualitzats.</p>\n";
  echo "<p>Millor temps de la cursa <b>". $nomcursa ."</b> actualitzat.</p>\n";
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Entrar els temps d'una altra cursa","entrarTemps.php");;
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
