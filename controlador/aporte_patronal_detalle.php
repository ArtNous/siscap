<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];

//Incluir las Clases y Objetos
include ("../modelo/aporte_patronal.class.php");
$objeto = new aporte_patronal;

include ("../modelo/descuento_ahorro.class.php");
$objeto_desc = new descuento_ahorro;
	
//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);  
}
 	
//Parametros
$tabla =  $_SESSION['tabla'];

$codigo 	= ($_GET['filtro']=='')?0:$_GET['filtro'];
$cedula 	= $_POST['id'];
$monto 		= $_POST['monto'];
$fecha 		= $objeto->convertirFecha($_POST['fechareg']);

$buscarCedula 	= $_GET['trabCedula'];
$buscarNombre 	= $_GET['trabNombre'];
$buscarMonto 	= $_GET['aporteMonto'];

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

	case 'edit':
			$objeto->editar_detalle($tabla,$codigo,$cedula,$monto);
		break;

	case 'del':
			$objeto->eliminar_detalle($tabla, $codigo,$cedula);
		break;

	case 'procesar':
		$where=" WHERE descahorroMonto > 0 ";
		$cantidad = $objeto->cantidadReg($tabla,$where);

		if($cantidad>0)
			$objeto->procesar($codigo,$usuario);
	
		echo $cantidad;
		break;

	default:	

		$where="WHERE descahorroCodigo = '".$codigo."' ";

		if($buscarCedula<>'')
			$where .= " AND trabCedula like '" . $buscarCedula . "%' ";
		if($buscarNombre<>''){
			//BUSCA LAS OPCIONES POR VARIAS PALABRAS
			$valoresx = explode(' ', $buscarNombre);
			foreach ($valoresx as $valeorx) {
				$where.= " AND (trabNombre like '%$valeorx%' or trabApellido like '%$valeorx%')";
			}
		}

		if($buscarMonto<>'')
			$where .= " AND descahorroMonto like '" . $buscarMonto . "%' ";
		
		$entidad = $tabla." LEFT JOIN trabajador ON trabajador.trabCedula = ".$tabla.".descahorroTrabCedula ";
		
		$campos = "
			trabajador.trabCedula,
			trabajador.trabNombre,
			trabajador.trabApellido,
			descahorroMonto as monto
		";

		
		///******* Obtener Datos Paginador
		$cantidad  = $objeto->cantidadReg($entidad,$where);
		$total_pag = $objeto->totalPagina($cantidad,$limite);
		$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);				

		///******* Sentencia SQL para mostrar Registros				
		$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite,$campos);
		
		$responce = new StdClass;
		$responce->page    = $pag;
		$responce->total   = $total_pag;
		$responce->records = $cantidad;
		$i=0;
		
		while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
			
			$cedula = number_format($row['trabCedula'], 0, ',', '.');
			
			$responce->rows[$i]['id']=$row['trabCedula'];
			$responce->rows[$i]['cell']=array(
                    $codigo,
                    $cedula,													
					$row['trabNombre'].' '.$row['trabApellido'],
					$row['monto']
			);
			$i++;
		}    

		echo json_encode($responce);
		///******* Fin Sentencia SQL para mostrar Registros
		break;
}
//***** Fin Oper
?>