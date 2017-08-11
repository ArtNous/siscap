<?php 
session_start(); 
$_SESSION['filtro']=$_GET['filtro'];
?>

<script type="text/javascript">
jQuery(document).ready(function(){    
    
	filtro  = obtener_variable('filtro');
	$entidad = 'Detalle'+filtro;
	
	jQuery("#rsperror"+$entidad).show();
	
	jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/detalle_ahorro.php?filtro='+filtro,
            datatype: "json",
            colNames:['C&oacute;digo','Cédula','Nombre','Organismo','Departamento','Cargo','Sueldo','Monto Bs.'],
            colModel:[
                
				{name:'codigo'
                    ,index:'detahorroCajahorroId'
                    ,width:25
                    ,editable:false
                    ,hidden:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 3,hidedlg:false}
                },
                
                {name:'cedula'
                    ,index:'detahorroTrabCedula'
                    ,width:20
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:8, maxlength: 8,hidedlg:false, 
						dataInit: function(elem) {
                            $(elem).autocomplete({
								source: 'controlador/trabajador.php?accion=autocompletar&campo=trabCedula&estatus=1',
                                select: function(event, ui) {  
			
                                    $('input[id=nombres]').attr("value",ui.item.nombre+' '+ui.item.apellido);
									$('input[id=organismoDescripcion]').attr("value",ui.item.organismo);
									$('input[id=departamento]').attr("value",ui.item.departamento);
									$('input[id=cargo]').attr("value",ui.item.cargo);
									$('input[id=sueldo]').attr("value",ui.item.sueldo);
							
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
					
					}
					,formoptions:{elmsuffix:" (*)" }
                },
                 {name:'nombres'
                    ,index:'trabNombre'
                    ,width:47
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:30, readonly:true, hidedlg:false}
                },
       
				{name:'organismoDescripcion'
                    ,index:'organismoDescripcion'
                    ,width:1
                    ,editable:true
                    ,hidden:true
                    ,resizable:false
                    ,edittype:"text"
                    ,editoptions:{size:50, readonly:true, hidedlg:false}
					,editrules:{edithidden:true}
                },
				{name:'departamento'
                    ,index:'departamentoDescripcion'
                    ,width:40
                    ,editable:true
                    ,hidden:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:50, readonly:true, hidedlg:false}
					,editrules:{edithidden:true}
                },
				{name:'cargo'
                    ,index:'trabCargo'
                    ,width:45
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:40, readonly:true, hidedlg:false}
					,formoptions:{ rowpos:6,colpos:1,  elmsuffix:" " }
                },
                 {name:'sueldo'
                    ,index:'detahorroSueldo'
                    ,width:18
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {size:10, readonly:true, hidedlg:false}
					,formoptions:{ rowpos:6,colpos:2,  elmsuffix:" " }
					,sorttype:"float", formatter:"number", summaryType:'sum'
                },
             
				{name:'monto'
                    ,index:'detahorroMonto'
                    ,width:20
                    ,editable:true
                    ,hidden:false
                    ,resizable:true
					,align:"right"
                    ,edittype:"text"
                    ,editoptions: {required:true, size:12, maxlength:14, hidedlg:false}
					,formoptions:{elmsuffix:" descuento del Sueldo en base al porcentaje (%)" }
					,sorttype:"float", formatter:"number", summaryType:'sum'
                }
				/*{name:'icono'
						,width:7
						,align:"center"
						,hidden:false
						,editable:false
			
				}	*/
					
            ],
            rowNum:10,
            autowidth: true,            
            height:"auto",
            rowList:[10,20,30,50,100],
            pager: '#paginador'+$entidad,
            caption:"Detalle Caja Ahorro",
            sortname: 'detahorroTrabCedula',
            sortorder: "ASC",
            editurl:'controlador/detalle_ahorro.php?filtro='+filtro,
            viewrecords: true,
			grouping:true, 
            groupingView:{ 
					groupField:['organismoDescripcion'], //Agrupar por campo
					groupOrder : ['asc'], // Ordenar grupo
					groupText : ['<b>{0}</b>'],
					groupColumnShow: [false], //mostrar columna --> false: ocultar
					groupCollapse: false, // Minimizar por grupo
					groupSummary : [true],
			},
            loadError : function(xhr,st,err) { jQuery("#rsperror"+$entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
                                
		
        jQuery("#listado"+$entidad).jqGrid('navGrid','#paginador'+$entidad,
        {edit:true,add:true,view:false,del:true,refresh:true,searchtext:"Buscar"}, //options
        {
            height:"auto",
            width:"auto",
            closeAfterEdit: true,
            closeOnEscape:true,
			//modal:true,
            jqModal:true,
            checkOnUpdate:true,
            savekey: [true,13],
            navkeys: [true,38,40],
            checkOnSubmit : true,	
            reloadAfterSubmit:true,
            edittext:"Editar",
            processData: "Modificando...",
            beforeShowForm: function(formid) {
				
				$("#cedula",formid).attr("readonly","readonly");
                $('#tr_organismoDescripcion td:eq(1),#tr_departamento td:eq(1),#tr_monto td:eq(1)',formid).attr("colspan","3");				
				
                
				
            },	
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {codigo:filtro};
                return retarr; 
            },		 
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Modificada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
					
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
            processData: "Agregando...",
            afterShowForm: function(formid) {
               
			   $("#cedula",formid).removeAttr("readonly");
			   $('#tr_organismoDescripcion td:eq(1),#tr_departamento td:eq(1),#tr_monto td:eq(1)',formid).attr("colspan","3");				
				
            },	
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";		
               
                if($('#cedula').val()==""){
					complete = false;
					message = "La C&eacute;dula NO puede quedar vacia.!! por favor, seleccione al trabajador";
				}else{
					if($('#nombres').val()==""){
						message = "Nombre NO puede quedar vacio.!! por favor, seleccione al trabajador";
					}else{
						if($('#sueldo').val()==""){
							message = "El trabajador NO puede tener el Sueldo en blanco";
						}else{
							message="";
							complete=true;
						}
					}
				}
                
                return [complete,message]
					
            },
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {codigo:filtro};
                return retarr; 
            },		 			
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
					
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
                retarr = {codigo:filtro};
                return retarr; 
            },		 		
			afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    
					//actualizar grid de Caja de Ahorro
					$("#listadoCajahorro").trigger("reloadGrid"); 
					
					return [true, response.responseText] 
					
                }else{
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {} // search options
    );
		
	var idSel = obtener_variable('filtro');
	
	
	//Procesar Cierre de la Caja de Ahorro
	$('#btnProcesar'+idSel).click(function(){
		alerta('Espere por favor, Procesando Cierre... '+idSel);
	
		$.ajax({
			url: "controlador/caja_ahorro.php",
			data:{oper:"procesar_cierre",id:idSel },
			type: "POST",
            success: function(ret){
                $('#lblFecha'+idSel).val(ret);
				$('#lblEstatus'+idSel).val('Procesado');
				mostrar_boton();
					
					jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Cierre Procesado Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000);
					
					//actualizar grid de Caja de Ahorro
					$("#listadoCajahorro").trigger("reloadGrid"); 
            }
        }); 
	});
	
	
	//Procesar Cierre de la Caja de Ahorro
	$('#btnImprimir'+idSel).click(function(){
		window.open("vista/rptComprobanteCajahorro.php?codigo="+idSel);
		
	});
	
	//mostrar_botones
	function mostrar_boton(){
		var tipo_usuario = obtener_variable('usuTipo');
		
		if(tipo_usuario!='Administrador'){
			$('#add_listado'+$entidad+',#edit_listado'+$entidad+',#del_listado'+$entidad).hide();
		}
		
		$('#btnProcesar'+idSel+',#btnImprimir'+idSel).show();
		
		if($('#lblEstatus'+idSel).val()=="Pendiente"){
			$('#btnProcesar'+idSel).show();
			$('#add_listado'+$entidad+',#edit_listado'+$entidad+',#del_listado'+$entidad).show();
		}else if($('#lblEstatus'+idSel).val()=="Procesado"){			
			$('#btnProcesar'+idSel).hide();
		}
	}
	
 	mostrar_boton();
	
});

