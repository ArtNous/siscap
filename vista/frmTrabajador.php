<script type="text/javascript">
    jQuery(document).ready(function(){
        
		var entidad = "Trabajador";
		
        jQuery("#listado"+entidad).jqGrid({
            url:'controlador/trabajador.php',
            datatype: "json",
            colNames:['C&oacute;digo','Cedula','Nombre del Asociado','Nombres','Apellidos','Sexo',
                      'Edo. Civil','Fecha Nacimiento','Nivel Educativo','Profesi&oacute;n','Direcci&oacute;n','Tel&eacute;fono', 'Correo',		  
					  'Fecha Ingreso','Organismo','Organismo','Departamento','Departamento','Cargo','Sueldo',
					  'Estatus','Fecha Egreso','Motivo','Foto','Registrado',''],
				       
            colModel:[
                
				{name:'codigo'
                    ,index:'trabCodigo'
                    ,width:27
                    ,hidden:false
                    ,editable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 8, tabindex:12, hidedlg:true}
                    ,editrules:{required:true,edithidden:true,number:true}
                    ,formoptions:{ rowpos:1, colpos:2, elmsuffix:" (*)"}
                }, 

                {name:'cedula'
                    ,index:'trabajador.trabCedula'
                    ,width:30
                    ,key: true
                    ,hidden:false
                    ,editable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 8, tabindex:1}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:1, colpos:1, elmsuffix:" (*)"}
                },                 
                          
                {name:'nombres'
                    ,index:'trabNombre'
                    ,width:65
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,editrules:{edithidden:true}
                    
                },
                {name:'nombre'
                    ,index:'trabNombre'
                    ,width:55
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:25, maxlength: 30, tabindex:2} //hidedlg:false,
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:2, colpos:1, elmsuffix:" (*)" }
                },
                {name:'apellido'
                    ,index:'trabApellido'
                    ,width:60
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:25, maxlength: 30, tabindex:3}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:3, colpos:1,  elmsuffix:" (*)" }
                },
                {name:'sexo'
                    ,index:'trabSexo'
                    ,width:20
                    ,align:"center"
                    ,hidden:true
                    ,editable: true
                    ,edittype:"select"
                    ,editoptions:{value:":Por favor seleccione;F:Femenino;M:Masculino", class:'estilo-input', tabindex:4}
                    ,editrules:{required:true, edithidden:true}
                    ,formoptions:{rowpos:4, colpos:1, elmsuffix:" (*)"}
                },  

                {name:'edocivil'
                    ,index:'trabEdocivil'
                    ,width:20
                    ,align:"center"
                    ,hidden:true
                    ,editable: true
                    ,edittype:"select"
                    ,editoptions:{tabindex:5, value:" :Por favor seleccione;soltero:Soltero(a);casado:Casado(a);divorsiado:Divorsiado(a);viudo:Viudo(a)", class:'estilo-input'}
                    ,editrules:{required:false, edithidden:true}
                    ,formoptions:{rowpos:5, colpos:1, elmsuffix:""}
                },
                {name:'fechanac'
                    ,index:'trabFechanac'
                    ,width:50
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 10, tabindex:6}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:6, colpos:1,elmsuffix:"" }
                },
                {name:'nivel'
                    ,index:'trabNivel'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: { tabindex:7, 
                        dataInit: function(elem) {
                            $(elem).autocomplete({
                                source: 'controlador/nivel_educativo.php?accion=autocompletar',
                                select: function(event, ui) { 
                                    
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
                             
                        ,size:30, maxlength: 40,hidedlg:false
                    } 
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:7, colpos:1, elmsuffix:"" }
                },
                {name:'profesion'
                    ,index:'trabProfesion'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: { tabindex:8,
                        dataInit: function(elem) {
                            $(elem).autocomplete({
                                source: 'controlador/profesion.php?accion=autocompletar',
                                select: function(event, ui) { 
                                    
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
                             
                        ,size:30, maxlength: 40,hidedlg:false
                    } 
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:8, colpos:1, elmsuffix:"" }
                },
                {name:'direccion'
                    ,index:'trabDireccion'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"textarea"
                    ,editoptions: {cols:45, maxlength: 150, tabindex:9, hidedlg:false}
                    ,editrules:{edithidden:false}
                    ,formoptions:{ rowpos:9, elmsuffix:" "}
                },
               
                {name:'telefono'
                    ,index:'trabTelefono'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 11, tabindex:11, hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:11, colpos:1}
                },
                {name:'correo'
                    ,index:'trabCorreo'
                    ,width:80
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:30, maxlength: 40, tabindex:10, hidedlg:false}
                    ,editrules:{required:false,edithidden:true,email:true}
                    ,formoptions:{rowpos:10}
                },                
                {name:'fechaingreso'
                    ,index:'trabFechaingreso'
                    ,width:35
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:10, maxlength:10, tabindex:13}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:2, colpos:2,elmsuffix:" (*)" }
                },              
                
                 {name:'orgaid'
                    ,index:'organismoId'
                    ,width:10
                    ,hidden:true
                    ,editable:false                    
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:11, tabindex:14, hidedlg:false}
                    ,formoptions:{rowpos:3, colpos:2}
                },
                
                {name:'organismoDescripcion'
                    ,index:'organismoDescripcion'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"select"
                    ,editoptions:{tabindex:15, dataUrl:'controlador/organismo.php?accion=carga_select', class:'estilo-input'}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:3, colpos:2, elmsuffix:" (*)" }
                },
                {name:'depaid'
                    ,index:'departamentoId'
                    ,width:10
                    ,hidden:true
                    ,editable:false                    
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 11, tabindex:16, hidedlg:false}
                    ,formoptions:{rowpos:4, colpos:2}
                },
                {name:'cmbDepartamento'
                    ,index:'departamentoDescripcion'
                    ,width:100
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"select"
                    ,editoptions:{tabindex:17, dataUrl:'controlador/departamento.php?accion=carga_select', class:'estilo-input'}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:4, colpos:2, elmsuffix:" (*)" }
                },
                {name:'cargo'
                    ,index:'trabCargo'
                    ,width:75
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"left"
                    ,editoptions: { tabindex:18,
                        dataInit: function(elem) {
                            $(elem).autocomplete({
                                source: 'controlador/cargos.php?accion=autocompletar',
                                select: function(event, ui) { 
                                    //$('input[id=cargo]').attr("value",ui.item.cargo);  
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
                             
                        ,size:40, maxlength: 80,hidedlg:false
                    } 
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:5, colpos:2, elmsuffix:" (*)" }
                },
                
                {name:'sueldo'
                    ,index:'movimientoSueldoR'
                    ,width:30
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength:17, tabindex:19}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:6,colpos:2, elmsuffix:" (*)"}
                },   
                {name:'estatus'
                    ,index:'trabEstatus'
                    ,width:23
                    ,hidden:true
                    ,editable:true
                    ,align:"center"
                    ,edittype:"checkbox"
                    ,editoptions: {tabindex:20, value:"activo:inactivo"}
                    ,editrules:{required:false,edithidden:false}
                    ,formoptions:{rowpos:7,colpos:2, elmsuffix:" "}
                },
                {name:'fechaegreso'
                    ,index:'trabFechaegreso'
                    ,width:50
                    ,hidden:true
                    ,editable:false
                    ,resizable:true
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 10, tabindex:21}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:8, colpos:2}
                },
                {name:'observacion'
                    ,index:'trabObservacion'
                    ,width:80
                    ,hidden:true
                    ,editable:false
                    ,resizable:true
                    ,edittype:"textarea"
                    ,editoptions: {cols:45, maxlength: 150, tabindex:22, hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:9, colpos:2}
                },
                {name:'foto'
                    ,index:'trabFoto'
                    ,width:33
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"file"
                    ,editoptions: {tabindex:23,hidedlg:false}
                    ,editrules:{edithidden:true}
                    ,formoptions:{rowpos:12,colpos:2, elmsuffix:" "}
                },{name:'fecharegistro'
                    ,index:'trabFecharegistro'
                    ,width:40
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength: 10, tabindex:21}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:10, colpos:2}
                },
                {name:'icono'
                        ,index:'ver'
                        ,width:20
                        ,align:"center"
                        ,hidden:false
                        ,editable:false
                }            
					
            ],
            rowNum:10,
            //autowidth: true,
            width:950,
            height:"auto",
            rowList:[10,20,30,50,100],
            pager: '#paginador'+entidad,
            caption:"Registro de Asociados",
            sortname: 'trabFecharegistro',
            sortorder: "DESC",
            editurl:'controlador/trabajador.php',
            viewrecords: true,
			rownumbers: true,
			grouping:true, 
            groupingView:{ 
					groupField:['organismoDescripcion'], //Agrupar por campo
					groupOrder : ['ASC'], // Ordenar grupo
					groupText : ['<b>{0}</b>'],
					groupColumnShow: [false,false], //mostrar columna --> false: ocultar
					groupCollapse: false // Minimizar por grupo
			},
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
				

        }); 
		
    jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,del:true,view:false,refresh:true,searchtext:"Buscar"}, //options
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
			editCaption:"Modificar Asociado",
            processData: "Modificando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {
				
				$.blockUI({ 
                    theme:     true, 
                    message: "Cargando, Espere Por Favor..." }); 
				
					centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
					
					$('#orgaid,#depaid',formid).hide();
					
					$('#estatus').attr("disabled","true");	
				
					
					$('#orgaid, #depaid',formid).attr("readonly","readonly");
					$('#cedula, #telefono, #codigo').attr("onKeyPress","return soloNum(event)");
					$('#nombre, #apellido, #nivel, profesion, #organismoDescripcion, #cmbDepartamento, #cargo').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
					$('#fechanac,#fechaingreso,#fechaegreso',formid).attr("onKeyPress","return NumGuion(event)");
					
					$('#fechanac,#fechaingreso,#fechaegreso',formid).datepicker({
						changeYear: true, 
						changeMonth: true, 
						dateFormat:"dd-mm-yy",
						maxDate:"+0d"
					});
					
					$('#sueldo',formid).attr("onKeyPress","return NumPunto(event)");
					
					$('#nombre,#apellido,#profesion,#organismoDescripcion, #cmbDepartamento, #cargo',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
					$('#correo').addClass("minuscula").attr("onblur","this.value=this.value.toLowerCase()");  //Convertir caracteres a minuscula
					
					$('#tr_apellido .DataTD:eq(1),#tr_sexo .DataTD:eq(1),#tr_edocivil .DataTD:eq(1),#tr_direccion .DataTD:eq(1),#tr_telefono .DataTD:eq(1)',formid).attr("colspan","5");
						
					$('#organismoDescripcion',formid).keyup(function() {
						if(this.value==''){
							$('#cmbDepartamento',formid).val(''); 
							$('#cmbDepartamento').attr("readonly","readonly");	
						}
						$('#cmbDepartamento',formid).val(''); 						
					});
					
					//Obtener registro seleccionado
					var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
					var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);
					
					//Mostrar foto del trabajador
					var valor = aleatorio(1000); //Actualizar foto en cache
					var archivo = "files/"+idSel+".jpg";
					$('#vistaFoto',formid).remove(); //remover foto actual
					if(file_exists(archivo)){
						archivo = "files/"+idSel+".jpg?"+valor;
						//podemos hacer cualquier cosa con ese fichero, porque sabemos que sí existe.
						$('#tr_profesion .DataTD:eq(1)').attr("rowspan","3");
						$('#tr_profesion .DataTD:eq(1)').append("<div id='vistaFoto' name='"+archivo+"' style='margin-left:10px;float:left;'>"+
																"<a href='"+archivo+"' target='_blank'><img id='imgfoto' src='"+archivo+"' title='Foto Perfil' width='70' height='95' style='border:1px solid black;' /> </a>"+
																"</div> ");
					} 
					
					
					//Carga combo Departamento
					var orgaid =row_data['orgaid'];
					var depaid =row_data['depaid'];
					cargarDepartamento(orgaid,depaid); //cargar combo parametros: idOrganismo , idDepartamento seleccionado
				
					$('#Act_Buttons .navButton').remove(); //remover control siguiente-anterior
					
					
				$.unblockUI();  
					
            },	
			//Antes de envio
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var ret = "";

				var valor = "";
                
				if(!($('#estatus').is(':checked'))){
					message = "No se puede Modificar.! Por concepto de Egreso el Trabajador se encuentra 'Inactivo'. ";
				}else{
					complete =true;
				}
                
                if(complete == true && $('#fechanac').val() != ''){
                    valor = $('#fechanac').val();
                    ret = validar_fecha(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true && $('#telefono').val() != ''){
                    valor = $('#telefono').val();
                    ret = validar_telefono(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true){
                    valor = $('#fechaingreso').val();
                    ret = validar_fecha(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true && $('#organismoDescripcion').val()==""){
                    complete = false;
                    message = "Debe Seleccionar el Organismo adscrito el trabajador";
                }
				
				if(complete == true && $('#cmbDepartamento').val()==""){
                    complete = false;
                    message = "Debe Seleccionar el Departamento adscrito el trabajador";
                }
				
				//validar Sueldo
                if(complete == true){
                    valor = $('#sueldo').val();
					var mascara = /^(\d|-)?(\d|,)*\.?\d*$/;   // el . como separador decimal                    
               
                    if (mascara.test(valor)){
                        complete = true;
                    }else{  
                        complete = false
                        message = 'El Sueldo es incorrecto! ejemplo: 2500.00 ';
                    }
                }
				
				if(complete == true){
					var filename = "foto";
							
					if ($("#"+filename).val() != "" && $("#"+filename).val() != null){
							
						var fileupload = document.getElementById("foto");
						var file = fileupload.files[0];
						var name = file.name;
						var ext = name.substr(name.lastIndexOf('.')); 
						var size = ((file.size)/1024).toFixed(2);
						var ruta = "files/"+$("#cedula").val()+""+ext;
														
						if (ext != '.jpg' && ext != '.JPG') { 
							complete=false;
							message = 'Tipo de Archivo no permitido, solo formato jpg, jpeg &oacute; png. (Foto Carnet).';
						}else if(size > 100){
							complete=false;
							message = 'El archivo supera el peso permitido (100 KB)..! \nPor favor, Reduzca la Imagen a cargar y vuelva a intentarlo.';	
						}else {
							//Subir al directorio la Imagen Frontal
							alerta2("Cargando, Espere Por Favor...");

                            $.ajaxFileUpload({
								url:'include/funciones.php',
								secureuri:false,
								fileElementId:filename,
								dataType: 'json',
								data:{oper:'upload',namefile:filename,id:ruta},
								success: function (data, status) {
									$.unblockUI();
								},
								error: function (data, status, e) {
									complete=false;
									//message=data.error;
                                    $.unblockUI();
								}
							});
									
						}
						
							
					}else{
						complete=true;
					}
				}
				
                return [complete,message];
            },		
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {orgaid:$('#organismoDescripcion').val(),depaid:$('#cmbDepartamento').val()};
                return retarr; 
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
			addCaption:"Registrar Asociado",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {
							
                $.blockUI({ 
                    theme:     true, 
                    message: "Cargando, Espere Por Favor..." }); 
					
					centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
					
					$('#cedula',formid).removeAttr("readonly");
					$('#orgaid,#depaid',formid).hide();
				
				
					$('#orgaid, #depaid, #cmbDepartamento',formid).attr("readonly","readonly");
					$('#cedula, #telefono, #codigo').attr("onKeyPress","return soloNum(event)");
					$('#nombre, #apellido, #nivel, profesion, #organismoDescripcion, #cmbDepartamento, #cargo').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
					$('#fechanac,#fechaingreso,#fechaegreso',formid).attr("onKeyPress","return NumGuion(event)");
					
					$('#fechanac,#fechaingreso,#fechaegreso',formid).datepicker({
						changeYear: true, 
						changeMonth: true, 
						dateFormat:"dd-mm-yy",
						maxDate:"+0d"
					});
					
					$('#sueldo',formid).attr("onKeyPress","return NumPunto(event)");
					
					$('#nombre,#apellido,#profesion,#organismoDescripcion, #departamento, #cargo',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
					$('#correo').addClass("minuscula").attr("onblur","this.value=this.value.toLowerCase()");  //Convertir caracteres a minuscula
					
					$('#tr_apellido .DataTD:eq(1),#tr_sexo .DataTD:eq(1),#tr_edocivil .DataTD:eq(1),#tr_direccion .DataTD:eq(1),#tr_telefono .DataTD:eq(1)',formid).attr("colspan","5");
					
					$('#estatus').attr("checked","true");			
					$('#estatus').attr("disabled","true");			
					
					$('#organismoDescripcion',formid).keyup(function() {
						if(this.value==''){
							$('#departamento',formid).val(''); 
							$('#departamento').attr("readonly","readonly");	
						}  
						$('#departamento',formid).val(''); 
					});
					
					
					$('#vistaFoto',formid).remove();
					
                $.unblockUI();  
            },	//fin del llamado a la funcion	
					
            //Antes de envio
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var ret = "";
				
				var valor = "";
				
                valor = $("#cedula").val();
                ret = validar_cedula(valor);
                complete = ret[0]; 
                message = ret[1];
                
                if(complete == true && $('#fechanac').val() != ''){
                    valor = $('#fechanac').val();
                    ret = validar_fecha(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true && $('#telefono').val()!=""){
                    valor = $('#telefono').val();
                    ret = validar_telefono(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true ){
                    valor = $('#fechaingreso').val();
                    ret = validar_fecha(valor);
                    complete = ret[0];
                    message = ret[1];
                }
				
				if(complete == true && $('#organismoDescripcion').val()==""){
                    complete = false;
                    message = "Debe Seleccionar el organismo adscrito el trabajador";
                }
				
				if(complete == true && $('#cmbDepartamento').val()==""){
                    complete = false;
                    message = "Debe Seleccionar el Departamento adscrito el trabajador";
                }
				
				//validar Sueldo
                if(complete == true){
                    valor = $('#sueldo').val();
                    var mascara = /^(\d|-)?(\d|,)*\.?\d*$/;   // el . como separador decimal
               
                    if (mascara.test(valor)){
                        complete = true;
                    }else{  
                        complete = false
                        message = 'El Sueldo es incorrecto! ejemplo: 2500.00 ';
                    }
                }
				
				
				if(complete==true){
                    var filename = "foto";
						if ($("#"+filename).val() != "" && $("#"+filename).val() != null){
                            var fileupload = document.getElementById(filename);
                            var file = fileupload.files[0];
                            var name = file.name;
                            var ext = name.substr(name.lastIndexOf('.')); 
                            var size = ((file.size)/1024).toFixed(2);
                            var ruta = "files/"+$("#cedula").val()+""+ext;
										
                            if (ext != '.jpg' && ext != '.JPG') { 
                                complete=false;
								message = 'Tipo de Archivo no permitido, solo formato jpg o jpeg.';
                            }else if(size > 100){
                                complete=false;
								message = 'El archivo supera el peso permitido (100 KB)..! \nPor favor, Reduzca la Imagen a cargar y vuelva a intentarlo.';	
                            }else{
												
                                //Subir al directorio la Imagen 
                                alerta2("Cargando, Espere Por Favor...");
                                $.ajaxFileUpload({
                                    url:'include/funciones.php',
                                    secureuri:false,
                                    fileElementId:filename,
                                    dataType: 'json',
                                    data:{oper:'upload',namefile:filename,id:ruta},
                                    success: function (data, status) {
                                        $.unblockUI();
                                        /*if (typeof(data.error) != 'undefined') {
                                            if (data.error != '') {
                                                complete=false;
                                                message=data.error;
                                            } else {
                                                complete=true;
                                                $('#'+filename).val('');
                                            }
                                        }*/
                                    },
                                    error: function (data, status, e) {
                                        complete=false;
										//message=data.error;
                                        $.unblockUI();
                                    }
                                });
								
                            }
									
								
                        }
                }
			
                return [complete,message];
            },
					
            // Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {estatus:'activo',orgaid:$('#organismoDescripcion').val(),depaid:$('#cmbDepartamento').val()};
                return retarr; 
            },
					
            afterSubmit: function(response, postdata) { 
						
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
							                    		
                    //window.open("vistas/rptCarnet.php?cedula="+$('#cedula').val());

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
				
				$('#v_organismoDescripcion span:eq(0),#v_cmbDepartamento span:eq(0)',formid).hide();
				$('#v_organismoDescripcion,#v_cmbDepartamento,#v_cargo',formid).attr('colspan','2');
				$('#trv_telefono .CaptionTD',formid).attr('style','vertical-align:top;');
								
				
            }	
		} // view options
    );
	
	//Cargar Departamento al seleccionar un cmbOrganismo
	$('select[id=organismoDescripcion]').live("change",function(){
		cargarDepartamento(this.value,'');
	});
	
	
	//Funcion para cargar combo Departamento
	function cargarDepartamento(filtro,opcSel){
		$.ajax({                
			url: "controlador/departamento.php",
            data:"oper=carga_select&filtro="+filtro+"&opcSel="+opcSel,
			type: "POST",
            success: function(ret){
				$('#tr_sexo td:eq(3)').empty(); //remover combo departamento
				$('#tr_sexo td:eq(3)').append(ret+" (*)"); //Cargar combo departamento
            }
        });
	}
	
			/*******  Dialogo para Asignar Clave de usuario  ****************************************************************************/
			$("#paginador"+entidad+"_left").attr("style","width:270px");
			
	        jQuery("#listado"+entidad).jqGrid('navButtonAdd','#paginador'+entidad,{
            caption: "Asignar Clave", 
            title: "Asignar Clave", 
            id:"btnClave",
            onClickButton: function (){
				
				///*** Limpiar Campos
				$('#dlgAsignar_clave #msj').hide();
				$('#txtCedula,#txtNombre,#txtClave1,#txtClave2').val('');
				
				///****  Autocompletar datos del trabajador 
				$('#txtCedula').autocomplete({
					source: 'controlador/trabajador.php?accion=autocompletar&campo=trabCedula&estatus=1',
                    select: function(event, ui) {  			
								$('input[id=txtNombre]').attr("value",ui.item.nombre+" "+ui.item.apellido);
                                $('input[id=txtClave1]').attr("value","");
								$('input[id=txtClave2]').attr("value","");
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
                        
				///****  Mostrar dialogo
                $("#dlgAsignar_clave").dialog({ 
                    modal: true,
                    width:350,
                    height:"auto",
                    //hide: 'slide',
					resizable:false,
                    title: 'ASIGNAR CLAVE',
                    closeOnEscape: true,
                    buttons: [
                        {
                            text: "Guardar",
                            click: function() { guardarClaveTrabajador();   }
                        },
                        {
                            text: "Cancelar",
                            click: function() {	 $(this).dialog("close"); }
                        }
                    ]
                });
				
				function guardarClaveTrabajador(){
					var cedula = $('#txtCedula').val();
					var nombre = $('#txtNombre').val();
					var clave1 = $('#txtClave1').val();
					var clave2 = $('#txtClave2').val();
					
					//var mascara = /^([0-9a-zA-Z]{8,10})$/ ; // de 8 a 10 numeros. mascara de Validacion para la clave de usuario
					
					if(cedula==""){
					
						mensaje_error(false,'Debes ingresar la C&eacute;dula de Identidad del Trabajador');
						$('#txtCedula').focus();
						
					}else if(nombre==""){
					
						mensaje_error(false,'Datos del Trabajador incorrectos!!');
						$('#txtCedula').focus();
						
					}else if(clave1==""){
					
						mensaje_error(false,'Debes ingresar la clave para acceder al sistema');
						$('#txtClave1').focus();
						
					}else{ 
					
						valida = validar_contrasena(clave1);								
						if(valida[0]==false){
							mensaje_error(false,'Clave incorrecta.!! m&iacute;nimo de 4 a 8 car&aacute;cteres');
							$('#txtClave1').focus();
								
						}else if(clave2==""){
							
							mensaje_error(false,'Debe ingresar la confirmaci&oacute;n de la clave');
							$('#txtClave2').focus();
								
						}else if(clave1!=clave2){
							
							mensaje_error(false,'Las Claves NO coinciden!!');
							$('#txtClave1,#txtClave2').val('');
							$('#txtClave1').focus();
								
						}else{
							mensaje_error(true,'');
							$("#dlgAsignar_clave").dialog("close");
							alerta('Guardando...');
								
							$.ajax({
								url: "controlador/trabajador.php",
								data:"oper=actualizar_clave&cedula="+cedula+"&clave="+clave1,
								type: "POST",
								success: function(ret){						
										jQuery("#listado"+entidad).trigger("reloadGrid");
										jQuery("#divListado"+entidad).show();
								}
							});
						}
					}
                                
				
				}

          
            }});
			
			$('#dlgAsignar_clave #txtCedula').keyup(function(){
				$('#txtNombre').val('');
			});
			
			var cssRight = { 
					cssStyles:{ color:'#cc0000', width:'170'},
					positions: 'bottom'
			};
	
			$('#dlgAsignar_clave #txtClave1').bt('Debe contener entre 4 a 8 car&aacute;cteres alfan&uacute;mericos.!',cssRight);
			$('#txtCedula').attr("onKeyPress","return contrasena(event)");
			$('#txtClave1').attr("onKeyPress","return contrasena(event)");
			
			/*******  Fin Dialogo para Asignar Clave de usuario  ****************************************************************************/
	
	
	/**   Activar el Boton Eliminar solo para el Usuario Administrador ***/
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+entidad).hide();
	}				
	
});
		
