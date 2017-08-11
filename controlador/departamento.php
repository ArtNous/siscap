<?php
if (!isset($_SESSION)) session_start();
//$usuario = $_SESSION['usuId'];

//Filtrar por Organismo
$orgaId = isset($_SESSION['orgaId'])?$_SESSION['orgaId']:0;

include ("../modelo/departamento.class.php");
$objeto = new departamento;

//Parametros
$entidad = 'departamento';

$id = isset($_POST['id'])?$_POST['id']:'';
$descrip = isset($_POST['descrip'])?$_POST['descrip']:'';
$organismo = isset($_POST['organismo'])?$_POST['organismo']:'';

//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	
//***Fin de Parametros

if(isset($_GET['accion'])){
	if ('carga_select' == $_GET['accion'])
	    $oper = 'carga_select';
	
	if ($_GET['accion'] == 'autocompletar')
	    $oper = 'autocompletar';
}

///******* Obtener Datos Paginador
$cantidad = $objeto->cantidadReg($entidad);
$total_pag = $objeto->totalPagina($cantidad, $limite);
$inicio = $objeto->inicioPagina($pag, $total_pag, $limite);
///******* Fin Obtener Datos Paginador

///******* Obtener variables necesarias
$ip = $objeto->ObtenerIP();
$fecha = $objeto->ObtenerFecha();
$hora = $objeto->ObtenerHora();
///******* Fin Obtener variables necesarias

switch ($oper) {

    case 'add':
		//Validar que no existan Departamentos duplicados
		$existeDepartamento = $objeto->cantidadReg($entidad, " WHERE departamentoOrganismoId = '".$organismo."'  AND  departamentoDescripcion= '".$descrip."' ");
		if($existeDepartamento!=0){
			echo "Departamento Duplicado!!";	
			exit;
		}
		
		$accion = $objeto->agregar($descrip, $organismo);
        break;

    case 'edit':
		//Validar que no existan Departamentos duplicados
		$existeDepartamento = $objeto->cantidadReg($entidad, " WHERE departamentoId != '".$id."' AND departamentoOrganismoId = '".$organismo."'  AND  departamentoDescripcion= '".$descrip."' ");
		if($existeDepartamento!=0){
			echo "Departamento Duplicado!!";	
			exit;
		}
		
		$accion = $objeto->editar($id, $descrip, $organismo);
        break;

    case 'del':
        //Validar que no existan registros en Trabajador
		$existeTrabajador = $objeto->cantidadReg("trabajador", " WHERE trabDepartmentoId = '".$id."' ");
		if($existeTrabajador!=0){
			echo "No puede ser eliminado, existen trabajadores asignados a este Departamento!";	
			exit;
		}
		
		$objeto->eliminar($id);
        break;

    case 'carga_select': //carga de datos para un combo   
        ///******* Sentencia SQL para mostrar Registros
        $orgaid = ($_POST['filtro']=='')?0:$_POST['filtro'];
				
		$where = " WHERE departamentoOrganismoId = '".$orgaid."' ";
		
		$objeto->consultaGeneral($entidad,$where, 2, 'asc', '', '');

        $combo = "<select id='cmbDepartamento' name='cmbDepartamento' class='estilo-input' >";
        $combo .= " <option value=''> ----- Por favor Seleccione ----- </option>";
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $seleccion = ($_POST['opcSel']==$row['departamentoId'])? 'selected="selected"' : '';	
			
			$combo .= " <option value='" . $row['departamentoId']."' ".$seleccion." >" . $row['departamentoDescripcion'] . "</option>";
        }
        $combo .= "</select>";
        echo $combo;

        break;

    case 'autocompletar':
        
		$filtro = "";
        if (isset($_SESSION['xorgaid']) && $_SESSION['xorgaid'] != '0') {
            $filtro = " AND departamentoOrganismoId = '" . $_SESSION['xorgaid'] . "' ";
        }
		
		$where = " WHERE departamentoDescripcion like '%" . $_GET['term'] . "%' " . $filtro . " ";
		
		$accion = $objeto->consultaGeneral($entidad, $where, 1, 'asc', '', '');

        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $responce[] = array(
                'label' => $row['departamentoDescripcion'],
                'value' => $row['departamentoDescripcion'],
                'depaid' => $row['departamentoId'],
                'orgaid' => $row['departamentoOrganismoId']
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
		
		
		if($where==''){
			$where = " WHERE departamentoOrganismoId = '".$orgaId."' "; 
		}else{
			$where = " AND departamentoOrganismoId = '".$orgaId."' "; 
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
            $responce->rows[$i]['id'] = $row['departamentoId'];
            $responce->rows[$i]['cell'] = array(
										$row['departamentoId'], 
										$row['departamentoDescripcion'], 
										$row['departamentoOrganismoId']
										);
            $i++;
        }
        echo json_encode($responce);
        ///******* Fin Sentencia SQL para mostrar Registros

        break;
}
//***** Fin Oper
?>