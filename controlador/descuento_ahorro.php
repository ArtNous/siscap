<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];
$tipo_usuario = $_SESSION['usuTipo'];

//Incluir las Clases y Objetos
include ("../modelo/descuento_ahorro.class.php");
$objeto = new descuento_ahorro;

//Parametros
$entidad = 'descuento_ahorro';

$trabced = $_SESSION['trabced'];
$id = $_POST['id'];
$cedula = $_POST['cedula'];
$tipo = $_POST['tipo'];
$concepto = $_POST['concepto'];
$fechadesc = $objeto->convertirFecha($_POST['fechadesc']);    		
$monto = $_POST['monto'];
$estatus = $_POST['estatus'];
$fechaestatus = $objeto->convertirFecha($_POST['fechaestatus']); 

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
		$objeto->agregar($cedula, $tipo, $concepto,$fechadesc, $monto,$usuario);
        break;

    case 'edit':
		
		$objeto->editar($id,$cedula, $tipo, $concepto,$fechadesc, $monto, $estatus, $fechaestatus,$usuario);
		
        break;

    case 'del':
		$objeto->eliminar($id);
        break;
	
	case 'consultarSaldo':
		$saldo = $objeto->consultarSaldoAhorro($cedula);
		echo $saldo;        
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
		
			
		$entidad.=" LEFT JOIN usuarios ON descuento_ahorro.descahorroUsuCedula = usuarios.usuCedula ";
		
		if($where==''){
			$where = " WHERE descahorroTrabCedula = '".$trabced."' "; 
		}else{
			$where .= " AND descahorroTrabCedula = '".$trabced."' "; 
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
            
			$fechadesc=$objeto->convertirFecha($row['descahorroFecha']);
			$fechaestatus=$objeto->convertirFecha($row['descahorroFechaestatus']);
			$icono = "<a class='btnImprimir' name='".$row['descahorroId']."'><img src='imagenes/icoImpresora.png' style=' padding:3px; border:none; cursor:pointer;' title='Imprimir Comprobante' alt='Imprimir' /></a>";
			
			$nombres = explode(' ',$row['usuNombre']);
			$apellidos = explode(' ',$row['usuApellido']);
			$usuario = $nombres[0].' '.$apellidos[0];
			
			$responce->rows[$i]['id'] = $row['descahorroId'];
            $responce->rows[$i]['cell'] = array(
						$row['descahorroId'], 
						$row['descahorroTrabCedula'], 
						$objeto->consultarSaldoAhorro($row['descahorroTrabCedula']),
						$row['descahorroConcepto'], 
						$fechadesc,
						$row['descahorroTipo'], 
						$row['descahorroMonto'], 
						$row['descahorroEstatus'], 
						$fechaestatus,
						$usuario,
						($row['descahorroEstatus']=='Anulado')?'':$icono
			);
			
            $i++;
        }
        echo json_encode($responce);
        ///******* Fin Sentencia SQL para mostrar Registros

        break;
}
//***** Fin Oper
?>