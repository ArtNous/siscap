<?php
    session_start();
    require('../controlador/rptEstadoCuenta.php');
    include_once("../modelo/consultas.class.php");
    $objeto = new consultas;
    
	$cedula = $_GET['cedula'];

	
	///////////////////////////////////////////////////////////////////////////////////////// Consulta SQL Consultar Trabajador
	$res = $objeto->consultarTrabajador($cedula);
	$nfilas = mysql_num_rows($objeto->resultado);
	while($row = mysql_fetch_array($objeto->resultado)){
		$_POST['cedula']=$cedula;
		$_POST['nombres']=$row['trabNombres'];
		$_POST['organismo']=$row['organismoDescripcion'];
		$_POST['codigo']=$row['trabCodigo'];
		$_POST['telefono']=$row['trabTelefono'];
		$_POST['fechai']=substr($row['trabFechaingreso'],8,2).'/'.substr($row['trabFechaingreso'],5,2).'/'.substr($row['trabFechaingreso'],0,4);
		$_POST['fechae']=($row['trabFechaegreso']=='')?'':substr($row['trabFechaegreso'],8,2).'/'.substr($row['trabFechaegreso'],5,2).'/'.substr($row['trabFechaegreso'],0,4);
	}

	///////////////////////////////////////////////////////////////////////////////// Consulta SQL Consultar Ahorros (Columna 0)		
		$res = $objeto->consultarAhorros($cedula);
		$nfilasAhorros = mysql_num_rows($objeto->resultado);
		 while($row = mysql_fetch_array( $objeto->resultado)){
			$dataAhorros[]=array_merge($row);
		}
				
	///////////////////////////////////////////////////////////////////////////////// Consulta SQL Consultar Tipo Prestamos	para los Titulos
		$consulta = "(SELECT prestamoTipoprestId FROM prestamos
					  WHERE prestamos.prestamoTrabCedula = '".$cedula."' AND prestamos.prestamoEstatus <> 'Anulado'
					  GROUP BY prestamoTipoprestId) AS consulta";
		$cantidad = $objeto->cantidadReg($consulta);
		
		
		$i=1;
		$res = $objeto->consultarTipoPrestamoTrab($cedula);
		$nfilasTipo = mysql_num_rows($objeto->result1);
		while($row = mysql_fetch_array($objeto->result1)){
			//if($i<=4 || $i <= $cantidad)
			if($i <= $cantidad)
				$_POST['titulo'.$i] = $row['tipoprestNombre'];
			$i++;
		}
		

	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($nfilas!=0){  /** Solo si el Trabajador existe **/
        $pdf=new PDF_MC_Table($orientation='L',$unit='mm',$format='legal');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
		
		$y=55; $alto="5"; $fuente="8"; //Propiedades:  posicion de Y, alto de la celda - Tamaño de la letra
		$x=4;
		
		
			$i=1;
			$res = $objeto->consultarTipoPrestamoTrab($cedula);
			while($row1 = mysql_fetch_array($objeto->result1)){
				
				if($i==1)
					$x+=71;
				else
					$x+=68;
				$tp = $row1['tipoprestId'];
				
				if($i<=4){ 
					$saldo=0;
					$pdf->SetY($y);
					$res = $objeto->consultarPrestamos($cedula,$tp);
					while($row = mysql_fetch_array( $objeto->result2)){
					
							$fecha = substr($row['fecha'],8,2)."-".substr($row['fecha'],5,2)."-".substr($row['fecha'],0,4);
							$saldo+=$row['debe']-$row['haber'];
							
							$columns = array(); 
							$col = array();
							$col[] = array('text' => $fecha, 'width' => '18', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['debe'], 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['haber'], 2, ',', '.'),   'width' => '16', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($saldo, 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$columns[] = $col;  
							$pdf->SetX($x);
							$pdf->WriteTable($columns); 
					}
				}		
				
				$i++;
			}
			
		
		//////////////////////////////////////////////////////// Mostrar AHORROS	(Columna 0)		
		if($nfilasAhorros > 0){
			$saldo=0;
			$pdf->SetY($y);
			//for($i=1;$i<=10;$i++){
				foreach($dataAhorros as $row){
					$fecha = substr($row['fecha'],8,2)."-".substr($row['fecha'],5,2)."-".substr($row['fecha'],0,4);	
					$saldo+=$row['debe']-$row['haber'];
					
					$columns = array(); 
					$col = array();
					$col[] = array('text' => $fecha, 'width' => '18', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
					$col[] = array('text' => number_format($row['debe'], 2, ',', '.'),   'width' => '16', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
					$col[] = array('text' => number_format($row['haber'], 2, ',', '.'),   'width' => '16', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
					$col[] = array('text' => number_format($saldo, 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
					$columns[] = $col;  
					$pdf->SetX(8);
					$pdf->WriteTable($columns); 
				}
			//}
		}
		
		
		if($cantidad>4){
				
			$pdf->AddPageNew();
			$pdf->header2(5,6,7,8,9);
			
			$x=-60;
			
			$i=1;
			$res = $objeto->consultarTipoPrestamoTrab($cedula);
			while($row1 = mysql_fetch_array($objeto->result1)){
				
				if($i>4 && $i<=9){ 
					$saldo=0;
					$x+=71;
					$pdf->SetY($y);

					$tp = $row1['tipoprestId'];
					
					$res = $objeto->consultarPrestamos($cedula,$tp);
					while($row = mysql_fetch_array( $objeto->result2)){
					
							$fecha = substr($row['fecha'],8,2)."-".substr($row['fecha'],5,2)."-".substr($row['fecha'],0,4);
							$saldo+=$row['debe']-$row['haber'];
							
							$columns = array(); 
							$col = array();
							$col[] = array('text' => $fecha, 'width' => '18', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '8.5', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['debe'], 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['haber'], 2, ',', '.'),   'width' => '16', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($saldo, 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$columns[] = $col;  
							$pdf->SetX($x);
							$pdf->WriteTable($columns); 
					}
				}		
				
				$i++;
			}
		}
		
			
		if($cantidad>9){
				
			$pdf->AddPageNew();
			$pdf->header2(10,11,12,13,14);
			
			$x=-60;
			
			$i=1;
			$res = $objeto->consultarTipoPrestamoTrab($cedula);
			while($row1 = mysql_fetch_array($objeto->result1)){
				
				if($i>9 && $i<=14){ 
					$saldo=0;
					$pdf->SetY($y);
					$x+=68;
					$tp = $row1['tipoprestId'];
					$res = $objeto->consultarPrestamos($cedula,$tp);
					while($row = mysql_fetch_array( $objeto->result2)){
					
							$fecha = substr($row['fecha'],8,2)."-".substr($row['fecha'],5,2)."-".substr($row['fecha'],0,4);
							$saldo+=$row['debe']-$row['haber'];
							
							$columns = array(); 
							$col = array();
							$col[] = array('text' => $fecha, 'width' => '18', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '8.5', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['debe'], 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($row['haber'], 2, ',', '.'),   'width' => '16', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$col[] = array('text' => number_format($saldo, 2, ',', '.'),   'width' => '17', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
							$columns[] = $col;  
							$pdf->SetX($x);
							$pdf->WriteTable($columns); 
					}
				}		
				
				$i++;
			}
			
		}
		
	
        //$pdf->Output('ListadoTrabajadores.pdf','D');
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