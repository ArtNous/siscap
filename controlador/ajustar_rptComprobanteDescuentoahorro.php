<?php
    
require('../libreria/fpdf/fpdf.php');
include_once("../include/funciones.class.php");

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
        
        $tipo = ($_POST['tipo']=='Descuento')?'DESCUENTO':'REINTEGRO';
		
		$this->SetXY(70,23);      
		$this->SetFont('Arial','BI',14);$this->SetFillColor(255,255,255);
        $this->Cell(130,5,'COMPROBANTE POR '.$tipo,0,0,'C');
		$this->SetXY(70,30);   
		$this->SetFont('Arial','BI',12);$this->SetTextColor(255,0,0);		
		$this->Cell(130,5,'DE LA CAJA AHORRO',0,0,'C');
       
		
        $this->Ln(20);
		
        ///// posicion en x de la primera linea de la sig pag.
        $this->SetX(15);         
        
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
    
	
	function convertirMesLetras($mes){
	if($mes==1)
		$xmes='Enero';
	if($mes==2)
		$xmes='Febrero';
	if($mes==3)
		$xmes='Marzo';
	if($mes==4)
		$xmes='Abril';
	if($mes==5)
		$xmes='Mayo';
	if($mes==6)
		$xmes='Junio';
	if($mes==7)
		$xmes='Julio';
	if($mes==8)
		$xmes='Agosto';
	if($mes==9)
		$xmes='Septiembre';
	if($mes==10)
		$xmes='Octubre';
	if($mes==11)
		$xmes='Noviembre';
	if($mes==12)
		$xmes='Diciembre';
	
	return $xmes;
}

