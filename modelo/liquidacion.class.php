<?php
include_once("../include/funciones.class.php");

class liquidacion extends funciones{

	
	function agregar($codigo,$quincena,$desde,$hasta,$estatus,$fecha,$usuario) {

		if ($this->con->conectar() == true) {
			
			//Consulta de prestamos pendiente por liquidar
			$sql = " SELECT * from (SELECT
								prestamos.prestamoId,
								prestamos.prestamoFecha,
								prestamos.prestamoTrabCedula as cedula,
								trabajador.trabSueldo,
								prestamos.prestamoCuota,
								prestamos.prestamoTipoprestId,
								prestamos.prestamoTipodesc,
								prestamos.prestamoMonto as monto,
								if(detalle_liquidacion.detliqMonto is null,0,SUM(detalle_liquidacion.detliqMonto)) AS liqtotal,
								(prestamos.prestamoMonto-(if(detalle_liquidacion.detliqMonto is null,0,SUM(detalle_liquidacion.detliqMonto)))) as saldo
							FROM
								prestamos
								LEFT JOIN detalle_liquidacion ON prestamos.prestamoId = detalle_liquidacion.detliqPrestamoId
								INNER JOIN trabajador ON prestamos.prestamoTrabCedula = trabajador.trabCedula
							WHERE
								prestamos.prestamoEstatus != 'Anulado' AND prestamos.prestamoTipodesc != 'ND' AND prestamoFecha <= '".$hasta."' AND prestamos.prestamoCuota > 0
							GROUP BY prestamos.prestamoId
					) as CONSULTA
					WHERE saldo>0
					ORDER BY prestamoFecha ASC
					";
			$data = mysql_query($sql) or die (mysql_error());
		   
			
			//Guardamos en Liquidacion
			$sql3 = "INSERT INTO liquidacion 
						   (liquidacionCodigo,liquidacionDesde,liquidacionHasta,liquidacionEstatus,liquidacionFechaestatus) 
					VALUES ('$codigo','$desde','$hasta','$estatus','$fecha')";
			$result= mysql_query($sql3) or die (mysql_error());
			
			//Borramos cualquier registro anterior asignado al codigo de cierre
			$borrar_detalle = mysql_query("DELETE FROM detalle_liquidacion WHERE detliqLiquidacionCodigo = '".$codigo."'");
			
			//Guardamos en detalle_liquidacion
			while($row = mysql_fetch_array($data)) {
			
				$cedula = $row['cedula'];
				$prestamo = $row['prestamoId'];
				$tipoprest = $row['prestamoTipoprestId'];
				$sueldo = $row['trabSueldo'];
				$tipo = $row['prestamoTipodesc'];
				$saldo = number_format($row['saldo'], 2, '.', '');
				$monto=0;
				
				//Calculamos el monto en base al tipo de decuento segun el tipo de prestamo, si es por % o cuota fija
				if($tipo=='%')
					$monto = number_format(($sueldo*$row['prestamoCuota'])/100, 2, '.', '');
				else
					$monto = number_format($row['prestamoCuota'], 2, '.', '');
				
				//Si es Quincenal
				if($quincena!="")
					$monto=$monto/2;
				
				//Si el monto a cancelar es mayor al saldo pendiente por liquidar, entonces el monto sera igual al saldo pendiente
				if($monto>$saldo)
					$monto=$saldo;
				
				
				$sql2 = "INSERT INTO detalle_liquidacion 
							(detliqTrabCedula,detliqFecha,detliqPrestamoId,detliqTipoprestId,detliqMonto,detliqLiquidacionCodigo,detliqUsuCedula) 
						 VALUES ('$cedula','$hasta','$prestamo','$tipoprest','$monto','$codigo','$usuario')";
				$result= mysql_query($sql2) or die (mysql_error());
					
			}

			//Generar descuentos tipo Global o Detallado (Clinica,Funeraria, otros configurados)
			$sql2 = "INSERT INTO detalle_liquidacion 
						(SELECT 
							'' as id,
							descuentos.cedula,						
							0 as prestamo,
						  	descuentos.tipo_prestamo,
						  	'$hasta' as fecha,
							IF(tipo_descuento_asociado.monto>0 AND tipo_descuento='D',tipo_descuento_asociado.monto,descuentos.monto) AS monto,
							'$codigo' as codigo,
							'$usuario' as usuarios
						FROM
							(SELECT							
								trabajador.trabCedula AS cedula,
								tipo_prestamo.tipoprestId as tipo_prestamo,
								tipo_prestamo.tipoprestDescuento as tipo_descuento,
								ROUND(IF(tipoprestTipodesc='%',trabajador.trabSueldo*tipo_prestamo.tipoprestMonto/100,tipo_prestamo.tipoprestMonto),2) as monto
							FROM trabajador, tipo_prestamo
							WHERE
								(tipo_prestamo.tipoprestDescuento ='G' OR (tipo_prestamo.tipoprestDescuento ='D' AND trabajador.trabCedula IN (SELECT trabCedula from tipo_descuento_asociado where tipodescId = tipo_prestamo.tipoprestId ))) AND trabajador.trabFechaingreso <= '$hasta'  AND (trabajador.trabFechaegreso >= '$hasta' OR trabajador.trabFechaegreso is NULL) AND tipoprestEstatus = 'activo') AS descuentos
							LEFT JOIN tipo_descuento_asociado ON tipo_descuento_asociado.trabCedula = descuentos.cedula AND tipo_descuento_asociado.tipodescId = descuentos.tipo_prestamo
						ORDER BY descuentos.cedula ASC 
					)";
			$result= mysql_query($sql2) or die (mysql_error());
				
			return $result;				
		}
	}
	//Fin agregar regsitro
	
	//	Eliminar liquidacion: *********
	//	Autor(es): DanielaRomero	
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				$result = mysql_query("DELETE FROM detalle_liquidacion WHERE detliqLiquidacionCodigo = '".$id."'");
				$result = mysql_query("DELETE FROM liquidacion WHERE liquidacionCodigo = '".$id."'");
				
				return $result;
			}
		}
	//****** Fin Eliminar liquidacion
	
	
	
	//	Gestionar cierre liquidacion: *********
	//	Autor(es): DanielaRomero	
		function procesar_cierre($id,$fecha) {
			if ($this->con->conectar() == true) {
				
				$result = mysql_query("UPDATE liquidacion  SET 
											liquidacionEstatus = 'Procesado',
											liquidacionFechaestatus = '$fecha'
										WHERE liquidacionCodigo = '$id' ");
				return $result;
			}
		}
	//****** Fin Eliminar liquidacion
	
	
	//******Consulta por codigo de caja de ahorro para Reportes
	function consultar($codigo) {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT * FROM liquidacion WHERE liquidacionCodigo = '".$codigo."' ";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
	//******Consulta General con parametros para Reportes:  Resumen General o por organismo
	function detalleResumen($where='',$grupo='',$ord='') {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT 
						liquidacion.cajahorroId,
						liquidacion.cajahorroDesde,
						liquidacion.cajahorroHasta,
						liquidacion.cajahorroFechaestatus,
						liquidacion.cajahorroEstatus,
						liquidacion.cajahorroPorcentaje,
						liquidacion.cajahorroCantidad,
						SUM(detalle_ahorro.detahorroMonto) AS cajahorroMonto,
						organismo.organismoId,
						organismo.organismoDescripcion
					FROM liquidacion 
						INNER JOIN detalle_ahorro ON liquidacion.cajahorroId = detalle_ahorro.detahorroCajahorroId
						INNER JOIN trabajador ON detalle_ahorro.detahorroTrabCedula = trabajador.trabCedula
						INNER JOIN organismo ON organismo.organismoId = trabajador.trabOrganismoId
					$where $grupo $ord ";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
	

}

?>
