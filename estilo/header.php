<?php   
	setlocale(LC_TIME, 'spanish');
    // $fechaM= ucwords(strtolower(strftime("%A, %d"))).' de '.ucwords(strtolower(strftime("%B %Y")));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<title>SISCAP</title>
		<link rel="shortcut icon" href="imagenes/logo.ico" />
		
		<!--  CSS  -->
		<link rel="stylesheet" type="text/css" media="screen" href="estilo/estilo.css" />		
		
		<!--  Libreria jQuery  -->
		<link rel="stylesheet" type="text/css" media="screen" href="libreria/jquery/css/jquery-ui.css" />
		<script type="text/javascript" src="libreria/jquery/jquery.min.js" ></script>
		<script type="text/javascript" src="libreria/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript" src="libreria/jquery/jquery.datepick-es.js"></script>
			
		
		<!--  Libreria jqGrid  -->
		<link rel="stylesheet" type="text/css" media="screen" href="libreria/jqgrid/css/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="libreria/jqgrid/plugins/ui.multiselect.css" />
		<script type="text/javascript" src="libreria/jqgrid/js/i18n/grid.locale-es.js"></script>
		<script type="text/javascript">
			$.jgrid.no_legacy_api = true;
			$.jgrid.useJSON = true;
		</script>
		<script type="text/javascript" src="libreria/jqgrid/js/jquery.jqGrid.min.js"></script>
		<script type="text/javascript" src="libreria/jqgrid/src/grid.subgrid.js"></script>
		<script type="text/javascript" src="libreria/jqgrid/plugins/jquery.blockUI.js"></script>
		<script type="text/javascript" src="libreria/jqgrid/plugins/jquery.tablednd.js"></script>
		<script type="text/javascript" src="libreria/jqgrid/plugins/jquery.bt.min.js"></script>
		<script type="text/javascript" src="libreria/jqgrid/plugins/jquery.contextmenu.js"></script>
		<link rel="stylesheet" href="libreria/dulce_alerta/sweetalert.css"></link>
		<link  rel="stylesheet" href="libreria/dulce_alerta/themes/twitter/twitter.css"></link>
		<link rel="stylesheet" href="estilo/cargando.css">
		<script src="libreria/dulce_alerta/sweetalert.min.js"></script>
		<script src="libreria/moment-with-locales.min.js"></script>
		<style>
 /*-----------Cargas masivas | Contenedor nomina | Archivo------------*/
			.Uploadbtn {
			  position: relative;
			  overflow: hidden;
			  padding:7px 17px;
			  text-transform: uppercase;
			  color:#fff;
			  background: #000066;
			  -webkit-border-radius: 4px;
			  -moz-border-radius: 4px;
			  -ms-border-radius: 4px;
			  -o-border-radius: 4px;
			  border-radius: 4px;
			  width:100px;
			  text-align:center;
			  cursor: pointer;
			}
			.Uploadbtn .input-upload {
			  position: absolute;
			  top: 0;
			  right: 0;
			  margin: 0;
			  padding: 0;
			  opacity: 0;
			  height:100%;
			  width:100%;
			}
 /*-----------Cargas masivas | Contenedor nomina | Archivo------------*/
		</style>
		
		<!--  Libreria Cargar Archivos  -->
		<script type="text/javascript" src="libreria/plugins/ajaxfileupload.js"></script>

		<!-- Funcion Encriptacion en Javascript -->
        <script src="libreria/plugins/encriptado/base_64.js" type="text/javascript"></script>

		<!--  Funciones js  -->
		<script src="include/funciones.js" type="text/javascript"></script>	
	
       
		<script type='text/javascript'>  
			jQuery(document).ready(function(){
				$('.menu').delegate('','click', function(){		
				   $("#vista").load(this.href);
				   return false;
				});	
			}); 
		</script>	
	
	</head>
	<body>
		<div id="contenedor">
			<div class="banner" >
			
				<object type="application/x-shockwave-flash" data="imagenes/banner.swf" width="950" height="205">   
					<param name="movie" value="imagenes/banner.swf" />   
					<param name="wmode" value="transparent"> 
				</object> 
				
				<div id="fecha" style='margin-left:340px; margin-top:-50px; color:#666666;'> 
					 <?php 
					//  echo utf8_encode($fechaM); 
					 ?>  
				</div>
			</div>
			<div class="contenido">