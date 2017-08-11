<script type="text/javascript">
    jQuery(document).ready(function(){
        var entidad = "Usuarios";
		
		jQuery("#listado"+entidad).jqGrid({
            url:'controlador/usuarios.php',
            datatype: "json",
            colNames:['C&eacute;dula','Nombres','Apellidos','Correo El&eacute;ctronico','Tel&eacute;fono','Tipo Usuario','Contrase&ntilde;a','Confirmar Contrase&ntilde;a','Estatus'],
            colModel:[
					
                {name:'cedula'
                    ,index:'usuCedula'
                    ,width:30
                    ,key: true
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
					,editoptions: { 
                        dataInit: function(elem) {
                            $(elem).autocomplete({
								source: 'controlador/trabajador.php?accion=autocompletar&campo=trabCedula&estatus=1',
                                select: function(event, ui) {  
			
                                    $('input[id=nombre]').attr("value",ui.item.nombre);
                                 	$('input[id=apellido]').attr("value",ui.item.apellido);
									$('input[id=correo]').attr("value",ui.item.correo);
									$('input[id=telefono]').attr("value",ui.item.telefono);
							
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
                                minLength: 2, 
                                selectFirst: false,
                                mustMatch: true
                            });
                            jQuery('.ui-autocomplete').css({'font-size':'90%','font-weight':'bold'});

                        }  
                        ,size:10, maxlength: 8,hidedlg:false
					}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ elmsuffix:" (*)"}
                },
					
                {name:'nombre'
                    ,index:'usuNombre'
                    ,width:50
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:30, maxlength: 30,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:2, colpos:1,elmsuffix:" (*)" }
                },
                {name:'apellido'
                    ,index:'usuApellido'
                    ,width:50
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:30, maxlength: 30,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:3, colpos:1,elmsuffix:" (*)" }
                },
                {name:'correo'
                    ,index:'usuCorreo'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:40, maxlength: 40,hidedlg:false}
                    ,editrules:{required:false,edithidden:true,email:true}
                    ,formoptions:{ elmsuffix:""}
                },
				{name:'telefono'
                    ,index:'usuTelefono'
                    ,width:40
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength: 11,hidedlg:false}
                    ,editrules:{required:false,edithidden:true,number:true}
                    ,formoptions:{rowpos:5, colpos:1 }
                },
                {name:'tipo'
                    ,index:'usuTipo'
                    ,width:40
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"select"
                    ,editoptions: {value:"Operador:Operador;Administrador:Administrador"}                    
                    ,editrules:{required:true,edithidden:true}
                },
                {name:'clave'
                    ,index:'usuClave'
                    ,width:50
                    ,align:"center"
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"password"
                    ,editoptions: {size:12, maxlength: 8,hidedlg:false, title:""}
                    ,editrules:{required:false,edithidden:true}
                },
                {name:'clave2'
                    ,index:'usuClave'
                    ,width:50
                    ,align:"center"
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"password"
                    ,editoptions: {size:12, maxlength: 8,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                },
                {name:'estatus'
                    ,index:'usuEstatus'
                    ,width:20
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"checkbox"
                    ,editoptions: {value:"Activo:Inactivo"}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ elmsuffix:"  Activo"}
                }
            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
			//width:850,
            rowList:[10,20,30],
            pager: '#paginador'+entidad,
            caption:"Usuarios del Sistema",
            sortname: 'CAST(usuCedula AS DECIMAL)',
            sortorder: "ASC",
            editurl:'controlador/usuarios.php',
            viewrecords: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 

        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,del:true,refresh:true,searchtext:"Buscar"}, //options
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
            beforeShowForm: function(formid) {
		                
                centrarDialogo('editmodlistado'+entidad,'1000'); //parametros: Id Objeto,z-index               
                
                $("#tr_clave .DataTD:eq(0) span, #tr_clave2 .DataTD:eq(0) span").remove();
                $("#tr_clave .DataTD:eq(0)").append("<span class='msj'> dejar en blanco  si NO deseas cambiar la clave</span>");
                
				var cssRight = { 
					cssStyles:{ color:'#cc0000', width:'150'},
					positions: 'right'
				};
	
				$('#clave',formid).bt('Ingrese entre 4 a 8 car&aacute;cteres alfan&uacute;mericos.!',cssRight);
				
                $('#tr_correo .DataTD:eq(0),#tr_clave .DataTD:eq(0)',formid).attr("colspan","3");
                
				$("#tipo, #clave,#clave2").removeClass("estilo-input");
				$("#tipo, #clave,#clave2").addClass("estilo-input");
                
                $('#cedula',formid).attr("readonly","readonly");
                $('#tr_estatus',formid).show();
                
                $('#cedula,#telefono',formid).attr("onKeyPress","return soloNum(event)");
                $('#nombre,#apellido',formid).attr("onKeyPress","return LetraEspacio(event)");
                
				$('#nombre,#apellido').addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#correo').addClass("minuscula").attr("onblur","this.value=this.value.toLowerCase()");  //Convertir caracteres a minuscula
                
				$('#clave').attr("onKeyPress","return contrasena(event)");
				
                $('#clave,#clave2').val('');
				
				var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
				if(idSel=="admin"){
					$('#tipo',formid).attr("readonly","readonly");
				}

            },	
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                var cedula = $('#cedula').val();
                if(cedula!='admin' && cedula!='demo'){
					var ret = validar_cedula(cedula);
					complete = ret[0];
					message = ret[1];
				}else{
					complete=true;
				}
                    
                if(complete==true){
                    var valor = $('#telefono').val();
                    var ret = validar_telefono(valor);
                    complete = ret[0];
                    message = ret[1];    
                }
                    
                if(complete==true){
                    var valor = $('#clave').val();
                    
                    if(valor!=""){                        
						var ret = validar_contrasena(valor);
						complete = ret[0];
						message = ret[1];    
                        
                        
                        if(complete==true){
                            var clave1 = $('#clave').val();
                            var clave2 = $('#clave2').val();
                            if(clave1!=clave2){
                                complete = false;
                                message = "Las Contrase&ntilde;as no coinciden, por favor verifiquelas.";   
                            }
                         
                        }
		
                    }
                }
                    
               
                
               	
					
                return [complete,message]
					
            },
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Modificada Satisfactoriamente");
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
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,		
            reloadAfterSubmit:true,
            processData: "Agregando...",
            beforeShowForm: function(formid) {
                
                centrarDialogo('editmodlistado'+entidad,'1000'); //parametros: Id Objeto,z-index               
                
				$("#tr_clave .DataTD:eq(0) span,#tr_clave2 .DataTD:eq(0) span").remove();
                
				$("#tr_clave .DataTD:eq(0)").append("<span class='msj'> (*) </span>"+
													"<span style='font-size:10px;color:#747474;'>"+
													"Ingrese de 4 a 8 d&iacute;gitos alfan&uacute;mericos,</span>");
                
				$("#tr_clave2 .DataTD:eq(0)").append("<span class='msj'> (*) </span>");
				
				var cssRight = { 
					cssStyles:{ color:'#cc0000', width:'155'},
					positions: 'right'
				};
	
				$('#clave',formid).bt('Recuerde ingresar entre 4 a 10 car&aacute;cteres alfan&uacute;mericos.',cssRight);
			
			
                $('#tr_correo .DataTD:eq(0)',formid).attr("colspan","3");
                
				$("#tipo, #clave,#clave2").removeClass("estilo-input");
				$("#tipo, #clave,#clave2").addClass("estilo-input");
				
                $('#cedula',formid).removeAttr("readonly");
                $('#tr_estatus',formid).hide();
                $('#cedula,#telefono',formid).attr("onKeyPress","return soloNum(event)");
                $('#nombre,#apellido',formid).attr("onKeyPress","return LetraEspacio(event)");
                
				$('#nombre,#apellido').addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#correo').addClass("minuscula").attr("onblur","this.value=this.value.toLowerCase()");  //Convertir caracteres a minuscula
				
				$('#clave').attr("onKeyPress","return contrasena(event)");
                
						
            },		
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                var valor = $('#cedula').val();
                var ret = validar_cedula(valor);
                complete = ret[0];
                message = ret[1];
                    
                if(complete==true){
                    var valor = $('#telefono').val();
                    var ret = validar_telefono(valor);
                    complete = ret[0];
                    message = ret[1];    
                }
           
                if(complete==true){
                    var valor = $('#clave').val();
                    var ret = validar_contrasena(valor);
					complete = ret[0];
					message = ret[1];    
                }
                    
                if(complete==true){
                    var clave1 = $('#clave').val();
                    var clave2 = $('#clave2').val();
                    if(clave1!=clave2){
                        complete = false;
                        message = "Las Contrase&ntilde;a no coinciden, por favor verifiquelas.";   
                    }
                         
                }
                
               	
					
                return [complete,message]
					
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
             onclickSubmit : function(eparams) {
                var retarr = {};
					
                var id = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
                var ret =jQuery("#listado"+entidad).jqGrid('getRowData',id);	
				
                retarr = {cedula:ret.cedula};
                return retarr; 
            },		
            afterSubmit: function(response, postdata) { 
					
                if (response.responseText == "") {
                    return [true, response.responseText] 
                } 
                else {
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {
			height:"auto",
            width:"auto",
            jqModal:false,
			closeOnEscape:true,
			closeAfterSearch: false
		} // search options
    );
	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+entidad).hide();
	}		
	
	
});
</script>

<span id="rsperrorUsuarios" style="color:red"></span> <br/>
<table id="listadoUsuarios"></table>
<div id="paginadorUsuarios"></div>