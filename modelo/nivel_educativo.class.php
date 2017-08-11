<?php
include_once("../include/funciones.class.php");

class nivel_educativo extends funciones {

   //Constructor Base Datos
		var $con;
		function nivel_educativo() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar Usuario: *****
	//	Autor(es): DanielaRomero
		function agregar($descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO nivel_educativo (nivelDescripcion) VALUES ('$descrip')";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar Usuario
	
	//	Editar Usuario: *****
	//	Autor(es): DanielaRomero
		function editar($id,$descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE nivel_educativo SET nivelDescripcion='".$descrip."' 
						WHERE nivelDescripcion = '".$id."'";
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Total Pagina
	
	//	Eliminar Usuario: *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM nivel_educativo WHERE nivelDescripcion = '".$id."'");
			}
		}
	//****** Fin Eliminar Usuario
              
                
}

?>