<?php
include_once("../include/funciones.class.php");

class consultas extends funciones {

   //Constructor Base Datos
		var $con;
		function consultas() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//  Autor(es): DanielaRomero
    function consultarTrabajador($cedula) {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT
						trabajador.trabCedula,
						trabajador.trabCodigo,
						CONCAT(trabajador.trabNombre,' ',trabajador.trabApellido) AS trabNombres,
						trabajador.trabFechaingreso,
						trabajador.trabOrganismoId,
						organismo.organismoDescripcion,
						trabajador.trabDepartmentoId,
						departamento.departamentoDescripcion,
						trabajador.trabCargo,
						trabajador.trabSueldo,
						trabajador.trabEstatus,
						trabajador.trabFechaegreso,
						trabajador.trabTelefono
						
					FROM
						trabajador
						LEFT JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
						LEFT JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId
					WHERE trabCedula = '".$cedula."'
					";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
	//  Autor(es): DanielaRomero
    function consultarAhorros($cedula) {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT  
						fecha,
						SUM(total_ahorro) AS debe,
						SUM(total_descuento) as haber
						FROM (
								SELECT
									detalle_ahorro.detahorroFecha  as fecha,
									detalle_ahorro.detahorroMonto AS total_ahorro,
									'' as total_descuento
								FROM
									detalle_ahorro
									INNER JOIN caja_ahorro ON detalle_ahorro.detahorroCajahorroId = caja_ahorro.cajahorroId
								WHERE caja_ahorro.cajahorroEstatus = 'Procesado' AND  detalle_ahorro.detahorroTrabCedula = '$cedula'
							UNION
							
								SELECT
									descuento_ahorro.descahorroFecha as fecha,
									if(descahorroTipo='Abono',descuento_ahorro.descahorroMonto,'')  AS total_ahorro,		
									if(descahorroTipo='Descuento',descuento_ahorro.descahorroMonto,'') AS total_descuento
								FROM
									descuento_ahorro
								WHERE descuento_ahorro.descahorroTrabCedula = '$cedula' AND descuento_ahorro.descahorroEstatus = 'Procesado'
							
						) AS CONSULTA
						GROUP BY fecha
					";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
	//  Autor(es): DanielaRomero
    function consultarTipoPrestamoTrab($cedula) {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT
						tipo_prestamo.tipoprestId,
						tipo_prestamo.tipoprestNombre,
						prestamos.prestamoTrabCedula,
						prestamos.prestamoFecha
					FROM
						tipo_prestamo
						LEFT JOIN  prestamos ON prestamos.prestamoTipoprestId = tipo_prestamo.tipoprestId AND prestamoTrabCedula = '".$cedula."'
					GROUP BY
						tipo_prestamo.tipoprestId
					ORDER BY
						prestamos.prestamoTrabCedula DESC,
						tipo_prestamo.tipoprestNombre ASC
					";
            $this->result1=  mysql_query($Sql);
            return true;
        }
    }
	

	
	//  Autor(es): DanielaRomero
    function consultarPrestamos($cedula,$tipo) {
        if ($this->con->conectar() == true) {
            
			
			$Sql = "SELECT	tipo,fecha,sum(debe) AS debe,sum(haber) as haber FROM
						(SELECT 
							prestamos.prestamoTipoprestId AS tipo,
							prestamos.prestamoFecha AS fecha,
							prestamos.prestamoMonto AS debe,
							0*0 AS haber
						 FROM prestamos 
						 WHERE prestamoEstatus!='Anulado' AND prestamoTrabCedula = '".$cedula."' AND prestamoTipoprestId = '".$tipo."'
						UNION
						SELECT 
							prestamos.prestamoTipoprestId AS tipo,
							detalle_liquidacion.detliqFecha as fecha,
							0*0 as debe,
							detalle_liquidacion.detliqMonto as haber
						 FROM detalle_liquidacion
						 LEFT JOIN prestamos ON (prestamos.prestamoId=detalle_liquidacion.detliqPrestamoId)
						 WHERE prestamoEstatus!='Anulado' AND prestamoTrabCedula = '".$cedula."' AND prestamoTipoprestId = '".$tipo."'
						) AS DEBE_HABER
					GROUP BY fecha
					ORDER BY fecha ASC
					";
            $this->result2=  mysql_query($Sql);
            return true;
        }
    }
	
}

?>