<?php
include_once("../include/funciones.class.php");

class configuracion extends funciones {

	//	Agregar registro: *****
	//	Autor(es): DanielaRomero
		function actualizar($ahorro,$aporte,$meses,$porcentaje,$liqprestipo) {
			if ($this->con->conectar() == true) {
				
				$Sql = "DELETE FROM configuracion ";
				$result= mysql_query($Sql) or die(mysql_error());
				
				$Sql = "INSERT INTO configuracion VALUES ('$ahorro','$aporte','$meses','$porcentaje','$liqprestipo')";
				$result= mysql_query($Sql) or die(mysql_error());
				
				return $result;
			}
		}
	//****** Fin Agregar registro
	            
}

?>