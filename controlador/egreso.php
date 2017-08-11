<?php
	if (!isset($_SESSION)) session_start();
	//$usuario=$_SESSION['usuId'];
	
	//Incluir las Clases y Objetos
	include ("../modelo/trabajador.class.php");
	$objeto = new trabajador;
	
  
//Parametros
$entidad      = 'trabajador';

$id       	= isset($_POST['id'])?$_POST['id']:'';
$cedula     = isset($_POST['cedula'])?$_POST['cedula']:'';
$codigo     = isset($_POST['codigo'])?$_POST['codigo']:'';
$fechaegreso = isset($_POST['fechaegreso'])?$objeto->convertirFecha($_POST['fechaegreso']):'';
$observacion = isset($_POST['observacion'])?$_POST['observacion']:'';        

//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:1;  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:''; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	
//***Fin de Parametros
	
	if (!$ord)
		$ord = 1;

	///******* Obtener Datos Paginador
		$cantidad  = $objeto->cantidadReg($entidad);
		$total_pag = $objeto->totalPagina($cantidad,$limite);
		$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);
	///******* Fin Obtener Datos Paginador	
	
	///******* Obtener variables necesarias
		$ip        = $objeto->ObtenerIP();
		$fecha     = $objeto->ObtenerFecha();
		$hora      = $objeto->ObtenerHora();
	///******* Fin Obtener variables necesarias

	
	switch ($oper) {
		
		case 'add':
			$objeto->gestionar_egreso($cedula,'E'.$codigo,$fechaegreso,$observacion,'inactivo');
			break;

		case 'edit':
			$objeto->gestionar_egreso($id,$codigo,$fechaegreso,$observacion,'inactivo');
			break;

		case 'del':
			$objeto->gestionar_egreso($id,$codigo,'','','activo');
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
			

			///******* Sentencia SQL para mostrar Registros
				
				$entidad .=" INNER JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
							 INNER JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId ";
				
				if($where==""){
					$where=" WHERE trabEstatus = 'inactivo' ";
				}else{
					$where.=" AND trabEstatus = 'inactivo' ";
				}
				
				///******* Obtener Datos Paginador
				$cantidad  = $objeto->cantidadReg($entidad,$where);
				$total_pag = $objeto->totalPagina($cantidad,$limite);
				$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);
				///******* Fin Obtener Datos Paginador	
				
				
				$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite);
				
				$responce = new StdClass;
				$responce->page    = $pag;
				$responce->total   = $total_pag;
				$responce->records = $cantidad;
				$i=0;
				
				while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
					$nombre= explode(' ',$row['trabNombre']);
					$apellido= explode(' ',$row['trabApellido']);
					$nombres =$nombre[0].' '.$apellido[0];
			
					$responce->rows[$i]['id']=$row['trabCedula'];
					$responce->rows[$i]['cell']=array(
											
											$row['trabCedula'],
											$row['trabCodigo'],
											$nombres,
											//$row['trabOrganismoId'],
											$row['organismoDescripcion'],
											//$row['trabDepartmentoId'],
											$row['departamentoDescripcion'],
											$row['trabCargo'],

											substr($row['trabFechaingreso'],8,2)."-".substr($row['trabFechaingreso'],5,2)."-".substr($row['trabFechaingreso'],0,4),
											substr($row['trabFechaegreso'],8,2)."-".substr($row['trabFechaegreso'],5,2)."-".substr($row['trabFechaegreso'],0,4)	,
											$row['trabObservacion']
					
					);
					$i++;
				}    

	            
				echo json_encode($responce);
			///******* Fin Sentencia SQL para mostrar Registros
			
			break;
	}	
	//***** Fin Oper
?>
