<?php
include_once("../include/funciones.class.php");

class aporte_patronal extends funciones{

	
	function agregar($codigo,$ano,$mesdesde,$meshasta,$concepto,$fecha) {

		if ($this->con->conectar() == true) {
			
			$this->eliminar($codigo);

			$sql = "INSERT INTO aporte_patronal VALUES('$codigo','$concepto','Pendiente','$fecha')";
			$result= mysql_query($sql) or die (mysql_error());

			$sql2 = "INSERT INTO aporte_patronal_temp 
							(SELECT
								'$codigo' as codigo,
								trabajador.trabCedula AS cedula,
								if(detalle_ahorro.detahorroMonto is null,0,ROUND(Sum(detalle_ahorro.detahorroMonto) * configuracion.configAportepatronal/100,2)) AS monto
							FROM
								configuracion, trabajador
								LEFT JOIN detalle_ahorro ON trabajador.trabCedula = detalle_ahorro.detahorroTrabCedula AND 
								year(detahorroFecha) ='$ano' AND (MONTH(detahorroFecha) BETWEEN '$mesdesde' AND '$meshasta')
							GROUP BY trabajador.trabCedula
							ORDER BY CAST(trabCedula AS DECIMAL)
							) ";
			$result .= mysql_query($sql2) or die (mysql_error());
								
			return $result;
	
		}
	}
	//Fin agregar

	//	Editar *****
	//	Autor(es): DanielaRomero
		function editar($id,$concepto,$fecha) {
			if ($this->con->conectar() == true) {
				
				$Sql = "UPDATE aporte_patronal SET aporteConcepto='$concepto', aporteFecha = '$fecha'
						WHERE aporteCodigo = '$id' ";
				$result = mysql_query($Sql) or die(mysql_error());

				//Editar Concepto y Fecha en la tabla descuento_ahorro
				$Sql2 = "UPDATE descuento_ahorro SET descahorroConcepto='$concepto', descahorroFecha = '$fecha'
						WHERE descahorroCodigo = '$id' ";

				$result = mysql_query($Sql2) or die(mysql_error());

				return $result;
			}
		}
	//****** Fin Editar


	//	Editar Temp *****
	//	Autor(es): DanielaRomero
		function editar_detalle($tabla,$codigo,$cedula,$monto) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE $tabla SET descahorroMonto='$monto'
						WHERE descahorroCodigo = '$codigo' AND descahorroTrabCedula = '$cedula' ";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Editar Temp

	
	//	Eliminar registros *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				$result = mysql_query("DELETE FROM descuento_ahorro  WHERE descahorroCodigo = '$id' ");
				$result = mysql_query("DELETE FROM aporte_patronal_temp  WHERE descahorroCodigo = '$id' ");
				$result = mysql_query("DELETE FROM aporte_patronal  WHERE aporteCodigo = '$id' ");
				return $result;
			}
		}
	//****** Fin Eliminar

	//	Eliminar registro de la tabla descuento_ahorro *********
	//	Autor(es): DanielaRomero	
		function eliminar_detalle($tabla,$codigo,$cedula) {
			if ($this->con->conectar() == true) {
				$result = mysql_query("DELETE FROM $tabla  WHERE descahorroCodigo = '$codigo' AND descahorroTrabCedula = '$cedula' ");
				return $result;
			}
		}
	//****** Fin Eliminar  
		
	//	Procesar *********
	//	Autor(es): DanielaRomero	
		function procesar($codigo,$usuario) {
			if ($this->con->conectar() == true) {
				
				$eliminareg = mysql_query("DELETE FROM descuento_ahorro  WHERE descahorroCodigo = '$codigo' ");

				$sql = "INSERT INTO descuento_ahorro 
							(SELECT
								'' AS id,
								aporte_patronal_temp.descahorroTrabCedula,
								'Abono'  AS tipo,
								aporte_patronal.aporteConcepto,
								aporte_patronal.aporteFecha,
								aporte_patronal_temp.descahorroMonto,
								'Procesado' as estatus,
								null as fechaestatus,
								'$usuario' as usuario,
								'$codigo' as aporteCodigo
							FROM
								aporte_patronal_temp
								LEFT JOIN aporte_patronal ON aporte_patronal_temp.descahorroCodigo = aporte_patronal.aporteCodigo 
							WHERE aporte_patronal_temp.descahorroMonto > 0 AND aporte_patronal.aporteCodigo = '$codigo'
							GROUP BY descahorroTrabCedula
							ORDER BY CAST(descahorroTrabCedula AS DECIMAL)
							) ";
				$result = mysql_query($sql) or die (mysql_error());				
			
				if($result==1){
					$eliminartemp = mysql_query("DELETE FROM aporte_patronal_temp  WHERE descahorroCodigo = '$codigo' ");
					
					$actualizar = mysql_query("UPDATE aporte_patronal SET aporteEstatus = 'Procesado' WHERE aporteCodigo = '$codigo' ");
				}

				return $result;
			}
		}
	//****** Fin Procesar

}

?>
