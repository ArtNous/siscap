<?php
include_once("../include/funciones.class.php");

class departamento extends funciones{

	//	Agregar Departamento: *********
	//	Autor(es): DanielaRomero
		function agregar($descrip,$organismo) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO departamento (departamentoDescripcion,departamentoOrganismoId) 
						VALUES ('$descrip','$organismo')";
				return mysql_query($Sql) or die(mysql_error());
			
			}
		}
	//****** Fin Agregar Departamento
	
	//	Editar Departamento: *********	
	//	Autor(es): DanielaRomero	
		function editar($id,$descrip, $organismo) {
			if ($this->con->conectar() == true) {
			
				$Sql = "UPDATE departamento 
						SET 
							departamentoDescripcion = '".$descrip."', 
							departamentoOrganismoId = '".$organismo."' 
						WHERE departamentoId = '".$id."'";
				return mysql_query($Sql) or die(mysql_error());
			                           
			}
		}
	//****** Fin Editar Departamento
	
	//	Eliminar Departamento: *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM departamento WHERE departamentoId = '".$id."'") or die(mysql_error());
			}
		}
	//****** Fin Eliminar Departamento
              
                
}

?>