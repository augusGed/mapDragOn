<?php
	include "utils.php";
	include "log_data.php";

	if ((isset($_POST['user']) && (strlen($user=preg_replace('/(^\s+)|\0+|(\s+$)/','',/*utf8_decode*/($_POST['user'])) ) >= USER_MIN_LENGTH) 
		&& strlen($user)<= USER_MAX_LENGTH) ){
		if ((isset($_POST['psswd']) && (strlen($pswd=preg_replace('/()/','',/*utf8_decode*/($_POST['psswd'])) ) >= PSWD_MIN_LENGTH) 
		&& (strlen($pswd)<= PSWD_MAX_LENGTH ) ) ){
			$pdo=createVisitorConnection();
			$min_lvl=/*isset($_POST['root']) &&  ($_POST['root']=== "checked") ? MIN_ROOTADMINLOGLEVEL :*/ MIN_ADMINLOGLEVEL;
			$hash=password_hash($pswd, PASSWORD_BCRYPT/*, [ "cost"=> ]*/);			

			/*var_dump($user, $pswd, $min_lvl, strlen($pswd))*/;
			$query=$pdo->prepare('SELECT ID, PWD, UserName, Email, CreationDateTime, AdminRank 
				FROM User 
				WHERE (UserName = ?) AND (AdminRank <= ?);');
			$query->bindParam(1, $user, PDO::PARAM_STR );
			$query->bindParam(2, $min_lvl, PDO::PARAM_STR );
			$query->execute();
			/*var_dump*/($err_info=$query->errorInfo());
			//var_dump(
			$user=$query->fetchAll(PDO::FETCH_ASSOC)[0]//, password_verify($pswd, $user['PWD']), !intval($err_info[0]))
			;

			/*return*/;
			$status = [
				'code'=>LOGINUP_STATUSFLAG_UNDEFINEDORSUCESS,
				'msg'=>null
			];
			if(!intval($err_info[0])){
				if(isset($user['ID']) && intval($user['ID'])>=0){					
					if(password_verify($pswd, $user['PWD'])){
						$sessionID=startLoggedinSession();
						$_SESSION['sessionID']=$sessionID;
						$_SESSION['user']=$user;
						header('Location: admin.php');
						exit();
					}else{
						$status['code']=LOGINUP_STATUSFLAG_PSWDDIFFERENT;
						$status['msg']='&nbsp;';
						//header('Location: admin.php');
						header("Location: ./admin.php?code=${status['code']}&msg=${status['msg']}");
						exit();
					}
				}else{
					$status['code']=LOGIN_STATUSFLAG_USERNAMEUNEXISTENT;
					$status['msg']='&nbsp;';
					header("Location: ./admin.php?code=${status['code']}&msg=${status['msg']}");
					exit();
				}
			}else {
			}
		
		}else{

		}
	}
	else{

	}

?>