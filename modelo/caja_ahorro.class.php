<?php
include_once("../include/funciones.class.php");

class caja_ahorro extends funciones{
	
	function agregar($codigo,$quincena,$desde,$hasta,$porcentaje,$estatus,$fecha) {

		if ($this->con->conectar() == true) {
		
			$sql = " SELECT * FROM trabajador 
						WHERE (trabEstatus  = 'Activo' AND trabFechaingreso <= '$hasta') OR  
							  (trabEstatus  = 'Inactivo' AND trabFechaingreso <= '$hasta' AND trabFechaegreso >= '$desde')
					";
			$trabajador = mysql_query($sql) or die (mysql_error());
		    $cant=mysql_num_rows($trabajador);
			
			$total = 0;
			
			if($cant>0){
			
				$borrar_detalle = mysql_query("DELETE FROM detalle_ahorro WHERE detahorroCajahorroId = '".$codigo."'");

				$sql2 = "INSERT INTO caja_ahorro(cajahorroId,cajahorroDesde,cajahorroHasta,cajahorroPorcentaje,cajahorroCantidad,cajahorroTotal,cajahorroEstatus,cajahorroFechaestatus) 
						VALUES ('$codigo','$desde','$hasta','$porcentaje','$cant','$total','$estatus','$fecha')";
				$result= mysql_query($sql2) or die (mysql_error());
				
				while($row = mysql_fetch_array($trabajador)) {

					$cedula = $row['trabCedula'];
					$sueldo = $row['trabSueldo'];
					$monto = number_format(($sueldo*$porcentaje)/100, 2, '.', '');
					
					//Si es Quincenal
					if($quincena!=""){
						$monto=$monto/2;
					}
					
					$total += $monto;
					
					$sql1 = "INSERT INTO detalle_ahorro (detahorroCajahorroId,detahorroFecha,detahorroTrabCedula,detahorroSueldo,detahorroMonto) 
							VALUES ('$codigo','$hasta','$cedula','$sueldo','$monto')";
					$result= mysql_query($sql1) or die (mysql_error());
								
				}

				$this->actualizar_cajahorro($codigo);
				
				
				return $result;
				
			}else{
				return " No Existen trabajadores Activos para procesar la Caja de Ahorro";
			}
				
		}
	}
	//Fin agregar regsitro
	
	//	Eliminar caja_ahorro: *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				$result = mysql_query("DELETE FROM detalle_ahorro WHERE detahorroCajahorroId = '".$id."'");
				$result = mysql_query("DELETE FROM caja_ahorro WHERE cajahorroId = '".$id."'");
				
				return $result;
			}
		}
	//****** Fin Eliminar caja_ahorro
	
	function actualizar_cajahorro($codigo) {

		if ($this->con->conectar() == true) {
		
			$sql = " SELECT * FROM detalle_ahorro WHERE detahorroCajahorroId = '".$codigo."' ";
			$detalle = mysql_query($sql) or die (mysql_error());
			$cant=mysql_num_rows($detalle);
			
		    $total=0;
			while($row = mysql_fetch_array($detalle)) {					
				$total = $total+$row['detahorroMonto'];					
			}
			
			$sql= "UPDATE caja_ahorro SET 
						cajahorroCantidad = '".$cant."',
						cajahorroTotal = '".$total."'
					WHERE cajahorroId = '".$codigo."' ";
			
			return mysql_query($sql) or die (mysql_error());
				
		}
	}
	//Fin agregar regsitro
	
	
	//	Gestionar cierre caja_ahorro: *********
	//	Autor(es): DanielaRomero	
		function procesar_cierre($id,$fecha) {
			if ($this->con->conectar() == true) {
				return mysql_query("UPDATE caja_ahorro 								
										SET 
											cajahorroEstatus = 'Procesado',
											cajahorroFechaestatus = '".$fecha."'
										WHERE cajahorroId = '".$id."'
									");
			}
		}
	//****** Fin Eliminar caja_ahorro
	
	
	//******Consulta por codigo de caja de ahorro para Reportes
	function consultar($codigo) {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT * FROM caja_ahorro WHERE cajahorroId = '".$codigo."' ";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
	//******Consulta General con parametros para Reportes:  Resumen General o por organismo
	function detalleResumen($where='',$ord='') {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT 
						caja_ahorro.cajahorroId,
						caja_ahorro.cajahorroDesde,
						caja_ahorro.cajahorroHasta,
						caja_ahorro.cajahorroFechaestatus,
						caja_ahorro.cajahorroEstatus,
						caja_ahorro.cajahorroPorcentaje,
						caja_ahorro.cajahorroCantidad,
						SUM(detalle_ahorro.detahorroMonto) AS cajahorroMonto,
						organismo.organismoId,
						organismo.organismoDescripcion
					FROM caja_ahorro 
						INNER JOIN detalle_ahorro ON caja_ahorro.cajahorroId = detalle_ahorro.detahorroCajahorroId
						INNER JOIN trabajador ON detalle_ahorro.detahorroTrabCedula = trabajador.trabCedula
						INNER JOIN organismo ON organismo.organismoId = trabajador.trabOrganismoId
					$where 
					GROUP BY cajahorroId
					$ord ";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
	function consultarResumen($desde='',$hasta='',$where='',$ord='') {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT 
						(SELECT	SUM(cajahorroTotal) FROM caja_ahorro WHERE cajahorroDesde<'".$desde."') AS saldoA,
						(SELECT	SUM(cajahorroTotal) FROM caja_ahorro WHERE cajahorroHasta>'".$hasta."') AS saldoD,
						caja_ahorro.cajahorroId,
						caja_ahorro.cajahorroDesde,
						caja_ahorro.cajahorroHasta,
						caja_ahorro.cajahorroFechaestatus,
						caja_ahorro.cajahorroEstatus,
						caja_ahorro.cajahorroPorcentaje,
						caja_ahorro.cajahorroCantidad,
						SUM(detalle_ahorro.detahorroMonto) AS cajahorroMonto,
						organismo.organismoId,
						organismo.organismoDescripcion
					FROM caja_ahorro 
						LEFT JOIN detalle_ahorro ON caja_ahorro.cajahorroId = detalle_ahorro.detahorroCajahorroId
						LEFT JOIN trabajador ON detalle_ahorro.detahorroTrabCedula = trabajador.trabCedula
						LEFT JOIN organismo ON organismo.organismoId = trabajador.trabOrganismoId
					WHERE cajahorrohasta BETWEEN '".$desde."' and '".$hasta."' $where
					GROUP BY cajahorroId 
					$ord
					";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}

}

?>
