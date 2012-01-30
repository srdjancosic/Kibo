jQuery(document).ready(function() {
	
	
	$("#select_category").live("change", function() {
		location.href='index.php?parent='+$(this).val();
	});
	
	$("#category_name_filter").live("keyup", function() {
		var val = jQuery.trim($(this).val());
		if(val == "") {
			$(".box_1").show();
		} else {
			$(".box_1").hide();
			$(".box_1 .inner:containsi("+val+")").parent().show();
		}
	});

});

	
	
	function saveContent(type, leafId, langId) {
		
		if(type == "html") {
			var name = jQuery.trim($("#c_name_"+langId).val());
			var css = jQuery.trim($("#css_"+langId).val());
			var content = jQuery.trim($("#content_c_"+langId).val());
			
			content = content.replace(/&/g, "{AND}");
			
			var string = name+"|:|"+display_title+"|:|"+css+"|:|"+content;
			
			
		} else if (type == "listing") {
			var name = jQuery.trim($("#c_name_"+langId).val());
			var display_title = jQuery.trim($("#display_title_"+langId).val());
			var css = jQuery.trim($("#css_"+langId).val());
			var selected = "";
			$('input.cat_'+langId+':checked').each(function() { selected += $(this).val()+","; });
			selected = selected.substr(0, selected.length - 1);
			
			var string = name+"|:|"+display_title+"|:|"+css+"|:|"+selected;
			
			
		} else if (type == "node") {
			
			var name = jQuery.trim($("#c_name_"+langId).val());
			var display_title = jQuery.trim($("#display_title_"+langId).val());
			var css = jQuery.trim($("#css_"+langId).val());
			var content = jQuery.trim($("#content_c_"+langId).val());
			var limit = jQuery.trim($("#limit_"+langId).val());
			var orderbyfield = jQuery.trim($("#orderbyfield_"+langId).val());
			var ordertype = jQuery.trim($("#ordertype_"+langId).val());
			var pagination = jQuery.trim($("#pagination_c_"+langId).val());
			var selected = "";
			$('input.cat_'+langId+':checked').each(function() { selected += $(this).val()+","; });
			selected = selected.substr(0, selected.length - 1);
			
			content = content.replace(/&/g, "{AND}");
			var string = name+"|:|"+display_title+"|:|"+css+"|:|"+selected+"|:|"+content+"|:|"+limit+"|:|"+orderbyfield+"|:|"+ordertype+"|:|"+pagination;
		} else if (type == "menu") {
			
			var string = "";
			
		} else if (type == "form") {
			
			var form = jQuery.trim($("#c_form_"+langId).val());
			
			var string = form;
			
		} else if (type == "filelist") {
			
			var folder = jQuery.trim($("#c_folder_"+langId).val());
			var content = jQuery.trim($("#content_c_"+langId).val());
			
			var string = folder+"|:|"+content;
			
		} else if (type == "slider") {
			
			var name = jQuery.trim($("#c_name").val());
			var css = jQuery.trim($("#css").val());
			var content = jQuery.trim($("#content_c").val());
			
			var string = name+"|:|"+css+"|:|"+content;
		} else if (type == "plugin") {
			var pluginname = jQuery.trim($("#c_plugin").val());
			
			var string = pluginname;
			
		} else if (type == "pluginView") {
			var methodName = jQuery.trim($("#c_name_"+langId).val());
			var pluginName = jQuery.trim($("#c_plugin_"+langId).val());
			
			var string = methodName+"|:|"+pluginName;
		} else if (type == "tagsearch") {
			var content = jQuery.trim($("#content_c_"+langId).val());
			
			var string = content;
		}
		
		$("#loader_"+langId).show();
		$.ajax({
			url: 'leaveswork.php',
			data: 'action=saveLeafContent&id='+leafId+'&content_name='+type+'&content='+string,
			type: 'POST',
			success: function(data) {
				$("#loader_"+langId).fadeOut();
			}
		});
	}
	
	function removeContent(leafId, langId) {
		
		$("#loader_"+langId).show();
		$.ajax({
			url: 'leaveswork.php',
			data: 'action=removeLeafContent&id='+leafId,
			type: 'POST',
			success: function(data) {
				$(".placeholder_"+langId).html("<br><br><br><br>");
				$(".placeholder_"+langId).droppable("enable");
				$("#loader_"+langId).fadeOut();
			}
		});
	}
	