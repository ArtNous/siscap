<?php
if (!isset($_SESSION)) session_start();
if ($_SESSION['logeado'] == true) {
?>
<script type="text/javascript">
		$(document).ready(function() {	
			$("#accordion").accordion({ 
					autoHeight: false,
					active: false,
					collapsible: true
			});
				
			$('#accordion div').attr('style','line-height:20px;');
			$('#accordion div .menu').hover(
				function () {
					//$(this).append($("<span> ***</span>"));
					$(this).attr("style","font-weight:bold;color:#CC0000;");
				  }, 
				  function () {
					$(this).find("span:last").remove();
					$(this).attr("style","font-weight:normal;color:black;");
				}
			);
			
			$('#accordion').show();
		});
</script>
<div id='imgVista' class='imgVista'></div>

<?php if($_SESSION['usuTipo']=="Administrador" || $_SESSION['usuTipo']=="Operador"){ ?>
  
		<div id="accordion" style="margin:30px 20px 20px 20px; padding:0px; width:290px; float:left; display:none;">	
		
			<h3 id="tab1"><a class='menu' href="vista/vistaAsociados.php">Asociados</a></h3>
			<div></div>	
			
			<h3 id="tab2"><a href="#">Caja de Ahorro</a></h3>
			<div> 				
				<a class='menu' href="vista/frmCaja_ahorro.php"> Gestionar Cierre </a> <br/>				
				<a class='menu' href="vista/vistaDescuento_ahorro.php">Operaciones de Ahorro</a><br/>	
				<a class='menu' href="vista/frmAporte_patronal.php"> Pago Aporte Patronal </a> 		
			</div>	
			
			<h3 id="tab3"><a href="#">Descuentos / Liquidaci&oacute;n </a></h3>
			<div> 
				<a class='menu' href="vista/frmTipo_prestamo.php">Tipos de Descuentos</a> <br />
				<a class='menu' href="vista/vistaPrestamos.php">Tramitar Pr&eacute;stamo </a> <br />
				<a class='menu' href="vista/frmLiquidacion.php">Gestionar Liquidación Masiva</a>
			</div>		        
			
			<h3 id="tab4"><a class='menu' href="vista/configuracion.php">Configuraci&oacute;n</a></h3>
			<div> </div>	
			
			<?php if($_SESSION['usuTipo']=="Administrador"){ ?> <!--  Solo para el usuario Administrador  -->
				<h3 id="tab5"><a class='menu' href="vista/frmUsuarios.php">Control de Usuarios</a></h3>
				<div> </div>

				<h3 id="tab7"><a href="#">Cargas masivas</a></h3>
			<div> 
				<a class='menu' href="vista/vistaCargarSueldos.php" target="_blank" >Importar sueldos</a> <br />
				<a class='menu' href="vista/vistaCargaMasivaAhorros.php" target="_blank" > Importar ahorros </a> <br />		
				<a class='menu' href="vista/vistaCargaMasivaPagosPrestamos.php" target="_blank" > Importar pagos de préstamos </a> <br />		
				<a class='menu' href="vista/vistaCargarAsociados.php" target="_blank" > Importar asociados </a> <br />		
			</div>			
			<?php } ?>
				
			<h3 id="tab6"><a href="#">Reportes</a></h3>
			<div> 
				<a class='menu' href="vista/vistarptTrabajador.php" target="_blank" >Listado de Asociados</a> <br />
				<a class='menu' href="vista/vistarptCajahorro.php" target="_blank" > Resumen de Caja Ahorro </a> <br />			
				<a class='menu' href="vista/vistarptPrestamos.php" target="_blank" > Relación de Préstamos </a> <br />			
				<a class='menu' href="vista/vistarptLiquidacion.php" target="_blank" > Registro de Liquidaciones </a> <br />
				<a class='menu' href="vista/vistarptEstadoCuenta.php" target="_blank" > Estado de Cuenta </a> <br />
				<a class='menu' href="vista/vistarptNomina.php" target="_blank" > Generar N&oacute;mina </a> 
			</div>
		</div>
		
		
	<?php }else if($_SESSION['usuTipo']=="Trabajador"){ ?>

				<div id="accordion" style="margin:30px 30px 20px 30px; padding:0px; width:275px;  float:left;">	
					
					<h3 id="tab1" style='padding: 10px 0px;' ><a class="menu" href="vista/vistaFichaAsociado.php">Informaci&oacute;n Personal</a></h3>
					<div> </div>	
					
					<h3 id="tab2" style='padding: 10px 0px;' ><a class="menu" href="vista/vistaDescuento_ahorro.php">Caja de Ahorro</a></h3>
					<div> </div>	
					
					<h3 id="tab3" style='padding: 10px 0px;'><a class="menu" href="vista/vistaPrestamos.php">Pr&eacute;stamos</a></h3>
					<div> </div>		        
					
				</div>
		
	<?php } ?>
	
	
		<div style='float:left; width:300px; margin-top:25px; margin-left:10px; padding:15px; border-radius:10px; font-size:12px; text-align:justify; line-height:1.8; background-color:#EAEAEA;'>
			<b> Bienvenido (a): </b>  
			<p>Al sistema automatizado para el registro y control de la Caja de Ahorro y los pr&eacute;stamos de los 
			Trabajadores del Ministerio de Salud del Estado Trujillo, permitiendo
			controlar y agilizar los procesos llevados a cabo en esta Instituci&oacute;n.</p>
		</div>
		
		
		<div style=' width:330px; font-size:12px; margin-right:25px; margin-top:10px; margin-bottom:-30px; padding:0px; float:right;  text-align:justify; line-height:1.5;'>
			
				&nbsp; &nbsp; &nbsp; <b>Estimado usuario</b>:
					<ul>
						<li>
							<b>Recuerde <span style='color:red;s'>cambiar peri&oacute;dicamente su contrase&ntilde;a</span></b>,
							para mayor seguridad solo es de uso personal, 
							"NO la comparta con terceras personas". <br /><br />
						</li>
						<li>
							<b>Si presenta alg&uacute;n inconveniente con el sistema:</b> por favor comunicarse 
							con el administrador del sistema.
							
						</li>
					<ul>
				
			
		</div>
	
	
	
<?php

} else {
    Header("Location: index.php");
}
?>
	