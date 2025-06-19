
$(document).ready(function(){
	
	"use strict";
	
	$(document).on('keypress', '#email, #password', function(e){
		if(e.which === 13) {
			$('.button-login').trigger('click');
		}
	});
	
	$(document).on('click', '.button-login', function(){
		var loader_template = '';
		loader_template += '	<div id="preloader" class="loader-container">';
		loader_template += '		<div id="loader"></div>';
		loader_template += '	</div>';
		
		$('.form').append(loader_template);
		$(this).prop('disabled', true);
		$("#email").prop('disabled', true);
		$("#password").prop('disabled', true);
		
		var icons = {
			'success'	:	'<i class="fas fa-check"></i>',
			'error'		:	'<i class="fas fa-exclamation-triangle"></i>',
			'disabled'	:	'<i class="fas fa-user-alt-slash"></i>'
		};
		var message = '';
		message = 	'	<div class="login-response d-flex {{code}}">';
		message +=	'		<div class="icon">{{icon}}</div>';
		message +=	'		<div class="message">{{message}}</div>';
		message +=	'	</div>';
		
		
		var data = {
			'controller'	:	'Login',
			'method'		:	'Auth',
			'params'		:	{
				'email'			:	$("#email").val(),
				'password'		:	$("#password").val(),
				'token'			:	$("#token").val(),
				'remember'		:	$("#is_remember").prop("checked")
			}
		};
		
		$("#password").removeClass('error');
		$("#email").removeClass('error');
		if($('.login-container .login-response').length > 0){
			$('.login-container .login-response').addClass('animate__bounceOutUp').remove();
		}  
		
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/login/auth/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			console.log(response);
			if(response.code === 1){
				
				if(response.msg === 'success'){
					$('.login-container').prepend( message.replace( "{{icon}}", icons.success ).replace( "{{code}}", 'success' ).replace( "{{message}}", "Success!" ) );	
					location.reload();
				}else if(response.msg === 'disabled'){
					$('.login-container').prepend( message.replace( "{{icon}}", icons.disabled ).replace( "{{code}}", 'disabled' ).replace( "{{message}}", "Account Disabled!" ) );
				}else{
					$('.login-container').prepend( message.replace( "{{icon}}", icons.error ).replace( "{{code}}", 'error' ).replace( "{{message}}", response.msg ) );
					$("#password").addClass('error');
					$("#email").addClass('error');
				}
				$('.login-container .login-response').addClass('animate__animated animate__shakeX');
				
				$('.button-login').prop('disabled', false);
				$("#email").prop('disabled', false);
				$("#password").prop('disabled', false);
				
				setTimeout(function(){ 
					$('.login-container .login-response').addClass('animate__bounceOutUp'); 
				}, 3000);
				
				
			}
			$("#preloader").remove();
			console.log(response);
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
	});
	
});