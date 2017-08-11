<?php 
session_start(); 
$_SESSION['filtro']=isset($_GET['filtro'])?$_GET['filtro']:0;
$_SESSION['xdesde']=isset($_GET['desde'])?$_GET['desde']:'';
$_SESSION['xhasta']=isset($_GET['hasta'])?$_GET['hasta']:'';
?>

<script type="text/javascript">
jQuery(document).ready(function(){    
    
	filtro  = obtener_variable('filtro');
	entidad = 'Detalle'+filtro;
	
	jQuery("#rsperror"+entidad).show();
	
	jQuery("#listado"+entidad).jqGrid({
            url:'controlador/detalle_liquidacion.php?filtro='+filtro,
            datatype: "json",
            colNames:['C&oacute;digo','Cedula','Nombre','Tipo Descuento','Monto Liquidado'],
            colModel:[
                
				{name:'codigo'
                    ,index:'detliqId'
                    ,width:25
                    ,editable:false
                    ,hidden:true
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"                    
                },
			
				{name:'trabcedula'
                    ,index:'trabCedula'
                    ,width:15
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
					,align:"center"
                    ,edittype:"text"
                    ,editoptions: {size:10, readonly:true, hidedlg:false}	
                },
				{name:'nombtrab'
                    ,index:'trabNombre'
                    ,width:30
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
					,align:"left"
                    ,edittype:"text"
                    ,editoptions: {size:30, readonly:true, hidedlg:false}					
                },	

                 {name:'tipoprestamo'
                    ,index:'tipoprestNombre'
                    ,width:30
                    ,editable:false
                    ,hidden:false
                    ,resizable:true
                    ,align:"center"
                    ,editrules:{edithidden:false}                      
                },
				{name:'montoliq'
                    ,index:'detliqMonto'
                    ,width:25
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
					,align:"right"
                    ,editrules:{edithidden:true}
					,formatter: 'number'
                }
            ],
            rowNum:15,
            autowidth: true,
            height:"auto",
            rowList:[15,30,50,100],
            pager: '#paginador'+entidad,
            caption:"Detalle Liquidaci&oacute;n de Pr&eacute;stamos",
            sortname: 'CAST(trabCedula AS DECIMAL)',
            sortorder: "ASC",
            editurl:'controlador/detalle_liquidacion.php?filtro='+filtro,
            viewrecords: true,
			grouping:true, 
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
		
         jQuery("#listado"+entidad).jqGrid('filterToolbar',{
                searchOperators : false,
                searchOnEnter: false
        });

        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:false,add:false,view:false,del:true,refresh:true,refreshtext:"Actualizar"}, //options
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
			bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
               
				
            },	
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {codigo:filtro,prestamoid:$('#prestamoid').val()};
                return retarr; 
            },		 
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Modificada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
					
					//actualizar grid de Caja de Ahorro
					$("#listadoLiquidacion").trigger("reloadGrid"); 
					
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
			bottominfo:"Los campos marcados con (*) son obligatorios",
            afterShowForm: function(formid) {

            },	
			
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {codigo:filtro};
                return retarr; 
            },		 			
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000); 
					
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
            afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    
					//actualizar grid de Caja de Ahorro
					$("#listadoLiquidacion").trigger("reloadGrid"); 
					
					return [true, response.responseText] 
					
                }else{
                    return [false, response.responseText]
                } 
            }
        }, // options Eliminar 

        {} // search options
    );
		
	var idSel = filtro;	
	
	
	//Procesar Cierre de la Caja de Ahorro
	$('#btnProcesar'+idSel).click(function(){
		alerta2('Espere por favor, Procesando Cierre... '+idSel);
	
		$.ajax({
			url: "controlador/liquidacion.php",
			data:{oper:"procesar_cierre",id:idSel },
			type: "POST",
            success: function(ret){
                $('#lblFecha'+idSel).val(ret);
				$('#lblEstatus'+idSel).val('Procesado');
				mostrar_boton();
					
					jQuery("#rsperror"+entidad).show();
                    jQuery("#rsperror"+entidad).html("Cierre Procesado Satisfactoriamente");
                    jQuery("#rsperror"+entidad).fadeOut(6000);
					
					$("#listadoLiquidacion").trigger("reloadGrid"); 
                    $.unblockUI();
            }
        }); 
	});
	
	
	//Procesar Cierre de la Caja de Ahorro
	$('#btnImprimir'+idSel).click(function(){
		var desde = obtener_variable('xdesde');
		var hasta = obtener_variable('xhasta');
		window.open("vista/rptListadoLiquidacion.php?ordenar=detliqLiquidacionCodigo&desde="+desde+"&hasta="+hasta);
		
	});
	
	//mostrar_botones
	function mostrar_boton(){
		var tipo_usuario = obtener_variable('usuTipo');
		
		if(tipo_usuario!='Administrador'){
			$('#add_listado'+entidad+',#edit_listado'+entidad+',#del_listado'+entidad).hide();
		}
		
		$('#btnProcesar'+idSel+',#btnImprimir'+idSel).show();
		
		if($('#lblEstatus'+idSel).val()=="Pendiente"){
			$('#btnProcesar'+idSel).show();
			$('#add_listado'+entidad+',#edit_listado'+entidad+',#del_listado'+entidad).show();
		}else if($('#lblEstatus'+idSel).val()=="Procesado"){			
			$('#btnProcesar'+idSel).hide();
		}
	}
	
 	mostrar_boton();
	
});

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
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:90px;'>C&oacute;digo</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:190px;'>Periodo</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:80px;' >Fecha Cierre</th>
							<th class="ui-state-default ui-th-column ui-th-ltr">Total</th>
							<th class="ui-state-default ui-th-column ui-th-ltr" style='width:80px;'>Estatus</th>
						</tr> 
					</thead>
					<tbody>
						<tr>
							<td> <?php echo $_GET['filtro']; ?>  </td>
							<td> <?php echo $_GET['desde']; ?> al <?php echo $_GET['hasta']; ?> </td>
							<td> <input id='lblFecha<?php echo $_GET['codigo']; ?>' type="text" size="12" value="<?php echo $_GET['fechaE']; ?>" style="border:0px; text-align:center;" /> </td>
							<td> <b> Bs. <?php echo $_GET['total']; ?> </b> </td>
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