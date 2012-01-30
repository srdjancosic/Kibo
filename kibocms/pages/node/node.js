jQuery(document).ready(function() {
	jQuery(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all").find(".portlet-header").addClass("ui-widget-header ui-corner-all").prepend('<span class="ui-icon ui-icon-circle-arrow-s"></span>').end().find(".portlet-content");

	jQuery(".portlet-header .ui-icon").click(function() {
		jQuery(this).toggleClass("ui-icon-minusthick");
		jQuery(this).parents(".portlet:first").find(".portlet-content").toggle();
	});
	
	
	$("#select_category").live("change", function() {
		location.href='index.php?catId='+$(this).val();
	});
	
	$("#order_nodes").live("change", function() {
		location.href='nodework.php?action=changeOrderType&catId='+$("#cat_id").val()+'&orderType='+$(this).val();
	});

	$(".showhide_subcategories").live("click", function() {
		var id = $(this).attr("catrgory_id");
		
		location.href='index.php?catId='+id;
	});
	
	$("#addTag").live("click", function() {
		var nodeId = $(this).attr("node_id");
		var name = $("input#node_tag_"+nodeId).val();
		var lang_id = $(this).attr("lang_id");
		
		$("#loader2_"+lang_id).show();
		$.ajax({
			url: 'nodework.php',
			data: 'action=addTag&nodeId='+nodeId+'&name='+name+'&lang_id='+lang_id,
			type: 'POST',
			success: function(data) {
				$("#loader2_"+lang_id).fadeOut();
				$("div#tags_"+lang_id).find("ul").append(data);
				$("#node_tag_"+nodeId).val("");
			}
		});
	});	
});
	
	function showMore(offset, limit, langId, catId, count) {
		$("#showMore").val("Loading...");
		$("#loader2_"+langId).show();
		$.ajax({
			url: 'nodework.php',
			data: 'action=showMore&limit='+limit+'&catId='+catId+'&lang_id='+langId+'&offset='+offset,
			type: 'POST',
			success: function(data) {
				$("#sortable_"+langId).append(data);
			}
		});
		$.ajax({
			url: 'nodework.php',
			data: 'action=showMoreButton&limit='+limit+'&catId='+catId+'&lang_id='+langId+'&offset='+offset+'&count='+count,
			type: 'POST',
			success: function(data) {
				$("#morearticles").empty();
				$("#morearticles").append(data);
				$("#loader2_"+langId).fadeOut();
			}
		});
	}
	
	function removeTag(id, nod_id, lang_id){
		if(confirm("Are you sure?")) {
			$("#loader2_"+lang_id).show();	
			$.ajax({
				url: 'nodework.php',
				data: 'action=removeTag&id='+id+'&node_id='+nod_id,
				type: 'POST',
				success: function(data) {
					$("#loader2_"+lang_id).fadeOut();
					$("div#tags_"+lang_id).find("li[id='tag_"+id+"']").fadeOut().remove();
				}
			});
		}
		return false;
	}
	