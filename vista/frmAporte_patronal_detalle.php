<?php 
session_start(); 
$_SESSION['filtro']=$_GET['filtro'];
$_SESSION['tabla']=($_GET['estatus']=='Procesado')?'descuento_ahorro':'aporte_patronal_temp';
?>

<script type="text/javascript">
jQuery(document).ready(function(){    
    
    var filtro  = obtener_variable('filtro');
    var entidad = 'Aportedetalle'+filtro;

    jQuery("#rsperror"+entidad).show();
    
    jQuery("#listado"+entidad).jqGrid({
            url:'controlador/aporte_patronal_detalle.php?filtro='+filtro,
            datatype: "json",
            colNames:['C&oacute;digo','Cédula','Nombre del Asociado','Monto'],
            colModel:[
                
                {name:'codigo'
                    ,index:'descahorroCodigo'
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
                    ,index:'descahorroMonto'
                    ,width:20                    
                    ,hidden:false
                    ,editable:true
                    ,resizable:false
                    ,align:"right"
                    ,edittype:'text'
                    ,editoptions: {size:10, maxlength:9,hidedlg:false, style:"font-weight:bold; font-size:14px; text-align;center;"}
                    ,editrules:{required:true,edithidden:true}
                    ,sorttype:"float", formatter:"number", summaryType:'sum'
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
            cellurl:'controlador/aporte_patronal_detalle.php?filtro='+filtro,
            editurl:'controlador/aporte_patronal_detalle.php?filtro='+filtro,
            viewrecords: true,
            loadError : function(xhr,st,err) { jQuery("#rsperror"+entidad).html("Tipo: "+st+"; Mensaje: "+ xhr.status + " "+xhr.statusText); }
        }); 
                                
        
        jQuery("#listado"+entidad).jqGrid('navGrid','#paginador'+entidad,
            {edit:false,add:false,view:false,del:true,search:false,refresh:true,refreshtext:"Actualizar"},
            {},
            {},
            {   height:"auto",
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
            }// options Eliminar 

        );

        jQuery("#listado"+entidad).jqGrid('filterToolbar',{
                searchOperators : false,
                searchOnEnter: false
        });

    
    var idSel = filtro;    
    //Cambiar estatus a Procesado y pasar registro a descuento_ahorro
    $('#btnProcesar'+idSel).click(function(){
        
        $("#dlgPregunta").dialog({ 
            modal:true,
            width:460,
            title: 'PROCESAR APORTE PATRONAL',
            closeOnEscape: true,
            buttons: [
            {
                text: "Si",
                click: function() {
                    
                    $(this).dialog("close");
                    alerta2('Espere por favor, Procesando... '+idSel);
    
                    $.ajax({
                        url: "controlador/aporte_patronal_detalle.php?filtro="+idSel,
                        data:{oper:"procesar"},
                        type: "POST",
                        success: function(ret){
                            $.unblockUI();
                            if(ret>0){
                                $("#listadoAporte_patronal").trigger("reloadGrid"); 
                                $('#tabs-close'+idSel).click();
                            }else{
                                mensaje("No existen registros con monto superiores a 0");
                            }
                            
                        }
                    }); 
                }
            },
            {
                text: "No",
                click: function() { 
                    $(this).dialog("close");
                }
            }
            ]
        });

       
    });
    
});

</script>

<center>
    
    <div id="div_listadoAportedetalle<?php echo $_GET['filtro']; ?>">
        <span id="rsperrorAportedetalle<?php echo $_GET['filtro']; ?>" style="color:red"></span> <br/> 
        <table id="listadoAportedetalle<?php echo $_GET['filtro']; ?>"></table>
        <div id="paginadorAportedetalle<?php echo $_GET['filtro']; ?>"></div>

        <?php if($_GET['estatus']=='Pendiente'){ ?>
            <div style='text-align:right; margin-top:15px;'>            
                <a id='btnProcesar<?php echo $_GET['filtro']; ?>' class='fm-button ui-state-default ui-corner-all fm-button-icon-left' href='javascript:void(0)' style='margin-left:15px;' > Procesar Pago <span class='ui-icon ui-icon-refresh'></span></a>
            </div>
            <div id="dlgPregunta" style='display:none;'> 
                    <p><span style='font-weight:bold; color:red;'>Importante:</span> 
                        Solo se procesaran los registros con monto mayor a 0,
                        siendo incluidos como 'Abono' a la Caja de Ahorro.
                    </p>
                    <center> <b> ¿Esta seguro que desea Procesar el PAGO? </b></center>
            </div>
        <?php } ?>
    </div>
</center>