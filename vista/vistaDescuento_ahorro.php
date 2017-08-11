<?php
session_start();
if ($_SESSION['logeado'] == true) {
?>
<script type='text/javascript'>  
jQuery(document).ready(function(){
        
    $("div[id^='tabs']").tabs({
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html("Documento no encontrado.!" );
            }
        }
    });	
			
    var usuario = obtener_variable('usuId');
	var tipo_usuario = obtener_variable('usuTipo');
	

	if(tipo_usuario=="Trabajador"){
		
		$('#trabCedula').attr("readonly","readonly");
		$('#btnLimpiar').hide();

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
					var ancho = 100;       
					var alto = 125;
					$('.FotoTD').append("<div id='vistaFoto' style=' margin-top:-122px; z-index:99999;'><img id='imgfrontal' src='"+archivo+"' title='Vista Frontal del Carnet' width='"+ancho+"' height='"+alto+"' style='border:1px solid #A29F9F; border-radius:5px;' /></div> ");
				} 
						
				//reloadGrid de los Listados			
				actualizarListado(cedula,tipo_usuario);

			} //fin sucess
		});
			
	}else{

		limpiarPantalla();					
		$('#trabCedula').removeAttr("readonly").focus();
							
		//Autocompletar del input buscar
		$("#trabCedula").autocomplete({
            minChars: 2,
            minLength: 2, 
            selectFirst: false,
            mustMatch: true,
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
					success: function(data){
						response(data);
					}
				});
			}, //fin source
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
					var ancho = 97;       
					var alto = 120;
					$('.FotoTD').append("<div id='vistaFoto' style=' margin-top:-120px; z-index:99999;'><img id='imgfrontal' src='"+archivo+"' title='Vista Frontal del Carnet' width='"+ancho+"' height='"+alto+"' style='border:1px solid #A29F9F; border-radius:5px;' /></div> ");
				} 
				
				//reloadGrid de los Listados			
				actualizarListado(cedula,tipo_usuario);
				
				if(ui.item.estatus!='activo'){
					jQuery("#add_listadoAhorro,#edit_listadoAhorro,#del_listadoAhorro").hide();
					jQuery("#add_listadoDescuentoAhorro,#edit_listadoDescuentoAhorro,#del_listadoDescuentoAhorro").hide();
				}

			}//fin select
		}); //fin autocomplete
 				
 		jQuery('.ui-autocomplete').css({'font-size':'90%','font-weight':'bold'});
					
					
		$('#trabCedula').change(function(){
			var trabced = obtener_variable('trabced');	
			if($('#trabCedula').val() != trabced)
				limpiarPantalla();
		});

		$('#btnLimpiar').click(function(){
			$('#trabCedula').val('');
			limpiarPantalla();
		});

	} //fin Else
			
 });

function limpiarPantalla(){
		
	$('#trabNombres,#trabCodigo,#trabFechai,#trabEstatus, #trabOrganismo, #trabDepartamento, #trabCargo, #trabSueldo').val('');
	$('.FotoTD #vistaFoto').remove(); //remover foto actual
	var tipo_usuario = obtener_variable('usuTipo');	
	actualizarListado(0,tipo_usuario);
	$('#trabCedula').focus();
}

function actualizarListado(cedula,tipo_usuario){
	
	crear_variable('trabced',cedula);
							
	jQuery("#listadoAhorro").trigger("reloadGrid");	
	comprobar_paginador(cedula,"Ahorro");
	verificartipousuario(tipo_usuario,"Ahorro");

	jQuery("#listadoDescuentoAhorro").trigger("reloadGrid");	
	comprobar_paginador(cedula,"DescuentoAhorro");
	verificartipousuario(tipo_usuario,"DescuentoAhorro");
}

</script>	


<div style='padding:10px;'>
	<div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style='padding:5px 10px;'> 
		Informacion del Asociado
	</div>
	
	<div style='margin-top:10px; padding:5px; border-radius:5px; border: 1px solid #EEEEEE;'>
		
		<!--  Informacion Laboral   -->
		<table id="tbFichaingreso" style="padding:3px; margin:0px auto; padding-top:0px; width:95%;  border-radius:5px; border: 0px solid #EEEEEE; text-align:left;">
			<tbody>
				<tr>
					<td>C&eacute;dula:</td>
					<td style='width:350px;' > 
						<input name='trabCedula'  id='trabCedula' class="estilo-input"  size='9' maxlength="8" onKeyPress="return LetraNumEspacio(event)" onkeyup="this.value=this.value.toUpperCase()" />
						<a id='btnLimpiar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:10px;' > Limpiar <span class='ui-icon ui-icon-refresh'></span></a> 
					</td>
					<td></td>
					<td> <input name='trabEstatus'  id='trabEstatus' class="estilo-input"  style="border:0px;color:red;text-transform: uppercase; " size='8' readonly value="" />  </td>
					<td rowspan="5" style="text-align:center;"> 
						<div class="FotoTD" style="width:98px;" >
							<div style="width:100%; text-align:center; color:#A2A29F; border:1px solid #C0C0C0; padding: 52px 0px; border-radius:5px;"> <b>Foto</b> </div>
						</div>
						
					</td> 
				</tr>
				
				<tr>
					<td>Nombres: </td>
					<td> <input name='trabNombres'  id='trabNombres' class="estilo-input"  size='40' value="" readonly /> </td>					
					<td style='width:100px; vertical-align:bottom;'>
						C&oacute;digo: 
					</td>
					<td> 
						<input name='trabCodigo'  id='trabCodigo' class="estilo-input"  size='8' readonly value="" style='border:0px;' /> 
					</td>
				</tr>
					
				
				<tr>
					<td>Organismo:</td>
					<td> <input name='trabOrganismo'  id='trabOrganismo' class="estilo-input"  size='40' value="" readonly /> </td>
					<td> Fecha Ingreso: </td>
					<td> <input name='trabFechai'  id='trabFechai' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					<td>Departamento:</td>
					<td> <input name='trabDepartamento'  id='trabDepartamento' class="estilo-input"  size='40' value="" readonly /> </td>
					<td> Fecha Egreso: </td>
					<td><input name='trabFechae'  id='trabFechae' class="estilo-input"  size='10' readonly value=""  style='border:0px;' /> </td>
					
				</tr>
				<tr>
					<td>Cargo:</td>
					<td> <input name='trabCargo'  id='trabCargo' class="estilo-input"  size='40' value="" readonly /> </td>
					<td>Sueldo:</td>
					<td> <input name='trabSueldo'  id='trabSueldo' class="estilo-input"  size='10' value="" readonly /> </td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
	 
	<div id="tabs" style="min-height:180px;">
		<ul>
			<li><a href="#tabAhorrotrab">Caja de Ahorro</a></li>
			<li><a href="#tabAbonodescuento">Abonos/Descuentos</a></li>
		</ul>

		<div id="tabAhorrotrab">	
			<?php include('frmAhorro.php'); ?>			
		</div>
		<div id="tabAbonodescuento">	
			<?php include('frmDescuento_ahorro.php'); ?>			
		</div>
	</div>

<?php
} else {
    Header("Location: index.php");
}
?>