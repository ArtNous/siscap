<style>
	.divConfiguracion{ font-size:11px;}
	.divConfiguracion table tr td, .divConfiguracion select {font-size:11px;}
	.divConfiguracion table{margin:0px auto; }
</style>

<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			limpiarPantalla();
			
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
							$('#organismo').attr("value",ui.item.organismo);
						} 
		  
			});
					
			
			
			function limpiarPantalla(){				
				$("#ventana input").val('');
				$.ajax({                
						url: "controlador/tipo_prestamo.php",
						data:"oper=carga_select",
						type: "POST",
						success: function(ret){
							$('.divTipo1,.divTipo2,.divTipo3,.divTipo4').empty(); //remover combo departamento
							$('.divTipo1,.divTipo2,.divTipo3,.divTipo4').append(ret); //Cargar combo departamento
						}
				});
				$("#ventana input:eq(0)").focus();
			}

			
			$('#optTam').click(function(){
				alert('entro');
			});
			
			$('#btnConfiguracion').live("click",function(){				
				//$('.divConfiguracion').show("blind", { direction: "vertical" }, 1000);
				//$('#btnConfiguracion').hide();
				$('.divConfiguracion').toggle(2000)
			});
			
			$('#btnCerrar').live("click",function(){
				$('.divConfiguracion').hide("blind", { direction: "vertical" }, 1000);
				$('#btnConfiguracion').show();
			});
			
			
			$('#ventana').delegate( "#btnImprimir", 'click', function(){	
				
				var cedula 	= $("#cedula").val();
				
				var tp1 = $(".divTipo1 select").val();
				var tp2 = $(".divTipo2 select").val();
				var tp3 = $(".divTipo3 select").val();
				var tp4 = $(".divTipo4 select").val();
				
				if(cedula!=""){
					if(tp1=="" && tp2=="" && tp3=="" && tp4==""){
						window.open("vista/rptEstadoCuenta.php?cedula="+cedula);
					}else{
						window.open("vista/rptEstadoCuenta.php?cedula="+cedula+"&tp1="+tp1+"&tp2="+tp2+"&tp3="+tp3+"&tp4="+tp4);
					}
					
				}else{
					mensaje("Por favor, ingrese los datos del trabajador.");
				}
				
			});
			
			$('#ventana').delegate( "#btnImprimir", 'click', function(){	
				limpiarPantalla();
			});

		});
</script>	


<div id='ventana'  style="width:600px;">
		
		<div class='titular'>ESTADO DE CUENTA</div>		
			
			<table>
				<tr>
					<td><strong>Por Trabajador: </strong></td>
					<td> 
						<input id='cedula' class='estilo-input' type='text' size="8" placeholder="buscar" title="Busqueda por C&eacute;dula, Nombres o C&oacute;digo" /> &nbsp; 
						<input id='nombre' class='estilo-input' type='text' size="40"  placeholder="Nombres" readonly />
					</td>
				</tr>
				<tr>
					<td><strong>Organismo: </strong></td>
					<td> 
						<input id='organismo' class='estilo-input' type='text' size="55"  placeholder="Organismo" readonly />
					</td>
				</tr>
			</table>
		
		
		
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
			<a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' title="Limpiar Campos"  href='javascript:void(0)' style='margin-right:10px; padding:8px 15px;' > &nbsp;&nbsp; Limpiar <span class='ui-icon ui-icon-refresh '></span></a>
			<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:7px 12px;' > &nbsp; Imprimir <span class='ui-icon ui-icon-print '></span></a>
			
		</div>
	
</div>
