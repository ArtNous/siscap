<?php
if (!isset($_SESSION)) session_start();

//Incluir las Clases y Objetos
include ("../modelo/detalle_liquidacion.class.php");
$objeto = new detalle_liquidacion;
	
//Incluir las Clases y Objetos
include ("../modelo/prestamos.class.php");
$objPrestamos = new prestamos;

//Permite limpiar los post enviados de otras paginas
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);
}
  
	
//********Parametros
	$entidad = 'detalle_liquidacion';
	
if(isset($_POST['id']))
	$id = $_POST['id'];

if(isset($_POST['codigo']))
	$codigo = $_POST['codigo'];	

if(isset($_POST['prestamoid']))
	$prestamoid = $_POST['prestamoid'];

if(isset($_POST['montoliq']))
	$monto 	= $_POST['montoliq'];

	$buscarCedula 	= isset($_GET['trabCedula'])?$_GET['trabCedula']:'';
	$buscarNombre 	= isset($_GET['trabNombre'])?$_GET['trabNombre']:'';
	$buscarTipo 	= isset($_GET['tipoprestNombre'])?$_GET['tipoprestNombre']:'';
	
//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

//***Fin de Parametros
		
switch ($oper) {

	case 'edit':
		$saldo = $objPrestamos->consultarSaldo($prestamoid);
		$objeto->editar($id,$monto,$saldo,$usuario);
		break;

	case 'del':
		$objeto->eliminar($id);
		break;
		
	default:

		$filtro = ($_GET['filtro']=='')?0:$_GET['filtro'];
		
		$where = " WHERE detliqLiquidacionCodigo = '".$filtro."' ";

		if($buscarCedula<>'')
			$where .= " AND trabCedula like '" . $buscarCedula . "%' ";
		
		if($buscarNombre<>''){
			$valoresx = explode(' ', $buscarNombre);
			foreach ($valoresx as $valeorx) {
				$where.= " AND (trabNombre like '%$valeorx%' or trabApellido like '%$valeorx%') ";
			}
		}

		if($buscarTipo<>'')
			$where .= " AND tipoprestNombre like '%" . $buscarTipo . "%' ";


			$entidad .=" LEFT JOIN prestamos ON (prestamos.prestamoId = detalle_liquidacion.detliqPrestamoId)
						 LEFT JOIN tipo_prestamo ON (tipo_prestamo.tipoprestId = detalle_liquidacion.detliqTipoprestId)
						 LEFT JOIN trabajador ON (trabajador.trabCedula = detalle_liquidacion.detliqTrabCedula)
						 LEFT JOIN liquidacion ON (liquidacion.liquidacionCodigo = detalle_liquidacion.detliqLiquidacionCodigo) ";
			

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
			
			while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
				$nombre= explode(' ',$row['trabNombre']);
				$apellido= explode(' ',$row['trabApellido']);
				$nombres =$nombre[0].' '.$apellido[0];
		
					$responce->rows[$i]['id']=$row['detliqId'];
					$responce->rows[$i]['cell']=array(
	                                            $row['detliqId'],
												$row['trabCedula'], 
												$nombres,
												$row['tipoprestNombre'],
												$row['detliqMonto']
						                 );
					$i++;
				}    

		echo json_encode($responce);
		///******* Fin Sentencia SQL para mostrar Registros
	    break;
}
	
//***** Fin Oper
?>