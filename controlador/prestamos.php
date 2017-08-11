<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];
$tipo_usuario = $_SESSION['usuTipo'];

//Incluir las Clases y Objetos
include ("../modelo/prestamos.class.php");
$objeto = new prestamos;

//Parametros
$entidad = 'prestamos';

$trabced = $_SESSION['trabced'];

$id = $_POST['id'];
$cedula = $_POST['cedula'];

$facturanro = $_POST['facturanro'];
$facturafecha = ($_POST['facturafecha']=='')?'':substr($_POST['facturafecha'],6,4)."-".substr($_POST['facturafecha'],3,2)."-".substr($_POST['facturafecha'],0,2);    		
$empresa = $_POST['empresa'];
$concepto = $_POST['concepto'];

$financiero = $_POST['financiero'];
$porcentaje = $_POST['porcentaje'];
$meses = $_POST['meses'];
$cheque = $_POST['cheque'];


$tipoprest = $_POST['tipoprest'];
$fechaprest = ($_POST['fechaprest']=='')?'':substr($_POST['fechaprest'],6,4)."-".substr($_POST['fechaprest'],3,2)."-".substr($_POST['fechaprest'],0,2);    		
$tipodesc = $_POST['tipodesc'];
$cuota = $_POST['cuota'];
$monto = $_POST['monto'];

$observacion = $_POST['observacion'];

$estatus = $_POST['estatus'];
$fechaestatus = ($_POST['fechaestatus']=='')?'':substr($_POST['fechaestatus'],6,4)."-".substr($_POST['fechaestatus'],3,2)."-".substr($_POST['fechaestatus'],0,2);    		

//**********Paginador
$oper = $_POST['oper']; // get the requested page
$pag = $_GET['page'];  // get the requested page
$limite = $_GET['rows'];  // get how many rows we want to have into the grid
$ord = $_GET['sidx']; // get index row - i.e. user click to sort
$dir = $_GET['sord'];  // get the direction Acs o Desc
//***Fin de Parametros


if ('carga_select' == $_GET['accion']) {
    $oper = 'carga_select';
};

if ($_GET['accion'] == 'autocompletar'){
	$oper='autocompletar';
};

if ($_GET['accion'] == 'autocompletarEmpresa'){
	$oper='autocompletarEmpresa';
};

if (!$ord)
    $ord = 1;

