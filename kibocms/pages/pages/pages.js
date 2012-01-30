$(document).ready(function(){
	
	$("#search_input").live("keyup", function() {
		var val = jQuery.trim($(this).val());
		if(val == "") {
			$(".box_1").show();
		} else {
			$(".box_1").hide();
			$(".box_1 .inner:containsi("+val+")").parent().show();
		}
	});
	
	$(".remove_page_view").live("click", function(){
		var id = $(this).attr("category_id");
		var lang_id = $(this).attr("lang_id");
		
		if(confirm("Are you sure?")) {
			$(this).find('img').attr("src", "/kibocms/preset/assets/loading.gif");	
			$.ajax({
				url: 'pageswork.php',
				data: 'action=removePageView&id='+id,
				type: 'POST',
				success: function(data) {
					$("div#asigne_page_"+lang_id).find("li[id='category_"+id+"']").fadeOut().remove();
				}
			});
		}
		return false;
	});
	
	
	$(".showhide_all").live("click", function() {
		var lang = $(this).attr("lang");
		
		$("#elements_available_"+lang).toggle();
	});
	
	$(".showhide_setting").live("click", function() {
		var lang = $(this).attr("lang");
		
		$("#page_setting_"+lang).toggle();
	});
	$(".showhide_category").live("click", function(){
		var lang = $(this).attr("lang");
		
		$("#asigne_page_"+lang).toggle();
		
		return false;
	});
	
	$(".single_remove_page_view").live("click", function(){
		var id = $(this).attr("category_id");
		var lang_id = $(this).attr("lang_id");
		
		if(confirm("Are you sure?")) {
			$(this).find('img').attr("src", "/kibocms/preset/assets/loading.gif");	
			$.ajax({
				url: 'pageswork.php',
				data: 'action=removeSinglePageView&id='+id,
				type: 'POST',
				success: function(data) {
					$("div#asigne_page_"+lang_id).find("li[id='single_category_"+id+"']").fadeOut().remove();
				}
			});
		}
		return false;
	});
	
	$(".search_elements_input").live("keyup", function() {
		var val = jQuery.trim($(this).val());
		var lang = $(this).attr("lang");
		
		if(val == "") {
			$("#elements_available_"+lang+" .leaf_content").show();
			$("#elements_available_"+lang).hide();
		} else {
			$("#elements_available_"+lang).show();
			$("#elements_available_"+lang+" .leaf_content").hide();
			$("#elements_available_"+lang+" .leaf_content h3:containsi("+val+")").parent().show();
		}
	});
	
});


	function addLeaf(pageId, leafId, leafDestination, lang_id) {
		$("#loader_"+lang_id).show();
		
		$.ajax({
			url: 'pageswork.php',
			data: 'action=addLeaf&id='+leafId+'&leafDestination='+leafDestination+'&pageId='+pageId+'&lang_id='+lang_id,
			type: 'POST',
			success: function(data) {
				$("#loader_"+lang_id).fadeOut();
				$("#"+leafDestination).find("ul").html(data);
			}
		});
	}
	
	function removeLeaf(id, lang_id) {
		if(confirm("Are you sure you want to delete?")) {
		
			$.ajax({
				url: 'pageswork.php',
				data: 'action=removeLeaf&id='+id+'&lang_id='+lang_id,
				type: 'POST',
				success: function(data) {
					$(".plid_"+id).remove();
				}
			});
		}
		 return false;
	}