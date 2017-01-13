<?php
	
	if (!(isset($_SESSION) && array_key_exists('sessionID',$_SESSION) ) ) {
		session_start();
		/*unset $_SESSION['user'];
		unset $_SESSION['sessionID'];*/
		$_SESSION=array();
		session_destroy();
		header('Location: admin.php');
		exit();
	}else{
		header('Location: ./');
		exit();
	}
?>