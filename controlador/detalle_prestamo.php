<?php
	if (!isset($_SESSION)) session_start();
	$usuario = $_SESSION['usuId'];

//Incluir las Clases y Objetos
include ("../modelo/detalle_prestamo.class.php");
$objeto = new detalle_prestamo;
	
//Incluir las Clases y Objetos
include ("../modelo/prestamos.class.php");
$objeto_prestamos = new prestamos;

//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);  
}
  	
//**********Parametros
	$entidad = 'detalle_liquidacion';
	
	$id 		= $_POST['id'];
	$codigo 	= $_POST['codigo'];
	$prestamoid 	= $_POST['prestamoid'];	
	$fechaliq 	= substr($_POST['fechaliq'], 6, 4) . "-" . substr($_POST['fechaliq'], 3, 2) . "-" . substr($_POST['fechaliq'], 0, 2);		
	$sueldo 	= $_POST['sueldo'];
	$monto 		= $_POST['monto'];
	
//**********Paginador
	$oper = $_POST['oper']; // get the requested page
	$pag = $_GET['page'];  // get the requested page
	$limite = $_GET['rows'];  // get how many rows we want to have into the grid
	$ord = $_GET['sidx']; // get index row - i.e. user click to sort
	$dir = $_GET['sord'];  // get the direction Acs o Desc	
//***Fin de Parametros
		
	if (!$ord)
		$ord = 1;
			
switch ($oper) {
	
		case 'add':
			$saldo = $objeto_prestamos->consultarSaldo($prestamoid);
			$objeto->agregar($prestamoid,$fechaliq,$monto,$saldo,$usuario); 
			$objeto_prestamos->actualizarPrestamos($prestamoid); // parametros (IdPrestamo, CodigoCierre) 
			break;

		case 'edit':
			
			$saldo = $objeto_prestamos->consultarSaldo($prestamoid);
			$objeto->editar($id,$fechaliq,$monto,$saldo,$usuario);
			$objeto_prestamos->actualizarPrestamos($prestamoid); // parametros (IdPrestamo, CodigoCierre) 
			break;

		case 'del':
			$objeto->eliminar($id);
			$objeto_prestamos->actualizarPrestamos($prestamoid); // parametros (IdPrestamo, CodigoCierre) 
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
			
				$entidad .=" LEFT JOIN usuarios ON (usuarios.usuCedula = detalle_liquidacion.detliqUsuCedula)
							 LEFT JOIN liquidacion ON (liquidacion.liquidacionCodigo = detalle_liquidacion.detliqLiquidacionCodigo) ";
				
				$filtro = ($_GET['filtro']=='')?0:$_GET['filtro'];
				
			   if($where == ''){
					$where = " WHERE detliqPrestamoId = '".$filtro."' ";
				}else{
					$where .= " AND detliqPrestamoId = '".$filtro."' ";
				} 
				
				
				///******* Obtener Datos Paginador
				
				$cantidad  = $objeto->cantidadReg($entidad,$where);
				$total_pag = $objeto->totalPagina($cantidad,$limite);
				$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);
				///******* Fin Obtener Datos Paginador	

				///******* Sentencia SQL para mostrar Registros
				$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite);
				
				$responce = new StdClass;
				$responce->page    = $pag;
				$responce->total   = $total_pag;
				$responce->records = $cantidad;
				$i=0;
				
				$total=0;
				
				while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
						$total += $row[detliqMonto];
						$responce->rows[$i]['id']=$row['detliqId'];
						$responce->rows[$i]['cell']=array(
                                                    $row['detliqId'],
													$row['detliqPrestamoId'],
													$row['usuCedula'], 
													$row['usuNombre'].' '.$row['usuApellido'],
													$row['detliqLiquidacionCodigo'],
													($row['detliqFecha']=="")?'':substr($row['detliqFecha'],8,2)."-".substr($row['detliqFecha'],5,2)."-".substr($row['detliqFecha'],0,4),																			
													$row['detliqSueldo'],
													$row['detliqMonto']
													
                                                   
							                 );
						$i++;
					}    
				
				$responce->userdata['fechaliq'] = 'Total:';
				$responce->userdata['monto'] = $total;
				
				echo json_encode($responce);
				///******* Fin Sentencia SQL para mostrar Registros
			    break;
			}
			
	//***** Fin Oper
?>