</script>
<script>
    document.getElementById('btnSubir').onchange = function()
    {
        document.getElementById('archivoSubido').value = this.value;
    }
</script>

<center>
		<div id="gbox_listado" class="ui-jqgrid ui-widget ui-widget-content ui-corner-all" dir="ltr" style="width: auto;margin:0 auto;"> 
			<div id="gview_listado" class="ui-jqgrid-view" style="width: auto;"> </div>
					
			<div class="ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix" >
				<a class="ui-jqgrid-titlebar-close HeaderButton" href="javascript:void(0)" role="link" style="float:right; right: 0px;">
					<span class="ui-icon ui-icon-circle-triangle-n"></span>
				</a>
				<span class="ui-jqgrid-title">Informaci&oacute;n del Cierre</span>
			</div>
			<table id="tbFichaingreso" style="padding:3px; width:95%; border: 0px solid #EEEEEE; margin-top:5px; text-align:center;">
				<thead>
					<tr>
						<th class="ui-state-default ui-th-column ui-th-ltr" style='width:70px;'>C&oacute;digo</th>
						<th class="ui-state-default ui-th-column ui-th-ltr" style='width:160px;'>Periodo</th>
						<th class="ui-state-default ui-th-column ui-th-ltr" style='width:80px;' >Fecha Cierre</th>
						<th class="ui-state-default ui-th-column ui-th-ltr">Porcentaje</th>
						<th class="ui-state-default ui-th-column ui-th-ltr">Total</th>
						<th class="ui-state-default ui-th-column ui-th-ltr" style='width:70px;'>Estatus</th>
					</tr> 
				</thead>
				<tbody>
					<tr>
						<td> <?php echo $_GET['filtro']; ?>  </td>
						<td> <?php echo $_GET['desde']; ?> al <?php echo $_GET['hasta']; ?> </td>
						<td> <input id='lblFecha<?php echo $_GET['codigo']; ?>' type="text" size="12" value="<?php echo $_GET['fechaE']; ?>" style="border:0px; text-align:center;" /> </td>
						<td> <?php echo $_GET['porc']; ?> </td>
						<td> <b> Bs. <?php echo $_GET['total'] ?> </b> </td>
						<td> <input id="lblEstatus<?php echo $_GET['filtro']; ?>" type="text" size="9" value="<?php echo $_GET['estatus']; ?>"  style="border:0px; color:red; text-align:center;" /> </td>
					</tr>
				</tbody>
			</table>
		</div>
		
	<div id="div_listadoDetalle<?php echo $_GET['filtro']; ?>">
		
		<span id="rsperrorDetalle<?php echo $_GET['filtro']; ?>" style="color:red"></span> <br/> 
		<table id="listadoDetalle<?php echo $_GET['filtro']; ?>"></table>
		<div id="paginadorDetalle<?php echo $_GET['filtro']; ?>"></div>
	
		<div style='text-align:right; margin-top:15px;'>
                                      
			<a id='btnImprimir<?php echo $_GET['filtro']; ?>' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Imprimir <span class='ui-icon ui-icon-print'></span></a>
			
			<a id='btnProcesar<?php echo $_GET['filtro']; ?>' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Procesar Cierre <span class='ui-icon ui-icon-refresh'></span></a>
			
		</div>
	
	</div>
</center>