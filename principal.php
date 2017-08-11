<?php
session_start();
	if ($_SESSION['logeado'] == false){
		Header("Location: index.php");
		exit;
		
	}else if($_SESSION['logeado'] == true){
		include('estilo/header.php');		
		echo "
				<div id='opcMenu'>
					".$_SESSION['usuTipo'].": <span style='color:red;'>".$_SESSION['usuNombres']."</span> | &nbsp;
					<a class='menu' href='vista/frmPassword.php'> <img src='imagenes/icoClave.png' width='12'/> Cambiar Contrase&ntilde;a</a> 	
				</div>
				<div style='float:right; margin-top:-30px; margin-right:45px;''> 
				        <a href='principal.php' > <img src='imagenes/btInicio.jpg' title='Página de Inicio' /> </a> &nbsp; 				        
				        <a href='index.php' > <img src='imagenes/btSalir.jpg' title='Salir del Sistema' /> </a> 
				</div>
			";
		echo "<div id='vista' class='vista'>";
	
		include('estilo/menu.php');
	
		echo "</div>";
		
		include('estilo/footer.php'); 
	} 
?>
<script src="libreria/papaparse/papaparse.min.js"></script>