</script>

<span id="rsperrorTrabajador" style="color:red"></span> <br/>
<table id="listadoTrabajador"></table>
<div id="paginadorTrabajador"></div>

<div id="dlgAsignar_clave" name="dlgAsignar_clave" style="display:none; padding:10px auto;">

	<table style='margin: 5px auto; height:190px;  margin-top:1px; '>
		<tr>
			<td id="msj" class="ui-state-error" colspan="3" style='display:none;'>&nbsp;</td>
        </tr>
		<tr>
			<td style="width:100px;" ><b>Cedula Nro.: </b></td>
			<td colspan='2'> <input id="txtCedula" class="estilo-input" type="text" size="10" maxlength="8" style='padding:5px;' onkeyup="this.value=this.value.toUpperCase()"  /> </td>
		</tr>
		<tr>
			<td><b>Nombre:</b> </td>
			<td colspan='2'> <input id="txtNombre" class="estilo-input" type="text" size="25" maxlength="40" style='padding:5px;' readonly  /> </td>
		</tr>
		<tr>
			<td><b>Ingrese Clave:</b> </td>
			<td> <input id="txtClave1" class="estilo-input"  type="password" size="12" maxlength="8" style='padding:5px;' title='' /> </td>
			<td rowspan="2" > <img src="imagenes/key.png" /> </td>
		</tr>
		<tr>
			<td><b>Confirme Clave:</b> </td>
			<td> <input id="txtClave2" class="estilo-input"  type="password" size="12" maxlength="8" style='padding:5px;' title='' /> </td>
		</tr>
		<tr>
			<td colspan="3" >
				<hr />
				<span style='font-size:11px; text-align:justify; color:#666666;'>
				Recuerde cambiar periodicamente su clave, memorizarla y 
				para mayor seguridad NO compartirla con terceras personas.
				<span>
			</td>
			
		</tr>
	</table>
</div>