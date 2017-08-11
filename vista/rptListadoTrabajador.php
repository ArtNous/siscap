<?php
    session_start();
    require('../controlador/rptListadoTrabajador.php');
    include_once("../modelo/trabajador.class.php");
    $objeto = new trabajador;
        
	
	$orgaid = $_GET['orgaid'];
	$depaid = $_GET['depaid'];
	$estatus = $_GET['estatus'];
	
	
	$ingresodesde	= ($_GET['ingresodesde']=='')?'':substr($_GET['ingresodesde'],6,4)."-".substr($_GET['ingresodesde'],3,2)."-".substr($_GET['ingresodesde'],0,2);
	$ingresohasta 	= ($_GET['ingresohasta']=='')?'':substr($_GET['ingresohasta'],6,4)."-".substr($_GET['ingresohasta'],3,2)."-".substr($_GET['ingresohasta'],0,2);
	$egresodesde 	= ($_GET['egresodesde']=='')?'':substr($_GET['egresodesde'],6,4)."-".substr($_GET['egresodesde'],3,2)."-".substr($_GET['egresodesde'],0,2);
	$egresohasta 	= ($_GET['egresohasta']=='')?'':substr($_GET['egresohasta'],6,4)."-".substr($_GET['egresohasta'],3,2)."-".substr($_GET['egresohasta'],0,2);
	
	
	
	$where=" WHERE 1=1 ";
		$where.= ($orgaid=='')?"":" AND trabOrganismoId = '".$orgaid."' ";
		$where.= ($depaid=='')?"":" AND trabDepartmentoId = '".$depaid."' ";
		$where.= ($estatus=='')?"":" AND trabEstatus = '".$estatus."' ";
		
		if($ingresodesde!="" && $ingresohasta!=""){
			$where.=" AND trabFechaingreso BETWEEN '".$ingresodesde."' AND '".$ingresohasta."' ";
			$mostrarfechaingreso= "/ Fecha Ingreso: desde ".$_GET['ingresodesde']." hasta ".$_GET['ingresohasta']."    ";
		}
		
		if($egresodesde!="" && $egresohasta!=""){
			$where.=" AND trabFechaegreso BETWEEN '".$egresodesde."' AND '".$egresohasta."' ";
			$mostrarfechaegreso.= " / Fecha Egreso: desde".$_GET['egresodesde']." hasta ".$_GET['egresohasta']." ";
		}
		
	$ord = " ORDER BY organismoDescripcion, CAST(trabCedula AS DECIMAL) ";
	
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
    $res = $objeto->consultar($where,$ord);
    $nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$instituto = $row['organismoDescripcion'];
        }
    }else{
        $data[]='';
    }
	
	$titulo="[Consulta]: ".$instituto." ";
	$titulo.= (isset($mostrarfechaingreso))?$mostrarfechaingreso:'';
	$titulo.= (isset($mostrarfechaegreso))?$mostrarfechaegreso:'';
	$_POST['titulo']=$titulo;
	
    //////////////////////////////////////////
    if($nfilas!=0){
        $pdf=new PDF_MC_Table($orientation='L',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
		
		$nro= 1;
		////////////////////////////////////////////////////////
		foreach($data as $row){
		
			//Mostrando Registros por columnas
			$alto="6"; //Propiedades: alto de la celda - Tamaño de la letra
			
			$nombre= explode(' ',$row['trabNombre']);
			$apellido= explode(' ',$row['trabApellido']);
			$nombres =$nombre[0].' '.$apellido[0];
			
			$fechai = substr($row['trabFechaingreso'],8,2)."-".substr($row['trabFechaingreso'],5,2)."-".substr($row['trabFechaingreso'],0,4);
			$fechae = substr($row['trabFechaegreso'],8,2)."-".substr($row['trabFechaegreso'],5,2)."-".substr($row['trabFechaegreso'],0,4);
			
            $columns = array(); 
            $col = array();
            $col[] = array('text' => $nro,   'width' => '10', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['trabCedula'], 0, ',', '.'),   'width' => '20', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => utf8_decode($nombres),    'width' => '40', 'height' => $alto, 'align' => 'L', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $fechai, 'width' => '22', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $row['departamentoDescripcion'],   'width' => '60', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => $row['trabCargo'],   'width' => '60', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$col[] = array('text' => number_format($row['trabSueldo'], 2, ',', '.'),   'width' => '20', 'height' => $alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
            $col[] = array('text' => $fechae, 'width' => '22', 'height' => $alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $fuente, 'font_style' => '', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
			$columns[] = $col;  
            $pdf->SetX(12);
            $pdf->WriteTable($columns); 
			
			$nro = $nro+1;
        }
		
		$pdf->SetX(80);      
		$pdf->SetFont('Arial','',9); $pdf->SetFillColor(255,0,0);
		$pdf->Cell(185,6,utf8_decode("[Total Trabajador]: ".$nfilas),0,0,'R');
		$pdf->Ln(3);
		
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