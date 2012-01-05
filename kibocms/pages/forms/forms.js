$(document).ready(function() {
	
	// submit da ima id="add_to_form"
	$("#add_to_form").live("click", function() {
		var type = $("select#field_type").val();
		var name = $("input#pre_def_name").val();
		var label = $("input#pre_def_label").val();
		var title = $("select#field_type option:selected").text();
		var form_id = $("input#form_id").val();
		
		$("#loader").show();
		$.ajax({
			url: 'formwork.php',
			data: 'action=addFieldType&type='+type+'&form_id='+form_id+'&title='+title+'&name='+name+'&label='+label,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#field_list").find("ul").append(data);
				$("#pre_def_name").val("");
				$("#pre_def_label").val("");
			}
		});
	});
		
	$("#save_button").live("click", function() {
		var field_id = $("#_field_id").val();
		var label = $("#_label").val();
		var name = $("#_name").val();
		var table_field = $("#_table_field").val();
		var validation = $("#_validation").val();
		validation = validation.replace(/\+/g, "{PLUS}");
		var error_message = $("#_error_message").val();
		var value = $("#_value").val();
		var from_table = ($("#_from_table").is(":checked")) ? 1 : 0;
		var required = ($("#_required").is(":checked")) ? 1 : 0;
		var selected_value = $("#_selected_value").val();
		var constant = $("#_constant").val();
		var identificator = $("#_identificator").val();
		var cssclass = $("#_class").val();
		var hint = $("#_hint").val();
		
		$("#loader").show();
		$.ajax({
			url: 'formwork.php',
			data: 'action=editFieldTypeOptions&id='+field_id+
						'&label='+label+
						'&name='+name+
						'&value='+value+
						'&from_table='+from_table+
						'&validation='+validation+
						'&error_message='+error_message+
						'&required='+required+
						'&selected_value='+selected_value+
						'&constant='+constant+
						'&identificator='+identificator+
						'&class='+cssclass+
						'&hint='+hint+
						'&table_field='+table_field,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#field_edit").html("<h3>Changes saved!</h3>").delay(1000).fadeOut("slow");
			}
		});
		
	});
	
	$("#cancel_button").live("click", function() {
		$("div#field_edit").fadeOut("slow").html("");
	});
	
	$('.sortable').sortable({
		placeholder: "ui-state-highlight2",
		connectWith: '.sortable',
		handle: '.handler',
		update: function(event, ui) {
			order = $(this).sortable('serialize');
			//var newOrder = $(this).attr("id") + "::" + order;
			formId = $("#form_id").val();
			$("#loader").show();
			$.ajax({
				url: 'formwork.php',
				data: 'action=sort&'+order+'&form_id='+formId,
				type: 'POST',
				async: false,
				success: function(data) {
					$("#loader").fadeOut();
				}
			});
		}
	}); 
	$( ".sortable" ).disableSelection();
	
	$("#_table_field").live("change", function() {
		var value = $(this).val();
		if(value != 0) {
			$("#_name").val(value);
		}
	});
	
});


function removeField(id) {
	if(confirm("Are you sure?")) {
		$("#loader").show();
		$.ajax({
			url: 'formwork.php',
			data: 'action=deleteFieldType&id='+id,
			type: 'POST',
			success: function(data) {
				$("#loader").fadeOut();
				$("div#field_list").find("li[id='field_"+id+"']").fadeOut().remove();
			}
		});
	}
	return false;
}

function editField(id) {
	$("#loader").show();
	$.ajax({
		url: 'formwork.php',
		data: 'action=editFieldType&id='+id,
		type: 'POST',
		success: function(data) {
			$("#loader").fadeOut();
			$("div#field_edit").html(data).fadeIn();
			return false;
		}
	});
	return false;
}