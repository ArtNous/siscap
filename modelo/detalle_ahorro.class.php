<?php
include_once("../include/funciones.class.php");

class detalle_ahorro extends funciones{
	
	//Agregar registro
	function agregar($codigo,$cedula,$sueldo,$monto) {

		if ($this->con->conectar() == true) {
			
			//Buscar ID Origen del ultimo movimiento de la Ficha de Ingreso
            $Sql1 = "SELECT cajahorroHasta FROM caja_ahorro WHERE cajahorroId = '" . $codigo . "' LIMIT 0,1";
            $result = mysql_query($Sql1) or die(mysql_error());
            $row = mysql_fetch_array($result);
			
			$fecha = $row['cajahorroHasta'];
			
			
			$sql = "INSERT INTO detalle_ahorro (detahorroCajahorroId,detahorroFecha,detahorroTrabCedula,detahorroSueldo,detahorroMonto) 
							VALUES ('$codigo','$fecha','$cedula','$sueldo','$monto')";
							
			return mysql_query($sql) or die (mysql_error());
		}
	}//Fin agregar registro
	
	//Editar registro
	function editar($codigo,$cedula,$monto) {

		if ($this->con->conectar() == true) {
			$sql = "UPDATE detalle_ahorro SET detahorroMonto = '".$monto."'
					WHERE detahorroCajahorroId = '".$codigo."' AND detahorroTrabCedula = '".$cedula."'
					";
			return mysql_query($sql) or die (mysql_error());
		}
	}//Fin editar registro
	
	//	Eliminar registro: *********
	//	Autor(es): DanielaRomero	
		function eliminar($codigo,$cedula) {
			if ($this->con->conectar() == true) {
				$sql = "DELETE FROM detalle_ahorro 
						WHERE detahorroCajahorroId = '".$codigo."' AND detahorroTrabCedula = '".$cedula."' ";
						
				return mysql_query($sql) or die (mysql_error());
			}
		}
	//****** Fin Eliminar registro
	
	//******Consulta para Reportes
	function consultar($codigo) {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT * FROM detalle_ahorro 
						INNER JOIN trabajador ON (detalle_ahorro.detahorroTrabCedula = trabajador.trabCedula) 
						INNER JOIN organismo ON (trabajador.trabOrganismoId = organismo.organismoId)
						INNER JOIN departamento ON (trabajador.trabDepartmentoId = departamento.departamentoId)
					WHERE detahorroCajahorroId = '".$codigo."' 
					ORDER BY organismoDescripcion, trabCedula ASC
					";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
}

?>
