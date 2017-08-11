<?php
include_once("../include/funciones.class.php");

class profesion extends funciones {

   //Constructor Base Datos
		var $con;
		function profesion() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar Usuario: *****
	//	Autor(es): DanielaRomero
		function agregar($descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO profesion (profesionDescripcion) VALUES ('$descrip')";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar Usuario
	
	//	Editar Usuario: *****
	//	Autor(es): DanielaRomero
		function editar($id,$descrip) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE profesion SET profesionDescripcion='".$descrip."' 
						WHERE profesionDescripcion = '".$id."'";
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Total Pagina
	
	//	Eliminar Usuario: *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM profesion WHERE profesionDescripcion = '".$id."'");
			}
		}
	//****** Fin Eliminar Usuario
              
                
}

?>