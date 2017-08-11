<?php 
session_start(); 
$_SESSION['filtro']=$_GET['filtro'];
$_SESSION['fechaprest']=$_GET['fechaprest'];
?>
<script type="text/javascript">
jQuery(document).ready(function(){    
    
	var filtro  = obtener_variable('filtro');
	var entidad = 'Detalle'+filtro;
	var tipo_usuario = obtener_variable('usuTipo');
	
	jQuery("#listado"+entidad).jqGrid({
            url:'controlador/detalle_prestamo.php?filtro='+filtro,
            datatype: "json",
            colNames:['C&oacute;digo','C&oacute;d. Prestamo','Cedula','Nombre','Cod. Cierre','Fecha','Sueldo','Monto'],
            colModel:[
                
				{name:'codigo'
                    ,index:'detliqId'
                    ,width:25
                    ,editable:false
                    ,hidden:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 3,hidedlg:false}
                },
                
                {name:'prestamoid'
                    ,index:'detliqPrestamoId'
                    ,width:20
                    ,editable:true
                    ,hidden:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
					,editoptions: {size:10, readonly:true, hidedlg:false}	
					,editrules:{required:true,edithidden:true}						
                },
				{name:'cedusuario'
                    ,index:'detliqCodigo'
                    ,width:20
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:10, readonly:true, hidedlg:false}					
                },
				{name:'nombusuario'
                    ,index:'detliqIdorigen'
                    ,width:45
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
					,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:10, readonly:true, hidedlg:false}					
                },				
				{name:'codcierre'
                    ,index:'detliqLiquidacionCodigo'
                    ,width:25
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:10, readonly:true, hidedlg:false}					
                },
                 {name:'fechaliq'
                    ,index:'detliqFecha'
                    ,width:25
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:10, maxlength: 10}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{elmsuffix:" (*)" }
                },
       
				{name:'sueldo'
                    ,index:'detliqSueldo'
                    ,width:20
                    ,editable:false
                    ,hidden:true
                    ,resizable:false
                    ,edittype:"text"
                    ,editoptions:{size:50, readonly:true, hidedlg:false}
					,editrules:{edithidden:true}
                },
				{name:'monto'
                    ,index:'detliqMonto'
                    ,width:25
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:15, maxlength:15,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{elmsuffix:" (*)" }
					,formatter: 'number'
                }
				
					
            ],
            //rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30,50,100],
            pager: '#paginador'+entidad,
            caption:"Detalle Liquidaci&oacute;n",
            sortname: 'detliqFecha',
            sortorder: "ASC",
            editurl:'controlador/detalle_prestamo.php?filtro='+filtro,
            viewrecords: true,
			rownumbers: true,		
			footerrow : true, 
			userDataOnFooter : true, 
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
                                
		
		jQuery("#listado"+entidad).jqGrid('setGroupHeaders', { 
			useColSpanStyle: false, 
			groupHeaders:[				
				{startColumnName: 'cedusuario', numberOfColumns: 3, titleText: '<em> Registro procesado por: </em>'},
				{startColumnName: 'fechaliq', numberOfColumns: 4, titleText: '<em>Abonado a Pr&eacute;stamo</em>'}
			] 
		});
		
		
        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,view:true,del:true,refresh:true,searchtext:"Buscar"}, //options
        {
            height:"auto",
            width:"auto",
            closeAfterEdit: true,
            closeOnEscape:true,
			//modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,	
            reloadAfterSubmit:true,
            edittext:"Editar",
            processData: "Modificando...",
			bottominfo:"Los campos marcados con (*) son obligatorios",
			afterShowForm: function(formid) {
				
				$('#fechaliq',formid).attr("onKeyPress","return NumGuion(event)");
				$('#fechaliq',formid).datepicker({
					changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				
				$('#monto',formid).attr("onKeyPress","return NumPunto(event)");
				
            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";		
				var valor = "";
					
					var fechaliq = $('#fechaliq').val();
					ret = validar_fecha(fechaliq);
					complete = ret[0];
					message = ret[1];
					
					if(complete==true){
						var fechaprest = obtener_variable('fechaprest');
						if(comprobarFechaMayor(fechaprest,fechaliq)){
							complete=false;
							message='La Fecha No puede ser mayor a la Fecha Pr&eacute;stamo: '+fechaprest;
						}else{
							complete=true;
						}
					}
					
					if(complete==true){
						var decimal = /^(\d){1,8}(.\d{2}$)?/;  // de 1 a 8 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
						valor = $('#monto').val();                
						if (!(decimal.test(valor))){
							complete = false
							message = 'Monto incorrecto!! solo 2 decimales, ejemplo: 15000.00 ';
						}
					}

					if(complete==true){
						if ($('#monto').val()<=0){
							complete = false
							message = 'Monto incorrecto!! ingrese un monto superior a 0 ';
						}
					}
                
                
                return [complete,message]
					
            },
			 
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
					
					$("#listadoPrestamos").trigger("reloadGrid"); 
                    
					jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Modificada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
					
					//actualizar grid de Caja de Ahorro
					$("#listadoCajahorro").trigger("reloadGrid"); 
					
					return [true, response.responseText] 
                } 
                else {
                    return [false, response.responseText]
						 
                } 
            }
        }, // options Editar
        {
            height:"auto",
            width:"auto",
            closeAfterAdd: true,
            caption: "Agregar",
            closeOnEscape:true,
            closeOnSubmit:true,
			//modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,		
            reloadAfterSubmit:true,
            addCaption:"Registrar Liquidaci&oacute;n",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {
				
				$('#tr_prestamoid',formid).hide();
				
				$('#prestamoid',formid).val(filtro);
				
				$('#fechaliq',formid).attr("onKeyPress","return NumGuion(event)");
				$('#fechaliq',formid).datepicker({
					changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
			   
			   $('#monto',formid).attr("onKeyPress","return NumPunto(event)");
				
            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";		
				var valor = "";
					
					var fechaliq = $('#fechaliq').val();
					ret = validar_fecha(fechaliq);
					complete = ret[0];
					message = ret[1];
					
					if(complete==true){
						var fechaprest = "<? echo $_GET['fechaprest']; ?>";
						if(comprobarFechaMayor(fechaprest,fechaliq)){
							complete=false;
							message='La Fecha No puede ser mayor a la Fecha Pr&eacute;stamo: '+fechaprest;
						}else{
							complete=true;
						}
					}
					
					if(complete==true){
						var decimal = /^(\d){1,8}(.\d{2}$)?/;  // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
						valor = $('#monto').val();                
						if (!(decimal.test(valor)) || valor<=0){
							complete = false
							message = 'Monto incorrecto!! solo 2 decimales, ejemplo: 15000.00 ';
						}
					}

					if(complete==true){
						if ($('#monto').val()<=0){
							complete = false
							message = 'Monto incorrecto!! ingrese un monto superior a 0 ';
						}
					}
                
                return [complete,message]
					
            },
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {prestamoid:filtro,sueldo:$('#trabSueldo').val()};
                return retarr; 
            },		 			
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
					
					$("#listadoPrestamos").trigger("reloadGrid"); 
					
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
					
					//actualizar grid de Caja de Ahorro
					$("#listadoCajahorro").trigger("reloadGrid"); 
					
					return [true, response.responseText]
                } 
                else {
                    return [false, response.responseText]
						 
                } 
            }			
					
        }, // options Agregar
        {
            height:"auto",
            width:"auto",
			modal:true,
            closeAfterDel: true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
            // Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {prestamoid:filtro};
                return retarr; 
            },		
			afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
					$("#listado"+entidad).trigger("reloadGrid"); 
					$("#listadoPrestamos").trigger("reloadGrid"); 
					return [true, response.responseText] 
                }else{
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {} // search options
    );
		
	var idSel = filtro;	
		
	//Procesar Cierre de la Caja de Ahorro
	$('#btnImprimir'+idSel).click(function(){
		window.open("vista/rptDetalle_prestamo.php?prestamoid="+idSel);
		
	});
	
	
	verificartipousuario(tipo_usuario,entidad);
	
});


