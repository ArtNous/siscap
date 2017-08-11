<?php
include_once("../include/funciones.class.php");

class tipo_descuento_asociado extends funciones {

   //Constructor Base Datos
		var $con;
		function tipo_descuento_asociado() {
			$this->con = new DBManager;
		}
	
	//	Agregar 
	//	Autor(es): DanielaRomero
		function agregar($codigo, $cedula,$monto='') {
			if ($this->con->conectar() == true) {
				
				if($codigo<>'' && $cedula <>''){
					$this->eliminar($codigo, $cedula);

					$Sql = "INSERT INTO tipo_descuento_asociado (tipodescId,trabCedula,monto) VALUES ('$codigo','$cedula','$monto')";
					$result= mysql_query($Sql) or die(mysql_error());
				}else{
					$result='Campo vacio, por favor comuniquese con el administrador.';
				}
				return $result;
			}
		}
	//****** Fin Agregar

	//	Agregar a todos
	//	Autor(es): DanielaRomero
		function agregar_todo($codigo) {
			if ($this->con->conectar() == true) {
				
				//$this->eliminar_todo($codigo);
				$Sql = "INSERT IGNORE INTO tipo_descuento_asociado SELECT '".$codigo."', trabajador.trabCedula, '' as monto FROM trabajador WHERE trabajador.trabEstatus <> 'inactivo' ";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar todos
	
	//	Eliminar 
	//	Autor(es): DanielaRomero
		function eliminar($codigo, $cedula) {
			if ($this->con->conectar() == true) {
				$Sql = "DELETE FROM tipo_descuento_asociado WHERE tipodescId = '".$codigo."' AND trabCedula = '".$cedula."' ";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Eliminar

	//	Eliminar todos
	//	Autor(es): DanielaRomero
		function eliminar_todo($codigo) {
			if ($this->con->conectar() == true) {
				$Sql = "DELETE FROM tipo_descuento_asociado WHERE tipodescId = '".$codigo."'";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Eliminar todos

}

?>