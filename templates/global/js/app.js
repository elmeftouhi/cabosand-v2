var HOST = 'http://localhost/apps/cabosand/';
//var HOST = 'http://www.manager.kabilamarina.com/';
var animationend = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";

$(document).ready(function(){

	$("#password").on("keyup",function(e) {
		if(e.keyCode === 13 ) {
			$(".btn_login").trigger('click');
		}
	});
	
	$(".btn_login").on("click", function(){
		var action = $(this).val();
		var columns = {
			"login*"		:	$("#login").val(),
			"password*"		:	$("#password").val(),
		};
		
		var _true = true;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1"){
					if(key.includes("*")){
						$("#" + key).addClass('error');
						_true = false;
					}
				}else{
					$("#" + key).removeClass('error');

				}			
			}
		}
		
		if(_true){
			
		var params = {
			"action"	: 	$(this).val(),
			"args"		:	{
				"login"			:	$("#login").val(),
				"password"		:	$("#password").val(),
				"formToken"		:	$("#formToken").val(),
				"remember"		:	($("#remember").is(':checked'))? 1:0,
			}
		};
			var this_btn = $(this);
			this_btn.find(".is_doing").removeClass("hide");
			this_btn.find(".do").addClass("hide");
			this_btn.prop("disabled",true);
			$.post("pages/login/ajax/login.php",{'param':params}, function(response){

				if(response=== "1"){
					if(action === "logout"){
						this_btn.val("login");
					}else{
						this_btn.val("logout");
					}

					this_btn.find(".is_doing").addClass("hide");
					this_btn.find(".do").removeClass("hide");
					this_btn.prop("disabled",false);

					$(".login_response").html('	<div class="info info-success"><b>Success ! </b> <div class="info-message">Message returned from server</div></div>');
					location.reload();
					//setInterval(function(){ location.reload(); }, 3000);


				}else{
					$(".login_response").html('	<div class="info info-error info-dismissible"> <div class="info-message"> '+response+' </div> <a href="#" class="close" data-dismiss="info" aria-label="close">&times;</a></div>');

					this_btn.find(".is_doing").addClass("hide");
					this_btn.find(".do").removeClass("hide");
					this_btn.prop("disabled",false);

				}


			});			
		}
		

		
	});
	
});


	function upload(params){
		"use strict";

		var IdInputFile = params.IdInputFile;
		var link = params.link;

		var xhrq = check_ajax_version();

		var form = new FormData(); //	Internet Explorer does not support it.
		var file = div(IdInputFile).files[0];
		form.append("upload", file);

		xhrq.upload.addEventListener("progress", upload_progress, false);
		xhrq.addEventListener("load", upload_response,false);

		xhrq.open("POST", link+"?uid="+params.params.UID+"&folder="+params.params.folder+"&is_unique="+params.params.is_unique);
		xhrq.send(form);

		return false;
	}

	//	Event listener for the progress of the file
	function upload_progress(event){

		"use strict";

		var upload_percentage = 0;
		var IdProgress = ".progress";

		if(event.lengthComputable){

			upload_percentage = Math.round((event.loaded / event.total) * 100);

			$(IdProgress).removeClass('hide');
			$(IdProgress+" .progress-bar").css('width',upload_percentage.toString() + "%");
			$(IdProgress+" .progress-bar").html(upload_percentage.toString() + "%");

			if(upload_percentage === 100){

				$(IdProgress).addClass('hide');

				var loader_template = '';
				loader_template += '	<div id="preloader" class="loader-container dashed">';
				loader_template += '		<div id="loader"></div>';
				loader_template += '	</div>';

				$('.image-container').prepend(loader_template);

			}
		}
		return false;
}

	//	Response from server whether success or failure
	function upload_response(event){
		"use strict";
		var response = null;

		if(event.target.responseText){		
			response = event.target.responseText;
			$("#preloader").remove();
			console.log(response);
			if(response === '1'){
				if($('.image-reload').length > 0){
					$('.image-reload').trigger('click');
				}else{
					$('.reload-files').trigger('click');
				}
				
			}else{
				
			}
		}
		return false;	
}

	//	Function to check the AJAX version of the browser
	function check_ajax_version(){
		var version = false;
		var ie_versions = ["MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.3.0", "Microsoft.XMLHTTP"];
		for(var i = 0; i < ie_versions.length; i++)
		{
			try
			{
				version = new ActiveXObject(ie_versions[i]);
				break;
			}
			catch(e)
			{
				continue;
			}
		}
		if(version == false)
		{
			version = new XMLHttpRequest();
		}
		else
		{
			version = version;
		}
		return version;
	}

	//  Mimic jQuery
	function div(id_of_element){
		id_of_element = document.getElementById(id_of_element);
		return id_of_element;
	} 


