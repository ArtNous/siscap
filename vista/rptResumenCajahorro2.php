<?php
    session_start();
    require('../controlador/ajustar_rptResumenCajahorro.php');
    include_once("../modelo/caja_ahorro.class.php");
    $objCaja = new caja_ahorro;
    
	$codigo = $_GET['codigo'];
	$_POST['mes']= substr($codigo,5,2);
	$_POST['ano']= substr($codigo,0,4);
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    	
	$where=" WHERE cajahorroEstatus = 'Procesado' ";
	$ord=" ORDER BY cajahorroId ";
	
	$res = $objCaja->detalleResumen($where,$ord);
	$nfilasCaja = mysql_num_rows($objCaja->resultado);
    if($nfilasCaja > 0){
         while($row = mysql_fetch_array( $objCaja->resultado)){
            $dataCaja[]=array_merge($row);
        }
    }else{
        $dataCaja[]='';
    }
		
    //////////////////////////////////////////
	if($nfilasCaja!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		$pdf->alto=7; 
		$pdf->fuente=10; //Propiedades: alto de la celda - Tamaño de la letra

		$pdf->mostrar_detalle();
		$monto = 0;
		$nro= 1;
				
		////////////////////////////////////////////////////////
		foreach($dataCaja as $row){
						
			//Mostrando Registros por columnas
			$alto="8"; $fontsize="10"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$fechaD = substr($row['cajahorroDesde'.$tipo],8,2)."-".substr($row['cajahorroDesde'.$tipo],5,2)."-".substr($row['cajahorroDesde'.$tipo],0,4);
			$fechaH = substr($row['cajahorroHasta'.$tipo],8,2)."-".substr($row['cajahorroHasta'.$tipo],5,2)."-".substr($row['cajahorroHasta'.$tipo],0,4);
			$fechaE = substr($row['cajahorroFechaestatus'.$tipo],8,2)."-".substr($row['cajahorroFechaestatus'.$tipo],5,2)."-".substr($row['cajahorroFechaestatus'.$tipo],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,'width' => '7', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['cajahorroId'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $fechaD,   'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $fechaH,    'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $fechaE,   'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['cajahorroCantidad'],   'width' => '30', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['cajahorroPorcentaje'].' %',   'width' => '25', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['cajahorroMonto'], 2, ',', '.'),   'width' => '36', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');			
            $columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$monto += $row['cajahorroMonto'];
			$nro++;
        }

		$pdf->mostrar_total($monto); 

        //$pdf->Output('ResumenCajaahorro.pdf','D');
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