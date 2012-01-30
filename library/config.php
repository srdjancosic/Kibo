<?php	
	ob_clean();
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_BASE", "kibocms");
	define("DB_PREFIX", "");
	
	
	define('YOUR_APP_ID', '313862961971829');
	define('YOUR_APP_SECRET', '0b993487cf90f110f8fad972d076eee7');
	
	define("DOMAIN", "http://srdjan.kibocloud.com/");
	
	function __autoload($class_name) {
	global $folderPath;
		
	    include("C:\\Documents and Settings\\Administrator\\Desktop\\kibocms\\Kibo\\".$folderPath."library\\" . strtolower($class_name) . '.php');
	}
	
	//$_SESSION['lang_id'] = 1;
	$lang_id = 1;

	$basePathForUpload = "../../upload";
?>