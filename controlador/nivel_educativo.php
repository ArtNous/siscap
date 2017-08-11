<?php

//Incluir las Clases y Objetos
include ("../modelo/nivel_educativo.class.php");
$objeto = new nivel_educativo;

//Parametros
$entidad = 'nivel_educativo';

$id = $_POST['id'];
$descrip = $_POST['descrip'];

//**********Paginador
$oper = $_POST['oper']; // get the requested page
$pag = $_GET['page'];  // get the requested page
$limite = $_GET['rows'];  // get how many rows we want to have into the grid
$ord = $_GET['sidx']; // get index row - i.e. user click to sort
$dir = $_GET['sord'];  // get the direction Acs o Desc
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


if ('carga_select' == $_GET['accion']) {
    $oper = 'carga_select';
};

if ($_GET['accion'] == 'autocompletar'){
	$oper='autocompletar';
};


if (!$ord)
    $ord = 1;

switch ($oper) {

    case 'add':
        $accion = $objeto->agregar($descrip);
        break;

    case 'edit':
        $accion = $objeto->editar($id,$descrip);
        break;

    case 'del':
		$accion = $objeto->eliminar($id);
        break;
	
	case 'autocompletar':
		
			$accion = $objeto->consultaGeneral($entidad,' where nivelDescripcion like "%' . $_GET['term'] . '%" ','nivelDescripcion', 'ASC', 0, 10);
       
            while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
                $responce[] = array(
                    'label'    		 => $row['nivelDescripcion'],
                    'value'    		 => $row['nivelDescripcion'],
                    'descrip'    	 => $row['nivelDescripcion'] 
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
			
        ///******* Fin contruccion de variable where
        ///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, $where, $ord, $dir, $inicio, $limite);

        $responce = new StdClass;
        $responce->page = $pag;
        $responce->total = $total_pag;
        $responce->records = $cantidad;
        $i = 0;
        while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $responce->rows[$i]['id'] = $row['nivelDescripcion'];
            $responce->rows[$i]['cell'] = array($row['nivelDescripcion']);
            $i++;
        }
        echo json_encode($responce);
        ///******* Fin Sentencia SQL para mostrar Registros

        break;
}
//***** Fin Oper
?>