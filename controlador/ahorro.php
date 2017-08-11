<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];
$trabced = $_SESSION['trabced'];
	
//Incluir las Clases y Objetos
include ("../modelo/caja_ahorro.class.php");
$objeto = new caja_ahorro;

//Permite limpiar los post enviados de otras paginas
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);  
}
  		
//Parametros
	$entidad = 'caja_ahorro';

	$id 		= isset($_POST['id'])?$_POST['id']:'';
	$ano 		= isset($_POST['ano'])?$_POST['ano']:'';	
	$mes 		= isset($_POST['mes'])?$_POST['mes']:'';
	
	if($mes>0 && $ano>0){
		$codigo 	= $ano.'-'.$mes;
		$desde 		= $ano.'-'.$mes.'-01';
		$ultimodia 	= date("d",(mktime(0,0,0,$mes+1,1,$ano)-1));  //Obtener el ultimo dia del mes
		$hasta 		= $ano.'-'.$mes.'-'.$ultimodia;
	}
		
	$porcentaje = isset($_POST['porcentaje'])?floatval($_POST['porcentaje']):'';
	$total 		= isset($_POST['total'])?$_POST['total']:'';
	$estatus 	= isset($_POST['estatus'])?$_POST['estatus']:'';
	$fechaE		= isset($_POST['fechaE'])?$_POST['fechaE']:'';
			
	//**********Paginador
	$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
	$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
	$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
	$ord = isset($_GET['sidx'])?$_GET['sidx']:''; // get index row - i.e. user click to sort
	$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

	//***Fin de Parametros
		
	if (!$ord)
		$ord = 1;

	///******* Obtener variables necesarias
	$ip = $objeto->ObtenerIP();
	$fecha = $objeto->ObtenerFecha();
	$hora = $objeto->ObtenerHora();
	///******* Fin Obtener variables necesarias
		
	switch ($oper) {
	
		case 'add':
			//Comprobar que exista al menos un registro generado en la base de datos
			$cantidad = $objeto->cantidadReg($entidad);
			
			if($cantidad>0){
				//Validar que no existan Cierres Pendientes
				$existeAhorro = $objeto->cantidadReg($entidad, " WHERE cajahorroId = '".$codigo."' ");
				if($existeAhorro!=0){
					echo "Ya existe un cierre generado del mes ".$mes." a&ntilde;o ".$ano." ";	
					exit;
				}
				
				//Validar que no existan Cierres Pendientes
				$existePrestamo = $objeto->cantidadReg($entidad, " WHERE cajahorroEstatus = 'Pendiente' ");
				if($existePrestamo!=0){
					echo "No se puede generar!! Existe un cierre \"Pendiente\" por procesar...";	
					exit;
				}
				
				//Validar que slo permita generar proximo mes
				$objeto->consultaGeneral($entidad, "", 'cajahorroId', 'DESC', 0, 1);
				$row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC);
				$ultimoAno = (int)substr($row['cajahorroId'],0,4);
				$ultimoMes = (int)substr($row['cajahorroId'],5,2);
				
				if($ultimoMes<9){
					$proxCierre = $ultimoAno.'-0'.($ultimoMes+1);
				}else{
					if($ultimoMes<12){					
						$proxCierre = $ultimoAno.'-'.($ultimoMes+1);
					}else if($ultimoMes==12){
						$proxCierre = ($ultimoAno+1).'-01';
					}								
				}
				if ($codigo != $proxCierre) {
					echo " El p&oacute;ximo Cierre que debe generar es del Mes ".substr($proxCierre,5,2)." A&ntilde;o ".substr($proxCierre,0,4);	
					exit;
				} 
				
			}
			
			$accion = $objeto->agregar($codigo,$desde,$hasta,$porcentaje,$estatus,$fecha); 
			break;

			
		case 'edit':
			echo "Opci&oacute;n NO programada";
			//$accion = $objeto->editar($id,$codigo,$desde,$hasta,$porcentaje,$estatus,$fecha);
			break;

			
		case 'del':
			$accion = $objeto->eliminar($id);
			break;
		
		
		case 'procesar_cierre':
			$accion = $objeto->procesar_cierre($id,$fecha);
			
			echo substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
			break;
				
		default:	
			///******* contruccion de variable where				
			function getWhereClause($col, $oper, $val) {
				
				$ops = array(//array to translate the search type
					'eq' => '=', //equal
					'ne' => '<>', //not equal
					'lt' => '<', //less than
					'le' => '<=', //less than or equal
					'gt' => '>', //greater than
					'ge' => '>=', //greater than or equal
					'bw' => 'LIKE', //begins with
					'bn' => 'NOT LIKE', //doesn't begin with
					'in' => 'LIKE', //is in
					'ni' => 'NOT LIKE', //is not in
					'ew' => 'LIKE', //ends with
					'en' => 'NOT LIKE', //doesn't end with
					'cn' => 'LIKE', // contains
					'nc' => 'NOT LIKE'  //doesn't contain
				);
			
				if ($oper == 'bw' || $oper == 'bn')
					$val .= '%';
				if ($oper == 'ew' || $oper == 'en')
					$val = '%' . $val;
				if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')
					$val = '%' . $val . '%';
				
				return $where = " WHERE $col ".$ops[$oper]." '$val' ";
			}

			$where = "";
			if ($_GET['_search'] == 'true') {
				$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
				$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper'] : false;
				$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
				$where = getWhereClause($searchField, $searchOper, $searchString);
			}
			///******* Fin contruccion de variable where
				
				$entidad.=" INNER JOIN detalle_ahorro ON caja_ahorro.cajahorroId = detalle_ahorro.detahorroCajahorroId ";
				
				if($where==""){
					$where = " WHERE detalle_ahorro.detahorroTrabCedula = '".$trabced."' AND caja_ahorro.cajahorroEstatus = 'Procesado' ";
				}else{
					$where .= " AND detalle_ahorro.detahorroTrabCedula = '".$trabced."' AND caja_ahorro.cajahorroEstatus = 'Procesado' ";
				}
				
				///******* Obtener Datos Paginador
				$cantidad  = $objeto->cantidadReg($entidad,$where);
				$total_pag = $objeto->totalPagina($cantidad,$limite);
				$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);
				///******* Fin Obtener Datos Paginador	

			///******* Sentencia SQL para mostrar Registros
				//echo $where;
				$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite);
				
				$responce = new StdClass;
				$responce->page    = $pag;
				$responce->total   = $total_pag;
				$responce->records = $cantidad;
				$i=0;
				
				while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
						
						$fechaD = substr($row['cajahorroDesde'],8,2)."-".substr($row['cajahorroDesde'],5,2)."-".substr($row['cajahorroDesde'],0,4);
						$fechaH = substr($row['cajahorroHasta'],8,2)."-".substr($row['cajahorroHasta'],5,2)."-".substr($row['cajahorroHasta'],0,4);
												
						$responce->rows[$i]['id']=$row['cajahorroId'];
						$responce->rows[$i]['cell']=array(
                                                    $row['cajahorroId'],
													$row['detahorroTrabCedula'],
                                                    $fechaD,
													$fechaH,																				
													$row['cajahorroPorcentaje'].' %',
													$row['detahorroSueldo'],
													$row['detahorroMonto']
							                 );
						$i++;
					}    

				echo json_encode($responce);
				///******* Fin Sentencia SQL para mostrar Registros
			    break;
			}
			
	//***** Fin Oper
?>