// JavaScript Document

$.fn.preloader = function(){
	"use strict";
	$('#preloader').remove();
	var loader_template = '';
	loader_template += '<div id="preloader">';
	loader_template += '	<div id="loader"></div>';
	loader_template += '</div>';
	this.prepend(loader_template);
};

$.fn.modal = function(options){
	"use strict";
	var params = $.extend({
		classes			: 	'close_on_click',
		style			:	'background-color:red',
		content			:	'<div style="{{style}}" class="modal_content">{{content}}</div>'
	}, options);
	
	var modal_template = '';
	modal_template += '<div id="modal" class="'+params.classes+'">';
	modal_template += params.content.replace('{{style}}', params.width);
	modal_template += '</div>';
		
	this.find('#preloader').remove();

	this.append(modal_template);
	
};

$.fn.ajax_response = function(options){
	"use strict";
	var params = $.extend({
		classes		: 	'close_on_click',
		content		:	'<div class="modal_content">{{content}}</div>'
	}, options);
	
	var ajax_response = '';
	ajax_response += '<div id="ajax_response">';
	ajax_response += params.content;
	ajax_response += '</div>';

	this.append(ajax_response);
	
};

$.fn.popup = function(options){
	"use strict";
	
	var params = $.extend({
		classes		: 	'close_on_click',
		content		:	'{{content}}',
		popup_title	:	'Popup Title',
		
	}, options);

	var tags_selector = '';
	tags_selector += '<div id="popup">';
	tags_selector += '	<div class="popup-header d-flex space-between">';
	tags_selector += '		<div class="">'+params.popup_title+'</div>';
	tags_selector += '		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>';
	tags_selector += '	</div>';

	tags_selector += '	<div class="popup-content">'+params.popup_title+'</div>';

	tags_selector += '	<div class="popup-actions">';
	tags_selector += '		<ul>';
	tags_selector += '			<li><button class="confirm">OUI</button></li>';
	tags_selector += '			<li><button class="abort">NON</button></li>';
	tags_selector += '		</ul>';
	tags_selector += '	</div>';
	
	tags_selector += '</div>';
	
	
	this.append('<div id="modal" class="'+params.classes+'">'+params.content+'</div>');
	
};

$.fn.wait = function(){
	
	"use strict";
	this.css('position', 'relative');
	var loader_template = '';
	loader_template += '	<div id="preloader" class="loader-container">';
	loader_template += '		<div id="loader"></div>';
	loader_template += '	</div>';
	
	this.append(loader_template);
	
	//this.append(loader_template);
	
};

$.fn.selector = function(options){
	
	"use strict";
	this.css('position', 'relative');
	
	var params = $.extend({
		classes			: 	'close_on_click',
		style			:	'background-color:red',
		content			:	'My Content here'
	}, options);
	
	var modal_template = '';
	modal_template += '	<div id="selector" class="'+params.classes+'">';
	modal_template += params.content;
	modal_template += '	</div>';

	this.append(modal_template);
	
};

