<script type='text/javascript'>  
		jQuery(document).ready(function(){
			
			//limpiarPantalla();
			
			$('.fecha').datepicker({ 
				changeYear: true,
				changeMonth: true, 				
				dateFormat:"dd-mm-yy",
				maxDate:"+0d"
			});
		
			//$('#btnImprimir').live("click",function(){
			$('#ventana').delegate( "#btnImprimir", 'click', function(){	
				
				var desde	= new Date(moment($("#desde").val().replace(/-/g,"/"), 'DD/MM/YYYY', true).format());
				var hasta  = new Date(moment($("#hasta").val().replace(/-/g,"/"), 'DD/MM/YYYY', true).format());
				
				var estatus ="";
				if(!($("#chk1").is(':checked') && $("#chk2").is(':checked'))) { 
					if($("#chk1").attr('checked')=="checked"){estatus ="Pendiente";}
					if($("#chk2").attr('checked')=="checked"){estatus ="Procesado";}
				}
				
				if(desde!="" && hasta!=""){
					if(comprobarFechaMayor(desde,hasta)){
						mensaje("La fecha Inicial NO debe ser mayor a la fecha final...");
					}else{
						
						window.open("vista/rptResumenCajahorro.php?desde="+desde+"&hasta="+hasta+"&estatus="+estatus);
						
					}
				}else{
					mensaje("Por favor, seleccione un rango de fechas para la consulta.");
				}
				
			});
		});
</script>	


<div id='ventana'  style="width:430px;">
		
		<div class='titular'>RESUMEN CAJA DE AHORROS </div>		
			<table >
				<tr>
					<td><strong>Fecha Cierre: </strong></td>
					<td> 
						<input id='desde' class='estilo-input fecha' type='text' size="12" placeholder="desde" readonly /> &nbsp; &nbsp;
						<input id='hasta' class='estilo-input fecha' type='text' size="12" placeholder="hasta" readonly />
					</td>
				</tr>
				<tr>
					<td><strong> Estatus Cierre: </strong></td>
					<td> 
						<input id='chk1' type='checkbox' value='Pendiente' checked /> Pendiente					
						<input id='chk2' type='checkbox' value='Procesado' checked /> Procesado
					</td>
				</tr>
					
			</table>
	
	
		<div style='text-align:right;padding:10px 15px;'>
			<hr style='color:#F0F0F0; ' /> <br />			
			<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:5px; padding:10px 18px;' > &nbsp; Imprimir <span class='ui-icon ui-icon-print '></span></a>
		</div>
</div>