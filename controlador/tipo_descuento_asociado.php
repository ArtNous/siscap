<?php
if (!isset($_SESSION)) session_start();

//Incluir las Clases y Objetos
include ("../modelo/tipo_descuento_asociado.class.php");
$objeto = new tipo_descuento_asociado;
	

//Permite limpiar los post enviados de otras paginas	
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);  
}
 	
//Parametros
$entidad = 'tipo_descuento_asociado';

if(isset($_POST['id']))
	$cedula = $_POST['id'];

if(isset($_POST['codigo']))
	$codigo = $_POST['codigo'];

else if(isset($_GET['filtro']))
	$codigo = $_GET['filtro'];

if(isset($_POST['monto']))
	$monto = $_POST['monto'];


$buscarCedula 	= isset($_GET['trabCedula'])?$_GET['trabCedula']:'';
$buscarNombre 	= isset($_GET['trabNombre'])?$_GET['trabNombre']:'';
$buscarMonto 	= isset($_GET['monto'])?$_GET['monto']:'';


//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

//***Fin de Parametros
		
		
switch ($oper) {

	case 'add':
		$objeto->agregar($codigo, $cedula);
		break;

	case 'add-all':
		$objeto->agregar_todo($codigo);
		break;

	case 'edit':
		$objeto->agregar($codigo, $cedula, $monto);
		break;

	case 'del':
		$objeto->eliminar($codigo, $cedula);
		break;
	
	case 'del-all':
		$objeto->eliminar_todo($codigo);
		break;

	default:	
		
		$where="WHERE trabajador.trabEstatus <> 'inactivo' ";

		if($buscarCedula<>'')
			$where .= " AND trabajador.trabCedula like '" . $buscarCedula . "%' ";
		
		if($buscarNombre<>''){
			//BUSCA LAS OPCIONES POR VARIAS PALABRAS
			$valoresx = explode(' ', $buscarNombre);
			foreach ($valoresx as $valeorx) {
				$where.= " AND (trabNombre like '%$valeorx%' or trabApellido like '%$valeorx%')";
			}
		}

		if($buscarMonto>0)
			$where .= " AND  tipo_descuento_asociado.monto like '" . $buscarMonto . "%' ";
		
		
		$filtro = ($_GET['filtro']=='')?0:$_GET['filtro'];
		$entidad =" trabajador LEFT JOIN tipo_descuento_asociado ON trabajador.trabCedula = tipo_descuento_asociado.trabCedula AND tipo_descuento_asociado.tipodescId = '".$filtro."' ";
		
		$campos = "
			trabajador.trabCedula,
			trabajador.trabNombre,
			trabajador.trabApellido,
			tipo_descuento_asociado.monto,
			if(tipo_descuento_asociado.tipodescId<>'',1,0) as descuento
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

			$checked = ($row['descuento']=='1')?'checked':'';
			$checkbox = "<input id='chk".$filtro."' name='".$row['trabCedula']."' type='checkbox'  ".$checked." >";
			
			$responce->rows[$i]['id']=$row['trabCedula'];
			$responce->rows[$i]['cell']=array(
                    $filtro,
                    $cedula,													
					$row['trabNombre'].' '.$row['trabApellido'],
					$row['monto'],
					$checkbox
			);
			$i++;
		}    

		echo json_encode($responce);
		///******* Fin Sentencia SQL para mostrar Registros
		break;
}
//***** Fin Oper
?>