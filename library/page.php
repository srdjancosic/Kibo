<?php

class Page extends Functions {

	private $lang_id;
	private $lang_code;
	private $langSQL;
	private $baseURL;
	private $nodeName;
	private $categoryId;
	private $pageNum;
	private $pageURL;
	private $pluginOpts;
	public $userGroup;
	public $nodeURL;

	function __construct($lang_id, $lang_code, $node, $categoryId, $pageNum) {
		$this -> lang_id = $lang_id;
		$this -> lang_code = $lang_code;
		$this -> langSQL = " AND lang_id = '" . $lang_id . "'";

		if (Functions::countAllLanguages() == 1) {
			$this -> baseURL = "/";
		} else {
			$this -> baseURL = "/" . $lang_code . "/";
		}
		$this -> nodeURL = $node;
		$this -> categoryId = $categoryId;
		$this -> pageNum = ($pageNum == "") ? 1 : $pageNum;

		Database::__construct();
	}

	function setPluginOpts($opts) {

		$this -> pluginOpts = $opts;
	}

	function __destruct() {
	}

	function getPage($pageUrl) {

		$this -> pageURL = $pageUrl;

		$query = Database::execQuery("SELECT * FROM `" . DB_PREFIX . "pages` WHERE `url` = '$pageUrl'" . $this -> langSQL);
		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			echo "<div class=\"wrap_header\">";
			$this -> printLeaf($data['header']);
			echo "</div>";

			echo "<div class=\"wrap_content\">";
			$this -> getPageBody($data['id'], $data['content']);
			echo "</div>";

			echo "<div class=\"wrap_footer\">";
			$this -> printLeaf($data['footer']);
			echo "</div>";
		}

	}// end of getPage

	function getTitle($pageURL) {
		$query = Database::execQuery("SELECT * FROM `". DB_PREFIX ."pages` WHERE `url` = '$pageURL'" . $this -> langSQL);

		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			if ($data['page_title'] != "") {// if there is defined title, then show it
				echo stripslashes(strip_tags($data['page_title'])) . " | ";
			} else {// else generate based on category and node

				$nodeTitle = Database::getValue("name", "node", "url", $this -> nodeURL);
				if ($nodeTitle != "")
					echo stripcslashes(strip_tags($nodeTitle)) . " | ";

				$categoryTitle = Database::getValue("name", "category", "id", $this -> categoryId);
				if ($categoryTitle != "")
					echo stripcslashes(strip_tags($categoryTitle)) . " | ";
			}
		}
	}

	function getDescription($pageURL) {
		$query = Database::execQuery("SELECT * FROM `". DB_PREFIX ."pages` WHERE `url` = '$pageURL'" . $this -> langSQL);

		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			if ($data['page_description'] != "") {// if there is defined title, then show it
				echo stripslashes(strip_tags($data['page_description'])) . " | ";
			} else {// else generate based on category and node

				$nodeDescription = Database::getValue("node_description", "node", "url", $this -> nodeURL);
				if ($nodeDescription != "")
					echo stripslashes(strip_tags($nodeDescription)) . " | ";

				$categoryDescription = Database::getValue("category_description", "category", "id", $this -> categoryId);
				if ($categoryDescription != "")
					echo stripslashes(strip_tags($categoryDescription)) . " | ";
			}
		}
	}

	function getKeywords($pageURL) {
		$query = Database::execQuery("SELECT * FROM `". DB_PREFIX ."pages` WHERE `url` = '$pageURL'" . $this -> langSQL);

		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			if ($data['page_keywords'] != "") {// if there is defined title, then show it
				echo stripslashes(strip_tags($data['page_keywords'])) . ", ";
			} else {// else generate based on category and node

				$nodeKeywords = Database::getValue("node_keywords", "node", "url", $this -> nodeURL);
				if ($nodeKeywords != "")
					echo stripslashes(strip_tags($nodeKeywords)) . ", ";

				$categoryKeywords = Database::getValue("category_keywords", "category", "id", $this -> categoryId);
				if ($categoryKeywords != "")
					echo stripslashes(strip_tags($categoryKeywords)) . ", ";
			}
		}
	}

	function getHeaderCode($pageURL) {
		$query = Database::execQuery("SELECT * FROM `". DB_PREFIX ."pages` WHERE `url` = '$pageURL'" . $this -> langSQL);
		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			echo stripslashes($data['add_head']);
		}
	}

	function getFooterCode($pageURL) {
		$query = Database::execQuery("SELECT * FROM `". DB_PREFIX ."pages` WHERE `url` = '$pageURL'" . $this -> langSQL);
		if (mysql_num_rows($query) == 1) {

			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			echo stripslashes($data['add_footer']);
		}
	}

	function printLeaf($leafId) {

		$query = Database::execQuery("SELECT * FROM `" . DB_PREFIX . "leaves` WHERE `id` = '$leafId'" . $this -> langSQL . " AND (FIND_IN_SET(0, user_group) > 0 || FIND_IN_SET($this->userGroup, user_group) > 0) ORDER BY `id` ASC");
		$resultCount = mysql_num_rows($query);
		if ($resultCount > 0) {
			$data = mysql_fetch_array($query, MYSQL_ASSOC);

			$content = $data['content'];
			$content_type = $data['content_type'];
			$css_class = $data['css_class'];
			$css_id = $data['css_id'];
			$this -> nodeName = $data['name'];

			$leafChildren = $this -> leafHasChildren($leafId);
			if ($leafChildren != null) {
				echo "<div class=\"" . $css_class . "\">";
				for ($i = 0; $i < count($leafChildren); $i++) {
					$this -> printLeaf($leafChildren[$i]);
				}
				echo "</div>";
			} else {
				echo $this -> printLeafContent($content_type, $content, $css_class, $css_id);

			}
		}
	}// end of printLeaf

	function printLeafContent($content_type, $content, $css_class, $css_id) {

		switch ($content_type) {
			case "html" :
			// get values for this leaf widget
				list($c_name, $c_display_header, $c_css, $c_content) = explode("|:|", $content);

				$header = ($c_display_header == "1") ? "<h3>" . $c_name . "</h3>" : "";
				$printNewDiv = false;
				if ($c_css != "")
					$printNewDiv = true;

				$css_id_ = ($css_id == "") ? "" : " id=\"" . $css_id . "\"";

				$output .= ($printNewDiv) ? "<div $css_id_ class=\"" . $c_css . "\">\n" : "<div $css_id_ class=\"" . $css_class . "\">\n";
				$output .= stripslashes($header) . "\n";
				$output .= stripslashes($this -> compile($c_content, array())) . "\n";
				$output .= "</div>";
				//($printNewDiv) ? "</div>\n" : "";

				break;
			case "listing" :
				list($c_name, $c_display_header, $c_css, $c_categories) = explode("|:|", $content);

				$c_name = ($c_name == "") ? Database::getValue("name", "category", "id", $c_categories) : $c_name;

				$header = ($c_display_header == "1") ? "<h3>" . $c_name . "</h3>" : "";
				$printNewDiv = false;
				if ($c_css != "")
					$printNewDiv = true;

				$css_id_ = ($css_id == "") ? "" : " id=\"" . $css_id . "\"";

				$output .= ($printNewDiv) ? "<div $css_id_ class=\"" . $c_css . "\">\n" : "<div $css_id_ class=\"" . $css_class . "\">\n";
				$output .= stripslashes($header) . "\n";
				$output .= "<ul>\n";
				$output .= $this -> listCategories($c_categories) . "\n";
				$output .= "</ul>";
				$output .= "</div>";

				break;
			case "node" :
				$output .= $this -> listNodes($content, $css_class, $css_id);
				break;
			case "menu" :
				$c_content = $content;
				$css_id_ = ($css_id == "") ? "" : " id=\"" . $css_id . "\"";
				$output .= "<div $css_id_ class=\"" . $css_class . "\">\n";
				$output .= "<ul>\n";

				$menu_items = unserialize($c_content);

				foreach ($menu_items as $key2 => $menu_item) {
					$openedUL = false;
					foreach ($menu_item as $key => $value) {
						list($item_type, $item_id) = explode(":", $value);

						if ($item_type == "category") {
							$item_name = Database::getValue("name", "category", "id", $item_id);
							$item_link = Database::getValue("url", "category", "id", $item_id);
							$item_href = Database::getValue("href", "category", "id", $item_id);

							$item_link = ($item_href == "") ? $item_link : $item_href;
						} elseif ($item_type == "node") {
							$item_name = Database::getValue("name", "node", "id", $item_id);
							$item_link = Database::getValue("url", "node", "id", $item_id);
							$category_id = Database::getValue("category", "node", "id", $item_id);
							$category_link = Database::getValue("url", "category", "id", $category_id);
							$category_href = Database::getValue("href", "category", "id", $category_id);

							$category_link = ($category_href == "") ? $category_link : $category_href;
							$submenu = "";

							$item_link = $category_link . "/" . $item_link;
						} elseif ($item_type == "home") {
							$item_name = $item_id;
							$item_link = "";
							$submenu = "";
						} elseif ($item_type == "page") {
							$item_name = Database::getValue("name", "pages", "id", $item_id);
							$item_link = Database::getValue("url", "pages", "id", $item_id);
							$submenu = "";
						} elseif ($item_type == "plugin") {
							$item_name = Database::getValue("name", "plugins", "id", $item_id);
							$item_link = "plugin/" . Database::getValue("url", "plugins", "id", $item_id);
							$submenu = "";
						}

						if ($_SERVER['REQUEST_URI'] == "" . $this -> baseURL . "" . $item_link || $_SERVER['REQUEST_URI'] == '/' . $item_link) {
							$aClass = " class=\"active\"";
						} else {
							$aClass = "";
						}

						if ($key == 0) {
							$output .= "<li><a href=\"" . $this -> baseURL . "" . $item_link . "\" " . $aClass . ">" . stripslashes($item_name) . "</a>";
							if (count($menu_item) > 1) {
								$output .= "<ul>";
							}
						} elseif ($key > 0) {
							$output .= "<li><a href=\"" . $this -> baseURL . $item_link . "\" " . $aClass . ">" . stripslashes($item_name) . "</a></li>";
						}
						if ($key == count($menu_item) - 1 && $key != 0) {
							$output .= "</ul>";
						}
					}
				}

				$output .= "</ul>\n";
				$output .= "</div>";

				break;

			case "plugin" :
				list($c_name, $c_css, $c_content, $c_pluginname) = explode("|:|", $content);
				$printNewDiv = false;
				if ($c_css != "")
					$printNewDiv = true;

				$output .= ($printNewDiv) ? "<div class=\"" . $c_css . "\">\n" : "<div class=\"" . $css_class . "\">\n";

				$URLoptions = new stdClass();
				$optionsArr;
				if ($this->pluginOpts != '') {
					$optionsArr = explode("/", $this->pluginOpts);
				} else {
					$optionsArr = array();
				}
				foreach ($optionsArr as $key => $value) {
					eval("\$tmpOptKey = \"opt_$key\";");
					$URLoptions -> ${tmpOptKey} = $value;
				}

				include ("plugin/" . $c_pluginname . "/" . $c_pluginname . ".class.php");

				$c_pluginname = str_replace("-", "_", $c_pluginname);

				$plugin = new ${c_pluginname}();
				$plugin -> __setOptions($URLoptions);

				$output .= $plugin -> execute($c_content);

				$output .= "</div>";

				break;
			case "pluginView" :
				list($c_methodname, $c_pluginname) = explode("|:|", $content);

				if ($_SESSION['included_views'][$c_pluginname] == "opened") {

				} else {
					require ("plugin/" . $c_pluginname . "/" . $c_pluginname . ".plugin.php");
					$_SESSION['included_views'][$c_pluginname] = "opened";
				}

				$output .= ${c_methodname}();

				break;
			case "filelist" :
				list($c_folder, $c_content) = explode("|:|", $content);

				$folder_list = scandir($c_folder);
				if (count($folder_list) > 0) {

					$regularExpression = "/(f:[a-zA-Z\-_|0-9]+)+/";
					$matches = array();

					$new_path = array();
					$file_path = array();
					preg_match_all($regularExpression, $c_content, $matches);
					
					foreach ($matches[0] as $key => $match) {
						$tmp = substr($match, 11, strlen($match));
						if ($tmp != "")
							$new_path[] = $tmp;
					}
					if (count($new_path) > 0) {
						list($_base, $_folder) = explode("/", $c_folder);
						foreach ($new_path as $id => $value) {
							$file_path[$value] = $_base . "/" . $_folder . "/" . $value . "/";
						}

					}

					function cmp($a, $b) {
						if (strlen($a) == strlen($b)) {
							return 0;
						}
						return (strlen($a) < strlen($b)) ? -1 : 1;
					}

					uasort($file_path, "cmp");

					foreach ($folder_list as $key => $file) {
						if ($file != "." && $file != ".." && is_file($c_folder . $file)) {
							$tmp = $c_content;
							foreach ($file_path as $_new_path => $_file_path) {

								if ($_new_path == "size") {
									$file_size = filesize($c_folder . $file);
									$file_size = Functions::sizeOfFile($file_size);
									$tmp = str_replace('f:filename|size', $file_size, $tmp);
								} elseif ($_new_path == "name") {
									list($ext, $fileName) = array_reverse(explode(".", basename($c_folder . $file)));
									$tmp = str_replace('f:filename|name', $fileName, $tmp);
								} else {
									$tmp = str_replace('f:filename|' . $_new_path, $_file_path . $file, $tmp);
								}

							}
							$output .= str_replace('f:filename', $c_folder . $file, $tmp);
						}
					}

					$output = stripslashes($output);

				}
				break;
			case "form" :
				$form_id = $content;

				$form = new View("forms", $form_id);

				$validate_fields = array();

				$output .= "<div class=\"" . $css_class . "\" id=\"$css_id\">\n";
				$multipart = ($form -> file_upload == 1) ? "enctype=\"multipart/form-data\"" : "";

				$output .= "<form method=\"POST\" id=\"$form->identificator\" action=\"" . $form -> action . "\" $multipart>";

				$field_list = $form -> linkWith("form_fields", array("form_id" => $form_id), "ORDER BY `ordering`");

				foreach ($field_list as $key => $field) {

					if ($field -> validation != "")
						$validate_fields[$field -> identificator] = $field -> validation;

					if ($field -> field_type != "hidden")
						$output .= "<p>";

					if ($field -> label != "")
						$output .= "<label>" . $field -> label . "</label>";

					$required = ($field -> required == 1) ? "required" : "";
					switch($field->field_type) {

						case "text" :
							$output .= "<input type=\"text\" name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "value=\"$field->selected_value\" ".
																 "$required ".
																 " >";
							break;
						case "password" :
							$output .= "<input type=\"password\" name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "value=\"$field->selected_value\" ".
																 "$required ".
																  ">";
							break;
						case "hidden" :
							$value = ($field -> constant == "") ? $field -> selected_value : constant($field -> constant);
							$output .= "<input type=\"hidden\" name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "value=\"$value\" ".
																  ">";
							break;
						case "textarea" :
							$output .= "<textarea name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "$required ".
																 ">$field->selected_value</textarea>";
							break;
						case "select" :
							$output .= "<select name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "$required ".
																 ">";
							if ($field -> from_table == 1) {
								list($table, $key, $value) = explode("\n", $field -> value);
								$query = mysql_query("SELECT $key, $value FROM `".DB_PREFIX."$table`");
								while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									$selected = ($field -> selected_value == $data[$key]) ? "selected=\"selected\"" : "";
									$output .= "<option $selected value=\"" . $data[$key] . "\">" . $data[$value] . "</option>";
								}
							} else {
								$array = explode("\n", $field -> value);
								foreach ($array as $key => $attribute) {
									list($key, $value) = explode(";", $attribute);
									$selected = ($field -> selected_value == $key) ? "selected=\"selected\"" : "";
									$output .= "<option $selected value=\"" . $key . "\">" . $value . "</option>";
								}
							}
							$output .= "</select>";
							break;
						case "select_multiple" :
							$output .= "<select multiple=\"multiple\" name=\"" . $field -> name . "[]\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "$required ".
																 ">";
							if ($field -> from_table == 1) {
								list($table, $key, $value) = explode("\n", $field -> value);
								$query = mysql_query("SELECT $key, $value FROM `".DB_PREFIX."$table`");
								while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									$selected = ($field -> selected_value == $data[$key]) ? "selected=\"selected\"" : "";
									$output .= "<option $selected value=\"" . $data[$key] . "\">" . $data[$value] . "</option>";
								}
							} else {
								$array = explode("\n", $field -> value);
								foreach ($array as $key => $attribute) {
									list($key, $value) = explode(";", $attribute);
									$selected = ($field -> selected_value == $key) ? "selected=\"selected\"" : "";
									$output .= "<option $selected value=\"" . $key . "\">" . $value . "</option>";
								}
							}
							$output .= "</select>";
							break;
						case "checkbox" :
							$checked = ($field -> value == $field -> selected_value) ? "checked=\"checked\"" : "";
							$output .= "<input type=\"checkbox\" name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "value=\"$field->value\" ".
																 "$checked ".
																 "$required ".
																  ">";
							break;
						case "radiobutton" :
							$checked = ($field -> value == $field -> selected_value) ? "checked=\"checked\"" : "";
							$output .= "<input type=\"radio\" name=\"" . $field -> name . "\" " . "id=\"$field->identificator\" " . "class=\"$field->class\" " . "value=\"$field->value\" " . "$checked " . "$required " . ">";
							break;
						case "button" :
							$output .= "<input type=\"button\" name=\"" . $field -> name . "\" ".
																 "id=\"$field->identificator\" ".
																 "class=\"$field->class\" ".
																 "value=\"$field->selected_value\" ".
																  stripslashes($field -> value) . ">";
							break;
						case "datepicker" :
							$output .= "<input type=\"text\" name=\"" . $field -> name . "\"
																 id=\"$field->identificator\"
																 class=\"$field->class\"
																 value=\"$field->value\">";
							break;
						case "colorpicker" :
							$output .= "<input type=\"text\" name=\"" . $field -> name . "\"
																 id=\"$field->identificator\"
																 class=\"$field->class\"
																 value=\"$field->value\">";
							break;
						case "fileupload" :
							$output .= "<input type=\"file\" name=\"" . $field -> name . "\"
																 id=\"$field->identificator\"
																 class=\"$field->class\"
																 value=\"$field->value\">";
							break;
					}

					if ($field -> hint != "")
						$output .= "<span class=\"hint\">" . $field -> hint . "</span>";

					if ($field -> field_type != "hidden")
						$output .= "</p>";
				}

				$output .= "<input type=\"submit\" value=\"" . $form -> submit_value . "\" class=\"$form->submit_class\" id=\"$form->submit_id\" />";
				$output .= "</form>";

				// jQuery form validation
				if (count($validate_fields) > 0) {
					$i = 1;
					$output .= "<script type=\"text/javascript\">";
					$output .= "$(document).ready(function() {";
					$output .= "$('#" . $form -> identificator . "').submit(function(e) {";
					foreach ($validate_fields as $identificators => $regExp) {

						$query = mysql_query("SELECT error_message FROM `".DB_PREFIX."form_fields` WHERE identificator = '$identificators' AND form_id = '$form->id'");
						$data = mysql_fetch_array($query, MYSQL_ASSOC);
						$error_message = $data['error_message'];
						//$output .= "e.preventDefault();\n";
						$output .= "var pattern_$i = " . stripslashes($regExp) . ";\n";
						$output .= "var val_$i = jQuery.trim($('#" . $identificators . "').val());";
						$output .= "var result_$i = pattern_$i.test(val_$i);";
						$output .= "if(!result_$i) { " . "if($('.error_$identificators').length == 0) {" . "$('#$identificators').parent().append(\"<span class='error error_$identificators'>$error_message</span>\"); " . "}" . " return false; }" . " else { $('#$identificators').parent().find('span.error').each(function() { $(this).remove(); }); }";

						$i++;
					}
					$output .= "return true;";
					$output .= "});";
					$output .= "});";
					$output .= "</script>";
				}

				$output .= "</div>";

				break;
			case "tagsearch" :
				$output = $this -> listNodesByTag($content, $css_class, $css_id);
				break;
			default :
			//echo "sadrzaj";
				break;
		}

		return $output;

	}// end of printLeaf

	function leafHasChildren($leafId) {

		$query = Database::execQuery("SELECT * FROM " . DB_PREFIX . "leaves WHERE parent = '$leafId' " . $this -> langSQL . " ORDER BY `order` ASC");
		if (mysql_num_rows($query) == 0)
			return null;
		else {
			$children = array();
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {

				array_push($children, $data['id']);
			}
			return $children;
		}

	}// end of leafHasChildren

	function listCategories($selected) {

		$output = "";

		$query = Database::execQuery("SELECT * FROM " . DB_PREFIX . "category WHERE parent = '" . $selected . "'");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$href = ($data['href'] == "") ? $this -> baseURL . $data['url'] : $data['href'];
			$output .= "<li><a href=\"" . $href . "\">" . $data['name'] . "</a></li>\n";
		}

		return $output;
	}// end of listCategories

	function listNodes($content, $css_class, $css_id) {
		list($c_name, $c_display_header, $c_css, $c_categories, $c_content, $c_limit, $c_orderbyfield, $c_ordertype, $c_pagination) = explode("|:|", $content);

		$additionalSQL = "";

		/*$this->debug($c_categories);
		 $this->debug($this->categoryId);
		 $this->debug($this->pageURL);
		 $this->debug($this->nodeName);
		 $this->debug($this->nodeURL);
		 */
		$notNode = false;

		if ($this -> categoryId == "" && $this -> nodeName != $this -> pageURL) {
			$additionalSQL .= " WHERE category in (" . $c_categories . ")";
		} elseif ($this -> categoryId != "" && $c_categories != "") {
			$additionalSQL .= " WHERE category in (" . $c_categories . ")";
			$notNode = true;
		} else {
			$additionalSQL .= " WHERE category = '" . $this -> categoryId . "'";
		}

		//echo $additionalSQL;

		if ($this -> nodeURL != "" && $notNode == false) {// && $this->nodeName == $this->pageURL) {
			$additionalSQL .= " AND url = '" . $this -> nodeURL . "'";
		}

		if (strtolower($c_ordertype) == "rand") {
			$orderSQL .= " ORDER BY RAND()";
		} else {
			if ($c_limit >= 1) {
				$orderSQL .= " ORDER BY `" . $c_orderbyfield . "`";
				$orderSQL .= ($c_ordertype == "") ? "" : " " . $c_ordertype;
			}
		}

		$querySQL = "SELECT * FROM " . DB_PREFIX . "node " . $additionalSQL . " " . $orderSQL;

		//$this->debug($querySQL);

		$header = ($c_display_header == "1") ? "<h3>" . $c_name . "</h3>" : "";
		$css = ($c_css == "") ? " class=\"" . $css_class . "\"" : " class=\"" . $c_css . "\"";

		$css_id_ = ($css_id == "") ? "" : " id=\"" . $css_id . "\"";

		$output = "<div $css_id_ " . $css . ">\n";
		$output .= stripslashes($header) . "\n";

		$resultCount = $this -> numRows($querySQL);

		if ($c_pagination != "") {

			if ($resultCount > $c_limit) {
				$offset = ($this -> pageNum - 1) * $c_limit;
				$paginationSQL = " LIMIT " . $offset . ", " . $c_limit;

				$categoryURL = Database::getValue("url", "category", "id", $this -> categoryId);

				$url = $this -> baseURL . $categoryURL . "/";
				list($prevLink, $nextLink) = explode(":", $c_pagination);
				$paginationURL = $this -> createPagination($url, $this -> pageNum, $resultCount, $c_limit, $this -> lang_id, $prevLink, $nextLink);
			}

		} else {
			$paginationSQL = " LIMIT " . $c_limit;
			$paginationURL = $c_pagination;
		}

		//$this->debug($querySQL.$paginationSQL);

		$query = Database::execQuery($querySQL . $paginationSQL);
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {

			$subquery = Database::execQuery("SELECT * FROM " . DB_PREFIX . "custom_fields WHERE node = '" . $data['id'] . "'");
			$subdata = array();
			while ($row = mysql_fetch_array($subquery, MYSQL_ASSOC)) {
				$subdata[$row['name']] = $row['value'];
			}
			$c_content = $this -> compileCustomFields($c_content, $subdata) . "\n";
			$output .= $this -> compile($c_content, $data) . "\n";
		}

		$output .= $paginationURL . "\n";
		$output .= "</div>";

		return $output;

	}// end of listNodes

	function getPageBody($pageId, $leafId) {

		$query = Database::execQuery("SELECT * FROM " . DB_PREFIX . "leaves WHERE id = '$leafId'" . $this -> langSQL . " AND (FIND_IN_SET(0, user_group) > 0 || FIND_IN_SET($this->userGroup, user_group) > 0) ORDER BY `order` ASC");

		$children = $this -> leafHasChildren($leafId);

		$pageCssClass = Database::getValue("css_class", "leaves", "id", $leafId);
		echo "<div class=\"" . $pageCssClass . "\">";
		/*
		 echo "<pre>";
		 print_r($children);
		 echo "</pre>";
		 echo "<hr>";
		 */
		for ($i = 0; $i < count($children); $i++) {

			//echo "<pre>";
			//echo "Parent: ".$children[$i]."<br>";

			$leafCssClass = Database::getValue("css_class", "leaves", "id", $children[$i]);

			echo "<div class=\"" . $leafCssClass . "\">";

			$subChildren = $this -> leafHasChildren($children[$i]);

			if (count($subChildren) > 0) {
				for ($j = 0; $j < count($subChildren); $j++) {
					$this -> printBasicLeaf($subChildren[$j]);
					//$this->getPageBody($pageId, $children[$i]);
				}
			}

			$subQuery = Database::execQuery("SELECT * FROM " . DB_PREFIX . "pages_leaves WHERE page_id = '$pageId' AND leaf_destination = '$children[$i]' ORDER BY `order` ASC");
			$subResultCount = mysql_num_rows($subQuery);
			while ($subData = mysql_fetch_array($subQuery, MYSQL_ASSOC)) {

				//print_r($subData);
				$this -> printLeaf($subData['leaf_id']);
			}
			echo "</div>";
		}

		echo "</div>";

	}// end of getPageBody

	function printBasicLeaf($leafId) {
		$leafContentType = Database::getValue("content_type", "leaves", "id", $leafId);
		$leafContent = Database::getValue("content", "leaves", "id", $leafId);
		$leafCssClass = Database::getValue("css_class", "leaves", "id", $leafId);
		$leafCssId = Database::getValue("css_id", "leaves", "id", $leafId);

		if ($leafContentType != "") {
			//echo "Stampam leaf: ".$leafId."<br />";
			echo $this -> printLeafContent($leafContentType, $leafContent, $leafCssClass, $leafCssId);
		}
	}

	function compile($kml, $data) {

		$regularExpression = "/(f:)([a-zA-Z\-_|0-9]+)+/";
		$matches = array();

		preg_match_all($regularExpression, $kml, $matches);

		//$this->debug($matches[2]);

		$str = "";
		for ($i = 0; $i < count($matches[0]); $i++) {
			$match = $matches[2][$i];
			$value = $data[$match];

			$prematch = substr($match, 0, 7);

			if ($match == "url") {
				$lang_id = Database::getValue("lang_id", "node", "url", $value);
				$node_id = Database::getValue("id", "node", "url", $value);
				$category_id = Database::getValue("category", "node", "url", $value);

				$lang_code = Database::getValue("lang_code", "languages", "id", $lang_id);
				$category_url = Database::getValue("url", "category", "id", $category_id);
				$value = $this->baseURL . $category_url . "/" . $value . "-" . $node_id;
			} elseif ($match == "lang_id") {
				$value = Database::getValue("lang_code", "languages", "id", $value);
			} elseif ($match == "category") {
				$value = Database::getValue("url", "category", "id", $value);
			} elseif ($match == "picture") {
				$value = $data['picture'];
			} elseif ($match == "date") {
				$value = date(DATE_FORMAT, strtotime($value));
			} elseif ($match == "REQUESTURI") {
				$pageURL = 'http';
				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";
				}
				$pageURL .= "://";
				if ($_SERVER["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
				}
				$value = $pageURL;
			} elseif ($match == "tags") {
				$value = $this -> listTags(Database::getValue("id", "node", "url", $data['url']));
			}

			if ($prematch == "picture") {
				$new_path = substr($match, 8, strlen($match));
				if ($new_path != "") {
					list(, $_base, $_folder, $_file) = explode("/", $data['picture']);
					$tmp_path = "/" . $_base . "/" . $_folder . "/" . $new_path . "/" . $_file;
					//str_replace($base, $new_path, $data['picture']);
					$value = $tmp_path;
				}

			} elseif ($prematch == "shorten") {
				$new_match = substr($match, 8, strlen($match));
				$new_match = explode("|", $new_match);
				$value = $this -> cutText($data[$new_match[0]], $new_match[1]);
			}

			$kml = str_replace("f:" . $match, $value, $kml);
		}

		return stripslashes($kml);
	}

	function compileCustomFields($kml, $data) {

		$regularExpression = "/(c:)([a-zA-Z0-9\-_]+)+/";
		$matches = array();

		preg_match_all($regularExpression, $kml, $matches);

		//$this->debug($matches);
		//$this->debug($data);

		for ($i = 0; $i < count($matches[0]); $i++) {

			$match = $matches[2][$i];
			$value = $data[$match];

			if ($match == "lang_id") {
				$value = Database::getValue("lang_code", "languages", "id", $value);
			} elseif ($match == "category") {
				$value = Database::getValue("url", "category", "id", $value);
			} elseif ($match == "date") {
				$value = date(DATE_FORMAT, strtotime($value));
			}

			$kml = str_replace("c:" . $match, $value, $kml);
		}

		return stripslashes($kml);
	}

	function debug($input) {
		echo "<pre>";
		if (is_array($input)) {
			print_r($input);
		} else {
			var_dump($input);
		}
		echo "</pre>";
	}

	function listTags($id) {
		$query = Database::execQuery("SELECT * FROM `".DB_PREFIX."tags` WHERE `node_id` = '$id'");

		$rows = Database::numRows("SELECT * FROM `". DB_PREFIX ."tags` WHERE `node_id` = '$id'");
		$string = "";
		$TmpcategoryId = Database::getValue("category", "node", "id", $id);
		$TmpcategoryURL = Database::getValue("url", "category", "id", $TmpcategoryId);

		for ($i = 0; $i < $rows; $i++) {
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			$string .= "<a href=\"".$this->baseURL."taglist/" . $TmpcategoryURL . ":" . $data['url'] . "\">";
			$string .= $data['name'];
			$string .= "</a>";
			if ($i != $rows - 1)
				$string .= ", ";
		}

		return $string;
	}

	function listNodesByTag($content, $css_class, $css_id) {
		list($categoriURL, $tagURL) = explode(":", $this -> nodeURL);
		$additional_sql = "";
		if ($categoriURL != "") {
			$cat_id = Database::getValue("id", "category", "url", $categoriURL);
			$additional_sql = " AND node.category = '$cat_id'";
		}
		$output .= "<div class=\"" . $css_class . "\" id=\"" . $css_id . "\">";
		$query = Database::execQuery("SELECT node.* FROM `".DB_PREFIX."node` JOIN 
										`".DB_PREFIX."tags` ON node_id = node.id 
										WHERE tags.url= '$tagURL'" . $additional_sql);
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {

			$subquery = Database::execQuery("SELECT * FROM " . DB_PREFIX . "custom_fields WHERE node = '" . $data['id'] . "'");
			$subdata = array();
			while ($row = mysql_fetch_array($subquery, MYSQL_ASSOC)) {
				$subdata[$row['name']] = $row['value'];
			}
			$c_content = $this -> compileCustomFields($content, $subdata) . "\n";
			$output .= $this -> compile($c_content, $data) . "\n";
		}
		$output .= "</div>";

		return $output;
	}

} // end of pages.class
?>