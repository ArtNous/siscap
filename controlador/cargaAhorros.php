<?php
	if (!isset($_SESSION)) session_start();
	function verificarPeriodo($periodo)
	{
		$resp = FALSE;
		$sql = "SELECT cajahorroId FROM caja_ahorro WHERE cajahorroId = '" .$periodo."'";
		$res = mysql_query($sql) or die("No se pudo verificar si el periodo es repetido.");
		if(mysql_num_rows($res) > 0){
			$resp = TRUE;
		}
		return $resp;
	}

	function verificarPeriodoPendiente($periodo)
	{
		$resp = FALSE;
		$sql = "SELECT cajahorroId FROM caja_ahorro WHERE cajahorroId = '" .$periodo."' AND cajahorroEstatus = 'Pendiente'";
		$res = mysql_query($sql) or die("No se pudo verificar si el periodo esta pendiente.");
		if(mysql_num_rows($res) > 0){
			$resp = TRUE;
		}
		return $resp;
	}

	$jsonString = str_replace("\\", "", $_POST["dato"]);
	$personas = json_decode($jsonString, FALSE);
	$con = mysql_connect('localhost', 'root', '123');
	mysql_select_db('siscap');
	$arrayStr = explode("-", $_POST["codmes"]);
	$total = $_POST["total"];
	$año = $arrayStr[0];
	$mes = $arrayStr[1];

	if (verificarPeriodo($_POST["codmes"]))
	{
		die("1");
	}
	if (verificarPeriodoPendiente($_POST["codmes"]))
	{
		die("2");
	}

	function convertirFecha_SpanishToEnglish($date)
	{
	    if($date)
	    {
	        $fecha=$date;
	        $hora="";
	 
	        # separamos la fecha recibida por el espacio de separación entre
	        # la fecha y la hora
	        $fechaHora=explode(" ",$date);
	        if(count($fechaHora)==2)
	        {
	            $fecha=$fechaHora[0];
	            $hora=$fechaHora[1];
	        }
	 
	        # cogemos los valores de la fecha
	        $values=preg_split('/(\/|-)/',$fecha);
	        if(count($values)==3)
	        {
	            # devolvemos la fecha en formato ingles
	            if($hora && count(explode(":",$hora))==3)
	            {
	                # si la hora esta separada por : y hay tres valores...
	                $hora=explode(":",$hora);
	                return date("Y/m/d H:i:s",mktime($hora[0],$hora[1],$hora[2],$values[1],$values[0],$values[2]));
	            }else{
	                return date("Y/m/d",mktime(0,0,0,$values[1],$values[0],$values[2]));
	            }
	        }
	    }
	    return "";
	}

	$fechaActual = new DateTime();
	$fechaActual = $fechaActual->format('Y-m-d');
	$desde = convertirFecha_SpanishToEnglish($_POST["desde"]);
	$hasta = convertirFecha_SpanishToEnglish($_POST["hasta"]);

	$sueldo = 0;
	$conteo = 0;
	mysql_query("BEGIN");
	foreach($personas -> datos as $fila)
	{

		// ciclo a ejecutar por cada trabajador del archivo de nomina

		// Obtiene el sueldo del trabajador
		if(($res = mysql_query("SELECT
			trabSueldo AS sueldo
			FROM
			trabajador
			WHERE
			trabCedula = '".$fila->cedula."'
			")))
		{
			if (mysql_num_rows($res) > 0) {
				$conteo++;
				$filaSQL = mysql_fetch_object($res);
				$sueldo = $filaSQL->sueldo;
				$hasta = new DateTime($hasta);
				$hasta = $hasta -> format('Y-m-d');
				$sql = "INSERT INTO detalle_ahorro VALUES
				('".$_POST["codmes"]."','".$hasta."','".$fila->cedula."',".$sueldo.",".$fila->descuento.")";
				if (!(mysql_query($sql))) {
					echo "Error con la consulta " . $sql . "\n";
					echo mysql_error($con);
				}
			} else {
				mysql_query("ROLLBACK");
				mysql_close($con);
				die("$fila->cedula");
			}
			
			// mysql_close($res);
		} // ---------------
		
	}
	mysql_query("COMMIT");
	$sql = " SELECT detahorroMonto FROM detalle_ahorro WHERE detahorroCajahorroId = '".$codmes."' ";
			$detalle = mysql_query($sql) or die (mysql_error());
			$cant=mysql_num_rows($detalle);
			
	$total=0;
	while($row = mysql_fetch_array($detalle)) {					
		$total = $total+$row['detahorroMonto'];					
	}

	$sql = "INSERT INTO caja_ahorro (cajahorroId,cajahorroDesde,cajahorroHasta,cajahorroPorcentaje,cajahorroCantidad,cajahorroTotal,cajahorroEstatus,cajahorroFechaestatus)
	VALUES ('".$codmes."','".$desde."','".$hasta."',10,".$conteo.",".$total.",'Pendiente','".$fechaActual."')";

	$res = mysql_query($sql) or die("No se pudo insertar la quincena seleccionada");
	mysql_close($con);
	echo 5;
?>