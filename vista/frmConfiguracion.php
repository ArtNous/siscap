<script type="text/javascript">
$(document).ready(function() {
		$.ajax({
			url: "controlador/configuracion.php",
			data:"oper=consultar",
			type: "POST",
			async:false,
			cache:false,
			dataType: "json",
			success: function(ret){
				$("#ahorro").val(ret[0].ahorro);
				$("#aporte").val(ret[0].aporte);
				$("#meses").val(ret[0].meses);
				$("#interes").val(ret[0].intereses);
				if(ret[0].liqprestipo==1)
					$("#liqprestipo").attr("checked","checked");
				else
					$("#liqprestipo").removeAttr("checked");
			}
		});
		
		$('#btnGuardar').delegate( "", 'click', function(){		
			
			var ahorro  = $("#ahorro").val();
			var aporte  = $("#aporte").val();
			var meses  = $("#meses").val();
			var interes  = $("#interes").val();

			var liqprestipo = 0;
			if( $('#liqprestipo').prop('checked'))
			    liqprestipo = 1;
			
			if(ahorro=='' ||  aporte=='' || meses=='' || interes==''){
				mensaje('Por favor, debe ingresar los datos solicitados.!! ');
			}else {
				
				if(meses=='0' || meses=='00'){
					mensaje('Mes incorrecto!. debe ser mayor a cero (0).!! ');
				}else{
					$.ajax({
							url: "controlador/configuracion.php",
							data:"oper=actualizar&ahorro="+ahorro+"&aporte="+aporte+"&meses="+meses+"&porcentaje="+interes+"&liqprestipo="+liqprestipo,		
							type: "POST",
							success: function(ret){
								
								if(ret==''){
									alerta('Informaci&oacute;n actualizada satisfactoriamente!!');
								}else{
									alerta('Se ha producido un error: '+ret);
								}
							}	
						});
				}	
			} 
		}); /*****Fin Guardar ***/	
});
</script>

	<br />
	<div id="ventana" style="width:500px; ">
		<div class='titular'>CONFIGURACI&Oacute;N GENERAL</div>
			<table >
				<tr>
					<td><strong>Descuento por Caja de Ahorro: </strong></td>
					<td >
						<input id="ahorro" type="text" size="6" value="" class='estilo-input' style="text-align:center;" maxlength="6" onKeyPress="return NumPunto(event)" /> % del Sueldo.
					</td>
				</tr>
				<tr>
					<td><strong>Pago del Aporte Patronal: </strong></td>
					<td >
						<input id="aporte" type="text" size="6" value="" class='estilo-input' style="text-align:center;" maxlength="6" onKeyPress="return NumPunto(event)" /> % del Descuento
					</td>
				</tr>
				<tr>
					<td colspan='2' style='color:#215986;' ><strong>PR&Eacute;STAMOS POR FINANCIAMIENTO</strong></td>
				</tr>
				<tr>
					<td><strong>M&aacute;ximo cuotas aprobar: </strong></td>
					<td >
						<input id="meses" type="text" size="6" value="" class='estilo-input' style="text-align:center;" maxlength="2" onKeyPress="return soloNum(event)" />	Meses.
					</td>
				</tr>
				<tr>
					<td><strong>Porcentaje de Intereses a descontar : </strong></td>
					<td >
						<input id="interes" type="text" size="6" value="" class='estilo-input'  style="text-align:center;" maxlength="5" onKeyPress="return NumPunto(event)" />	% del Monto a financiar.
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="checkbox" name="liqprestipo" id="liqprestipo" >
						<strong>Descontar solo un préstamo del mismo tipo al generar la liquidación</strong>
					</td>
				</tr>
			</table>
		<div style='color:#6C6C6C; text-align:justify; font-size:11px; padding: 0px 40px 15px 40px;'> 
			<hr style='color:#6C6C6C; margin-bottom:5px;'/>
		</div>
	
		<div style='text-align:right;'>
			<a id='btnGuardar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:10px;' > Guardar <span class='ui-icon ui-icon-disk '></span></a>
		</div>
</div>