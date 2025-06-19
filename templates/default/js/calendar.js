// JavaScript Document
//var moment = moment();
var emoment = moment();
/*

console.log(moment.add(-5, 'days')); // 'March'
*/
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

$(document).ready(function() {
	
	"use strict";
	
	var d = new Date();
  	var weekday = new Array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
	var months = new Array("Janvier","Février","Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Nevombre", "Décembre");
	
	/********************************
				MY CALENDAR
	**********************************/
	
	$(document).on('click', '#mycalendar .mycalendar-container .mycalendar-header .tabs ul li a', function(e){
		e.preventDefault();
		$('#mycalendar .mycalendar-container .mycalendar-header .tabs ul li a').removeClass('active');
		$(this).addClass('active');
		
		$('.mycalendar-body-header .navigation a.current').trigger('click');
		
	});
	
	$(document).on('click', '.mycalendar-body-header .navigation a', function(e){
		e.preventDefault();
		var counter = parseInt( $('.mycalendar-body-header .navigation a.current').attr('data-counter') );
		
		if($(this).hasClass('pre')){
			$('.mycalendar-body-header .navigation a.current').attr('data-counter', counter - 1);
		}else if($(this).hasClass('next')){
			$('.mycalendar-body-header .navigation a.current').attr('data-counter', counter + 1);
		}else{
			$('.mycalendar-body-header .navigation a.current').attr('data-counter', counter);
		}
		
		var data = {
			'controler'		:	'Calendar',
			'function'		:	'Get',
			'params'		:	{
				'counter'		:	$('.mycalendar-body-header .navigation a.current').attr('data-counter'),
				'style'			:	$('#mycalendar .mycalendar-container .mycalendar-header .tabs ul li a.active').attr('data-style'),
			}
		};
		
		if($("#UID").length > 0){
			data.params.UID = $("#UID").val();
		}
		if($("#id_complexe").length > 0){
			data.params.id_complexe = $("#id_complexe").val();
		}
		
		$('#mycalendar').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			$("#mycalendar .mycalendar-container .mycalendar-body").html(response.msg);
			$("#preloader").remove();
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
	$(document).on('change', '.client #UID', function(){
		var id_client = $(this).val();
		var id_client_search;
		console.log(id_client);
		$("table tr.tickets").each(function(){
			if(id_client == -1){
				$(this).removeClass('hide');
			}else{
				id_client_search = $(this).find('td .ticket').attr("data-id_client");
				if (id_client_search === id_client) {
					$(this).removeClass('hide');
				}else{
					$(this).addClass('hide');
				}				
			}

			
		});
		
	});
	
	$(document).on('click', '.calendar-header .btn-group.style a', function(){
		$('.calendar-header .btn-group.style a').removeClass("selected");
		var current_counter = $(".calendar-header .btn-group.calendar a.direction").attr('data-counter');
		
		if($(this).attr('data-style') === 'week'){	//	week
			var d1 = weekday[0] + ", " + months[d.getMonth()] + ' ' + (d.getDate()-d.getDay()) + ', ' + d.getFullYear();
			var d2 = weekday[d.getDay()] + ", " + months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
			
			$('.calendar_current_interval').html(d1 + " - " + d2);
			
		}else if($(this).attr('data-style') === 'month' || $(this).attr('data-style') === 'month_'){	// month
			
			//d.setMonth(d.getMonth() + current_counter);
			var m = parseInt(d.getMonth());
			m = m + parseInt(current_counter);
			//var m = (parseInt(d.getMonth()) + current_counter);
			$('.calendar_current_interval').html(months[m] + ' / ' + d.getFullYear());
			
		}else{	// day
			
			$('.calendar_current_interval').html(weekday[d.getDay()] + ", " + months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear());
		}
		
		//var n = weekday[d.getDay()];
		$(this).addClass("selected");
		
		

		$('.calendar-header .btn-group.calendar a.direction').attr("data-counter", current_counter);
		$('.calendar-header .btn-group.calendar a.cl_refresh').trigger("click");
		
	});
	
	$(document).on('change', '#id_complexe', function(){
		var id_complexe = $(this).val();
		
		
		var data = {
			'controler'		:	'Calendar',
			'function'		:	'Get_Client_By_Complexe',
			'params'		:	{
				'id_complexe'		:	id_complexe
			}
		};
		$('#mycalendar').wait();
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.mycalendar-body-header .navigation a.current').trigger('click');
			$(".client #UID").html(response.msg);
			$("#preloader").remove();
			
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		
		/*
		
		var data = {
				'module' 			: 	'client_by_complexe',
				'id_complexe'		:	id_complexe,
			};	
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").removeClass("show");
				$("#UID").html(response.msg);
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}	
			
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			alert(textStatus);
		});
		
		
		if($('.calendar-header .btn-group.style a.selected').attr('data-style') === 'month'){
			$('.calendar-header .btn-group.calendar a.cl_refresh').trigger('click');
		}
		*/
	});
	
	$(document).on('click', '.calendar-header .btn-group.calendar a.cl_refresh', function(){
		
		var counter = $('.calendar-header .btn-group.calendar a.direction').attr("data-counter");
			
		var style = "";
		
		$('.calendar-header .btn-group.style a').each(function(){
			if($(this).hasClass("selected")){
				style = $(this).attr("data-style");
			}
		});

		var data = {
				'style' 		: 	style,
				'counter'		:	counter,
				'UID'			:	$("#UID").val(),
				'id_complexe'	:	$("#id_complexe").val()
			};	
		console.log(data);
		
		//$.post("pages/default/ajax/calendar/util.php", data, function(r){$(".debug").html(r);});
		
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/calendar/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").removeClass("show");
				$(".calendar-body").html(response.msg);
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}	
			
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			alert(textStatus);
		});
		

	});

	$(document).on('click', '.calendar-header .btn-group.calendar a.direction', function(){
		
		var counter = $(this).attr("data-counter");
		var action = $(this).attr("data-action");
		var style = "";
		
		$('.calendar-header .btn-group.style a').each(function(){
			if($(this).hasClass("selected")){
				style = $(this).attr("data-style");
			}
		});
		
		
		
		if(action === "next"){
			if(style === "month" || style === "month_"){	
				emoment.add(1, 'months');				
				$('.calendar_current_interval').html(months[emoment.format('M')-1] + ' / ' + emoment.format('YYYY'));	
			}else if(style === "week"){
				var d1 = weekday[0] + ", " + months[d.getMonth()] + ' ' + (d.getDate() + (7 * counter) - d.getDay()) + ', ' + d.getFullYear();
				var d2 = weekday[d.getDay()] + ", " + months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
				$('.calendar_current_interval').html(d1 + " - " + d2);
			}
			$('.calendar-header .btn-group.calendar a.direction').attr("data-counter", (parseInt(counter) + 1));
		}else{
			if(style === "month" || style === "month_"){	
				emoment.add(-1, 'months');
				//alert(moment.format('M'));
				$('.calendar_current_interval').html(months[emoment.format('M')-1] + ' / ' + emoment.format('YYYY'));	
			}
			
			$('.calendar-header .btn-group.calendar a.direction').attr("data-counter", (parseInt(counter) - 1));
		}
		//console.log(moment.format('MMMM') + " / " + emoment.format('MMMM'));
		$('.calendar-header .btn-group.calendar a.cl_refresh').trigger("click");
	});
	
	$(document).on('click', '.show_calendar', function(){
		parent.location.hash = "calendar";
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/includes/calendar.php",
			success 	: 	function(response){
								$('.content').html(response);
								$(".modal").removeClass("show");

							},
			error		:	function(response){
								$('.content').html(response);
								$(".modal").removeClass("show");

			}
		});
		
	});
	
	$(document).on('click', '.calendar_dev', function(){
		var _this = $(this).parent();
		
		_this.find(".to_show").removeClass('hide');
		$(this).addClass('hide');
	});
	
});
