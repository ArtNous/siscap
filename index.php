<?php
session_start();
$_SESSION['logeado'] = FALSE;

	include('estilo/header.php');
	
?>
<script type='text/javascript'>  
jQuery(document).ready(function(){
	$('#fecha').hide();
});
</script>	
<?php
	echo "<div class='imgLogin'></div>";
	
	echo "<div id='vista'>";
		
		echo "
		<div id='login'>
			<form id='frmAcceso' action=''>
				<span class='titulo' style='margin-left:20px;'>Acceso al Sistema </span>
				<fieldset>
					<table cellspacing='12' style=' width:90%; margin-top:14px; margin-left:13px; '>
						<tr align='left'>
							<td colspan='2'> &nbsp; <span class='error'></span> </td>
						</tr>
						
						<tr>
					       	<td style='width:85px;'><b>Usuario:</b></td>
					       	<td><input name='usuario' id='usuario' type='text' size='15' maxlength='10'  class='estilo-input' style='padding:5px;'  title='Introduzca el ID del Usuario'  style='padding:5px;' /></td>  
						</tr>
						<tr>
							<td><b>Contrase&ntilde;a:</b></td>
						   	<td><input type='password' name='clave' id='clave' size='15' maxlength='10' class='estilo-input' style='padding:5px;' /></td>
						</tr>
						<tr>
							<td><b>Tipo Usuario:</b></td>
								<td>
									<select id='cmbTipo' name='cmbTipo' class='estilo-input' style='padding:3px; min-width:145px;'>
										<option value='Trabajador'>Asociado</option>
										<option value='Operador'>Operador</option>
										<option value='Administrador'>Administrador</option>
										
									</select>
							</td>
						</tr>
						
						
						<tr align='right' >
							<td colspan='2'>
								
								<a id='btnAcceder' title='Conectar al Sistema' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:comprobar_datos()' style='margin-right:22px; margin-top:15px; padding:10px 25px;'> Conectar </a>
							</td>
						</tr>
					</table>
				</fieldset>
			</form>
			
			<div style='width:310px; margin:10px; line-height:18px; text-align:justify; padding:3px;'>
				Se recomienda utilizar el navegador <b>Mozilla Firefox</b>, puedes descargalo en el siguiente enlace: 
				<a href='http://www.mozilla.org/en-US/firefox/new/' target='_blank' >
					<img src='imagenes/firefox.png' align='right' style='margin-bottom:-40px;' />
				</a>
			</div>
		</div>
		";		
		
	echo "</div>";

	include('estilo/footer.php'); 

?>