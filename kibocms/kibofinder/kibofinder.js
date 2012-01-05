$(function() {
	$("#search_input").live("keyup", function(e) {
		if(e.keyCode == 27) {
			$(this).val("");
			$("a#fileOptions").show();
		} else {
			var val = jQuery.trim($(this).val());
			if(val == "") {
				$("a#fileOptions").show();
			} else {
				$("a#fileOptions").hide();
				$("#folderContent a:contains("+val+")").show();
			}
		}
	});
	
	$("a.renameFile").live("click", function() {
		var name = $("h3.name span").html();
		$("h3.name").html("<input type='text' value='"+name+"' oldname='"+name+"' class='text inlineedit' />");
		$("input.inlineedit").focus();
		$(this).hide();
	});
	
	$("input.inlineedit").live("keyup", function(e) {
		var oldname = $(this).attr("oldname");
		var name = $(this).val();
		
		if(e.keyCode == 27) {
			$("h3.name").html("<span>"+oldname+"</span>  <small>(<a href='#' class='renameFile'>Rename</a>)</small>");
		} else if(e.keyCode == 13) {
			$.ajax({
				url: 'work.php',
				data: 'action=renameFile&file='+oldname+'&new='+name+'&folder='+$("#current_folder_name").val(),
				type: 'POST',
				async: false,
				success: function(data) {
					if(data != 1) {
						alert("Greska prilikom brisanja dimenzije! Proverite da li je folder prazan!");
					} else {
						$("#rightSide h3.name").html(name);
						setCurrentFolder($("#current_folder_name").val());
					}
				}
			});
		}
	});
	
	$("input.inlineedit").live("blur", function() {
		var name = $(this).val();
		var oldname = $(this).attr("oldname");
		
		$.ajax({
			url: 'work.php',
			data: 'action=renameFile&file='+oldname+'&new='+name+'&folder='+$("#current_folder_name").val(),
			type: 'POST',
			async: false,
			success: function(data) {
				if(data != 1) {
					alert("Greska prilikom brisanja dimenzije! Proverite da li je folder prazan!");
				} else {
					$("#rightSide h3.name").html(name);
					setCurrentFolder($("#current_folder_name").val());
				}
			}
		});
	
	});
	
	$("a.removeFile").live("click", function() {
		if(confirm('Are you sure?')) {
			
			$.ajax({
				url: 'work.php',
				data: 'action=removeFile&file='+$(this).attr("link")+'&folder='+$("#current_folder_name").val(),
				type: 'POST',
				async: false,
				success: function(data) {
					if(data != 1) {
						alert("Error while deleting file!");
					} else {
						setCurrentFolder($("#current_folder_name").val());
					}
				}
			});
			
		}
		
	});
	$("#imgPreview").dialog({
		modal: true,
		autoOpen: false,
		width: 640,
		height: 480,
		title: 'Preview image'
	});
	$(".previewImage").live("click", function() {
		var pic = $(this).attr("link");
		var randomnumber=Math.floor(Math.random()*11)
		$("#imgPreview").html("<img src='"+pic+"?rand="+randomnumber+"' border='0' alt='' class='viewingImg' />").css("text-align", "center");
		$("#imgPreview").dialog("open");
		
	});
	
	
	/** EDITING IMAGE **/
	$("#dialog").dialog({
		modal: true,
		autoOpen: false,
		title: 'Edit image',
		beforeClose: function(event, ui) {
			$("#loading").show();
			
			var file = $(document).data("editing");
			var current = $("#current_folder_name").val();
			setCurrentFolder(current);
			$.ajax({
				url: 'work.php',
				data: 'action=loadContent&options=fileOptions&current='+current+'&file='+file,
				type: 'POST',
				async: false,
				success: function(data) {
					$("#rightSide").html(data);
				}
			});
			$("#loading").hide();
		}
	});
	
	$(".editImage").live("click", function() {
		
		var pic = $(this).attr("link");
		var dest = $(this).attr("folder");
		$(document).data("editing", $(this).attr("file"));
		var w = 0, h = 0;
		if(dest == "_thumb") { w = 200; h = 500; } else {
			var tmp = dest.split("x");
			w = parseInt(tmp[0]);
			h = parseInt(tmp[1]);
		}
		w1 = w + 350;
		h1 = 500;
		$("#dialog").dialog("option", "width", w1);
		$("#dialog").dialog("option", "height", h1);
		w2 = w + 300;
		h2 = 450;
		$("#dialog").html("<iframe style='border: 0px; width: "+w2+"px; height:"+h2+"px;' src='test.php?pic="+pic+"&dest="+dest+"'></iframe>");
		
		$("#dialog").dialog('open');
		
	});
	
	/** END OF EDITING IMAGE **/
	
	
	
	$("#folder_list").live("change", function() {
		setCurrentFolder($(this).val());
	});
	
	$("#newFolderButton").live("click", function() {
		createFolder();
	});
	
	$("#removeFolder").live("click", function() {
		if(confirm('Are you sure?')) {
			removeFolder();
		}
	});
	
	$(".removeDimension").live("click", function() {
		if(confirm('Jeste sigurni?')) {
			$.ajax({
				url: 'work.php',
				data: 'action=removeDimension&file='+$(this).attr("link"),
				type: 'POST',
				async: false,
				success: function(data) {
					if(data != 1) {
						alert("Greska prilikom brisanja dimenzije! Proverite da li je folder prazan!");
					}
				}
			});
			loadContent("folderOptions");
		}
	});
	
	$("#optionsFolder").live("click", function() {
		loadContent("folderOptions");
	});
	
	$("#new_dimmension").live("click", function() {
		var new_x = $("#new_x").val();
		var new_y = $("#new_y").val();
		var current = $("#current_file_name").val();
		$.ajax({
			url: 'work.php',
			data: 'action=createDimmension&new_x='+new_x+'&new_y='+new_y+'&current='+$("#current_folder_name").val(),
			type: 'POST',
			async: false,
			success: function(data) {
				
			}
		});
		loadContent("folderOptions");
	});
	
	$("#fileOptions").live("click", function() {
		$("#loading").show();
		var file = $(this).attr("file");
		var current = $("#current_folder_name").val();
		$.ajax({
			url: 'work.php',
			data: 'action=loadContent&options=fileOptions&current='+current+'&file='+file,
			type: 'POST',
			async: false,
			success: function(data) {
				$("#rightSide").html(data);
			}
		});
		$("#loading").hide();
	});
	
	$(".insertFile").live("click", function() {
		var path = $(this).attr("link");
		path = path.replace("../../", "/");
		
		var parentWindow = ( window.parent == window ) ? window.opener : window.parent;
		if(funcNum != -1) {
			parentWindow.CKEDITOR.tools.callFunction(funcNum, path);
		} else {
			parentWindow.document.getElementById(field_to_insert).value = path;
		}
		window.close();
	});
	
	$("#close_dimmension").live("click", function() {
		$("#rightSide").html("");
	});
	
});
/*****

*****/
	function loadContent(options) {
		
		$.ajax({
			url: 'work.php',
			data: 'action=loadContent&options='+options+'&current='+$("#current_folder_name").val(),
			type: 'POST',
			async: false,
			success: function(data) {
				$("#rightSide").html(data);
			}
		});
		
	}


	function setCurrentFolder(folder) {
		$("#loading").show();
		loadFolderList(folder);
		$("#rightSide").html("");
		$("#current_folder_name").val(folder);
		if(folder != "") {
			getFolderContent(folder);
		}
		
		
		if(folder == "root" || folder == "") {
			$("#newFile").hide();
			$("#fOptions").hide();
			$("#newFolder").show();
			$("#rightSide").html("");
			$("#folderContent").html("");
			
		} else {
			$("#newFile").show();
			$("#fOptions").show();
			$("#newFolder").hide();
			var numImages = $("#folderContent").find("a#fileOptions");
			if(numImages.length == 0) 
				$("#removeFolder").show(); //.attr("disabled", ""); 
			else 
				$("#removeFolder").hide(); //.attr("disabled", "disabled");
		}
		$("#loading").hide();
	}

	function loadFolderList(current) {
		$.ajax({
			url: 'work.php',
			data: 'action=loadFolderList&current='+current,
			type: 'POST',
			async: false,
			success: function(data) {
				$("#folderSelect").html(data);
			}
		});
	}
	
	function createFolder() {
		var name = jQuery.trim($("#new_folder_name").val());
		
		$.ajax({
			url: 'work.php',
			data: 'action=createFolder&name='+name,
			type: 'POST',
			async: false,
			success: function(data) {
				if(data != 0) {
					setCurrentFolder(data);
					$("#new_folder_name").val("");
				} else {
					alert('Došlo je do greške prilikom pravljenja foldera!');
				}
			}
		});
	}
	
	function removeFolder() {
		var current = $("#current_folder_name").val();
		$.ajax({
			url: 'work.php',
			data: 'action=removeFolder&current='+current,
			type: 'POST',
			async: false,
			success: function(data) {
				if(data != 0) {
					setCurrentFolder("");
				} else {
					alert('Došlo je do greške prilikom brisanja foldera!');
				}
			}
		});
	}
	
	function getFolderContent() {
		var current = $("#current_folder_name").val();
		if(current != "root") {
			$.ajax({
			url: 'work.php',
			data: 'action=getFolderContent&current='+current,
			type: 'POST',
			async: false,
			success: function(data) {
				if(data != 0) {
					$("#folderContent").html(data);
				} else {
					//alert('Došlo je do greške prilikom otvaranja foldera!');
					//setCurrentFolder("");
					$("#folderContent").html("Nema fajlova");
				}
			}
		});
		}
	}
	
	/****
	AJAX FILE UPLOAD
	****/
	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
			$("#fileToUpload").hide();
			$("#new_file_name").hide();
		})
		.ajaxComplete(function(){
			$(this).hide();
			$("#fileToUpload").show();
			$("#new_file_name").show();
		});

		$.ajaxFileUpload
		(
			{
				url:'ajaxfileupload/doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data: {
					destination: $("#current_folder_name").val(),
					new_file_name: $("#new_file_name").val()
				},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							//alert("Er: "+data.error);
						}else
						{
							//alert("M: "+data.msg);
							$("#new_file_name").val("");
							$("#fileToUpload").val("");
							getFolderContent();
						}
					}
					
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}
	
	$.extend($.expr[':'], {
	  'contains': function(elem, i, match, array)
	  {
	    return (elem.textContent || elem.innerText || '').toLowerCase()
	    .indexOf((match[3] || "").toLowerCase()) >= 0;
	  }
	});
	
	
/****
STANDALONE USAGE
****/

	function kiboFinderAlone(url, field, folder) {
		popupWindow = window.open(
			url+'?field='+field+'&f='+folder,'popUpWindow','height=650,width=1100,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
	}