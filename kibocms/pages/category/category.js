jQuery(document).ready(function() {
	
	$("#select_category").live("change", function() {
		location.href='index.php?parent='+$(this).val();
	});
	
	$("#search_input").live("keyup", function() {
		var val = jQuery.trim($(this).val());
		if(val == "") {
			$(".box_1").show();
		} else {
			$(".box_1").hide();
			$(".box_1 div.inner:containsi("+val+")").parent().show();
		}
	});
	
	$("#create_dimmension").live("click", function() {
		$.ajax({
			url: 'categorywork.php',
			data: 'action=createDimmension&new_x='+$("#int_x").val()+'&new_y='+$("#int_y").val()+'&catId='+$("#catId").val(),
			type: 'POST',
			success: function(data) {
				$("#dimmensions").html(data);
				alert("Dimension successfuly created!");
				$("#int_x").val("");
				$("#int_y").val("");
			}
		});
	});
	
	$(".showhide_setting").live("click", function() {
		var lang = $(this).attr("lang");
		
		$("#category_setting_"+lang).toggle();
	});
});


	function addCategoryCustomField(categoryId) {
		$("#loader_"+categoryId).show();
		var name = jQuery.trim($("#field_name_"+categoryId).val());
		var type = jQuery.trim($("#field_type_"+categoryId).val());
		
		$.ajax({
			url: 'categorywork.php',
			data: 'action=addCustomField&name='+name+'&type='+type+'&id='+categoryId,
			type: 'POST',
			success: function(data) {
				$("tbody.table_"+categoryId).hide();
				$("tbody.table_"+categoryId).empty();
				$("tbody.table_"+categoryId).append(data);
				$("tbody.table_"+categoryId).fadeIn(200);
				$("#loader_"+categoryId).fadeOut();
				$("#field_name_"+categoryId).val("");
				$("#field_type_"+categoryId).val("");
			}
		});
	}
	