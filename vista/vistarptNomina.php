<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			limpiarPantalla();
			
			$('.fecha').datepicker({ 
				changeYear: true,
				changeMonth: true, 
				dateFormat:"dd-mm-yy"
			});
			
			
			$('#ventana').delegate( "#btnLimpiar", 'click', function(){	
				limpiarPantalla();
			});
				
			$('#ventana').delegate( "#btnImprimir", 'click', function(){	
				
				var orgaid 	= $("#cmbOrganismo").val();
				var cargo 	= $("#cmbCargo").val();	
				var desde	= $("#desde").val();
				var hasta  = $("#hasta").val();
		
				if(desde!="" && hasta!=""){
					if(comprobarFechaMayor(desde,hasta)){
						mensaje("La fecha Inicial NO debe ser mayor a la fecha final...");
					}else{
						window.open("vista/rptNomina.php?desde="+desde+"&hasta="+hasta+"&orgaid="+orgaid+"&cargo="+cargo);
						//abrirVentana("vista/rptNomina.php?desde="+desde+"&hasta="+hasta+"&orgaid="+orgaid+"&cargo="+cargo,'Reporte Nomina',600,450);
					}
				}else{
					mensaje("Por favor, seleccione un rango de fechas para la consulta.");
				}
				
			});
			
			

		});
		
		function limpiarPantalla(){				
				$("#ventana input").val('');				
				
				//Cargar Combo Organismo
				$.ajax({                
						url: "controlador/organismo.php",
						data:"oper=carga_select",
						type: "POST",
						success: function(ret){
							$('.divOrganismo').empty(); //remover combo departamento
							$('.divOrganismo').append(ret); //Cargar combo departamento
						}
				});
				
				cargarCargos();
				
		}
		
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
			
			
		//Funcion para cargar combo Cargos
			function cargarCargos(){
				$.ajax({                
					url: "controlador/cargos.php",
					data:"oper=carga_select",
					type: "POST",
					success: function(ret){
						$('.divCargo').empty(); //remover combo departamento
						$('.divCargo').append(ret); //Cargar combo departamento
					}
				});
			}
			
</script>	


<div id='ventana'  style="width:480px;">		
		<div class='titular'>GENERAR N&Oacute;MINA </div>		
				<table >
				<tr>
					<td><strong>Organismo: </strong></td>
					<td> <div class='divOrganismo'> </div> </td>
				</tr>
				<tr>
					<td><strong>Cargo: </strong></td>
					<td> <div class='divCargo'> </div> </td>
				</tr>
				
				<tr>
					<td><strong>Fecha: </strong></td>
					<td> 
						<input id='desde' class='estilo-input fecha' type='text' size="12" placeholder="desde" readonly /> &nbsp; &nbsp;
						<input id='hasta' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly />
					</td>
				</tr>
			</table>
	
		<div style='text-align:right;padding:10px 15px;'>
			<hr style='color:#F0F0F0; ' /> <br />			
			<a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' title="Limpiar Campos"  href='javascript:void(0)' style='margin-right:10px; padding:8px 15px;' > &nbsp;&nbsp; Limpiar <span class='ui-icon ui-icon-refresh '></span></a>
			<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:8px 18px;' > &nbsp; Imprimir <span class='ui-icon ui-icon-print '></span></a>
			
		</div>
</div>