<?php
	include "utils.php";
	include "log_data.php";


	if ((isset($_POST['user']) && (strlen($user=preg_replace('/(^[\s]+)|(\0+)|(s+$)/','',$_POST['user']) ) >= USER_MIN_LENGTH) 
		&& strlen($user)<= USER_MAX_LENGTH) ){
		if (isset($_POST['psswd']) && (strlen($pswd=preg_replace('/(^\s+)|(\0+)|(\s+$)/','',$_POST['psswd']) ) >= PSWD_MIN_LENGTH) 
		 && (strlen($pswd)<= PSWD_MAX_LENGTH )  
		 && (isset($_POST['email']) && ($_POST['email']===filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )) ) {
			$min_lvl=isset($_POST['root']) &&  ($_POST['root']=== CHECKBOX_CHECKEDON)  ? MIN_ROOTADMINLOGLEVEL : MIN_ADMINLOGLEVEL;
			$pdo=createClientConnection();
			$hash=password_hash($pswd, PASSWORD_BCRYPT);
			$status = [
				'code'=>LOGINUP_STATUSFLAG_UNDEFINEDORSUCESS,
				'msg'=>null
			];
			if(htmldata_fielddecode(htmldata_fieldencode($user,true),true)!==htmldata_fielddecode(htmldata_fieldencode($user,false,true),false,true) ){
				$status['code']=LOGUP_STATUSFLAG_USERNAMEGOTINVALIDCHARACTERS;
				$status['msg']= htmldata_fieldencode("Le Nom d'utilisateur \"") . htmldata_fieldencode($user,true,true) . htmldata_fieldencode("\" est différent de ") . htmldata_fieldencode($user,false,true);
			}
			$user=htmldata_fieldencode($user);
			/*var_dump($user
				, $hash
				, $_POST['email']
				, $min_lvl
				, $_POST['root']
			);*/

			$query=$pdo->prepare('
				INSERT INTO User (UserName, PWD, Email, AdminRank, CreationDatetime)
				VALUES ( ?, ?, ?, ?, CAST(NOW() AS DATETIME) );'
			);
			$query->bindParam(1, $user);
			$query->bindParam(2, $hash);
			$query->bindParam(3, $_POST['email']);
			$query->bindParam(4, $min_lvl);
			$query->execute();
			var_dump($err_info=$query->errorInfo());
			if ($err_info[0]){
				if ($err_info[1]===MYSQLSERVERERRORCODE_DUPLICATEKEYENTRY){
					$status['code']=LOGUP_STATUSFLAG_USERNAMEALREADYEXISTENT;
					$status['msg']=answerToLoginupStatus(LOGUP_STATUSFLAG_USERNAMEALREADYEXISTENT);

					header("Location: ./admin.php?code=${status['code']}&msg=${status['msg']}");
					exit();
				}
			}else{
				$user=['UserName'=>$user,'Email'=>$_POST['email'],'AdminRank'=>$min_lvl];
				$query=$pdo->prepare('SELECT CreationDatetime
					FROM User
					WHERE UserName= ?; ');
				$query->execute(array( $user['UserName'] ));
				var_dump($user['CreationDatetime']=$query->fetchColumn(PDO::FETCH_ASSOC));
				//header("Location: ./");
				$sessionID=startLoggedinSession();
				$_SESSION['sessionID']=$sessionID;
				$_SESSION['user']=$user;
				header('Location: ./admin.php');
				exit();
			}

			
		}
	}
?>