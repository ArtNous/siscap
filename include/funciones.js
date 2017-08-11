$(document).ready(function(){
    document.oncontextmenu = function(){
        return false
    }
	
	//Abrir Dialogo para la informacion de Contactanos 
	$('#a_contactanos').click(function(){
		$("#dlgContactanos").dialog({ 
            width:570,  //ancho
            height:450,  //alto
			modal: true, //Bloquear fondo
            //hide: 'slide', //Efecto de Salida
			show: "scale", //Efecto de Entrada
			hide: "scale", // Efecto de Salida
			draggable: false, //Mover 
			closeOnEscape: true, //Cerrar con la tecla [Esc]
            resizable: false, //Ampliar o reducir ventana
			position: 'center', //posicion:  top - center  - bottom - left - right
			title: 'Cont&aacute;ctanos', 
            buttons: [
						{
                            text: "Cerrar",
                            click: function() {	
                                $(this).dialog("close");
                            }
                        }
                    ]
        });
	});
	
	
});  

function abrirVentana(url,title,ancho,alto){ 
	var posicion_x; 
	var posicion_y; 
	posicion_x=(screen.width/2)-(ancho/2); 
	posicion_y=(screen.height/2)-(alto/2); 
	
	window.open(url,title,"width="+ancho+",height="+alto+",menubar=0,toolbar=0,directories=0,scrollbars=1,resizable=no,left="+posicion_x+",top="+posicion_y+"");
} 

//Funciones en JavaScripts para mostrar mensaje en el Index
function comprobar_datos(){
		
    var usuario = $("#usuario").val();
    var clave = $("#clave").val();
	var tipo = $("#cmbTipo").val();
	
	$(".error").show(); 
	
    if(usuario == "" || clave == "" || tipo == ""){
        //mensaje('Debes ingresar los datos solicitados..!');
		$(".error").empty();
		$(".error").append("Faltan datos por completar.!");
		jQuery(".error").fadeOut(8000); 
    }else{
			
        var sdC=Base64.encode(usuario+"||"+clave+"||"+tipo);
        $.ajax({
            url: "controlador/usuarios.php",
            context: document.body,
            type: "POST",
            data:'accion=xssggx&xt56yz='+sdC,
            success: function(retorno){	
				$(".error").empty();
                if (retorno == "invalido"){
                    //alerta('Datos Incorrectos.!');
					$(".error").append("Usuario &oacute; Contrase&ntilde;a incorrecta.!");
                    $("#usuario").val('');
                    $("#clave").val('');
					$("#cmbTipo").val('');
                    $("#usuario").focus();
				}else if(retorno == "inactivo"){
                   //alerta('El USUARIO se encuentra Inactivo.!');
				   $(".error").append("Su cuenta de usuario se encuentra Inactiva.!");
				}else if(retorno == "valido"){
					alerta('Por favor espere, Cargando...');
					window.location="principal.php";
                }
				jQuery(".error").fadeOut(8000); 
            }
			
        }); 
		
    }
		
};

 
/*************************** FUNCIONES Y VALIDACIONES GENERALES  (para crear archivo de validacion) ************************/

function crear_variable(variable,valor){  
	$.ajax({
        url: "include/funciones.php",        
		data:"oper=crear_variable&"+variable+"="+valor,
        type: "POST",
        async:false,
        cache:false,
        success: function(ret){	}
    });
};

function obtener_variable(variable){  
	var $variable="";
	$.ajax({
        url: "include/funciones.php",        
		data:"oper=obtener_variable&"+variable+"=0",
        type: "POST",
        async:false,
        cache:false,
        success: function(ret){
			$variable= ret;
		}
    });
	return $variable;
};

function obtener_fecha(formato){  
	var $fecha = "";
	$.ajax({
        url: "include/funciones.php",        
		data:"oper=obtener_fecha&"+formato+"=0",
        type: "POST",
        async:false,
        cache:false,
        success: function(ret){	
			$fecha = ret;
		}
    });
	
	return $fecha;
};

function aleatorio(max){  
    return Math.round(Math.random()*max);
}   
/*********** FUNCIONES JAVASCRIPT PARA VENTANAS Y MENSAJES  *****************/

function cerrarVentana(){ 
    crear_sesion("logeado",false);
    window.close();
}


