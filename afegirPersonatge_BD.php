#!/usr/bin/php-cgi

<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Inserir personatge a la base de dades");
    
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Afegir Personatge, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php
  $aliasNou = substr($_POST["usuari"], 0, 5); //creem l'alias a partir del usuari
  $alias = $aliasNou;
  $consultaAlias="SELECT alias
					FROM Personatges
					WHERE alias=:alias";
			
  $comanda = oci_parse($conn, $consultaAlias);
  oci_bind_by_name($comanda,":alias",$alias);
  $exit = oci_execute($comanda);
  $fila=oci_fetch_array($comanda); // no fem control d'errors
  
  while ($fila){ // mentres existeixi un personatge amb l'alias rebut
	$randomNumber = rand(1000, 9999);
	$alias = $aliasNou . $randomNumber;
	$comanda = oci_parse($conn, $consultaAlias);
	oci_bind_by_name($comanda,":alias",$alias);
	$exit = oci_execute($comanda);
	$fila=oci_fetch_array($comanda);
  }
 
 // no existeix cap personatge amb l'alias rebut 
  oci_free_statement($comanda);
  $sentenciaSQL = "INSERT INTO Personatges (alias, despesaMensual, dataCreacio, usuari, tipusPersonatge) 
                   VALUES (:alias, :despesaMensual, TO_DATE(:dataCreacio, 'YYYY-MM-DD'), :usuari, :tipusPersonatge)";
  $comanda = oci_parse($conn, $sentenciaSQL);
  oci_bind_by_name($comanda, ":alias", $alias);
  oci_bind_by_name($comanda, ":despesaMensual", $_POST["despesaMensual"]);
  oci_bind_by_name($comanda, ":dataCreacio", $_POST["dataCreacio"]);
  oci_bind_by_name($comanda, ":usuari", $_POST["usuari"]);
  oci_bind_by_name($comanda, ":tipusPersonatge", $_POST["tipusPersonatge"]);
  $exit = oci_execute($comanda); 
  if ($exit) {
      echo "<p>Nou personatge amb alias " . $alias . " inserit a la base de dades</p>\n";
  } else {
      mostraErrorExecucio($comanda);
  }

  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>