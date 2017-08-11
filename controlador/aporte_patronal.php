<?php
if (!isset($_SESSION)) session_start();
	
//Incluir las Clases y Objetos
include ("../modelo/aporte_patronal.class.php");
$objeto = new aporte_patronal;

//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);
}
 
//Entidad
$entidad = 'aporte_patronal';
		
//Parametros
$id 		= isset($_POST['id'])?$_POST['id']:'';
$ano		= isset($_POST['ano'])?$_POST['ano']:'';
$mesdesde	= isset($_POST['mesdesde'])?$_POST['mesdesde']:'';
$meshasta	= isset($_POST['meshasta'])?$_POST['meshasta']:'';
$codigo		= isset($_POST['codigo'])?$_POST['codigo']:'';
$concepto	= isset($_POST['concepto'])?$_POST['concepto']:'';
$fecha 		= isset($_POST['fechareg'])?$objeto->convertirFecha($_POST['fechareg']):'';
	
	
//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:''; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

	//***Fin de Parametros
		
	if (!$ord)
		$ord = 1;

	switch ($oper) {
	
		case 'add':
			
			//Validar que no existan Cierres con el mismo codigo
			$existe = $objeto->cantidadReg($entidad, " WHERE aporteCodigo = '".$codigo."' ");
			if($existe!=0){
				echo "Ya existe un pago generado del mes ".$mesdesde." al ".$meshasta."  a&ntilde;o ".$ano." ";	
				exit;
			}

			//Verificar que no exista un mes generado anteriormente
			$where="WHERE substr(aporteCodigo,1,4) = '".$ano."' AND 
					( ('".$mesdesde."' BETWEEN substr(aporteCodigo,5,2) AND substr(aporteCodigo,7,2) OR  '".$meshasta."' BETWEEN substr(aporteCodigo,5,2) AND substr(aporteCodigo,7,2))
					OR (substr(aporteCodigo,5,2) BETWEEN '".$mesdesde."' AND '".$meshasta."' OR  substr(aporteCodigo,7,2) BETWEEN '".$mesdesde."' AND '".$meshasta."' ) ) ";
			$existe = $objeto->cantidadReg($entidad,$where);
			if($existe!=0){
				echo "Ya se ha generado uno de los meses correspondiente al a&ntilde;o ".$ano." ";	
				exit;
			}


			$objeto->agregar($codigo,$ano,$mesdesde,$meshasta,$concepto,$fecha); 

		
			break;

		case 'edit':
			$objeto->editar($id,$concepto,$fecha);
			break;

		case 'del':
			$objeto->eliminar($id);
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

			$campos=" aporte_patronal.*, 
					  IF(SUM(descuento_ahorro.descahorroMonto)>0,SUM(descuento_ahorro.descahorroMonto),SUM(aporte_patronal_temp.descahorroMonto)) as total ";
			
			$entidad .=" LEFT JOIN descuento_ahorro ON descuento_ahorro.descahorroCodigo = aporte_patronal.aporteCodigo AND descuento_ahorro.descahorroEstatus = 'Procesado'
						 LEFT JOIN aporte_patronal_temp ON aporte_patronal_temp.descahorroCodigo = aporte_patronal.aporteCodigo ";
			
			$grupo=" GROUP BY aporte_patronal.aporteCodigo ";
			
			///******* Sentencia SQL para mostrar Registros
			$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite,$campos,$grupo);
				
			$responce = new StdClass;
			$responce->page    = $pag;
			$responce->total   = $total_pag;
			$responce->records = $cantidad;
			$i=0;
			
			while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
						
				$fechareg = $objeto->convertirFecha($row['aporteFecha']);
				$total= number_format($row['total'], 2, ',', '.');
				$icono = "<a class='consultar' name='".$row['aporteCodigo']."'><img src='imagenes/icoCarpeta-b.png' style='width:25px; border:none; cursor:pointer;' title='Ver detalle' alt='Detalle' /></a>";
						
						$responce->rows[$i]['id']=$row['aporteCodigo'];
						$responce->rows[$i]['cell']=array(
                              	$row['aporteCodigo'],
								substr($row['aporteCodigo'],0,4),
								substr($row['aporteCodigo'],4,2),
								substr($row['aporteCodigo'],6,2),
								$row['aporteConcepto'],
								$row['aporteEstatus'],
								$total,
								$fechareg,
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
