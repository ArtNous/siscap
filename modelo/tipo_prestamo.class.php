<?php
include_once("../include/funciones.class.php");

class tipo_prestamo extends funciones {

   //Constructor Base Datos
		var $con;
		function tipo_prestamo() {
			$this->con = new DBManager;
		}
	//***Fin Constructor Base Datos
	
	//	Agregar *****
	//	Autor(es): DanielaRomero
		function agregar($nombre, $prefijo, $descuento, $tipodesc, $monto, $estatus) {
			if ($this->con->conectar() == true) {
								
				$Sql = "INSERT INTO tipo_prestamo (tipoprestNombre,tipoprestPrefijo,tipoprestDescuento,tipoprestTipodesc,tipoprestMonto,tipoprestEstatus) 
						VALUES ('$nombre','$prefijo','$descuento','$tipodesc','$monto','$estatus')";
				
				return mysql_query($Sql) or die(mysql_error());
			}
		}
	//****** Fin Agregar
	
	//	Editar *****
	//	Autor(es): DanielaRomero
		function editar($id, $nombre, $prefijo, $descuento, $tipodesc, $monto, $estatus) {
			if ($this->con->conectar() == true) {
				
				$Sql = "UPDATE tipo_prestamo SET 
							tipoprestNombre 	= '$nombre',
							tipoprestPrefijo 	= '$prefijo',
							tipoprestDescuento 	= '$descuento',
							tipoprestTipodesc 	= '$tipodesc',
							tipoprestMonto		= '$monto',
							tipoprestEstatus 	= '$estatus' 
						WHERE tipoprestId = '$id' ";
				
				return mysql_query($Sql) or die(mysql_error());
                                
			}
		}
	//****** Fin Editar
	
	//	Eliminar *****
	//	Autor(es): DanielaRomero
		function eliminar($id) {
			if ($this->con->conectar() == true) { 
				return mysql_query("DELETE FROM tipo_prestamo WHERE tipoprestId = '$id' ");
			}
		}
	//****** Fin Eliminar 
    

	//  Consultar: *****Consulta el registro a imprimir en el listado****
	//  Autor(es): DanielaRomero
    function consultarTipoPrestamos($where='') {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT	* FROM tipo_prestamo WHERE tipoprestEstatus = 'Activo' $where  ORDER BY tipoprestNombre ";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }

    function consultarTipoPrestamo() {
        if ($this->con->conectar() == true) {
            
			$Sql = "SELECT	tipoprestNombre FROM tipo_prestamo ORDER BY tipoprestNombre ";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
		
	//  Consultar: *****Consulta el registro a imprimir en el Reporte de Nomina****	
	//  Autor(es): DanielaRomero
    function consultarNominaPrestamos($cedula,$desde,$hasta) {
        if ($this->con->conectar() == true) {
            //LEFT JOIN prestamos ON (tipo_prestamo.tipoprestId = prestamos.prestamoTipoprestId AND prestamos.prestamoEstatus<>'Anulado')
			$Sql = "SELECT
						detalle_liquidacion.detliqTrabCedula,
						tipo_prestamo.tipoprestId,
						tipo_prestamo.tipoprestNombre,
						SUM(detalle_liquidacion.detliqMonto) as monto
					FROM
						tipo_prestamo
						LEFT JOIN detalle_liquidacion ON (tipo_prestamo.tipoprestId = detalle_liquidacion.detliqTipoprestId and detalle_liquidacion.detliqTrabCedula = '$cedula' and detliqFecha BETWEEN '$desde' AND '$hasta')
					WHERE tipo_prestamo.tipoprestEstatus = 'Activo'
					GROUP BY
						tipo_prestamo.tipoprestId
					ORDER BY 
						tipo_prestamo.tipoprestNombre
					";
            $this->resultado=  mysql_query($Sql);
            return true;
        }
    }
	
    
}

?>