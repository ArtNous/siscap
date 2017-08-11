<?php	
if (!isset($_SESSION)) session_start();

//Incluir las Clases y Objetos
include ("../modelo/trabajador.class.php");
$objeto = new trabajador;
	
//Parametros
	$entidad = "trabajador";
			
	if(isset($_POST['id']))
		$id	= $_POST['id'];
	
	if(isset($_POST['cedula']))
		$cedula = $_POST['cedula'];

	if(isset($_POST['nombre']))
		$nombre	= strtoupper($_POST['nombre']);

	if(isset($_POST['apellido']))
		$apellido = strtoupper($_POST['apellido']);
	
	if(isset($_POST['sexo']))
		$sexo = $_POST['sexo'];

	if(isset($_POST['fechanac']))
		$fechanac = ($_POST['fechanac']=="")?"":substr($_POST['fechanac'],6,4)."-".substr($_POST['fechanac'],3,2)."-".substr($_POST['fechanac'],0,2);

	if(isset($_POST['edocivil']))
		$edocivil = $_POST['edocivil'];
	
	if(isset($_POST['nivel']))
		$nivel = $_POST['nivel'];
	
	if(isset($_POST['profesion']))
		$profesion = $_POST['profesion'];

	if(isset($_POST['direccion']))
		$direccion = $_POST['direccion'];
	
	if(isset($_POST['correo']))
		$correo = strtolower($_POST['correo']);    
	
	if(isset($_POST['telefono']))
		$telefono = $_POST['telefono'];  		
	
	if(isset($_POST['codigo']))
		$codigo = $_POST['codigo']; 
	
	if(isset($_POST['fechaingreso']))
		$fechaingreso  	= substr($_POST['fechaingreso'],6,4)."-".substr($_POST['fechaingreso'],3,2)."-".substr($_POST['fechaingreso'],0,2);    		
	
	if(isset($_POST['orgaid']))
		$orgaid  = $_POST['orgaid'];

	if(isset($_POST['depaid']))
		$depaid  = $_POST['depaid'];	

	if(isset($_POST['cargo']))
		$cargo  = $_POST['cargo'];

	if(isset($_POST['sueldo']))
		$sueldo = $_POST['sueldo'];

	if(isset($_POST['estatus']))
		$estatus = $_POST['estatus'];

	if(isset($_POST['fechaegreso']))
		$fechaegreso = substr($_POST['fechaegreso'], 6, 4) . "-" . substr($_POST['fechaegreso'], 3, 2) . "-" . substr($_POST['fechaegreso'], 0, 2);

	if(isset($_POST['observacion']))
		$observacion = $_POST['observacion'];

	if(isset($_POST['clave']))
		$clave = SHA1($_POST['clave']);  		
	
//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	

//***Fin de Parametros
	
///******* Obtener variables necesarias
	$ip        = $objeto->ObtenerIP();
	$fecha     = $objeto->ObtenerFecha();
	$hora      = $objeto->ObtenerHora();
///******* Fin Obtener variables necesarias

if(isset($_GET['accion'])){
	if ($_GET['accion'] == 'autocompletar') {
		$oper = 'autocompletar';
		$campo = $_GET['campo'];
		$estatus = $_GET['estatus'];
	}
}
	
