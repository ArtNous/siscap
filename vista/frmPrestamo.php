<script type="text/javascript">
    jQuery(document).ready(function(){
		
		var entidad = "Prestamos";
		var tipo_usuario = obtener_variable('usuTipo'); 
		var trabced = obtener_variable('trabced');
		
        jQuery("#listado"+entidad).jqGrid({
            url:'controlador/prestamos.php',
            datatype: "json",
            colNames:['C&oacute;digo','Cedula','Empresa','Factura Nro.','Factura Fecha','Concepto','Fecha','Tipo Pr&eacute;stamo','Financiero','% Intereses','Monto','Meses','Cheque a emitir','Cuota','Tipo Descuento','Observaci&oacute;n','Estatus','Fecha','Detalle'],
            colModel:[
                {name:'id'
                    ,index:'prestamoId'
                    ,width:15
                    ,editable:false
                    ,key:true
                    ,hidden:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 3,hidedlg:false}
                    ,formoptions:{ rowpos:1}
                },
                {name:'cedula'
                    ,index:'prestamoTrabCedula'
                    ,width:10
                    ,hidden:true
                    ,editable:false
                    ,resizable:true
                    ,editrules:{edithidden:true}
                },
				{name:'empresa'
                    ,index:'prestamoEmpresa'
                    ,width:15
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    //,editoptions: {size:70, maxlength:70,hidedlg:false}
					,editoptions: { 
                        dataInit: function(elem) {
                            $(elem).autocomplete({
								source: 'controlador/prestamos.php?accion=autocompletarEmpresa',
                                select: function(event, ui) {  
			
                                  //  $('input[id=nombre]').attr("value",ui.item.nombre);
                                 
							
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
                        ,size:70, maxlength:70,hidedlg:false
					}
                    ,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:4, colpos:1, elmsuffix:" " }
                },
				{name:'facturanro'
                    ,index:'prestamoFacturaNro'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:20, maxlength:20,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:3, colpos:1, elmsuffix:" " }
                },
				{name:'facturafecha'
                    ,index:'prestamoFacturaFecha'
                    ,width:16
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:10,hidedlg:false, readonly:true}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:3, colpos:2, elmsuffix:" " }
                },
				{name:'concepto'
                    ,index:'prestamoConcepto'
                    ,width:15
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:80, maxlength:80,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:5, colpos:1, elmsuffix:" " }
                },
				{name:'fechaprest'
                    ,index:'prestamoFecha'
                    ,width:16
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:10,hidedlg:false, readonly:true}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:7, elmsuffix:" (*)" }
                },
				{name:'tipoprest'
                    ,index:'tipoprestNombre'
                    ,width:40
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"select"
                    ,editoptions:{dataUrl:'controlador/tipo_prestamo.php?oper=carga_select_prestamo', class:"estilo-input",
						dataEvents: [
                            {  type: 'change',
                                fn: function(e) {
									//var monto = $('#tipoprest option[value='+this.value+']').attr("monto")									
									//$('#monto').val(monto);
                                }
                            }
                        ]
					}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:6, elmsuffix:" (*)" }
                },
				
				 {name:'financiero'
                    ,index:'prestamoFinanciero'
                    ,width:15
                    ,hidden:true
                    ,editable:true                    
                    ,edittype:"checkbox"
                    ,editoptions: {value:'Si:Si:No:No',hidedlg:false,  class:"FormElement estilo-input",
						dataEvents: [
                            {  type: 'click',
                                fn: function(e) {
                                    if(this.checked==true){
										$('#lblPorcentaje, #tr_meses, #tr_monto td:eq(2),#tr_monto td:eq(3)').show(); //Meses, Cheque , Porcentaje
										$('#meses,#cheque').val('');
										
										$.ajax({
											url: "controlador/configuracion.php",
											data:"oper=consultar",
											type: "POST",
											async:false,
											cache:false,
											dataType: "json",
											success: function(ret){
												$("#porcentaje").val(ret[0].intereses);
											}
										});
										
										calcularMontoCheque();
								   
										$("#tipodesc").val('Bs.');
										$("#tipodesc, #cheque").attr("readonly","readonly");
										
									}else{
										$('#lblPorcentaje, #tr_meses, #tr_monto td:eq(2),#tr_monto td:eq(3)').hide(); //Meses, Cheque , Porcentaje
										$('#meses,#cheque').val('');
										$("#porcentaje").val('');
										$("#tipodesc").removeAttr("readonly");
									}
									
                                }
                            }
                        ]
					}
					,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:6, colpos:2, elmsuffix:" Financiero  &nbsp; &nbsp; &nbsp; <span id='lblPorcentaje'><input id='porcentaje' type='text' size='4' value='' class='FormElement estilo-input' style='text-align:center;' /> % Intereses </span> " }                
				},
				
				
				{name:'intereses'
                    ,index:'prestamoIntereses'
                    ,width:15
                    ,hidden:true
                    ,editable:false                    
                    ,edittype:"text"
                    ,editoptions: {size:10, maxlength:12,hidedlg:false, disabled:true }
					,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:6, colpos:3 }
                },
				
				{name:'monto'
                    ,index:'prestamoMonto'
                    ,width:15
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:15, maxlength:10,hidedlg:false,
						dataEvents: [
                            {  type: 'change',
                                fn: function(e) {
								
									calcularMontoCheque();
								   
                                }
                            }
                        ]
					}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:7, colpos:2,  elmsuffix:" (*)" }
                },
				
				{name:'meses'
                    ,index:'prestamoMeses'
                    ,width:15
                    ,hidden:false
                    ,editable:true      
					,align:"center"					
                    ,edittype:"select"
                    ,editoptions: {dataUrl:"controlador/configuracion.php?oper=carga_select", hidedlg:false,  class:"FormElement estilo-input",
						dataEvents: [
                            {  type: 'change',
                                fn: function(e) {
                                   calcularMontoCheque();
                                }
                            }
                        ]
					}
					,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:8, colpos:1, elmsuffix:" (*) &nbsp; <a id='consultarDetalle' href='javascript:void(0)' style='font-weight:bold; font-size:11px; color:#0C1889; text-decoration: blink; ' >Consultar Cuotas</a>" }
                },
				
				{name:'cheque'
                    ,index:'prestamoCheque'
                    ,width:15
                    ,hidden:true
                    ,editable:true                    
                    ,edittype:"text"
                    ,editoptions: {size:15, maxlength:15,hidedlg:false, readonly:true, style:"font-size:16px; font-weight:bold; text-align:center; color:red;"}
					,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:8, colpos:2, elmsuffix:" (*)" }
                },
				{name:'cuota'
                    ,index:'prestamoCuota'
                    ,width:12
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:15, maxlength: 10,hidedlg:false,title:'Cuota del Prestamo'}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:9}
				},
				{name:'tipodesc'
                    ,index:'prestamoTipodesc'
                    ,width:6
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"select"
                    ,editoptions: {value:"Bs.:Bs.;%:%;ND:ND", class:"FormElement estilo-input"}                    
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:9, colpos:1, elmsuffix:" (*) <span class='msj'> Cuota mensual del sueldo.<span>" }
                },
				{name:'observacion'
                    ,index:'prestamoObservacion'
                    ,width:15
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"textarea"
                    ,editoptions: {cols:80, rows:3, maxlength:200,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
					,formoptions:{ rowpos:10, elmsuffix:" " }
                },
				{name:'estatus'
                    ,index:'prestamoEstatus'
                    ,width:15
                    ,align:"center"
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,edittype:"select"
                    ,editoptions: {value:"Pendiente:Pendiente;Liquidado:Liquidado;Anulado:Anulado", class:"FormElement estilo-input"} 
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:11}
                },
				{name:'fechaestatus'
                    ,index:'prestamoFechaestatus'
                    ,width:15
                    ,align:"center"
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,edittype:"text"
					,editoptions: {size:12, maxlength: 10}
					,formoptions:{ rowpos:12}
                },
				{name:'detalle'
						,index:'prestamoId'
						,width:9
						,align:"center"
						,hidden:false
						,editable:false
				}
                
                                        
            ],
            rowNum:10,
            autowidth: true,
			//width:685,
            height:"auto",
            rowList:[10,20,30,50],
            pager: '#paginador'+entidad,
            caption:"Tramitar Pr&eacute;stamos",
            sortname: 'prestamoFecha',
            sortorder: "DESC",
            editurl:'controlador/prestamos.php',
            viewrecords: true,
			rownumbers: true,	
			afterInsertRow: function(rowid, aData){ 
				switch (aData.estatus) { 
					case 'Pendiente': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'red'}); break; 
					case 'Liquidado': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'#004276'}); break; 
					case 'Anulado': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'#666666'}); break; 
				} 
			},
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
		
		jQuery("#listado"+entidad).jqGrid('setGroupHeaders', { 
			useColSpanStyle: false, 
			groupHeaders:[				
				{startColumnName: 'fechaprest', numberOfColumns: 4, titleText: '<em>Datos del Prestamo</em>'},
				{startColumnName: 'meses', numberOfColumns: 4, titleText: '<em>Descuento por:</em>'}, 
				{startColumnName: 'estatus', numberOfColumns: 2, titleText: '<em>Liquidaci&oacute;n</em>'} 
			] 
		});
		
        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,del:true,view:true,refresh:true,searchtext:"Buscar"}, //options
        { //opcion Editar
            height:"auto",
            width:"auto",
             closeAfterEdit: true,
            caption: "Modificar",
            closeOnEscape:true,
            //modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,		
            reloadAfterSubmit:true,
            editCaption:"Modificar Pr&eacute;stamo",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid,rowid) {
             
               centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#tr_tipoprest .CaptionTD:eq(1)').remove();
				$('#tr_tipoprest .DataTD:eq(1)').attr("style","padding-top:7px;");
				$('#tr_tipoprest .DataTD:eq(1)').attr("colspan","2");
				
				var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
				var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);
				var intereses =row_data.intereses;
				$("#porcentaje").val(intereses);
				
				$("#porcentaje").keyup(function(){
					calcularMontoCheque();
				});
				
				var financiero = row_data.financiero;
				if(financiero=='Si'){					
					$('#lblPorcentaje, #tr_meses, #tr_monto td:eq(2),#tr_monto td:eq(3)').show(); //Meses, Cheque , Porcentaje
				}else{
					$('#lblPorcentaje, #tr_meses, #tr_monto td:eq(2),#tr_monto td:eq(3)').hide(); //Meses, Cheque , Porcentaje
				}
				
				$('#facturanro').attr("onKeyPress","return sinEspacio(event)");
				$('#facturafecha,#fechaprest,#fechaestatus',formid).attr("onKeyPress","return NumGuion(event)");
				$('#facturafecha,#fechaprest,#fechaestatus',formid).datepicker({
					changeMonth: true, 
                    changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				$('#cuota,#monto',formid).attr("onKeyPress","return NumPunto(event)");
				
				$('#tr_empresa td:eq(1), #tr_concepto td:eq(1), #tr_observacion td:eq(1), #tr_cuota td:eq(1)',formid).attr("colspan","3");				
				$('#facturanro,#empresa,#concepto,#observacion',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				
				//mostrar campo Estatus y Fecha a solo los Administradores
				if(tipo_usuario=="Administrador"){
					$('#tr_estatus,#tr_fechaestatus').show();
				}else{
					$('#tr_estatus,#tr_fechaestatus').hide();
				}

            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";
				var valor = "";
				var porcentaje = /^(\d){1,3}(.\d{2}$)?/ ; //  de 1 a 3 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				
				
                if($('#tipoprest').val()==""){                
                    complete = false;
                    message = "Debes seleccionar el Tipo de Prestamo";
                }


                if(complete==true){
                    valor = $('#fechaprest').val();
                    ret = validar_fecha(valor);
                    complete = ret[0];
                    message = ret[1];

                    var fecha_ingreso = $('#trabFechai').val();
                    if (comprobarFechaMayor(fecha_ingreso,valor)){
                        complete = false;
                        message = 'La fecha del Prestamo debe no puede ser antes de la fecha Ingreso '+fecha_ingreso;
                    }

                }
                
				
				var cuota = $('#cuota').val();                
				var tipo = $('#tipodesc').val();
                
				if(complete==true){
					if(tipo=="%"){
						if (porcentaje.test(cuota)){
							complete = true;
						}else{  
							complete = false
							message = 'Porcentaje Cuota incorrecto!! ejemplo: 100.00 &oacute; 2.00 ';
						}
					}
					
					if(tipo=="Bs."){
						if (decimal.test(cuota)){
							complete = true;
						}else{  
							complete = false
							message = 'Monto Cuota incorrecto!! solo 2 decimales, ejemplo: 4500.00 ';
						}
					}
					
					if(tipo=="ND"){
						$('#cuota').val('');
						complete = true;
					}
               
				}
				
							
				if(complete==true){
					if( (tipo=="Bs." || tipo=="%") && cuota<=0 ){
						complete = false;
						message = 'La cuota no puede ser menor o igual a 0';
					}
				}
				
				if(complete==true){
					if( $("#financiero").is(':checked') && $("#meses").val()=='' ){
						complete = false;
						message = 'Debe seleccionar los meses a cancelar';
					}
				}
				
				return [complete,message]
				
            },	
			onclickSubmit : function(eparams) {
                 if( $("#financiero").is(':checked')){
					var financiero = 'Si';
				}else{
					var financiero = 'No';
				}
				
				var retarr = {};
                retarr = {tipoprest:$('#tipoprest').val(), porcentaje:$('#porcentaje').val(), financiero:financiero};
                return retarr; 
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
        }, // Fin opcion Editar
        {  // opcion añadir
            height:"auto",
            width:"auto",
            closeAfterAdd: true,
            closeOnEscape:true,
            //modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,	
            reloadAfterSubmit:true,
            addCaption:"Registrar Pr&eacute;stamo",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
				centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#tr_tipoprest .CaptionTD:eq(1)').remove();
				$('#tr_tipoprest .DataTD:eq(1)').attr("style","padding-top:7px;");
				$('#tr_tipoprest .DataTD:eq(1)').attr("colspan","2");
				
				//Ocultar campos Meses y Cheque a emitir
				$('#lblPorcentaje, #tr_meses, #tr_monto td:eq(2),#tr_monto td:eq(3)').hide(); //Meses, Cheque, Porcentaje

				$('#facturanro').attr("onKeyPress","return sinEspacio(event)");
				$('#facturafecha,#fechaprest,#fechaestatus',formid).attr("onKeyPress","return NumGuion(event)");
				$('#facturafecha,#fechaprest,#fechaestatus',formid).datepicker({
					changeMonth: true, 
                    changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				$('#cuota,#monto',formid).attr("onKeyPress","return NumPunto(event)");
				$('#tr_empresa td:eq(1), #tr_concepto td:eq(1), #tr_observacion td:eq(1), #tr_cuota td:eq(1)',formid).attr("colspan","3");				
				$('#facturanro,#empresa,#concepto,#observacion',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#tr_estatus,#tr_fechaestatus').hide();
             
            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";
				var valor = "";
				var porcentaje = /^(\d){1,3}(.\d{2}$)?/ ; //  de 1 a 3 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				
				
                if(complete==true){
					valor = $('#fechaprest').val();
					ret = validar_fecha(valor);
					complete = ret[0];
					message = ret[1];

                    var fecha_ingreso = $('#trabFechai').val();
					//console.log('validacion de fecha prestamo '+valor+' fecha ingreso: '+fecha_ingreso);
                    if (comprobarFechaMayor(fecha_ingreso,valor)){
                        complete = false;
                        message = 'La fecha del Prestamo debe no puede ser antes de la fecha Ingreso ';
                    }

				}
				

                if($('#tipoprest').val()==""){                
                    complete = false;
                    message = "Debes seleccionar el Tipo de Prestamo";
                }


				var cuota = $('#cuota').val();                
				var tipo = $('#tipodesc').val();
				
				if(complete==true){
					if(tipo=="%"){
						if (porcentaje.test(cuota)){
							complete = true;
						}else{  
							complete = false;
							message = 'Porcentaje Cuota incorrecto!! ejemplo: 100.00 &oacute; 2.00 ';
						}
					}
					
					if(tipo=="Bs."){
						if (decimal.test(cuota)){
							complete = true;
						}else{  
							complete = false;
							message = 'Monto Cuota incorrecto!! solo 2 decimales, ejemplo: 4500.00 ';
						}
					}
					
					if(tipo=="ND"){
						$('#cuota').val('');
						complete = true;
					}
               
				}
				
				if(complete==true){
					if( (tipo=="Bs." || tipo=="%") && cuota<=0 ){
						complete = false;
						message = 'La cuota no puede ser menor o igual a 0';
					}
				}
				
				
				if(complete==true){
					if( $("#financiero").is(':checked') && $("#meses").val()=='' ){
						complete = false;
						message = 'Debe seleccionar los meses a cancelar';
					}
				}
				
				return [complete,message]
				
            },
						// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                if( $("#financiero").is(':checked')){
					var financiero = 'Si';
				}else{
					var financiero = 'No';
				}
				
				var retarr = {};
                retarr = {cedula:$('#trabCedula').val(), tipoprest:$('#tipoprest').val(), porcentaje:$('#porcentaje').val(), financiero:financiero};
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
        }, // fin options Añadir
        { // opcion Eliminar
            width:"auto",
			height:"auto",
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
        }, // fin options Eliminar 

       {
			height:"auto",
            width:"auto",
            jqModal:false,
			closeOnEscape:true,
			closeAfterSearch: false
		}, // search options
		
		{
			height:"auto",
            width:"auto",
			beforeShowForm: function(formid) {
				$('table',formid).removeAttr('style');
				$('.CaptionTD,.DataTD',formid).removeAttr('width');
				$('.CaptionTD',formid).attr("style","width:auto");
				$('.DataTD',formid).attr("style","width:auto");
				//centrarDialogo('viewmodlistado'+entidad,'1000'); //parametros: Id Objeto,z-index              
			
            }	
		} // view options
    );
		
		
		//Ver detalle de Caja de Ahorro
	
	$("a[class='btnDetalle']").live("click",function(id){             
		$.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:"enable"});
			var maintab =jQuery('#tabs').tabs({
							add: function(e, ui) {
								$(ui.tab).parents('li:first')
								.append('<li><span class="ui-tabs-close ui-icon ui-icon-close " title="Close Tab" ></span></li>')
								.find('span.ui-tabs-close')
								.click(function() {  $("#tabs").tabs('remove', '#' + ui.panel.id); 	});

								$("#tabs").tabs('select', '#' + ui.panel.id);
							}
						 });
				 
			var idSel = jQuery('#listado'+entidad).jqGrid('getGridParam','selrow'); //idseleccionado		
			var row_data = $('#listado'+entidad).jqGrid('getRowData',idSel);
            var st = "#tabs"+idSel;
			
			if($(st).html() != null ) {
				$("#tabs").tabs('select',st);
			}else{
				$("#tabs").tabs('add',st,idSel);

                $.ajax({
					url: "vista/frmPrestamo_detalle.php",
					data:{
						filtro		: idSel,
						codigo 		: row_data.id,
						fechaprest 	: row_data.fechaprest,
						tipoprest 	: row_data.tipoprest,
						monto 		: row_data.monto,
						cuota		: row_data.cuota,
						tipodesc 	: row_data.tipodesc,
						estatus 	: row_data.estatus,
						fechaE 		: row_data.fechaestatus
					},
					type: "GET",
                    dataType: "html",
                    complete : function (req, err) {
                            $(st,"#tabs").append(req.responseText);
                            try { var pageTracker = _get._getTracker("UA-5463047-4"); pageTracker._trackPageview(); } catch(err) {};
                    }

                 }); 

            }

	}); //Fin Ver detalle
		
		function calcularMontoCuota(){
			var monto = $("#monto").val();
			var meses = $("#meses").val();					   
			if(meses!='' && monto!=''){
				meses=meses*1;
				monto = monto*1;
				var cuota = monto/meses;
				$("#cuota").val(cuota.toFixed(2));
			}
		}
		
		function calcularMontoCheque(){
			var monto= $("#monto").val();
			var meses= $("#meses").val();
			var porcentaje= $("#porcentaje").val();
			
			if(monto!='')
				monto=parseFloat(monto);
			if(meses!='')
				meses=parseInt(meses);
			if(porcentaje!='')
				porcentaje=parseFloat(porcentaje);
			
			
			if(meses!='' && monto!=''){
				var cuota = (monto/meses).toFixed(2);
				$("#cuota").val(cuota);
			}
			
			$("#dlgDetalleprestamo tbody").empty();
			
			if(porcentaje!='' && porcentaje!=0 && monto!='' && meses!='' && cuota!='' && cuota!=0 ){
				
				var interes = 0;
				var totalinteres = 0;
				var montoinicial=parseFloat(monto).toFixed(2);
				var saldo = monto;
				
				for($i=1;$i<=meses;$i++){
					montoinicial=saldo;
					interes=(saldo*porcentaje/100).toFixed(2);
					totalinteres=parseFloat(totalinteres)+parseFloat(interes);
					saldo=parseFloat(saldo-cuota).toFixed(2);
										
					$("#dlgDetalleprestamo tbody").append("<tr style='background-color:#F5F5F5;' ><td>"+$i+"</td><td>"+montoinicial+"</td><td>"+cuota+"</td><td>"+interes+"</td><td>"+saldo+"</td></tr>");
					//console.log("Monto: "+monto+" Cuota: "+cuota+" interes: "+interes+" saldo: "+saldo);
					
				}
				
				var montoCheque = monto-totalinteres;
				$("#cheque").val(montoCheque.toFixed(2));
				
				$("#dlgDetalleprestamo tbody").append("<tr class='ui-state-default ui-th-column ui-th-ltr' style='height:20px;' ><td></td><td></td><td></td><td>"+totalinteres+"</td><td></td></tr>");
				//console.log("Meses: "+meses+" Total Intereses: "+totalinteres+" Monto Cheque: "+montoCheque);
				
				
			}else{
				$("#cheque").val('');
			}
		}
		
		
		$("a[id='consultarDetalle']").live("click",function(){
			
			calcularMontoCheque();
			
			var porcentaje= $("#porcentaje").val();
			
			$("#dlgDetalleprestamo #trTitulo").empty();
			$("#dlgDetalleprestamo #trTitulo").append("PR&Eacute;STAMO POR CUOTAS EN BASE AL "+porcentaje+" %");
			
			$("#dlgDetalleprestamo").dialog({ 
							modal: true,
							width:550,
							height:400,
							zIndex:9999,
							title: 'Detalle del Pr&eacute;stamo',
							closeOnEscape: true,
							buttons: [
								{
									text: "Cerrar",
									click: function() {	
										$(this).dialog("close");
									}
								}
							]
			});
		});
		
		
		$('#tipodesc').live("click",function(){
			if(this.value=="ND"){
				$('#cuota').val('');
				$('#cuota').attr("readonly","readonly")
			}else{
				$('#cuota').removeAttr("readonly");
			}
		}); 
		
		
		//Imprimir Estado de Cuenta del trabajador
		$('#btnEdoCuenta').click(function(){
			var trabced = obtener_variable('trabced');	
			window.open("vista/rptEstadoCuenta.php?cedula="+trabced);
		});
		
		//Imprimir Listado de Prestamos del Trabajador
		$('#btnImprimir').click(function(){
			var trabced = obtener_variable('trabced');	
			window.open("vista/rptPrestamos.php?cedula="+trabced);
			
		});
		
		verificartipousuario(tipo_usuario,entidad);
		jQuery("#paginador"+entidad+"_left, #paginador"+entidad+"_center").hide();
		
    });
