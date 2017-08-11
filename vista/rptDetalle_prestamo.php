<?php
    session_start();
    require('../controlador/rptDetalle_prestamo.php');
    include_once("../modelo/detalle_prestamo.class.php");
    $objeto = new detalle_prestamo;   
	
	
	
	$prestamoid = $_GET['prestamoid'];

	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    	
	//$res = $objDetalle->consultar($codigo);
	//$where=" WHERE cajahorroEstatus = 'Procesado' ";
	//$grupo=" GROUP BY cajahorroId ";
	//$ord=" ORDER BY cajahorroId ";
	
	$res = $objeto->consultar($prestamoid);
	$nfilasPrest = mysql_num_rows($objeto->resultado);
    if($nfilasPrest > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $dataPrest[]=array_merge($row);
			$cedula = $row['prestamoTrabCedula'];
        }
    }else{
        $dataPrest[]='';
    }
	
		
    //////////////////////////////////////////
	if($nfilasPrest!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		$pdf->alto=7; 
		$pdf->fuente=10; //Propiedades: alto de la celda - Tamaño de la letra
		
		
		$pdf->mostrar_trabajador($cedula);
		
		
		$pdf->mostrar_detalle();
		$total = 0;
		$nro= 1;
				
		////////////////////////////////////////////////////////
		foreach($dataPrest as $row){
						
			//Mostrando Registros por columnas
			$alto="8"; $fontsize="10"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$fechaP = substr($row['prestamoFecha'],8,2)."/".substr($row['prestamoFecha'],5,2)."/".substr($row['prestamoFecha'],0,4);
			$fechaE = substr($row['prestamoEstatus'],8,2)."/".substr($row['prestamoEstatus'],5,2)."/".substr($row['prestamoEstatus'],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,'width' => '7', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['prestamoId'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $fechaP,   'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($row['tipoprestNombre']),    'width' => '70', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $row['prestamoEstatus'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['prestamoCuota'], 2, ',', '.').' '.$row['prestamoTipodesc'],   'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['prestamoMonto'], 2, ',', '.'),   'width' => '25', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$total += $row['prestamoMonto'];
			$nro++;
        }
				
		$pdf->mostrar_total($total); 
				
		
        //$pdf->Output('prestamos'.$cedula.'.pdf','D');
		$pdf->Output();
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