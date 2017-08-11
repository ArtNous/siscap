<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			limpiarPantalla();
			
			$('.fecha').datepicker({ 
				changeYear: true,
				changeMonth: true, 
				dateFormat:"dd-mm-yy",
				maxDate:"+0d"
			});
			
			//Autocompletar del input buscar
			$("#cedula").autocomplete({
						selectFirst: true,
						autoFill:true,
						source: function( request, response ) {
							$.ajax({
								type: "GET",
								url: "controlador/trabajador.php",
								dataType: "json",
								data: {
									accion:'autocompletar',
									estatus:0,
									campo:'trabCedula',
									term: request.term
								},
								success: function( data ) {
									response(data);
								}
							});
						},
						select: function(event, ui) {
							$('#cedula').attr("value",ui.item.cedula);
							$('#nombre').attr("value",ui.item.nombres);
						} 
		  
			});
					
			
			
			$('#cmbGrupo').live("change",function(){
				if(this.value==""){
					$('#chkGrupo').attr("checked","checked");
				}else{
					$('#chkGrupo').removeAttr("checked");
				}
				
			});
			
			
			
			
			function limpiarPantalla(){
				//Cargar Combo Organismo
				$.ajax({                
						url: "controlador/organismo.php",
						data:"oper=carga_select",
						type: "POST",
						success: function(ret){
							$('.divOrganismo').empty(); //remover combo departamento
							$('.divOrganismo').append(ret); //Cargar combo departamento
							cargarDepartamento(0);
						}
				});
				
				$.ajax({                
						url: "controlador/tipo_prestamo.php",
						data:"oper=carga_select",
						type: "POST",
						success: function(ret){
							$('.divTipoprestamo').empty(); //remover combo departamento
							$('.divTipoprestamo').append(ret); //Cargar combo departamento
						}
				});
				
				$("#ventana input").val('');
				$("#cmbEstatus").val('');
				$('#chkEstatus').attr("checked","checked");
				
			}
			
			$('#cmbOrganismo').live("change",function(){
				cargarDepartamento(this.value);
			});
			
						
			//Funcion para cargar combo Departamento
			function cargarDepartamento(filtro){
				$.ajax({                
					url: "controlador/departamento.php",
					data:"oper=carga_select&filtro="+filtro,
					type: "POST",
					success: function(ret){
						$('.divDepartamento').empty(); //remover combo departamento
						$('.divDepartamento').append(ret); //Cargar combo departamento
					}
				});
			}
			
			
			$('#ventana').delegate( "#btnImprimir", 'click', function(){	
				var grupo	= $("#cmbGrupo").val();
				
				var cedula 	= $("#cedula").val();
				var orgaid 	= $("#cmbOrganismo").val();
				var depaid 	= $("#cmbDepartamento").val();		
				var tipoid 	= $("#cmbTipoprest").val();		
				var desde	= $("#desde").val();
				var hasta  = $("#hasta").val();
				
				var estatus ="";
				if(!($("#chk1").is(':checked') && $("#chk2").is(':checked'))) { 
					if($("#chk1").attr('checked')=="checked"){estatus ="Pendiente";}
					if($("#chk2").attr('checked')=="checked"){estatus ="Liquidado";}
				}
								

				if(comprobarFechaMayor(desde,hasta)){
					mensaje("La fecha Inicial NO debe ser mayor a la fecha final...");
				}else{
					if(cedula=="" && orgaid=="" && depaid=="" && tipoid=="" && desde=="" && hasta=="" && estatus==""){
						window.open("vista/rptListadoLiquidacion.php?ordenar="+grupo);
					}else{
						window.open("vista/rptListadoLiquidacion.php?ordenar="+grupo+"&cedula="+cedula+"&orgaid="+orgaid+"&depaid="+depaid+"&tipoid="+tipoid+"&estatus="+estatus+"&desde="+desde+"&hasta="+hasta);
					}
				}
				
				
			});
			
			$('#btnLimpiar').live("click",function(){
				limpiarPantalla();
			});

		});
</script>	


<div id='ventana'  style="width:520px;">
		
		<div class='titular'>REPORTE LIQUIDACI&Oacute;N A ASOCIADOS</div>		
			
			<table >
				<tr>
					<td><strong>Listado Por: </strong></td>
					<td> 
						<select id='cmbGrupo' class="estilo-input" >
							<option value=""> -- Seleccione --</option>
							<option value="detliqLiquidacionCodigo">Cierre</option>
							<option value="prestamoId">Tipo Descuento</option>
							<option value="trabCedula">Trabajador</option>
						</select>
						<input id='chkGrupo' type='checkbox' value='activo' checked /> General					
					</td>
				</tr>
				<tr>
					<td><strong>Trabajador: </strong></td>
					<td> 
						<input id='cedula' class='estilo-input' type='text' size="10" placeholder="buscar"  /> &nbsp; 
						<input id='nombre' class='estilo-input' type='text' size="30"  placeholder="Nombres" readonly />
					</td>
				</tr>
				<tr>
					<td><strong>Organismo: </strong></td>
					<td> <div class='divOrganismo'> </div> </td>
				</tr>
				<tr>
					<td><strong>Departamento: </strong></td>
					<td> <div class='divDepartamento'> </div> </td>
				</tr>
				
				<tr>
					<td><strong>Descuento por: </strong></td>
					<td> <div class='divTipoprestamo'> </div> </td>
				</tr>
				<tr>
					<td><strong>Fecha Liquidaci&oacute;n: </strong></td>
					<td> 
						<input id='desde' class='estilo-input fecha' type='text' size="12" placeholder="desde" readonly /> &nbsp; &nbsp;
						<input id='hasta' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly />
					</td>
				</tr>
				<tr>
					<td><strong> Estatus Pr&eacute;stamo: </strong></td>
					<td> 
						<input id='chk1' type='checkbox' value='Pendiente' checked /> Pendiente					
						<input id='chk2' type='checkbox' value='Liquidado' checked /> Liquidado
					</td>
				</tr>

			</table>
	
	
		<div style='text-align:right;padding:10px 15px;'>
			<hr style='color:#F0F0F0; ' /> <br />			
			<a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' title="Limpiar Campos"  href='javascript:void(0)' style='margin-right:10px; padding:10px 15px;' > &nbsp;&nbsp; Limpiar <span class='ui-icon ui-icon-refresh '></span></a>
			<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:10px 18px;' > &nbsp; Imprimir <span class='ui-icon ui-icon-print '></span></a>
			
		</div>
</div>