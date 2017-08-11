<?php
	session_start();
	$_SESSION['logeado']=FALSE;
	Header("Location: ../index.php"); 
	
?>