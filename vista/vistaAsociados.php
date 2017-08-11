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
            <li><a href="vista/frmTrabajador.php">Asociados Activos</a></li>
            <li><a href="vista/frmEgreso.php">Gestionar Egresos</a></li>
        </ul>
    </div>
<?php
} else {
    Header("Location: index.php");
}
?>