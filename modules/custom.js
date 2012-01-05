

$(".order").live("click", function() {
	var id = $(this).attr("refid"); 
	var lang = $(this).attr("lang");
	var content = $(this).html();
	$(this).html("<img src='/upload/img/loading.gif' border='0'> "+content);
	
	$.ajax({
		url: '/work.php',
		data: 'action=addToCart&id='+id,
		type: 'POST',
		success: function(data) {
			
			if(lang == "ser") {
				if(data == 1)
					alert("Uspešno dodato u korpu!");
				else 
					alert("Samo za registrovane članove! Ukoliko ste registrovani, prijavite se, ukoliko niste, molimo vas da se registrujete!")
			} else if(lang == "eng") {
				if(data == 1) 
					alert("Successfully added to shopping cart!");
				else
					alert("Only for registered users! If you are already registered, please log in, otherwise, please register!");
			}
			$(".order[refid="+id+"]").html(content);
		}
	});
	return false;
});


$(".naruci").live("click", function() {
	var lang = $(this).attr("lang");
	var content = $(this).html();
	
	var name = jQuery.trim($("#_name").val());
	var address = jQuery.trim($("#_address").val());
	var phone = jQuery.trim($("#_phone").val());
	
	if(name == "" || address == "" || phone == "") {
		if(lang == "ser") {
			alert("Sva polja su obavezna!");
		} else {
			alert("All fields are required!");
		}
		return false;
	} else {
	
		$(this).html("<img src='/upload/img/loading.gif' border='0' /> "+content);
		$.ajax({
			url: '/work.php',
			data: 'action=orderCart&name='+name+'&address='+address+'&phone='+phone,
			type: 'POST',
			async: false,
			success: function(data) {
				if(data == 1) {
					if(lang == "ser") alert("Proizvodi uspešno poručeni!");
					if(lang == "eng") alert("Products are successfully ordered!");
					location.href = '/'+lang+'/shoppingbag';
				} else if(data == "-1") {
					if(lang == "ser") alert("Greška prilikom poručivanja!");
					if(lang == "eng") alert("There was an error while ordering!");
				}
				$(".naruci").html(content);
			}
		});
		return false;
	}
});