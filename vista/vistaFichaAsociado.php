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
			
			//if(tipo_usuario=="Trabajador"){
				
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
							$('#trabDireccion').attr("value",ret[0].direccion);
							$('#trabCorreo').attr("value",ret[0].correo);
							$('#trabTelefono').attr("value",ret[0].telefono);
							
							if(ret[0].sexo=="F"){
								$('#trabSexo').attr("value","FEMENINO");
							}else{
								$('#trabSexo').attr("value","MASCULINO");
							}
							
							$('#trabEdocivil').attr("value",ret[0].edocivil);
							$('#trabFechanac').attr("value",ret[0].fechanac);
							$('#trabNivel').attr("value",ret[0].nivel);
							$('#trabProfesion').attr("value",ret[0].profesion);
							
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
								var ancho = 100;       
								var alto = 130;
								$('.FotoTD').append("<div id='vistaFoto' style=' margin-top:-128px; z-index:99999;'><img id='imgfrontal' src='"+archivo+"' title='Vista Frontal del Carnet' width='"+ancho+"' height='"+alto+"' style='border:1px solid #A29F9F; border-radius:5px;' /></div> ");
							} 
		
						
					}
				});
			
			//}
			
			$('#btnLimpiar').hide();
			
			$('#btnLimpiar').click(function(){
				$('#trabCedula').val('');
				limpiarPantalla();
			});
			
			function limpiarPantalla(){
			
				$('#trabNombres,#trabCodigo,#trabFechai,#trabEstatus, #trabOrganismo, #trabDepartamento, #trabCargo, #trabSueldo').val('');
				$('.FotoTD #vistaFoto').remove(); //remover foto actual
				
				var trabced= 0;
				crear_variable('trabced',trabced);	
				
				jQuery("#listadoPrestamos").trigger("reloadGrid");		
				comprobar_paginador(trabced,"Prestamos");	

				$('#tabs .ui-tabs-close').click();
			}
		
			
        });
    </script>	

	
	
<div style='padding:10px; font-family:arial;' >
	<div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style='padding:5px 10px;'> 
		Informaci&oacute;n Personal
	</div>
	
	<div style='margin-top:10px; padding:5px; border-radius:5px; border: 1px solid #EEEEEE;'>
		
		<!--  Informacion Laboral   -->
		<table id="tbFichaingreso" style="width:800px; padding:3px; margin:0px auto; padding-top:0px;  border-radius:5px; border: 0px solid #EEEEEE; text-align:left;">
	
				<tr>
					
					<td> C&eacute;dula: </td>
					<td> <input name='trabCedula'  id='trabCedula' class="estilo-input"  size='9' maxlength="8"  readonly />  </td>
					<td></td>
					<td></td>
					<td rowspan="6"> <div class="FotoTD" style="float:right; position:relative; width:95px;" ><div style="width:100px; padding: 55px 0px; text-align:center; color:#A2A29F; border:1px solid #C0C0C0;  border-radius:5px; margin:0px auto;"> <b>Foto</b> </div></div></td> 
				</tr>
					
				<tr>
					<td>Nombres: </td>
					<td> <input name='trabNombres'  id='trabNombres' class="estilo-input"  size='40' value="" readonly /> </td>
					<td colspan="2" style='vertical-align:bottom;'>
						Sexo: <input name='trabSexo'  id='trabSexo' class="estilo-input"  size='12' readonly value="" style='border:0px;' /> 
					</td>
				</tr>
				<tr>
					<td rowspan='2'>Direcci&oacute;n:</td>
					<td rowspan='2' > 
						<textarea name='trabDireccion'  id='trabDireccion' class="estilo-input" cols="30" rows="2" readonly ></textarea>
					</td>
					<td colspan="2"> Edo. Civil: <input name='trabEdocivil'  id='trabEdocivil' class="estilo-input"  size='14' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					
					<td colspan="2"> Fecha de Nac.: <input name='trabFechanac'  id='trabFechanac' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
				</tr>
				<tr>
					<td>Correo:</td>
					<td> <input name='trabCorreo'  id='trabCorreo' class="estilo-input"  size='35' value="" readonly /> </td>
					<td>Tel&eacute;fono: <input name='trabTelefono'  id='trabTelefono' class="estilo-input"  size='12' value="" readonly /> </td>
				</tr>
				<tr>
					<td>Nivel Educativo: </td>
					<td> <input name='trabNivel'  id='trabNivel' class="estilo-input"  size='30' value="" readonly /> </td>					
					<td colspan='2'>Profesi&oacute;n: <input name='trabProfesion'  id='trabProfesion' class="estilo-input"  size='25' value="" readonly /> </td>
					
				</tr>
		</table>
		
		</div>
	
	<br />
	
	<div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style='padding:5px 10px;'> 
		Informaci&oacute;n Laboral
	</div>
		<div style='margin-top:10px; padding:5px; border-radius:5px; border: 1px solid #EEEEEE;'>
		
		<!--  Informacion Laboral   -->
		<table id="tbFichaingreso" style="width:700px;   padding:3px; margin:0px auto; padding-top:0px; border-radius:5px; border: 0px solid #EEEEEE; text-align:left;">
			<tbody>
					
				<tr>
					<td> C&oacute;digo: </td>
					<td colspan='3'><input name='trabCodigo'  id='trabCodigo' class="estilo-input"  size='12' readonly value="" />  </td>
					<td colspan='2'> Estatus: <input name='trabEstatus'  id='trabEstatus' class="estilo-input"  style="border:0px;color:red;text-transform: uppercase; " size='10' readonly value="" />  </td>
				</tr>
				<tr>
					<td>Organismo:</td>
					<td colspan='3'> <input name='trabOrganismo'  id='trabOrganismo' class="estilo-input"  size='50' value="" readonly /> </td>
					<td colspan="2"> Fecha Ingreso: <input name='trabFechai'  id='trabFechai' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					<td>Departamento:</td>
					<td colspan='3'> <input name='trabDepartamento'  id='trabDepartamento' class="estilo-input"  size='50' value="" readonly /> </td>
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

<?php
} else {
    Header("Location: index.php");
}
?>