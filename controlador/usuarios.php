<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];
$tipo_usuario = $_SESSION['usuTipo'];

foreach ($_POST as $key => $value) {
    $_POST[$key] = str_replace("'", "", $value);
    $_POST[$key] = str_replace('"', '', $value);
}

//Incluir las Clases y Objetos
include ("../modelo/usuarios.class.php");
$objeto = new usuarios;

include ("../modelo/trabajador.class.php");
$objTrabajador = new trabajador;


$entidad = 'usuarios';

$cedula = isset($_POST['cedula'])?$_POST['cedula']:'';
$nombre = isset($_POST['nombre'])?strtoupper($_POST['nombre']):'';
$apellido = isset($_POST['apellido'])?strtoupper($_POST['apellido']):'';
$correo = isset($_POST['correo'])?strtolower($_POST['correo']):'';
$telefono = isset($_POST['telefono'])?$_POST['telefono']:'';
$tipo = isset($_POST['tipo'])?$_POST['tipo']:'';
$estatus = isset($_POST['estatus'])?$_POST['estatus']:'';

$clave = isset($_POST['clave'])?$_POST['clave']:'';
if ($clave != '') {
    $clave = SHA1($_POST['clave']);
}

//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:''; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

//**** Encriptacion por Seguridad
if ($_POST['accion'] == 'xssggx') {
    $oper = 'buscar';
    $datos = explode("||", base64_decode($_POST['xt56yz'])); //
    $cedula = $datos[0];
    $clave = $datos[1];
	$tipo = $datos[2];
};

//***Fin de Parametros

if (!$ord)
    $ord = 1;

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

        $where = " WHERE usuCedula = '" . $cedula . "' ";
        $cant = $objeto->cantidadReg($entidad, $where);

        if ($cant > 0) {
            echo ('Ya existe un Usuario registrado con la C&eacute;dula Nro. ' . $cedula);
        } else {
            $accion = $objeto->agregar($cedula, $nombre, $apellido, $correo, $telefono, $tipo, $clave);
        }

        break;

    case 'edit':
        
        $accion = $objeto->editar($cedula, $nombre, $apellido, $correo, $telefono, $tipo, $clave, $estatus);
        break;

    case 'del':
        
		if ($cedula == "admin") {
            echo" No puedes eliminar el usuario 'admin'.";
			exit;
        } 
		
		if ($cedula == $usuario) {
            echo" No puedes eliminar el usuario que actualmente se encuantra logeado.";
			exit;
        } 
		
		$accion = $objeto->eliminar($cedula);
       
        break;

    case 'buscar':
		
		$acceso = "invalido";
        $accion = 'Acceso Fallido';
        $_SESSION['logeado'] = false;					
		
		if($tipo=="Trabajador"){
			
			$where = " WHERE trabCedula = '" . $cedula . "' ";
			$objeto->consultaGeneral("trabajador", $where, 'trabCedula', 'ASC', 0, 1);
			while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
				
				//echo"ento ".$row['trabClave']." || ".SHA1($clave)." ||" ;
				
				if ($row['trabEstatus'] == 'inactivo') {
					$acceso = "inactivo";
				}else if ($row['trabClave'] == SHA1($clave)) {
					
					$_SESSION['logeado'] = true;
					$accion = 'Acceso';
					$acceso = "valido";

					$_SESSION['usuId'] = $row['trabCedula'];
					$_SESSION['usuNombres'] = $row['trabNombre'] . ' ' . $row['trabApellido'];
					$_SESSION['usuTipo'] = "Trabajador";
				}
			}
		}else{
			$where = " WHERE usuCedula = '" . $cedula . "' AND usuTipo = '".$tipo."' ";
			$objeto->consultaGeneral($entidad, $where, $ord, $dir, 0, 1);
			while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
				if ($row['usuEstatus'] == 'Inactivo') {
					$acceso = "inactivo";
				}else if ($row['usuClave'] == SHA1($clave)) {

					$_SESSION['logeado'] = true;
					$accion = 'Acceso';
					$acceso = "valido";

					$_SESSION['usuId'] = $row['usuCedula'];
					$_SESSION['usuNombres'] = $row['usuNombre'] . ' ' . $row['usuApellido'];
					$_SESSION['usuTipo'] = $row['usuTipo'];
				}
			}
		}
		
        echo $acceso;
        break;

	 case 'verificarClave':
		
		$clave = base64_decode($_POST['datos']);
		$result=0;
			
			
			/*** Buscar en tabla Trabajdor ***/
			if($tipo_usuario=="Trabajador"){
				$where = " WHERE trabCedula = '" . $usuario . "' ";
				$objeto->consultaGeneral('trabajador', $where, 'trabCedula', 'ASC', 0, 1);
				while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
					if ($row['trabClave'] == SHA1($clave)) {
						$result = 1;
					} 
				}
			}else{
				/*** Buscar en tabla Usuarios ***/
				$where = " WHERE usuCedula = '" . $usuario. "' ";
				$objeto->consultaGeneral($entidad, $where, 'usuCedula', 'ASC', 0, 1);
				while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
					if ($row['usuClave'] == SHA1($clave)) {
						$result = 1;
					} 
				}
			
			}
		
		echo $result;
        break;
		
	case 'actualizarClave':
		
		$clave = SHA1(base64_decode($_POST['datos']));
				
		if($tipo_usuario=="Trabajador"){
			$accion = $objTrabajador->actualizarClave($usuario,$clave);
		}else{
			$accion = $objeto->actualizarClave($usuario,$clave);
		}
		
		
        break;

    default:
	
        if ($_SESSION['logeado'] == true) {
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
            
			if($usuario != "admin" && $where==""){
				$where = " WHERE usuCedula <> 'admin' ";
			}else if($usuario != "admin" && $where!=""){
				$where .= " AND usuCedula <> 'admin' ";
			}
			
			
            /////******* Obtener Datos Paginador
            $cantidad = $objeto->cantidadReg($entidad,$where);
            $total_pag = $objeto->totalPagina($cantidad, $limite);
            $inicio = $objeto->inicioPagina($pag, $total_pag, $limite);
            ///******* Fin Obtener Datos Paginador	
            //
            ///******* Sentencia SQL para mostrar Registros
            $objeto->consultaGeneral($entidad, $where, $ord, $dir, $inicio, $limite);

            $responce = new StdClass;
            $responce->page = $pag;
            $responce->total = $total_pag;
            $responce->records = $cantidad;
            $i = 0;

            while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {

                $responce->rows[$i]['id'] = $row['usuCedula'];
                $responce->rows[$i]['cell'] = array(
							$row['usuCedula'], 
							$row['usuNombre'], 
							$row['usuApellido'], 
							$row['usuCorreo'], 
							$row['usuTelefono'],
							$row['usuTipo'], 
							'', 
							'', 
							$row['usuEstatus']
				);
                $i++;
            }

            echo json_encode($responce);
            ///******* Fin Sentencia SQL para mostrar Registros
        }else {
            Header("Location: index.php");
        }
        break;
}
//***** Fin Oper
?>
