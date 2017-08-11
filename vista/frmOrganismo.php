<script type="text/javascript">
    jQuery(document).ready(function(){
		var $entidad = "Organismo";
	
        jQuery("#listado"+$entidad).jqGrid({
            url:'controlador/organismo.php',
            datatype: "json",
            colNames:['Co&acute;digo','Nombre','Direcci&oacuten','Tel&eacute;fono'],
            colModel:[
                {name:'id'
                    ,index:'organismoId'
                    ,width:'40'
                    ,editable:false
                    ,key:true
                    ,hidden:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {required:false, size:4, maxlength: 3,hidedlg:false}
                    ,formoptions:{ rowpos:1}
                },
                {name:'descrip'
                    ,index:'organismoDescripcion'
                    ,width:'50'
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"text"
                    ,editoptions: {size:50, maxlength: 50,hidedlg:false,title:'Cédula'}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:2, elmsuffix:" (*)" }
                },
                {name:'direccion'
                    ,index:'organismoDireccion'
                    ,width:'30'
                    ,hidden:false
                    ,editable:true
                    ,resizable:true
                    ,edittype:"textarea"
                    ,editoptions: {cols:50, rows: 2,hidedlg:false,title:'Cédula'}
                    ,editrules:{required:true,edithidden:true}
                    ,formoptions:{ rowpos:3, elmsuffix:" (*)" }
                },
                {name:'telefono'
                    ,index:'organismoTelefono'
					,width:'10' 
					,hidden:true
                    ,align:"center"
                    ,editable:true
                    ,sorttype:"text"
					,editoptions: {size:12, maxlength: 11,hidedlg:false,title:'Introduzca un Numero Telefonico'}
                    ,editrules:{required:false,edithidden:true}
                    ,formoptions:{ rowpos:4}
                }
                                        
            ],
            rowNum:10,
            autowidth: true,
            height:"auto",
            rowList:[10,20,30],
            pager: '#paginador'+$entidad,
            caption:"Organismo",
            sortname: 'organismoDescripcion',
            sortorder: "ASC",
            editurl:'controlador/organismo.php',
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
            editCaption:"Modificar Organismo",
            processData: "Modificando...", 
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid,rowid) {
             
                centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#descrip').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#descrip').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
				$('#telefono').attr("onKeyPress","return soloNum(event)");

            },	
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
					
                var telefono = $('#telefono').val();
                if(telefono!=''){
					var ret = validar_telefono(telefono);
					complete = ret[0];
					message = ret[1];
				}else{
					complete=true;
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
            addCaption:"Registrar Organismo",
            edittext:"Agregando",
            processData: "Agregando...",
            bottominfo:"Los campos marcados con (*) son obligatorios",
            beforeShowForm: function(formid) {
                
				centrarDialogo('editmodlistado'+$entidad,'1000');//parametros: Id Objeto,z-index 
				
				$('#descrip').attr("onkeyup","this.value=this.value.toUpperCase()"); //Convertir caracteres a Mayuscula
				$('#descrip').attr("onKeyPress","return LetraEspacio(event)"); //Acepta solo Letras y espacios
				$('#telefono').attr("onKeyPress","return soloNum(event)");
				
             
            },	
			beforeSubmit:function(response,postdata){
                var complete=false;
                var message = "";
					
                var telefono = $('#telefono').val();
                if(telefono!=''){
					var ret = validar_telefono(telefono);
					complete = ret[0];
					message = ret[1];
				}else{
					complete=true;
				}
                
				return [complete,message]
				
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

<span id="rsperrorOrganismo" style="color:red"></span> <br/>
<table id="listadoOrganismo"></table>
<div id="paginadorOrganismo"></div>