<?php
	session_start();
	if (!(isset($_SESSION) && array_key_exists('sessionID',$_SESSION) ) ) {
		include "utils.php";
		include "log_data.php";
		$pdo=createVisitorConnection();
		$query=$pdo->prepare('SELECT MIN(AdminRank) FROM User WHERE AdminRank = ?; ');
		$root_admin_lvl =MIN_ROOTADMINLOGLEVEL;
		$query->bindParam(1, $root_admin_lvl , PDO::PARAM_INT);
		$query->execute();		
		if (!$query->errorInfo()[0]){			
			//$_SESSION['sessionID']=startAdminSession();
			$hasRootAdminRankAvailable = $query->fetchColumn() !== MIN_ROOTADMINLOGLEVEL;
		}else{
			$err_info=$query->errorInfo();
		}

		if(isset($_GET['code']) && preg_match('/[0-9]{1,8}/',$_GET['code']) 
			&& isset($_GET['msg'])   ){
			$status=array(
				'code' => $_GET['code'],
				'msg' => answerToLoginupStatus(intval($_GET['code'])) ." : ".$_GET['msg']
			);
		}
	}
	else{
		$sessionID=$_SESSION['sessionID'];
		$user= $_SESSION['user'];
		$err_info="";
		include "g_access.php";
		$g_key = GOOGLEAPIKEY_JAVASCRIPT;
		$hasRootAdminRankAvailable = null;
	}

	include "templates/admin.phtml";
?>