<?php
include_once("../include/funciones.class.php");

class organismo extends funciones {

   //Constructor Base Datos
		var $con;
		function organismo() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar Usuario: *****
	//	Autor(es): DanielaRomero
		function agregar($descrip, $direccion, $telefono) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO organismo (organismoDescripcion,organismoDireccion,organismoTelefono) 
						VALUES ('$descrip','$direccion','$telefono')";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar Usuario

	//	Editar Usuario: *****
	//	Autor(es): DanielaRomero
		function editar($id, $descrip, $direccion, $telefono) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE organismo SET organismoDescripcion='".$descrip."',organismoDireccion='".$direccion."', organismoTelefono = '".$telefono."' WHERE organismoId = '".$id."'";
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Total Pagina
	
	//	Eliminar Usuario: *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM organismo WHERE organismoId = '".$id."'");
			}
		}
	//****** Fin Eliminar Usuario
              
                
}

?>