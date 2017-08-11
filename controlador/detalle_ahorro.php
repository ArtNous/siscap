<?php
if (!isset($_SESSION)) session_start();

//Incluir las Clases y Objetos
include ("../modelo/detalle_ahorro.class.php");
$objeto = new detalle_ahorro;
	
include ("../modelo/caja_ahorro.class.php");
$objCajahorro = new caja_ahorro;

//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);  
}
 	
//Parametros
$entidad = 'detalle_ahorro';

$id 		= $_POST['id'];
$codigo 	= $_POST['codigo'];
$cedula 	= $_POST['cedula'];
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
		//Validar que no existan Ahorros procesados
		$existe = $objeto->cantidadReg($entidad, " WHERE detahorroCajahorroId = '".$codigo."' AND detahorroTrabCedula = '".$cedula."' ");
		if($existe!=0){
			$objeto->editar($codigo,$cedula,$monto);
		}else{
			$objeto->agregar($codigo,$cedula,$sueldo,$monto); 
		}
		$objCajahorro->actualizar_cajahorro($codigo); 
		break;

	case 'edit':
		$objeto->editar($codigo,$cedula,$monto);
		$objCajahorro->actualizar_cajahorro($codigo); 
		break;

	case 'del':
		$objeto->eliminar($codigo,$id);
		$objCajahorro->actualizar_cajahorro($codigo); 
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
					
		$entidad .=" INNER JOIN trabajador ON (detalle_ahorro.detahorroTrabCedula = trabajador.trabCedula) 
					 INNER JOIN organismo ON (trabajador.trabOrganismoId = organismo.organismoId)
					 INNER JOIN departamento ON (trabajador.trabDepartmentoId = departamento.departamentoId) ";
			
		$filtro = ($_GET['filtro']=='')?0:$_GET['filtro'];
		
	   if($where == ''){
			$where = " WHERE detahorroCajahorroId = '".$filtro."' ";
		}else{
			$where .= " AND detahorroCajahorroId = '".$filtro."' ";
		} 
				
		///******* Obtener Datos Paginador
		$cantidad  = $objeto->cantidadReg($entidad,$where);
		$total_pag = $objeto->totalPagina($cantidad,$limite);
		$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);				

		///******* Sentencia SQL para mostrar Registros				
		$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite);
		
		$responce = new StdClass;
		$responce->page    = $pag;
		$responce->total   = $total_pag;
		$responce->records = $cantidad;
		$i=0;
		
		while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
				
			$icono = "<a class='verTrabajador' name='".$row['detahorroTrabCedula']."'><img src='imagenes/icono_detalle.png' style='width:26px; border:none; height:22px; cursor:pointer;' title='Ver Trabajador' alt='Ver' /></a>";
					
			$responce->rows[$i]['id']=$row['detahorroTrabCedula'];
			$responce->rows[$i]['cell']=array(
                    $row['detahorroCajahorroId'],													
					$row['detahorroTrabCedula'],
					$row['trabNombre'].' '.$row['trabApellido'],
					$row['organismoDescripcion'],
					$row['departamentoDescripcion'],
					$row['trabCargo'],
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