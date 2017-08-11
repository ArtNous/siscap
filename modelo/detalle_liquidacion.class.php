<?php
include_once("../include/funciones.class.php");

class detalle_liquidacion extends funciones{
	
		
	//Editar registro
	function editar($id,$monto, $saldo, $usuario) {
		if ($this->con->conectar() == true) {
			
			$sql = mysql_query(" SELECT * FROM detalle_liquidacion WHERE detliqId = '".$id."' " ) or die (mysql_error());
			$row=mysql_fetch_array($sql);
			$monto_ant = $row['detliqMonto'];
			$saldo_nvo = $saldo+$monto_ant;
			
			if($monto<=$saldo_nvo){
			
				$sql2 = "UPDATE detalle_liquidacion 
						SET 							
							detliqMonto = '".$monto."',
							detliqUsuCedula = '".$usuario."'
					WHERE detliqId = '".$id."' ";
			
				$result = mysql_query($sql2) or die (mysql_error());
			
			}else{
				echo "El Monto a ingresar supera el monto por liquidar: ".$saldo_nvo;
			}
			
			return true;

		}
	}//Fin editar registro
	
	//	Eliminar registro: *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				$sql = "DELETE FROM detalle_liquidacion  WHERE detliqId = '".$id."'";
						
				return mysql_query($sql) or die (mysql_error());
			}
		}
	//****** Fin Eliminar registro
	
	// Reporte por Parametros (listado: Registros de Liquidaciones)
	//  Autor(es): DanielaRomero 
    function consultar($where='',$group='',$ord='') {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT 
						trabajador.trabCedula,
						CONCAT(trabajador.trabNombre,' ',trabajador.trabApellido) AS trabNombres,
						trabajador.trabNombre, 
						trabajador.trabApellido,
						trabajador.trabOrganismoId,
						organismo.organismoDescripcion,
						trabajador.trabDepartmentoId,
						departamento.departamentoDescripcion,
						tipo_prestamo.tipoprestNombre,	
						prestamos.*,
						liquidacion.*,
						detalle_liquidacion.*

					FROM detalle_liquidacion
							LEFT JOIN prestamos ON detalle_liquidacion.detliqPrestamoId = prestamos.prestamoId		
							LEFT JOIN tipo_prestamo ON prestamos.prestamoTipoprestId = tipo_prestamo.tipoprestId							
							LEFT JOIN trabajador ON trabajador.trabCedula = prestamos.prestamoTrabCedula							
							LEFT JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
							LEFT JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId
							LEFT JOIN liquidacion ON detalle_liquidacion.detliqLiquidacionCodigo = liquidacion.liquidacionCodigo

					$where	
					$group
					$ord
					";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
}

?>
