<?php
    
require('../libreria/fpdf/fpdf.php');
include_once("../include/funciones.class.php");
include_once("../modelo/detalle_prestamo.class.php");
    

class PDF_MC_Table extends FPDF{
    var $widths;
    var $aligns;
	var $alto;
	var $fuente;
    //Cabecera de p�gina
    function Header(){
        $objeto = new funciones;
        $fecha     = $objeto->ObtenerFecha();
        $this->SetFillColor(255,255,255);
        $this->Image('../imagenes/logo.png',13,10,60,25);
        
        
		$this->SetXY(13,15); 
        $this->SetFont('Arial','I',9);$this->SetFillColor(255,255,255);
        $this->Cell(190,3,'Impreso: '.substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4),0,0,'R');
		
		$this->SetXY(76,28);      
		$this->SetFont('Arial','BI',16);$this->SetFillColor(255,255,255);
        $this->Cell(130,7,utf8_decode('DETALLE DE PRÉSTAMOS'),0,0,'C');
       		
        $this->Ln(17);
		
        ///// posicion en x de la primera linea de la sig pag.
        $this->SetX(15);         
        
    }
	
	function mostrar_trabajador($cedula){
			$objeto = new trabajador;  
			$accion = $objeto->consultar($cedula);
			$row = mysql_fetch_array($objeto->resultado, MYSQL_ASSOC);
			
			$this->SetX(13); 
			$this->SetFillColor(31, 43, 119);  $this->SetLineWidth(0.1); $this->RoundedRect(11,45, 195, 6, 1, 'DF');
			$this->SetTextColor(255,255,255);  $this->SetFont('Arial','B',10); 
			$this->Cell(190,6,utf8_decode('Información del Trabajador'),0,0,'L');	
			$this->Ln(8);
			
			$this->SetFillColor(255, 255, 255); $this->SetLineWidth(0.1); $this->RoundedRect(11,52, 194, 26, 1, 'DF');
			
			
			$this->SetX(13); 
			$this->SetTextColor(0,0,0);  
			$this->SetFont('Arial','',10); 				
			$this->Cell(15,6,utf8_decode("Cédula: "),0,0,'L');
			$this->SetTextColor(255,0,0);
			$this->Cell(25,6,utf8_decode($row['trabCedula']),0,0,'L');
			$this->SetTextColor(0,0,0);	
			$this->Cell(75,6,utf8_decode("Nombre: ".$row['trabNombres']),0,0,'L');		
			$this->Cell(50,6,utf8_decode("Código: ".$row['trabCodigo']),0,0,'L');		
			$this->Image('../files/'.$cedula.'.jpg',185,54,17,22);
			
			$this->Ln(6);
			$this->SetX(13); 
			$this->Cell(115,6,utf8_decode("Organismo: ".$row['organismoDescripcion']),0,0,'L');	
			$fecha = ($row['trabFechaingreso']=='')?'':substr($row['trabFechaingreso'],8,2)."/".substr($row['trabFechaingreso'],5,2)."/".substr($row['trabFechaingreso'],0,4);			
			$this->Cell(50,6,utf8_decode("Fecha Ingreso: ".$fecha),0,0,'L');		
			
			$this->Ln(6);
			$this->SetX(13); 
			$this->Cell(115,6,utf8_decode("Departamento: ".$row['departamentoDescripcion']),0,0,'L');		
			$fecha = ($row['trabFechaegreso']=='')?'':substr($row['trabFechaegreso'],8,2)."/".substr($row['trabFechaegreso'],5,2)."/".substr($row['trabFechaegreso'],0,4);			
			$this->Cell(50,6,utf8_decode("Fecha Egreso: ".$fecha),0,0,'L');		
			
			$this->Ln(6);
			$this->SetX(13); 
			$this->Cell(115,6,utf8_decode("Cargo: ".$row['trabCargo']),0,0,'L');		
			$this->Cell(50,6,utf8_decode("Sueldo: ".$row['trabSueldo']),0,0,'L');		
			//$this->Cell(30,6,utf8_decode($row['trabEstatus']),1,0,'C');		
			
			$this->Ln(12);
			
	}
	
	function mostrar_detalle(){
			
			$this->SetX(13); 
			$this->SetFillColor(31, 43, 119);  $this->SetLineWidth(0.1); $this->RoundedRect(11,83, 195, 6, 1, 'DF');
			$this->SetTextColor(255,255,255);  $this->SetFont('Arial','B',10); 
			$this->Cell(190,6,utf8_decode('Préstamos Tramitados'),0,0,'L');	
			$this->Ln(8);
			
				$columns = array(); 
				$col = array();				
				$col[] = array('text' => utf8_decode('Nº'),   'width' => '7', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');				
				$col[] = array('text' => utf8_decode('Código'),   'width' => '20', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$col[] = array('text' => utf8_decode('Fecha'),    'width' => '25', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$col[] = array('text' => utf8_decode('Tipo de Préstamo'),   'width' => '70', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$col[] = array('text' => utf8_decode('Estatus'),   'width' => '20', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$col[] = array('text' => utf8_decode('Descuento'),   'width' => '25', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$col[] = array('text' => utf8_decode('Monto'),   'width' => '25', 'height' => $this->alto, 'align' => 'C', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
				$columns[] = $col;  
				$this->SetX(12);
				$this->WriteTable($columns); 
			$this->Ln(1);
	}
	
	function mostrar_total($total){
					$columns = array(); 
					$col = array();
					$col[] = array('text' => utf8_decode('TOTAL'),   'width' => '167', 'height' => $this->alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');					
					$col[] = array('text' => utf8_decode(number_format($total, 2, ',', '.')),   'width' => '25', 'height' => $this->alto, 'align' => 'R', 'font_name' => 'Arial', 'font_size' => $this->fuente, 'font_style' => 'B', 'fillcolor' => '255,255,255', 'textcolor' => '0,0,0', 'drawcolor' => '0,0,0', 'linewidth' => '0.05', 'linearea' => 'LTBR');
					$columns[] = $col;  
					$this->SetX(12);
					$this->WriteTable($columns); 					
	}
	
    //Pie de p�gina
    function Footer(){
        //PosiciÃ³n: a 1,5 cm del final
       $this->SetXY(12,-9);
       $this->SetFont('Arial','I','9');
       $this->Cell(190,3,'INSCRITO EN LA SUPERINTENDENCIA DE CAJAS DE AHORRO (SUDECA)',0,0,'L');
       $this->SetXY(12,-13);
       $this->SetFont('Arial','I','9');
       $this->Cell(190,3,'SEDE DE FUNDA SALUD - LA MORITA - TRUJILLO',0,0,'L');
       $this->SetFont('Arial','','10');
       $this->SetXY(12,-9);
       $this->Cell(190,3,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');

    }
    function Rotate($angle,$x=-1,$y=-1){
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0){
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }
    function _endpage(){
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
    function RotatedText($x, $y, $txt, $angle){
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }
    function Circle($x, $y, $r, $style='D'){
        $this->Ellipse($x,$y,$r,$r,$style);
    }
    function Ellipse($x, $y, $rx, $ry, $style='D'){
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,($h-$y)*$k,
            ($x+$rx)*$k,($h-($y-$ly))*$k,
            ($x+$lx)*$k,($h-($y-$ry))*$k,
            $x*$k,($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,($h-($y-$ry))*$k,
            ($x-$rx)*$k,($h-($y-$ly))*$k,
            ($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,($h-($y+$ly))*$k,
            ($x-$lx)*$k,($h-($y+$ry))*$k,
            $x*$k,($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x+$lx)*$k,($h-($y+$ry))*$k,
            ($x+$rx)*$k,($h-($y+$ly))*$k,
            ($x+$rx)*$k,($h-$y)*$k,
            $op));
    }
    function RoundedRect($x, $y, $w, $h, $r, $style = ''){
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    function SetWidths($w){
        //Ajuste el ancho de las columnas de la matriz
        $this->widths=$w;
    }
    function SetAligns($a){
        //Establecer la matriz columna de las alineaciones
        $this->aligns=$a;
    }
    // Create Table
   function WriteTable($tcolums){
      // go through all colums
      for ($i = 0; $i < sizeof($tcolums); $i++){
         $current_col = $tcolums[$i];
         $height = 0;
         // get max height of current col
         $nb=0;
         for($b = 0; $b < sizeof($current_col); $b++){
            // set style
            $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
            $color = explode(",", $current_col[$b]['fillcolor']);
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $color = explode(",", $current_col[$b]['textcolor']);
            $this->SetTextColor($color[0], $color[1], $color[2]);            
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            $this->SetLineWidth($current_col[$b]['linewidth']);          
            $nb = max($nb, $this->NbLines($current_col[$b]['width'], $current_col[$b]['text']));            
            $height = $current_col[$b]['height'];
         }  
         //$h=$height*$nb;
         $h=$height*$nb;
         // Issue a page break first if needed
         $this->CheckPageBreak($h);
         // Draw the cells of the row
         for($b = 0; $b < sizeof($current_col); $b++){
            $w = $current_col[$b]['width'];
            $a = $current_col[$b]['align'];
            // Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            // set style
            $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
            $color = explode(",", $current_col[$b]['fillcolor']);
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $color = explode(",", $current_col[$b]['textcolor']);
            $this->SetTextColor($color[0], $color[1], $color[2]);            
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            $this->SetLineWidth($current_col[$b]['linewidth']);
            $color = explode(",", $current_col[$b]['fillcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            // Draw Cell Background
            $this->Rect($x, $y, $w, $h, 'FD');
            $color = explode(",", $current_col[$b]['drawcolor']);            
            $this->SetDrawColor($color[0], $color[1], $color[2]);
            // Draw Cell Border
            if (substr_count($current_col[$b]['linearea'], "T") > 0){
               $this->Line($x, $y, $x+$w, $y);
            }            
            if (substr_count($current_col[$b]['linearea'], "B") > 0){
               $this->Line($x, $y+$h, $x+$w, $y+$h);
            }            
            if (substr_count($current_col[$b]['linearea'], "L") > 0){
               $this->Line($x, $y, $x, $y+$h);
            }           
            if (substr_count($current_col[$b]['linearea'], "R") > 0){
               $this->Line($x+$w, $y, $x+$w, $y+$h);
            }  
            // Print the text
            $this->MultiCell($w, $current_col[$b]['height'], $current_col[$b]['text'], 0, $a, 0);
            // Put the position to the right of the cell
            $this->SetXY($x+$w, $y);         
         }         
         // Go to the next line
         $this->Ln($h);          
      }                  
   }
    function Row($data){
        //Calcular la altura de la fila
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=4*$nb;
        //Emitir un salto de página si se necesita
        $this->CheckPageBreak($h);
        //Dibuje las células de la fila-
        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Guardar la posición actual
            $x=$this->GetX();
            $y=$this->GetY();
            //dibujar borde
            $this->Rect($x,$y,$w,$h);
            //imprimir el texto
            $this->MultiCell($w,3,$data[$i],0,$a);
            //Ponga la posición a la derecha de la celda
            $this->SetXY($x+$w,$y);
        }
        //Ir a la siguiente línea
        $this->Ln($h);
    }
    function Row1($data){
        //Calcular la altura de la fila
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=12;
        //Emitir un salto de página si se necesita
        $this->CheckPageBreak($h);
        //Dibuje las células de la fila-
        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Guardar la posición actual
            $x=$this->GetX();
            $y=$this->GetY();
            //dibujar borde
            $this->Rect($x,$y,$w,$h);
            //imprimir el texto
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Ponga la posición a la derecha de la celda
            $this->SetXY($x+$w,$y);
        }
        //Ir a la siguiente línea
        $this->Ln(12);
    }
    function CheckPageBreak($h){
        //Si la altura h provocara un desbordamiento, añadir una nueva página de inmediato
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }
    function NbLines($w,$txt){
        //Calcula el número de líneas de un MultiCell de ancho w tendrá
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb){
            $c=$s[$i];
            if($c=="\n"){
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax){
                if($sep==-1){
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}
?>