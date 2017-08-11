<?php 
session_start(); 
$_SESSION['filtro']=$_GET['filtro'];
?>

<script type="text/javascript">
jQuery(document).ready(function(){    
    
    var filtro  = obtener_variable('filtro');
	var entidad = 'Descuento'+filtro;
	
	jQuery("#rsperror"+entidad).show();
	
	jQuery("#listado"+entidad).jqGrid({
            url:'controlador/tipo_descuento_asociado.php?filtro='+filtro,
            datatype: "json",
            colNames:['C&oacute;digo','Cédula','Nombre del Asociado','Monto',''],
            colModel:[
                
				{name:'codigo'
                    ,index:'tipodescId'
                    ,width:25
                    ,editable:false
                    ,hidden:true                    
                    ,resizable:false
					,align:"center"
                    ,edittype:"text"
                },
                
                {name:'cedula'
                    ,index:'trabCedula'
                    ,width:20
                    ,editable:false
                    ,hidden:false
                    ,resizable:false
					,align:"center"
                    ,edittype:"text"
                },
                 {name:'nombres'
                    ,index:'trabNombre'
                    ,width:47
                    ,editable:false
                    ,hidden:false
                    ,resizable:false
                    ,edittype:"text"
                },
                {name:'monto'
                    ,index:'monto'
                    ,width:20
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"right"
                    ,edittype:'text'
                    ,editoptions: {size:10, maxlength:9,hidedlg:false, style:"font-weight:bold; font-size:14px; text-align;center;"}
                    ,editrules:{required:true,edithidden:true}
                    ,sorttype:"float", formatter:"number", summaryType:'sum'
                },
                {name:'descuento'
						,index:'descuento'
						,width:20
						,align:"center"
						,hidden:false
						,editable:false
						,resizable:false
						,edittype:'checkbox'
                        ,editoptions: {value:"On:Off",hidedlg:false }
                        //,searchoptions: { sopt: ['eq'], value: '1:Yes;0:No' }
                        ,stype: 'checkbox'
				}
     
            ],
            rowNum:20,
            autowidth: true,            
            height:"auto",
            rowList:[20,30,50,100],
            pager: '#paginador'+entidad,
            caption:"Asociados",
            sortname: 'CAST(trabajador.trabCedula AS DECIMAL)',
            sortorder: "ASC",
            cellEdit: true,
            cellsubmit:'remote',
            cellurl:'controlador/tipo_descuento_asociado.php?filtro='+filtro,
            editurl:'controlador/tipo_descuento_asociado.php?filtro='+filtro,
            viewrecords: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
                                
		
        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
        {edit:false,add:false,view:false,del:false,search:false,refresh:true,refreshtext:"Actualizar"} );

        jQuery("#listado"+entidad).jqGrid('filterToolbar',{
                searchOperators : false,
                searchOnEnter: false
        });

        //$("#listado"+entidad+"_descuento").empty().append(" <input id='chkall"+filtro+"' type='checkbox' title='Asignar descuento a todos' > ");
        $("#gview_listado"+entidad+" .ui-search-toolbar th:eq(4)").empty().append(" <input id='chkall"+filtro+"' type='checkbox' title='Asignar descuento al filtro'  > ");

        //Aplicar descuento a todos los Asociados
        $("#chkall"+filtro).live("click",function(id){           
           var cedula = this.name;
           var accion = "add-all";
           if(this.checked==false)
                accion="del-all";

           $.ajax({
                url: "controlador/tipo_descuento_asociado.php",
                data:{oper:accion,codigo:filtro},
                type: "POST",
                success: function(ret){
                    $("#listado"+entidad).trigger("reloadGrid"); 
                }
            });
        });

        
        //Agregar descuento a Asociado
        $("#chk"+filtro).live("click",function(id){           
           var cedula = this.name;
           var accion = "add";
           if(this.checked==false)
                accion="del";

           $.ajax({
                url: "controlador/tipo_descuento_asociado.php",
                data:{oper:accion,codigo:filtro,id:cedula },
                type: "POST",
                success: function(ret){
                    //$("#listado"+entidad).trigger("reloadGrid"); 
                }
            });
        });

   
	
});

</script>

<center>
	
    <div id="div_listadoDescuento<?php echo $_GET['filtro']; ?>">
		<span id="rsperrorDescuento<?php echo $_GET['filtro']; ?>" style="color:red"></span> <br/> 
		<table id="listadoDescuento<?php echo $_GET['filtro']; ?>"></table>
		<div id="paginadorDescuento<?php echo $_GET['filtro']; ?>"></div>

	</div>
</center>