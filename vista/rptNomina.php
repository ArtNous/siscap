<?php
    session_start();
    require('../controlador/rptNomina.php');
    include_once("../modelo/trabajador.class.php");
    $objeto = new trabajador;
    
	include_once("../modelo/tipo_prestamo.class.php");
    $objetoTipo = new tipo_prestamo;
	
	$desde	= ($_GET['desde']=='')?'':substr($_GET['desde'],6,4)."-".substr($_GET['desde'],3,2)."-".substr($_GET['desde'],0,2);
	$hasta 	= ($_GET['hasta']=='')?'':substr($_GET['hasta'],6,4)."-".substr($_GET['hasta'],3,2)."-".substr($_GET['hasta'],0,2);
	$orgaid = $_GET['orgaid'];	
	$cargo = $_GET['cargo'];
		

	///////////////////////////////////////////////////////////////////////////////////////// Consulta SQL Tipo de Prestmos
		$res = $objetoTipo->consultarTipoPrestamos();
		$nfilasTipo = mysql_num_rows($objetoTipo->resultado);
		if($nfilasTipo > 0){
			 while($row = mysql_fetch_array( $objetoTipo->resultado)){
				$dataTipo[]=array_merge($row);
			}
		}else{
			$dataTipo[]='';
		}
		$ancho=215/($nfilasTipo);
	
	
	///////////////////////////////////////////////////////////////////////////////////////// Consulta SQL Trabajador
    $where=" WHERE trabEstatus = 'activo' ";	
	$where.= ($orgaid=='')?"":" AND trabOrganismoId = '".$orgaid."' ";	
	$where.= ($cargo=='')?"":" AND trabCargo = '".$cargo."' ";
	$where.= ($hasta=='')?"":" AND trabFechaingreso <= '".$hasta."' ";
	
	$ord = "ORDER BY CAST(trabCodigo AS DECIMAL) ASC ";
	
	$res = $objeto->consultar($where,$ord);
    $nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$organismo = $row['organismoDescripcion'];
			$departamento = $row['departamentoDescripcion'];
        }
    }else{
        $data[]='';
    }
	
	
	
	$titulo="[Consulta]: ";
	$titulo .= ($orgaid=='' && $cargo=='')?"GENERAL /":"";
	$titulo .= ($orgaid=='')?"":$organismo." / ";
	//$titulo .= ($depaid=='')?"":$departamento." / ";
	$titulo .= ($cargo=='')?"":$cargo." / ";
	$titulo .= "DESDE ".$_GET['desde']." HASTA ".$_GET['hasta']."    ";
	$_POST['titulo']=$titulo;
	
	
    //////////////////////////////////////////
    if($nfilas!=0){
        $pdf=new PDF_MC_Table($orientation='L',$unit='mm',$format='legal');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		
		$nro= 1;
		$total=0;
			
		////////////////////////////////////////////////////////
		foreach($data as $row){
			//Mostrando Registros por columnas
			$alto="5"; $fuente="9"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$cedula= $row['trabCedula'];
			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$nombres =$nombre[0].' '.$apellido[0];
			
            $columns = array(); 
            $col = array();
            //$col[] = array('text' => $nro,   'width' => '9', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '8', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['trabCodigo'],   'width' => '16', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '8', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($cedula, 0, ',', '.') ,   'width' => '18', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '8', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($nombres),    'width' => '41', 'height' => $alto, 'align' => 'L', 'font_name' => 'Arial', 'font_size' => '8', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			
			$subtotal =0;
			
			$res = $objeto->consultarAhorros($cedula,$desde,$hasta);
			while($row = mysql_fetch_array( $objeto->resultado)){
				$monto = ($row['monto']=='')?'0':$row['monto'];
				$subtotal += $monto;
			}
			$col[] = array('text' =>  number_format($monto, 2, ',', '.') ,   'width' => '19', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
		   
		   
		   ///////////////////////////////////////////////////////////////////////////////////////// Consulta SQL Tipo de Prestmos
			
			$res = $objetoTipo->consultarNominaPrestamos($cedula,$desde,$hasta);
			while($row = mysql_fetch_array($objetoTipo->resultado)){
				$monto = ($row['monto']=='')?'0':$row['monto'];
				$col[] = array('text' =>  number_format($monto, 2, ',', '.') ,   'width' => $ancho, 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$subtotal += $monto;
			}
			$col[] = array('text' =>number_format($subtotal, 2, ',', '.'),    'width' => '22', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '150', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$total+=$subtotal;
			$nro = $nro+1;
			
        }
		
		$pdf->mostrarTotal($total); 
		
        //$pdf->Output('Nomina.pdf','D');
		$pdf->Output();
   }else{
?>
<!--  ************Libreria jQuery*************  -->
<link rel="stylesheet" type="text/css" media="screen" href="../estilo/estilo.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../libreria/jquery/css/jquery-ui.css" />
<script src="../libreria/jquery/js/jquery.min.js" type="text/javascript"></script>
<script src="../libreria/plugins/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
        jQuery(document).ready(function(){
            $.blockUI({
                theme:     true, 
                title:    'Notificaci&oacute;n',
                message: "El reporte que Ud. ha seleccionado no se puede generar, NO existe Informaci&oacute;n sobre la consulta... ",
                timeout:   4500 
            }); 
			setTimeout("window.close()",5000);  
        }); 
</script>
<?php } ?>