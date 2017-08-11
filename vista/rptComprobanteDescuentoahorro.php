<?php
    session_start();
    require('../controlador/ajustar_rptComprobanteDescuentoahorro.php');
    $objeto = new funciones;   
	
	
	$id = $_GET['id'];
	
	///////////////////////////////////////////////////////////////////////////////////////// Ejecutar Funcion de la Consulta SQL
	$campos="descuento_ahorro.descahorroId,
			trabajador.trabCodigo AS codigo,
			descuento_ahorro.descahorroTrabCedula AS cedula,
			CONCAT(trabajador.trabNombre,' ',trabajador.trabApellido) as nombre,
			organismo.organismoDescripcion AS organismo,
			departamento.departamentoDescripcion AS departamento,
			trabajador.trabCargo AS cargo,
			descuento_ahorro.descahorroConcepto AS concepto,
			descuento_ahorro.descahorroFecha AS fecha,
			descuento_ahorro.descahorroMonto AS monto,
			descuento_ahorro.descahorroEstatus AS estatus,
			descuento_ahorro.descahorroFechaestatus,
			descuento_ahorro.descahorroTipo,
			concat(usuarios.usuNombre,' ',usuarios.usuApellido) as usuario ";
	
	$entidad="descuento_ahorro
				LEFT JOIN trabajador ON descuento_ahorro.descahorroTrabCedula = trabajador.trabCedula
				LEFT JOIN organismo ON trabajador.trabOrganismoId = organismo.organismoId
				LEFT JOIN departamento ON trabajador.trabDepartmentoId = departamento.departamentoId
				LEFT JOIN usuarios ON descuento_ahorro.descahorroUsuCedula = usuarios.usuCedula ";
	
	$where=" WHERE descuento_ahorro.descahorroId = '$id'";
	$res = $objeto->consultaGeneral($entidad,$where,"descahorroTrabCedula",'ASC','','',$campos);
	$nfilas = mysql_num_rows($objeto->resultado);
    if($nfilas > 0){
         while($row = mysql_fetch_array( $objeto->resultado)){
            $data[]=array_merge($row);
			$tipo = $row['descahorroTipo'];
        }
    }else{
        $data[]='';
    }
	
	$_POST['tipo']=$tipo;		
    //////////////////////////////////////////
	if($nfilas!=0){
        $pdf=new PDF_MC_Table($orientation='P',$unit='mm',$format='letter');
        $pdf->AddPage();
        $pdf->AliasNbPages(); 
        
		$pdf->alto=7; 
		$pdf->fuente=10; //Propiedades: alto de la celda - Tamaño de la letra
		
		
		////////////////////////////////////////////////////////
		foreach($data as $row){
			
			$cedula = number_format($row['cedula'], 0, ',', '.');
			$nombre = strtoupper($row['nombre']);
			$organismo = $row['organismo'];
			$departamento = $row['departamento'];
			$cargo = $row['cargo'];
			$fecha = $objeto->convertirFecha($row['fecha']);	
			$monto = number_format($row['monto'], 2, ',', '.');
			$concepto = $row['concepto'];
			$usuario = strtoupper($row['usuario']);
			
			$montoletra = $pdf->convertirNumLetras($row['monto']);
			
			$tipotexto = ($_POST['tipo']=='Descuento')?'recibido de':'abonado a';
			
			$textHTML0='         ';
			$textHTML0.='YO, [b]'.$nombre.'[/b], titular de la Cédula de Identidad [b]Nº '.$cedula.'[/b], ';
			$textHTML0.='adscrito al departamento de: [b]'.$departamento.'[/b] de [b]'.$organismo.'[/b], ';
			$textHTML0.=' como [b]'.$cargo.'[/b], por medio de la presente hago [b]CONSTAR[/b] que he '.$tipotexto.' la [b]CAJA DE AHORRO[/b] ';
			$textHTML0.=' un monto de: [b]'.strtoupper($montoletra).'[/b] (BS. '.$monto.'), solicitud realizada a la fecha [b]'.$fecha.'[/b], por concepto de: [b]'.$concepto.'[/b]. ' ;
			$pdf->JLCell(utf8_decode("$textHTML0"),170,'j');	
				
			
			$pdf->SetFont('Arial','B',12);		 

			$pdf->Ln(10);
			$pdf->SetX(11);
			 
			$mesletra = $pdf->convertirMesLetras(date(m));
			$textHTML = '[b][i]Constancia que se expide a petición de parte interesada en Trujillo, ';
			$textHTML.= 'a los '.date(d).' días del mes de '.$mesletra.' del año '.date(Y).'. [/i][/b]';
			$pdf->JLCell(utf8_decode("$textHTML"),170,'j');
			

			$pdf->SetFont('Courier','B',12);		 
		
			$pdf->Ln(25);
			$pdf->SetX(28);
			$pdf->SetFont('Arial','B',12); $pdf->SetFillColor(255,255,255);
			$pdf->Cell(90,6,html_entity_decode('Procesado por:'),0,0,'L',1);
			$pdf->Cell(90,6,html_entity_decode('Solicitado por:'),0,0,'L',1);
		   
			$pdf->Ln(23);
			$pdf->SetX(15);
			$pdf->SetFont('Arial','B',12); $pdf->SetFillColor(255,255,255);
			$pdf->Cell(90,6,html_entity_decode($usuario),0,0,'C',1);
			$pdf->Cell(90,6,html_entity_decode($nombre),0,0,'C',1);
			$pdf->Ln(6);
			$pdf->SetX(15);
			
			$pdf->Cell(90,6,html_entity_decode('OPERADOR DE CATSET'),0,0,'C',1);
			$pdf->Cell(90,6,html_entity_decode('ASOCIADO DE CATSET'),0,0,'C',1);
				
        }
				
		
        //$pdf->Output('DetallePrestamos'.$cedula.'.pdf','D');
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