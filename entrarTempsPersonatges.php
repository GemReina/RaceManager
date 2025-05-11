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
  $consulta="SELECT nom FROM Curses WHERE codi=:cursa";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda,":cursa",$_POST['cursa']);
  $exit = oci_execute($comanda);
  $fila= oci_fetch_array($comanda);
  capcalera("Entrar els temps de la cursa: ". $fila['NOM']); 
?>
  <form action="entrarTempsPersonatges_BD.php" method="post">
<?php 
  $personatges = "SELECT p.usuari, pc.personatge, pc.vehicle, pc.temps
                     FROM ParticipantsCurses pc JOIN Personatges p ON p.alias=pc.personatge
                     WHERE pc.cursa=:cursa";
  $comanda = oci_parse($conn, $personatges);
  oci_bind_by_name($comanda,":cursa",$_POST['cursa']);
  $exit=oci_execute($comanda);
  if (!$exit){
      mostraErrorExecucio($comanda);
  }
  while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
      echo " <p><label>" .$fila['USUARI']. " - " .$fila['PERSONATGE']. " - " .$fila['VEHICLE']. ": </label>";
      echo ' <input type="floatval" value="' .$fila['TEMPS']. '" name="' .$fila['PERSONATGE']. '"></p>' . "\n";
  }
  echo '    <p><input type = "hidden" name="cursa" value="'.$_POST['cursa'].'"></p>';
?>      
    <p><label>&nbsp;</label><input type = "submit" value="Entrar temps"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
