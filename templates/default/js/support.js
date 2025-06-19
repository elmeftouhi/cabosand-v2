// JavaScript Document

var totalItems = 1;
var timer;
var loop = 1000;

$(document).ready(function(){
	var animationend = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
	
	$(".support .icon").on("click", function(){
		
		var animation ;
		
		if( ! $(".support .c").hasClass("hide")){
			animation = 'animated fadeOut';
			
			$(".support .c").addClass(animation).one(animationend, function(){
				$(".support .c").addClass('hide');
				$(".support .c").removeClass(animation);	

			});	
			listner();
		}else{
			animation = 'animated fadeIn';
			
			$(".support .c").removeClass('hide');
			$(".support .c").addClass(animation).one(animationend, function(){
				$(".support .c").removeClass(animation);	
				$(".support_refresh").trigger("click");
			});
			
			clearTimeout(timer);
			
		}

	});
	
	$(".support .c .header .close").on("click", function(){
		$(".support .icon").trigger("click");
	});
	
	$("#support_send").on("click", function(){
		if($("#support_message").val() !== ""){
			var data = {
				"t_n"				:	"Support",
				"columns"			:	{
					"support_message"	:	$("#support_message").val()
				}
				
			};
			$("#support_message").val("");
			$.post("pages/default/ajax/support/save.php",{'data':data},function(r){
				if(r==="1"){
					
					$(".support_refresh").trigger("click");
					
				}
				
			});
		}else{
			$("#support_message").focus();
		}
	});
	
	$("#support_message").on("keyup",function(e) {
		if(e.keyCode === 13 ) {
			$("#support_send").trigger('click');
		}
	});
	
	$(".support_refresh").on("click", function(){
		var data = {
			"t_n"	:	"Support",

		};
		$(".support .c .display").html("<i style='font-size:30px;' class='fas fa-cog fa-spin'></i>");
		$.post("pages/default/ajax/support/get.php",{'data':data},function(rr){
			$(".support .c .display").html(rr);
		});
	});
	
	function listner(){
		if($(".support").length !== 0){
			timer = setInterval(function(){
				
				totalItems = $(".support .c .display .item").length;
				
				var data = {
					't_n'	:	'Support'
				}
				;
				$.post("pages/default/ajax/support/getTotalItems.php",{'data':data}, function(r){
					
					if(totalItems===0){
						totalItems = r;
					}else if( totalItems < r ){
						
						var animation = 'animated bounce';

						$(".support .icon .badge").addClass(animation).one(animationend, function(){
							$(".support .icon .badge").html(r - totalItems);
							$(".support .icon .badge").removeClass(animation);	

						});
						
					}else{
						$(".support .icon .badge").html(0);
					}
					
				});
				
			}, loop);
		}		
	}
	listner();

	
});