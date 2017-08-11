<?php
	date_default_timezone_set('America/Caracas');
    if (!isset($_SESSION)) session_start();
    include('../modelo/prestamos.class.php');
    include('../modelo/detalle_prestamo.class.php');
	$usuario = $_SESSION["usuId"];

    $prestamo = new prestamos();
    $detalle_prestamo = new detalle_prestamo();

	$jsonString = str_replace("\\", "", $_POST["dato"]);
	$personas = json_decode($jsonString, false);
	$con = mysql_connect('localhost', 'root', 'programacion');
	mysql_select_db('siscap');

	$respuesta = array();
	$respuesta["msj"] = 1;
	$respuesta["trabajador"] = "";

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


	foreach($personas -> datos as $fila)
	{
        // Busca el trabajador
		$sql = "SELECT trabCedula FROM trabajador WHERE trabCedula = '".$fila->cedula."'";
		$res = mysql_query($sql,$con);
		if(!$res) {
			die(mysql_error($con));
		}
        // Si no lo consigue, envia un mensaje 
        // al navegador para decir que lo cree
        // y cancela el proceso
		if(mysql_num_rows($res)===0){
			$respuesta["msj"] = 2;
			$respuesta["trabajador"] = $fila->cedula;
			header('Content-type: application/json; charset=utf-8');
			die(json_encode($respuesta));
		}

		$prestamo->consultar($fila->cedula,"AND prestamoTipoprestId = ".$_POST["tipoPrestamo"]);
		$res = $prestamo->resultado;
		if (mysql_num_rows($res) > 0) {
			$fechaHasta = new DateTime(convertirFecha_SpanishToEnglish($_POST["hasta"]));
			while ( $dato = mysql_fetch_assoc($res) ) {
				$estado = $dato["prestamoEstatus"];
				$fechaPrestamo = $dato["prestamoFecha"];
				$fechaPrestamo = new DateTime($fechaPrestamo);
				if ($fechaPrestamo > $fechaHasta) {
					$respuesta["trabajador"] = $fila->cedula;
					$respuesta["msj"] = 8;

					$detalle_prestamo->eliminarLote(convertirFecha_SpanishToEnglish($_POST["hasta"]));

					header('Content-type: application/json; charset=utf-8');
					die(json_encode($respuesta));
				}
				if ($estado === 'Pendiente') {
					$saldo = $prestamo->consultarSaldo($dato["prestamoId"]);
					$detalle_prestamo->agregar($dato["prestamoId"],convertirFecha_SpanishToEnglish($_POST["hasta"]),$fila->pago,$saldo,$usuario);
					$prestamo->actualizarPrestamos($dato['prestamoId']);
					break;
				}
			}
		} else {
			$respuesta["trabajador"] = $fila->cedula;
			$respuesta["msj"] = 0;
			$detalle_prestamo->eliminarLote(convertirFecha_SpanishToEnglish($_POST["hasta"]));
			header('Content-type: application/json; charset=utf-8');
			die(json_encode($respuesta));
		}
 
        // Inserta el abono del trabajador
		//	$sql = "INSERT INTO detalle_liquidacion VALUES(detliqTrabCedula,detliqPrestamoId,detliqTipoprestId,detliqFecha,detliqMonto)
     	//          VALUES ('".$fila->cedula."',".."";
		//if (!(mysql_query($sql, $con)))
		//{
		//	die("No pudo insertar los datos en la base de datos: " . $sql);
		//}
	}
	mysql_close($con);
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($respuesta);

?>