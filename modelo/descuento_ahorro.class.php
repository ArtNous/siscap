<?php
include_once("../include/funciones.class.php");

class descuento_ahorro extends funciones {

	//	Agregar: *****
	//	Autor(es): DanielaRomero
		function agregar($cedula, $tipo, $concepto,$fechadesc, $monto,$usuario) {
			if ($this->con->conectar() == true) {
				$Sql = "INSERT INTO descuento_ahorro (descahorroTrabCedula, descahorroTipo, descahorroConcepto, descahorroFecha, descahorroMonto, descahorroEstatus, descahorroUsuCedula)
						VALUES ('$cedula','$tipo','$concepto','$fechadesc','$monto','Procesado','$usuario')";
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar Usuario
	
	//	Editar Usuario: *****
	//	Autor(es): DanielaRomero
		function editar($id,$cedula, $tipo, $concepto,$fechadesc, $monto, $estatus, $fechaestatus,$usuario) {
			if ($this->con->conectar() == true) {
				
				$fechaE = ($fechaestatus=="")?"descahorroFechaestatus = null":"descahorroFechaestatus = '".$fechaestatus."' ";
				
				$Sql = "UPDATE descuento_ahorro SET 
							descahorroTipo='".$tipo."',
							descahorroConcepto='".$concepto."',
							descahorroFecha='".$fechadesc."',
							descahorroMonto='".$monto."',
							descahorroEstatus = '".$estatus."', 
							".$fechaE.",
							descahorroUsuCedula = '".$usuario."'
						WHERE descahorroId = '".$id."'";
						
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Total Pagina
	
	//	Eliminar Usuario: *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				return mysql_query("DELETE FROM descuento_ahorro WHERE descahorroId = '".$id."'");
			}
		}
	//****** Fin Eliminar Usuario
        
    
	
	//  Autor(es): DanielaRomero
    function consultarSaldoAhorro($cedula) {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT  
						cedula,
						SUM(total_ahorro) AS ahorro,
						SUM(total_descuento) as descuento,
						SUM(total_ahorro)-SUM(total_descuento)  AS saldo
						FROM (
								SELECT
									detalle_ahorro.detahorroTrabCedula  as cedula,
									SUM(detalle_ahorro.detahorroMonto) AS total_ahorro,
									'' as total_descuento
								FROM
									detalle_ahorro
									INNER JOIN caja_ahorro ON detalle_ahorro.detahorroCajahorroId = caja_ahorro.cajahorroId
								WHERE caja_ahorro.cajahorroEstatus = 'Procesado' AND  detalle_ahorro.detahorroTrabCedula = '$cedula'
								GROUP BY detahorroTrabCedula
								
							UNION
								SELECT
									descuento_ahorro.descahorroTrabCedula as cedula,
									descuento_ahorro.descahorroMonto AS total_ahorro,		
									'' AS total_descuento
								FROM
									descuento_ahorro
								WHERE descuento_ahorro.descahorroTrabCedula = '$cedula' AND descuento_ahorro.descahorroEstatus = 'Procesado' AND 
								descuento_ahorro.descahorroTipo = 'Abono'
								GROUP BY descahorroTrabCedula
							UNION
								SELECT
									descuento_ahorro.descahorroTrabCedula as cedula,
									'' AS total_ahorro,		
									SUM(descuento_ahorro.descahorroMonto) AS total_descuento
								FROM
									descuento_ahorro
								WHERE descuento_ahorro.descahorroTrabCedula = '$cedula' AND descuento_ahorro.descahorroEstatus = 'Procesado' AND 
								descuento_ahorro.descahorroTipo = 'Descuento'
								GROUP BY descahorroTrabCedula
						) AS CONSULTA
						GROUP BY cedula
					";
            
			$resultado=mysql_query($Sql) or die (mysql_error());
			$row=mysql_fetch_array($resultado);
				  
            return $row['saldo'];
        }
    }	
}
?>