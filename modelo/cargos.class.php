<?php
include_once("../include/funciones.class.php");

class cargos extends funciones {

   //Constructor Base Datos
		var $con;
		function cargos() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar Usuario: *****
	//	Autor(es): DanielaRomero
		function agregar($descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO cargos (cargoDescripcion) VALUES ('$descrip')";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar Usuario
	
	//	Editar *****
	//	Autor(es): DanielaRomero
		function editar($id,$descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE cargos SET cargoDescripcion='".$descrip."' 
						WHERE cargoDescripcion = '".$id."'";
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Editar
	
	//	Eliminar Usuario: *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM cargos WHERE cargoDescripcion = '".$id."'");
			}
		}
	//****** Fin Eliminar Usuario
              
                
}

?>