<style>
	.divConfiguracion{ font-size:11px;}
	.divConfiguracion table tr td, .divConfiguracion select {font-size:11px;}
	.divConfiguracion table{margin:0px auto; }
</style>

<script type='text/javascript'>  
		jQuery(document).ready(function(){

			$('.fecha').datepicker({ 
				changeYear: true,
				changeMonth: true, 
				dateFormat:"dd-mm-yy",
				maxDate:"+0d"
			});

			var actual = new Date();
			$('#campoAño').val(actual.getFullYear());
			document.getElementById('campoMes').addEventListener('change',function(){
				var fecha = new Date();
				var primero = moment('01-'+this.value+'-'+fecha.getFullYear(), 'DD-MM-YYYY');
				
				$('#fechadesde').val(primero.format("DD-MM-YYYY"));
				$('#fechahasta').val(primero.add(1,'month').subtract(1,'day').format('DD-MM-YYYY'));
			});

			$('#ventana').delegate( "#btnGuardar", 'click', function(){	

				var entrada = document.getElementById("campoArchivo");
				var archivos = entrada.files;
				var ano = document.getElementById('campoAño').value;
				var mes = document.getElementById('campoMes').value;
               
				if ( mes == 0 ) {
					swal("Escoja el mes","No seleccionó el mes para procesar la nomina. Seleccione el mes y cargue el archivo de nuevo.", "warning");
					return false;
				}
				if(mes < 10 )
				{
					mes = '0'+mes;
				}
				var codMes = ano+"-"+mes;

				var desde = $('#fechadesde').val();
				var hasta = $('#fechahasta').val();
				var tipoP = $('#combo-descuento').val();

				Papa.parse(archivos[0], 
				{
					complete: function(resultado)
					{
						var json = {
							datos: new Array()
						};
						resultado.data.forEach(function(fila, i) {
							var temp = {
								cedula: fila[0],
								pago: fila[1],
							}
							json.datos.push(temp);
						});
						$.ajax({
							type: "POST",
							url: 'controlador/cargaPrestamos.php',
							data: 'dato='+JSON.stringify(json)+'&tipoPrestamo='+$('#combo-descuento').val()+"&hasta="+hasta,
							beforeSend: function(){
								$('#cargando').css("display", "block");
							},
							success: function(dato)
							{
								console.log(dato);
								$('#cargando').css("display", "none");

								switch(dato.msj){
									case 0:
										swal('Sin prestamos',
										'El trabajador con cedula ' + dato.trabajador + " no tiene prestamos de " + $('#combo-descuento option[value='+tipoP+']').text() + " registrados.",
										'warning');
										break;
									case 2:
										swal('No existe',
										'El trabajador con la cedula ' + dato.trabajador + " no existe, ingreselo al sistema para continuar con la operación",
										'warning');
										break;
									case 3:
										swal('Sobremonto',
										'El pago de la persona con cedula ' + dato.trabajador + " sobrepasa el saldo deudor de su prestamo.",
										'warning');
										break;
									case 8:
										swal('Fechas incorrectas',
										'La fecha seleccionada es menor a la fecha del prestamo ' + $('#combo-descuento option[value='+tipoP+']').text() + " del trabajador " + dato.trabajador,
										'warning');
										break;
									default: 
										swal('Correcto',
											'Los datos fueron guardados exitosamente',
											'success');
								}
							},
							error: function(err) {
								console.log(err);
							},
							dataType: 'json'
						});

						// $.post('controlador/cargaNomina.php','dato='+JSON.stringify(json)+'&codmes='+codMes+"&desde="+desde+"&hasta="+hasta+"&total="+(totalTrabajadores-1),
						// 	function(dato)
						// 	{
						// 		if (dato.length > 4) {
						// 			swal('Trabajador no existe',
						// 				'El trabajador con cedula '+dato+" no existe, ingreselo al sistema y vuelva a cargar el archivo.",
						// 				'warning');
						// 			return false;
						// 		}

						// 		switch(dato){
						// 			case 1:
						// 				swal('Repetida',
						// 				'El periodo que seleccionó ya existe, seleccione otro',
						// 				'warning');
						// 				break;
						// 			case 2:
						// 				swal('Pendiente',
						// 				'El periodo que seleccionó ya existe, y no ha sido procesado',
						// 				'warning');
						// 				break;
						// 			default: 
						// 				swal('Correcto',
						// 					'Los datos fueron guardados exitosamente',
						// 					'success');
						// 		}
						// 	}, 'text');
					},
				})
			});
			
		});
</script>	

<div id='ventana'  style="width:600px;">
		
		<div class='titular'>CARGA MASIVA DE PAGOS</div>		
		
			<div class="contenedor-nomina" style="width: 70%; margin: auto; padding: 10px;">
				
				<table >
					<tr>
						<td><strong>Periodo: </strong></td>
						<td> 
							<input style="width: 60px;" id='campoAño' type='text' readonly disabled="disabled" />
							<span>-</span>
							<select id="campoMes">
								<option value="">Mes</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<!-- <input id='mes' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly /> -->
						</td>
					</tr>
					<tr>
						<td><input id="fechadesde" type="text" class="fecha estilo-input" readonly placeholder="desde" /></td>
						<td><input id="fechahasta" type="text" class="fecha estilo-input" readonly placeholder="hasta" /></td>
					</tr>
					<tr>
						<td>
							Tipo de descuento: 
							<select name="tipo-des" id="combo-descuento">
							<?php
								$con = mysql_connect('localhost','root','programacion');
								mysql_select_db('siscap');
								if (!$con) {
									die("No se pudo conectar a la base de datos");
								}
								$res = mysql_query("SELECT tipoprestNombre as nombre, tipoprestId as id FROM tipo_prestamo",$con);
								if(!$res){
									die("Error: " . mysql_error($con));
								}
								if(mysql_num_rows($res) > 0) {
									while($fila = mysql_fetch_object($res)){
										echo '<option value="'.$fila->id.'">'. $fila->nombre .'</option>';
									}
								}
								mysql_close($res);
								mysql_close($con);
							?>
							</select>
						</td>
					</tr>
				</table>
				<p>Seleccione un archivo en formato CSV o un libro Excel.</p>
				<p><strong style="font-size: 25px; display: block; text-align: center; margin-bottom: 10px;">Nota</strong> El archivo debe tener el formato correcto para su lectura y carga.</p>
				<p><a href="formatoAhorro.pdf" target="_blank" style="background-color: black; color:white; padding: 0 5px;">Aqui</a> puede ver el formato correcto que debe llevar.</p>
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
