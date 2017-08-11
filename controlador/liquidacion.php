<?php
	if (!isset($_SESSION)) session_start();
	$usuario = $_SESSION['usuId'];
	
//Incluir las Clases y Objetos
include ("../modelo/liquidacion.class.php");
$objeto = new liquidacion;

include ("../modelo/prestamos.class.php");
$objeto_prestamos = new prestamos;

//Permite limpiar los post enviados de otras paginas
foreach($_POST as $key => $value) {
    $_POST[$key] = str_replace("'","",$value);
    $_POST[$key] = str_replace('"','',$value);
}
 
		
//*******Parametros
$entidad = 'liquidacion';

if(isset($_POST['id']))
	$id = $_POST['id'];

if(isset($_POST['codigo']))
	$codigo = $_POST['codigo'];

if(isset($_POST['codigo']))
	$codmes	= substr($_POST['codigo'],0,7);

if(isset($_POST['ano']))
	$ano = $_POST['ano'];

if(isset($_POST['mes']))	
	$mes = $_POST['mes'];

if(isset($_POST['codigo']))
	$quincena  = substr($_POST['codigo'],9,1);

if(isset($_POST['desde']))
	$desde 	= substr($_POST['desde'],6,4)."-".substr($_POST['desde'],3,2)."-".substr($_POST['desde'],0,2);

if(isset($_POST['hasta']))
	$hasta 	= substr($_POST['hasta'],6,4)."-".substr($_POST['hasta'],3,2)."-".substr($_POST['hasta'],0,2);

if(isset($_POST['estatus']))
	$estatus 	= $_POST['estatus'];

if(isset($_POST['fechaE']))
	$fechaE		= substr($_POST['fechaE'],6,4)."-".substr($_POST['fechaE'],3,2)."-".substr($_POST['fechaE'],0,2);
			
//**********Paginador
$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page
$pag = isset($_GET['page'])?$_GET['page']:'';  // get the requested page
$limite = isset($_GET['rows'])?$_GET['rows']:'';  // get how many rows we want to have into the grid
$ord = isset($_GET['sidx'])?$_GET['sidx']:1; // get index row - i.e. user click to sort
$dir = isset($_GET['sord'])?$_GET['sord']:'';  // get the direction Acs o Desc	
//***Fin de Parametros
		
///******* Obtener variables necesarias	
$fecha = $objeto->ObtenerFecha();	
		
	switch ($oper) {
	
		case 'add':
			//Comprobar que exista al menos un registro generado en la base de datos
			$cantidad = $objeto->cantidadReg($entidad);
			
			if($cantidad>0){
				
				//Validar que no existan Cierres Pendientes
				$existePendiente = $objeto->cantidadReg($entidad, " WHERE liquidacionEstatus = 'Pendiente' ");
				if($existePendiente!=0){
					echo "No se puede generar!! Existe un cierre \"Pendiente\" por procesar...";	
					exit;
				}
				
				//Validar que no existan Cierres con el mismo codigo
				$existe = $objeto->cantidadReg($entidad, " WHERE liquidacionCodigo = '".$codmes."' ");
				if($existe!=0){
					echo "Ya existe un cierre generado del mes ".$mes." a&ntilde;o ".$ano." ";	
					exit;
				}
				
				//Validar que la Quincena No exista generada
				$existe = $objeto->cantidadReg($entidad, " WHERE liquidacionCodigo = '".$codigo."' ");
				if($existe!=0){
					echo "Ya existe generada la Quincena ".$quincena." del mes ".$mes." a&ntilde;o ".$ano." ";	
					exit;
				}
				
				//Validar que slo permita generar proximo mes
				$objeto->consultaGeneral($entidad, "", 'liquidacionCodigo', 'DESC', 0, 1);
				$row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC);
				$ultimoAno = (int)substr($row['liquidacionCodigo'],0,4);
				$ultimoMes = (int)substr($row['liquidacionCodigo'],5,2);
				$ultimaQ   = substr($row['liquidacionCodigo'],8,2);
				
				if($ultimaQ=="Q1" ){
					$proxCierre = $ultimoAno.'-'.substr($row['liquidacionCodigo'],5,2).'-Q2';
				}else{
					
					if($ultimoMes<9){
						$proxCierre = $ultimoAno.'-0'.($ultimoMes+1);
					}else{
						if($ultimoMes<12){					
							$proxCierre = $ultimoAno.'-'.($ultimoMes+1);
						}else if($ultimoMes==12){
							$proxCierre = ($ultimoAno+1).'-01';
						}								
					}
					
					$proxCierre.=($quincena!='')?'-Q1':'';
				}

				if ($codigo != $proxCierre) {
					echo " El p&oacute;ximo Cierre pendiente por generar es: ".$proxCierre;	
					exit;
				} 
				
				
			}
			
			$objeto->agregar($codigo,$quincena,$desde,$hasta,$estatus,$fecha,$usuario); 
			break;


		case 'del':
			$objeto->eliminar($id);
			$objeto_prestamos->actualizarPrestamos('',$id); // parametros (IdPrestamo, CodigoCierre) 
			break;
		
		
		case 'procesar_cierre':
			$objeto->procesar_cierre($id,$fecha);
			$objeto_prestamos->actualizarPrestamos('',$id); // parametros (IdPrestamo, CodigoCierre) 
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
			
				$entidad .=" LEFT JOIN detalle_liquidacion ON detalle_liquidacion.detliqLiquidacionCodigo = liquidacion.liquidacionCodigo ";
				
				$campos = " liquidacion.*, sum(detalle_liquidacion.detliqMonto) as total ";
				$grupo	= " GROUP BY liquidacion.liquidacionCodigo ";

				///******* Obtener Datos Paginador
				$cantidad  = $objeto->cantidadReg($entidad,$where);
				$total_pag = $objeto->totalPagina($cantidad,$limite);
				$inicio    = $objeto->inicioPagina($pag,$total_pag,$limite);
				///******* Fin Obtener Datos Paginador	

			///******* Sentencia SQL para mostrar Registros
				//echo $where;
				$objeto->consultaGeneral($entidad,$where,$ord,$dir,$inicio,$limite,$campos,$grupo);
				
				$responce = new StdClass;
				$responce->page    = $pag;
				$responce->total   = $total_pag;
				$responce->records = $cantidad;
				$i=0;
				
				while($row = mysql_fetch_array($objeto->resultado,MYSQL_ASSOC)) {
						
						$fechaD = substr($row['liquidacionDesde'],8,2)."-".substr($row['liquidacionDesde'],5,2)."-".substr($row['liquidacionDesde'],0,4);
						$fechaH = substr($row['liquidacionHasta'],8,2)."-".substr($row['liquidacionHasta'],5,2)."-".substr($row['liquidacionHasta'],0,4);
						$fechaE = substr($row['liquidacionFechaestatus'],8,2)."-".substr($row['liquidacionFechaestatus'],5,2)."-".substr($row['liquidacionFechaestatus'],0,4);
						$icono = "<a class='consultar' name='".$row['liquidacionCodigo']."'><img src='imagenes/icoCarpeta-b.png' style='width:28px; border:none; height:25px; cursor:pointer;' title='Ver detalle' alt='Detalle' /></a>";
						
						$responce->rows[$i]['id']=$row['liquidacionCodigo'];
						$responce->rows[$i]['cell']=array(
													$row['liquidacionCodigo'],
													substr($row['liquidacionCodigo'],0,4),
													substr($row['liquidacionCodigo'],5,2),
                                                    $fechaD,
													$fechaH,
													$row['liquidacionEstatus'],
													$fechaE,
													$row['total'],
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
