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
		var entidad = "Tipoprestamo";
	
        jQuery("#listado"+entidad).jqGrid({
            url:'controlador/tipo_prestamo.php',
            datatype: "json",
            colNames:['Codigo','Nombre','Abreviatura','Descuento','Tipo','Monto','Estatus',''],
            colModel:[
                {name:'id'
                    ,index:'tipoprestId'
                    ,width:10
                    ,editable:false
                    ,key:true
                    ,hidden:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 3,hidedlg:false}
                },
                {name:'nombre'
                    ,index:'tipoprestNombre'
                    ,width:60
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:40, maxlength: 40,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{rowpos:3, colpos:1, elmsuffix:" (*)" }
                },
				{name:'prefijo'
                    ,index:'tipoprestPrefijo'
                    ,width:15
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:8, maxlength: 8,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:2, colpos:1, elmsuffix:" (*) " }
                },
                {name:'descuento'
                    ,index:'tipoprestDescuento'
                    ,width:10
                    ,hidden:true
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:"P:Prestamo;G:Global;D:Detallado", class:"FormElement estilo-input"}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:4, colpos:1, elmsuffix:"" }
                },
				{name:'tipodesc'
                    ,index:'tipoprestTipodesc'
                    ,width:10
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,align:"center"
                    ,edittype:"select"
                    ,editoptions: {value:":-;Bs.:Bs.;%:%", class:"FormElement estilo-input"}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:5, colpos:1, elmsuffix:"" }
                },
				{name:'monto'
                    ,index:'tipoprestMonto'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:15, maxlength:15,hidedlg:false}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:5, colpos:1, elmsuffix:"" }
                    ,sorttype:"float", formatter:"number", summaryType:'sum'
                },
                
                {name:'estatus'
                    ,index:'tipoahorroEstatus'
                    ,width:11
                    ,align:"center"
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"checkbox"
                    ,editoptions: {value:"Activo:Inactivo"}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ elmsuffix:"  Activo"}
                },
                {name:'icono'
                        ,index:'ver'
                        ,width:10
                        ,align:"center"
                        ,hidden:false
                        ,editable:false
                }      
                                        
            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30],
            pager: '#paginador'+entidad,
            caption:"Tipo de Descuentos",
            sortname: 'tipoprestNombre',
            sortorder: "ASC",
            editurl:'controlador/tipo_prestamo.php',
            viewrecords: true,
			rownumbers: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 

        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {}, 
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
            editCaption:"Modificar Descuento ",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid,rowid) {
             
                centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#nombre,#prefijo,#descrip',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#prefijo',formid).attr("onKeyPress","return soloLetra(event)");
				$('#nombre',formid).attr("onKeyPress","return LetraNumEspacio(event)");
				$('#montomax',formid).attr("onKeyPress","return NumPunto(event)");
				
				$('#tr_descrip td:eq(1)',formid).attr("colspan","3");

            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";
                var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
                
                if($('#descuento').val()!='P' && $('#tipodesc').val()=="" ){
                    complete = false;
                    message = 'Por favor, seleccione el tipo de Descuento (Bs. o %) ';
                }

                if(complete==true){
                    if($('#descuento').val()!='P' && ($('#monto').val()=="" || $('#monto').val()==0) ){
                        complete = false;
                        message = 'Por favor, ingrese el monto a descontar. ';
                    }
                }

                
                if(complete==true){
                    if($('#monto').val()!=""){
                        valor = $('#monto').val();                
                        if (decimal.test(valor)){
                            complete = true;
                        }else{  
                            complete = false;
                            message = 'Monto incorrecto!! solo 2 decimales, ejemplo: 15000.00 ';
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
            addCaption:"Registrar Descuento",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
				centrarDialogo('editmodlistado'+entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#nombre,#prefijo',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#prefijo',formid).attr("onKeyPress","return soloLetra(event)");
				$('#nombre',formid).attr("onKeyPress","return LetraNumEspacio(event)");
				$('#monto',formid).attr("onKeyPress","return NumPunto(event)");
				$('#estatus').attr("checked","checked");
             
            },	
			beforeSubmit:function(response,postdata){
                var complete=true;
                var message = "";
                var decimal = /^(\d|-)?(\d|,)*\.?\d*$/ ; // de 1 a 6 digitos numericos, separador de decimal: .  y solo 2 digitos como decimales
                
                if($('#descuento').val()!='P' && $('#tipodesc').val()=="" ){
                    complete = false;
                    message = 'Por favor, seleccione el tipo de Descuento (Bs. o %) ';
                }

                if(complete==true){
                    if($('#descuento').val()!='P' && ($('#monto').val()=="" || $('#monto').val()==0) ){
                        complete = false;
                        message = 'Por favor, ingrese el monto a descontar. ';
                    }
                }

                
                if(complete==true){
                    if($('#monto').val()!=""){
                        valor = $('#monto').val();                
                        if (decimal.test(valor)){
                            complete = true;
                        }else{  
                            complete = false;
                            message = 'Monto incorrecto!! solo 2 decimales, ejemplo: 15000.00 ';
                        }
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

    //Ver detalle 
    $("a[class='consultar']").live("click",function(id){             
        
        $.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:"enable"});

            var idSel = jQuery("#listado"+entidad).jqGrid('getGridParam','selrow'); //idseleccionado                               
            var row_data = $("#listado"+entidad).jqGrid('getRowData',idSel);
            var id      = row_data.id;
            var codigo  = row_data.prefijo;
            var descuento  = row_data.descuento;

            var maintab =jQuery('#tabs').tabs({
                            add: function(e, ui) {
                                $(ui.tab).parents('li:first')
                                .append('<li><span class="ui-tabs-close ui-icon ui-icon-close " title="Close Tab" ></span></li>')
                                .find('span.ui-tabs-close')
                                .click(function() {  $("#tabs").tabs('remove', '#' + ui.panel.id);  });

                                $("#tabs").tabs('select', '#' + ui.panel.id);
                            }
                         });
            var st = "#tabs"+idSel;
            
            if(descuento=='D'){
                if($(st).html() != null ) {
                    $("#tabs").tabs('select',st);
                }else{
                    $("#tabs").tabs('add',st,codigo);

                    $.ajax({
                        url: "vista/frmTipo_descuento_asociado.php?filtro="+idSel+"&codigo="+codigo,
                        type: "GET",
                        dataType: "html",
                        complete : function (req, err) {
                                $(st,"#tabs").append(req.responseText);
                                try { var pageTracker = _get._getTracker("UA-5463047-4"); pageTracker._trackPageview(); } catch(err) {};
                        }

                     }); 
                }
            }else{
                mensaje("Solo aplica a descuentos Detallados (D)  ");
            }

    }); //Fin Ver detalle
    
	/***  Solo boton Eliminar para el usuario Administrador ***/	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+entidad).hide();
	}		
   
});
</script>

<div id="tabs">
    <ul> <li><a href="#tabDescuentos">Descuentos</a></li>  </ul>
    <div id="tabDescuentos">
        <span id="rsperrorTipoprestamo" style="color:red"></span> <br/>
        <table id="listadoTipoprestamo"></table>
        <div id="paginadorTipoprestamo"></div>
    </div>
</div>


