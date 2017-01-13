<?php
	function createVisitorConnection(){
		$pdo = new PDO('mysql:host=localhost;dbname=MapDragonSlayer','maduser_visitor', 'm1d2s3rM4D5S6R');
		$pdo->exec('SET NAMES UTF8');
		$pdo->exec("USE MapDragonSlayer");
		return $pdo;
	}

	function createClientConnection(){
		$pdo = new PDO('mysql:host=localhost;dbname=MapDragonSlayer','maduser_client', 'M1dP23rdK4nghts');
		$pdo->exec('SET NAMES UTF8');
		$pdo->exec("USE MapDragonSlayer");
		return $pdo;
	}


	function htmldata_fieldencode($str, $is_to_enforce_disallowedflag=false, $is_to_suggest_ignoreflag=false){
		return htmlentities($str, ENT_QUOTES | ENT_HTML5 | ($is_to_enforce_disallowedflag ? 
			ENT_DISALLOWED: $is_to_suggest_ignoreflag ? ENT_IGNORE : 0),'UTF-8');
	}
	function htmldata_fielddecode($str, $is_to_enforce_disallowedflag=false, $is_to_suggest_ignoreflag=false){
		return html_entity_decode($str, ENT_QUOTES | ENT_HTML5 | ($is_to_enforce_disallowedflag ? 
			ENT_DISALLOWED: $is_to_suggest_ignoreflag ? ENT_IGNORE : 0),'UTF-8');
	}
	function htmldata_fieldsencode(&$fields, $is_to_enforce_disallowedflag=false, $is_to_suggest_ignoreflag=false){
		return array_map(function($f,$i){$fields[$i]=htmldata_fieldencode($f, $is_to_enforce_disallowedflag, $is_to_suggest_ignoreflag);}, $fields);
	}
	function htmldata_fieldsdecode(&$fields, $is_to_enforce_disallowedflag=false, $is_to_suggest_ignoreflag=false){
		return array_map(function($f,$i){$fields[$i]=htmldata_fielddecode($f, $is_to_enforce_disallowedflag, $is_to_suggest_ignoreflag);}, $fields);
	}
	function htmldata_loginextraencode($str){
		return htmlentities(
				htmlentities($str, ENT_QUOTES | ENT_HTML5 | ENT_IGNORE,'UTF-8'),
				 ENT_HTML401 | ENT_IGNORE,
				 'UTF-8'
		);
	}
	function htmldata_loginextradecode($str){
		return html_entity_decode(
				html_entity_decode($str, ENT_HTML401 | ENT_IGNORE),
				 ENT_QUOTES | ENT_HTML5 | ENT_IGNORE,'UTF-8',
				 'UTF-8'
		);
	}
	function htmldata_signupextraencode($str){
		return htmlentities(
				htmlentities($str, ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED,'UTF-8'),
				 ENT_HTML401 | ENT_DISALLOWED,
				 'UTF-8'
		);
	}
	function htmldata_signupextradecode($str){
		return html_entity_decode(
				html_entity_decode($str, ENT_HTML401 | ENT_DISALLOWED),
				 ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED,'UTF-8',
				 'UTF-8'
		);
	}

	const CHECKBOX_CHECKEDON='on';
?>