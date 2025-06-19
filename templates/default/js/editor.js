// JavaScript Document



function getSelectedText(idTextArea){
	"use strict";
	var txtarea = document.getElementById(idTextArea);
    // obtain the index of the first selected character
    var start = txtarea.selectionStart;
    // obtain the index of the last selected character
    var finish = txtarea.selectionEnd;
    // obtain the selected text
    var sel = txtarea.value.substring(start, finish);	
	return sel;
}

$(document).ready(function(){
	
	"use strict";
	/*******************
		EDITOR
	********************/

	$(document).on('click','.btn-group .btn', function(){
		var option = $(this).val();
		editArticle(option);
	});
	
	$(document).on("change","#style",function(){
		var selection = $(this).val();
		editArticle(selection);
	});
	
	$(document).on("change","#label",function(){
		var selection = $(this).val();
		editArticle(selection);
	});
	
	$(document).on("change","#header",function(){
		var selection = $(this).val();
		editArticle(selection);
	});
	
	function editArticle(options){
		
		var option = options;
		var selection = getSelectedText("editorTextArea");
		
		var newText = "";
		switch(option) {
			case "bold":
				newText = "<b>"+selection+"</b>";
				break;
			case "italic":
				newText = "<i>"+selection+"</i>";
				break;
			case "underline":
				newText = "<span style='text-decoration:underline'>"+selection+"</span>";
				break;
			case "ul":
				newText = "<ul><li>"+selection+"</li></ul>";
				break;
			case "ol":
				newText = "<ol><li>"+selection+"</li></ol>";
				break;
			case "paragraph":
				newText = "<p>"+selection+"</p>";
				break;
			case "br":
				newText = selection+"<br>";
				break;
			case "quotes":
				newText = "<div class='quote'>"+selection+"</div>";
				break;
			case "link":
				newText = "<a href='"+HOST+"'>"+selection+"</a>";
				break;
			case "image":
				newText = "<img src='"+HOST+"'>"+selection;
				break;
				
			case "error":
				newText = "<div class='info info-error'>"+selection+"</div>";
				break;
			case "success":
				newText = "<div class='info info-success'>"+selection+"</div>";
				break;
			case "alert":
				newText = "<div class='info info-alert'>"+selection+"</div>";
				break;
				
			case "label_red":
				newText = "<span class='label label-red'>"+selection+"</span>";
				break;
			case "label_blue":
				newText = "<span class='label label-blue'>"+selection+"</span>";
				break;
			case "label_green":
				newText = "<span class='label label-breen'>"+selection+"</span>";
				break;
			case "label_orange":
				newText = "<span class='label label-orange'>"+selection+"</span>";
				break;
			case "label_default":
				newText = "<span class='label label-default'>"+selection+"</span>";
				break;
			
			case "h1":
				newText = "<h1>"+selection+"</h1>";
				break;
			case "h2":
				newText = "<h2>"+selection+"</h2>";
				break;
			case "h3":
				newText = "<h3>"+selection+"</h3>";
				break;
				
				
			default:
				newText = selection;
		}
		
		var cursorPos = $('#editorTextArea').prop('selectionStart');
		var v = $('#editorTextArea').val();
		var textBefore = v.substring(0,  cursorPos);
		var textAfter  = v.substring(cursorPos+selection.length, v.length);

		$('#editorTextArea').val(textBefore + newText + textAfter);
		$(".apercu").html($('#editorTextArea').val());
		
	}
	
	$('#editorTextArea').bind('input propertychange', function() {
			$(".apercu").html(this.value);
	});	
	
	var myVar = setInterval(function(){
		var content = $('#editorTextArea').val();
		$(".apercu").html(content);
	}, 500);
	
	
});

