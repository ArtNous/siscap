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
		var entidad = "Aporte_patronal";
		jQuery("#listado"+entidad).jqGrid({
            url:'controlador/aporte_patronal.php',
            datatype: "json",
            colNames:['C&oacute;digo','C&oacute;digo','C&oacute;digo','C&oacute;digo','Concepto','Estatus','Total','Fecha',''],
            colModel:[
					
                {name:'codigo'
                    ,index:'aporteCodigo'
                    ,width:20
                    ,key: true
                    ,hidden:false
                    ,editable:false
                    ,resizable:false
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:8, maxlength:7,hidedlg:false}
                    ,editrules:{required:false,edithidden:false}
                    ,formoptions:{ rowpos:3,colpos:1, elmsuffix:" "}
                },
				{name:'ano'
                    ,index:'aporteCodigo'
                    ,hidden:true
                    ,editable:true
                    ,align:"center"
                    ,edittype:"text"
					,editoptions: {size:4, maxlength:4,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:2,colpos:1, elmsuffix:" - " }
                },	
				{name:'mesdesde'
                    ,index:'aporteCodigo'
                    ,width:25
                    ,hidden:true
                    ,editable:true
					,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:":Mes;01:01;02:02;03:03;04:04;05:05;06:06;07:07;08:08;09:09;10:10;11:11;12:12", class:'estilo-input', hidedlg:false}                    
					,editrules:{required:true,edithidden:true}
					,formoptions:{ rowpos:2,colpos:1, elmsuffix:" - "} 
                },
                {name:'meshasta'
                    ,index:'aporteCodigo'
                    ,width:25
                    ,hidden:true
                    ,editable:true
                    ,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:":Mes;01:01;02:02;03:03;04:04;05:05;06:06;07:07;08:08;09:09;10:10;11:11;12:12", class:'estilo-input', hidedlg:false}                    
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:2,colpos:1, elmsuffix:" (*) "} 
                },
                {name:'concepto'
                    ,index:'aporteConcepto'
                    ,width:55
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:40, maxlength:50,hidedlg:false}
                    ,editrules:{required:true,edithidden:false}
                    ,formoptions:{elmsuffix:" (*) "}
                },  
				
                
				{name:'estatus'
                    ,index:'cajahorroEstatus'
                    ,width:20
                    ,hidden:false
                    ,editable:false
					,resizable:false
					,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:"Pendiente:Pendiente;Procesado:Procesado", class:'estilo-input'}                    
					,editrules:{required:false,edithidden:false}
					,formoptions:{ elmsuffix:" "} 
                },
               {name:'total'
                    ,index:'aporteFecha'
                    ,width:20
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength: 17}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{elmsuffix:""}
                },
                {name:'fechareg'
                    ,index:'aporteFecha'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:9, maxlength:10,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{elmsuffix:" (*) " }
                },      
				{name:'detalle'
						,index:'total_deduccion'
						,width:8
						,align:"center"
						,hidden:false
						,editable:false
				}
				

            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30,50],
            pager: '#paginador'+entidad,
            caption:"Generar Aporte Patronal",
            sortname: 'aporteCodigo',
            sortorder: "DESC",
            editurl:'controlador/aporte_patronal.php',
            viewrecords: true,
			rownumbers: true,
            afterInsertRow: function(rowid, aData){ 
                switch (aData.estatus) { 
                    case 'Pendiente': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'red'}); break; 
                    case 'Procesado': jQuery("#listado"+entidad).jqGrid('setCell',rowid,'estatus','',{color:'#004276'}); break; 
                } 
            },
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

                //Convertir caracteres a Mayuscula
                $('#concepto',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); 
                $('#tr_ano').hide();

                 $('#fechareg',formid).datepicker({
                    changeYear: true, 
                    dateFormat:"dd-mm-yy",
                    maxDate:"+0d"
                });
                $('#fechareg',formid).attr("onKeyPress","return NumGuion(event)");
                

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
            closeAfterAdd:true,
            closeOnEscape:true,
            closeOnSubmit:true,
            //modal:true,
            jqModal:true,
            //checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            checkOnSubmit:true,		
            reloadAfterSubmit:true,
			caption: "Agregar",
			addCaption:"Generar Pago",
            processData: "Generando...",
			//bottominfo:"Generar Pago de Aporte",
            beforeShowForm: function(formid) {
                
                centrarDialogo('editmodlistado'+entidad,'1000'); //parametros: Id Objeto,z-index               
				
                $('#tr_ano').show();

                //Convertir caracteres a Mayuscula
                $('#concepto',formid).addClass("mayuscula").attr("onblur","this.value=this.value.toUpperCase()"); 
                
                //Obtener año actual
				var anio = obtener_fecha('Y');
				$('#ano',formid).val(anio);

                $('#concepto',formid).val("APORTE "+anio);

                var fecha = obtener_fecha('d-m-Y');
                $('#fechareg',formid).datepicker({
                    changeYear: true, 
                    dateFormat:"dd-mm-yy",
                    maxDate:"+0d"
                });
                $('#fechareg',formid).attr("onKeyPress","return NumGuion(event)").val(fecha);
				
				//Cambiar Nombre del boton Guardar con Generar
				$('.EditButton a:eq(0)').empty();
				$('.EditButton a:eq(0)').append("Generar <span class='ui-icon ui-icon-disk'></span> ");
				
				
            },
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                var mesdesde = $('#mesdesde').val();
                var meshasta = $('#meshasta').val();
				var anio = $('#ano').val();
		
				//Generar solo Mensual o Quincenal
				if(mesdesde <= meshasta){ 
					complete=true;
				}else{
					message = "Rango de Meses incorrecto, ejemplo 2015-01-03";
                    complete=false;
				}
			
                return [complete,message]
					
            },			
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
               
                
				var codigo = $('#ano').val() + $('#mesdesde').val() + $('#meshasta').val();
				
				var retarr = {};
                retarr = {codigo:codigo};
                return retarr; 
            },		
            afterSubmit: function(response, postdata) { 
            
                if (response.responseText == "") {
                    alerta2("Generando Cierre");
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Generada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
                    $.unblockUI();
					return [true, response.responseText]
                }else {
                    return [false, response.responseText]
						 
                } 
            }
        }, // options Agregar
        {
            height:"auto",
            width:"auto",
			checkOnUpdate:true,
			checkOnSubmit :true,
            closeAfterDel:true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    return [true, response.responseText] 
                }else {
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {} // search options
    );
	

	//Ver detalle de Caja de Ahorro
	$("a[class='consultar']").live("click",function(id){             
		
		$.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:"enable"});

            var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado                                
            var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);
            var estatus = row_data.estatus;

			var maintab =jQuery('#tabs').tabs({
							add: function(e, ui) {
								$(ui.tab).parents('li:first')
								.append('<li><span id="tabs-close'+idSel+'" class="ui-tabs-close ui-icon ui-icon-close " title="Close Tab" ></span></li>')
								.find('span.ui-tabs-close')
								.click(function() {  $("#tabs").tabs('remove', '#' + ui.panel.id); 	});
								$("#tabs").tabs('select', '#' + ui.panel.id);
							}
						 });
			
            var st = "#tabs"+idSel;
            
			if($(st).html() != null ) {
				$("#tabs").tabs('select',st);
			}else{
				$("#tabs").tabs('add',st,idSel);

                //var archivo = "frmAporte_patronal_temp.php";
                //if(estatus=='Procesado')
                 // var  archivo="frmAporte_patronal_detalle.php";

                $.ajax({
					url: "vista/frmAporte_patronal_detalle.php?filtro="+idSel+"&estatus="+estatus,
					type: "GET",
                    dataType: "html",
                    complete : function (req, err) {
                            $(st,"#tabs").append(req.responseText);
                            try { var pageTracker = _get._getTracker("UA-5463047-4"); pageTracker._trackPageview(); } catch(err) {};
                    }
                 }); 

            }

	}); //Fin Ver detalle
		
	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+entidad).hide();
	}		
	
}); //Fin de Document ready
</script>

	<div id="tabs">
		<ul> <li><a href="#tabAporte_patronal">APORTE PATRONAL</a></li> 	</ul>
		<div id="tabAporte_patronal">
				<span id="rsperrorAporte_patronal" style="color:red"></span> <br/>
				<table id="listadoAporte_patronal"></table>
				<div id="paginadorAporte_patronal"></div>
		</div>
	</div>