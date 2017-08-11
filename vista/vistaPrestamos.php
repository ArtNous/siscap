<?php
session_start();
if ($_SESSION['logeado'] == true) {
?>

    <script type='text/javascript'>  
        jQuery(document).ready(function(){
		  	
            $("div[id^='tabs']").tabs({
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                        "Documento no encontrado.!" );
                    }
                }
            });
			
			var usuario = obtener_variable('usuId');
			var tipo_usuario = obtener_variable('usuTipo');
			
			if(tipo_usuario=="Trabajador"){
				
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "controlador/trabajador.php",        
					data: {	accion:'autocompletar',
							estatus:0,
							campo:'trabCedula',
							term: usuario
					},
					success: function(ret){
						
						//retornar los campos del autocompletr del trabajador                
							var cedula = ret[0].cedula;
							$('#trabCedula').attr("value",cedula);
							$('#trabNombres').attr("value",ret[0].nombres);
							$('#trabCodigo').attr("value",ret[0].codigo);
							$('#trabFechai').attr("value",ret[0].fechaingreso);
							$('#trabFechae').attr("value",ret[0].fechaegreso);
							$('#trabEstatus').attr("value",ret[0].estatus);
							$('#trabOrganismo').attr("value",ret[0].organismo);
							$('#trabDepartamento').attr("value",ret[0].departamento);
							$('#trabCargo').attr("value",ret[0].cargo);
							$('#trabSueldo').attr("value",ret[0].sueldo);
							
							var archivo = "files/"+cedula+".jpg";
							$('.FotoTD #vistaFoto').remove(); //remover foto actual
							if(file_exists(archivo)){
								//podemos hacer cualquier cosa con ese fichero, porque sabemos que sí existe.
								var ancho = 70;       
								var alto = 95;
								$('.FotoTD').append("<div id='vistaFoto' style=' margin-top:-86px; z-index:99999;'><img id='imgfrontal' src='"+archivo+"' title='Vista Frontal del Carnet' width='"+ancho+"' height='"+alto+"' style='border:1px solid #A29F9F; border-radius:5px;' /></div> ");
							} 
							
							crear_variable('trabced',cedula);
							
							jQuery("#listadoPrestamos").trigger("reloadGrid");	
							comprobar_paginador(cedula,"Prestamos");
							verificartipousuario(tipo_usuario,'Prestamos');
							
							$('#btnImprimir,#btnEdoCuenta').show();
						
					}
				});
	
				
				
				$('#trabCedula').attr("readonly","readonly");
				$('#btnLimpiar').hide();
			
			}else{
					
					$('#trabCedula').removeAttr("readonly");
					//$('#trabCedula').attr("onKeyPress","return LetraNumEspacio(event)");
					
					//Autocompletar del input buscar
					$("#trabCedula").autocomplete({
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
							
							//retornar los campos del autocompletr del trabajador                
							var cedula = ui.item.cedula;
							$('#trabCedula').attr("value",cedula);
							$('#trabNombres').attr("value",ui.item.nombres);
							$('#trabCodigo').attr("value",ui.item.codigo);
							$('#trabFechai').attr("value",ui.item.fechaingreso);
							$('#trabFechae').attr("value",ui.item.fechaegreso);
							$('#trabEstatus').attr("value",ui.item.estatus);
							$('#trabOrganismo').attr("value",ui.item.organismo);
							$('#trabDepartamento').attr("value",ui.item.departamento);
							$('#trabCargo').attr("value",ui.item.cargo);
							$('#trabSueldo').attr("value",ui.item.sueldo);
							
							var archivo = "files/"+ui.item.cedula+".jpg";
							$('.FotoTD #vistaFoto').remove(); //remover foto actual
							if(file_exists(archivo)){
								//podemos hacer cualquier cosa con ese fichero, porque sabemos que sí existe.
								var ancho = 70;       
								var alto = 95;
								$('.FotoTD').append("<div id='vistaFoto' style=' margin-top:-86px; z-index:99999;'><img id='imgfrontal' src='"+archivo+"' title='Vista Frontal del Carnet' width='"+ancho+"' height='"+alto+"' style='border:1px solid #A29F9F; border-radius:5px;' /></div> ");
							} 
							
							/** Cargar Prestamos **/
							crear_variable('trabced',cedula);
							
							jQuery("#listadoPrestamos").trigger("reloadGrid");	
							comprobar_paginador(cedula,"Prestamos");
							verificartipousuario(tipo_usuario,'Prestamos');
							$('#btnImprimir,#btnEdoCuenta').show();
							
							if(ui.item.estatus!='activo'){
								jQuery("#add_listadoPrestamos,#edit_listadoPrestamos,#del_listadoPrestamos").hide();
							}
							
								
						} 
		  
					});
					
					
					$('#trabCedula').keyup(function(){
						var trabced = obtener_variable('trabced');	
						if($('#trabCedula').val() != trabced){
							limpiarPantalla();
						}
					});
			}
			
			
			$('#btnLimpiar').click(function(){
				$('#trabCedula').val('');
				limpiarPantalla();
			});
			
			
			
			limpiarPantalla();
			
        });
		
		function limpiarPantalla(){
			
			$('#trabNombres,#trabCodigo,#trabFechai,#trabEstatus, #trabOrganismo, #trabDepartamento, #trabCargo, #trabSueldo').val('');
			$('.FotoTD #vistaFoto').remove(); //remover foto actual
				
			var trabced= '';
			crear_variable('trabced',trabced);	
			
			comprobar_paginador(trabced,"Prestamos");	
			jQuery("#listadoPrestamos").trigger("reloadGrid");		
				
			$('#tabs .ui-tabs-close').click();
			$('#btnImprimir,#btnEdoCuenta').hide();
		}
    </script>	

	
	
