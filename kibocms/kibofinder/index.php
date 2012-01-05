<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<link href="style.css" type="text/css" rel="stylesheet">
	<link href="style.min.css" type="text/css" rel="stylesheet">
	<link href="custom-theme/jquery-ui-1.8.16.custom.css" type="text/css" rel="stylesheet" id="theme" />
	
	<style>
	.ui-widget-header {
		
	}
	.fileinput-button {
		width: 220px;
		margin-right: 5px;
		position: relative;
	}
	.ui-dialog-titlebar {
		height: 20px;
	}	
	.fileupload-content .ui-progressbar-value {
		background: #7E00C6;
		height: 19px;
	}
	</style>
	
	<?php
		$funcNum = $_GET['CKEditorFuncNum'] ;
		$funcNum = ($funcNum == "") ? "-1" : $funcNum;
		
		$field = $_GET['field'];
		$initFolder = ($_GET['f'] == "undefined" || $_GET['f'] == "") ? "" : $_GET['f'];
		$field = ($field == "") ? "" : $field;
		echo "<script type='text/javascript'>var funcNum = $funcNum; var field_to_insert = '$field';</script>";
	?>
	
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	
		
	<script src="jquery.fileupload/jquery-1.6.1.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
	<script src="jquery.fileupload/jquery.iframe-transport.js"></script>
	<script src="jquery.fileupload/jquery.fileupload.js"></script>
	<script src="jquery.fileupload/jquery.fileupload-ui.js"></script>

	<script src="/cloud/preset/js/easyTooltip.js" type="text/javascript"></script>
	<script type="text/javascript" src="kibofinder.js"></script>
	
</head>

<body>
	<div id="dialog"></div>
	<div id="imgPreview"></div>
	
	<div id="hLeft">
		<div id="folderSelect"></div>
		<img src="loading.gif" border="0" id="loading">
	
		<div id="content">
		
			<div id="folderOptions">
				
				<div id="fOptions">
					<input type="button" class="button" value="Dimensions" id="optionsFolder">
					<input type="button" class="button" value="Delete folder" id="removeFolder">
					<br clear="all">
					<br clear="all">
					<img src="/cloud/preset/img/search.png" border="0" alt="search" />
					<input type="text" id="search_input" class="text">
				</div>
				
				<div id="newFolder">
					<img src="file_icon/folder_new.gif" title="New folder" class="tooltip">
					<input type="text" id="new_folder_name" class="text tooltip" title="New folder name">
					<input type="button" value="Create" id="newFolderButton" class="button">
					
				</div>
			</div>
	
		</div> <!-- end of content -->
		
		<div id="rightSide">
		</div>	
	</div> <!-- end of hLeft -->
	

	<div id="newFile">
		<div id="fileupload">
		<form action="upload.php" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="current_folder_name" name="current_folder_path">
	        <div class="fileupload-buttonbar">
	            <label class="fileinput-button">
	                <span>Add files...</span>
	                <input type="file" name="files[]" multiple id="uploaded_files_array">
	            </label>
	            <button type="submit" class="start">Start upload</button>
	            <button type="reset" class="cancel">Cancel upload</button>
	        </div>
	    </form>
	    <div class="fileupload-content">
	        <table class="files"></table>
	        <div class="fileupload-progressbar"></div>
	    </div>
	    </div>
	</div>

	

	<div id="folderContent">
	</div>



<script>	
	setCurrentFolder("<?= ($initFolder != "") ? "../../upload/".$initFolder : ""; ?>");
	
</script>

<script id="template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
        <td class="preview"></td>
        <td class="name">${name}</td>
        <td class="size">${sizef}</td>
        {{if error}}
            <td class="error" colspan="2">Error:
                {{if error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else}}${error}
                {{/if}}
            </td>
        {{else}}
            <td class="progress"><div></div></td>
            <td class="start"><button>Start</button></td>
        {{/if}}
        <td class="cancel"><button>Cancel</button></td>
    </tr>
</script>
<script src="jquery.fileupload/application.js"></script>
</body>

</html>