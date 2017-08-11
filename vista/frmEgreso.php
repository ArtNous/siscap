<script type="text/javascript">
    jQuery(document).ready(function(){
     
		var entidad = "Egreso";
        jQuery("#listado"+entidad).jqGrid({
            url:'controlador/egreso.php',
            datatype: "json",
            colNames:['Cédula',' &nbsp;&nbsp;  C&oacute;digo','Nombre',
					  'Organismo','Departamento','Cargo','Fecha Ingreso','Fecha Egreso','Motivo'],
            colModel:[
                {name:'cedula'
                    ,index:'trabCedula'
                    ,width:20
					,key: true
                    ,hidden:false
                    ,editable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {
                        dataInit: function(elem) {
                            $(elem).autocomplete({
                                source: 'controlador/trabajador.php?accion=autocompletar&campo=trabCedula&estatus=1',
                                select: function(event, ui) { 
                                    $('input[id=codigo]').attr("value",ui.item.codigo);  
									$('input[id=nombre]').attr("value",ui.item.nombres);  
									$('input[id=organismo]').attr("value",ui.item.organismo);  
									$('input[id=departamento]').attr("value",ui.item.departamento);  
									$('input[id=cargo]').attr("value",ui.item.cargo);  
									$('input[id=fechaingreso]').attr("value",ui.item.fechaingreso);
									
									$('#cedula').live("keyup",function(){
										if($('#cedula').val()!=ui.item.cedula)
											$('#codigo,#nombre,#organismo,#departamento,#cargo,#fechaingreso').val('');
									});
                                },
                                formatItem: function(row, i, max) {
                                    return i + "/" + max + ": \"" + row.value + "\" [" + row.label + "]";
                                },
                                formatMatch: function(row, i, max) {
                                    return row.value + " " + row.label;
                                },
                                formatResult: function(row) {
                                    return row.label;
                                },	
                                width: 250,
                                minChars: 3,
                                selectFirst: false,
                                mustMatch: true
                            });
                            jQuery('.ui-autocomplete').css({'font-size':'90%','font-weight':'bold'});

                        } 
							 
                        ,size:10, maxlength: 8,hidedlg:false
                    } 
                    ,editrules:{required:true,edithidden:true,number:true}
                    ,formoptions:{ rowpos:1, colpos:1, elmsuffix:" (*) &nbsp;&nbsp; "}
                },                 
				{name:'codigo'
                    ,index:'trabCodigo'
                    ,width:30
                    ,hidden:true
                    ,editable:true
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 12}
                    ,editrules:{edithidden:true}
                    ,formoptions:{ rowpos:1, colpos:2}
                },                 
                {name:'nombre'
                    ,index:'trabNombre'
                    ,width:40
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:30, maxlength: 40}
                    ,editrules:{edithidden:true}
                    ,formoptions:{ rowpos:2, colpos:1}
                },
				
                {name:'organismo'
                    ,index:'organismoDescripcion'
                    ,width:75
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions:{size:50, maxlength: 80}
                },
				{name:'departamento'
                    ,index:'departamentoDescripcion'
                    ,width:110
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
					,editrules:{edithidden:true}
                    ,editoptions:{size:40, maxlength: 80}
                },   
				{name:'cargo'
                    ,index:'trabCargo'
                    ,width:110
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
					,editrules:{edithidden:true}
                    ,editoptions:{size:40}
                },    				
                {name:'fechaingreso'
                    ,index:'trabFechaingreso'
                    ,width:40
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
					,editoptions: {size:10, maxlength: 10}
                    ,editrules:{edithidden:true}
                },
				{name:'fechaegreso'
                    ,index:'trabFechaegreso'
                    ,width:26
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
					,editoptions: {size:10, maxlength: 10}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:7, colpos:2, elmsuffix:" (*) "}
                },
				{name:'observacion'
                    ,index:'trabObservacion'
                    ,width:60
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"textarea"
                    ,editoptions: {cols:50, maxlength: 150,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{elmsuffix:" (*) "}
                }
					
            ],
            rowNum:10,
            autowidth: true,
            //width:860,
            height:"auto",
            rowList:[10,20,30],
            pager: '#paginador'+entidad,
            caption:"Gesti&oacute;n de Egresos",
            sortname: 'trabFechaegreso',
            sortorder: "DESC",
            editurl:'controlador/egreso.php',
            viewrecords: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 

        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,del:true,view:true,refresh:true,searchtext:"Buscar"}, //options
        {
            height:"auto",
            width:"auto",
            closeAfterEdit: true,
            closeOnEscape:true,
            //modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            //savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,	
            reloadAfterSubmit:true,
            edittext:"Editar",
			editCaption:"Modificar Trabajador",
            processData: "Modificando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {
				
				$.blockUI({ 
                    theme:     true, 
                    message: "Cargando, Espere Por Favor..." }); 
				
					centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
					
					$('#cedula,#nombre,#organismo,#departamento,#cargo,#fechaingreso',formid).attr("readonly","readonly");
					
					$('#cedula').attr("onKeyPress","return soloNum(event)");
					$('#observacion').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
					$('#observacion',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
					$('#fechaegreso',formid).attr("onKeyPress","return NumGuion(event)");
					$('#fechaegreso',formid).datepicker({dateFormat:"dd-mm-yy"});
					
					$('#tr_nombre .DataTD,#tr_organismo .DataTD,#tr_departamento .DataTD,#tr_cargo .DataTD,#tr_observacion .DataTD',formid).attr("colspan","4");
					
				$.unblockUI();  
					
            },	
			//Antes de envio
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var ret = "";
					
                if($("#cedula").val()=="" || $("#nombre").val()==""){
					message='Datos del trabajador incorrectos.!';
				}else{
					complete=true;
				}
				
				if(complete == true){
					var cedula = $("#cedula").val();
					ret = validar_cedula(cedula);
					complete = ret[0]; 
					message = ret[1];
				}
                
				var fechaI = $('#fechaingreso').val();
				var fechaE = $('#fechaegreso').val();
					if(complete == true){
						ret = validar_fecha(fechaE);
						complete = ret[0];
						message = ret[1];
					}
					
					//Validar que la Fecha Egreso sea mayor a la Fecha ingreso
					if(complete==true){
						if(comprobarFechaMayor(fechaI,fechaE)){
							message='La Fecha del Egreso No puede ser menor a la Fecha de Ingreso';
							complete=false;
						}
					}
				
                return [complete,message];
            },			
            afterSubmit: function(response, postdata) { 
						
                if (response.responseText == "") {
                   					
					jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Actualizada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
					
					
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
            //savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,		
            reloadAfterSubmit:true,
			addCaption:"Registrar Trabajador",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {
							
                $.blockUI({ 
                    theme:     true, 
                    message: "Cargando, Espere Por Favor..." }); 
					
					centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
					
					$('#nombre,#organismo,#departamento,#cargo,#fechaingreso',formid).attr("readonly","readonly");
					
					$('#cedula',formid).removeAttr("readonly");
					$('#cedula').attr("onKeyPress","return soloNum(event)");
					$('#observacion').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
					$('#observacion',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
					$('#fechaegreso',formid).attr("onKeyPress","return NumGuion(event)");
					$('#fechaegreso',formid).datepicker({dateFormat:"dd-mm-yy"});
					
					$('#tr_nombre .DataTD,#tr_organismo .DataTD,#tr_departamento .DataTD,#tr_cargo .DataTD,#tr_observacion .DataTD',formid).attr("colspan","4");
				
                $.unblockUI();  
            },	//fin del llamado a la funcion	
					
            //Antes de envio
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var ret = "";	
                
				if($("#cedula").val()=="" || $("#nombre").val()==""){
					message='Datos del trabajador incorrectos.!';
				}else{
					complete=true;
				}
				
				if(complete == true){
					var cedula = $("#cedula").val();
					ret = validar_cedula(cedula);
					complete = ret[0]; 
					message = ret[1];
				}
                
				var fechaI = $('#fechaingreso').val();
				var fechaE = $('#fechaegreso').val();
					if(complete == true){
						ret = validar_fecha(fechaE);
						complete = ret[0];
						message = ret[1];
					}
					
					//Validar que la Fecha Egreso sea mayor a la Fecha ingreso
					if(complete==true){
						if(comprobarFechaMayor(fechaI,fechaE)){
							message='La Fecha del Egreso No puede ser menor a la Fecha de Ingreso';
							complete=false;
						}
					}
				
                return [complete,message];
            },
					
            // Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
				var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);				
				
				var retarr = {};
                retarr = {codigo: row_data.codigo, estatus:'activo'};
                return retarr; 
            },
					
            afterSubmit: function(response, postdata) { 
						
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 

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
            closeAfterDel: true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
				var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);				
				
				var retarr = {};
                retarr = {codigo:row_data.codigo, estatus:'activo'};
                return retarr; 
            },
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Eliminada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
							                    		
					return [true, response.responseText] 
                } 
                else {
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {}, // search options
		
		{
			height:"auto",
            width:"auto",
			beforeShowForm: function(formid) {
				$('table',formid).removeAttr('style');
				$('.CaptionTD,.DataTD',formid).removeAttr('width');
				$('.CaptionTD',formid).attr("style","width:auto");
				$('.DataTD',formid).attr("style","width:auto");
				
				centrarDialogo('viewmodlistado'+entidad,'1000'); //parametros: Id Objeto,z-index              
				
            }	
		} // view options
    );
	
	/***  Solo boton Eliminar para el usuario Administrador ***/	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+entidad).hide();
	}		

});
</script>

<span id="rsperrorEgreso" style="color:red"></span> <br/>
<table id="listadoEgreso"></table>
<div id="paginadorEgreso"></div>