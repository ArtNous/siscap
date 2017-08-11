<?php

include_once("conexion.class.php"); 

class funciones {
		
	//Constructor Base Datos
		var $con;
		var $resultado;
		function funciones() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	
	//  Total Pagina: ****deacuerdo al limite de registros por pagina****
	//	Parametros: cantidad, limite.
	//	Sistemas: todos los sistemas
	//	Autor(es): DanielaRomero.
		function totalPagina($cant,$limite) {
			if ($cant > 0) {
				if ($limite == 0) {
					$total_pag = $cant;
				} else {
					$total_pag = ceil($cant / $limite);
				}
			} else {
				$total_pag = 0;
			}
			return $total_pag;
		}
	//****** Fin Total Pagina
	
	
	//  Inicio Pagina: *****Inicio de pagina por limite****
	//	Parametros: pagina, total_pag, limite
	//	Sistemas: todos los sistemas
	//	Autor(es): DanielaRomero	
		function inicioPagina($pag,$total_pag,$limite) {
		
			if ($pag > $total_pag)
				$pag = $total_pag;
			if (0 ==$total_pag)
			  $inicio = 1;
			else
			 $inicio =$limite * $pag - $limite; 
			return $inicio;
		}
	//****** Fin Total Pagina
	
		
	//	Cantidad Registro: *****Breve Descripcion****
	//	Parametros: 
	//	Sistemas: todos los sistemas
	//	Autor(es): DanielaRomero	
			function cantidadReg($entidad,$where='') {
				if ($this->con->conectar() == true) {
					$sql = "SELECT COUNT(*) as cant FROM $entidad $where";
				  $resultado=mysql_query($sql) or die ("contando " . mysql_error() . " " .$sql);
				  $cant=mysql_fetch_array($resultado);
				 // $this->con->desconectar();
				  return $cant['cant'];
				} 
			}
	//****** Fin Total Pagina
		
		
	//	Consulta General: *****Consulta general por parametros****
	//	Parametros: todos los campos dependiente de un where, orden, inicio, limite
	//	Sistemas: todos los sistemas
	//	Autor(es): DanielaRomero	
		function consultaGeneral($entidad,$where,$ord,$dir,$inicio='',$limite='',$campos=' * ',$grupo='') {
			if ($this->con->conectar() == true) {
				  if ($limite=='' and $inicio =='')
					$limiteX = " ";
				  else
				   	$limiteX = " LIMIT $inicio,$limite";
				$this->resultado = mysql_query("SELECT $campos FROM $entidad $where $grupo ORDER BY $ord $dir $limiteX" ) or die (mysql_error());
			 // $this->con->desconectar();
			  return true;
			}
		}
	//****** Fin Total Pagina




     //	Consulta General: *****Breve Descripcion****
	//	Parametros: 
	//	Sistemas: 
	//	Autor(es): DanielaRomero	
		function ejecutarcomando($csql) {
			if ($this->con->conectar() == true) {
				
				$this->resultado = mysql_query($csql) or die (mysql_error());
			  return true;
			}
		}
	//****** Fin Total Pagina
	
	
	//	Obtener Ip: *********
	//	Sistemas: todos los sistemas
	//	Autor(es): Google-Ismar
	function ObtenerIP(){
		   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
				   $ip = getenv("HTTP_CLIENT_IP");
		   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
				   $ip = getenv("HTTP_X_FORWARDED_FOR");
		   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
				   $ip = getenv("REMOTE_ADDR");
		   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
				   $ip = $_SERVER['REMOTE_ADDR'];
		   else
				   $ip = "IP desconocida";
		   return($ip);
	}
	//****** Fin Obtener Ip
		
	function enviar($destinatario,$remitente,$asunto,$mensaje) {
		    $headers = "From: ".$remitente."\r\nReply-To: ". $remitente;
		    ob_start(); 
		    $message = ob_get_clean();
		    $mail_sent = @mail( $destinatario, $asunto, $mensaje, $headers );
		    return $mail_sent ? "Correo Enviado" : "No se ha podido enviar el correo";
	}
	
	//*****Obtener Fecha y Hora actual
	function ObtenerFecha(){
		
		$timezone = -4.5;
		// $fecha   = gmdate("Y-m-d", time() + 3600*($timezone+date("I")));
		//$fecha   = gmdate("Y-m-d");
		// return $fecha;
	}
	
	function ObtenerHora(){
		$timezone = -4.5;
		// $hora    = gmdate("H:i:s", time() + 3600*($timezone+date("I")));
		// return $hora;
	}
	
	function convertirFecha($fecha) {
		if($fecha=='00-00-0000' || $fecha=='0000-00-00' || $fecha==''){
			$fecha='';
		}else{
			$data = explode("-",substr($fecha,0,10));
			$hora=(mb_strlen($fecha)>10)?substr($fecha,10,mb_strlen($fecha)):'';
			$fecha = $data[2].'-'.$data[1].'-'.$data[0].''.$hora;
		}
		return $fecha;
	}
	
		
}
?>
