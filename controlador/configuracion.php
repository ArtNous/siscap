<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuId'];
$tipo_usuario = $_SESSION['usuTipo'];

include ("../modelo/configuracion.class.php");
$objeto = new configuracion;

//Parametros
	$entidad = 'configuracion';
	
if (isset($_POST['ahorro']))
	$ahorro=$_POST['ahorro'];

if (isset($_POST['aporte']))
	$aporte=$_POST['aporte'];	

if (isset($_POST['meses']))
	$meses=$_POST['meses'];	

if (isset($_POST['porcentaje']))
	$porcentaje=$_POST['porcentaje'];

if (isset($_POST['liqprestipo']))
	$liqprestipo=$_POST['liqprestipo'];
	
	//**********Paginador	
	$oper = isset($_POST['oper'])?$_POST['oper']:''; // get the requested page	
	if (isset($_GET['oper'])) // get oper a ejecutar
		$oper   = $_GET['oper'];
	if (isset($_GET['page']))
		$pag    = $_GET['page'];  // get the requested page
	if (isset($_GET['rows']))			
		$limite = $_GET['rows'];  // get how many rows we want to have into the grid
	$ord=1;
	if (isset($_GET['sidx']))	
		$ord    = $_GET['sidx']; // get index row - i.e. user click to sort
	if (isset($_GET['sord']))			
		$dir    = $_GET['sord'];  // get the direction Acs o Desc
//***Fin de Parametros

if(isset($_GET['oper']))
	$oper=$_GET['oper'];

switch ($oper) {

    case 'actualizar':
		$objeto->actualizar($ahorro,$aporte,$meses,$porcentaje,$liqprestipo);

        break;
	
	case 'consultar': //carga de datos para un combo   
        $resultado = $objeto->consultaGeneral($entidad,'',2,'ASC',0,1);
		while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $responce[] = array(
                'ahorro' => $row['configCajahorro'],
                'aporte' => $row['configAportepatronal'],
                'meses' => $row['configMeses'],
                'intereses' => $row['configIntereses'],
                'liqprestipo' => $row['configLiqprestipo']
            );
        }
		
		if (count($responce) == 0)
			$responce[] = array( 'ahorro' => '', 'aporte' => '', 'meses' => '',	'intereses' => '', 'liqprestipo' => 0 );
		
		echo json_encode($responce);
		
        break;
		
	 case 'carga_select': //carga de datos para un combo   
        	
        $resultado = $objeto->consultaGeneral($entidad,'',2,'ASC',0,1);
		while ($row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC)) {
            $meses = $row['configMeses'];
        }
		
        $combo = " <select id='cmbMeses' name='cmbMeses' > ";
		$combo .= " <option value='' > </option> ";
			for($i=1;$i<=$meses;$i++)
				$combo.= " <option value='".$i."'>".$i."</option> ";
        $combo .= " </select> ";
        echo $combo;

        break;
}
//***** Fin Oper
?>