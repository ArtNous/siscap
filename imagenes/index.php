<?php
	session_start();
	$_SESSION['logeado']=FALSE;
	
	header('Location: ../index.php');
?>