<div style='padding:10px; ' >
	<div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style='padding:5px 10px;'> 
		Informaci&oacute;n del Asociado
	</div>
	
	<div style='margin-top:10px; padding:5px; border-radius:5px; border: 1px solid #EEEEEE;'>
		
		<!--  Informacion Laboral   -->
		<table id="tbFichaingreso" style=" width:95%;  padding:3px; margin:0px auto; padding-top:0px; border-radius:5px; border: 0px solid #EEEEEE; text-align:left;">
			<tbody>
				<tr>
					<td style='width:100px;' >C&eacute;dula:</td>
					<td> <input name='trabCedula'  id='trabCedula' class="estilo-input"  size='9' maxlength="8" onKeyPress="return LetraNumEspacio(event)" /> </td>
					<td> <a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' onClick="limpiarPantalla();" style='margin-left:10px;' > Limpiar <span class='ui-icon ui-icon-refresh'></span></a> </td>
					<td></td>					
				</tr>
				
				<tr>
					<td>Nombres: </td>
					<td colspan="3"> <input name='trabNombres'  id='trabNombres' class="estilo-input"  size='47' value="" readonly /> </td>					
					<td colspan="2" style='vertical-align:bottom;'>
						C&oacute;digo: <input name='trabCodigo'  id='trabCodigo' class="estilo-input"  size='8' readonly value="" style='border:0px;' /> 
						<input name='trabEstatus'  id='trabEstatus' class="estilo-input"  style="border:0px;color:red;text-transform: uppercase; " size='8' readonly value="" /> 
					</td>
					<td rowspan="4"> <div class="FotoTD" style="float:right; position:relative; width:70px;" ><div style="width:70px; text-align:center; color:#A2A29F; border:1px solid #C0C0C0; padding: 35px 0px; border-radius:5px; margin:0px auto;"> <b>Foto</b> </div></div></td> 
				</tr>
					
				
				<tr>
					<td>Organismo:</td>
					<td colspan='3'> <input name='trabOrganismo'  id='trabOrganismo' class="estilo-input"  size='47' value="" readonly /> </td>
					<td colspan="2"> Fecha Ingreso: <input name='trabFechai'  id='trabFechai' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					<td>Departamento:</td>
					<td colspan='3'> <input name='trabDepartamento'  id='trabDepartamento' class="estilo-input"  size='47' value="" readonly /> </td>
					<td colspan="2"> Fecha Egreso: <input name='trabFechae'  id='trabFechae' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					<td>Cargo:</td>
					<td colspan='3'> <input name='trabCargo'  id='trabCargo' class="estilo-input"  size='40' value="" readonly /> </td>
					<td>Sueldo:</td>
					<td> <input name='trabSueldo'  id='trabSueldo' class="estilo-input"  size='10' value="" readonly /> </td>
				</tr>
			</tbody>
		</table>
		
		</div>

</div>
	 
	<div id="tabs" style="min-height: 180px; width:900px;">
		<ul>
			<li><a href="#tabPrestrab">Pr&eacute;stamos</a></li>
		</ul>

		<div id="tabPrestrab">	
			<?php include_once('frmPrestamo.php'); ?>			
		</div>
		
	</div>
<?php
} else {
    Header("Location: index.php");
}
?>