//Estilo de mensaje con JqueryUI
function mensaje(msj){
    $.blockUI({
        theme:     true, 
        title:    'Advertencia:',
        message: "<div>"+msj+"</div>",
        css: {
            cursor:'default'
        }
    });
    $('.blockOverlay').click($.unblockUI); 
};

function alerta(msj){
    $.blockUI({ 
        message: "<p style='font-size:13px; font-weight:bold;'>"+msj+"</p>", 
        baseZ: 1005,        
		timeout: 3000,			
		centerX: true, 
        centerY: true,		
        css: { 
			//width:500,
			border: 'none', 
            padding: '10px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .6, 
            color: '#fff' 
        }
    }); 
};

function alerta2(msj){
    $.blockUI({ 
        message: "<p style='font-size:13px; font-weight:bold;'>"+msj+"</p>", 
        baseZ: 1005,
        centerX: true, 
        centerY: true,
        css: { 
            border: 'none', 
            padding: '15px',
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .6, 
            color: '#fff' 
        }
    }); 
};


function notificacion(error){
    $.blockUI({
        theme: true,
        css: {
            cursor:'default'
        },
        title:    'Advertencia:',
        message: "<div style='text-align:justify; padding:10px; width:auto;'>"+
        "<span style='color:#B40404;font-weight:bold;'> "+error+"..!</span><br /><br />"+
        "<b>Estimado usuario</b>, si usted presenta alg&uacute;n inconveniente por favor, comunicarse con Dv&VE Asesores Profesionales de Occidente C.A.  "+
        "a tr&aacute;ves de nuestro correo electr&oacute;nico <span style='color:blue'>vvasesores@hotmail.com</span> "+
        "&oacute; mediante los n&uacute;meros telef&oacute;nicos <span style='color:blue'>0272-236.26.26 / 0414-726.14.78 </span> "+ 
        "</div>"
		
    });
    $('.blockOverlay').click($.unblockUI); 
};

//Dialogo en Jquery
function centrarDialogo(objeto,index){
    $("#"+objeto).css({
        'z-index':index,
        position:'absolute',
        left: ($(window).width() - $("#"+objeto).outerWidth())/2,
        top: ($(window).height() - $("#"+objeto).outerHeight())/2
    });    
}

/************************* FIN FUNCIONES JAVASCRIPT PARA VENTANAS Y MENSAJES  ************************/


/********** Funciones Validacion de los Campos Compartidas *************/

function soloNum(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13)	{
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[0-9]/
        return re.test(keyChar);
    }	
}

function NumGuion(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[0-9-]/
        return re.test(keyChar);
    }	
}
function NumPunto(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[0-9.]/
        return re.test(keyChar);
    }	
}

function soloLetra(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /^[\a-zA-ZÑñÁÉÍÓÚáéíóú]+$/
        return re.test(keyChar);
    }	
}

function LetraNum(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[a-zA-Z0-9_-]/
        return re.test(keyChar);
    }	
}

function LetraEspacio(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[\sa-zA-ZÑñÁÉÍÓÚáéíóú]/
        return re.test(keyChar);			
    }	
}

function LetraNumEspacio(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[\sa-zA-Z0-9_-]/
        return re.test(keyChar);			
    }	
}


function sinEspacio(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /\s/ 
        return !re.test(keyChar);
    }	
}

function contrasena(evt){	
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode <= 13){
        return true;
    }else{
        var keyChar = String.fromCharCode(charCode);
        var re = /[a-zA-Z0-9]/
        return re.test(keyChar);
    }	
}

function validar_contrasena(valor){
    //var RegExPattern = /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{4,10})$/; //de 4 a 10 caracteres, al menos un digito numerico y sin caracteres especiales
    var RegExPattern = /^([a-zA-Z0-9]{4,8})$/; //de 4 a 8 caracteres alfanumericos
	if (RegExPattern.test(valor)){
        return [true,''];
    }else{  
        var complete = false;
        var msj = 'Contrase&ntilde;a incorrecta.!! m&iacute;nimo de 4 a 8 car&aacute;cteres.';
        return [false,msj];	
    }
    
}

/*****    Validacion con expresiones regulares    *****/

function validar_cedula(valor){
    var RegExPattern = /^([0-9]{7,8})$/;  // ####### ó ########
    if (RegExPattern.test(valor)){
        return [true,''];
    }else{  
        var complete = false;
        var msj = 'C&eacute;dula incorrecta!! m&iacute;nimo de 7 a 8 car&aacute;cteres.';
        return [false,msj];	
    }
}


