<fieldset style="padding:5px;">
	<legend id="leyenda"><strong>Consultar por Organismo: </strong></legend>
	<input name='lblOrganismo'  id='lblOrganismo' size='90'>
</fieldset>	

<script type="text/javascript">
    jQuery(document).ready(function(){
		
		var $tipo_usuario = obtener_variable('usuTipo');
		var $entidad = "Departamento";
		
		$('#lblOrganismo').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
		
		var $orgaId = '';
		crear_variable('orgaId',$orgaId);	
		jQuery("#listado"+$entidad).trigger("reloadGrid");	
				
		
        jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/departamento.php',
            datatype: "json",
            colNames:['C&oacute;digo','Descripci&oacute;n','Organismo'],
            colModel:[
                {name:'id'
                    ,index:'departamentoId'
                    ,width:20
                    ,editable:true
                    ,key:true
                    ,hidden:false
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 4,hidedlg:false}
					,editrules:{required:false,edithidden:true}
                }, 
                {name:'descrip'
                    ,index:'departamentoDescripcion'
                    ,width:100
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:80, maxlength: 100,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
					,formoptions:{elmsuffix:" (*) " }
                },
        
				{name:'organismo'
						,index:'departamentoOrganismoId'
						,width:80
						,hidden:true
						,editable:true
						,resizable:true
						,align:"left"
						,edittype:"select"
						,editoptions:{dataUrl:'controlador/organismo.php?accion=carga_select'}
						,editrules:{required:false,edithidden:true}
						,formoptions:{rowpos:3, colpos:1, elmsuffix:" (*)" } 
				}
            ],
            rowNum:10,
            autowidth: true,
			//width:800,
            height:"auto",
            rowList:[10,20,30],
            pager: '#paginador'+$entidad,
            caption:"Departamento",
            sortname: 'departamentoDescripcion',
            sortorder: "ASC",
            editurl:'controlador/departamento.php',
            viewrecords: true,
			rownumbers: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+$entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 

        jQuery("#listado"+$entidad).jqGrid('navGrid','#paginador'+$entidad,
        {edit:true,add:true,del:true,refresh:true,searchtext:"Buscar"}, //options
        {
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
            editCaption:"Modificar Departamento",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
				
				centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#tr_id,#tr_organismo',formid).show();
				$('#id',formid).attr("readonly","readonly");
				$('#descrip',formid).attr("onKeyPress","return LetraEspacio(event)");	
				$('input',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
            },
			//Antes de envio
			beforeSubmit:function(response,postdata){
				var complete=false;
				var message = "";		
			
					if ($('#organismo').val() != ''){
						complete = true;
					}else{  
						complete = false;
						message = 'Debes seleccionar el Organismo al que pertenece el Departamento';
					}

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
            addCaption:"Registrar Departamento",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
				
				centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#tr_id,#tr_organismo',formid).hide();
				$('#descrip',formid).attr("onKeyPress","return LetraEspacio(event)");
				$('input',formid).attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				
			},
			// Enviar datos adicionales al posdata
            onclickSubmit : function(eparams) {
                var retarr = {};
                retarr = {organismo:$orgaId };
                return retarr; 
            },
            afterSubmit: function(response, postdata) { 
                
				if (response.responseText == "") {	
					jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Adicionada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
                    return [true, response.responseText]
                } 
                else {
                    return [false, response.responseText]
						 
                } 
            }
        }, // fin options Añadir
        { // opcion Eliminar
            height:"auto",
			width:"auto",
            closeAfterDel: true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
            onclickSubmit : function(eparams) {
					var retarr = {};
					var id = jQuery("#listado"+$entidad).jqGrid('getGridParam','selrow'); 
					if (id) {
						var ret =jQuery("#listado"+$entidad).jqGrid('getRowData',id);
					}
					retarr = {id:ret.id,descrip:ret.descrip,direcid:ret.direcid,organismo:ret.organismo};		
					return retarr;
			},		
			afterSubmit: function(response, postdata) { 
                if (response.responseText == "") {
                    jQuery("#rsperror"+$entidad).show();
                    jQuery("#rsperror"+$entidad).html("Informacion Eliminada Satisfactoriamente");
                    jQuery("#rsperror"+$entidad).fadeOut(6000); 
                    return [true, response.responseText]
                } 
                else {
                    return [false, response.responseText]
                } 
            }
        }, // fin options Eliminar 

        {} // search options
		);
		
		
		$("#lblOrganismo").autocomplete({
			source: 'controlador/organismo.php?accion=autocompletar',
			select: function(event, ui) { 
				$orgaId = ui.item.orgaid;														  
				$valor = ui.item.value;
				
				crear_variable('orgaId',$orgaId);
				jQuery("#listado"+$entidad).trigger("reloadGrid");
				jQuery("#divListado"+$entidad).show();
				comprobar_paginador($orgaId,$entidad);
				verificartipousuario($tipo_usuario,$entidad);
				
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
		
		
		$('#lblOrganismo').keyup(function(){
			if(this.value==''){
				var $orgaId = '';
				crear_variable('orgaId',$orgaId);	
				jQuery("#listado"+$entidad).trigger("reloadGrid");
				comprobar_paginador($orgaId,$entidad);
				verificartipousuario($tipo_usuario,$entidad);
			}
		});

		comprobar_paginador($orgaId,$entidad);
		verificartipousuario($tipo_usuario,$entidad);
    });
</script>

<span id="rsperrorDepartamento" style="color:red"></span> <br/>
<table id="listadoDepartamento"></table>
<div id="paginadorDepartamento"></div>