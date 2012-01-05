<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<link href="style.css" type="text/css" rel="stylesheet">
	<link href="style.min.css" type="text/css" rel="stylesheet">
	<link href="custom-theme/jquery-ui-1.8.13.custom.css" type="text/css" rel="stylesheet" id="theme" />
	
	<style>
	.ui-widget-header {
		height: 37px;
	}
	.fileinput-button {
		width: 220px;
		margin-right: 5px;
	}
	</style>
	
	<?php
		$funcNum = $_GET['CKEditorFuncNum'] ;
		$funcNum = ($funcNum == "") ? "-1" : $funcNum;
		
		$field = $_GET['field'];
		$initFolder = $_GET['f'];
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
	<!-- <script type="text/javascript" src="kibofinder.js"></script> -->
	
	<link rel="stylesheet" media="screen" type="text/css" href="colorpicker/css/colorpicker.css" />
	<script type="text/javascript" src="colorpicker/js/colorpicker.js"></script>
	<style>
		body {
			margin: 0px;
			padding: 0px;
		}
		.image {
			margin: 20px;
			float: left;
			clear: both;
			width: auto;
			display: block;
			overflow: hidden;
			background: #000000;
			border: 2px solid #666666;
		}
		.image img {
			padding: 0px;
		}
		.dragImage {
			cursor: move;
		}
		#slider {
			width: 300px;
			height: 30px;
			border: 1px solid #d3d3d3;
			position: relative;
		}
		input.button {
			float: left;
		}
		body {
			background: #ffffff;
		}
		span.result {
			float: left;
			margin: 10px 0px 0px 0px;
			color: #7E00C6;
			font-size: 12px;
			font-style: italic;
		}
		.ui-slider .ui-slider-handle {
			cursor:e-resize; 
			width:15px; 
		    height:30px; 
		    background:#7300c6;
		    overflow: hidden; 
		    position: absolute;
		    top: 0px;
		    border-style:none; 
		}
		.ui-slider-horizontal .ui-state-default {
			background: #7E00C6;
			border: none;
		}
		.colorpicker {
			left: 100px;
		}
	</style>
</head>

<body>
	<div id="all">
	<?php
		$pic = $_GET['pic']; //  		../../upload/telefoni/darko.jpg
		$dest = $_GET['dest']; //   	300x200
		$crop_w = 0; $crop_h = 0;
		if($dest != "_thumb") {
			list($crop_w, $crop_h) = explode("x", $dest);
		} else {
			$crop_w = 100;
			$crop_h = 100;
		}
	?>
	<small>Drag image to position it as you wish or use slider to zoom in/out to get better viewport.</small>
	<br clear="all">
	<div class="image" style="height: <?= $crop_h; ?>px; width: <?= $crop_w; ?>px;">
		<img src="<?= $pic; ?>" border="0" class="dragImage" />
	</div>
	<br clear="all">
	<br clear="all">
	<div id="slider"></div>

	
	<form method="POST" action="test.php">
		<br clear="all">
		<input type="hidden" value="" id="n_w" />
		<input type="hidden" value="" id="n_h" />
		<input type="hidden" value="" id="n_x" />
		<input type="hidden" value="" id="n_y" />
		<input type="hidden" value="" id="bg" />
		
		<input type="button" value="Save" class="button" id="save_button" />
		<input type="button" class="button color" value="Change background color"/>
		<span class="result"></span>
	</form>
	<script>
	$(document).ready(function() {
		$(".dragImage").draggable({
			stop: function() {
				var n_x = parseInt($(".dragImage").css("left"));
				var n_y = parseInt($(".dragImage").css("top"));
				$("#n_x").val(n_x);
				$("#n_y").val(n_y);
			}
		});
		
		$('.color').ColorPicker({
			onChange: function (hsb, hex, rgb) {
				$('.image').css('backgroundColor', '#' + hex);
				$("#bg").val(hex);
			}
		});
		
		var width = $(".dragImage").width();
		var height = $(".dragImage").height();
		
		$("#n_w").val(width);
		$("#n_h").val(height);
		
		$( "#slider" ).slider({
			value: 100,
			max: 300,
			slide: function(event, ui) {
				var perc = ui.value / 100;
				var n_width = Math.round(width * perc);
				var n_height = Math.round(height * perc);
				$(".dragImage").width(n_width).height(n_height);
			},
			change: function(event, ui) {
				var perc = ui.value / 100;
				var n_width = Math.round(width * perc);
				var n_height = Math.round(height * perc);
				$("#n_w").val(n_width);
				$("#n_h").val(n_height);
			}, 
		});
		
		$("#save_button").live("click", function() {
			$("span.result").html("Saving...");
			$.ajax({
				url: 'testwork.php',
				data: 'action=save_new_thumb&pic=<?= $pic; ?>&dest=<?= $dest; ?>&n_w='+$("#n_w").val()+'&n_h='+$("#n_h").val()+'&n_x='+$("#n_x").val()+'&n_y='+$("#n_y").val()+'&bg='+$("#bg").val(),
				async: false,
				type: 'POST',
				success: function(msg) {
					if(msg == "") {
						$("span.result").html("Saved!");
					}
				}
			});
		});
	});
	</script>
	</div>
	
</body>
</html>