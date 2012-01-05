<?php
	require("library/config.php");

	
	$_SESSION['included_views'] = false;
	
	$db = new Database();
	$f  = new Functions();

	$default_lang = $f->getDefaultLanguage();
	$default_lang_code = $db->getValue("lang_code", "languages", "id", $default_lang);
	
	//get from URL
	$lang_code 	= ($f->getValue("lang_code") == "") ? $default_lang_code : $f->getValue("lang_code");
	
	
	$category 	= $f->getValue("category");
	$node 		= $f->getValue("node");
	$page_num 	= $f->getValue("page_num");
	
	$plugin		= $f->getValue("plugin");
	$plugin_opt = $f->getValue("plugin_options");
	
	
	$userId = $f->logedUser();
	$userGroup = $f->getUserGroup();
	
	define("USER_ID", $userId);
	define("USER_GROUP", $userGroup);
	define("LANG_ID", $lang_id);

	
	if($plugin == "") {
	
		// get language and category id
		$lang_id	= Database::getValue("id", "languages", "lang_code", $lang_code);
		$categoryId	= Database::getValue("id", "category", "url", $category);
		if($categoryId == "" && $category != "") {
			$categoryId = Database::getValue("id", "category", "href", $category);
		}
		
		//based on URL, determine what page to load

		$categoryUrl = ($node == "") ?  Database::getValue("page_id", "category", "id", $categoryId) :
										Database::getValue("page_single", "category", "id", $categoryId); // if node is defined, then load pagetemplate-single
	
		$categoryUrl = ($categoryId == "" && $category != "") ? $category : $categoryUrl;

		$pageUrl = Database::getValue("id", "pages", "url", $category);
		
		$categoryUrl = ($categoryUrl == 0 && $pageUrl != "") ? $category : $categoryUrl;
		
		$pageToLoad = ($category == "") ? "index" : $categoryUrl; // if URL is /ser/ or /, then load INDEX page
		
		$page  = new Page($lang_id, $lang_code, $node, $categoryId, $page_num);
		
		$nodeId = $db->getValue("id", "node", "url", $node);
		define("CATEGORY_ID", $categoryId);
		define("NODE_ID", $nodeId);
		
		
	} else {
		
		$lang_id = Database::getValue("id", "languages", "lang_code", $lang_code);
		
		$node = Database::getValue("node", "plugins", "url", $plugin);
		$categoryId = Database::getValue("category", "plugins", "url", $plugin);
		
		$page = new Page($lang_id, $lang_code, $node, $categoryId, $page_num);
		$page->setPluginOpts($plugin_opt);
		$pageToLoad = $plugin;
	}
	$page->userGroup = $userGroup;
	
	/**
	 * LOAD SETTINGS
	 */
	$settingsQuery = $db->execQuery("SELECT * FROM settings WHERE lang_id = '$lang_id'");
	$settingsValues = mysql_fetch_array($settingsQuery, MYSQL_ASSOC);
	
?>
<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<META name="description" content="<?= $page->getDescription($pageToLoad). $settingsValues['site_description']; ?>">
	<META name="keywords" content="<?= $page->getKeywords($pageToLoad). $settingsValues['site_keywords']; ?>">
	<META name="Author" content="kibo cms">
	<title><?= $page->getTitle($pageToLoad). $settingsValues['site_title']; ?></title>
	<link rel="stylesheet" href="/css/style2.css" type="text/css">
	<?php 
		echo stripslashes($settingsValues['head_js']);
		$page->getHeaderCode($pageToLoad);
	?>
</head>
<body>

<?php
	//echo "Load: ".$pageToLoad;
	echo $page->getPage($pageToLoad);
 	echo stripslashes($settingsValues['footer_js']); 
	echo $page->getFooterCode($pageToLoad); 	
 ?>
</body>
</html>