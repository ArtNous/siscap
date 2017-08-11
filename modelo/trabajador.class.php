<?php
include_once("../include/funciones.class.php");

class trabajador extends funciones{
	
	//	Agregar trabajador ****
	//	Autor(es): DanielaRomero
	  
		function agregar($cedula,$nombre,$apellido,$sexo,$edocivil,$fechanac,$nivel,$profesion,$direccion,$correo,$telefono,$codigo,$fechaingreso,$orgaid,$depaid,$cargo,$sueldo,$fecha){
			
			if ($this->con->conectar() == true) {
				
				if($fechanac==""){
				
					$Sql = "INSERT INTO trabajador 
								(trabCedula,trabNombre,trabApellido,trabSexo,trabEdocivil,trabNivel,trabProfesion,trabDireccion,trabCorreo,trabTelefono,
								 trabCodigo,trabFechaingreso,trabOrganismoId,trabDepartmentoId,trabCargo,trabSueldo,trabEstatus,trabFecharegistro) 
											  
								VALUES ('".$cedula."','".$nombre."','".$apellido."','".$sexo."','".$edocivil."','".$nivel."','".$profesion."','".$direccion."','".$correo."','".$telefono."',
										'".$codigo."','".$fechaingreso."','".$orgaid."','".$depaid."','".$cargo."','".$sueldo."','Activo','".$fecha."')";
				}else{
					$Sql = "INSERT INTO trabajador 
								(trabCedula,trabNombre,trabApellido,trabSexo,trabEdocivil,trabFechanac,trabNivel,trabProfesion,trabDireccion,trabCorreo,trabTelefono,
								 trabCodigo,trabFechaingreso,trabOrganismoId,trabDepartmentoId,trabCargo,trabSueldo,trabEstatus,trabFecharegistro) 
											  
								VALUES ('".$cedula."','".$nombre."','".$apellido."','".$sexo."','".$edocivil."','".$fechanac."','".$nivel."','".$profesion."','".$direccion."','".$correo."','".$telefono."',
										'".$codigo."','".$fechaingreso."','".$orgaid."','".$depaid."','".$cargo."','".$sueldo."','Activo','".$fecha."')";
				}
				return mysql_query($Sql)  or die (mysql_error());	
					
			}
		}
	//****** Fin Agregar trabajador
	
	
	//	Editar trabajador****
	//	Autor(es): DanielaRomero	
		function editar($id,$cedula,$nombre,$apellido,$sexo,$edocivil,$fechanac,$nivel,$profesion,$direccion,$correo,$telefono,$codigo,$fechaingreso,$orgaid,$depaid,$cargo,$sueldo) {
			
			if ($this->con->conectar() == true) {
				
				if($fechanac==""){
					$Sql = "UPDATE trabajador 
					SET 
						trabCedula 			= '".$cedula."',
						trabNombre 			= '".$nombre."',
						trabApellido   		= '".$apellido."',
						trabSexo   			= '".$sexo."',
						trabEdocivil    	= '".$edocivil."',
						trabFechanac  		= null,
						trabNivel  			= '".$nivel."',
						trabProfesion  		= '".$profesion."',
						trabDireccion   	= '".$direccion."',
						trabCorreo			= '".$correo."',
						trabTelefono		= '".$telefono."',
						trabCodigo    		= '".$codigo."',
						trabFechaingreso   	= '".$fechaingreso."',
						trabOrganismoId    	= '".$orgaid."',
						trabDepartmentoId 	= '".$depaid."',
						trabCargo    		= '".$cargo."',
						trabSueldo    		= '".$sueldo."'
					WHERE trabCedula = '".$id."'";
				}else{
					$Sql = "UPDATE trabajador 
					SET 
						trabCedula 			= '".$cedula."',
						trabNombre 			= '".$nombre."',
						trabApellido   		= '".$apellido."',
						trabSexo   			= '".$sexo."',
						trabEdocivil    	= '".$edocivil."',
						trabFechanac  		= '".$fechanac."',
						trabNivel  			= '".$nivel."',
						trabProfesion  		= '".$profesion."',
						trabDireccion   	= '".$direccion."',
						trabCorreo			= '".$correo."',
						trabTelefono		= '".$telefono."',
						trabCodigo    		= '".$codigo."',
						trabFechaingreso   	= '".$fechaingreso."',
						trabOrganismoId    	= '".$orgaid."',
						trabDepartmentoId 	= '".$depaid."',
						trabCargo    		= '".$cargo."',
						trabSueldo    		= '".$sueldo."'
					WHERE trabCedula = '".$id."'";
				}
				
				return mysql_query($Sql)  or die (mysql_error());
				
			}
		}
	//****** Fin Total Pagina
	
