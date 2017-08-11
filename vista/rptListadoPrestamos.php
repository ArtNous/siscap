<?php
    session_start();
    require('../controlador/rptListadoPrestamos.php');
    include_once("../modelo/prestamos.class.php");
    $objeto = new prestamos;
    
	$ordenar = $_GET['ordenar'];
	
	$cedula = $_GET['cedula'];
	$tipoid = $_GET['tipoid'];
	$orgaid = $_GET['orgaid'];
	$depaid = $_GET['depaid'];
	$desde	= ($_GET['desde']=='')?'':substr($_GET['desde'],6,4)."-".substr($_GET['desde'],3,2)."-".substr($_GET['desde'],0,2);
	$hasta 	= ($_GET['hasta']=='')?'':substr($_GET['hasta'],6,4)."-".substr($_GET['hasta'],3,2)."-".substr($_GET['hasta'],0,2);
	
	$tipodesc = explode("-", str_replace("PC","%",$_GET['tipodesc']) );
	$estatus = explode("-",$_GET['estatus']);
		
	$where=" WHERE 1=1 ";
		$where.= ($cedula=='')?"":" AND trabCedula = '".$cedula."' ";
		$where.= ($tipoid=='')?"":" AND prestamoTipoprestId = '".$tipoid."' ";
		$where.= ($orgaid=='')?"":" AND trabOrganismoId = '".$orgaid."' ";
		$where.= ($depaid=='')?"":" AND trabDepartmentoId = '".$depaid."' ";
		$where.= ($desde=='' || $hasta=='')?"":" AND prestamoFecha BETWEEN '".$desde."' AND '".$hasta."' ";	
		
		$where.= ($_GET['tipodesc']=='')?"":" AND prestamoTipodesc IN('".$tipodesc[0]."','".$tipodesc[1]."')  ";
		$where.= ($_GET['estatus']=='')?"":" AND prestamoEstatus IN('".$estatus[0]."','".$estatus[1]."') ";

	if($ordenar=="")
		$ord = " ORDER BY prestamos.prestamoId ";
	if($ordenar!="")
		$ord = " ORDER BY ".$ordenar.",trabCedula";
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    $res = $objeto->consultarPrestamos($where,$ord);
    $nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$tipoprestamo = $row['tipoprestNombre'];
			$organismo = $row['organismoDescripcion'];
			$departamento = $row['departamentoDescripcion'];

			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$trabnombre =$nombre[0].' '.$apellido[0];			
			
        }
    }else{
        $data[]='';
    }
	
	//Titulo de la Consulta (Filtros)
	$titulo="[Consulta]: ";
		
	$titulo .= ($cedula=='')?"":" ".$cedula."-".$trabnombre." /";	
	$titulo .= ($tipoid=='')?"":" ".$tipoprestamo." /";	
	$titulo .= ($orgaid=='')?"":" ".$organismo." /";	
	$titulo .= ($depaid=='')?"":" ".$departamento." /";	
	$titulo .= ($_GET['estatus']=='')?"":" ".strtoupper($_GET['estatus'])." /";	
	$titulo .= ($desde=='' || $hasta=='')?"":" DESDE ".$_GET['desde']." HASTA ".$_GET['hasta']." /";	
		
	
	$_POST['titulo']=$titulo;	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($nfilas>0){
        $pdf=new PDF_MC_Table($orientation='L',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
			
		$agrupar = "";
		$nro= 1;
		
		$subtotalMonto=0;
		$subtotalSaldo=0;
		
		$totalMonto=0;
		$totalSaldo=0;
		
		
		
		if($ordenar=="")
			$pdf->mostrarEncabezado();
		
		////////////////////////////////////////////////////////
		foreach($data as $row){
			
			//Agrupar Registros
			if($ordenar!=""){
				if($agrupar=="" || $agrupar!=$row[$ordenar]){
					if($agrupar!=""){
						$pdf->mostrarSubtotal($subtotalMonto,$subtotalSaldo);
					}
							
					$agrupar= $row[$ordenar];
					
					$pdf->mostrarGrupo($agrupar);
					$pdf->mostrarEncabezado();
					$nro= 1;
					$subtotalMonto=0;
					$subtotalSaldo=0;
				}
			}
			
			//Mostrando Registros por columnas
			$alto="6"; $fuente="8"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$nombres =$nombre[0].' '.$apellido[0];
			
			$fecha = substr($row['prestamoFecha'],8,2)."-".substr($row['prestamoFecha'],5,2)."-".substr($row['prestamoFecha'],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,   'width' => '10', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['prestamoId'],   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['trabCedula'], 0, ',', '.'),   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($nombres),    'width' => '43', 'height' => $alto, 'align' => 'L', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $fecha, 'width' => '22', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => utf8_decode($row['tipoprestNombre']),   'width' => '48', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => '9', 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			
            $col[] = array('text' => number_format($row['prestamoMonto'], 2, ',', '.'),   'width' => '22', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => number_format($row['prestamoCuota'], 2, ',', '.'),   'width' => '20', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $row['prestamoTipodesc'],   'width' => '7', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['prestamoEstatus'], 'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['saldo'], 2, ',', '.'),   'width' => '22', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$nro+=1;
			
			$subtotalMonto+=$row['prestamoMonto'];
			$subtotalSaldo+=$row['saldo'];
			
			$totalMonto+=$row['prestamoMonto'];
			$totalSaldo+=$row['saldo'];
        }
		
		if($ordenar!=""){
			$pdf->mostrarSubtotal($subtotalMonto,$subtotalSaldo);
		}
		
		$pdf->SetX(12);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(163,6,utf8_decode('TOTAL '),1,0,'R');
		$pdf->Cell(22,6,utf8_decode(number_format($totalMonto, 2, ',', '.')),1,0,'R');
		$pdf->Cell(69,6,utf8_decode(number_format($totalSaldo, 2, ',', '.')),1,0,'R');
	
		
        //$pdf->Output('ListadoPrestamo.pdf','D');
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