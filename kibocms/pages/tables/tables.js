$(document).ready(function() {
	
	$("#add_columne").live("click", function() {
		var type = $("select#columne_type").val();
		var name = $("input#column_name").val();
		var length = $("input#length").val();
		var table_name = $("input#table_name").val();
		var use_default = $("input#use_default:checked").val();
		var default_value = $("input#default_value").val();
		
		$("#loader").show();
		$.ajax({
			url: 'tablework.php',
			data: 'action=addColumne&table_name='+table_name+'&name='+name+'&type='+type+'&length='+length+'&use_default='+use_default+'&default_value='+default_value,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#columnes").find("ul").append(data);
				$("#column_name").val("");
				$("#default_value").val("");
				$("#length").val("");
				$("#columne_type option[value='-1']").attr('selected', 'selected');
				$("#length1").hide();
				$("#default").hide();
				uncheck();
			}
		});
	});
	
	$(".editCol").live("click", function() {
		var name = $(this).attr("name");
		var table_name = $(this).attr("table_name");
		$(this).find("img").attr("src", "/kibocms/preset/assets/loading.gif");
		
		$.ajax({
			url: 'tablework.php',
			data: 'action=reloadColumn&name='+name+'&table_name='+table_name,
			type: 'POST',
			success: function(data) {
				$("#buttons_"+name).hide();
				$("#buttons_"+name).find(".editCol").find("img").attr("src", "/kibocms/preset/actions_small/Pencil.png");
				$("div#columnes").find("li[id='column_"+name+"']").append(data);
			}
		});
	});
	
});

function removeColumne(name,tableName) {
	
	if(confirm("Are you sure?")) {
		$("#loader").show();
		$.ajax({
			url: 'tablework.php',
			data: 'action=deleteColumn&column_name='+name+'&table_name='+tableName,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#columnes").find("li[id='column_"+name+"']").fadeOut().remove();
			}
		});
	}
	return false;
}

function saveColumne(name,tableName) {
		
		var type = $("select#columne_type_"+name).val();
		var new_name = $("input#column_name_"+name).val();
		var length = $("input#length_"+name).val();
		var table_name = $("input#table_name_"+name).val();
		var use_default = $("input#use_default_"+name+":checked").val();
		var default_value = $("input#default_value_"+name).val();
	
	
		$("#loader").show();
		$.ajax({
			url: 'tablework.php',
			data: 'action=saveColumn&column_name='+name+'&table_name='+tableName+'&type='+type+'&length='+length+'&use_default='+use_default+'&default_value='+default_value+'&new_name='+new_name,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#columnes").find("div#col_setting_"+name).fadeOut().remove();
				$("div#columnes").find("li[id='column_"+name+"']").append(data);
				$("#buttons_"+name).show();
			}
		});
}

function cancelColumne(name) {
		
		
	
		$("#loader").show();
		$.ajax({
			url: 'tablework.php',
			data: 'action=none&column_name='+name,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#columnes").find("#col_setting_"+name).fadeOut().remove();
				$("#buttons_"+name).show();
			}
		});
}


function uncheck()
{
  document.getElementById("use_default").checked=false;
}