switch ($oper) {

    case 'add':
		$objeto->agregar($cedula, $facturanro, $facturafecha, $empresa, $concepto, $tipoprest, $fechaprest, $tipodesc, $cuota, $monto, $observacion,$financiero,$porcentaje ,$meses,$cheque);
        break;

    case 'edit':
		
		if($tipo_usuario != 'Administrador'){
			$existeLiq = $objeto->cantidadReg("detalle_liquidacion"," WHERE detliqPrestamoId = '".$id."' ");
			if($existeLiq!=0){
				echo" Ya existen liquidaciones procesadas para este pr&eacute;stamo. <br />
					  por favor, debe comunicarse con el Administrador para su modificaci&oacute;n.";
				exit;
			}
		}
		
		$objeto->editar($id, $cedula, $facturanro, $facturafecha, $empresa, $concepto, $tipoprest, $fechaprest, $tipodesc, $cuota, $monto, $observacion, $financiero, $porcentaje , $meses, $cheque);

        break;

    case 'del':
		if($tipo_usuario != 'Administrador'){
			$existeLiq = $objeto->cantidadReg("detalle_liquidacion"," WHERE detliqPrestamoId = '".$id."' ");
			if($existeLiq!=0){
				echo" Ya existen liquidaciones procesadas para este pr&eacute;stamo. <br />
					  por favor, debe comunicarse con el Administrador para procesar su eliminacion.";
				exit;
			}
		}

		$objeto->eliminar($id);
        break;
		

    case 'carga_select': //carga de datos para un combo   
        
		///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, '', 2, 'ASC', 0, $cantidad);

        $combo = "<select id='cmbPrestamo' name='cmbPrestamo' >";
		$combo .= " <option value='' > ---- Por favor Seleccione --- </option>";
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $combo .= "<option value='" . $row['prestamoId']."'>" . $row['prestamoNombre'] . "</option>";
        }
        $combo .= "</select>";
        echo $combo;

        break;
	
	case 'autocompletar':
		
			$objeto->consultaGeneral($entidad,' where prestamoNombre like "%' . $_GET['term'] . '%"  ','prestamoNombre', 'ASC', 0, 10);
       
            while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
                $responce[] = array(
                    'label'    		 => $row['prestamoNombre'],
                    'value'    		 => $row['prestamoNombre'],
                    'prestamoid'         => $row['prestamoId'] 
                );
            }
        
        echo json_encode($responce);
        break;
	
	case 'autocompletarEmpresa':
		
			$objeto->consultaGeneral($entidad,' where prestamoEmpresa like "%' . $_GET['term'] . '%"   GROUP BY prestamoEmpresa','prestamoEmpresa', 'ASC', 0, 10);
       
            while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
                $responce[] = array(
                    'label'    		 => $row['prestamoEmpresa'],
                    'value'    		 => $row['prestamoEmpresa']
                );
            }
        
        echo json_encode($responce);
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
		
		$entidad .=  " LEFT JOIN tipo_prestamo ON prestamos.prestamoTipoprestId = tipo_prestamo.tipoprestId ";
		
		if($where==''){
			$where = " WHERE prestamoTrabCedula = '".$trabced."' "; 
		}else{
			$where .= " AND prestamoTrabCedula = '".$trabced."' "; 
		}
		
        //******* Obtener Datos Paginador
        $cantidad = $objeto->cantidadReg($entidad, $where);
        $total_pag = $objeto->totalPagina($cantidad, $limite);
        $inicio = $objeto->inicioPagina($pag, $total_pag, $limite);
        ///******* Fin Obtener Datos Paginador

        ///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, $where, $ord, $dir, $inicio, $limite);

        
        $responce = new StdClass;
        $responce->page = $pag;
        $responce->total = $total_pag;
        $responce->records = $cantidad;
        $i = 0;
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            
			$icono = "<a class='btnDetalle' name='".$row['prestamoId']."'><img src='imagenes/icoCarpeta-b.png' style='width:28px; border:none; height:25px; cursor:pointer;' title='Ver detalle' alt='Detalle' /></a>";
			
			$responce->rows[$i]['id'] = $row['prestamoId'];
            $responce->rows[$i]['cell'] = array(
						$row['prestamoId'], 
						$row['prestamoTrabCedula'], 
						$row['prestamoEmpresa'], 
						$row['prestamoFacturaNro'], 
						($row['prestamoFacturaFecha']=="")?'':substr($row['prestamoFacturaFecha'],8,2)."-".substr($row['prestamoFacturaFecha'],5,2)."-".substr($row['prestamoFacturaFecha'],0,4),						
						$row['prestamoConcepto'], 
						($row['prestamoFecha']=="")?'':substr($row['prestamoFecha'],8,2)."-".substr($row['prestamoFecha'],5,2)."-".substr($row['prestamoFecha'],0,4),						
						//$row['prestamoTipoprestId'], 
						$row['tipoprestNombre'], 
						$row['prestamoFinanciero'], 
						$row['prestamoIntereses'], 
						
						$row['prestamoMonto'], 
						
						$row['prestamoMeses'], 
						$row['prestamoCheque'], 
						
						$row['prestamoCuota'], 
						$row['prestamoTipodesc'], 
						$row['prestamoObservacion'], 
						$row['prestamoEstatus'], 
						($row['prestamoFechaestatus']=="")?'':substr($row['prestamoFechaestatus'],8,2)."-".substr($row['prestamoFechaestatus'],5,2)."-".substr($row['prestamoFechaestatus'],0,4),												
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