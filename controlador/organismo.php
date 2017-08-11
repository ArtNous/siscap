<?php

//Incluir las Clases y Objetos
include ("../modelo/organismo.class.php");
$objeto = new organismo;

//Parametros
$entidad = 'organismo';

if(isset($_POST['id']))
	$id = $_POST['id'];

if(isset($_POST['descrip']))
	$descrip = $_POST['descrip'];

if(isset($_POST['dreccion']))
	$direccion = $_POST['dreccion'];

if(isset($_POST['telefono']))
	$telefono = $_POST['telefono'];

//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	
//***Fin de Parametros

///******* Obtener Datos Paginador
$cantidad = $objeto->cantidadReg($entidad, '');
$total_pag = $objeto->totalPagina($cantidad, $limite);
$inicio = $objeto->inicioPagina($pag, $total_pag, $limite);
///******* Fin Obtener Datos Paginador
///******* Obtener variables necesarias
$ip = $objeto->ObtenerIP();
$fecha = $objeto->ObtenerFecha();
$hora = $objeto->ObtenerHora();
///******* Fin Obtener variables necesarias


if(isset($_GET['accion'])){
	if ('carga_select' == $_GET['accion'])
	    $oper = 'carga_select';
	
	if ($_GET['accion'] == 'autocompletar')
	    $oper = 'autocompletar';
}

switch ($oper) {

    case 'add':
        $accion = $objeto->agregar($descrip, $direccion, $telefono);
        break;

    case 'edit':
        $accion = $objeto->editar($id, $descrip, $direccion, $telefono);
        break;

    case 'del':
        
		//Validar que no existan registros en Departamento
		$existeDepartamento = $objeto->cantidadReg("departamento", " WHERE departamentoOrganismoId = '".$id."' ");
		if($existeDepartamento!=0){
			echo "No puede ser eliminado, ya cuenta con Departamentos registrados!";	
			exit;
		}
		
		//Validar que no existan registros en Trabajador
		$existeTrabajador = $objeto->cantidadReg("trabajador", " WHERE trabOrganismoId = '".$id."' ");
		if($existeTrabajador!=0){
			echo "No puede ser eliminado, existen trabajadores con relaci&oacute;n a este Organismo!";	
			exit;
		}
		
		$objeto->eliminar($id);
        break;
		

    case 'carga_select': //carga de datos para un combo   
        
		///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, '', 2, 'ASC', 0, $cantidad);

        $combo = "<select id='cmbOrganismo' name='cmbOrganismo' class='estilo-input' >";
		$combo .= " <option value='' > ----- Por favor Seleccione ----- </option>";
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $combo .= "<option value='" . $row['organismoId']."'>" . $row['organismoDescripcion'] . "</option>";
        }
        $combo .= "</select>";
        echo $combo;

        break;
	
	case 'autocompletar':
		
			$accion = $objeto->consultaGeneral($entidad,' where organismoDescripcion like "%' . $_GET['term'] . '%" ','organismoDescripcion', 'ASC', 0, 10);
       
            while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
                $responce[] = array(
                    'label'    		 => $row['organismoDescripcion'],
                    'value'    		 => $row['organismoDescripcion'],
                    'orgaid'         => $row['organismoId'] 
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
			
        ///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, $where, $ord, $dir, $inicio, $limite);

        $responce = new StdClass;
        $responce->page = $pag;
        $responce->total = $total_pag;
        $responce->records = $cantidad;
        $i = 0;
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $responce->rows[$i]['id'] = $row['organismoId'];
            $responce->rows[$i]['cell'] = array($row['organismoId'], $row['organismoDescripcion'], $row['organismoDireccion'], $row['organismoTelefono']);
            $i++;
        }
        echo json_encode($responce);
        ///******* Fin Sentencia SQL para mostrar Registros

        break;
}
//***** Fin Oper
?>