function convertirNumLetras($num, $fem = false, $dec = true) { 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 
   
   //Zi hack
   $float=explode('.',$num);
   $num=$float[0];

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   //Zi hack --> return ucfirst($tex);
   
   if($dec==true)
		$end_num=ucfirst($tex).' BOLÍVARES CON '.$float[1].'/100 CÉNTIMOS';
   
   if($dec==false)
		$end_num=ucfirst($tex);
   
   return $end_num; 
} 
 
 
    function Rotate($angle, $x = -1, $y = -1) {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle*=M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage() {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    function RotatedText($x, $y, $txt, $angle) {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Circle($x, $y, $r, $style = 'D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    function Ellipse($x, $y, $rx, $ry, $style = 'D') {
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c', ($x + $rx) * $k, ($h - $y) * $k, ($x + $rx) * $k, ($h - ($y - $ly)) * $k, ($x + $lx) * $k, ($h - ($y - $ry)) * $k, $x * $k, ($h - ($y - $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', ($x - $lx) * $k, ($h - ($y - $ry)) * $k, ($x - $rx) * $k, ($h - ($y - $ly)) * $k, ($x - $rx) * $k, ($h - $y) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', ($x - $rx) * $k, ($h - ($y + $ly)) * $k, ($x - $lx) * $k, ($h - ($y + $ry)) * $k, $x * $k, ($h - ($y + $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s', ($x + $lx) * $k, ($h - ($y + $ry)) * $k, ($x + $rx) * $k, ($h - ($y + $ly)) * $k, ($x + $rx) * $k, ($h - $y) * $k, $op));
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));

        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function SetWidths($w) {
        //Ajuste el ancho de las columnas de la matriz
        $this->widths = $w;
    }

    function SetAligns($a) {
        //Establecer la matriz columna de las alineaciones
        $this->aligns = $a;
    }

    // Create Table
    function WriteTable($tcolums) {
        // go through all colums
        for ($i = 0; $i < sizeof($tcolums); $i++) {
            $current_col = $tcolums[$i];
            $height = 0;
            // get max height of current col
            $nb = 0;
            for ($b = 0; $b < sizeof($current_col); $b++) {
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
            $h = $height * $nb;
            // Issue a page break first if needed
            $this->CheckPageBreak($h);
            // Draw the cells of the row
            for ($b = 0; $b < sizeof($current_col); $b++) {
                $w = $current_col[$b]['width'];
                $a = $current_col[$b]['align'];
                // Save the current position
                $x = $this->GetX();
                $y = $this->GetY();
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
                if (substr_count($current_col[$b]['linearea'], "T") > 0) {
                    $this->Line($x, $y, $x + $w, $y);
                }
                if (substr_count($current_col[$b]['linearea'], "B") > 0) {
                    $this->Line($x, $y + $h, $x + $w, $y + $h);
                }
                if (substr_count($current_col[$b]['linearea'], "L") > 0) {
                    $this->Line($x, $y, $x, $y + $h);
                }
                if (substr_count($current_col[$b]['linearea'], "R") > 0) {
                    $this->Line($x + $w, $y, $x + $w, $y + $h);
                }
                // Print the text
                $this->MultiCell($w, $current_col[$b]['height'], $current_col[$b]['text'], 0, $a, 0);
                // Put the position to the right of the cell
                $this->SetXY($x + $w, $y);
            }
            // Go to the next line
            $this->Ln($h);
        }
    }

    function Row($data) {
        //Calcular la altura de la fila
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 4 * $nb;
        //Emitir un salto de página si se necesita
        $this->CheckPageBreak($h);
        //Dibuje las células de la fila-
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Guardar la posición actual
            $x = $this->GetX();
            $y = $this->GetY();
            //dibujar borde
            $this->Rect($x, $y, $w, $h);
            //imprimir el texto
            $this->MultiCell($w, 3, $data[$i], 0, $a);
            //Ponga la posición a la derecha de la celda
            $this->SetXY($x + $w, $y);
        }
        //Ir a la siguiente línea
        $this->Ln($h);
    }

    function Row1($data) {
        //Calcular la altura de la fila
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 12;
        //Emitir un salto de página si se necesita
        $this->CheckPageBreak($h);
        //Dibuje las células de la fila-
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Guardar la posición actual
            $x = $this->GetX();
            $y = $this->GetY();
            //dibujar borde
            $this->Rect($x, $y, $w, $h);
            //imprimir el texto
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Ponga la posición a la derecha de la celda
            $this->SetXY($x + $w, $y);
        }
        //Ir a la siguiente línea
        $this->Ln(12);
    }

    function CheckPageBreak($h) {
        //Si la altura h provocara un desbordamiento, añadir una nueva página de inmediato
        if ($this->GetY() + $h > $this->PageBreakTrigger){
            $this->AddPage($this->CurOrientation);
            $this->SetX($this->margenIzq);
        }
    }

    function NbLines($w, $txt) {
        //Calcula el número de líneas de un MultiCell de ancho w tendrá
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    //----------------------- comandos disponibles ----------------------
    var $comm = array("[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]", "[blue]", "[red]", "[black]", "[green]", "[arial]", "[courier]", "[times]", "[helvetica]", "[BIG]", "[small]", "[normal]");
    var $Style = '';
    var $Color = array('r' => 0, 'g' => 0, 'b' => 0);
    var $PosX = 0; //<- para mantener el margen izquierdo
    var $FontSizeInicial = 12;
    var $align = 'j';

    //------- Cambiar los estilos de la fuente (negritas, it?lica y subrayado)
    function Estilo($caracter, $valor) {
        $this->Style = str_replace("$caracter", '', $this->Style);
        if ($valor)
            $this->Style.="$caracter";
        $this->SetFont('', $this->Style);
    }

    //------- Cambiar los estilos de la fuente (negritas, it?lica y subrayado)
    function TextColor($r, $g, $b) {
        $this->Color['r'] = $r;
        $this->Color['g'] = $g;
        $this->Color['b'] = $b;
        $this->SetTextColor($this->Color['r'], $this->Color['g'], $this->Color['b']);
    }

    //-------- Ejecutar alguno de los comandos--------------
    function SetComando($comando) {
        if (in_array($comando, $this->comm)) {
            if ($comando == '[b]')
                $this->Estilo('b', TRUE);
            if ($comando == '[/b]')
                $this->Estilo('b', FALSE);
            if ($comando == '[i]')
                $this->Estilo('i', TRUE);
            if ($comando == '[/i]')
                $this->Estilo('i', FALSE);
            if ($comando == '[u]')
                $this->Estilo('u', TRUE);
            if ($comando == '[/u]')
                $this->Estilo('u', FALSE);
            if ($comando == '[blue]')
                $this->TextColor(0, 0, 255);
            if ($comando == '[black]')
                $this->TextColor(0, 0, 0);
            if ($comando == '[red]')
                $this->TextColor(200, 0, 0);
            if ($comando == '[green]')
                $this->TextColor(0, 200, 0);
            if ($comando == '[arial]')
                $this->SetFont('arial');
            if ($comando == '[courier]')
                $this->SetFont('courier');
            if ($comando == '[times]')
                $this->SetFont('times');
            if ($comando == '[helvetica]')
                $this->SetFont('helvetica');
            if ($comando == '[BIG]')
                $this->SetFontSize($this->FontSizeInicial + 3);
            if ($comando == '[normal]')
                $this->SetFontSize($this->FontSizeInicial);
            if ($comando == '[small]')
                $this->SetFontSize($this->FontSizeInicial - 3);
        }
    }

    //---------- separar en palabras y comandos-----
    function JLsplit($cadena) {
        $palabra = '';
        $res = array();
        while (strlen($cadena) > 0) {
            if (substr($cadena, 0, 1) != ' ') {
                while (substr($cadena, 0, 1) != ' ' && strlen($cadena) > 0) {
                    $escomando = FALSE;
                    foreach ($this->comm as $un) { // es comando
                        if ($un == substr($cadena, 0, strlen($un))) {
                            if ($palabra != '')
                                $res[] = $palabra;
                            $palabra = '';
                            $res[] = $un;
                            $cadena = substr($cadena, strlen($un));
                            $escomando = TRUE;
                            break;
                        }
                    }
                    if ($escomando)
                        break;
                    $letra = substr($cadena, 0, 1);
                    $cadena = substr($cadena, 1);
                    $palabra.=$letra;
                }
            }
            if (substr($cadena, 0, 1) == ' ') {
                while (substr($cadena, 0, 1) == ' ' && strlen($cadena) > 0) {
                    $letra = substr($cadena, 0, 1);
                    $cadena = substr($cadena, 1);
                    $palabra.=$letra;
                }
            }
            //----agregamos la palabra     
            if ($palabra != '')
                $res[] = $palabra;
            $palabra = '';
        }
        return($res);
    }

    //---------------------- Devuelve el ancho de una palabra sencilla o compuesta (con comandos) ----
    function AnchoPalabra($arreglo) {
        $medidalinea = 0;
        for ($i = 0; $i < count($arreglo); $i++) { // ciclo sobre todos los elementos de la palabra
            $palabra = $arreglo[$i];
            if (in_array($palabra, $this->comm)) { // Es comando? 
                $this->SetComando($palabra);
            } else { // es palabra
                $medidalinea += $this->GetStringWidth($palabra);
            }
        } //fin for
        return($medidalinea);
    }

    //---------------------- Extraer una palabra sencilla o compuesta (con comandos) del array de palabras ----
    function ExtraerPalabra(&$arreglo) {
        $res = array();
        while (count($arreglo) > 0) { // ciclo sobre todos los elementos del array de la palabra
            $palabra = array_shift($arreglo);
            $res[] = $palabra;
            if (!in_array($palabra, $this->comm)) { // No es comando? 
                if (strpos("*$palabra", " ") > 0)
                    break; //<-  si tiene un espacio es fin de palabra
            }
        } //fin for
        return($res);
    }

    //---------------------- Devuelve cuantas palabras caben en la linea de $ancho pixeles ----
    function ExtraerCadena_qcabe($arreglo, $ancho) {
        $cadenas = array();
        $palabra = array();
        $medidalinea = 0;
        $contadorpalabras = 0;
        while (count($arreglo) > 0) { // ciclo sobre todos los elementos del array de palabras
            $palabra = $this->ExtraerPalabra($arreglo);
            $anchura = $this->AnchoPalabra($palabra);

            if ($medidalinea + $anchura < $ancho) { // la palabra si cabe en la linea?
                $medidalinea += $anchura;
                $contadorpalabras++;
            } else { // no cabe
                if ($medidalinea == 0) { // la palabra es mas ancha que toda la l?nea (se obliga a imprimirla)
                    $cadenas[] = 1;
                    $medidalinea = 0;
                    $contadorpalabras = 0;
                } else { //No cabe la siguiente palabra, grabamos la linea anterior
                    $cadenas[] = $contadorpalabras;
                    $medidalinea = $anchura;
                    $contadorpalabras = 1;
                }
            }
        } //fin while
        $cadenas[] = $contadorpalabras;
        return($cadenas);
    }

    //--------------- Imprimir un parrafo-----------
    function JLprint($cad, $ANCHO, $margenIzq = 10) {
        $palabras = $this->JLsplit($cad);

        $Auxfont = $this->FontFamily;     // save settings
        $Auxfontsize = $this->FontSizePt; // save settings
        $AuxStyle = $this->Style; // save settings
        $AuxColor = $this->Color; // save settings

        $lineasN = $this->ExtraerCadena_qcabe($palabras, $ANCHO);

        $this->SetFont($Auxfont, $AuxStyle); // restore settings
        $this->SetFontSize($Auxfontsize);   // restore settings  
        $this->TextColor($AuxColor['r'], $AuxColor['g'], $AuxColor['b']); // restore settings  

        $palabrasAux = $palabras;
        $icount = 0;
        $lineasNew = array();
        for ($i = 0; $i < count($lineasN); $i++) {
            //---- averiguar la longitud total de la linea
            $longitud = 0;
            for ($j = 0; $j < $lineasN[$i]; $j++) {
                $palabra = $this->ExtraerPalabra($palabras);
                $anchura = $this->AnchoPalabra($palabra);
                $longitud += $anchura;
            }
            $npalabras = $lineasN[$i];
            //----- calcular el tama?o del espaciado
            $espaciado = 0;
            if ($npalabras > 1)
                $espaciado = ($ANCHO - $longitud) / ($npalabras - 1);
            $lineasNew[] = array($lineasN[$i], $espaciado, $longitud, $npalabras);
        }

        $this->SetFont($Auxfont, $AuxStyle); // restore settings
        $this->SetFontSize($Auxfontsize); // restore settings  
        $this->TextColor($AuxColor['r'], $AuxColor['g'], $AuxColor['b']); // restore settings  
        //----- imprimir todas las lineas del parrafo
        $palabras = $palabrasAux;
        for ($i = 0; $i < count($lineasN); $i++) {
            //Texto justificado  
            if ($this->align == 'l') {
                $espaciado = 0;
                $leftmargin = $margenIzq;
            } elseif ($this->align == 'r') {
                $espaciado = 0;
                $leftmargin = ($ANCHO - $lineasNew[$i][2]);
            } elseif ($this->align == 'c') {
                $espaciado = 0;
                $leftmargin = ($ANCHO - $lineasNew[$i][2]) / 2;
            } else {
                $espaciado = $lineasNew[$i][1];
                $leftmargin = $margenIzq;
                if ($i == count($lineasN) - 1)
                    $espaciado = 0; // <- para la ultima l?nea
            }

            //-----imprimir una linea   
            $posicionX = $this->PosX + $leftmargin;
            for ($j = 0; $j < $lineasN[$i]; $j++) {
                $palabra = $this->ExtraerPalabra($palabras);
                for ($ii = 0; $ii < count($palabra); $ii++) {
                    $subpalabra = $palabra[$ii];
                    if (in_array($subpalabra, $this->comm)) { // Es comando?
                        $this->SetComando($subpalabra);
                    } else { // es palabra
                        $this->SetX($posicionX);
                        $this->write(5, "$subpalabra");
                        $posicionX+=$this->GetStringWidth($subpalabra);
                    }
                }
                $posicionX+=$espaciado;
            }
            $this->Ln(10);
        }
    }

    //--------------- Imprimir un texto-----------
    function JLCell($txt, $ANCHO, $alineacion = 'j') {
        $this->align = $alineacion;

        $Auxfont = $this->FontFamily;     // save settings
        $Auxfontsize = $this->FontSizePt; // save settings
        $AuxStyle = $this->Style; // save settings  
        $AuxColor = $this->Color; // save settings

        $this->PosX = $this->GetX();
        $txt = str_replace("\r", "", $txt); //<- Validate text on windows style
        //--- se divide en parrafos y se imprime cada parrafo
        $parrafos = explode("\n", $txt);
        $posX = $this->GetX();
        for ($i = 0; $i < count($parrafos); $i++) {
            $this->SetX($posX);
            $this->SetFont($Auxfont, $AuxStyle);     // restore settings
            $this->SetFontSize($Auxfontsize); // restore settings  
            $this->TextColor($AuxColor['r'], $AuxColor['g'], $AuxColor['b']); // restore settings  

            $this->JLprint($parrafos[$i], $ANCHO);

            $Auxfont = $this->FontFamily;     // save settings
            $Auxfontsize = $this->FontSizePt; // save settings
            $AuxStyle = $this->Style; // save settings  
            $AuxColor = $this->Color; // save settings
        }
    }
    
	
}
?>