<?php
session_start();
if ($_SESSION['logeado'] == true) {
?>
    <script type='text/javascript'>  
        jQuery(document).ready(function(){
    	
            $("div[id^='tabs']").tabs({
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                        "Documento no encontrado.!" );
                    }
                }
            });
  

        });
    </script>	
    <div id="tabs">
        <ul>
            <li><a href="vista/frmOrganismo.php">Organismo</a></li>
            <li><a href="vista/frmDepartamento.php">Departamento</a></li>
            <li><a href="vista/frmCargos.php">Cargos</a></li>
			<li><a href="vista/frmNivel_educativo.php">Nivel Educativo</a></li>
			<li><a href="vista/frmProfesion.php">Profesi&oacute;n</a></li>
			<li><a href="vista/frmConfiguracion.php"> Config/General</a></li>
        </ul>
    </div>
<?php
} else {
    Header("Location: index.php");
}
?>