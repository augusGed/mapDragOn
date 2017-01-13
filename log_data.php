<?php
	const USER_MIN_LENGTH = 6;
	const USER_MAX_LENGTH = 24;
	const PSWD_MIN_LENGTH = 8;
	const PSWD_MAX_LENGTH = 32;

	const MIN_ADMINLOGLEVEL = 3;
	const MIN_ROOTADMINLOGLEVEL = 0;

	const EMAIL_PREFIX_MINLENGTH = 4;
	const EMAIL_SUFFIX_MINLENGTH = 4;

	const LOGINUP_STATUSFLAG_UNDEFINEDORSUCESS = 0x0;
	const LOGINUP_STATUSFLAG_PSWDDIFFERENT = 0x1;
	const LOGINUP_STATUSFLAG_ADMINRANKINVALID = 0x8;
	const LOGUP_STATUSFLAG_USERNAMEALREADYEXISTENT = 0x10;
	const LOGUP_STATUSFLAG_USERNAMEGOTINVALIDCHARACTERS = 0x20;
	const LOGIN_STATUSFLAG_USERNAMEUNEXISTENT = 0x100;

	const MYSQLSERVERERRORCODE_DUPLICATEKEYENTRY = 1062;

	const PHPSESSION_NAME='PSESS';

	function startLoggedinSession(){
		session_start();
		$sid=session_id();
		return $sid;
		$has_set_cookie=setcookie(
			/*name=*/session_name(),
			/*value=*/$sid,
			/*expire*/null,
			/*path=*/null,
			/*domain=*/null,
			/*secure=*/null
		);
		return $has_set_cookie ? session_id() : null;
	}
	function startAdminSession(){
		//session_start();
		return session_id();
	}

	function answerToLoginupStatus($loginup_status){
		switch ($loginup_status){
			case LOGIN_STATUSFLAG_USERNAMEUNEXISTENT:
				return htmldata_fielddecode("Nom d'utilisateur inexistant");
				break;
			case LOGUP_STATUSFLAG_USERNAMEALREADYEXISTENT:
				return htmldata_fielddecode("Nom d'utilisateur déjà existant");
				break;
			case LOGINUP_STATUSFLAG_PSWDDIFFERENT:
				return htmldata_fielddecode("Mot de passe ou Nom d'utilisateur invalide");
				break;
			case LOGINUP_STATUSFLAG_ADMINRANKINVALID:
				return 	"Rang Admin invalide";
				break;				
			case LOGUP_STATUSFLAG_USERNAMEGOTINVALIDCHARACTERS:
				return 	htmldata_fielddecode("Caractère(s) invalide(s) présent(s) dans le nom d'utilisateur");
				break;				
		}
	}
?>