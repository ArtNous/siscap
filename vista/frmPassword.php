<?php
session_start();
$usuario = $_SESSION['usuId'];
$nombres = $_SESSION['usuNombres'];
?>

<script type="text/javascript">
    $(document).ready(function() {
		
		$('.contrasena:eq(0)').attr("onKeyPress","return contrasena(event)"); //Acepta solo letras y Numeros
		
		//Mensaje de validacion
		var cssRight = { 
			cssStyles:{
				color:'#cc0000', 
				width:'200'
			},
			positions: 'right'
		};
			
		$('.contrasena:eq(0)').bt('Recuerde ingresar entre 8 a 10 d&iacute;gitos alfan&uacute;mericos, al menos un n&uacute;mero y sin caracteres especiales.!',cssRight);
		$('.contrasena:eq(1)').bt('Ingrese nuevamente la contrase&ntilde;a para su respectiva confirmaci&oacute;n!',cssRight);
		
		
		 $("#btnCancelar").live("click",function(){ 
			window.location="principal.php";
		 });

		
         
		$("#btnGuardar").live("click",function(){ 
			
			var actual  = $("#SegDatxx").attr("value");
			var contra  = $("#SegDatE").attr("value");
			var contra1 = $("#SegDatE1").attr("value");
		  
			if(actual ==''){
				alerta('Por favor, debe ingresar su contrase&ntilde;a actual.!! ');
				$("#SegDatxx").focus();
			
			}else {
				if(contra=='' || contra1==''){
					alerta('Por favor, ingrese y confirme su nueva contrase&ntilde;a.!! ');
					$("#contra").focus();				
				}else{
					
					var datos=Base64.encode(actual);
					$.ajax({
						url: "controlador/usuarios.php",
						data:"oper=verificarClave"+"&datos="+datos,		
						type: "POST",
						success: function(ret){
							
							if(ret==1){
								
								 /** Validar la nueva contraseña**/
								valida = validar_contrasena(contra);								
								if(valida[0]==false){
									alerta("Nueva "+valida[1]);
									$("#SegDatE,#SegDatE1").val('');
									$('#SegDatE').focus();
								}else{
									/** Confirmar contraseñas  **/
									if(contra!=contra1){
										alerta("Las Contrase&ntilde;as NO coinciden!! debe ser igual a la anterior.");
										$("#SegDatE1").val('');
										$('#SegDatE1').focus();
									}else{
										
										var datos=Base64.encode(contra);
										$.ajax({
											url: "controlador/usuarios.php",
											data:"oper=actualizarClave"+"&datos="+datos,		
												type: "POST",
												success: function(ret){
													alerta('Su Contrase&ntilde;a ha sido cambiada exitosamente.!! ');
													$("#SegDatxx,#SegDatE,#SegDatE1").val('');
													//window.location="principal.php";
													setTimeout("window.location='principal.php'",2000);
												}		
										});
										
									
									}
								
								}
								
							}else{
								alerta('Su Contrase&ntilde;a actual ha sido incorrecta.!! ');
								$("#SegDatxx,#SegDatE,#SegDatE1").val('');
								$("#SegDatxx").focus();
							}
						}	
					});
				
				}
			
			} 
			
		}); /*****Fin Guardar ***/

    });
</script>

<br /> 
<div id='ventana'  style="width:480px;">
	<div class='titular'>CAMBIO DE CONTRASE&Ntilde;A </div>
			
	<div style="padding-top:15px;text-align:center; font-size:14px;"> <b> Sr(a)  <?php echo $nombres; ?> </b> </div>
			
	<div style='text-align:center; color:#6C6C6C;'>Ingrese la siguiente informaci&oacute;n: </div>
			
		<table >
				<tr>
					<td><strong>Contrase&ntilde;a actual: </strong></td>
					<td><input id="SegDatxx" name="SegDatxx" type="password" size="13" maxlength="10" title="Ingrese su Contrase&ntilde;a Actual" /></td>
				</tr>
				<tr>
					<td><strong>Nueva Contrase&ntilde;a: </strong></td>
					<td><input id="SegDatE" name="SegDatE" class='contrasena' type="password" size="13" maxlength="10" title="" /></td>
					<td rowspan="2" > <img src="imagenes/key.png" /> </td>
				</tr>
				<tr>
					<td><strong>Confirmar Contrase&ntilde;a: </strong></td>
					<td><input id="SegDatE1" name="SegDatE1" class='contrasena' type="password" size="13" maxlength="10" title="" /></td>
				</tr>
				
		</table>
		
		<div style='color:#6C6C6C; text-align:justify; font-size:11px; padding: 0px 40px 15px 40px;'> 
			<hr style='color:#6C6C6C; margin-bottom:5px;'/>
			<b>Estimado usuario:</b> <span style="color:#6C6C6C;"> recuerde actualizar peri&oacute;dicamente su Contrase&ntilde;a 
			y para mayor seguridad <b>NO</b> compartirla con terceras personas.!! </span>
			
		</div>
	
		<div style='text-align:right;'>
			<a id='btnGuardar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:10px;' > Guardar <span class='ui-icon ui-icon-disk '></span></a>
			<a id='btnCancelar' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-right:15px;' > Cancelar <span class='ui-icon ui-icon-cancel '></span></a>
		</div>
</div>