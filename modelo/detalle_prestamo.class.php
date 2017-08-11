<?php
include_once("../include/funciones.class.php");

class detalle_prestamo extends funciones{
	
	//Agregar registro
	function agregar($prestamoid,$fechaliq,$monto,$saldo,$usuario) {

		if ($this->con->conectar() == true) {
			
			$consulta = mysql_query(" SELECT * FROM prestamos WHERE prestamoid = '".$prestamoid."' " ) or die (mysql_error());
			$row=mysql_fetch_array($consulta);
			$cedula = $row['prestamoTrabCedula'];
			$tipo = $row['prestamoTipoprestId'];

			if($saldo==0){
				echo " El prestamo ya se encuentra Liquidado ";
				
			}else if($monto<=$saldo){
			
				$sql = "INSERT INTO detalle_liquidacion (detliqTrabCedula,detliqPrestamoId,detliqTipoprestId,detliqFecha,detliqMonto,detliqUsuCedula) 
							VALUES ('$cedula','$prestamoid','$tipo','$fechaliq','$monto','$usuario')";
				$result = mysql_query($sql) or die (mysql_error());
				
			}else{
				echo "El Monto a ingresar supera el monto por liquidar: ".$saldo;
			}				
							
			return $result;
		}
	}//Fin agregar registro
	
	//Editar registro
	function editar($id,$fechaliq,$monto, $saldo, $usuario) {

		if ($this->con->conectar() == true) {	
						
			$sql = mysql_query(" SELECT * FROM detalle_liquidacion WHERE detliqId = '".$id."' " ) or die (mysql_error());
			$row=mysql_fetch_array($sql);
			$monto_ant = $row['detliqMonto'];
			$saldo_nvo = $saldo+$monto_ant;
			
			if($monto<=$saldo_nvo){
			
				$sql2 = "UPDATE detalle_liquidacion 
						SET 
							detliqFecha = '".$fechaliq."',
							detliqMonto = '".$monto."',
							detliqUsuCedula = '".$usuario."'
					WHERE detliqId = '".$id."' ";
			
				$result = mysql_query($sql2) or die (mysql_error());
			
			}else{
				echo "El Monto a ingresar supera el monto por liquidar: ".$saldo_nvo;
			}
			
			return $result;
		}
	}//Fin editar registro
	
	//	Eliminar registro: *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				$sql = "DELETE FROM detalle_liquidacion WHERE detliqId = '".$id."' ";
						
				return mysql_query($sql) or die (mysql_error());
			}
		}

		function eliminarLote($fecha) {
			if ($this->con->conectar() == true) {
				$sql = "DELETE FROM detalle_liquidacion WHERE detliqFecha = '".$fecha."' ";
						
				return mysql_query($sql) or die (mysql_error());
			}
		}

	//****** Fin Eliminar registro
	
	//******Consulta para Reportes
	function consultar($codigo) {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT * FROM detalle_liquidacion 
						INNER JOIN trabajador ON (detalle_liquidacion.detahorroTrabCedula = trabajador.trabCedula) 
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