	//	Eliminar trabajador****
	//	Autor(es): DanielaRomero
		function eliminar($cedula) {
			if ($this->con->conectar() == true) {
				if(file_exists("../files/".$cedula.".jpg")) {
					unlink("../files/".$cedula.".jpg"); 
				}
				$sql = "DELETE FROM trabajador WHERE trabCedula = '".$cedula."'";
				return mysql_query($sql) or die (mysql_error());
			}
		}
	//****** Fin Eliminar trabajador
	
	//	Actualizar Clave del trabajador****
	//	Autor(es): DanielaRomero	
		function actualizarClave($cedula,$clave) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE trabajador SET trabClave = '$clave' WHERE trabCedula = '".$cedula."' ";
				return mysql_query($Sql)  or die (mysql_error());
				
			}
		}
	//****** Fin Actualizar Clave
	
	//	Gestionar Egreso del trabajador****
	//	Autor(es): DanielaRomero	
		function gestionar_egreso($cedula,$codigo,$fechaegreso,$observacion,$estatus) {
			
			if ($this->con->conectar() == true) {
				
				if($fechaegreso==""){
						$Sql = "UPDATE trabajador 
							SET 
							trabCodigo 		= '".substr($codigo,1,strlen($codigo))."',
							trabEstatus 		= '".$estatus."',
							trabFechaegreso    	= null,
							trabObservacion    	= '".$observacion."'
							WHERE trabCedula = '".$cedula."'";

				}else{
					$Sql = "UPDATE trabajador 
							SET 
							trabCodigo 		= '".$codigo."',
							trabEstatus 		= '".$estatus."',
							trabFechaegreso    	= '".$fechaegreso."',
							trabObservacion    	= '".$observacion."'
							WHERE trabCedula = '".$cedula."'";
				}
				
				return mysql_query($Sql)  or die (mysql_error());
				
			}
		}
	//****** Fin 
	
	
	//  Consultar Registro: *****Consulta el registro a imprimir en el listado****
	//  Autor(es): DanielaRomero
    function consultar($where='',$ord='') {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT
						trabajador.trabCedula,
						trabajador.trabCodigo,
						CONCAT(trabajador.trabNombre,' ',trabajador.trabApellido) AS trabNombres,
						trabajador.trabNombre, 
						trabajador.trabApellido,
						trabajador.trabFechaingreso,
						trabajador.trabOrganismoId,
						organismo.organismoDescripcion,
						trabajador.trabDepartmentoId,
						departamento.departamentoDescripcion,
						trabajador.trabCargo,
						trabajador.trabSueldo,
						trabajador.trabEstatus,
						trabajador.trabFechaegreso
					FROM
						trabajador
						LEFT JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
						LEFT JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId
					$where   $ord ";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
	
	//  Consultar Registro: *****Consulta el registro a imprimir en el listado****
	//  Autor(es): DanielaRomero
    function consultarAhorros($cedula,$desde,$hasta) {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT
						detalle_ahorro.detahorroTrabCedula,
						sum(detalle_ahorro.detahorroMonto) as monto
						FROM detalle_ahorro
						WHERE detalle_ahorro.detahorroTrabCedula = '".$cedula."' AND detahorroFecha BETWEEN '".$desde."' AND '".$hasta."'
						GROUP BY detalle_ahorro.detahorroTrabCedula";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
	
}

?>
