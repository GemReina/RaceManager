#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Genera les factures d'una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    $cursa=$_POST['cursa'];
	$consultaCursa="SELECT nom FROM Curses WHERE codi=:cursa";
	$comandaCursa = oci_parse($conn, $consultaCursa);
	oci_bind_by_name($comandaCursa,":cursa",$cursa);
	$exit = oci_execute($comandaCursa);
	if (!$exit) {mostraErrorExecucio($comandaCursa);}
	$filaCursa= oci_fetch_array($comandaCursa);
	capcalera("Generar factures de la cursa: " . $filaCursa['NOM']);

	//Primer reunim totes les dades comuns per a totes les factures, el codi de la cursa ja el tenim a $cursa
	//Consultem el valor del iva i el guardem
	$consultaIVA="SELECT valor AS VALOR FROM Parametres WHERE nom='IVA'";
	$comandaIVA = oci_parse($conn, $consultaIVA);
	$exit = oci_execute($comandaIVA);
	if (!$exit) {mostraErrorExecucio($comandaIVA);}
	$filaIVA= oci_fetch_array($comandaIVA, OCI_ASSOC+OCI_RETURN_NULLS);
	$IVA=$filaIVA['VALOR'];
	
	//Consultem el preu del servei i el guardem
	$consultaServei="SELECT valor AS VALOR FROM Parametres WHERE nom='preuServei'";
	$comandaServei = oci_parse($conn, $consultaServei);
	$exit = oci_execute($comandaServei);
	if (!$exit) {mostraErrorExecucio($comandaServei);}
	$filaServei= oci_fetch_array($comandaServei, OCI_ASSOC+OCI_RETURN_NULLS);
	$servei=$filaServei['VALOR'];
	
	//Farem les factures per vehicle. Seleccionem la fila referent als vehicles de la cursa corresponent
	$consultaVehicles="SELECT vehicle AS CODI FROM ParticipantsCurses WHERE cursa=:cursa";
	$comandaVehicles = oci_parse($conn, $consultaVehicles);
	oci_bind_by_name($comandaVehicles,":cursa",$cursa);
	$exit = oci_execute($comandaVehicles);
	if (!$exit) {mostraErrorExecucio($comandaVehicles);}
	
	//Inicialitzem el número de factura per si la taula encara està buida
	$numero=1;
	
	//Amb un bucle podem obtenir les dades restants depenent de cada vehicle, i alhora les inserim a la base de dades
	while($filaVehicles = oci_fetch_array($comandaVehicles, OCI_ASSOC+OCI_RETURN_NULLS)){
		//Primer obtenim el nom del vehicle
		$vehicle=$filaVehicles['CODI'];
		
		//Obtenim el numero de factura
		$consultaNumero="SELECT MAX(numero) AS NUM FROM Factures";
		$comandaNumero = oci_parse($conn, $consultaNumero);
		$exit = oci_execute($comandaNumero);
		if (!$exit) {mostraErrorExecucio($comandaNumero);}
		$filaNumero = oci_fetch_array($comandaNumero, OCI_ASSOC+OCI_RETURN_NULLS);
		if ($filaNumero) {$numero=$filaNumero['NUM']+1;
		}
		
		//Obtenim l'usuari propietari del vehicle
		$consultaPropietari="SELECT propietari AS NOM FROM Vehicles WHERE codi=:codi";
		$comandaPropietari = oci_parse($conn, $consultaPropietari);
		oci_bind_by_name($comandaPropietari,":codi",$vehicle);
		$exit = oci_execute($comandaPropietari);
		if (!$exit) {mostraErrorExecucio($comandaPropietari);}
		$filaPropietari= oci_fetch_array($comandaPropietari, OCI_ASSOC+OCI_RETURN_NULLS);
		$propietari=$filaPropietari['NOM'];
		
		//Obtenim el temps del vehicle durant la cursa. Si el temps es NULL, s'utilitzarà el temps de l'ultim vehicle
		$consultaTemps="SELECT temps AS TEMPS FROM ParticipantsCurses WHERE vehicle=:vehicle";
		$comandaTemps = oci_parse($conn, $consultaTemps);
		oci_bind_by_name($comandaTemps,":vehicle",$vehicle);
		$exit = oci_execute($comandaTemps);
		if (!$exit) {mostraErrorExecucio($comandaTemps);}
		$filaTemps= oci_fetch_array($comandaTemps, OCI_ASSOC+OCI_RETURN_NULLS);
		$temps=$filaTemps['TEMPS'];
		
		if($temps==NULL){
			$consultaMax = "SELECT MAX(temps) AS MAX FROM ParticipantsCurses WHERE cursa=:cursa";
			$comandaMax = oci_parse($conn, $consultaMax);
			oci_bind_by_name($comandaMax,":cursa", $cursa);
			$exit = oci_execute($comandaMax);
			if (!$exit) { mostraErrorExecucio($comandaMax);}
			$filaMax = oci_fetch_array($comandaMax, OCI_ASSOC+OCI_RETURN_NULLS);
			$temps = $filaMax['MAX'];
		}
		
		//Obtenim el cost del combustible. Primer obtindrem el preu del combustible i seguidament el multiplicarem per el temps del vehicle
		$consultaCombustible="SELECT c.preuUnitat AS PREU
								FROM Combustibles c JOIN Vehicles v ON  c.descripcio=v.combustible
								WHERE v.codi=:codi";
		$comandaCombustible= oci_parse($conn, $consultaCombustible);
		oci_bind_by_name($comandaCombustible,":codi",$vehicle);
		$exit = oci_execute($comandaCombustible);
		if (!$exit) {mostraErrorExecucio($comandaCombustible);}
		$filaCombustible= oci_fetch_array($comandaCombustible, OCI_ASSOC+OCI_RETURN_NULLS);
		$combustible=round($filaCombustible['PREU']*$temps, 2);
		
		//Calculem el total de la factura
		$total=round(($combustible+$servei)*(($IVA/100)+1), 2);
		
		//Inserim totes les dades a la base de dades
		$inserirFactura="INSERT INTO Factures (numero, vehicle, propietari, cursa, temps, combustible, servei, iva, total)
							VALUES (:numero, :vehicle, :propietari, :cursa, :temps, :combustible, :servei, :iva, :total)";
		$comandaInserir= oci_parse($conn, $inserirFactura);
		oci_bind_by_name($comandaInserir,":numero",$numero);
		oci_bind_by_name($comandaInserir,":vehicle",$vehicle);
		oci_bind_by_name($comandaInserir,":propietari",$propietari);
		oci_bind_by_name($comandaInserir,":cursa",$cursa);
		oci_bind_by_name($comandaInserir,":temps",$temps);
		oci_bind_by_name($comandaInserir,":combustible",$combustible);
		oci_bind_by_name($comandaInserir,":servei",$servei);
		oci_bind_by_name($comandaInserir,":iva",$IVA);
		oci_bind_by_name($comandaInserir,":total",$total);
		$exit = oci_execute($comandaInserir);
		if (!$exit) {mostraErrorExecucio($comandaInserir);}
	}
	
	echo "<p>Factures de la cursa " .$filaCursa['NOM']. " emeses i inserides a la base de dades.</p>";
	oci_free_statement($comandaCursa);
	oci_free_statement($comandaIVA);
	oci_free_statement($comandaServei);
	oci_free_statement($comandaVehicles);
	oci_free_statement($comandaNumero);
	oci_free_statement($comandaPropietari);
	oci_free_statement($comandaTemps);
	oci_free_statement($comandaMax);
	oci_free_statement($comandaCombustible);
	oci_free_statement($comandaInserir);
	oci_close($conn);
	
	peu("Tornar al menú principal","menu.php");?>
</body>
</html>
