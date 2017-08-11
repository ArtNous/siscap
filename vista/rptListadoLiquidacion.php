<?php
    session_start();
    require('../controlador/rptListadoLiquidacion.php');
    include_once("../modelo/detalle_liquidacion.class.php");
    $objeto = new detalle_liquidacion;
    
	$ordenar = $_GET['ordenar'];
	
	$cedula = $_GET['cedula'];
	$tipoid = $_GET['tipoid'];
	$orgaid = $_GET['orgaid'];
	$depaid = $_GET['depaid'];
	$desde	= ($_GET['desde']=='')?'':substr($_GET['desde'],6,4)."-".substr($_GET['desde'],3,2)."-".substr($_GET['desde'],0,2);
	$hasta 	= ($_GET['hasta']=='')?'':substr($_GET['hasta'],6,4)."-".substr($_GET['hasta'],3,2)."-".substr($_GET['hasta'],0,2);
	
	$estatus = $_GET['estatus'];
		
	$where=" WHERE 1=1 ";
		$where.= ($cedula=='')?"":" AND trabCedula = '".$cedula."' ";
		$where.= ($tipoid=='')?"":" AND prestamoTipoprestId = '".$tipoid."' ";
		$where.= ($orgaid=='')?"":" AND trabOrganismoId = '".$orgaid."' ";
		$where.= ($depaid=='')?"":" AND trabDepartmentoId = '".$depaid."' ";
		$where.= ($desde=='' || $hasta=='')?"":" AND detliqFecha BETWEEN '".$desde."' AND '".$hasta."' ";	
		
		//$where.= ($_GET['estatus']=='')?"":" AND prestamoEstatus IN('".$estatus[0]."','".$estatus[1]."') ";
		$where.= ($_GET['estatus']=='')?" AND prestamoEstatus!='Anulado' ":" AND prestamoEstatus='".$estatus."' ";
		
		
	
	if($ordenar=="")
		$ord = " ORDER BY detliqFecha ";
	if($ordenar!="")
		$ord = " ORDER BY ".$ordenar.",detliqFecha";
	
	if($ordenar=="detliqLiquidacionCodigo")
		$where .= " AND detliqLiquidacionCodigo!='' ";
		
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    $res = $objeto->consultar($where,$ord);
    $nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$tipoprestamo = $row['tipoprestNombre'];
			$organismo = $row['organismoDescripcion'];
			$departamento = $row['departamentoDescripcion'];
			$trabnombre = $row['trabNombres'];
			
        }
    }else{
        $data[]='';
    }
	
	//Titulo de la Consulta (Filtros)
	$titulo="[Consulta]:";
		
	$titulo .= ($cedula=='')?"":" ".$cedula."-".$trabnombre." /";	
	$titulo .= ($tipoid=='')?"":" ".$tipoprestamo." /";	
	$titulo .= ($orgaid=='')?"":" ".$organismo." /";	
	$titulo .= ($depaid=='')?"":" ".$departamento." /";	
	$titulo .= ($estatus=='')?"":" ".strtoupper($estatus)." /";	
	$titulo .= ($desde=='' || $hasta=='')?"":" DESDE ".$_GET['desde']." HASTA ".$_GET['hasta']." /";	
		
	
	$_POST['titulo']=$titulo;	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($nfilas!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
			
		$agrupar = "";
		$nro= 1;
		
		$subtotalMonto=0;
		$totalMonto=0;
	
		
		
		////////////////////////////////////////////////////////
		foreach($data as $row){
			
			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$nombres =$nombre[0].' '.$apellido[0];
			
			//Agrupar Registros
			if($ordenar!=""){
				if($agrupar=="" || $agrupar!=$row[$ordenar]){
					if($agrupar!=""){
						$pdf->mostrarSubtotal($subtotalMonto);
					}
							
					$agrupar= $row[$ordenar];
					$titulo="Cierre Periodo:";
					
					if($ordenar=="prestamoId"){
						$datos = " - ".$row['tipoprestNombre']." [ ".$row['prestamoMonto']." ]";
						$titulo="Préstamo:";
					}
					
					if($ordenar=="trabCedula"){
						$datos = " - ".$nombres." [ ".$row['organismoDescripcion']." ]";
						$titulo="Trabajador:";
					}
					
					$pdf->mostrarGrupo($agrupar."".$datos,$titulo);
					//$pdf->mostrarEncabezado();
					$nro= 1;
					$subtotalMonto=0;
				}
			}
			
			//Mostrando Registros por columnas
			$alto="4"; $fuente="7"; //Propiedades: alto de la celda - Tamaño de la letra
			$fecha = substr($row['detliqFecha'],8,2)."-".substr($row['detliqFecha'],5,2)."-".substr($row['detliqFecha'],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,   'width' => '10', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['prestamoId'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['trabCedula'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($nombres),    'width' => '50', 'height' => $alto, 'align' => 'L', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => number_format($row['detliqSueldo'], 2, ',', '.'),   'width' => '20', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['prestamoCuota'], 2, ',', '.')." ".$row['prestamoTipodesc'],   'width' => '23', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $fecha, 'width' => '24', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['detliqMonto'], 2, ',', '.'),   'width' => '23', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');			
			$columns[] = $col;  
            $pdf->SetX(13);
            $pdf->WriteTable($columns); 
			
			$nro+=1;
			
			
			$subtotalMonto+=$row['detliqMonto'];
			$totalMonto+=$row['detliqMonto'];
        }
		
		if($ordenar!=""){
			$pdf->mostrarSubtotal($subtotalMonto,$subtotalSaldo);
		}
		
		$pdf->SetX(13);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(167,6,utf8_decode('TOTAL '),1,0,'R');
		$pdf->Cell(23,6,utf8_decode(number_format($totalMonto, 2, ',', '.')),1,0,'R');

		
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