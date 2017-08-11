<?php
    session_start();
    require('../controlador/rptResumenCajahorro.php');
    include_once("../modelo/caja_ahorro.class.php");
    $objeto = new caja_ahorro;
    
	
	$desde	= ($_GET['desde']=='')?'':substr($_GET['desde'],6,4)."-".substr($_GET['desde'],3,2)."-".substr($_GET['desde'],0,2);
	$hasta 	= ($_GET['hasta']=='')?'':substr($_GET['hasta'],6,4)."-".substr($_GET['hasta'],3,2)."-".substr($_GET['hasta'],0,2);
	$estatus = $_GET['estatus'];
	
	$where.= ($_GET['estatus']=='')?" AND cajahorroEstatus!='Anulado' ":" AND cajahorroEstatus='".$estatus."' ";
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    
	$ord=" ORDER BY cajahorroId ";
	
	$res = $objeto->consultarResumen($desde,$hasta,$where,$ord);
	$nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$saldoA = $row['saldoA'];
			$saldoD = $row['saldoD'];
        }
    }else{
        $data[]='';
    }
	
	if($saldoA=="" || $saldoA==null)
		$saldoA=0;
	
	if($saldoD=="" || $saldoD==null)
		$saldoD=0;
	
	
	//Titulo de la Consulta (Filtros)
	$titulo="[Consulta]:";
	$titulo .= ($estatus=='')?"":" ".strtoupper($estatus)." /";	
	$titulo .= ($desde=='' || $hasta=='')?"":" DESDE ".$_GET['desde']." HASTA ".$_GET['hasta']." /";	
	$_POST['titulo']=$titulo;	
	
    //////////////////////////////////////////
	if($nfilas!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		$pdf->alto=7; 
		$pdf->fuente=10; //Propiedades: alto de la celda - Tamaño de la letra

		$pdf->mostrar_detalle();
		
		$pdf->SetX(12);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(157,6,utf8_decode('AHORROS ACUMULADOS ANTES DEL '.$_GET['desde']),1,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(36,6,utf8_decode(number_format($saldoA, 2, ',', '.')),1,0,'R');
		$pdf->Ln(7);
		
		$monto = 0;
		$nro= 1;
				
		////////////////////////////////////////////////////////
		foreach($data as $row){
						
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
		
		$pdf->mostrarSubtotal($monto); 
		
		$pdf->SetX(12);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(157,6,utf8_decode('AHORROS ACUMULADOS DESPUES DEL '.$_GET['hasta']),1,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(36,6,utf8_decode(number_format($saldoD, 2, ',', '.')),1,0,'R');
		$pdf->Ln(7);
		
		$total= $saldoA+$monto+$saldoD;
		$pdf->mostrarTotal($total); 
		

        //$pdf->Output('resumen-cajaahorro.pdf','D');
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