</script>

<div style='margin-left:-10px;'>
	<span id="rsperrorPrestamos" style="color:red"></span> <br/>
	<table id="listadoPrestamos"></table>
	<div id="paginadorPrestamos"></div>
</div>

<div style='float:left; margin-top:15px;'>			
	<a id='btnEdoCuenta' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Consultar Estado Cuenta <span class='ui-icon ui-icon-print'></span></a>
</div>		

<div style='text-align:right; margin-top:15px;'>			
	<a id='btnImprimir' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Relaci&oacute;n de Pr&eacute;stamos <span class='ui-icon ui-icon-print'></span></a>
</div>		

<div id="dlgDetalleprestamo" style='display:none;' >			
	<table width='510' style='text-align:center;' >
		<thead>
			<th id='trTitulo' class="ui-state-default ui-th-column ui-th-ltr" colspan='5' style='height:20px;' > PR&Eacute;STAMO DETALLADO POR CUOTAS</th>
		</thead>
		<thead>
			<th class="ui-state-default ui-th-column ui-th-ltr" >Mes</th>
			<th class="ui-state-default ui-th-column ui-th-ltr" >Monto (Bs.)</th>
			<th class="ui-state-default ui-th-column ui-th-ltr" >Cuota (Bs.)</th>
			<th class="ui-state-default ui-th-column ui-th-ltr" >Inter&eacute;s (Bs.)</th>
			<th class="ui-state-default ui-th-column ui-th-ltr" >Saldo (Bs.)</th>
		</thead>
        <tbody> <!-- Mostrar Detalle  -->  	</tbody>
	</table>
</div>