switch ($oper) {
	
	case 'add':
		$existe = $objeto->cantidadReg($entidad, " WHERE trabCedula = '".$cedula."' ");
		if($existe!=0){
			echo " Ya Existe registrado un Asociado con la C&eacute;dula Nro. ".$cedula;	
			exit;
		}
		
		$existeCodigo = $objeto->cantidadReg($entidad, " WHERE trabCodigo = '".$codigo."' AND trabEstatus != 'inactivo' ");
		if($existeCodigo!=0){
			echo " Ya Existe un Asociado activo con la C&oacute;digo Nro. ".$codigo;	
			exit;
		}
		
		$objeto->agregar($cedula,$nombre,$apellido,$sexo,$edocivil,$fechanac,$nivel,$profesion,$direccion,$correo,$telefono,$codigo,$fechaingreso,$orgaid,$depaid,$cargo,$sueldo,$fecha);
		break;

	case 'edit':
		if($cedula!=$id){
			//Validar que no existan Ahorros procesados
			$existeAhorro = $objeto->cantidadReg('detalle_ahorro', " WHERE detahorroTrabCedula = '".$id."' ");
			if($existeAhorro!=0){
				echo " No puede Modificar la C&eacute;dula del Asociado.!!  Existen registros procesados en Caja de Ahorro.";	
				exit;
			}
			
			$existePrestamo = $objeto->cantidadReg('prestamos', " WHERE prestamoTrabCedula = '".$id."' ");
			if($existePrestamo!=0){
				echo " No puede Modificar la C&eacute;dula del Asociado.!!  Existen registros procesados en Prestamos.";	
				exit;
			}
		}

		$existeCodigo = $objeto->cantidadReg($entidad, " WHERE trabCodigo = '".$codigo."' AND trabEstatus != 'inactivo' and trabCedula!='".$id."' ");
		if($existeCodigo!=0){
			echo " Ya Existe un Asociado activo con la C&oacute;digo Nro. ".$codigo;	
			exit;
		}
		
		$objeto->editar($id,$cedula,$nombre,$apellido,$sexo,$edocivil,$fechanac,$nivel,$profesion,$direccion,$correo,$telefono,$codigo,$fechaingreso,$orgaid,$depaid,$cargo,$sueldo,$fecha);
		break;

	case 'del':
		
		//Validar que no existan Ahorros procesados
		$existeAhorro = $objeto->cantidadReg('detalle_ahorro', " WHERE detahorroTrabCedula = '".$id."' ");
		if($existeAhorro!=0){
			echo " No puede eliminar al Asociado.!! <br /> Existen registros procesados en Caja de Ahorro.";	
			exit;
		}

		$existePrestamo = $objeto->cantidadReg('prestamos', " WHERE prestamoTrabCedula = '".$id."' ");
		if($existePrestamo!=0){
			echo " No puede eliminar al Asociado.!! <br /> Existen registros procesados en Prestamos.";	
			exit;
		}
		
		$objeto->eliminar($id);
		break;
	
	case 'actualizar_clave':
		$objeto->actualizarClave($cedula,$clave);
		break;
		
	case 'autocompletar':
		
		$entidad .=" INNER JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
				     INNER JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId ";						
					
		$where = '';
		$valoresx = explode(' ', $_GET['term']);
		foreach ($valoresx as $valeorx) {
			$where.= ($where == '' ? ' WHERE ' : ' and ') . "(trabCedula like '%$valeorx%' or trabNombre like '%$valeorx%' or trabApellido like '%$valeorx%')";
		}


		if($estatus==1)
			$where .=" AND trabEstatus = 'activo' ";
		
		
		$objeto->consultaGeneral($entidad,$where, 'trabCedula', 'asc', 0, 12);

		if (mysql_num_rows($objeto->resultado) > 0) {
			while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
				
				$resultado = $row['trabCedula'] . " [ " . $row['trabNombre'] . " " . $row['trabApellido'] . " ]";
				$responce[] = array(
					'label' 		=> $resultado,
					'value' 		=> $row['trabCedula'],
					'cedula' 		=> $row['trabCedula'],
					'codigo' 		=> $row['trabCodigo'],
					'nombres' 		=> $row['trabNombre'].' '.$row['trabApellido'],
					'nombre' 		=> $row['trabNombre'],
					'apellido' 		=> $row['trabApellido'],
					'direccion' 	=> $row['trabDireccion'],
					'correo' 		=> $row['trabCorreo'],
					'telefono' 		=> $row['trabTelefono'],
					'sexo' 			=> $row['trabSexo'],
					'edocivil' 		=> strtoupper($row['trabEdocivil']." (a)"),
					'fechanac' 		=> substr($row['trabFechanac'],8,2)."-".substr($row['trabFechanac'],5,2)."-".substr($row['trabFechanac'],0,4),
					'nivel' 		=> $row['trabNivel'],
					'profesion' 		=> $row['trabProfesion'],
					'organismo' 	=> $row['organismoDescripcion'],
					'departamento' 	=> $row['departamentoDescripcion'],
					'cargo' 		=> $row['trabCargo'],
					'sueldo' 		=> $row['trabSueldo'],
					'fechaingreso' 	=> substr($row['trabFechaingreso'],8,2)."-".substr($row['trabFechaingreso'],5,2)."-".substr($row['trabFechaingreso'],0,4),
					'fechaegreso' 	=> substr($row['trabFechaegreso'],8,2)."-".substr($row['trabFechaegreso'],5,2)."-".substr($row['trabFechaegreso'],0,4),
					'estatus'		=> $row['trabEstatus']
				);
			}
		}
		if (count($responce) == 0) {
				$responce[] = array(
					'label' 		=> $_GET['term']." [No Existe]",
					'value' 		=> $_GET['term'],
					'cedula' 		=> '',
					'codigo' 		=> '',
					'nombres' 		=> '',
					'nombre' 		=> '',
					'apellido' 		=> '',
					'correo' 		=> '',
					'telefono' 		=> '',
					'organismo' 	=> '',
					'departamento' 	=> '',
					'cargo' 		=> '',
					'sueldo' 		=> '',
					'fechaingreso' 	=> '',
					'fechaegreso' 	=> '',
					'estatus' 		=> ''
				);
			}	
		echo json_encode($responce); 
		break;
	
	case 'autocompletar2':
		
		$entidad .=" INNER JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
				     INNER JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId ";			
		
		$where = ' WHERE ' . $campo . ' like "%' . $_GET['term'] . '%" ';
		
		if($estatus==1){
			$where .=" AND trabEstatus = 'activo' ";
		}
		
		$accion = $objeto->consultaGeneral($entidad,$where, 'trabCedula', 'asc', 0, 12);

		if (mysql_num_rows($objeto->resultado) > 0) {
			while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
				
				if ($campo == 'trabNombre') {
					$resultado = $row['trabCedula'] . " [ " . $row['trabNombre'] . " " . $row['trabApellido'] . " ]";
				} else {
					$resultado = $row[$campo] . " [ " . $row['trabNombre'] . " " . $row['trabApellido'] . " ]";
				}
			
				$responce[] = array(
					'label' 		=> $resultado,
					'value' 		=> $row['trabCedula'],
					'cedula' 		=> $row['trabCedula'],
					'codigo' 		=> $row['trabCodigo'],
					'nombres' 		=> $row['trabNombre'].' '.$row['trabApellido'],
					'nombre' 		=> $row['trabNombre'],
					'apellido' 		=> $row['trabApellido'],
					'direccion' 	=> $row['trabDireccion'],
					'correo' 		=> $row['trabCorreo'],
					'telefono' 		=> $row['trabTelefono'],
					'sexo' 			=> $row['trabSexo'],
					'edocivil' 		=> strtoupper($row['trabEdocivil']." (a)"),
					'fechanac' 		=> substr($row['trabFechanac'],8,2)."-".substr($row['trabFechanac'],5,2)."-".substr($row['trabFechanac'],0,4),
					'nivel' 		=> $row['trabNivel'],
					'profesion' 		=> $row['trabProfesion'],
					'organismo' 	=> $row['organismoDescripcion'],
					'departamento' 	=> $row['departamentoDescripcion'],
					'cargo' 		=> $row['trabCargo'],
					'sueldo' 		=> $row['trabSueldo'],
					'fechaingreso' 	=> substr($row['trabFechaingreso'],8,2)."-".substr($row['trabFechaingreso'],5,2)."-".substr($row['trabFechaingreso'],0,4),
					'fechaegreso' 	=> substr($row['trabFechaegreso'],8,2)."-".substr($row['trabFechaegreso'],5,2)."-".substr($row['trabFechaegreso'],0,4),
					'estatus'		=> $row['trabEstatus']
				);
			}
		}
		if (count($responce) == 0) {
				$responce[] = array(
					'label' 		=> $_GET['term']." [No Existe]",
					'value' 		=> $_GET['term'],
					'cedula' 		=> '',
					'codigo' 		=> '',
					'nombres' 		=> '',
					'nombre' 		=> '',
					'apellido' 		=> '',
					'correo' 		=> '',
					'telefono' 		=> '',
					'organismo' 	=> '',
					'departamento' 	=> '',
					'cargo' 		=> '',
					'sueldo' 		=> '',
					'fechaingreso' 	=> '',
					'fechaegreso' 	=> '',
					'estatus' 		=> ''
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
					
		$where .=($where=='')?" WHERE trabEstatus='activo' ":" AND trabEstatus='activo' ";

		///******* Sentencia SQL para mostrar Registros				
		$entidad .=" INNER JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
					 INNER JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId ";

		///******* Obtener Datos Paginador
		$cantidad = $objeto->cantidadReg($entidad,$where);
		$total_pag = $objeto->totalPagina($cantidad, $limite);
		$inicio = $objeto->inicioPagina($pag, $total_pag, $limite);

        ///******* Sentencia SQL para mostrar Registros
        $objeto->consultaGeneral($entidad, $where, $ord, $dir, $inicio, $limite);
		 
		$responce = new StdClass;
		$responce->page = $pag;
    	$responce->total = $total_pag;
    	$responce->records = $cantidad;
    	$i = 0;
			
			while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
				
				$prefijo = substr(md5(uniqid(rand())),0,5);
				
				if(file_exists("../files/".$row['trabCedula'].".jpg")){
					$foto = "<a href='files/".$row['trabCedula'].".jpg' target='_blank'><img src='files/".$row['trabCedula'].".jpg?".$prefijo."'  width='60' height='80' /></a>";
				}else{
					$foto ="";
				}
				
				$nombre= explode(' ',$row['trabNombre']);
				$apellido= explode(' ',$row['trabApellido']);
				$nombres =$nombre[0].' '.$apellido[0];
				
				$fechanac = $objeto->convertirFecha($row['trabFechanac']);
				$fechaingreso = $objeto->convertirFecha($row['trabFechaingreso']);
				$fechaegreso = $objeto->convertirFecha($row['trabFechaegreso']);
				$fecharegistro = $objeto->convertirFecha($row['trabFecharegistro']);

				$icono = "<a class='btnConsultar' name='".$row['trabCedula']."'><img src='imagenes/icoAsignar.png'  title='Consultar' alt='ver' style='border:none; cursor:pointer;' /></a>";
				
				
				$responce->rows[$i]['id']=$row['trabCedula'];
				$responce->rows[$i]['cell']=array(
						$row['trabCodigo'],
						$row['trabCedula'],
						$nombres,
						$row['trabNombre'],
						$row['trabApellido'],
						$row['trabSexo'],
						$row['trabEdocivil'],
						$fechanac,
						$row['trabNivel'],
						$row['trabProfesion'],
						$row['trabDireccion'],
						$row['trabTelefono'],
						$row['trabCorreo'],								
						$fechaingreso,
						$row['trabOrganismoId'],
						$row['organismoDescripcion'],
						$row['trabDepartmentoId'],
						$row['departamentoDescripcion'],
						$row['trabCargo'],
						$row['trabSueldo'],
						$row['trabEstatus'],
						$fechaegreso,
						$row['trabObservacion'],
						$foto,
						$fecharegistro,
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