function toggleFullScreen() {
  if (!document.fullscreenElement &&    // alternative standard method
      !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

function currencyFormatDE(num) {
  return (
	num
	  .toFixed(2) // always two decimal digits
	  .replace('.', ',') // replace decimal point character with ,
	  .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' MAD'
  ); // use . as a separator
}

var myBarsChart=null;

function isCanvasBlank(canvas) {
  var context = canvas.getContext('2d');

  var pixelBuffer = new Uint32Array(
	context.getImageData(0, 0, canvas.width, canvas.height).data.buffer
  );

  return !pixelBuffer.some(color => color !== 0);
}
	

$(document).ready(function(){
	
	/****************
				Page Tags
	****************/
	"use strict";
		
	$(document).on('click', '.store', function(){
		var controler = $(this).attr('data-controler');
		
		if (typeof controler !== typeof undefined && controler !== false) {
			var columns = {};
			var success = true;

			var selected = [];
			var tag;

			$("#"+controler.toLowerCase() ).find(".field").each(function(){

				if( $(this).hasClass("required") ){
					if($(this).val() === "" || $(this).val() === "-1"){
						$(this).addClass("error");
						success = false;
					}else{
						$(this).removeClass("error");
						columns[$(this).attr("id")] = $(this).val();
					}
				}else{
					if($(this).hasClass("on_off")){
						columns[$(this).attr("id")] = $(this).hasClass("on")? 1 : 0;
					}else{
						if($(this).is(':checkbox')){
							if($(this).is(':checked')){
								var id = $(this).attr('data-id');
								if (typeof id !== typeof undefined && id !== false) {
									columns[$(this).attr("id")] = id;
									console.log("data-id : " + id);
								}else{
									columns[$(this).attr("id")] = 1;
									console.log("id : " + id);
								}
								
							}else{
								columns[$(this).attr("id")] = 0;
							}
						}else if($(this).hasClass("collection")){
							if($(this).is(':checked')){
								selected.push($(this).attr('value'));
								tag = $(this).attr("data-table");

							}
						}else{
							columns[$(this).attr("id")] = $(this).val();
						}

						if(selected.length>0){
							columns[tag] = selected;
						}	

					}		
				}

			});

			if(success){

				var data = {
					'controler'	:	controler,
					'function'	:	'Store',
					'params'	:	{
						'columns'	:	columns
					}
				};

				$('body').preloader();

				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/ajax/ajax.php",
					data		:	data,
					dataType	: 	"json",
				}).done(function(response){
					console.log(response);
					$('.modal_close').trigger('click');
					$(".page_search_button").trigger('click');

				}).fail(function(xhr) {
					alert("Error");
					console.log(xhr.responseText);
					$("#preloader").remove();
				});
			}
			
			
		}else{
			alert("Define A Controler");
		}
	});
	
	$(document).on('click', '.delete', function(){
		
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'		:	$(this).attr("data-controler"),
				'function'		:	'Remove',
				'params'		:	{
					'id'	:	$(this).val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$('.modal_close').trigger('click');
					$(".page_search_button").trigger('click');					
				}else{
					alert("Impossible de supprimer cet element, verifier ses connections");
				}


			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}
		

	});
	
	$(document).on('click', '#page .page-head .actions .add', function(){
		var controler = $(this).attr('data-controler');
		var data = {
			'controler'		:	controler,
			'function'		:	'Create'
		};
		
		$('body').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').modal();
			$('#modal .modal_content').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
		
		
	});
	
	$(document).on('click', '.update', function(){
		var controler = $(this).attr('data-controler');
		var data = {
			'controler'		:	controler,
			'function'		:	'Update',
			'params'		:	{
				'id'	:	$(this).val()
			}
		};
		
		$('body').preloader();
		$(this).parent().parent().parent().find("tr").removeClass("activate");
		$(this).parent().parent().addClass("activate");
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').modal();
			$('#modal .modal_content').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
		
		
	});
	
	$(document).on('click', '.show_fullscreen', function(){
		
		toggleFullScreen();

	});
	
	$(document).on('click', '.show_alert', function(){
		alert('show')
	})

	$(document).on('click', '.open', function(){
		var page = $(this).attr('data-page');
		var data = {
			'controler'	:	'Route',
			'function'	:	'Exist',
			'params'	:	{
				'main'	:	page
			}
		};
		
		var _this = $(this);
		
		$('body').preloader();
		parent.location.hash = page.replace(".", "/");
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			if(response.msg == 1){

				var data = {};

				$(this).addClass("active");

				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/includes/"+page.replace(".", "/")+".php",
					data		:	data,
					dataType	: 	"text",
				}).done(function(response){
					$("#app").html(response);
					$("#preloader").remove();
					$("ul li").removeClass('active');
					_this.addClass('active');
					
				}).fail(function(xhr) {
					$("#preloader").remove();
					alert("Error");
					console.log(xhr.responseText);
				});
				
				
			}else{
				$("#app").html(response.msg);
				$("#preloader").remove();
			}
			

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	
		
	});
	
	$(document).on('click', '.show_filters', function(){
		
		$('body').preloader();
		
		var tags_selector = '<div class="modal_content">';
		tags_selector += '		<div class="checklist tags-container">';
		tags_selector += '			<div class="checklist-header d-flex space-between">';
		tags_selector += '				<div class=" pt-5">Liste des elements</div>';
		tags_selector += '				<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>';
		tags_selector += '			</div>';
		tags_selector += '			<ul class="checklist-selector tags-selector">';

		$(".tags ul li").each(function(){
			
			if(! $(this).hasClass('show_filters')){
				if( ! $(this).hasClass('hide') ){
					tags_selector += '<li class="d-flex"><div><label class="switch"><input class="option" id="' + $(this).attr("id") + '" data-name="' + $(this).text() + '" type="checkbox" checked><span class="slider round"></span></label></div><div class="pt-5 pl-5">' + $(this).text() + ' </li>';	
				}else{
					tags_selector += '<li class="d-flex"><div><label class="switch"><input class="option" id="' + $(this).attr("id") + '" data-name="' + $(this).text() + '" type="checkbox"><span class="slider round"></span></label></div><div class="pt-5 pl-5">' + $(this).text() + ' </li>';
				}			   
			}

		});
		
		tags_selector += '			</ul>';
		tags_selector += '		</div>';	
		tags_selector += '	</div>';
		
		$('body').modal({
			'content'	: tags_selector
		});
		
		$("#modal .modal_content").css('width', '250px');
		$("#modal .modal_content").addClass('animate__animated animate__jello');
		
	});
	
	$(document).on('change', 'ul.tags-selector input[type=checkbox]', function(){
		
		$(".tags ul li").addClass('hide');
		$(".tags ul li#"+$(this).attr("id")).removeClass('hide');
		$(".tags ul li.show_filters").removeClass('hide');

	});
		
	$(document).on('change', 'ul.checklist-selector input[type=checkbox]', function(){
		
		if( $('ul.checklist-selector input[type=checkbox]').hasClass('option') ){
			$('ul.checklist-selector input[type=checkbox]').prop('checked', false);
			$(this).prop('checked', true);
		}
		
	});
	
	$(document).on('change', '.filter select', function(){
		if($(this).val() === "-1"){
			$(this).removeClass('bordred');
		}else{
			$(this).addClass('bordred');
		}
		
		$('.page_search_button').trigger('click');
	});
	
	$(document).on('change', '.pp select', function(){
		$('.page_search_button').trigger('click');
	});
	
	/****************
				Modal
	****************/
	$(document).on('click', '.modal_close', function(){
		$("#modal").remove();
	});
	
	$(document).on('click', '.close_on_click', function(e){
		if(e.target !== this) return;
		$(this).remove();
	});
	
	/****************
				Page Table
	****************/
	
	/*** Table Header Sort ***/
	$(document).on('click', '#page .page-body .table-container table thead tr th.sort_by', function(){
		$('#page .page-body .table-container table thead tr th').removeClass('active');
		$(this).addClass('active');
		if ( $(this).attr("data-sort_type") === "desc" ){
			$(this).attr("data-sort_type", "asc");
		}else{
			$(this).attr("data-sort_type", "desc");
		}
		
		$('.page_search_button').trigger('click');
		
	});
	
	/*** Search */
	$(document).on('click', '.page_search_button', function(){
		console.log('search clicked')
		$('body').preloader();
		
		var tags = [];
		$('.tags ul li').each(function(){
			if( ! $(this).hasClass('hide') && ! $(this).hasClass('show_filters') ){
				tags.push($(this).attr("id"));
			}
			
		});
		
		var filters = [];
		$('.filter select').each(function(){
			filters.push( { 'id':$(this).prop("id"), 'value' : $(this).val() }  );			
		});

		var dates = [];
		if($('input.date_debut').length > 0){
			if($('input.date_debut').val() != '' && $('input.date_fin').val() != ''){
				dates.push($('input.date_debut').val())
			}
		}
		if($('input.date_fin').length > 0){
			if($('input.date_debut').val() != '' && $('input.date_fin').val() != ''){
				dates.push($('input.date_fin').val())
			}
		}

		var data = {
			'controler'	:	$(this).attr("data-controler"),
			'function'	:	'Table',
			'params'	:	{
				'request'		:	$('.request input').val(), 
				"tags"			:	tags,
				'use'			:	$(this).attr('data-use'),
				'column_style'	:	$(this).attr('data-column_style'),
				'filters'		:	filters,
				'pp'			:	parseInt( $(".pp select").val() ),
				'current'		:	parseInt(  $(".current").html() ),
				'dates'			:	dates
			}
		};
		
		if( $('#page .page-body .table-container table thead tr th.active').length > 0 ){
			data.params.sort = $('#page .page-body .table-container table thead tr th.active').attr('data-sort') + " " + $('#page .page-body .table-container table thead tr th.active').attr('data-sort_type');
		}
		console.log(data)
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#page .page-body .table-container").html(response.msg);
			$("#preloader").remove();
			$("#modal").remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
		
	});
		
	$(document).on('keypress', '.request input[type=text]', function(e){
		if(e.which === 13){
			$('.page_search_button').trigger('click');
		}
	});
	
	/*** Change columns Style */
	$(document).on('click', '.show_list_options', function(){
		
		$('body').preloader();
		
		
		var data = {
			'controler'	:	'Helpers.Modal',
			'function'	:	'ChangeTableList',
			'params'	:	{
				'module'	:	$(this).val(), 
				"selected"	:	$(this).attr('data-default')
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('body').modal({
				'content' :	response.msg
			});
			$("#modal .modal_content").addClass('animate__animated animate__jello');
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	/*** Save New Table columns Style */
	$(document).on('click', '.table_listview_save', function(){
		
		$('body').preloader();
		
		var data = {
			'controler'	:	'Helpers.Modal',
			'function'	:	'SaveTableList',
			'params'	:	{
				'module'		:	$(this).attr('data-module'),
				'is_default'	:	1, 
				'name'			:	$("input[type='checkbox'].table_style:checked").val(),
				'name_temp'		:	$("input[type='checkbox'].table_style:checked").val()
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.page_search_button').trigger('click');
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});		
		
	});
	
	/*** Next Previous */
	$(document).on('click', '.nex_prev .direction button', function(){
		var step = parseInt( $(this).attr("data-step") );
		var pp = parseInt( $(".pp select").val() );
		var current = parseInt(  $(".current").html() );
		
		if(current + (step * pp) >= 0){
			$(".current").html( current + (step * pp) );
			$('.page_search_button').trigger('click');			
		}
		

	
		
	});
	
	/****************
				Export CSV
	****************/

	$(document).on('click', '.exportTo', function(e){
		var table = $(this).data("target");
		if($("#"+table).length > 0){
			$("#"+table).table2excel({
			// exclude CSS class
			exclude: ".hide",
			name: "Worksheet Name",
			filename: "Exported File", //do not include extension
			fileext: ".xls", // file extension
			preserveColors:true

			}); 
		}else{
			alert('not found');
		}

	});

	$(document).on('click', '.exportToo', function(e){
		
		var file_type = $(this).data("type");
		var table = $(this).data("target");
		if($("#"+table).length > 0){
			var data = [];
			var sub_data = [];
			var html = '<table><thead><tr>';
			$("#"+table).find("thead tr th").each(function(){
				sub_data.push( $(this).text() );
				html = html + '<td>' + $(this).text() + '</td>';
			});
			html = html + '</tr></thead></table>';
			data.push( sub_data );
			sub_data = [];

			$("#"+table).find("tbody tr").each(function(){
				$(this).find('td').each(function(){
					sub_data.push( $(this).text() );
				});
				data.push( sub_data );
				sub_data = [];
			});

			html = `
			<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Error Messages</x:Name><x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body><table border='1px'><thead><tr><td><b>Column Header Text</b></td>  </tr></thead><tbody><tr><td>
			message1</td></tr><tr><td>
			message2</td></tr><tr><td></td></tr></tbody></table></body></html>
			
			`;

			console.log(html);

			var ColumnHead = "Column Header Text";
			var Messages = "\n message1.\n message2.";
			   window.open('data:application/vnd.ms-excel,' + html);
				e.preventDefault();

		}else{
			alert("not found");
		}
		
	})

	/****************
				Propriete
	****************/
	
	$(document).on('click', '.reload-files', function(){
		
		var container = $(this).attr("data-container");
		var controler = $(this).attr("data-controler");
		var _function = $(this).attr("data-function");
		
		$("." + container).wait();

		var data = {
			'controler'	:	controler,
			'function'	:	_function,
			'params'	:	{
				'folder'	:	$(this).attr('data-folder'),
				'UID'		:	$(this).attr('data-uid')
			}
		};
		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("." + container).html(response.msg); //.html("<img src='" + response.msg + "'>");
			$("#preloader").remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});	
		
		
		
	});
	
	$(document).on('click', '.remove-file', function(){
		
		if(confirm("Etes vous sûr de vouloir supprimer ce fichier?")){
			var container = $(this).attr("data-container");
			var controler = $(this).attr("data-controler");
			var _function = $(this).attr("data-function");

			$("." + container).wait();

			var data = {
				'controler'	:	controler,
				'function'	:	_function,
				'params'	:	{
					'folder'	:	$(this).attr('data-folder'),
					'UID'		:	$(this).attr('data-uid'),
					'file_name'	:	$(this).attr('data-filename'),
				}
			};
			console.log(data);
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				console.log(response);
				$(".reload-files").trigger('click');
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}
		

	});
	
	$(document).on('click', '.select_proprietaire', function(){
		$("#propriete").preloader();
		
		var data = {
			'controler'		:	'Proprietaire',
			'function'		:	'ShortTable',
			'params'		:	{
				'id_propritaire' : $('#id_proprietaire').val()
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$("#propriete").selector({'content':response.msg});
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.select_this_proprietaire', function(){
		var ID 		=  $(this).attr("data-id");
		var NAME 	=  $(this).attr("data-name");
		var EMAIL 	=  $(this).attr("data-email");
		var TELE 	=  $(this).attr("data-telephone");
		var VILLE 	=  $(this).attr("data-ville");
		var RIB	=  $(this).attr("data-rib");

		$("#id_proprietaire").val(ID);
		$("#proprietaire_name").val(NAME);
		$("#proprietaire_email").val(EMAIL);
		$("#proprietaire_telephone").val(TELE);
		$("#proprietaire_ville").val(VILLE);
		$("#proprietaire_rib").val(RIB);
		
		$("#selector").remove();
		
	});
	
	$(document).on('click', '.notes_show_more', function(){

		$(".item.status").removeClass("hide");
		$(this).parent().remove();
		
	});
	
	$(document).on('click', '.hide_unhide_note', function(){

		var data = {
			'controler'	:	'Notes',
			'function'	:	'Hide_Unhide',
			'params'	:	{
				'status'		:	$(this).attr("data-status"),
				'id'			:	$(this).val()
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(){
			$('.reload_notes').trigger('click');
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('click', '.archive_note', function(){
		if($("#notes.archive").val() !== ""){
			$("#notes.archive").removeClass("error");
			var data = {
				'controler'	:	'Notes',
				'function'	:	'Store',
				'params'	:	{
					'id_module'		:	$(this).attr("data-id_module"),
					'module'		:	$(this).attr("data-module"),
					'notes'			:	$("#notes.archive").val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$("#notes.archive").val("");
					$('.reload_notes').trigger('click');					
				}else{
					alert("Impossible de suivre...");
				}

			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});
		
			
		}else{
			$("#notes.archive").addClass("error");
		}
	});
	
	$(document).on('click', '.reload_notes', function(){
		var data = {
			'controler'		:	'Notes',
			'function'		:	'NotesBy',
			'params'		:	{
				'id_module'		:	$(this).attr('data-id_module'),
				'module'		:	$(this).attr('data-module')
			}	
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			console.log(response);
			$(".notes .label").remove();
			$(".notes .item").remove();
			$(".notes").append(response.msg);
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('click', '.show_propriete_proprietaire', function(){
		
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'Table',
			'params'		:	{
				'id_propriete'		:	$(this).attr("data-id")
			}
		};
		$('body').preloader();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').popup({
				'content' : response.msg, 
				'popup_title': 'Liste des Contrats'
			});
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.ppl .add', function(){
		
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'Create',
			'params'		:	{
				'id_propriete'	:	$(this).val()
			}
		};
		$('.ppl_wrapper').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".ppl-add-container").html(response.msg);
			$("#preloader").remove();
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.ppl .refresh', function(){
		
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'TableShort',
			'params'		:	{
				'id_propriete'	:	$(this).val()
			}
		};
		$('.ppl').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".ppl table tbody").html(response.msg);
			$('#preloader').remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.ppl-update', function(){
		
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'Update',
			'params'		:	{
				'id'	:	$(this).val()
			}
		};
		$('.ppl').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".ppl-add-container").html(response.msg);
			$('#preloader').remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.ppl_abort', function(){
		$('.ppl_add').remove();
	});
	
	$(document).on('click', '.ppl_store', function(){
		
		var continu = true;
		
		if($("#periode_nuite").val() === "" || $("#periode_nuite").hasClass("error")){
			continu = false;
			$("#periode_nuite").addClass("error");
		}else{
			$("#periode_nuite").removeClass("error");
		}
		
		if($("#periode_montant").val() === "" || $("#periode_montant").val() === 0){
			continu = false;
			$("#periode_montant").addClass("error");
		}else{
			$("#periode_montant").removeClass("error");
		}
		
		if($("#ppl_type").val() === "-1"){
			continu = false;
			$("#ppl_type").addClass("error");
		}else{
			$("#ppl_type").removeClass("error");
		}
		
		if(continu){
			$(this).prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i>');
			console.log('clicked.....')
			var columns = {
				'de'							:	$("#periode_de").val(),
				'a'								:	$("#periode_a").val(),
				'montant'						:	$("#periode_montant").val(),
				'id_propriete'					:	$(this).val(),
				'status'						:	$("#status").is(':checked')? 1: 0,
				'id_propriete_location_type'	:	$("#ppl_type").val(),
			};
			
			if($("#id").length > 0){
				columns.id = $("#id").val();
			}
			
			var data = {
				'controler'		:	'Propriete_Proprietaire_Location',
				'function'		:	'Store',
				'params'		:	columns
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){

				$('.ppl .refresh').trigger('click');
				$('.ppl_abort').trigger('click');

			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});
						
		}
		

		
	});

	$(document).on('click', '.ppl_delete', function(){
		
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'		:	'Propriete_Proprietaire_Location',
				'function'		:	'Remove',
				'params'		:	{
					'id'	:	$(this).val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$('.ppl .refresh').trigger('click');
					$('.ppl_abort').trigger('click');					
				}else{
					alert("Impossible de supprimer cet element, verifier ses connections");
				}
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});
	
	/*********************/
	$(document).on('click', '.ppc .add', function(){
		
		var data = {
			'controler'		:	'Propriete_Location',
			'function'		:	'Add_Propriete_To_Periode',
			'params'		:	{
				'id_propriete'	:	$(this).val()
			}
		};
		$(this).addClass('hide');
		$('.ppl_wrapper').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".ppc-add-container").html(response.msg);
			$("#preloader").remove();
			$('.ppc_abort').removeClass('hide');
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.ppc_abort', function(){
		$('#propriete_to_periode').remove();
		$(this).addClass('hide');
		$('.ppc .add').removeClass('hide');
	});
	
	$(document).on('click', '.ppc .refresh', function(){
		
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'TableShort',
			'params'		:	{
				'id_propriete'	:	$(this).val()
			}
		};
		$('.ppl').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".ppl table tbody").html(response.msg);
			$('#preloader').remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('change', '#propriete_to_periode .client', function(){
		$('.periodes_container').wait();
		
		var data = {
			'controler'		:	'Contrat_Periode',
			'function'		:	'Table_To_Select_Periode',
			'params'		:	{
				'UID'			:	$(this).val(),
				'id_propriete'	:	$(this).attr('data-id_propriete'),
				'id_client'		:	$('option:selected', this).attr('data-id'), // $(this).attr('data-id'),
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".periodes_container").html(response.msg);
			$('#preloader').remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('click', '.add_this_propriete_to_this_contrat', function(){
		$(this).prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i>');
		console.log('clicked.....')
		var data = {
			'controler'		:	'Propriete_Location',
			'function'		:	'Store',
			'params'		:	{
				'UID'			:	$(this).attr("data-UID"),
				'id_periode'	:	$(this).attr("data-id_periode"),
				'id_propriete'	:	$(this).attr("data-id_propriete"),
				'date_debut'	:	$(this).attr("data-date_debut"),
				'date_fin'		:	$(this).attr("data-date_fin")
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			console.log(response);
			$('#propriete_to_periode .client').trigger('change');			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.remove_this_proprietaire_from_propriete', function(){
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			$("#id_proprietaire").val("");
			$("#proprietaire_name").val("").addClass('error');
			$("#proprietaire_telephone").val("");
			$("#proprietaire_telephone_2").val("");
			$("#proprietaire_ville").val("");
			$("#proprietaire_agence").val("");
			$("#proprietaire_rib").val("");
		}
	});
	
	/****************
				Depense
	****************/
	$(document).on('change', '.depense-links input[type=checkbox]', function(){
		
		$(this).parent().parent().parent().find("input[type=text]").prop('disabled', !$(this).prop('checked'));
		$(this).parent().parent().parent().find("input[type=text]").val('').select();
		$(this).parent().find('.is_exists').removeClass('success error').html('');
		
	});
	
	$(document).on('keyup', '.depense-links input[type=text]', function(){
		var data = {
			'controler'  	:  	$(this).attr('data-controler'),
			'function'		:	'FindBy',
			'params'		:	{
					'findby'	:	$(this).attr('data-findby'),
					'request'		:	$(this).val(),
			}
		};
		var _this = $(this);
		_this.parent().find('.is_exists').removeClass('success error').html('<i class="fas fa-sync fa-spin"></i>');
		if(_this.val().length > 2){
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				console.log(response);
				if(response.msg === 0){
					_this.parent().find('.is_exists').removeClass('success').addClass('error').html('<i class="fas fa-exclamation-triangle"></i>');
					_this.parent().parent().find('.field').attr('data-id', 0);
					//_this.attr('data-id', 0);
				}else{
					_this.parent().find('.is_exists').removeClass('error').addClass('success').html('<i class="fas fa-check"></i>');
					_this.parent().parent().find('.field').attr('data-id', response.msg.id);
					//_this.attr('data-id', response.msg.id);
					//console.log(response.msg);
				}
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
				_this.parent().parent().find('.field').attr('data-id', 0);
			});				
		}else{
			_this.parent().find('.is_exists').removeClass('success').addClass('error').html('<i class="fas fa-exclamation-triangle"></i>');
		}

		
	});
		
	$(document).on('click', '.depense_chart', function(){
		var year = $(this).attr("data-year");
		var data = {
			'controler'	:	'Depense',
			'function'	:	'Graph_01',
			'params'	:	{'year':year}
		};
		$('.panel.graph').wait();
		//$('#bchart').parent().prepend("<div class='loading'><i class='fas fa-sync fa-spin'></i> Loading</div>");
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			//$('.cart_general_refresh').trigger('click');
			if(response.code === 1){
				var months = [];
				var totals = [];
				
				var _data = response.msg;
				for(var i in _data){					
					months.push(_data[i].month);
					totals.push(_data[i].total);
				}

				
				var ctx = document.getElementById('bchart').getContext('2d');
				
				if(myBarsChart && !isCanvasBlank(document.getElementById('bchart'))){
					$('#preloader').remove();
					myBarsChart.ctx.canvas.id = "bchart";
					myBarsChart.data.datasets[0].data = totals;	
					myBarsChart.update();

				}else{
					$('#preloader').remove();
					myBarsChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: months,
							datasets: [{
								label: 'Total Dépenses Par Mois',
								data: totals,
								backgroundColor: 'rgba(255,0,0,0.5)'
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true,
										callback: function (value) {
											return currencyFormatDE(value);
										}
									}
								}]
							},
							tooltips: {
								callbacks: {
									label: function(tooltipItem, chart){
										//return 'Total : ' + currencyFormatDE(tooltipItem);
										return 'Total : ' + currencyFormatDE(tooltipItem.yLabel);

									}
								}
							}
						}
					});	

				}
				

								
			}
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});



		
	});
	
	
	/****************
				Caisse
	****************/	
	$(document).on('click', '.caisse_mouvement', function(){
		
		var data = {
			'controler'		:	'Caisse',
			'function'		:	'Mouvement',
			'params'		:	{
				'id_caisse'		:	$(this).val()
			}
		};
		
		$('body').preloader();
		$(this).parent().parent().parent().find("tr").removeClass("activate");
		$(this).parent().parent().addClass("activate");
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').modal();
			$('#modal .modal_content').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
	});
	
	$(document).on('click', '.create_mouvement', function(){
		
		var data = {
			'controler'		:	'Caisse_Alimentation',
			'function'		:	'Create',
			'params'		:	{
				'id_caisse'		:	$(this).val()
			}
		};
		
		$('.mouvement_container').removeClass('hide');
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$('.mouvement_container').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
	});
	
	$(document).on('click', '.update_mouvement', function(){
		
		var data = {
			'controler'		:	'Caisse_Alimentation',
			'function'		:	'Update',
			'params'		:	{
				'id'		:	$(this).val()
			}
		};
		
		$('.mouvement_container').removeClass('hide');
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$('.mouvement_container').html(response.msg);
			//$('.popup-content.mouvement').scrollTop(0);
			$('.popup-content.mouvement').animate({scrollTop: $('.popup-content.mouvement').offset().top});

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
	});
	
	$(document).on('click', '.mouvement_abort', function(){
		$('.mouvement_container').addClass('hide').html('');
	});
	
	$(document).on('click', '.mouvement_store', function(){
		var data = {
			'controler'		:	'Caisse_Alimentation',
			'function'		:	'Store',
			'params'		:	{
				'created'		:	$("#created").val(),
				'montant'		:	$("#montant").val(),
				'source'		:	$("#source").val(),
				'notes'			:	$("#notes").val(),
				'id_caisse'		:	$(this).val()
			}
		};
		
		if($("#id").length > 0){
			data.params.id = $('#id').val();
		}
		$('.mouvement_container').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$('.mouvement_abort').trigger('click');
			$('.refresh_mouvement').trigger('click');
			

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
	});
	
	$(document).on('click', '.refresh_mouvement', function(){
		
		var data = {
			'controler'		:	'Caisse_Alimentation',
			'function'		:	'Get',
			'params'		:	{
				'id_caisse'		:	$(this).val()
			}
		};
		
		$('.items').wait();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$('.items').html(response.msg);
			$("#preloader").remove();

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
	});
	
	$(document).on('click', '.mouvement_remove', function(){
		
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'		:	'Caisse_Alimentation',
				'function'		:	'Remove',
				'params'		:	{
					'id'	:	$(this).val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$('.mouvement_abort').trigger('click');
					$('.refresh_mouvement').trigger('click');					
				}else{
					alert("Impossible de supprimer cet element, verifier ses connections");
				}


			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});
	
	
	/****************
				Propriete Location
	****************/
	$(document).on('click', '.add_this_propriete_to_contrat', function(){
		
		var data = {
			'controler'		:	'Propriete_Location',
			'function'		:	'Store',
			'params'		:	{
				'UID'			:	$(".select_propriete").val(),
				'id_periode'	:	$(".show_this_periode.active").attr("data-id"),
				'id_propriete'	:	$(this).attr("data-id_propriete"),
				'date_debut'	:	$(this).attr("data-date_debut"),
				'date_fin'		:	$(this).attr("data-date_fin")
			}
		};
		
		var _this = $(this);
		$(this).prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i>');
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			_this.parent().html('<button  class="red"><i class="fas fa-ban"></i></button>');
			$(".show_this_periode.active").trigger('click');			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.remove_this_propriete_from_periode', function(){
		
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'		:	'Propriete_Location',
				
				'function'		:	'Remove',
				'params'		:	{
					'id'	:	$(this).val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$('.show_this_periode.active').trigger('click');					
				}else{
					alert("Impossible de supprimer cet element, verifier ses connections");
				}
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});
	

	/****************
				Contrat
	****************/
	
	$(document).on('click', '.select_client', function(){
		$("#contrat").preloader();
		
		var data = {
			'controler'		:	'Client',
			'function'		:	'ShortTable',
			'params'		:	{
				'id_client' : $('#id_client').val()
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$("#contrat").selector({'content':response.msg});
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.select_propriete', function(){
		if($(".show_this_periode").length > 0){
			if($(".show_this_periode").hasClass('active')){
				$("#periode").preloader();

				var data = {
					'controler'		:	'Propriete',
					'function'		:	'ShortTable',
					'params'		:	{
						'date_debut' : $(".show_this_periode.active").attr("data-date_debut"),
						'date_fin' : $(".show_this_periode.active").attr("data-date_fin"),
						'code' : "",
					}
				};
				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/ajax/ajax.php",
					data		:	data,
					dataType	: 	"json",
				}).done(function(response){
					$("#preloader").remove();
					$("#periode").selector({'content':response.msg});
				}).fail(function(xhr) {
					alert("Error");
					console.log(xhr.responseText);
				});				
			}else{
				alert("Aucune Periode n\'est selectionné!");
			
			}
		}else{
			alert("Enregistrer aumoin une période!");
		}
	});
	
	$(document).on('keyup', '.short_table .search_bar .request', function(){
		
		var id = $(this).attr('data-id');
		
		var data = {
			'controler'		:	$(this).attr('data-controler'),
			'function'		:	'ShortTableBy',
			'params'		:	{
				'id_table' 	: 	$('#' + id).val(),
				'request'			:	$(this).val()	
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$(".short_table .result").html(response.msg);
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});

	$(document).on('keyup', '.short_table .search_bar .request_2', function(){
		var req = $(this).val().toUpperCase();
		var code = "";
		var counter = 0;
		$(".result .item").each(function(){
			code = $(this).find('.code').html().toUpperCase();
			if (code.indexOf(req) > -1) {
				$(this).removeClass('hide');
				counter++;
			}else{
				$(this).addClass('hide');
			}
		});
		$(".result_counter").removeClass('hide').html(counter + " items");
		
	});
	
	$(document).on('keyup', '.search_bar_2 .request_3', function(){
		var req = $(this).val().toUpperCase();
		var code = "";
		var counter = 0;
		$(".items .item.app").each(function(){
			code = $(this).find('.code').html().toUpperCase();
			if (code.indexOf(req) > -1) {
				$(this).removeClass('hide');
				counter++;
			}else{
				$(this).addClass('hide');
			}
		});

		$(".result_counter_2").removeClass('hide').html(counter + " items");
		
	});
	
	$(document).on('click', '.select_this_client', function(){
		var ID 			=  $(this).attr("data-id");
		var societe 	=  $(this).attr("data-societe_name");
		var first_name 	=  $(this).attr("data-first_name");
		var last_name 	=  $(this).attr("data-last_name");
		var cin 		=  $(this).attr("data-cin");
		var passport 	=  $(this).attr("data-passport");
		var VILLE 		=  $(this).attr("data-ville");


		$("#id_client").val(ID);
		$("#client_societe_name").val(societe);
		$("#client_first_name").val(first_name);
		$("#client_last_name").val(last_name);
		$("#client_cin").val(cin);
		$("#client_passport").val(passport);
		$("#client_ville").val(VILLE);
		
		$("#selector").remove();
		
	});
	
	$(document).on('click', '.show_periode', function(){

		
		var data = {
			'controler'		:	"Contrat",
			'function'		:	'Periode',
			'params'		:	{
				'UID'	:	$(this).val(),
				'id'	:	$(this).attr('data-id')
				
			}
		};
		
		$('body').preloader();
		$(this).parent().parent().parent().find("tr").removeClass("activate");
		$(this).parent().parent().addClass("activate");
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').modal();
			$('#modal .modal_content').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
		
		
	});

	$(document).on('click', '.show_this_periode', function(){
		//if(e.target != this) return;
		
		var data = {
			'controler'		:	"Contrat",
			
			'function'		:	'PeriodeBy',
			'params'		:	{
				'id'	:	$(this).attr('data-id')
			}
		};
		$('.show_this_periode').removeClass('active');
		$(this).addClass('active');
		$('.appartements .items').wait();
		
		
		$(this).parent().parent().parent().find("tr").removeClass("activate");
		$(this).parent().parent().addClass("activate");
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){			
			$("#preloader").remove();
			$('.appartements .items').html(response.msg);
			$('.appartements .title').html( 'Les Appartements : ' + $('.appartements .items .item').length);
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$("#preloader").remove();
		});
		
		
	});
	
	$(document).on('click', '.periode .add', function(){
		
		var data = {
			'controler'		:	'Contrat_Periode',
			'function'		:	'Create',
			'params'		:	{
				'UID'	:	$(this).val()
			}
		};
		
		$('.periode').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".periode_container").html(response.msg);
			$("#preloader").remove();
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.periode_update', function(){

		var data = {
			'controler'		:	'Contrat_Periode',
			'function'		:	'Update',
			'params'		:	{
				'id'	:	$(this).val()
			}
		};
		
		$('.periode').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".periode_container").html(response.msg);
			$("#preloader").remove();
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.periode_store', function(){
		
		var continu = true;
		
		if($("#date_debut").val() === "" || $("#date_debut").hasClass("error")){
			continu = false;
			$("#date_debut").addClass("error");
		}else{
			$("#date_debut").removeClass("error");
		}
		
		if($("#date_fin").val() === "" || $("#date_fin").val() === 0){
			continu = false;
			$("#date_fin").addClass("error");
		}else{
			$("#date_fin").removeClass("error");
		}
		
		if( parseInt($("#nbr__nuite").val()) <= 0){
			continu = false;
			$("#nbr__nuite").addClass("error");
		}else{
			$("#nbr__nuite").removeClass("error");
		}
		console.log($("#nbr__nuite").val());
		if(continu){
			$(this).prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i>');
			var columns = {
				'date_debut'					:	$("#date_debut").val(),
				'date_fin'						:	$("#date_fin").val(),
				'nbr_nuite'						:	$("#nbr__nuite").val(),
				'UID'							:	$("#UID").val(),
				'status'						:	$("#status").is(':checked')? 1: 0
			};
			console.log(columns);
			if($("#id").length > 0){
				columns.id = $("#id").val();
			}
			
			var data = {
				'controler'		:	'Contrat_Periode',
				'function'		:	'Store',
				'params'		:	columns
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){

				$('.periode .refresh').trigger('click');
				$('.periode_abort').trigger('click');

			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});
						
		}
		

		
	});
	
	$(document).on('click', '.periode_delete', function(){
		
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'		:	'Contrat_Periode',
				'function'		:	'Remove',
				'params'		:	{
					'id'	:	$(this).val()
				}
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if(response.msg === 1){
					$('.periode .refresh').trigger('click');
					$('.periode_abort').trigger('click');					
				}else{
					alert("Impossible de supprimer cet element, verifier ses connections");
				}


			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});
	
	$(document).on('click', '.periode_abort', function(){
		$('.periode_add').remove();
	});
	
	$(document).on('click', '.periode .refresh', function(){
		
		var data = {
			'controler'		:	'Contrat_Periode',
			'function'		:	'TableShort',
			'params'		:	{
				'UID'	:	$(this).val()
			}
		};
		$('.periode').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$(".periode .items").html(response.msg);
			$('#preloader').remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('change', '#date_debut, #date_fin', function(){
		var d1 = $("#date_debut").val();
		var d2 = $("#date_fin").val();
		
		var start = moment(d1, "YYYY-MM-DD");
		var end = moment(d2, "YYYY-MM-DD");

		if( moment.duration(end.diff(start)).asDays() > 0){
			$("#nbr__nuite").val( Math.round( moment.duration(end.diff(start)).asDays() ) );
			$("#nbr__nuite").removeClass("error");
		}else{
			$("#nbr__nuite").addClass("error");
			$("#nbr__nuite").val("0");
		}
		
		
	});
	
	$(document).on('click', 'tr.tickets td .ticket', function(){
		var id_location = $(this).attr("data-id_location");
		var data = {
			'controler'		:	'Propriete_Location',
			'function'		:	'Create',
			'params'		:	{
				'id'	:	id_location
			}
		};
		$('#mycalendar').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$('body').popup({
				'content' : response.msg, 
				'popup_title': 'Détails'
			});			
			$("#preloader").remove();

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
	});
	
	
	/****************
				Profile
	****************/
	
	$(document).on('click', '.show_profile', function(){
		var data = {
			'controler'	:	'Person',
			'function'	:	'GetProfile'
		};
		
		$('body').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){		
			if(response.code === 1){
				$('body').modal();
				$("#modal .modal_content").css('width', '350px');
				$("#modal .modal_content").html(response.msg);
				$("#modal .modal_content").addClass('animate__animated animate__jello');				
			}else if(response.code === -1){
				document.reload();
			}else{
				console.log(response);
			}
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});	
		
	});
	
	$(document).on('click', '.store-profile', function(){
		
		var isContinu = true;
		
		if($('#user_first_name').val() === ""){
			$('#user_first_name').addClass("error");
			isContinu = false;
		}else{
			$('#user_first_name').removeClass("error");
		}
		
		if($('#user_last_name').val() === ""){
			$('#user_last_name').addClass("error");
			isContinu = false;
		}else{
			$('#user_last_name').removeClass("error");
		}
		
		if($('#user_telephone').val() === ""){
			$('#user_telephone').addClass("error");
			isContinu = false;
		}else{
			$('#user_telephone').removeClass("error");
		}
				
		if($('#user_email').val() === ""){
			$('#user_email').addClass("error");
			isContinu = false;
		}else{
			$('#user_email').removeClass("error");
		}
		
		if($('#user_login').val() === ""){
			$('#user_login').addClass("error");
			isContinu = false;
		}else{
			$('#user_login').removeClass("error");
		}
		
		
		if($('#user_password').val() === ""){
			$('#user_password').addClass("error");
			isContinu = false;
		}else{
			$('#user_password').removeClass("error");
		}
		
		if(isContinu){
			var columns = {
				'first_name'	:	$('#user_first_name').val(),
				'last_name'		:	$('#user_last_name').val(),
				'telephone'		:	$('#user_telephone').val(),
				'email'			:	$('#user_email').val(),
				'user_login'	:	$('#user_login').val(),
				'user_password'	:	$('#user_password').val(),
			};
			
			if($("select#user_profile").length > 0){
				columns.id_profil = $('#user_profile').val();
				columns.UID = $('#UID').val();
				columns.status = $("#user_status").is(':checked')? 1: 0;
			}
			if($("#id").length > 0){
				columns.id = $('#id').val();
			}
			console.log(columns);
			var data = {
				'controler'	:	'Person',
				'function'	:	'StoreProfile',
				'params'	:	columns
			};
			$('body').preloader();
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				
				console.log(response);
				if(response.msg === 1){
					$("#profile").ajax_response({'content' : "<div class='green'> Success! </div>"});
					$('#preloader').remove();
					$('.modal_close').trigger('click');
					
				}else if(response.msg === 2){
					$("#profile").ajax_response({'content' : "<div class='green'> Success! </div>"});
					$('#preloader').remove();
					location.reload();
				}else{
					$("#profile").ajax_response({'content' : "<div class='red'> Error! </div>"});
					$('#preloader').remove();
					$('.modal_close').trigger('click');
				}
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
				$('#preloader').remove();
			});			
		}
		

		
	});
	
	$(document).on('click', '.edit-password-profile', function(){
		
		$('#user_password').prop('disabled', !$('#user_password').prop('disabled') );
		
		if( $('#user_password').prop('disabled') ){
			$('#user_password').css('background-color', 'rgba(0,0,0,0.1)');
		}else{
			$('#user_password').css('background-color', 'rgba(0,0,0,0.0)');
			$('#user_password').select();
		}
		
		
	});
	
	$(document).on('click', '.image-reload', function(){
		
		$(".image-container").wait();

		var data = {
			'controler'	:	'Person',
			'function'	:	'GetDefaultPicture',
			'params'	:	{
				'folder'	:	$(this).attr('data-folder'),
				'UID'		:	$(this).attr('data-uid')
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.image-container .image img').attr("src", response.msg); //.html("<img src='" + response.msg + "'>");
			$("#preloader").remove();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});	
		
		
		
	});
	
	$(document).on('click', '.upload_btn', function(){
		var IdFile = $(this).attr('data-target');
		$("#"+IdFile).trigger('click');
	});

	$(document).on('change', '#upload', function(){
		var UID = $(this).attr('data-uid');
		var folder = $(this).attr('data-folder');
		var is_unique = $(this).attr('data-is_unique');
		
		var params = {
			IdInputFile		: 	'upload',
			link			: 	'pages/default/ajax/upload.php',
			params			:	{
					'UID'			:	UID,
					'is_unique'		:	is_unique,
					'folder'		:	folder
			}
		};

		upload(params);
		
	});
	
	$(document).on('click', '.show_log', function(){
		
		var data = {
			'controler'		:	'Person',
			'function'		:	'Log',
			'params'		:	{
				'id_user'		:	$(this).val()
			}
		};
		$('body').preloader();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').popup({
				'content' : response.msg, 
				'popup_title': 'Liste des Logs'
			});
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.show_droits', function(){
		
		var data = {
			'controler'		:	'Person',
			'function'		:	'Get_Droits',
			'params'		:	{
				'id_user'		:	$(this).val()
			}
		};
		$('body').preloader();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('body').popup({
				'content' : response.msg, 
				'popup_title': 'Droits d\'utilisation'
			});
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
	});
	
	$(document).on('click', '.module .module-input', function(){
		var this_id = $(this).attr('id');
		console.log(this_id);
		$( '.' + this_id + ' input').each(function(){
			$(this).prop('disabled', function(i, v) { return !v; });
		});
	});
	
	$(document).on('click', '.permission_save', function(){
		var json = '{\n';
		
		$('.droits .item').each(function(){
			if( $(this).find('.module .module-input').is(':checked') ){
				json += '"' + $(this).find('.module .module-input').attr('data-id') + '":{';
				
				$(this).find('.actions .action input').each(function(){
					if($(this).is(':checked') ){
						json += '"' + $(this).attr('data-id') + '":1,';
					}else{
						json += '"' + $(this).attr('data-id') + '":0,';
					}
				});
				json = json.slice(0, -1);
				json += '},';
				
			}
			
		});
		json = json.slice(0, -1);
		json += '}';
		
		var data = {
			'controler'	:	'Person',
			'function'	:	'SavePermission',
			'params'	:	{
				'id_user'		:	$(this).val(),
				'permissions'	:	json
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){

			console.log(response);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
			$('#preloader').remove();
		});
		
	});
	
	/****************
				Parametres
	****************/
	$(document).on('click', '.parametre_menu ul li', function(){
		var page = $(this).attr('data-page');
		var data = {
			'controler'	:	'Parametre',
			'function'	:	page
		};
		$('.parametre_menu ul li').removeClass('selected');
		$(this).addClass('selected');
		
		$('.parametre_content').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('.parametre_content').html(response.msg);			

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	
		
	});
	
	$(document).on('click', '.item.mdl .edit', function(){
		$('.modules_liste_add').removeClass('hide');
		var name = $(this).parent().parent().find('.d-flex .name').html();
		$('.modules_liste_add input').val(name).select();
		$('.modules_liste_add .save').addClass('edit');
	});
	
	$(document).on('click', '.mdl_add', function(){
		$('.modules_liste_add').removeClass('hide');
		$('.modules_liste_add input').val("").select();
		$('.modules_liste_add .save').removeClass('edit');
	});
	
	$(document).on('click', '.mdl_refresh', function(){
		var data = {
			'controler'	:	'Parametre',
			'function'	:	'Get_Modules_Only'
		};
		
		$('.modules_liste').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$('.module_actions_container').html('');
			$(".modules_container").html(response.msg);	

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
	});
	
	$(document).on('click', '.modules_liste_add .abort', function(){
		$('.modules_liste_add').addClass('hide');
		$('.modules_liste_add input').val('');
	});
	
	$(document).on('click', '.modules_liste_add .save', function(){
		var data = {
			'controler'	:	'Module',
			'function'	:	'Store',
			'params'	:	{
				'name'		:	$(".modules_liste_add input").val()
			}
			
		};
		if($(this).hasClass('edit')){
			data.params.id = $(".item.mdl.selected").find('.id').html();	
		}
		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(){
					
			$('.modules_liste_add .save').removeClass('edit');
			$('.modules_liste_add .abort').trigger('click');
			$('.mdl_refresh').trigger('click');
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('keypress', ".modules_liste_add input", function(e){
		if(e.which === 13){
			$('.modules_liste_add .save').trigger('click');
		}
	});
	
	$(document).on('click', '.modules_liste_add .remove', function(){
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'	:	'Module',
				'function'	:	'Remove',
				'params'	:	{
					'id'		:	$(".item.mdl.selected").find('.id').html()
				}
			};

			console.log(data);
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(){

				$('.modules_liste_add .save').removeClass('edit');
				$('.modules_liste_add .abort').trigger('click');
				$('.mdl_refresh').trigger('click');
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});
	
	$(document).on('click', '.modules_liste .item.mdl', function(){
	
		var id_module = $(this).attr('data-id');
		var data = {
			'controler'	:	'Parametre',
			'function'	:	'Get_Module_Actions',
			'params'		: {
				'id_module'		:	id_module
			}
		};

		$('.modules_liste .item.mdl').removeClass('selected');
		$(this).addClass('selected');
		$('.actions_liste').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#preloader").remove();
			$('.module_actions_container').html(response.msg);

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	
		
	});
	
	$(document).on('click', '.actions_refresh', function(){
		var data = {
			'controler'	:	'Parametre',
			'function'	:	'Get_Module_Actions',
			'params'	:	{
				'id_module'		:	$(this).val()
			}
		};
		
		$('.action_liste').preloader();
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$("#preloader").remove();
			$(".module_actions_container").html(response.msg);	

		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
	});
	
	$(document).on('click', '.actions_add', function(){
		$('.actions_liste_add').removeClass('hide');
		$('.actions_liste_add input').val("").select();
		$('.actions_liste_add .save').removeClass('edit');
	});
		
	$(document).on('click', '.item.actions .edit', function(){
		$('.actions_liste_add').removeClass('hide');
		var name = $(this).parent().parent().find('.d-flex .name').html();
		$('.actions_liste_add input').val(name).select();
		$('.actions_liste_add .save').addClass('edit');
		$('.item.actions').removeClass('selected');
		$(this).parent().parent().addClass('selected');
	});
	
	$(document).on('click', '.actions_liste_add .abort', function(){
		$('.actions_liste_add').addClass('hide');
		$('.actions_liste_add input').val('');
	});

	$(document).on('click', '.actions_liste_add .save', function(){
		var data = {
			'controler'	:	'Module',
			'function'	:	'Store_Action',
			'params'	:	{
				'name'		:	$(".actions_liste_add input").val(),
				'id_module'	:	$(".item.mdl.selected").find('.id').html()
			}
			
		};
		if($(this).hasClass('edit')){
			data.params.id = $(".item.actions.selected").find('.id').html();	
		}
		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(){
					
			$('.actions_liste_add .save').removeClass('edit');
			$('.actions_liste_add .abort').trigger('click');
			$('.actions_refresh').trigger('click');
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('keypress', ".actions_liste_add input", function(e){
		if(e.which === 13){
			$('.actions_liste_add .save').trigger('click');
		}
	});
	
	$(document).on('click', '.actions_liste_add .remove', function(){
		if(confirm("Etes vous sûr de vouloir Supprimer?")){
			var data = {
				'controler'	:	'Module',
				'function'	:	'Remove_Action',
				'params'	:	{
					'id'		:	$(".item.actions.selected").find('.id').html()
				}
			};

			console.log(data);
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(){

				$('.actions_liste_add .save').removeClass('edit');
				$('.actions_liste_add .abort').trigger('click');
				$('.actions_refresh').trigger('click');
			}).fail(function(xhr) {
				alert("Error");
				console.log(xhr.responseText);
			});			
		}

		
	});


	/****************
				Vertical Menu
	****************/
	
	$(document).on('click', '.has_sub', function(){
		var target = $(this).attr("data-sub-target");
		$("." + target).toggleClass("hide");
		if( !$("." + target).hasClass('hide')){
			$("." + target).addClass('animate__animated animate__headShake');
		} 
		$(this).toggleClass('active');
	});
	
	/****************
				Log Out
	****************/
	
	$(document).on('click', '#logout', function(){
		
		$('body').preloader();
		$('body').modal();
		
		
		var tags_selector = '';
		tags_selector += '<div id="popup">';
		tags_selector += '	<div class="popup-header d-flex space-between">';
		tags_selector += '		<div class="">Log Out Confirmation</div>';
		tags_selector += '		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>';
		tags_selector += '	</div>';
		
		tags_selector += '	<div class="popup-content">';
		tags_selector += '		Voullez vous vraiment vous déconnecter?';
		tags_selector += '	</div>';

		tags_selector += '	<div class="popup-actions">';
		tags_selector += '		<ul>';
		tags_selector += '			<li><button class="confirm">OUI</button></li>';
		tags_selector += '			<li><button class="abort">NON</button></li>';
		tags_selector += '		</ul>';
		tags_selector += '	</div>';
		
		tags_selector += '</div>';	
		
		$("#modal .modal_content").css('width', '330px');
		$("#modal .modal_content").html(tags_selector);
		$("#modal .modal_content").addClass('animate__animated animate__jello');
	});
	
	$(document).on('click', '#popup .confirm', function(){
		var data = {
			'controler'		:	'Login',
			'function'		:	'Logout'
		}
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			location.reload();
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});	
		
	});
	
	$(document).on('click', '#popup .abort', function(){
		$('.modal_close').trigger('click');
	});
	
	
});