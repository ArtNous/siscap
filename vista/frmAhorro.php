<script type="text/javascript">
    jQuery(document).ready(function(){
		var $entidad = "Ahorro";
		jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/ahorro.php',
            datatype: "json",
            colNames:['C&oacute;digo','cedula','Desde','hasta','Descuento','Sueldo','Monto Bs.'],
            colModel:[
					
                {name:'id'
                    ,index:'cajahorroId'
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
				
				{name:'cedula'
                    ,index:'detahorroTrabCedula'
                    ,width:25
                    ,hidden:true
                    ,editable:false
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength:12,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                },				
				{name:'desde'
                    ,index:'cajahorroDesde'
                    ,width:25
                    ,hidden:false
                    ,editable:false
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength:12,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{rowpos:2,colpos:1, elmsuffix:" " }
                },				
				{name:'hasta'
                    ,index:'cajahorroHasta'
                    ,width:25
                    ,hidden:false
                    ,editable:false
                    ,resizable:false
                    ,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength:12,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:2,colpos:2,  elmsuffix:" " }
                },		
				
				{name:'porcentaje'
                    ,index:'cajahorroPorcentaje'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"center"
					,edittype:"select"
					,editoptions:{dataUrl:'controlador/tipo_ahorro.php?accion=carga_select', class:'estilo-input'}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ elmsuffix:" (*) " }
                },
				{name:'sueldo'
                    ,index:'detahorroSueldo'
                    ,width:22
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength: 17}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{elmsuffix:""}
                    ,sorttype:"float", formatter:"number", summaryType:'sum'
                },	
               {name:'monto'
                    ,index:'detahorroMonto'
                    ,width:22
                    ,hidden:false
                    ,editable:false
                    ,resizable:true
                    ,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:14, maxlength: 17}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{elmsuffix:""}
					,sorttype:"float", formatter:"number", summaryType:'sum'
                }
				

            ],
            rowNum:15,
            height:"auto",
			autowidth: true,
            rowList:[15,30,50,100],
            pager: '#paginador'+$entidad,
            caption:"Registro de Cierres Mensuales",
            sortname: 'cajahorroId',
            sortorder: "DESC",
            editurl:'controlador/ahorro.php',
            viewrecords: true,
			rownumbers: true,
			afterInsertRow: function(rowid, aData){ 
				switch (aData.estatus) { 
					case 'Pendiente': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'red'}); break; 
					case 'Procesado': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'#004276'}); break; 
					case 'Anulado': jQuery("#listado"+$entidad).jqGrid('setCell',rowid,'estatus','',{color:'#666666'}); break; 
				} 
			},
			/*grouping:true, 
            groupingView:{ 
					groupField:['cedula'], //Agrupar por campo
					//groupOrder : ['asc'], // Ordenar grupo
					groupText : ['Titular: <b>{0}</b>'],
					groupColumnShow: [false], //mostrar columna --> false: ocultar
					groupCollapse: false, // Minimizar por grupo
					groupSummary : [true]
			},*/
            loadError : function(xhr,st,err) { jQuery("#rsperror"+$entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
			
		jQuery("#listado"+$entidad).jqGrid('setGroupHeaders', { 
			useColSpanStyle: true, 
			groupHeaders:[				
				{startColumnName: 'desde', numberOfColumns: 2, titleText: '<em>Periodo</em>'},
				{startColumnName: 'fechaE', numberOfColumns: 2, titleText: '<em>Cierre</em>'} 
			] 
		});
		

        jQuery("#listado"+$entidad).jqGrid('navGrid','#paginador'+$entidad,
        {edit:false,add:false,del:false,refresh:true,searchtext:"Buscar"}, //options
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
              
                $('#id',formid).attr("readonly","readonly");
				$('#fecha',formid).datepicker({dateFormat:"dd-mm-yy"});
				$('#fecha',formid).attr("onKeyPress","return NumGuion(event)");
                $('#descrip,#municipio,#parroquia').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula	

            },	
            beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                var valor = $('#fecha').val();
                var ret = validar_fecha(valor);
                complete = ret[0];
                message = ret[1];

                return [complete,message]
					
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
            checkOnSubmit:true,		
            reloadAfterSubmit:true,
			caption: "Agregar",
			addCaption:"Generar Cierre",
            processData: "Generando...",
			bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
                centrarDialogo('editmodlistado'+$entidad,'1000'); //parametros: Id Objeto,z-index               
                				
				$('#tr_id td:eq(1), #tr_porcentaje td:eq(1)',formid).attr("colspan","3");				
				
				//Obtener año actual
				var anio = obtener_fecha('Y');
				$('#ano',formid).attr("readonly","readonly");
				$('#ano',formid).val(anio);
				
				//Cambiar Nombre del boton Guardar con Generar
				$('.EditButton a:eq(0)').empty();
				$('.EditButton a:eq(0)').append("Generar <span class='ui-icon ui-icon-disk'></span> ");
				
				
            },		
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {porcentaje:$('#porcentaje').val(),estatus:'Pendiente'};
                return retarr; 
            },		
            afterSubmit: function(response, postdata) { 
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
					url: "vista/frmDetalle_ahorro.php?filtro="+idSel+"&codigo="+codigo+"&desde="+desde+"&hasta="+hasta+"&fechaE="+fechaE+"&estatus="+estatus+"&cant="+cant+"&porc="+porc+"&total="+total,
					type: "GET",
                    dataType: "html",
                    complete : function (req, err) {
                            $(st,"#tabs").append(req.responseText);
                            try { var pageTracker = _get._getTracker("UA-5463047-4"); pageTracker._trackPageview(); } catch(err) {};
                    }

                 }); 

            }

	}); //Fin Ver detalle

	//Procesar Cierre de la Caja de Ahorro
	$('#btnImprimirResumen').click(function(){
		window.open("vista/rptResumenCajahorro.php");
		
	});
	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+$entidad).hide();
	}		

}); //Fin de Document ready
</script>

<span id="rsperrorAhorro" style="color:red"></span> <br/>
<table id="listadoAhorro"></table>
<div id="paginadorAhorro"></div>
<div style='text-align:right; margin-top:15px; display:none;'>			
	<a id='btnImprimirResumen' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Imprimir <span class='ui-icon ui-icon-print'></span></a>
</div>