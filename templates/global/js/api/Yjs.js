/*         ______________________________________
  ________|                                      |_______
  \       |           Yjs v1.1.3          |      /
   \      |      Copyright © 2017 www.yasinus.com       |     /
   /      |______________________________________|     \
  /__________)                                (_________\
last update : 13-05-2017
action : add tooltip

*/

$(document).ready(function(e) {
var animationend = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';	
	//$('.tooltip').tooltipster();

	/************************************************************** SETUP TAB ELEMENTS   */	
	
	$('.tab-content').not(':eq(0)').hide();

    $('.panel-header-tab a').on('click',function(e){
		e.preventDefault();	
		$('.panel-header-tab a').removeClass('active');
		$(this).addClass('active');
		var index = $( ".panel-header-tab a" ).index( this );
		//$.cookie('article-tab_clicked',index); //save the clicked tab in cookies
		$('.tab-content').hide();
		$('.tab-content').eq(index).fadeIn('slow');
		$('.tab-title').text($(this).text());
	});
		
	$('.btn-group-radio button').on('click',function(e){
		$(this).parent().find('button').removeClass('checked');
		
		$(this).addClass('checked');	
	});
	
	/************** FOR AJAX CALL	*********/
	$('body').on('click','.btn-group-radio button',function(e){
		$(this).parent().find('button').removeClass('checked');
		$(this).addClass('checked');	
	});
		
	
	
	$('.btn-group-check button').on('click',function(e){
		$(this).toggleClass('selected');	
	});

	/************** FOR AJAX CALL	*********	
	$('body').on('click','.btn-group-check button',function(e){
		$(this).toggleClass('selected');	
	}); */

	$('body').on('click','.show_modal',function(){
		var animation = 'animated fadeIn';
		$('.modal').addClass('show');
		$('.modal').addClass(animation).one(animationend, function(){
		$('.modal').removeClass(animation);	
		
		});	
	});
	
	$('body').on('click','.modal .close',function(){
		var animation = 'animated fadeOut';
		$('.modal').addClass(animation).one(animationend, function(){
		$('.modal').removeClass(animation);	
		$('.modal').remove();
		
		});
	});
	/*
	$('body').on('click','.modal',function(e){
		if(e.target != this) return;
		
		var animation = 'animated fadeOutUp';
		$('.modal-content').addClass(animation).one(animationend, function(){
			$('.modal-content').removeClass(animation);	
			$('.modal-content').remove();
			
			var animation = 'animated fadeOut';
			$('.modal').addClass(animation).one(animationend, function(){
				$('.modal').removeClass(animation);	
				$('.modal').removeClass('show');
			
			});
		
		});
				
	});
	*/
	
	$("body").on("click", ".close", function(){
		
		var _class= $(this).attr("data-dismiss");
		
		$("." + _class).each(function(){
			if( $(this).hasClass("info-dismissible") ){
				$(this).fadeOut("fast");
			}			
		});

		
	});
	
	/************************************************************** SETUP TAB ELEMENTS   */
	$('.tab-content').not(':eq(0)').hide();
    $('body').on('click','.panel-header-tab a',function(e){
		e.preventDefault();	
		$('.panel-header-tab a').removeClass('active');
		$(this).addClass('active');
		var index = $( ".panel-header-tab a" ).index( this );
		//$.cookie('article-tab_clicked',index); //save the clicked tab in cookies
		$('.tab-content').hide();
		$('.tab-content').eq(index).fadeIn('slow');
		$('.tab-title').text($(this).text());
	});
	
	
	$(".has-topbar-submenu").on("mouseover",function(e){
		var animation = 'animated fadeIn';
		$(this).find('.topbar-submenu').addClass('show');
		$(this).find('.topbar-submenu').addClass(animation).one(animationend, function(){
		$(this).find('.topbar-submenu').removeClass(animation);	
		
		});	
	});
	$(".has-topbar-submenu").on("mouseleave",function(e){
		$(this).find('.topbar-submenu').removeClass('show');	
	});
	
	/************************************************************** TOOLTIP   */	
	$(".hasTooolTip").on('mouseover',function(e){
		var animation = 'animated bounceIn';
		$(this).parent().find(".tooolTip").removeClass('hide');	
		$(this).parent().find(".tooolTip").addClass(animation).one(animationend, function(){
			$(this).parent().find(".tooolTip").addClass(animation);	
			$(this).parent().find(".tooolTip").removeClass(animation);
		});
		
	});
	$(".hasTooolTip").on('mouseleave',function(e){
		$(this).parent().find(".tooolTip").addClass('hide');	
	});
});