<style>
	.divConfiguracion{ font-size:11px;}
	.divConfiguracion table tr td, .divConfiguracion select {font-size:11px;}
	.divConfiguracion table{margin:0px auto; }
</style>

<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			$('#ventana').delegate( "#btnGuardar", 'click', function(){	
				var entrada = document.getElementById("campoArchivo");
				var archivos = entrada.files;
				if (archivos.length == 0) {
					swal("No hay archivo",
						"Debe seleccionar un archivo para continuar.",
						"warning");
					return false;
				}

				Papa.parse(archivos[0], 
				{
					complete: function(resultado)
					{
						var json = {
							datos: new Array()
						};
						resultado.data.forEach(function(fila, i) {
							var temp = {
								nombre: fila[0],
								apellido: fila[1],
								cedula: fila[2],
								sexo: fila[3],
								civil: fila[4],
								codigo: fila[5],
								fechaI: fila[6],
								organismo: fila[7],
								dpto: fila[8],
								cargo: fila[9],
								sueldo: fila[10],
							}
							json.datos.push(temp);
						});

						$.ajax({
							type: 'POST',
							url: 'controlador/cargaAsociados.php',
							data: 'dato='+JSON.stringify(json),
							beforeSend: function() {
								$('#cargando').css("display", "block");
							},
							success: function(respuesta) {
								$('#cargando').css("display", "none");
								console.log(respuesta);
								if(respuesta == 1){
								swal("Correcto",
									"Datos insertados correctamente",
									"success");
								} else {
								swal("Fallida",
									"No se pudo realizar la operación",
									"error");
								}
							},
							error: function(err){
								console.log(err);
							},
						});
					},
				})				
			});
		});
</script>	

<div id='ventana'  style="width:600px;">
		
		<div class='titular'>CARGA MASIVA DE TRABAJADORES</div>		
			
			<div class="contenedor-sueldos" style="width: 70%; margin: auto; padding: 10px;">
				
				<p>Seleccione un archivo en formato CSV o un libro Excel.</p>
				<p><strong style="font-size: 25px; display: block; text-align: center; margin-bottom: 10px;">Nota</strong> El archivo debe tener el formato correcto para su lectura y carga.</p>
				<p><a href="formatoSueldo.pdf" target="_blank" style="background-color: black; color:white; padding: 0 5px;">Aqui</a> puede ver el formato correcto que debe llevar.</p>
				<form action="">
					<input type="file" id="campoArchivo">
				</form>
				<div id="cargando">
					<div class="loading"></div>
					<p id="txtCargando">Cargando...</p>
				</div>
			</div>		
		
		<div class='divConfiguracion' style='display:none;'>
		 <fieldset style="width:320px; border-radius:5px; margin:0px auto; border:1px solid #C0C0C0;">	
			<legend style='color:#004276;'> <b>Configurar Tipo Pr&eacute;stamos</b> </legend>
			<!--  <div style='float:right; margin-top:-5px; margin-right:-5px;'><a id='btnCerrar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' title='Ocultar' style='margin-right:5px; padding:8px 11px;' > <span class='ui-icon ui-icon-close '></span></a></div>  -->
			<table style='padding:0px;'>
				<tr>
					<td><strong>Columna 1: </strong></td>
					<td> <div class='divTipo1'> </div> </td>
				</tr>
				<tr>
					<td><strong>Columna 2: </strong></td>
					<td> <div class='divTipo2'> </div> </td>
				</tr>
				<tr>
					<td><strong>Columna 3: </strong></td>
					<td> <div class='divTipo3'> </div> </td>
				</tr>
				<tr>
					<td><strong>Columna 4: </strong></td>
					<td> <div class='divTipo4'> </div> </td>
				</tr>
			</table>
		</fieldset>
		</div>
	
		<div style='text-align:right;padding:10px 15px;'>
			<hr style='color:#F0F0F0; ' /> <br />			
			<!--  <a id='btnConfiguracion' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:7px 12px;' > &nbsp; Configuraci&oacute;n <span class='ui-icon ui-icon-refresh '></span></a>  -->
			<a id='btnGuardar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:7px 12px;' > &nbsp; Guardar <span class='ui-icon ui-icon-disk '></span></a>
			
		</div>
	
</div>
