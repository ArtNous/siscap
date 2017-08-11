<?php
    session_start();
    require('../controlador/ajustar_rptComprobanteCajahorro.php');
    include_once("../modelo/caja_ahorro.class.php");
    $objCaja = new caja_ahorro;
    
	include_once("../modelo/detalle_ahorro.class.php");
    $objDetalle = new detalle_ahorro;
    
	$codigo = $_GET['codigo'];
	$_POST['mes']= substr($codigo,5,2);
	$_POST['ano']= substr($codigo,0,4);
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    $res = $objCaja->consultar($codigo);
    $nfilasCaja = mysql_num_rows($objCaja->resultado);
    if($nfilasCaja > 0){
         while($row = mysql_fetch_array( $objCaja->resultado)){
            $dataCaja[]=array_merge($row);
        }
    }else{
        $dataCaja[]='';
    }
	
	
	$res = $objDetalle->consultar($codigo);
    $nfilasDetalle = mysql_num_rows($objDetalle->resultado);
    if($nfilasDetalle > 0){
         while($row = mysql_fetch_array( $objDetalle->resultado)){
            $dataDetalle[]=array_merge($row);
        }
    }else{
        $dataDetalle[]='';
    }
		
    //////////////////////////////////////////
    if($nfilasCaja!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		foreach($dataCaja as $row){
			$fuente="9"; 

			$fechaD = substr($row['cajahorroDesde'],8,2)."-".substr($row['cajahorroDesde'],5,2)."-".substr($row['cajahorroDesde'],0,4);
			$fechaH = substr($row['cajahorroHasta'],8,2)."-".substr($row['cajahorroHasta'],5,2)."-".substr($row['cajahorroHasta'],0,4);
			$fechaE = substr($row['cajahorroFechaestatus'],8,2)."-".substr($row['cajahorroFechaestatus'],5,2)."-".substr($row['cajahorroFechaestatus'],0,4);
			
			$pdf->SetLineWidth(0.2); $pdf->SetDrawColor('DC', 'DC', 'DC');
			
			$pdf->SetX(13);  
			$pdf->SetFillColor(31, 43, 119);  $pdf->SetLineWidth(0.1); $pdf->RoundedRect(11,39, 195, 6, 1, 'DF');
			$pdf->SetTextColor(255,255,255);  $pdf->SetFont('Arial','B',$fuente); 
			$pdf->Cell(190,6,utf8_decode('Información General '),0,0,'L');			
			
			$pdf->RoundedRect(11,39, 195, 18, 1, '');
			
			$pdf->Ln(7);
			$pdf->SetX(13);   
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','B',$fuente); 
			$pdf->Cell(18,5,utf8_decode('Código:'),0,0,'L');
			$pdf->SetFont('Arial','B',$fuente);  $pdf->SetTextColor(255,0,0);
			$pdf->Cell(70,5,utf8_decode($row['cajahorroId']),0,0,'L');
			
			$pdf->SetFont('Arial','B',$fuente); $pdf->SetTextColor(0,0,0);
			$pdf->Cell(20,5,utf8_decode('Caja Ahorro:'),0,0,'L');
			$pdf->SetFont('Arial','',$fuente); 
			$pdf->Cell(90,5,utf8_decode(' en base al '.$row['cajahorroPorcentaje'].' % del sueldo mensual '),0,0,'L');
			
	
			$pdf->Ln(5);
			$pdf->SetX(13);  
			
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','B',$fuente); 
			$pdf->Cell(18,5,utf8_decode('Periodo: '),0,0,'L');
			$pdf->SetFont('Arial','',$fuente); 
			$pdf->Cell(70,5,utf8_decode($fechaD.' al '.$fechaH),0,0,'L');
			
			$pdf->SetFont('Arial','B',$fuente); 
			$pdf->Cell(32,5,utf8_decode('N° de Trabajadores:'),0,0,'L');
			$pdf->SetFont('Arial','',$fuente); $pdf->SetTextColor(0,0,0);
			$pdf->Cell(25,5,utf8_decode($row['cajahorroCantidad']),0,0,'L');
			
			$pdf->SetFont('Arial','B',$fuente);   $pdf->SetTextColor(0,0,0);
			$pdf->Cell(17,5,utf8_decode('Total Bs.: '),0,0,'L');
			$pdf->SetFont('Arial','B',$fuente);  $pdf->SetTextColor(255,0,0);
			$pdf->Cell(22,5,utf8_decode(number_format($row['cajahorroTotal'], 2, ',', '.')),0,0,'R');
			
			$pdf->Ln(10);
			$pdf->SetX(13); 
			
			$pdf->SetFillColor(31, 43, 119);  $pdf->SetLineWidth(0.1); $pdf->RoundedRect(11,61, 195, 6, 1, 'DF');
			$pdf->SetTextColor(255,255,255);  $pdf->SetFont('Arial','B',$fuente); 
			$pdf->Cell(190,6,utf8_decode('Información Detallada por Organismo '),0,0,'L');	
			
					
			$pdf->Ln(3);
			
			
        }
		
		
		$grupo = "";
	
		
	
		////////////////////////////////////////////////////////
		foreach($dataDetalle as $row){
			//Agrupar por Organismo
			
			if($grupo=="" || $grupo!=$row['organismoDescripcion']){
				
				$pdf->alto=6; 
				$pdf->fuente=9; //Propiedades: alto de la celda - Tamaño de la letra
				
				if($grupo!=""){
					$pdf->mostrar_subtotal($sueldo,$monto); 
				}
				
				$grupo= $row['organismoDescripcion'];			
				$pdf->mostrar_grupo($grupo); 
				
				$sueldo = 0;
				$monto = 0;
				$nro= 1;
			}
			
			
			
			//Mostrando Registros por columnas
			$alto="5"; $fuente="8"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$nombres =$nombre[0].' '.$apellido[0];
			$fecha = substr($row['trabFecha'.$tipo],8,2)."-".substr($row['trabFecha'.$tipo],5,2)."-".substr($row['trabFecha'.$tipo],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,'width' => '8', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => 8, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			//$col[] = array('text' => $row['trabCodigo'],   'width' => '15', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['trabCedula'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($nombres),    'width' => '49', 'height' => $alto, 'align' => 'L', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $row['trabCargo'],   'width' => '60', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['trabSueldo'], 2, ',', '.'),   'width' => '30', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['detahorroMonto'], 2, ',', '.'),   'width' => '27', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$sueldo += $row['trabSueldo'];
			$monto += $row['detahorroMonto'];
			$nro++;
        }
				if($grupo!=""){
					$pdf->mostrar_subtotal($sueldo,$monto); 
				}
		
        //$pdf->Output('cajaahorro'.$codigo.'.pdf','D');
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