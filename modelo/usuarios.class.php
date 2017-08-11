<?php

include_once("../include/funciones.class.php");

class usuarios extends funciones {

    //	Agregar usuarios: *****Breve Descripcion****
    //	Parametros: descripcion de usuarios
    //	Autor(es): LuisMendez	
    function agregar($cedula, $nombre, $apellido, $correo, $telefono, $tipo, $clave) {
        if ($this->con->conectar() == true) {

            $Sql = "INSERT INTO usuarios (usuCedula,usuNombre,usuApellido,usuCorreo,usuTelefono,usuTipo,usuClave,usuEstatus) 
                                  VALUES ('$cedula','$nombre','$apellido','$correo','$telefono','$tipo','$clave','Activo')";
            return mysql_query($Sql) or die(mysql_error());

        }
    }

    //****** Fin Agregar usuarios
    //	Editar usuarios: *****Breve Descripcion****
    //	Parametros: descripcion de usuarios
    //	Autor(es): LuisMendez	
    function editar($cedula, $nombre, $apellido, $correo, $telefono, $tipo, $clave, $estatus) {
        if ($this->con->conectar() == true) {

            if ($clave == "") {
                $Sql = "UPDATE usuarios SET 
                
                        usuNombre      = '" . $nombre . "',
                        usuApellido    = '" . $apellido . "',
                        usuCorreo      = '" . $correo . "',
						usuTelefono     = '" . $telefono . "',
                        usuTipo        = '" . $tipo . "',
                        usuEstatus     = '" . $estatus . "'
                            
                        WHERE usuCedula   = '" . $cedula . "'";
            } else {

                $Sql = "UPDATE usuarios SET 
                
                        usuNombre      = '" . $nombre . "',
                        usuApellido    = '" . $apellido . "',
                        usuCorreo      = '" . $correo . "',
						usuTelefono     = '" . $telefono . "',
                        usuTipo        = '" . $tipo . "',    
                        usuClave       = '" . $clave . "',
                        usuEstatus     = '" . $estatus . "'
                            
                        WHERE usuCedula   = '" . $cedula . "'";
            }


            return mysql_query($Sql) or die(mysql_error());

        }
    }

    //****** Fin Total Pagina
    //	Eliminar usuarios: *****Breve Descripcion****
    //	Parametros: Id de usuarios
    //	Autor(es): LuisMendez	
    function eliminar($cedula) {
        if ($this->con->conectar() == true) {
            $result = mysql_query("DELETE FROM usuarios WHERE usuCedula = '" . $cedula . "'");
            return true;
        }
    }

    //****** Fin Eliminar usuarios
	
	
	//	Actualizar Clave ****
	//	Autor(es): DanielaRomero	
		function actualizarClave($cedula,$clave) {
			if ($this->con->conectar() == true) {
				$Sql = "UPDATE usuarios SET usuClave = '".$clave."' WHERE usuCedula = '".$cedula."' ";
				return mysql_query($Sql)  or die (mysql_error());
				
			}
		}
	//****** Fin Actualizar Clave
	
	
}

?>
