<?php
//Incluir las Clases y Objetos
include ("../modelo/tipo_prestamo.class.php");
$objeto = new tipo_prestamo;

//Parametros
$entidad = 'tipo_prestamo';

if(isset($_POST['id']))
	$id = $_POST['id'];

if(isset($_POST['nombre']))
	$nombre = $_POST['nombre'];

if(isset($_POST['prefijo']))
	$prefijo = $_POST['prefijo'];

if(isset($_POST['descuento']))
	$descuento = $_POST['descuento'];

if(isset($_POST['tipodesc']))
	$tipodesc = $_POST['tipodesc'];

if(isset($_POST['monto']))
	$monto = ($_POST['monto']!='')?$_POST['monto']:0;

if(isset($_POST['estatus']))
	$estatus = $_POST['estatus'];


//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

if(isset($_GET['oper']))
	$oper=$_GET['oper'];

switch ($oper) {

    case 'add':
		//Validar que no existan NOMBRES duplicados
		$existeNombre = $objeto->cantidadReg($entidad, " WHERE tipoprestNombre= '".$nombre."' ");
		if($existeNombre!=0){
			echo "Nombre de Tipo de Prestamo ya existe!!";	
			exit;
		}		
		
		$objeto->agregar($nombre, $prefijo, $descuento, $tipodesc, $monto, $estatus);
        break;

    case 'edit':
		//Validar que no existan NOMBRES duplicados
		$existeNombre = $objeto->cantidadReg($entidad, " WHERE tipoprestNombre= '".$Nombre."' AND tipoprestId != '".$id."' ");
		if($existeNombre!=0){
			echo "Nombre de Tipo de Prestamo ya existe!!";	
			exit;
		}		
		
        $objeto->editar($id, $nombre, $prefijo, $descuento, $tipodesc, $monto, $estatus);
        break;

    case 'del':
		$objeto->eliminar($id);
        break;
		

    case 'carga_select': //carga de datos para un combo   
        
		///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad,'','tipoprestNombre','ASC');

        $combo = "<select id='cmbTipoprest' name='cmbTipoprest' class='estilo-input' >";
		$combo .= " <option value='' > ---- Por favor Seleccione --- </option>";
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $combo .= "<option value='" . $row['tipoprestId']."' >" . $row['tipoprestNombre'] . "</option>";
        }
        $combo .= "</select>";
        echo $combo;

        break;

    case 'carga_select_prestamo': //carga de datos para un combo   
        
        $where = " WHERE tipoprestDescuento = 'P' ";
		///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad,$where,'tipoprestNombre','ASC');

        $combo = "<select id='cmbTipoprest' name='cmbTipoprest' class='estilo-input' >";
		$combo .= " <option value='' > ---- Por favor Seleccione --- </option>";
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $combo .= "<option value='" . $row['tipoprestId']."' >" . $row['tipoprestNombre'] . "</option>";
        }
        $combo .= "</select>";
        echo $combo;

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
		$cantidad = $objeto->cantidadReg($entidad,$where);
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
            $icono = "<a class='consultar' name='".$row['tipoprestId']."'><img src='imagenes/icoAsignar.png'  title='Asociados' alt='ver' style='border:none; cursor:pointer;' /></a>";

            $responce->rows[$i]['id'] = $row['tipoprestId'];
            $responce->rows[$i]['cell'] = array(
						$row['tipoprestId'], 
						$row['tipoprestNombre'], 
						$row['tipoprestPrefijo'], 
						$row['tipoprestDescuento'], 
						$row['tipoprestTipodesc'], 
						$row['tipoprestMonto'], 
						$row['tipoprestEstatus'],
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