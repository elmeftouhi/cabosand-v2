
$(document).ready(function(e) {
var animationend = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';	

	alert("uploader");
	/*
    $('#upload_file').change(function(){
		$('#file_up').css('display','none');
		$('#upload_btn').css('display','inline-block').hide().fadeIn('slow');
	});
	$("#file_up").click(function (e) {
		e.preventDefault();
    	$("#upload_file").trigger('click');
	});	
	
	$('#upload_btn').on('click',function(e){
		e.preventDefault();
		$('#upload_btn').fadeOut('fast').css('display','none');
		fichier("upload_file");
		return false;
	});
	*/
});

function uploader(params){
	var IdIputFile;			// Contains the file selected by File Element
		IdIputFile = params['IdIputFile'];
		
	var PHPUploader;		// Path to the PHP File Uploder
		PHPUploader = params['PHPUploader'];
		
	var PHPUploaderParams;	// Parameters to Pass to the PHPUploader
		PHPUploaderParams = params['PHPUploaderParams'];
	
	var IdProgress;			// The ID of the progress bar to display progress <div id="progress"
							// <div id="progress"><div id="progress-bar"></div></div>
		IdProgress = "progress";
	
	var xhrq = check_ajax_version();

	var form = new FormData(); //	Internet Explorer does not support it.
	var file = div(IdIputFile).files[0];
	form.append("upload_file", file);
	
	xhrq.upload.addEventListener("progress", upload_progress(IdProgress), false);
	xhrq.addEventListener("load", upload_response,false);
	xhrq.open("POST", PHPUploader+"?"+"param=" + PHPUploaderParams);
	xhrq.send(form);

	return false;
}


//	Event listener for the progress of the file
function upload_progress(event,IdProgress){
	var upload_percentage = 0;
	var IdProgress = IdProgress;
	
	if(event.lengthComputable){
		upload_percentage = Math.round((event.loaded / event.total) * 100);
		
		$(IdProgress).css('display','block');
		$(IdProgress+" .progress-bar").css('width',upload_percentage.toString() + "%");
		$(IdProgress+" .progress-bar").html(upload_percentage.toString() + "%");

		if(upload_percentage == 100){
			var animation = 'animated fadeOut';
			$(IdProgress).addClass(animation).one(animationend, function(){
				$(IdProgress).removeClass(animation);
				$(IdProgress).css('display','none');	
			});	
		}
	}
	return false;
}

//	Response from server whether success or failure
function upload_response(event){
	var response = null;
	
	if(event.target.responseText){
		response = event.target.responseText;
		alert(response);
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