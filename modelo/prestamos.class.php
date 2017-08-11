<?php
include_once("../include/funciones.class.php");

class prestamos extends funciones {

   //Constructor Base Datos
		var $con;
		function prestamos() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar  *****
	//	Autor(es): DanielaRomero
		function agregar($cedula, $facturanro, $facturafecha, $empresa, $concepto, $tipoprest, $fechaprest, $tipodesc, $cuota, $monto, $observacion, $financiero,$porcentaje, $meses,$cheque) {
			if ($this->con->conectar() == true) {
				if($meses!=''){
					$Sql = "INSERT INTO prestamos 
							(prestamoTrabCedula,prestamoFacturaNro,prestamoFacturaFecha,prestamoEmpresa,prestamoConcepto,prestamoTipoprestId,prestamoFecha,prestamoMonto,prestamoCuota,prestamoTipodesc,prestamoObservacion,prestamoEstatus,prestamoFinanciero,prestamoIntereses,prestamoMeses,prestamoCheque) 
							VALUES ('$cedula','$facturanro','$facturafecha','$empresa','$concepto','$tipoprest','$fechaprest','$monto','$cuota','$tipodesc','$observacion','Pendiente','$financiero','$porcentaje','$meses','$cheque')";
				}else{
						$Sql = "INSERT INTO prestamos 
							(prestamoTrabCedula,prestamoFacturaNro,prestamoFacturaFecha,prestamoEmpresa,prestamoConcepto,prestamoTipoprestId,prestamoFecha,prestamoMonto,prestamoCuota,prestamoTipodesc,prestamoObservacion,prestamoEstatus,prestamoFinanciero,prestamoIntereses,prestamoMeses,prestamoCheque) 
							VALUES ('$cedula','$facturanro','$facturafecha','$empresa','$concepto','$tipoprest','$fechaprest','$monto','$cuota','$tipodesc','$observacion','Pendiente','$financiero',NULL,NULL,NULL)";

				}
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar 
	
	//	Editar *****
	//	Autor(es): DanielaRomero
		function editar($id, $cedula, $facturanro, $facturafecha, $empresa, $concepto, $tipoprest, $fechaprest, $tipodesc, $cuota, $monto, $observacion, $financiero, $porcentaje, $meses,$cheque) {
			if ($this->con->conectar() == true) {
								
				$campos = ($meses=='')?"prestamoIntereses=NULL, prestamoCheque=NULL ":"prestamoMeses='".$meses."', prestamoCheque='".$cheque."' ";
	
					$Sql = "UPDATE prestamos SET 
								prestamoFacturaNro='".$facturanro."',
								prestamoFacturaFecha='".$facturafecha."',
								prestamoEmpresa='".$empresa."',
								prestamoConcepto='".$concepto."',
								prestamoTipoprestId = '".$tipoprest."',
								prestamoFecha='".$fechaprest."',
								prestamoMonto='".$monto."',
								prestamoTipodesc='".$tipodesc."', 
								prestamoCuota='".$cuota."',
								prestamoObservacion='".$observacion."',
								prestamoFinanciero='".$financiero."',						
								".$campos."
						WHERE prestamoId = '".$id."'";
				
						
				$result = mysql_query($Sql) or die(mysql_error());

				$this->actualizarPrestamos($id);

				return $result;
                                
			}
		}
	//****** Fin 
	
	//	Eliminar : *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) {
				
				$result =  mysql_query("DELETE FROM detalle_liquidacion WHERE detliqPrestamoId = '$id' ");
				$result =  mysql_query("DELETE FROM prestamos WHERE prestamoId = '$id' ");				

				return $result;
			}
		}
	//****** Fin Eliminar 
        
    
	function actualizarPrestamos($id="",$cierre="") {

		if ($this->con->conectar() == true) {
					
			$where = "";

			if($id!='')
				$where = "WHERE prestamoId = '$id' ";

			if($cierre!='')
				$where = "WHERE prestamos.prestamoID IN(SELECT detliqPrestamoId FROM detalle_liquidacion WHERE detliqLiquidacionCodigo = '$cierre')";

			$consulta = mysql_query("SELECT
						prestamos.prestamoId,
						prestamos.prestamoTrabCedula,
						prestamos.prestamoMonto,
						if(detalle_liquidacion.detliqMonto>0,Sum(detalle_liquidacion.detliqMonto),0) as liquidado,
						prestamos.prestamoMonto - if(detalle_liquidacion.detliqMonto>0,Sum(detalle_liquidacion.detliqMonto),0) as total
					FROM
					prestamos
					LEFT JOIN detalle_liquidacion ON prestamos.prestamoId = detalle_liquidacion.detliqPrestamoId
					$where
					GROUP BY prestamos.prestamoId
					ORDER BY prestamos.prestamoId ASC ");

			$result='';
			
			while($row = mysql_fetch_array($consulta)) {
				$prestamo = $row['prestamoId'];
				$monto = floatval($row['prestamoMonto']);
				$liquidado = floatval($row['liquidado']);
				$estatus="Pendiente";
				$fecha = "NULL";

				/*** Obtener la fecha del ultimo registro de la liquidacion ***/
				if($monto==$liquidado){
					$consulta = mysql_query(" SELECT detliqFecha FROM detalle_liquidacion WHERE detliqPrestamoId = '$prestamo' ORDER BY detliqFecha DESC LIMIT 0,1 " );
					$row=mysql_fetch_array($consulta);
					$fecha = "'".$row['detliqFecha']."'";
					$estatus="Liquidado";
				}				

				$Sql = " UPDATE prestamos SET  prestamoEstatus = '$estatus', prestamoFechaestatus = $fecha WHERE prestamoId = '$prestamo' ";									

				//echo $where.' '.$Sql; 
				$result = mysql_query($Sql) or die(mysql_error());

			}

			return $result;
				
		}
	}
	
	//******Consulta saldo Pendiente por liquidar al prestamo
	function consultarSaldo($id) {
		if ($this->con->conectar() == true) {
			$sql = mysql_query("
						SELECT
							prestamos.prestamoMonto as monto,
							IF(detalle_liquidacion.detliqMonto>0,SUM(detalle_liquidacion.detliqMonto),'0') AS liqtotal,
							(prestamos.prestamoMonto-(IF(detalle_liquidacion.detliqMonto>0,SUM(detalle_liquidacion.detliqMonto),'0'))) as saldo
						FROM
							prestamos
							LEFT JOIN detalle_liquidacion ON detalle_liquidacion.detliqPrestamoId = prestamos.prestamoId
						WHERE
							prestamos.prestamoId = ".$id."
						GROUP BY prestamos.prestamoId" ) or die (mysql_error());
			
			$row=mysql_fetch_array($sql);
			return $row['saldo'];
		}
	}
	
	/** Consulta para Reporte **/
	function consultar($cedula,$where="") {
		if ($this->con->conectar() == true) {
			$Sql = " SELECT * FROM prestamos 
						LEFT JOIN tipo_prestamo ON prestamos.prestamoTipoprestId = tipo_prestamo.tipoprestId							
						WHERE prestamoTrabCedula = '".$cedula."' ".$where."
						ORDER BY prestamoFecha ASC
						";
			$this->resultado=  mysql_query($Sql);
			return true;
		}
	}
	
	//  Autor(es): DanielaRomero
    function consultarPrestamos($where='',$ord='') {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT 
						trabajador.trabCedula,
						trabajador.trabNombre,
						trabajador.trabApellido,
						trabajador.trabOrganismoId,
						organismo.organismoDescripcion,
						trabajador.trabDepartmentoId,
						departamento.departamentoDescripcion,
						tipo_prestamo.tipoprestNombre,	
						prestamos.*,
						if(detalle_liquidacion.detliqMonto is null,0,SUM(detalle_liquidacion.detliqMonto)) AS liquidacionTotal,
						(prestamos.prestamoMonto-(if(detalle_liquidacion.detliqMonto is null,0,SUM(detalle_liquidacion.detliqMonto)))) as saldo
					FROM prestamos
						LEFT JOIN tipo_prestamo ON prestamos.prestamoTipoprestId = tipo_prestamo.tipoprestId							
						LEFT JOIN trabajador ON trabajador.trabCedula = prestamos.prestamoTrabCedula							
						LEFT JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
						LEFT JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId
						LEFT JOIN detalle_liquidacion ON detalle_liquidacion.detliqPrestamoId = prestamos.prestamoId
					$where    
					GROUP BY prestamos.prestamoId
					$ord
					";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
}

?>