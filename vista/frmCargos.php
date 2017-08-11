<script type="text/javascript">
    jQuery(document).ready(function(){
		var $entidad = "Cargos";
        jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/cargos.php',
            datatype: "json",
            colNames:['Denominaci&oacute;n del Cargo'],
            colModel:[
                {name:'descrip'
                    ,index:'cargoDescripcion'
                    ,width:'100'
					,key:true
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:50, maxlength: 50,hidedlg:false}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:2, elmsuffix:" (*)" }
                }
            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30],
            pager: '#paginador'+$entidad,
            caption:"Cargos",
            sortname: 'cargoDescripcion',
            sortorder: "ASC",
            editurl:'controlador/cargos.php',
            viewrecords: true,
			rownumbers: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+$entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 

        jQuery("#listado"+$entidad).jqGrid('navGrid','#paginador'+$entidad,
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
            editCaption:"Modificar Cargo",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid,rowid) {
             
                centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#descrip').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#descrip').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios

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
            addCaption:"Registrar Cargo",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
				centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#descrip').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#descrip').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
             
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
            width:"auto",
			height:"auto",
            closeAfterDel: true,
            reloadAfterSubmit:true,
            processData: "Borrando...",
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
    
	/***  Solo boton Eliminar para el usuario Administrador ***/	
	var tipo_usuario = obtener_variable('usuTipo');
	if(tipo_usuario!='Administrador'){
		$('#del_listado'+$entidad).hide();
	}		
	
});
</script>

<span id="rsperrorCargos" style="color:red"></span> <br/>
<table id="listadoCargos"></table>
<div id="paginadorCargos"></div>