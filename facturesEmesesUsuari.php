#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Factures emeses d'un usuari</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
	$usuari=$_POST['usuari'];
	$consultaUsuari="SELECT nom || ' ' || cognoms AS NOM FROM Usuaris WHERE alias=:alias";
	$comandaUsuari = oci_parse($conn, $consultaUsuari);
	oci_bind_by_name($comandaUsuari,":alias",$usuari);
	$exit = oci_execute($comandaUsuari);
	if (!$exit) {mostraErrorExecucio($comandaUsuari);}
	$filaUsuari= oci_fetch_array($comandaUsuari);
    capcalera("Factures de l’usuari " . $filaUsuari['NOM']);
	
	//Seleccionem la fila referent als vehicles de l'usuari corresponent, utilitzem la taula factures per no obtenir vehicles sense factures
	$consultaVehicles="SELECT vehicle AS VEHICLE
							FROM Factures
							WHERE propietari=:usuari
							GROUP BY vehicle";
	$comandaVehicles = oci_parse($conn, $consultaVehicles);
	oci_bind_by_name($comandaVehicles,":usuari",$usuari);
	$exit = oci_execute($comandaVehicles);
	if (!$exit) {mostraErrorExecucio($comandaVehicles);}
		
	//Amb un bucle obtenim les factures de cada vehicle
	while($filaVehicles = oci_fetch_array($comandaVehicles, OCI_ASSOC+OCI_RETURN_NULLS)){
		//Seleccionem les files de factures del vehicle
		$consultaFactures="SELECT numero AS factura, cursa, temps, combustible, servei, iva AS IVA, total AS TOTAL
								FROM Factures
								WHERE vehicle=:vehicle";
		$comandaFactures = oci_parse($conn, $consultaFactures);
		oci_bind_by_name($comandaFactures,":vehicle",$filaVehicles['VEHICLE']);
		if (!$comandaFactures) { mostraErrorParser($consultaFactures);} // mostrem error i avortem
		$exit=oci_execute($comandaFactures);
		if (!$exit) { mostraErrorExecucio($comandaFactures);} // mostrem error i avortem
		//obtenim el numemero de columnes
		$numColumnes=oci_num_fields($comandaFactures);
		
		//inicialitzem el total final
		$total = 0;
		
		//obtenim la descripció/el nom complet del vehicle actual
		$consultaDesc="SELECT descripcio AS NOM FROM Vehicles WHERE codi=:codi";
		$comandaDesc = oci_parse($conn, $consultaDesc);
		oci_bind_by_name($comandaDesc,":codi",$filaVehicles['VEHICLE']);
		$exit = oci_execute($comandaDesc);
		if (!$exit) {mostraErrorExecucio($comandaDesc);}
		$filaDesc= oci_fetch_array($comandaDesc);
		
		echo "<p>Vehicle " . $filaDesc['NOM'] . "</p>";
		echo "<table>\n";
		echo "<tr>";
		for ($i=1;$i<=$numColumnes; $i++) {
			echo "<th>".htmlentities(oci_field_name($comandaFactures, $i), ENT_QUOTES) . "</th>"; 
		}
		echo "</tr>\n";
		// recorrem les files
		while (($filaFactures = oci_fetch_array($comandaFactures, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
			echo " <tr>";
			foreach ($filaFactures as $element) {
				echo "<td>".($element !== null ? htmlentities($element, ENT_QUOTES) : "&nbsp;") . "</td>";
			}
			echo "</tr>\n";
			//sumem el total de cada factura
			$total += $filaFactures['TOTAL'];
		}
		echo "</table>\n";
		echo "<p>TOTAL: " . $total . "</p></br>";
		
		oci_free_statement($comandaFactures);
		oci_free_statement($comandaDesc);
	}
	oci_free_statement($comandaUsuari);
	oci_free_statement($comandaVehicles);
	
	oci_close($conn);
	
	peu("Tornar al menú principal","menu.php");?>
</body>
</html>
