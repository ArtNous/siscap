<script type='text/javascript'>  
jQuery(document).ready(function(){
	$("div[id^='tabs']").tabs({
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html("Documento no encontrado.!" );
            }
        }
    });
});
</script>	

<script type="text/javascript">
    jQuery(document).ready(function(){

		var $entidad = "Liquidacion";
		jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/liquidacion.php',
            datatype: "json",
            colNames:['C&oacute;digo','C&oacute;digo','C&oacute;digo','Desde','Hasta','Estatus','Fecha','Total Bs.',''],
            colModel:[
			
				{name:'codigo'
                    ,index:'liquidacionCodigo'
					,width:20
					,key: true
                    ,hidden:false
                    ,editable:true
                    ,align:"left"
                    ,edittype:"text"
					,editoptions: {size:10, maxlength:10, readonly:true, hidedlg:false}
                    ,editrules:{edithidden:true}
                },
				{name:'ano'
                    ,index:'cajahorroId'
                    ,hidden:true
                    ,editable:true
                    ,align:"center"
                    ,edittype:"text"
					,editoptions: {size:4, maxlength:4, readonly:true, hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:2,colpos:1, elmsuffix:" - " }
                },	
				{name:'mes'
                    ,index:'cajahorroId'
                    ,width:25
                    ,hidden:true
                    ,editable:true
					,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:":Mes;01:01;02:02;03:03;04:04;05:05;06:06;07:07;08:08;09:09;10:10;11:11;12:12", class:'estilo-input', hidedlg:false}                    
					,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:2,colpos:1, elmsuffix:""} 
                },				
				{name:'desde'
                    ,index:'liquidacionDesde'
                    ,width:25
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:12, readonly:true, hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:3,colpos:1, elmsuffix:" " }
                },				
				{name:'hasta'
                    ,index:'liquidacionHasta'
                    ,width:25
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:12, maxlength:12, readonly:true, hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:3,colpos:2,  elmsuffix:" " }
                },	
				{name:'estatus'
                    ,index:'liquidacionEstatus'
                    ,width:22
                    ,hidden:false
                    ,editable:true
					,resizable:false
					,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:"Pendiente:Pendiente;Procesado:Procesado;Anulado:Anulado", class:'estilo-input'}                    
					,editrules:{required:false,edithidden:false}
					,formoptions:{ elmsuffix:" "} //rowpos:7,colpos:2,
                },				
				{name:'fechaE'
                    ,index:'liquidacionEstatus'
                    ,width:25
                    ,hidden:false
                    ,editable:false
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength:12,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{elmsuffix:"" }
                },			
				
				
               {name:'total'
                    ,index:'liquidacionTotal'
                    ,width:22
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,align:"right"
                    ,edittype:"text"
                    ,formatter:'currency'
                    ,editoptions: {size:14, maxlength: 17}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{elmsuffix:""}
                },
				{name:'detalle'
						,index:'total_deduccion'
						,width:10
						,align:"center"
						,hidden:false
						,editable:false
				}
            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30,50],
            pager: '#paginador'+$entidad,
            caption:"Gesti&oacute;n de Cierres Masivos",
            sortname: 'liquidacionCodigo',
            sortorder: "DESC",
            editurl:'controlador/liquidacion.php',
            viewrecords: true,
			rownumbers: true,
			afterInsertRow: function(rowid, aData){ 
				switch (aData.estatus) { 
					case 'Pendiente': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'red'}); break; 
					case 'Procesado': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'#004276'}); break; 
					case 'Anulado': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'#666666'}); break; 
				} 
			},
			
            loadError : function(xhr,st,err) { jQuery("#rsperror"+$entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
			
		jQuery("#listado"+$entidad).jqGrid('setGroupHeaders', { 
			useColSpanStyle: true, 
			groupHeaders:[				
				{startColumnName: 'desde', numberOfColumns: 2, titleText: '<em>Periodo</em>'},
				{startColumnName: 'estatus', numberOfColumns: 2, titleText: '<em>Cierre</em>'} 
			] 
		});
		

        jQuery("#listado"+$entidad).jqGrid('navGrid','#paginador'+$entidad,
        {edit:false,add:true,del:true,refresh:true,searchtext:"Buscar"}, //options
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
		                
                centrarDialogo('editmodlistado'+$entidad,'1000'); //parametros: Id Objeto,z-index               
                

                $('#tr_codigo,#tr_estatus',formid).show();
				$('#tr_ano,#tr_desde',formid).hide();
				$('#fecha',formid).datepicker({
					changeYear: true, 
					dateFormat:"dd-mm-yy",
					maxDate:"+0d"
				});
				$('#fecha',formid).attr("onKeyPress","return NumGuion(event)");
                $('#descrip,#municipio,#parroquia').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula	
				
			
              
            },	
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Modificada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
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
            closeAfterAdd:true,
            closeOnEscape:true,
            closeOnSubmit:true,
            //modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            //checkOnSubmit:true,		
            reloadAfterSubmit:true,
			caption: "Agregar",
			addCaption:"Generar Cierre",
            processData: "Generando...",
			bottominfo:"Generar Mensual o Quincenal",
            beforeShowForm: function(formid) {
                
                centrarDialogo('editmodlistado'+$entidad,'1000'); //parametros: Id Objeto,z-index               
   
				$('#tr_codigo,#tr_estatus',formid).hide();
				$('#tr_ano,#tr_desde',formid).show();
				
				$('#tr_ano .DataTD:eq(0)',formid).attr("colspan","3");
				
				//Obtener a�o actual
				var anio = obtener_fecha('Y');
				$('#ano',formid).val(anio);

				//Cambiar Nombre del boton Guardar con Generar
				$('.EditButton a:eq(0)').empty();
				$('.EditButton a:eq(0)').append("Generar <span class='ui-icon ui-icon-disk'></span> ");
				
				
            },		
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                var mes = $('#mes').val();
				var anio = $('#ano').val();
				
				var inicio1Q = "01-"+mes+"-"+anio;
				var ultimo1Q = "15-"+mes+"-"+anio;
				var inicio2Q = "16-"+mes+"-"+anio;
				var ultimo2Q = obtenerDiasMes(mes,anio)+"-"+mes+"-"+anio;
				
				var desde = $('#desde').val();
				var hasta = $('#hasta').val();
				
				//Generar solo Mensual o Quincenal
				if((desde==inicio1Q && hasta==ultimo2Q) || (desde==inicio1Q && hasta==ultimo1Q) || (desde==inicio2Q && hasta==ultimo2Q)){ 
					complete=true;
				}else{
					message = "Rango de Fechas NO permitido!!";
				}
			
                
                return [complete,message]
					
            },
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                
                alerta2("Generando Liquidaci&oacute;n");

				var mes = $('#mes').val();
				var anio = $('#ano').val();
				var desde = $('#desde').val();
				var hasta = $('#hasta').val();
				
				var inicio1Q = "01-"+mes+"-"+anio;
				var ultimo1Q = "15-"+mes+"-"+anio;
				var inicio2Q = "16-"+mes+"-"+anio;
				var ultimo2Q = obtenerDiasMes(mes,anio)+"-"+mes+"-"+anio;
				
				if(desde==inicio1Q && hasta==ultimo2Q){ //Generar Mes Completo
					var $codigo = anio+"-"+mes;
				}else if(desde==inicio1Q && hasta==ultimo1Q){ //Generar 1era Quincena
					var $codigo = anio+"-"+mes+"-Q1";
				}else if(desde==inicio2Q && hasta==ultimo2Q){ //Generar 2da Quincena
					var $codigo = anio+"-"+mes+"-Q2";
				}
					
			
				var retarr = {};
                retarr = {codigo:$codigo,estatus:'Pendiente'};
                return retarr; 
            },		
            afterSubmit: function(response, postdata) { 
                $.unblockUI();

                if (response.responseText == "") {
                    jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Generada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
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
			checkOnUpdate:true,
			checkOnSubmit : true,
            closeAfterDel: true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
             onclickSubmit : function(eparams) {
                var retarr = {};
					
                var id = jQuery("#listado"+$entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
                var ret =jQuery("#listado"+$entidad).jqGrid('getRowData',id);	
				
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

        {} // search options
    );
	
	//Ver detalle de Caja de Ahorro
	$("a[class='consultar']").live("click",function(id){             
		
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
				 
			var idSel = jQuery("#listado"+$entidad).jqGrid('getGridParam','selrow'); //idseleccionado								
			var row_data = $("#listado"+$entidad).jqGrid('getRowData',idSel);
			
			var codigo 	= row_data.id;
            var desde 	= row_data.desde;
            var hasta 	= row_data.hasta;
			var fechaE 	= row_data.fechaE;
			var estatus	= row_data.estatus;
            var cant 	= row_data.cantidad;
            var porc 	= row_data.porcentaje;
			var total 	= row_data.total;
			
            var st = "#tabs"+idSel;
            
			if($(st).html() != null ) {
				$("#tabs").tabs('select',st);
			}else{
				$("#tabs").tabs('add',st,idSel);

                $.ajax({
					url: "vista/frmLiquidacion_detalle.php?filtro="+idSel+"&codigo="+codigo+"&desde="+desde+"&hasta="+hasta+"&fechaE="+fechaE+"&estatus="+estatus+"&cant="+cant+"&porc="+porc+"&total="+total,
					type: "GET",
                    dataType: "html",
                    complete : function (req, err) {
                            $(st,"#tabs").append(req.responseText);
                            try { var pageTracker = _get._getTracker("UA-5463047-4"); pageTracker._trackPageview(); } catch(err) {};
                    }

                 }); 

            }

	}); //Fin Ver detalle
		
	
	$("#mes").live("change",function(){
		var mes = $("#mes").val();
		var anio = $("#ano").val();
		
		if(mes!=""){
			var desde = "01-"+mes+"-"+anio;
			var hasta = obtenerDiasMes(mes,anio)+"-"+mes+"-"+anio;
			$("#desde").val(desde);
			$("#hasta").val(hasta);

			$('#desde,#hasta').datepicker({	
				changeYear: true, 
				dateFormat:"dd-mm-yy",
				maxDate:"+0d"
			});
			
		}else{
			$("#codigo,#desde,#hasta").val('');
		}
		
	});
	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+$entidad).hide();
	}		

}); //Fin de Document ready
</script>

<div id="tabs">
	<ul> <li><a href="#tabLiquidacion">Liquidaci&oacute;n de Pr&eacute;stamos</a></li> 	</ul>
	<div id="tabLiquidacion">
		<span id="rsperrorLiquidacion" style="color:red"></span> <br/>
		<table id="listadoLiquidacion"></table>
		<div id="paginadorLiquidacion"></div>
	</div>
</div>