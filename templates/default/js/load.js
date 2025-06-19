// JavaScript Document

$.fn.preloader = function(){
	"use strict";
	var loader_template = '';
	loader_template += '<div id="preloader">';
	loader_template += '	<div id="loader"></div>';
	loader_template += '</div>';
	this.prepend(loader_template);
};


$(document).ready(function(){

	"use strict";
	var animationend = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
		
	$(document).on('click', '.show_form_right_container', function(){
		
		var html = '<div class="form-right hide">';
			html += ' 	<div class="body">';
			html += ' 	</div>';
			html += '</div>';
		
		$(".content").prepend(html);

		var page = $(this).attr('data-page');
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			"page"			:	page,
			"action"		:	$(this).attr("data-action")
		};
		
		if($(this).attr("data-action") === "edit"){ data.id = $(this).val(); }
		
		console.log(data);
		
		$("body").css("overflow", "hidden");

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page.toLowerCase()+"/form.php",
			data		:	data,
			success 	: 	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");
								$('.content .form-right .body').addClass("animated fadeInRight");
				
							},
			error		:	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");

			}
		});			
		
	});
	
	
	
	/*****************************************************************************************************
			EDIT
	******************************************************************************************************/	
	$(document).on('click', '.actions ._add', function(){
		var page = $(this).val();

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			"page"	:	page
		};

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page.toLowerCase()+"/form.php",
			data		:	data,
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
	
	$(document).on('click', '.save_form', function(){

		var selector = $(this).attr("data-table");
		console.log(selector + " table");
		var columns = {};
		var success = true;
		
		var selected = [];
		var tag;
		
		$("."+selector).find(".form-element").each(function(){
			
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
					
					if($(this).hasClass("collection")){
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

		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var data = {
			't_n'		:	selector,
			'columns'	:	columns
		};
		
		
		if(success){
			$.ajax({

				type		: 	"POST",
				url			: 	"pages/default/ajax/"+selector.toLowerCase()+"/save.php",
				data		:	data,
				success 	: 	function(response){
					$(".modal").removeClass("show");
					if(response === "1"){

						swal("SUCCESS!", "L'élement' a été ajouté!", "success");
						var data = {
							"page"	:	"menu",
							"p"		:	{
								"s"		:	0,
								"pp"	:	50
							}
						};

						$.ajax({

							type		: 	"POST",
							url			: 	"pages/default/includes/" + selector.toLowerCase() + ".php",
							data		:	data,
							success 	: 	function(response){
												$('.content').html(response);
												$(".modal").removeClass("show");
											},
							error		:	function(response){
												$(".debug").html("Error : " + response);
												$(".modal").removeClass("show");

							}
						});
					}else{
						$(".debug").html("Error : " + response);
					}

				},
				error		:	function(response){
									$('.debug').html("Error : " + response);
									$(".modal").removeClass("show");
				}
			});
		}

	});

	$(document).on('click', '.actions .__add', function(){
		var page = $(this).val().toLowerCase();

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			"page"	:	page,
			"p"		:	{
				"s"		:	0,
				"pp"	:	50
			}
		};

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page+"/form_add.php",
			data		:	data,
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
	
	$(document).on('click', '.actions .add_', function(){
		
		
		var html = '<div class="form-right hide">';
			html += ' 	<div class="body">';
			html += ' 	</div>';
			html += '</div>';
		
		$(".content").prepend(html);
		
		

		var page = $(this).val().toLowerCase();
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

		var id = 0;
		
		if($(this).hasClass("btn")){
			id = $(this).val();
		}else{
			id = $(this).find(".id-ligne").html();
		}
		
		var data = {
			"page"		:	page,
			"id"		:	id
		};

		$("body").css("overflow", "hidden");
		
		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page+"/form_add.php",
			data		:	data,
			success 	: 	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");
								$('.content .form-right .body').addClass("animated fadeInRight");
				
							},
			error		:	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");

			}
		});
		
		
		
		
		
		
		
		/*
		
		
		var page = $(this).val().toLowerCase();

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			"page"	:	page,
			"p"		:	{
				"s"		:	0,
				"pp"	:	50
			}
		};

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page+"/form_add.php",
			data		:	data,
			success 	: 	function(response){
								$('.content').html(response);
								$(".modal").removeClass("show");
								
							},
			error		:	function(response){
								$('.content').html(response);
								$(".modal").removeClass("show");

			}
		});	
*/
	});	
	
	$(document).on('click', '.edit_ligne_', function(){

		var html = '<div class="form-right hide">';
			html += ' 	<div class="body">';
			html += ' 	</div>';
			html += '</div>';
		
		$(".content").prepend(html);
		
		

		var page = $(this).attr('data-page');
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

		var id = 0;
		
		if($(this).hasClass("btn")){
			id = $(this).val();
		}else{
			id = $(this).find(".id-ligne").html();
		}
		
		var data = {
			"page"		:	page,
			"id"		:	id
		};

		$("body").css("overflow", "hidden");
		
		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page.toLowerCase()+"/form_edit.php",
			data		:	data,
			success 	: 	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");
								$('.content .form-right .body').addClass("animated fadeInRight");
				
							},
			error		:	function(response){
								$('.content .form-right').removeClass("hide");
								$('.content .form-right .body').html(response);
								$(".modal").removeClass("show");

			}
		});	
		
		
		
		
	});
	
	$(document).on('click', '.close_form', function(){
		var animation = 'animated fadeOutRight';
		$('.content .form-right .body').addClass(animation).one(animationend,function(){
			$('.content .form-right .body').removeClass(animation);	
			$('.content .form-right').remove();
			
			$("body").css("overflow", "auto");
			
		});
	});
	
	$(document).on('click', '._edit_ligne', function(){

		var page = $(this).attr('data-page');

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

		var id = 0;
		
		if($(this).hasClass("btn")){
			id = $(this).val();
		}else{
			id = $(this).find(".id-ligne").html();
		}
		
		var data = {
			"page"		:	page,
			"id"		:	id
		};

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page.toLowerCase()+"/form.php",
			data		:	data,
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
	
	$(document).on('click', '.edit_ligne', function(){

		var page = $(this).attr('data-page');

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var id = 0;
		
		if($(this).hasClass("btn")){
			id = $(this).val();
		}else{
			id = $(this).find(".id-ligne").html();
		}
		
		var data = {
			"page"		:	page,
			"id"		:	id
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/"+page.toLowerCase()+"/form_edit.php",
			data		:	data,
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
	
	$(document).on('click', '.remove_ligne', function(e){
		e.stopImmediatePropagation();
		
		var _this = $(this);
		var page = $(this).attr('data-page');
		
		swal({
			  title: "Vous êtes sûr?",
			  text: "Êtes vous sûr de vouloir supprimer cette ligne? " + page.toLowerCase(),
				type:"warning",
				showCancelButton:!0,
				confirmButtonColor:"#3085d6",
				cancelButtonColor:"#d33",
				confirmButtonText:"Oui, Supprimer!"
			}).then(function(t){
			  if (t.value) {

					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
					var id = _this.val();

					var data = {
						"page"		:	page,
						"id"		:	id
					};
				  page = page.toLowerCase();
					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/ajax/" + page + "/delete.php",
						data		:	data,
						success 	: 	function(response){
											//alert(response);
											$(".modal").removeClass("show");
											if(_this.hasClass("p_p_l")){
												$(".refresh_location").trigger("click");
											}else if(_this.hasClass("c_a")){
												$(".refresh_c_a").trigger("click");
											}else if(_this.hasClass("periode")){
												$('.periode_refresh').trigger("click");
											}else if(_this.hasClass("propriete_location")){
												$(".refresh_appartement").trigger("click");
											}else{
												$(".refresh").trigger("click");
											}
										},
						error		:	function(response){
											$('.content').html(response);
											$(".modal").removeClass("show");

						}
					});	


			  } else {

			  }
		});		
	});	

	$(document).on("click", ".actions .close", function(){
		var page = $(this).val().toLowerCase();
		
		
		
		swal({
		title:"Êtes vous sûr?",
		text:"Si vous annuller, Vous perdez toutes les informations saisies!",
		type:"warning",
		showCancelButton:!0,
		confirmButtonColor:"#3085d6",
		cancelButtonColor:"#d33",
		confirmButtonText:"Oui, Quitter!"
			
		}).then(function(t){
		if(t.value){
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			var data = {
				"page"	:	page,
				"p"		:	{
					"s"		:	0,
					"pp"	:	50
				}
			};

			$.ajax({

				type		: 	"POST",
				url			: 	"pages/default/includes/"+page+".php",
				data		:	data,
				success 	: 	function(response){
									$('.content').html(response);
									$(".modal").removeClass("show");
									
								},
				error		:	function(response){
									$('.content').html(response);
									$(".modal").removeClass("show");
									
				}
			});
		}
		});		
	});	
	
	$(document).on('click', '._close', function(){
		$(".modal").removeClass("show").html("");
	});
	
	$(document).on('click','.on_off',function(){ 
	
		if($(this).hasClass("on")){
			$(this).removeClass("on").addClass("off");
		}else{
			$(this).removeClass("off").addClass("on");
		}
	
	});

	$(document).on('click','.star_on_off',function(){ 
		
		$(this).find(".on").toggleClass("hide");
		$(this).find(".off").toggleClass("hide");
		
	
	});
	
	/***************************
			SEARCH
	***************************/	
	
	$(document).on("click", "#a_u_s", function(){
		if($(this).hasClass("_propriete")){
			if($("#request").val() === ""){
				$("._choices ." + $(this).attr("data")).remove();
			}else{
				$("._choices ." + $(this).attr("data")).remove();
				$("._choices").append('<span class="label label-blue '+$(this).attr("data")+'" style="margin-right:7px">'+$("#request").val()+'</span>');
			}			
		}
		$(".refresh").trigger('click');
	});
	
	$(document).on("keyup", "#request",function(e) {
		if(e.keyCode === 13 ) {
			
			$(".refresh").trigger('click');
		}
	});
	
	$(document).on("change", "._select select",function() {
		if($(this).val() === "-1"){
			$("._choices ." + $(this).attr("data")).remove();
		}else{
			$("._choices ." + $(this).attr("data")).remove();
			$("._choices").append('<span class="label label-blue '+$(this).attr("data")+'" style="margin-right:7px">'+$(this).find('option:selected').text()+'</span>');
		}
		$(".refresh").trigger('click');
		
	});
	
	/***************************
			REFRECH / ACTUALISER
	***************************/
	
	$(document).on('change', '#showPerPage', function(){
		$(".p_p").html($("#showPerPage").val());
		$(".current").html(0);
		$(".refresh").trigger('click');
	});
	
	$(document).on('click',"#btn_passive_next", function(){
		
		var current = $(".current").html();
		$(".current").html(parseInt(current)+1);
		$(".refresh").trigger('click');

				
	});
	
	$(document).on("click",".showSearchBar", function(){
		$(".searchBar").toggleClass("hide");
	});
	
	$(document).on('click', "#btn_passive_preview", function(){
		
		var current = $(".current").html();
		if(current > 0){
			$(".current").html(parseInt(current)-1);
			$(".refresh").trigger('click');
			
		}
				
	});
	
	/*********** SORT BY COLUMNS 	***/
	
	$(document).on("click", ".sort_by", function(){
		
		var sort_by = $(this).attr("data-sort");
		
		if($("#sort_by").hasClass("desc")){
			
			$("#sort_by").removeClass("desc");
			$("#sort_by").addClass("asc");
			$("#sort_by").html(sort_by + " asc");
			
		}else{
			
			$("#sort_by").removeClass("asc");
			$("#sort_by").addClass("desc");
			$("#sort_by").html(sort_by + " desc");		
			
		}
		
		$(".refresh").trigger('click');
		
	});	
	
	$(document).on('click','.refresh',function(e){
		
		e.preventDefault();
		
		var current = $(".current").html();
		var pearPage = $("#showPerPage").val();
		var sort_by = $("#sort_by").html();
		var request = $("#request").val();
		
		var data = {
				't_n' : $(this).val(),
				'current'	:	current,
				'p_p'		:	pearPage,
				'sort_by'	:	sort_by,
				'request'	:	request
			};
		
		var filter = {};
		$("._select select").each(
			function(){
				if($(this).val() !== "-1"){
					filter[$(this).attr("data")] = $(this).val();
				}

			}
		);
		if(Object.keys(filter).length > 0 ){
			data.filter = filter;
		}

		var tag = $(this).val().toLocaleLowerCase();
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$("."+tag).html("");

		$.post("pages/default/ajax/"+tag+"/get.php",{'data':data},function(response){
			$("."+tag).html(response);
			$(".modal").removeClass("show");
			
		});

	});
	
	// SHOW VERTICAL MENU
	$(".show_vertical_menu").on("click", function(){
		
		if ($(".vertical_menu").hasClass("toLeft")){
			
			$(".vertical_menu").animate({
				marginLeft: '0px'
			},500);
			
			$(".vertical_menu").removeClass("toLeft");	
			$(this).addClass("selected");
			
		}else{
			
			$(".vertical_menu").animate({
				marginLeft: '-=350px'
			},500);
			
			$(".vertical_menu").addClass("toLeft");	
			$(this).removeClass("selected");
		}
		
	});

	// SHOW SUB MENU
	$(".show_submenu").on("click", function(e){
		if(e.target !== this) {return;}
		$(this).find(".sub_menu").toggleClass("hide");
	});
	
	function checkAfterLoad(){

		setInterval(function(){
		$(".after_load").each(function(){
			if(!$(this).hasClass('loaded')){
				$(this).trigger('click');
				$(this).addClass('loaded');
			}

		});	
		}, 1000);		
	}	
	
	$('.show_hide_menu').on('click', function(){
		$('.navigation-menu').toggleClass('hidden')
	})
});

$(window).on('load', function() {

	"use strict";
	
	var hash = window.location.hash.substr(1);
	var page = (hash === "")? "index/index": hash;
	
	var data = {
		'controler'	:	'Route',
		'function'	:	'Exist',
		'params'	:	{
			'main'	:	page
		}
	};

	$('body').preloader();
	parent.location.hash = page.replace(".", "/");
	
	$.ajax({
		type		: 	"POST",
		url			: 	"pages/default/ajax/ajax.php",
		data		:	data,
		dataType	: 	"json",
	}).done(function(response){
		console.log(response);
		if(response.msg === 1){
			
			var data = {};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/includes/"+page.replace(".", "/")+".php",
				data		:	data,
				dataType	: 	"text",
			}).done(function(response){
				$("#app").html(response);
				$("#preloader").remove();
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
	
	
	
	setInterval(function(){
	$(".after_load").each(function(){
		if(!$(this).hasClass('loaded')){
			$(this).trigger('click');
			$(this).addClass('loaded');
		}
		
	});	
	}, 1000);
	
	
	
});
