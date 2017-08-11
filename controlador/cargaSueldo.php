<?php
	$jsonString = str_replace("\\", "", $_POST["dato"]);
	$personas = json_decode($jsonString, false);
	$con = mysql_connect('localhost', 'root', '123');
	mysql_select_db('siscap');

	$respuesta = array();
	$respuesta["msj"] = 1;
	$respuesta["trabajador"] = "";
	foreach($personas -> datos as $fila)
	{
		$sql = "SELECT trabCedula FROM trabajador WHERE trabCedula = '".$fila->cedula."'";
		$res = mysql_query($sql,$con);
		if(!$res) {
			die(mysql_error($con));
		}
		if(mysql_num_rows($res)===0){
			$respuesta["msj"] = 2;
			$respuesta["trabajador"] = $fila->cedula;
			header('Content-type: application/json; charset=utf-8');
			die(json_encode($respuesta));
		}

		$sql = "UPDATE trabajador SET trabSueldo = " . $fila -> sueldo . " WHERE trabCedula='". $fila -> cedula ."'";
		if (!(mysql_query($sql, $con)))
		{
			die("No pudo insertar los datos en la base de datos: " . $sql);
		}
	}
	mysql_close($con);
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($respuesta);

?>