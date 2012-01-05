
	function loadFile(dest) {
		
		$.ajax({
			url: 'work.php',
			data: 'action=loadFile&file='+dest,
			type: 'POST',
			async: false,
			success: function(data) {
				if(data == "-1") {
					alert("There was error while opening file!");
				} else {
					$("#folderContent").show();
					
					if(editor == false) {
						$("#fileContent").val(data);
						editor = CodeMirror.fromTextArea(document.getElementById("fileContent"), {
								lineNumbers: true,
								onCursorActivity: function() {
									editor.setLineClass(hlLine, null);
									hlLine = editor.setLineClass(editor.getCursor().line, "activeline");
								}
						});
						lastContent = data;
					} else {
						
						var tekst = editor.getValue();
						if(tekst != lastContent) {
							if(confirm('Do you want to save changes?')) {
								saveContent();
							} else {
								return;
							}
						}
						lastContent = data;
						editor.setValue(data);
						
					}
					var hlLine = editor.setLineClass(0, "activeline");
					$("#dest").val(dest);
				}
			}
		});
		
	}
	var editor = false;
	var lastPos = null, lastQuery = null, marked = [], lastContent = "";
	var isCtrl = false;
	
	function unmark() {
		for (var i = 0; i < marked.length; ++i) marked[i]();
		marked.length = 0;
	}
		
	function search() {
		unmark();                     
		var text = document.getElementById("query").value;
		if (!text) return;
		for (var cursor = editor.getSearchCursor(text); cursor.findNext();)
		marked.push(editor.markText(cursor.from(), cursor.to(), "searched"));
		
		if (lastQuery != text) lastPos = null;
		var cursor = editor.getSearchCursor(text, lastPos || editor.getCursor());
		if (!cursor.findNext()) {
		cursor = editor.getSearchCursor(text);
		if (!cursor.findNext()) return;
		}
		editor.setSelection(cursor.from(), cursor.to());
		lastQuery = text; lastPos = cursor.to();
	}
	
	function saveContent() {
		var tekst = editor.getValue();
			$.ajax({
				url: 'work.php',
				data: 'action=saveFile&file='+$("#dest").val()+'&content='+tekst,
				type: 'POST',
				async: false,
				success: function(data) {
					//$("#fileContent").val(data);
					$("#saveButton").html("Save");
					
				}
			});
		lastContent = tekst;
	}
	
	$(document).ready(function() {
		$("li span.file").live("dblclick", function() {
			
			if(document.selection && document.selection.empty) {
		        document.selection.empty();
		    } else if(window.getSelection) {
		        var sel = window.getSelection();
		        sel.removeAllRanges();
		    }
			
			var file = $(this).attr("link");
			$("li span").removeClass("opened");
			$(this).addClass("opened");
			loadFile(file);
			
			
		});
		
		
		$("#saveButton").live("click", function() {
			$(this).html("Saving...");
			
			saveContent();
			
		});
		
		$("#closeButton").live("click", function() {
			var tekst = editor.getValue();
			
			if(tekst != lastContent) {
				if(confirm('Do you want to save changes?')) {
					saveContent();
				}
			}
			lastContent = "";
			$("#dest").val("");
			editor.setValue("");
			$("#folderContent").hide();
			$("li span").removeClass("opened");
			//window.close();
		});
		
		// CTRL+S shortcut for saving file
		
		document.onkeyup=function(e){
			var keyCode = e.keyCode || e.which; 
			if(keyCode == 17) isCtrl=false;
			
		}
		document.onkeydown=function(e){
			var keyCode = e.keyCode || e.which; 
			if(keyCode == 17) isCtrl=true;
			if(keyCode == 13) {
				search();
				e.preventDefault();
				return false;
			}
			if(keyCode == 83 && isCtrl == true) {
				e.preventDefault();
				//run code for CTRL+S -- ie, save!
				
				var tekst = editor.getValue();
					$.ajax({
						url: 'work.php',
						data: 'action=saveFile&file='+$("#dest").val()+'&content='+tekst,
						type: 'POST',
						success: function(data) {
							//$("#fileContent").val(data);
							$("#saveButton").html("Save");
							
						}
					});
				lastContent = tekst;
				return false;
			}
		}
		
		
		
		
	});
	
	function kiboEditorAlone(url) {
		popupWindow = window.open(
			url,'popUpWindow2','height=500,width=1300,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
	}