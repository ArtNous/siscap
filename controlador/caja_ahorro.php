<?php
if (!isset($_SESSION)) session_start();

//Incluir las Clases y Objetos
include ("../modelo/caja_ahorro.class.php");
$objeto = new caja_ahorro;

//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);
}
 	
//Entidad
	$entidad = 'caja_ahorro';
		
	//Parametros
	$id 		= $_POST['id'];
	
	$ano 		= $_POST['ano'];	
	$mes 		= $_POST['mes'];
	
	$codigo 	= $_POST['codigo'];
	$codmes		= substr($_POST['codigo'],0,7);
	$quincena   = substr($_POST['codigo'],9,1);
	echo $quincena;
	
	
	$desde 		= substr($_POST['desde'],6,4)."-".substr($_POST['desde'],3,2)."-".substr($_POST['desde'],0,2);
	$hasta 		= substr($_POST['hasta'],6,4)."-".substr($_POST['hasta'],3,2)."-".substr($_POST['hasta'],0,2);
	
	
	$porcentaje = $_POST['porcentaje'];
	$total 		= $_POST['total'];
	$estatus 	= $_POST['estatus'];
	$fechaE		= $_POST['fechaE'];
			
	//**********Paginador
	$oper = $_POST['oper']; // get the requested page
	$pag = $_GET['page'];  // get the requested page
	$limite = $_GET['rows'];  // get how many rows we want to have into the grid
	$ord = $_GET['sidx']; // get index row - i.e. user click to sort
	$dir = $_GET['sord'];  // get the direction Acs o Desc	

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
				$existePrestamo = $objeto->cantidadReg($entidad, " WHERE cajahorroEstatus = 'Pendiente' ");
				if($existePrestamo!=0){
					echo "No se puede generar!! Existe un cierre \"Pendiente\" por procesar...";	
					exit;
				}
				
				//Validar que no existan Cierres con el mismo codigo
				$existe = $objeto->cantidadReg($entidad, " WHERE cajahorroId = '".$codmes."' ");
				if($existe!=0){
					echo "Ya existe un cierre generado del mes ".$mes." a&ntilde;o ".$ano." ";	
					exit;
				}
				
				//Validar que la Quincena No exista generada
				$existe = $objeto->cantidadReg($entidad, " WHERE cajahorroId = '".$codigo."' ");
				if($existe!=0){
					echo "Ya existe generada la Quincena ".$quincena." del mes ".$mes." a&ntilde;o ".$ano." ";	
					exit;
				}
				
				
				//Validar que slo permita generar proximo mes
				$objeto->consultaGeneral($entidad, "", 'cajahorroId', 'DESC', 0, 1);
				$row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC);
				$ultimoAno = (int)substr($row['cajahorroId'],0,4);
				$ultimoMes = (int)substr($row['cajahorroId'],5,2);
				$ultimaQ   = substr($row['cajahorroId'],8,2);
				
				if($ultimaQ=="Q1" ){
					$proxCierre = $ultimoAno.'-'.substr($row['cajahorroId'],5,2).'-Q2';
				}else{
					
					if($ultimoMes<9){
						$proxCierre = $ultimoAno.'-0'.($ultimoMes+1);
					}else{
						if($ultimoMes<12){					
							$proxCierre = $ultimoAno.'-'.($ultimoMes+1);
						}else if($ultimoMes==12){
							$proxCierre = ($ultimoAno+1).'-01';
						}								
					}
					
					$proxCierre.=($quincena!='')?'-Q1':'';
				}

				/*if ($codigo != $proxCierre) {
					echo " El p&oacute;ximo Cierre pendiente por generar es: ".$proxCierre;	
					exit;
				} */
				
			}
			
			$objeto->agregar($codigo,$quincena,$desde,$hasta,$porcentaje,$estatus,$fecha); 
			break;


		case 'del':
			$objeto->eliminar($id);
			break;
		
		
		case 'procesar_cierre':
			$objeto->procesar_cierre($id,$fecha);
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
						$fechaE = substr($row['cajahorroFechaestatus'],8,2)."-".substr($row['cajahorroFechaestatus'],5,2)."-".substr($row['cajahorroFechaestatus'],0,4);
						$total= number_format($row['cajahorroTotal'], 2, ',', '.');
						$icono = "<a class='consultar' name='".$row['cajahorroId']."'><img src='imagenes/icoCarpeta-b.png' style='width:25px; border:none; cursor:pointer;' title='Ver detalle' alt='Detalle' /></a>";
						
						$responce->rows[$i]['id']=$row['cajahorroId'];
						$responce->rows[$i]['cell']=array(
                                                    $row['cajahorroId'],
													substr($row['cajahorroId'],0,4),
													substr($row['cajahorroId'],5,2),
                                                    //$row['cajahorroMes'],
													//$row['cajahorroAno'],
                                                    $fechaD,
													$fechaH,
													$fechaE,
													$row['cajahorroEstatus'],
													$row['cajahorroCantidad'],
													$row['cajahorroPorcentaje'].' %',
													$total,
													$icono
													
													
							                 );
						$i++;
					}    

				echo json_encode($responce);
				///******* Fin Sentencia SQL para mostrar Registros
			    break;
			}
			
	//***** Fin Oper
?>