</script>


	<center>
		
		<div id="gbox_listado" class="ui-jqgrid ui-widget ui-widget-content ui-corner-all" dir="ltr" style="width: auto;margin:0 auto;"> 
			<div id="gview_listado" class="ui-jqgrid-view" style="width: auto;"> </div>
					
			<div class="ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix" >
				<a class="ui-jqgrid-titlebar-close HeaderButton" href="javascript:void(0)" role="link" style="float:right; right: 0px;">
					<span class="ui-icon ui-icon-circle-triangle-n"></span>
				</a>
				<span class="ui-jqgrid-title">Detalle del Prestamo</span>
			</div>
		
		
				<table id="tbFichaingreso" style="padding:3px; width:650px; border: 0px solid #EEEEEE; margin-top:5px; text-align:center;">
					<thead>
						<tr>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:230px;'>Tipo Prestamo</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:70px;'>Fecha</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:100px;' > Descuento</th>
							<th class="ui-state-default ui-th-column ui-th-ltr">Monto Aprobado</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:70px;'>Estatus</th>
						</tr> 
					</thead>
					<tbody>
						<tr>
							<td> <? echo $_GET['tipoprest']; ?> </td>
							<td> <? echo $_GET['fechaprest']; ?>  </td>
							<td> <? echo $_GET['cuota']." ".$_GET['tipodesc']; ?>  </td>
							<td> <b> Bs. <? echo $_GET['monto']; ?> </b> </td>
							<td> <input id="lblEstatus<? echo $_GET['filtro']; ?>" type="text" size="9" value="<? echo $_GET['estatus']; ?>"  style="border:0px; color:red; text-align:center;" /> </td>
						</tr>
					</tbody>
				</table>
		</div>
		
</center>
		
	<div id="div_listadoDetalle<? echo $_GET['filtro']; ?>">
		
		<span id="rsperrorDetalle<? echo $_GET['filtro']; ?>" style="color:red"></span> <br/> 
		<table id="listadoDetalle<? echo $_GET['filtro']; ?>"></table>
		<div id="paginadorDetalle<? echo $_GET['filtro']; ?>"></div>
	
		<div style='text-align:right; margin-top:15px; display:none;'>
			<!-- <a id='btnProcesar<? //echo $_GET['filtro']; ?>' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Procesar Cierre <span class='ui-icon ui-icon-refresh'></span></a> -->
			<a id='btnImprimir<? echo $_GET['filtro']; ?>' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Imprimir <span class='ui-icon ui-icon-print'></span></a>
		</div>
	
	</div>
