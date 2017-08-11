<?php
class DBManager {
    var $conect;

    var $BaseDatos;
    var $Servidor;
    var $Usuario;
    var $Clave;

    function DBManager() {

		$this->BaseDatos = "siscap"; //Base de Datos
        $this->Servidor = "localhost"; //Servidor
        $this->Usuario = "root"; //usuario
        $this->Clave = "programacion"; //Clave

    }

    function conectar()  {
        if (!($con = @mysql_connect($this->Servidor, $this->Usuario, $this->Clave))) {
            echo "<h1> [:(] Error al conectar con el servidor</h1>";
            exit();
        }
        if (!@mysql_select_db($this->BaseDatos, $con)) {
            echo "<h1> [:(] Error al seleccionar la base de datos</h1>";
            exit();
        }
		mysql_query("SET NAMES 'utf8'");
        $this->conect = $con;
        return true;
    }
}
?>
