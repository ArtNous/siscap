<?php
	$jsonString = str_replace("\\", "", $_POST["dato"]);
	$personas = json_decode($jsonString, false);
	// $con = mysql_connect('localhost', 'root', 'programacion');
    // mysql_select_db('siscap');
    include('../modelo/trabajador.class.php');
    include('../modelo/organismo.class.php');
    $objeto = new trabajador();
    $objOrganismo = new organismo();
    date_default_timezone_set('America/Caracas');

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
        // Validacion del organismo
        if ( ($objeto->cantidadReg('trabajador',' WHERE trabCedula = ' . $fila->cedula)) != 0 ){
            continue;
        }
        if ( ($objOrganismo->cantidadReg('organismo','WHERE organismoId = ' . $fila->organismo)) == 0 ){
            header('Content-type: text/plain; charset=utf-8');
            die("El código de organismo que colocó al trabajador(a) " . $fila->cedula ." no existe en el sistema, por favor verifique la información");
        }
        // ------------------------
        $fecha = convertirFecha_SpanishToEnglish($fila->fechaI);
        $oper = $objeto->agregar($fila->cedula,
                            strtoupper($fila->nombre),
                            strtoupper($fila->apellido),
                            strtoupper($fila->sexo),
                            strtoupper($fila->civil),'','','','','','',
                            $fila->codigo,
                            $fecha,
                            $fila->organismo,
                            $fila->dpto,
                            strtoupper($fila->cargo),
                            $fila->sueldo,
                            date('Y-m-d'));
        if($oper === FALSE) {
            die("Error agregando una fila");
        }
	}
	header('Content-type: text/plain; charset=utf-8');
	echo 1;
?>