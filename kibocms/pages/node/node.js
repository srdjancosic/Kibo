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
	
	$(".showhide_setting").live("click", function() {
		var lang = $(this).attr("lang");
		
		$("#node_setting_"+lang).toggle();
	});
});
	