function validar_telefono(valor){
    var RegExPattern = /^([0][2,4][0-9]{9})$/;  // 02######### ó 04#########
    if (RegExPattern.test(valor)){
        return [true,''];
    }else{  
        var msj = 'N&uacute;mero telef&oacute;nico incorrecto!! ejemplo: 04261234567';
        return [false,msj];
    }
}

//************ Validaciones de Fecha **************//
function validar_fecha(fecha){

    var RegExPattern = /^([0-9]{2}[-][0-9]{2}[-][0-9]{4})$/;  // ##-##-####
    if (RegExPattern.test(fecha)){
              
        if(esFechaValida(fecha)){
            return [true,''];
        }else{
            var msj = 'La fecha que intenta introducir no es valida.!!';
            return [false,msj];
        }
       
        
    }else{  
        var msj = 'Formato de Fecha incorrecta!! (dd-mm-yyyy) ';
        return [false,msj];
    }
}


function esFechaValida(fecha){ 
  
    var dia  =  parseInt(fecha.substring(0,2),10);
    var mes  =  parseInt(fecha.substring(3,5),10);
    var anio =  parseInt(fecha.substring(6),10);
     
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if(comprobarSiBisisesto(anio)){
                numDias=29
            }else{
                numDias=28
            }
            break;
        default:
            //alert("Fecha introducida errÃ³nea");
            return false;
        
    }
     
    if (dia>numDias || dia==0){
        //alert("Fecha introducida errÃ³nea");
        return false;
    }else{
        return true;
    }
        
}

function comprobarSiBisisesto(anio){
    if((anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))){
        return true;
    }else{
        return false;
    }
}

function comprobarFechaMayor(fecha1,fecha2){
    var xMes=fecha1.substring(3, 5);
    var xDia=fecha1.substring(0, 2);
    var xAnio=fecha1.substring(6,10);
    var yMes=fecha2.substring(3, 5);
    var yDia=fecha2.substring(0, 2);
    var yAnio=fecha2.substring(6,10);
    if (xAnio > yAnio){
        return(true);
    }else{
        if (xAnio == yAnio){
            if (xMes > yMes){
                return(true);
            }
            if (xMes == yMes){
                if (xDia > yDia){
                    return(true);
                }else{
                    return(false);
                }
            }else{
                return(false);
            }
        }
        else{
            return(false);
        }
    } 
}

function obtenerDiasMes(mes, anio) {
	return new Date(anio || new Date().getFullYear(), mes, 0).getDate();
}

//*****************Fin Validacion de Fechas******************//


/********** Fin Funciones Validacion de los Campos Compartidas *************/

/***** Funcion para mostrar u ocultar el mensaje de error en formularios independientes ****/
function mensaje_error(complete,message){
    if(complete == true){
        $("#xmsj").remove();
        $("#msj").hide();
    }else{
        $("#xmsj").remove();
        $("#msj").show();
        $("#msj").append("<span id='xmsj' style='color:black;'>"+message+"</span>");
    }
}


/****** Comprobar si existe archivo en un directorio *****/
function file_exists(url) {
    var req = this.window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
    if (!req) {
        throw new Error('XMLHttpRequest not supported');
    }
 
    req.open('HEAD', url, false);
    req.send(null);
    if (req.status == 200) {
        return true;
    }
 
    return false;
}


//Ocultar o Visualizar el Paginador del jqGrid
function comprobar_paginador(idSel,entidad){
	if(idSel!=''){
        jQuery("#paginador"+entidad+"_left, #paginador"+entidad+"_center").show();
        jQuery("#add_listado"+entidad+",#edit_listado"+entidad+",#del_listado"+entidad).show();
    }else{
        jQuery("#paginador"+entidad+"_left, #paginador"+entidad+"_center").hide();
        jQuery("#add_listado"+entidad+",#edit_listado"+entidad+",#del_listado"+entidad).hide();
    }
			
					
}

/** Comprobar si no es Administrador ocultar boton eliminar ***/
function verificartipousuario(tipo,entidad){
	if(tipo!='Administrador'){
		$('#del_listado'+entidad).hide();
	}
	
	if(tipo=='Trabajador'){
		jQuery("#add_listado"+entidad+",#edit_listado"+entidad+",#del_listado"+entidad).hide();
	}		
}