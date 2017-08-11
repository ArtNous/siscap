<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			$('.fecha').datepicker({ 
				changeYear: true,
				changeMonth: true, 
				dateFormat:"dd-mm-yy",
				maxDate:"+0d"
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
			
			$('#cmbEstatus').live("change",function(){
				if(this.value==""){
					$('#chkEstatus').attr("checked","checked");
				}else{
					$('#chkEstatus').removeAttr("checked");
				}
				
			});
			
			
			$('#ventana').delegate( "#btnImprimir", 'click', function(){
								
				var orgaid 	= $("#cmbOrganismo").val();
				var depaid 	= $("#cmbDepartamento").val();
				var estatus = $("#cmbEstatus").val();
				var ingresodesde = $("#ingresodesde").val();
				var ingresohasta = $("#ingresohasta").val();
				var egresodesde	 = $("#egresodesde").val();
				var egresohasta  = $("#egresohasta").val();
				
				
				if(orgaid==''){
					mensaje("Por favor, seleccione el Organismo a Consultar");
				}else{
					if( (comprobarFechaMayor(ingresodesde,ingresohasta)) || (comprobarFechaMayor(egresodesde,egresohasta))){
						mensaje("La fecha Inicial NO debe ser mayor a la fecha final...");
					}else{
						window.open("vista/rptListadoTrabajador.php?orgaid="+orgaid+"&depaid="+depaid+"&estatus="+estatus+"&ingresodesde="+ingresodesde+"&ingresohasta="+ingresohasta+"&egresodesde="+egresodesde+"&egresohasta="+egresohasta);
						//abrirVentana("vista/rptListadoTrabajador.php?orgaid="+orgaid+"&depaid="+depaid+"&estatus="+estatus+"&ingresodesde="+ingresodesde+"&ingresohasta="+ingresohasta+"&egresodesde="+egresodesde+"&egresohasta="+egresohasta,'Listado Asociados',900,700);
					}
				}
				
				
			});
			
			$('#btnLimpiar').live("click",function(){
				limpiarPantalla();
			});
			
			limpiarPantalla();

		});
</script>	


<div id='ventana'  style="width:500px;">
		
		<div class='titular'>LISTADO DE ASOCIADOS </div>		
			
			<table >
				<tr>
					<td><strong>Organismo: </strong></td>
					<td> <div class='divOrganismo'> </div> </td>
				</tr>
				<tr>
					<td><strong>Departamento: </strong></td>
					<td> <div class='divDepartamento'> </div> </td>
				</tr>
				<tr>
					<td><strong>Fecha Ingreso: </strong></td>
					<td> 
						<input id='ingresodesde' class='estilo-input fecha' type='text' size="12" placeholder="desde" readonly /> &nbsp; &nbsp;
						<input id='ingresohasta' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly />
					</td>
				</tr>
				<tr>
					<td><strong>Fecha Egreso: </strong></td>
					<td> 
						<input id='egresodesde' class='estilo-input fecha' type='text' size="12" placeholder="desde" readonly /> &nbsp; &nbsp;
						<input id='egresohasta' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly />
					</td>
				</tr>
				<tr>
					<td><strong>Socio: </strong></td>
					<td> 
						<select id='cmbEstatus' class="estilo-input" >
							<option value=""> -- Seleccione --</option>
							<option value="activo">Activo</option>
							<option value="inactivo">Inactivo</option>
						</select>
						<input id='chkEstatus' type='checkbox' value='activo' checked /> Todos					
					</td>
				</tr>
			</table>
	
		<div style='text-align:right;padding:10px 15px;'>
			<hr style='color:#F0F0F0; ' /> <br />			
			<a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' title="Limpiar Campos"  href='javascript:void(0)' style='margin-right:10px; padding:10px 15px;' > &nbsp;&nbsp; Limpiar <span class='ui-icon ui-icon-refresh '></span></a>
			<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:10px 18px;' > &nbsp; Imprimir <span class='ui-icon ui-icon-print '></span></a>
			
		</div>
</div>