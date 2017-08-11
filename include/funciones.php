<?php 
session_start(); 
$oper = $_POST['oper'];

switch ($oper) {
	
	case 'crear_variable':
		foreach($_POST as $key => $value) {
			if($key!='oper'){
				$_POST[$key] = str_replace("'","",$value);
				$_POST[$key] = str_replace('"','',$value);
				$_SESSION[$key]=$value;
			}
		}
	break;
	
	case 'obtener_variable':
		foreach($_POST as $key => $value) {
			if($key!='oper'){
				$_POST[$key] = str_replace("'","",$value);
				$_POST[$key] = str_replace('"','',$value);
				echo $_SESSION[$key];
			}
		}
	break;
	
	case 'destruir_variable':
		foreach($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","",$value);
			$_POST[$key] = str_replace('"','',$value);
			unset($_SESSION[$key]);
		}
	break;
    
	case 'obtener_fecha':
		foreach($_POST as $key => $value) {
			if($key!='oper'){
				$fecha   = gmdate($key);
				echo $fecha;
			}
		}
		
	break;

	case 'upload':
		
		$error = "";
		$msg = "";
		$id = $_REQUEST['id'];
		$fileElementName = $_REQUEST['namefile'];
		
		if(!empty($_FILES[$fileElementName]['error'])){
			switch($_FILES[$fileElementName]['error']){
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
			$error = 'No se logro subir el archivo..';
		}else {
				$archivo = $_FILES[$fileElementName]['name'];
				$prefijo = substr(md5(uniqid(rand())),0,8);
					
				if($id == ""){
					$_SESSION[$fileElementName] = "files/".$prefijo.$_REQUEST['ext'];
				}else{
					$_SESSION[$fileElementName] = $id;
				}
					
				$destino =  "../".$_SESSION[$fileElementName];
				
				if (copy($_FILES[$fileElementName]['tmp_name'],$destino)) {
					//$msg = "Archivo subido: <b>".$archivo."</b>. ";
				} else {
					$error = "Error al subir el archivo. ";
				}
		}		
		
		echo "{error: '" . $error . "' }";

	break;
    
	
}

 ?>
