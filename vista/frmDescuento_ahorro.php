<script type="text/javascript">
    jQuery(document).ready(function(){
			
		var entidad = "DescuentoAhorro";
		var tipo_usuario = obtener_variable('usuTipo'); 
		var trabced = obtener_variable('trabced');
		
        jQuery("#listado"+entidad).jqGrid({
            url:'controlador/descuento_ahorro.php',
            datatype: "json",
            colNames:['Id','Cedula','<b> Tiene un Saldo de </b>','Concepto','Fecha','Tipo','Monto','Estatus','Fecha Anulaci&oacute;n','Operador',''],
            colModel:[
                {name:'id'
                    ,index:'descahorroId'
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
                    ,index:'descahorroTrabCedula'
                    ,width:10
                    ,hidden:true
                    ,editable:false
                    ,resizable:true
                    ,editrules:{edithidden:true}
                },
				{name:'saldo'
                    ,index:'descahorroConcepto'
                    ,width:20
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:10, maxlength:20,hidedlg:false, style:'text-align:center; color:#D30111; font-size:14px; font-weight:bold; '}
                    ,editrules:{required:false,edithidden:true}
					,formoptions:{ elmsuffix:" <span style='color:#757575; '> Bs. disponible en la Caja de Ahorro </span> " }
                },
				
				{name:'concepto'
                    ,index:'descahorroConcepto'
                    ,width:45
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:70, maxlength:100,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:5, colpos:1, elmsuffix:" (*)" }
                },
				{name:'fechadesc'
                    ,index:'descahorroFecha'
                    ,width:14
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:10,hidedlg:false, readonly:true}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:7, elmsuffix:" (*)" }
                },
				{name:'tipo'
                    ,index:'descahorroTipo'
                    ,width:12
                    ,hidden:false
                    ,editable:true
                    ,align:"center"
                    ,edittype:"select"
                    ,editoptions:{value:"Descuento:Descuento de Ahorro;Abono:Abono o Reintegro de Ahorros", class:"FormElement estilo-input" }
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:4, elmsuffix:" (*)" }
                },
                {name:'monto'
                    ,index:'descahorroMonto'
                    ,width:13
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:10, maxlength:9,hidedlg:false, style:"font-weight:bold; font-size:14px; text-align;center;"}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:8, elmsuffix:" (*)" }
					,sorttype:"float", formatter:"number", summaryType:'sum'
                },
			
				{name:'estatus'
                    ,index:'descahorroEstatus'
                    ,width:14
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"select"
                    ,editoptions: {value:"Procesado:Procesado;Anulado:Anulado", class:"FormElement estilo-input" } 
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:11}
                },
				{name:'fechaestatus'
                    ,index:'descahorroFechaestatus'
                    ,width:15
                    ,align:"center"
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
					,editoptions: {size:12, maxlength: 10}
					,formoptions:{ rowpos:12}
                },
				{name:'operador'
                    ,index:'descahorroUsuCedula'
                    ,width:35
                    ,align:"center"
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,edittype:"text"
					,editoptions: {size:12, maxlength: 10}
				},
				{name:'detalle'
						,index:'prestamoId'
						,width:5
						,align:"center"
						,hidden:false
						,editable:false
				}
                
                                        
            ],
            rowNum:10,
            //autowidth: true,			
            width:870,
            height:"auto",
            rowList:[10,20,30,50],
            pager: '#paginador'+entidad,
            caption:"Relaci&oacute;n de Abonos &oacute; Descuentos de Caja de Ahorro",
            sortname: 'descahorroFecha',
            sortorder: "DESC",
            editurl:'controlador/descuento_ahorro.php',
            viewrecords: true,
			rownumbers: true,	
			afterInsertRow: function(rowid, aData){ 
				switch (aData.estatus) { 
					case 'Procesado': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'#004276'}); break; 
					case 'Anulado': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'red'}); break; 
				} 
			},
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
		
		
		
        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:true,add:true,del:true,view:false,refresh:true,searchtext:"Buscar"}, //options
        { //opcion Editar
            height:"auto",
            width:"auto",
             closeAfterEdit: true,
            caption: "Modificar",
            closeOnEscape:true,
            modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,		
            reloadAfterSubmit:true,
            editCaption:"Modificar Abono o Descuento Ahorro",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid,rowid) {
             
               // centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
				var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);
					
				var saldo = consultarSaldo($('#trabCedula').val())*1;
				var monto =row_data.monto*1;
			
				if(row_data.estatus=="Procesado")
					$('#saldo',formid).val(saldo+monto);
				
				if(row_data.estatus=="Anulado")
					$('#saldo',formid).val(saldo);
				
				
				$('#saldo',formid).attr("readonly","readonly")
				
				$('#fechadesc,#fechaestatus',formid).attr("onKeyPress","return NumGuion(event)");
				$('#fechadesc,#fechaestatus',formid).datepicker({
					changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				$('#monto',formid).attr("onKeyPress","return NumPunto(event)");
				
				$('#tr_concepto td:eq(1)',formid).attr("colspan","3");				
				$('#concepto',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				
				//mostrar campo Estatus y Fecha a solo los Administradores
				if(tipo_usuario=="Administrador"){
					$('#tr_estatus,#tr_fechaestatus').show();
				}else{
					$('#tr_estatus,#tr_fechaestatus').hide();
				}
							
				$('#concepto',formid).focus();
				
            },	
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var valor = "";				
				var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				
			
				valor = $('#fechadesc').val();
				ret = validar_fecha(valor);
				complete = ret[0];
				message = ret[1];
				
                
				if(complete==true){
					if (decimal.test($('#monto').val())){
							complete = true;
					}else{  
						complete = false;
						message = 'Monto Cuota incorrecto!! solo 2 decimales, ejemplo: 4500.00 ';
					}
				}
				
				if(complete==true){
					if ($('#monto').val()<=0){
						complete = false;
						message = 'Monto Cuota incorrecto!! debe ser mayor a cero (0.00) ';
					}
				}
				
				if(complete==true && $('#estatus').val()!='Anulado' ){
					var tipo = $('#tipo').val();
					var saldo = $('#saldo').val() * 1;
					var monto = $('#monto').val() * 1;
					if(monto>saldo && tipo == 'Descuento' ){
						complete = false;
						message = 'Monto a solicitar NO puede ser mayor al Saldo disponible: '+saldo;
					}
				}
				
				return [complete,message]
				
            },	
			onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {tipoprest:$('#tipoprest').val()};
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
            modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit : true,	
            reloadAfterSubmit:true,
            addCaption:"Registrar Abono o Descuento Ahorro",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
				//centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
				var saldo = consultarSaldo($('#trabCedula').val());
				$('#saldo',formid).val(saldo);
				$('#saldo',formid).attr("readonly","readonly")
				
				$('#fechadesc,#fechaestatus',formid).attr("onKeyPress","return NumGuion(event)");
				$('#fechadesc,#fechaestatus',formid).datepicker({
					changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				$('#monto',formid).attr("onKeyPress","return NumPunto(event)");
				
				$('#tr_concepto td:eq(1)',formid).attr("colspan","3");				
				$('#concepto',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				
				$('#tr_estatus,#tr_fechaestatus').hide();
				
				$('#concepto',formid).focus();
				
            },	
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
				var valor = "";
				var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
				
				
                valor = $('#fechadesc').val();
				ret = validar_fecha(valor);
				complete = ret[0];
				message = ret[1];
				
                
				if(complete==true){
					if (decimal.test($('#monto').val())){
							complete = true;
					}else{  
						complete = false;
						message = 'Monto Cuota incorrecto!! solo 2 decimales, ejemplo: 4500.00 ';
					}
				}
				
				if(complete==true){
					if ($('#monto').val()<=0){
						complete = false;
						message = 'Monto Cuota incorrecto!! debe ser mayor a cero (0.00) ';
					}
				}
				
				if(complete==true){
					var tipo = $('#tipo').val();
					var saldo = $('#saldo').val() * 1;
					var monto = $('#monto').val() * 1;
					if(monto>saldo && tipo == 'Descuento' ){
						complete = false;
						message = 'Monto a solicitar NO puede ser mayor al Saldo disponible: '+saldo;
					}
				}
				
				return [complete,message]
				
            },
						// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {cedula:$('#trabCedula').val(), tipoprest:$('#tipoprest').val()};
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

        {} // search options
    );
		
		
		//Ver detalle de Caja de Ahorro
	
	$('#listado'+entidad).delegate( "a[class='btnImprimir']", 'click', function(){			
		//abrirVentana("vista/rptComprobanteDescuentoahorro.php?id="+this.name,'Comprobante por Descuento de Caja de Ahorro',800,600);
		window.open("vista/rptComprobanteDescuentoahorro.php?id="+this.name);

	}); //Fin Ver detalle
		
		
		//Consultar Saldo Caja de Ahorro
		function consultarSaldo(cedula){
			var saldo;
			//alerta("Espere por favor, consultando Saldo...");
			$.ajax({
				url: "controlador/descuento_ahorro.php",
				data:"oper=consultarSaldo&cedula="+cedula,
				type: "POST",
				async:false,
				cache:false,
				success: function(ret){
					saldo = ret;
					$.unblockUI();  
				}
			});
			return saldo;
		}

		verificartipousuario(tipo_usuario,entidad);
		//comprobar_paginador(trabced,entidad);
		jQuery("#paginador"+entidad+"_left, #paginador"+entidad+"_center").hide();
		
    });
</script>

<div >
	<span id="rsperrorDescuentoAhorro" style="color:red"></span> <br/>
	<table id="listadoDescuentoAhorro"></table>
	<div id="paginadorDescuentoAhorro"></div>
</div>		