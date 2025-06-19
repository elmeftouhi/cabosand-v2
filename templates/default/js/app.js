

$(document).ready(function() {
	
	"use strict";
		
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
	
	/****************************
			NOTES
	*****************************/
	
	$(document).on('click', '.refresh_note', function(){
		var data = {
				'module' 	: 	$(this).attr("data-module"),
				'id'		:	$(this).attr("data-id_module")
					};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/notes/get.php",
			data		:	data,
			dataType	: 	"text",
		}).done(function(response){

			$(".notes").find("table").remove();
			$(".notes").prepend(response);
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			alert(textStatus);
		});
	});
	
	$(document).on('click', '.notes_set_status', function(){
		var data = {
			'notes' : {
				'id'		:	$(this).attr("data-notes_id"),
				'status'	:	$(this).hasClass("on")? 1:0
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/notes/set_status.php",
			data		:	data,
			dataType	: 	"text",
		}).done(function(){

			$('.refresh_note').trigger("click");

		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			alert(textStatus);
		});	
		
	});
	
	$(document).on("click", ".add_note", function(){
		var notes = prompt("Entrez votre notes", "");
		if (notes !== null && notes !=="") {
			var data = {
				'notes' : {
					'module'		:	$(this).attr("data-module"),
					'id_module'		:	$(this).attr("data-id_module"),
					'notes'			:	notes,
				}
			};
			
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/notes/save.php",
				data		:	data,
				dataType	: 	"text",
			}).done(function(){

				$('.refresh_note').trigger("click");

			}).fail(function(response, textStatus){
				$(".debug").html(textStatus);
				alert(textStatus);
			});	
			
		}
	});
	
	$(document).on('click', '.delete_note', function(){
		if (confirm('Voulez-Vous supprimer cette note?')) {
			var data = {
				'id'		:	$(this).attr("data-id")
			};

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/notes/delete.php",
				data		:	data,
				dataType	: 	"text",
			}).done(function(){

				$('.refresh_note').trigger("click");

			}).fail(function(response, textStatus){
				$(".debug").html(textStatus);
				alert(textStatus);
			});	
		}
	});
	
	/****************************
			CONTRAT
	*****************************/	
	
	$(document).on('click','.check_all',function(){
		
		$('.propriete_verify').each(function(){
			$(this).trigger('click');
		});
		
	});
	
	$(document).on('click', '.propriete_verify', function(){
		
		var data = {
			'module'	:	'propriete_check',
			'options'	:	{
				'UID'		:	$(this).attr('data-uid'),
				'id'		:	$(this).attr('data-id')
			}
		};
		
		var _this = $(this);
	
		_this.find(".is_doing").removeClass("hide");
		_this.find(".do").addClass("hide");
		_this.prop("disabled",true);
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			if (response.code === 1){
				if(response.msg){
					_this.parent().parent().find('.propriete_checked').removeAttr('checked');
					_this.parent().parent().find('.propriete_checked').attr('disabled','disabled');
				}else{
					_this.parent().parent().find('.propriete_checked').attr('checked','checked');
					_this.parent().parent().find('.propriete_checked').removeAttr('disabled');
				}
				$(".debug").html("");
				
				_this.find(".is_doing").addClass("hide");
				_this.find(".do").removeClass("hide");
				_this.prop("disabled",false);
				
			}else{
				_this.find(".is_doing").addClass("hide");
				_this.find(".do").removeClass("hide");
				_this.prop("disabled",false);
				alert(response.msg);
				$(".debug").html("");
			}
							
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			$(".modal").html("").removeClass('show');
		});
		
	});
	
	$(document).on("click", ".refresh_appartement", function(e){
		e.preventDefault();
		
		var tag = "propriete_location";
		var data = {
			'UID'	:	$(this).val()
		};
		
		if($(this).hasClass("btn")){
			data.UID = $(this).val();
		}else{
			data.UID = $(this).attr("data");
		}
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$("."+tag).html("");

		$.post("pages/default/ajax/"+tag+"/get.php",{'data':data},function(response){
			$("."+tag).html(response);
			$(".modal").removeClass("show");
			
		});
	});
	
	$(document).on('change', '#periode_de, #periode_a', function(){
		var d1 = $("#periode_de").val();
		var d2 = $("#periode_a").val();
		
		var start = moment(d1, "YYYY-MM-DD");
		var end = moment(d2, "YYYY-MM-DD");

		//Difference in number of days
		//moment.duration(start.diff(end)).asDays();

		//Difference in number of weeks
		//moment.duration(start.diff(end)).asWeeks();
		if(moment.duration(end.diff(start)).asDays()>0){
			$("#periode_nuite").val( Math.round( moment.duration(end.diff(start)).asDays() ) );
			$("#periode_nuite").removeClass("error");
		}else{
			$("#periode_nuite").addClass("error");
			$("#periode_nuite").val("0");
		}
		
	});
	
	$(document).on('click', '.periode.save', function(){
		var _status = $("#periode_status").hasClass("on")? 1 : 0;
		
		var UID="";
		
		if($(this).hasClass("edit")){
			UID = $(this).attr("data-uid");
		}else{
			UID = $(this).val();
		}
		
		var columns = {
			'periode_de*'				:	$("#periode_de").val(),
			'periode_a*'				:	$("#periode_a").val(),
			'periode_nuite*'			:	$("#periode_nuite").val(),
			'UID'						:	UID,
			'status'					:	_status
		};
		
		if($(this).hasClass("edit")){
			columns.id = $(this).val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
					if(key.includes("*")){
						$("#" + key).addClass('error');
						_true = false;
						console.log("error!");		
					}
				}else{
					$("#" + key).removeClass('error');

				}			
			}
		}
		
		if(_true){
			var data = {

						't_n'				:	'Contrat_Periode',
						'columns'			:	columns
			};
			$.post("pages/default/ajax/contrat_periode/save.php",{'data':data},function(response){
				if(response === "1"){
					$('.periode_refresh').trigger("click");
				}else{
					$(".debug").html(response);
				}
				


			});	
		}
		
	});

	$(document).on('click', '.periode.edit', function(e){
		//var UID = $(this).val();
		e.stopImmediatePropagation();
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var id_ligne = 0;
		if($(this).hasClass("btn")){
			id_ligne = $(this).val();
		}else{
			id_ligne = $(this).attr("data-id");
		}
		
		var data = {
			'module' 	: 	'periode_edit',
			'options'	:	{'id':id_ligne}
		};
		/*
		$.post("pages/default/ajax/contrat/util.php",{'data':data}, function(r){
			$(".debug").html(r);
		});
		*/
		
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
			}
							
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			$(".modal").html("").removeClass('show');
		});
		
		
	});
	
	$(document).on('click', '.periode.add', function(){
		//var UID = $(this).val();
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var id_ligne = 0;
		if($(this).hasClass("btn")){
			id_ligne = $(this).val();
		}else{
			id_ligne = $(this).attr("data");
		}
		
		
		var data = {
			'module' 	: 	'periode',
			'options'	:	{'id':id_ligne}
		};
		/*
		$.post("pages/default/ajax/contrat/util.php",{'data':data}, function(r){
			$(".debug").html(r);
		});
		*/
		
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
			}
							
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			$(".modal").html("").removeClass('show');
		});
		
		
	});
	
	$(document).on('click','.periode_refresh',function(){

		var UID = $(this).val();
		var data = {
			UID		:	UID,
			t_n		:	'Contrat_Periode'
		};
		
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$(".contrat_periode").html("");

		$.post("pages/default/ajax/contrat_periode/get.php",{'data':data},function(response){
			$(".contrat_periode").html(response);
			$(".modal").removeClass("show");
			
		});

	});
	
	$(document).on('click', '.propriete_checked_all', function(){
		var nbr;
		if($(this).is(':checked')){
			$(".propriete_checked").each(function(){
				if(!$(this).attr("disabled")){
					$(this).attr('checked','checked');
				}
			});
			nbr = $('.propriete_checked:checked').length;
			$("._select_this_propriete").html("Select (" + nbr + ")");
		}else{
			if(!$(this).attr("disabled")){
				$(".propriete_checked").removeAttr('checked');
			}
			
			nbr = $('.propriete_checked:checked').length;
			$("._select_this_propriete").html("Select (" + nbr + ")");
		}
	});
	
	$(document).on('click', '.propriete_checked', function(){
		var nbr = $('.propriete_checked:checked').length;
		$("._select_this_propriete").html("Select (" + nbr + ")");
	});
	
	$(document).on('click', '._select_propriete', function(){

		var nbr_periode = $(".periode_nbr").attr('data-nbr');
		if(nbr_periode === "0"){
			alert("select periode !");
		}else{
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			
			var data = {
				'module'	: 'propriete',
				'UID'		:	$(this).val()
			};
			/*
			$.post("pages/default/ajax/contrat/util.php", data, function(r){$(".debug").html(r); $(".modal").removeClass("show"); $(".modal").html("");  });
			*/
			
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/contrat/util.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if (response.code === 1){
					$(".modal").html("<div class='modal-content' style='width:480px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");

				}else{
					$(".modal").html("<div class='modal-content' style='width:480px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				}

			}).fail(function(response, textStatus){
				$(".debug").html(textStatus);
			});		
			

		}

	});

	$(document).on('click', '.__search', function(){
		
		var data = {
			'module' 	: 	'propriete',
			'UID'		:	$(this).val()
		};
		
		var request={};
		
		if($("#_r").val() !==""){request.code = $("#_r").val();}
		if($("#_complexe").val() !=="-1"){request.complexe = $("#_complexe").val();}
		
		data.request = request;
		/*
		$.post("pages/default/ajax/contrat/util.php",data,function(r){
			console.log(r);
			$(".debug").html(r);
		});
		*/

		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){

			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:480px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:480px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}			
		}).fail(function(response, textStatus){
			alert(textStatus);
			$(".debug_client").html(textStatus);
		});

	});
	
	$(document).on('change','#upload_file_contrat',function(){
		var id_client =  $(this).attr("data");
		
		var params = {
			IdIputFile			:	"upload_file_contrat",
			PHPUploader			:	"pages/default/ajax/upload_files.php",
			PHPUploaderParams	:	"?id=contrat/"+id_client
			
		};
		
		
		if($(this).val() !== ""){
			uploader(params);
		}
		
	});
	
	$(document).on('click', '._select_this_propriete', function(){
		
		var selected = [];
		$(".propriete_checked").each(function(){
			if($(this).is(':checked')){
				selected.push($(this).attr("data-id"));
			}
		});
		if(selected.length>0){
			var IDS 		=  selected;
			var UID		=	$(".UID").html();

			var _this = $(this);
			var data = {
				'module' 	: 	'add',
				'IDS'		:	IDS,
				'UID'		:	UID
			};

			//$.post("pages/default/ajax/depense/util.php", {'module':'propriete'}, function(r){$(".debug_client").html(r);});

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/contrat/util.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){

				if (response.code === 1){
					//$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
					$(".refresh_appartement").trigger("click");
					//$("._select_propriete").trigger("click");
					//_this.parent().html(response.msg);
					//_this.remove();

				}else{
					$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				}

			}).fail(function(response, textStatus){
				$(".debug").html(textStatus);
			});			
		}


		
		
		//$("._close").trigger('click');

	});
	
	$(document).on('click', '.edit_ligne_propriete_location', function(e){
		e.stopImmediatePropagation();
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

		var id = 0;
		
		if($(this).hasClass("btn")){
			id = $(this).val();
		}else{
			id = $(this).find(".id-ligne").html();
		}
		
		var data = {
			'module' 	 : 'propriete_edit',
			'options'	 : {'id': id}
		};

		/*
		$.post("pages/default/ajax/contrat/util.php",data, function(r){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + r + "</div>");
				$(".debug").html("");
		});
		*/
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/contrat/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug").html(textStatus);
			$(".modal").html("").removeClass('show');
		});


	});
	
	$(document).on('click', '.remove_ligne_propriete_location', function(e){
		
		e.stopImmediatePropagation();
		var _this = $(this);

		swal({
			  title: "Vous êtes sûr?",
			  text: "Êtes vous sûr de vouloir supprimer cette ligne? ",
				type:"warning",
				showCancelButton:!0,
				confirmButtonColor:"#3085d6",
				cancelButtonColor:"#d33",
				confirmButtonText:"Oui, Supprimer!"
			}).then(function(t){
			  if (t.value) {

					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

					var id = 0;

					if(_this.hasClass("btn")){
						id = _this.val();
					}else{
						id = _this.find(".id-ligne").html();
					}

					var data = {
						'module' 	 : 'propriete_remove',
						'options'	 : {'id': id}
					};

					/*
					$.post("pages/default/ajax/contrat/util.php",data, function(r){
							$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + r + "</div>");
							$(".debug").html("");
					});
					*/

					$.ajax({
						type		: 	"POST",
						url			: 	"pages/default/ajax/contrat/util.php",
						data		:	data,
						dataType	: 	"json",
					}).done(function(response){

						if (response.code === 1){
							$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
							$(".refresh_appartement").trigger("click");
							$(".debug").html("");

						}else{
							$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
							$(".refresh_appartement").trigger("click");
							$(".debug").html("");
						}

					}).fail(function(response, textStatus){
						$(".debug").html(textStatus);
						$(".modal").html("").removeClass('show');
					});


			  } else {

			  }
		});	
		
		
		

		

	});
	
	$(document).on('click', '.add_contrat', function(){
		alert('add');
	});
	
	/****************************
			ENTREPRISE
	*****************************/		
	$(document).on('click','.actions.entreprise_forme_juridique .save',function(){
		var _status = $("#forme_juridique_status").hasClass("on")? 1 : 0;
		var is_default = $("#forme_juridique_is_default").hasClass("on")? 1 : 0;
		
		var columns = {
			'forme_juridique*'			:	$("#forme_juridique").val(),
			'ABR*'						:	$("#ABR").val(),
			'status'					:	_status,
			'is_default'				:	is_default,

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1" ){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Entreprise_Forme_Juridique',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/entreprise_forme_juridique/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Ajouté avec success!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/entreprise_forme_juridique.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	$(document).on('click','.actions.entreprise .save',function(){
		var _status = $("#status").hasClass("on")? 1 : 0;
		var is_default = $("#is_default").hasClass("on")? 1 : 0;
		
		var columns = {
			'raison_social*'			:	$("#raison_social").val(),
			'forme_juridique*'			:	$("#forme_juridique").val(),
			'slogon'					:	$("#slogon").val(),
			'capital'					:	$("#capital").val(),
			'adresse*'					:	$("#adresse").val(),
			'telephone_1*'				:	$("#telephone_1").val(),
			'telephone_2'				:	$("#telephone_2").val(),
			'fax_1'						:	$("#fax_1").val(),
			'fax_2'						:	$("#fax_2").val(),
			'email'						:	$("#email").val(),
			'site_internet'				:	$("#site_internet").val(),
			'ice*'						:	$("#ice").val(),
			'registre_commerce*'		:	$("#registre_commerce").val(),
			'patente*'					:	$("#patente").val(),
			'identification_fiscale*'	:	$("#identification_fiscale").val(),
			'cnss*'						:	$("#cnss").val(),
			'notes'						:	$("#notes").val(),
			'status'					:	_status,
			'is_default'				:	is_default,

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1" ){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Entreprise',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/entreprise/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Ajouté avec success!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/entreprise.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	
	/****************************
			DEPENSE
	*****************************/		
	
	$(document).on('click','.actions.depense .save',function(){
		var _status = $("#depense_status").hasClass("on")? 1 : 0;
		var columns = {
			'depense_category*'			:	$("#depense_category").val(),
			'depense_caisse*'			:	$("#depense_caisse").val(),
			'depense_montant*'			:	$("#depense_montant").val(),
			'depense_libelle*'			:	$("#depense_libelle").val(),
			'notes'						:	$("#notes").val(),
			'depense_status'			:	_status,
			'depense_propriete'			:	$("#propriete_id").val(),
			'UID'						:	$("#UID*").val()
		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1" ){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Depense',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/depense/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/depense.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
		
	$(document).on('click','.actions.depense_category .save',function(){
		var _status = $("#depense_category_status").hasClass("on")? 1 : 0;
		var columns = {
			'depense_category*'	:	$("#depense_category").val(),
			'is_default'			:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Depense_Category',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/depense_category/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/depense_category.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	$(document).on('click','.actions.caisse .save',function(){
		var _status = $("#caisse_status").hasClass("on")? 1 : 0;
		var columns = {
			'name*'				:	$("#name").val(),
			'solde_initial*'	:	$("#solde_initial").val(),
			'solde_minimum*'	:	$("#solde_minimum").val(),
			'notes'				:	$("#notes").val(),
			'caisse_status'		:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Caisse',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/caisse/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/caisse.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
		
	$(document).on('click', '.c_a_save', function(){

		var columns = {
			'c_a_montant*'		:	$("#c_a_montant").val(),
			'c_a_source'		:	$("#c_a_source").val(),
			'c_a_notes'			:	$("#c_a_notes").val(),
			'c_a_id_caisse*'	:	$(this).attr("data")
		};
		
		if($(this).hasClass("edit")){
			columns.id = $(this).val();
		}
		
		var _true = true	;

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
			//data['columns']['date_naissance*'] = 
			var data = {
				't_n'				:	'Caisse_Alimentation',
				'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/caisse_alimentation/save.php",{'data':data},function(response){
				
				if (response === "1"){
					$(".refresh_c_a").trigger("click");
				}else{
					$(".debug_client").html(response);
				}

			});	
		}	
	
	});
	
	$(document).on("click", ".refresh_c_a", function(){
		var data = {
			'module' 	 : 'caisse_alementation',
			'options'	 : {'id_caisse':$(this).val()}
		};

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$(".c_a_form").html("");

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/caisse/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("").removeClass('show');
				$(".c_a_form").html(response.msg);
				$(".debug_client").html("");
				
			}else{
				$(".modal").html("").removeClass('show');
				$(".c_a_form").html(response.msg);
				$(".debug_client").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});
		
	});
	
	$(document).on('click', '.edit_c_a', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			'module' 	 : 'edit',
			'options'	 : {'id': $(this).attr("data")}
		};
		/*
		$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
			$(".debug_client").html(r);
		});
		*/

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/caisse/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});

		
	});
	
	$(document).on('click', '.add_c_a', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			'module' 	 : 'add',
			'options'	 : {'id': 0, 'id_caisse':$(this).val()}
		};
		/*
		$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
			$(".debug_client").html(r);
		});
		*/

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/caisse/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});

		
	});
	
	$(document).on('click', '.select_propriete', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			'module' : 'propriete',
		};
		
		//$.post("pages/default/ajax/depense/util.php", data, function(r){$(".debug").html(r);});
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/depense/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
		});

		
	});
	
	$(document).on('click', '.select_this_propriete', function(){
		var ID 		=  $(this).attr("data-id");
		var NUMERO 	=  $(this).attr("data-numero");
		var ZONE 	=  $(this).attr("data-zone");
		var BLOC	=  $(this).attr("data-bloc");
		var COMPL 	=  $(this).attr("data-complexe");
		var CODE	=  $(this).attr("data-code");

		$("#propriete_id").val(ID);
		$("#propriete_complexe").val(COMPL);
		$("#propriete_code").val(CODE);
		$("#propriete_numero").val(NUMERO);
		$("#propriete_zone").val(ZONE);
		$("#propriete_bloc").val(BLOC);
		$("._close").trigger('click');
		
	});
	
	$(document).on('click', '._search', function(){

		var data = {
			'module' 	: 	'propriete',
		};
		
		var request={};
		
		if($("#_r").val() !==""){request.code = $("#_r").val();}
		if($("#_complexe").val() !=="-1"){request.complexe = $("#_complexe").val();}
		
		data.request = request;
		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/depense/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			console.log(response);
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
								
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		
		/*
		var data = {
			'controler'	:	'Commande',
			'function'	:	'Edit_Item',
			'params'	:	{'id':id, 'qte':new_qte}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	HOST + "pages/default/ajax/commande/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.cart_general_refresh').trigger('click');
			console.log(response);
		}).fail(function(xhr) {
			alert("Error");
			console.log(xhr.responseText);
		});
		*/
		
		
	});

	$(document).on('click', '.depense_direction', function(){
		$(".depense_chart").attr('data-year', parseInt( $(".depense_chart").attr('data-year') ) + parseInt( $(this).attr('data-step') ) );
		$(".depense_label").html( $(".depense_chart").attr('data-year') );
		$('.depense_chart').trigger('click');
	});
	
	/****************************
			CLIENT
	*****************************/	
	
	$(document).on('change','#id_color',function(){
		
		var element = $(this).find('option:selected');  
		var color = element.attr('data-hex');
		$("#color").css('background-color', color);
	});
	
	$(document).on('click','.actions.client .save',function(){
		
		var columns = {
			'first_name*'				:	$("#first_name").val(),
			'last_name*'				:	$("#last_name").val(),
			'societe_name'				:	$("#societe_name").val(),
			'client_category*'			:	$("#client_category").val(),
			'client_type*'				:	$("#client_type").val(),
			'client_status*'			:	$("#client_status").val(),
			'client_cin'				:	$("#client_cin").val(),
			'client_passport'			:	$("#client_passport").val(),
			'client_ville*'				:	$("#client_ville").val(),
			'client_adresse'			:	$("#client_adresse").val(),
			'client_email'				:	$("#client_email").val(),
			'client_contact_1'			:	$("#client_contact_1").val(),
			'client_contact_2'			:	$("#client_contact_2").val(),
			'client_notes'				:	$("#client_notes").val(),
			'id_color'					:	$("#id_color").val(),
			'UID'						:	$("#UID").val()
		};
		
		if($("#id").length !== 0) {
		  columns.id = $("#id").val();
		}
		
		
		var _true = true	;

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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.client_status .save',function(){
		var _status = $("#client__status").hasClass("on")? 1 : 0;
		var columns = {
			'client_status*'	:	$("#client_status").val(),
			'is_default'			:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client_Status',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client_status/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client_status.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.client_document_category .save',function(){
		var columns = {
			'document_category*'		:	$("#document_category").val()

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client_Document_Category',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client_document_category/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client_document_category.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.client_modalite_paiement .save',function(){
		var _status = $("#client_modalite_paiement_status").hasClass("on")? 1 : 0;
		var columns = {
			'client_modalite_paiement*'	:	$("#client_modalite_paiement").val(),
			'is_default'				:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client_Modalite_Paiement',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client_modalite_paiement/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client_modalite_paiement.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.client_category .save',function(){
		var _status = $("#client_category_status").hasClass("on")? 1 : 0;
		var columns = {
			'client_category*'	:	$("#client_category").val(),
			'is_default'			:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client_Category',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client_category/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client_category.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.client_type .save',function(){
		var _status = $("#client_type_status").hasClass("on")? 1 : 0;
		var columns = {
			'client_type*'	:	$("#client_type").val(),
			'is_default'			:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Client_Type',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/client_type/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/client_type.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	
	/*****************************
			PERSON SECTION
	******************************/
	
	$(document).on('click', '.person_password_reset', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var id_person = $(this).val();
		var data = {
			'module' 	 : 'person',
			'options'	 : {'id_person': id_person, 'person_password':$("#person_password").val()}
		};
		/*
		$.post("pages/default/ajax/person/util.php",{'module':'person','options':{'id_person': id_person, 'person_password':$("#person_password").val()}}, function(r){
			//$(".debug_client").html(r);
			alert(r);
		});
		*/
		

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/person/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				alert(response.msg);
				$(".debug_client").html("");
				$(".modal").html("").removeClass('show');
				
			}else{
				$(".debug_client").html('	<div class="info info-error info-dismissible"> <div class="info-message"> '+response+' </div> <a href="#" class="close" data-dismiss="info" aria-label="close">&times;</a></div>');
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});

		
	});

	$(document).on('click','.actions.person .save',function(){
		var _status = $("#person_status").hasClass("on")? 1 : 0;
		var columns = {
			'person_first_name*'	:	$("#person_first_name").val(),
			'UID*'					:	$("#UID").val(),
			'person_last_name*'		:	$("#person_last_name").val(),
			'person_profile*'		:	$("#person_profile").val(),
			'person_telephone'		:	$("#person_telephone").val(),
			'person_email'			:	$("#person_email").val(),
			'person_login*'			:	$("#person_login").val(),
			'person_password*'		:	$("#person_password").val(),
			'status'				:	_status

		};
		
		if($("#id").length>0){
			columns.id = $("#id").val();
			delete columns["person_password*"];
		}

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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Person',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/person/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({
						
						type		: 	"POST",
						url			: 	"pages/default/includes/person.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	

	$(document).on('click',".apercu_creative",function(){
		$(".screen_apercu").html($("#body").val());
		
	});


	/*********************************
			PERSON PROFILE SECTION
	**********************************/
	
	$(document).on('click','.actions.person_profile .save',function(){
		var _status = $("#person_profile_status").hasClass("on")? 1 : 0;
		var columns = {
			'person_profile*'	:	$("#person_profile").val(),
			'is_default'			:	_status

		};

		if($("#id").length>0){
			columns.id = $("#id").val();
		}
		
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Person_Profile',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/person_profile/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/person_profile.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		
	
	$(document).on('change','#upload_file_person',function(){
		var id_client =  $(this).attr("data");
		
		var params = {
			IdIputFile			:	"upload_file_person",
			PHPUploader			:	"pages/default/ajax/upload_files.php",
			PHPUploaderParams	:	"?id=person/"+id_client
			
		};
		
		
		if($(this).val() !== ""){
			uploader(params);
		}
		
	});
	
	/**********************************
			PROPRIETAIRE SECTION
	***********************************/	
	
	$(document).on('click','.actions.proprietaire .save',function(){
		var proprietaire_status = $("#proprietaire_status").hasClass("on")? 1 : 0;
		var columns = {
			'proprietaire_name*'		:	$("#proprietaire_name").val(),
			'proprietaire_cin'			:	$("#proprietaire_cin").val(),
			'proprietaire_passport'		:	$("#proprietaire_passport").val(),
			'proprietaire_ville'		:	$("#proprietaire_ville").val(),
			'proprietaire_email'		:	$("#proprietaire_email").val(),
			'proprietaire_adresse'		:	$("#proprietaire_adresse").val(),
			'proprietaire_contact_1*'	:	$("#proprietaire_contact_1").val(),
			'proprietaire_contact_2'	:	$("#proprietaire_contact_2").val(),
			'proprietaire_agence_1'		:	$("#proprietaire_agence_1").val(),
			'proprietaire_rib_1'		:	$("#proprietaire_rib_1").val(),
			'proprietaire_agence_2'		:	$("#proprietaire_agence_2").val(),
			'proprietaire_rib_2'		:	$("#proprietaire_rib_2").val(),
			'proprietaire_notes'		:	$("#proprietaire_notes").val(),
			'proprietaire_status'		:	proprietaire_status
		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			var name = $("#proprietaire_name").val();
			var _data = {
				'module' 	: 'is_exist',
				'name'	 	: name
			};
			/*
			$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
				$(".debug_client").html(r);
			});
			*/

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/proprietaire/util.php",
				data		:	_data,
				dataType	: 	"json",
			}).done(function(response){
				
				if (response.code === 1){
					$("#proprietaire_name").addClass('error');
					alert(response.msg);
				}else{
					
					//data['columns']['date_naissance*'] = 
					var data = {

								't_n'				:	'Proprietaire',
								'columns'			:	columns
					};
					
					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
					
					$.post("pages/default/ajax/proprietaire/save.php",{'data':data},function(response){

						$(".modal").removeClass("show");
						if(response === "1"){

							swal("SUCCESS!", "Le produit a été ajouté!", "success");
							var data = {
								"page"	:	"menu",
								"p"		:	{
									"s"		:	0,
									"pp"	:	50
								}
							};

							$.ajax({

								type		: 	"POST",
								url			: 	"pages/default/includes/proprietaire.php",
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



						}else{
							$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
						}


					});					
				}
			}).fail(function(response, textStatus){
				$(".debug_client").html(textStatus);
			});			
		}
		
	});		
	
	$(document).on('click','.actions.proprietaire .save_edit',function(){

		var proprietaire_status = $("#proprietaire_status").hasClass("on")? 1 : 0;
		var columns = {
			'proprietaire_name*'		:	$("#proprietaire_name").val(),
			'id*'						:	$("#id").val(),
			'proprietaire_cin'			:	$("#proprietaire_cin").val(),
			'proprietaire_passport'		:	$("#proprietaire_passport").val(),
			'proprietaire_ville'		:	$("#proprietaire_ville").val(),
			'proprietaire_email'		:	$("#proprietaire_email").val(),
			'proprietaire_adresse'		:	$("#proprietaire_adresse").val(),
			'proprietaire_contact_1*'	:	$("#proprietaire_contact_1").val(),
			'proprietaire_contact_2'	:	$("#proprietaire_contact_2").val(),
			'proprietaire_agence_1'		:	$("#proprietaire_agence_1").val(),
			'proprietaire_rib_1'		:	$("#proprietaire_rib_1").val(),
			'proprietaire_agence_2'		:	$("#proprietaire_agence_2").val(),
			'proprietaire_rib_2'		:	$("#proprietaire_rib_2").val(),
			'proprietaire_notes'		:	$("#proprietaire_notes").val(),
			'proprietaire_status'		:	proprietaire_status
		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Proprietaire',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/proprietaire/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/proprietaire.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
		
	/**********************************
			PROPRIETE SECTION
	***********************************/
	
	$(document).on('click', '.search .start', function(){
		var request = {};
		
		var current = $(".current").html();
		var pearPage = $("#showPerPage").val();
		var sort_by = $("#sort_by").html();
		
		var data = {
				't_n'		:	'Propriete',
				'current'	:	current,
				'p_p'		:	pearPage,
				'sort_by'	:	sort_by,
				'filter'	:	{}
			};
		
		
		$(".options .r").each(function(){
			if($(this).val() !== "" && $(this).val() !== "-1"){
				request[$(this).attr("id")] = $(this).val();
			}			
		});
		
		
		if(!jQuery.isEmptyObject(request)){
			data.filter = request;
		}
		

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");

		$.post("pages/default/ajax/propriete/get.php", {'data':data}, function(r){
			$(".modal").html("").removeClass("show");
			$(".propriete").html(r);
		});

		
	});
	
	$(document).on('click', '.refresh_propriete', function(){
		$('.search .start').trigger('click');
	});
	
	$(document).on('click', '.p_l_save', function(){
		
		var data = {
			't_n'	:	"Propriete_Location"
		};
		
		var _data = Array();
		
		var i = 0;
		
		$(".p_l").each(function(){
			
			if($(this).find(".p_l_status")){
				
				var ob = $(this).find(".p_l_status");
				if(ob.is(':checked')){
					
					_data[i] = {
						'status'	:	1,
						'p_l_de'	:	$(this).find(".p_l_de").val(),
						'p_l_a'		:	$(this).find(".p_l_a").val(),
						'id'		:	ob.attr("data-id")
					};
					
				}else{
					_data[i] = {
						'status'	:	0,
						'p_l_de'	:	$(this).find(".p_l_de").val(),
						'p_l_a'		:	$(this).find(".p_l_a").val(),
						'id'		:	ob.attr("data-id")
					};
				}

			}
			i++;	
		});
		
		data.columns = _data;
		
		$.post("pages/default/ajax/propriete_location/save.php",data, function(r){
			if(r==="1"){
				$(".modal").html("").removeClass('show');
				$(".debug").html("");
				$(".refresh_appartement").trigger("click");
			}else{
				$(".modal").html("").removeClass('show');
				$(".debug").html(r);
			}
			
		});
	});

	$(document).on('focusout','#propriete_code',function(){
				
		var code_temp = $("#propriete_code").attr("data-code");
		var code = $("#propriete_code").val();
		var _data = {
			'module' 	: 'is_exist',
			'code'	 	: code
		};
		/*
		$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
			$(".debug_client").html(r);
		});
		*/
		
		if( code === ""){
			$(".propriete_code_correct").addClass("hide");
			$("#propriete_code").addClass('error');
		}
		
		if(code_temp === -1 ){
		   // ADD NEW CASE
			$(".propriete_code_loding").removeClass("hide");
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/propriete/util.php",
				data		:	_data,
				dataType	: 	"json",
			}).done(function(response){
				$(".propriete_code_loding").addClass("hide");
				if (response.code === 1){
					$("#propriete_code").addClass('error');
					$(".propriete_code_correct").addClass("hide");
					alert(response.msg);
				}else{
					$(".propriete_code_correct").removeClass("hide");
					$("#propriete_code").removeClass('error');
				}
			});			
		}else{
			// EDIT CASE
			if(code_temp !== code && code !== ""){
				$(".propriete_code_loding").removeClass("hide");
				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/ajax/propriete/util.php",
					data		:	_data,
					dataType	: 	"json",
				}).done(function(response){
					$(".propriete_code_loding").addClass("hide");
					if (response.code === 1){
						$("#propriete_code").addClass('error');
						$(".propriete_code_correct").addClass("hide");
						alert(response.msg);
					}else{
						$(".propriete_code_correct").removeClass("hide");
						$("#propriete_code").removeClass('error');
					}
				});				
			}
		}
		   

	});
	
	$(document).on('click','.actions.propriete .save',function(){
		
		var selected = [];
		$('input.propriete_options:checked').each(function() {
			selected.push($(this).attr('value'));
		});
		
		var columns = {
			'propriete_complexe*'		:	$("#propriete_complexe").val(),
			'propriete_code*'			:	$("#propriete_code").val(),
			'propriete_category*'		:	$("#propriete_category").val(),
			'propriete_type*'			:	$("#propriete_type").val(),
			'propriete_status*'			:	$("#propriete_status").val(),
			'propriete_zone'			:	$("#propriete_zone").val(),
			'propriete_bloc*'			:	$("#propriete_bloc").val(),
			'propriete_numero*'			:	$("#propriete_numero").val(),
			'propriete_etage*'			:	$("#propriete_etage").val(),
			'propriete_surface*'		:	$("#propriete_surface").val(),
			'propriete_chambre*'		:	$("#propriete_chambre").val(),
			'propriete_max_person*'		:	$("#propriete_max_person").val(),
			'propriete_proprietaire_id*':	$("#propriete_proprietaire_id").val(),
			'propriete_isForSell'		:	$('#propriete_isForSell').is(':checked'),
			'propriete_isForLocation'	:	$('#propriete_isForLocation').is(':checked'),
			'propriete_notes'			:	$("#propriete_notes").val(),
			'UID'						:	$("#UID").val()
		};
		
		columns.propriete_options = selected;
		if($("#id").length !== 0) {
		  columns.id = $("#id").val();
		}
		
		
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
			_true = false;
			var code_temp = $("#propriete_code").attr("data-code");
			var code = $("#propriete_code").val();			
			var _data = {
				'module' 	: 'is_exist',
				'code'	 	: code
			};

			if(code_temp === -1 ){
			   // ADD NEW CASE
				$(".propriete_code_loding").removeClass("hide");
				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/ajax/propriete/util.php",
					data		:	_data,
					dataType	: 	"json",
				}).done(function(response){
					$(".propriete_code_loding").addClass("hide");
					if (response.code === 1){
						$("#propriete_code").addClass('error');
						$(".propriete_code_correct").addClass("hide");
						alert(response.msg);
					}else{
						$(".propriete_code_correct").removeClass("hide");
						$("#propriete_code").removeClass('error');
						_true = true;
					}
				});			
			}else{
				// EDIT CASE
				if(code_temp !== code && code !== ""){
					$(".propriete_code_loding").removeClass("hide");
					$.ajax({
						type		: 	"POST",
						url			: 	"pages/default/ajax/propriete/util.php",
						data		:	_data,
						dataType	: 	"json",
					}).done(function(response){
						$(".propriete_code_loding").addClass("hide");
						if (response.code === 1){
							$("#propriete_code").addClass('error');
							$(".propriete_code_correct").addClass("hide");
							alert(response.msg);
						}else{
							$(".propriete_code_correct").removeClass("hide");
							$("#propriete_code").removeClass('error');
							save_propriete();
						}
					});				
				}else{
					_true = true;
				}
			}
		}
		
		if(_true){ save_propriete(); }
		
		function save_propriete(){
			var data = {
					't_n'				:	'Propriete',
					'columns'			:	columns
			};
			
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			
			$.post("pages/default/ajax/propriete/save.php",{'data':data},function(response){
				$(".modal").removeClass("show");
				if(response === "1"){
					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					$('.close_form').trigger('click');
					/*
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete.php",
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
				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}
			});				
		}

	});	
	
	$(document).on('change','#upload_file_propriete',function(){
		var id_client =  $(this).attr("data");
		
		var params = {
			IdIputFile			:	"upload_file_propriete",
			PHPUploader			:	"pages/default/ajax/upload_files.php",
			PHPUploaderParams	:	"?id=propriete/"+id_client
			
		};
		
		
		if($(this).val() !== ""){
			uploader(params);
		}
		
	});
		
	$(document).on('click', '.p_p_save', function(){

		var _status = $("#p_p_status").hasClass("on")? 1 : 0;
		var columns = {
			'p_p_montant*'		:	$("#p_p_montant").val(),
			'p_p_type*'			:	$("#p_p_type").val(),
			'periode_de*'		:	$("#periode_de").val(),
			'periode_a*'		:	$("#periode_a").val(),
			'p_p_id_propriete'	:	$(this).attr("data"),
			'_status'			:	_status
		};
		
		if($(this).hasClass("edit")){
			columns.id = $(this).val();
		}
		
		var _true = true	;

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
			//data['columns']['date_naissance*'] = 
			var data = {
				't_n'				:	'Propriete_Proprietaire_Location',
				'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_proprietaire_location/save.php",{'data':data},function(response){
				
				if (response === "1"){
					$(".refresh_location").trigger("click");
				}else{
					$(".debug_client").html(response);
				}

			});	
		}	
	
	});
	
	$(document).on("click", ".refresh_location", function(){
		var data = {
				't_n' 			: 	"Propriete_Proprietaire_Location",
				'id_propriete'	:	$(this).val()
				};

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$(".location_form_content").html("");

		$.post("pages/default/ajax/propriete_proprietaire_location/get.php",{'data':data},function(r){
			$(".location_form_content").html(r);
			$(".modal").removeClass("show");

		});
		
	});
	
	$(document).on('click', '.add_location', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			'module' 	 : 'location',
			'options'	 : {'id': 0, 'id_propriete':$(this).val()}
		};
		/*
		$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
			$(".debug_client").html(r);
		});
		*/

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/propriete_proprietaire_location/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});

		
	});
	
	$(document).on('click', '.edit_ligne_p_p_l', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var id_ligne = 0;
		if($(this).hasClass("btn")){
			id_ligne = $(this).val();
		}else{
			id_ligne = $(this).attr("data");
		}
		
		var data = {
			'module' 	: 	'location',
			'options'	:	{'id':id_ligne}
		};
		/*
		$.post("pages/default/ajax/propriete/util.php",{'module':'location'}, function(r){
			$(".debug_client").html(r);
		});
		*/

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/propriete_proprietaire_location/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				$(".debug_client").html("");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			$(".modal").html("").removeClass('show');
		});

		
	});
	
	$(document).on('click','.p_p_periode', function(){
		var d = new Date();
		if($(this).attr("data") === "1"){
			$("#p_p_periode_de").val( d.getFullYear() + "-01-01");
			$("#p_p_periode_a").val( d.getFullYear() + "-12-31");
		}else{
			$("#p_p_periode_de").val( d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() );
			$("#p_p_periode_a").val( d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() );
		}
		
	});
	
	$(document).on("keyup", "#_r",function(e) {
		if(e.keyCode === 13 ) {
			
			$("._s").trigger('click');
		}
	});
	
	$(document).on('click', '._s', function(){

		var data = {
			'module' 	: 	'proprietaire',
		};
		if($("#_r").val() !==""){
			data.request = $("#_r").val();
		}
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/propriete/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
		});
		
	});
	
	$(document).on('click', '.select_proprietaire', function(){

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		
		var data = {
			'module' : 'proprietaire',
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/propriete/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				
			}else{
				$(".modal").html("<div class='modal-content' style='width:350px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
		});
		
	});
	
	$(document).on('change', '#propriete_complexe', function(){
		var _this = $(this);
		var data = {
			'module' : 'complexe',
			'options': _this.val()
		};
		console.log(data);
		_this.prop('disabled', true).css('background-color', '#ededed');
		$(".is_doing").removeClass("hide");
		$("#propriete_code").prop('disabled', true).css('background-color', '#ededed');
		$("#propriete_ville").prop('disabled', true).css('background-color', '#ededed');
		$("#propriete_adresse").prop('disabled', true).css('background-color', '#ededed');
		$(".propriete_code_correct").addClass("hide");
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/propriete/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code == 1){
				$("#propriete_code").val(response.msg);
				$("#propriete_ville").val(response.ville);
				$("#propriete_adresse").val(response.adresse);
				
				_this.prop('disabled', false);
				$("#propriete_code").prop('disabled', false).css('background-color', '#fff');
				$("#propriete_ville").prop('disabled', false).css('background-color', '#fff');
				$("#propriete_adresse").prop('disabled', false).css('background-color', '#fff');
				$(".is_doing").addClass("hide");
				
			}else{
				$("#propriete_code").prop('disabled', false).css('background-color', '#fff');
				$("#propriete_ville").prop('disabled', false).css('background-color', '#fff');
				$("#propriete_adresse").prop('disabled', false).css('background-color', '#fff');
				$(".is_doing").addClass("hide");
				_this.prop('disabled', false);
			}
								
		}).fail(function(response, textStatus){
			$(".debug_client").html(textStatus);
			_this.prop('disabled', false);
			$("#propriete_code").prop('disabled', false).css('background-color', '#fff');
			$("#propriete_ville").prop('disabled', false).css('background-color', '#fff');
			$("#propriete_adresse").prop('disabled', false).css('background-color', '#fff');
			$(".is_doing").addClass("hide");
		});

		
	});

	$(document).on('click','.actions.propriete_options .save',function(){

		var columns = {
			'propriete_options*'	:	$("#propriete_options").val()

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Options',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_options/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_options.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		

	$(document).on('click','.actions.propriete_options .save_edit',function(){

		var columns = {
			'propriete_options*'	:	$("#propriete_options").val(),
			'id*'					:	$("#id").val(),

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Options',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_options/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_options.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	$(document).on('click','.actions.propriete_modalite_paiement .save',function(){

		var columns = {
			'propriete_modalite_paiement*'	:	$("#propriete_modalite_paiement").val()

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Modalite_Paiement',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_modalite_paiement/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_modalite_paiement.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		
	
	$(document).on('click','.actions.propriete_modalite_paiement .save_edit',function(){

		var columns = {
			'propriete_modalite_paiement*'	:	$("#propriete_modalite_paiement").val(),
			'id*'					:	$("#id").val(),

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Modalite_Paiement',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_modalite_paiement/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_modalite_paiement.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	
	/*******************************************
			PROPRIETE CATEGORY SECTION
	*******************************************/
	
	$(document).on('click','.actions.propriete_category .save',function(){
		var _status = $("#propriete_category_status").hasClass("on")? 1 : 0;
		var columns = {
			'propriete_category*'	:	$("#propriete_category").val(),
			'is_default'			:	_status

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Category',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_category/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_category.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		

	$(document).on('click','.actions.propriete_category .save_edit',function(){
		var _status = $("#propriete_category_status").hasClass("on")? 1 : 0;
		var columns = {
			'propriete_category*'	:	$("#propriete_category").val(),
			'id*'					:	$("#id").val(),
			'is_default'			:	_status

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Category',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_category/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_category.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	/*********************************************
			PROPRIETE STATUS SECTION
	**********************************************/
	
	$(document).on('click','.actions.propriete_status .save',function(){
		var _status = $("#propriete__status").hasClass("on")? 1 : 0;
		var columns = {
			'propriete_status*'	:	$("#propriete_status").val(),
			'is_default'			:	_status

		};
		
		if($("#id").length > 0){
			columns.id = $("#id").val();
		}
		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Status',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_status/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_status.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		
	
	
	/********************************************
			PROPRIETE TYPE SECTION
	*********************************************/

	$(document).on('click','.actions.propriete_type .save',function(){
		var _status = $("#propriete_type_status").hasClass("on")? 1 : 0;
		var columns = {
			'propriete_type*'	:	$("#propriete_type").val(),
			'is_default'			:	_status
		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Type',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_type/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_type.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});		

	$(document).on('click','.actions.propriete_type .save_edit',function(){
		var _status = $("#propriete_type_status").hasClass("on")? 1 : 0;
		var columns = {
			'propriete_type*'	:	$("#propriete_type").val(),
			'id*'					:	$("#id").val(),
			'is_default'			:	_status

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Propriete_Type',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/propriete_type/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/propriete_type.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	/*********************************************
			COMPLEXE SECTION
	**********************************************/
	
	$(document).on('click','.actions.complexe .save',function(){
		
		var selected = [];
		$('input:checked').each(function() {
			selected.push($(this).attr('value'));
		});

		var columns = {
			'complexe_name*'	:	$("#complexe_name").val(),
			'complexe_ABR*'		:	$("#complexe_ABR").val(),
			'complexe_type*'	:	$("#complexe_type").val(),
			'complexe_ville*'	:	$("#complexe_ville").val(),
			'complexe_adresse*'	:	$("#complexe_adresse").val(),
			'complexe_contact1'	:	$("#complexe_contact1").val(),
			'complexe_phone1'	:	$("#complexe_phone1").val(),
			'complexe_contact2'	:	$("#complexe_contact2").val(),
			'complexe_phone2'	:	$("#complexe_phone2").val(),
			'facilities'		:	selected

		};

		var _true = true	;

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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}

	});	
	
	$(document).on('click','.actions.complexe .save_edit',function(){

		//var menu_status = $("#menu_status").hasClass("on")? 1 : 0;
		var selected = [];
		$('input:checked').each(function() {
			selected.push($(this).attr('value'));
		});
		var columns = {
			'id*'					:	$("#id").val(),
			'complexe_name*'	:	$("#complexe_name").val(),
			'complexe_ABR*'		:	$("#complexe_ABR").val(),
			'complexe_type*'	:	$("#complexe_type").val(),
			'complexe_ville*'	:	$("#complexe_ville").val(),
			'complexe_adresse*'	:	$("#complexe_adresse").val(),
			'complexe_contact1'	:	$("#complexe_contact1").val(),
			'complexe_phone1'	:	$("#complexe_phone1").val(),
			'complexe_contact2'	:	$("#complexe_contact2").val(),
			'complexe_phone2'	:	$("#complexe_phone2").val(),
			'facilities'		:	selected

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	/*****************************************************************************************************
			COMPLEXE TYPE SECTION
	******************************************************************************************************/
	
	$(document).on('click','.actions.complexe_type .save',function(){

		var columns = {
			'complexe_type*'	:	$("#complexe_type").val()

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe_Type',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe_type/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe_type.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.complexe_type .save_edit',function(){

		var columns = {
			'complexe_type*'	:	$("#complexe_type").val(),
			'id*'					:	$("#id").val(),

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe_Type',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe_type/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe_type.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});
	
	/******************************
			FACILITIES SECTION
	*******************************/
	
	$(document).on('click','.actions.complexe_facilities .save',function(){

		var columns = {
			'complexe_facilities*'	:	$("#complexe_facilities").val()

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe_Facilities',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe_facilities/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe_facilities.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	

	$(document).on('click','.actions.complexe_facilities .save_edit',function(){

		var menu_status = $("#menu_status").hasClass("on")? 1 : 0;

		var columns = {
			'complexe_facilities*'	:	$("#complexe_facilities").val(),
			'id*'					:	$("#id").val(),

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === ""){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Complexe_Facilities',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/complexe_facilities/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/complexe_facilities.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	

	/******************************
			MENU SECTION
	*******************************/
	
	$(document).on('change','#menu_icon',function(){
		$(".icon_display").html($(this).val());
	});
	
	$(document).on('click', '.__menu .btn.order, .__sub .btn.order', function(){
		var data = {
			action 	: 	'',
			i		:	$(this).attr("data-id"),
			next	:	$(this).attr("data-id-n"),
			preview	:	$(this).attr("data-id-p"),
			order	:	$(this).attr("data-order")
		};
		
		if($(this).hasClass("up")){
			data.action = "UP";
		}else{
			data.action = "DOWN";
		}
		

		$.ajax({

			type		: 	"POST",
			url			: 	"pages/default/ajax/menu/util.php",
			data		:	data,
			success 	: 	function(response){
								//$('.debug_client').html(response);
								location.reload();
							},
			error		:	function(response){
								$('.debug_client').html(response);

			}
		});
		
	});
		
	$(document).on('click','.actions.menu .save',function(){

		var menu_status = $("#menu_status").hasClass("on")? 1 : 0;

		var columns = {
			'menu_libelle*'			:	$("#menu_libelle").val(),
			'menu_parent'			:	$("#menu_parent").val(),
			'menu_icon'				:	$("#menu_icon").val(),
			'menu_order*'			:	$("#menu_order").val(),
			'menu_url*'				:	$("#menu_url").val(),
			'menu_status*'			:	menu_status,

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1" ){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Menu',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/menu/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/menu.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	$(document).on('click','.actions.menu .save_edit',function(){

		var menu_status = $("#menu_status").hasClass("on")? 1 : 0;

		var columns = {
			'menu_libelle*'			:	$("#menu_libelle").val(),
			'menu_parent'			:	$("#menu_parent").val(),
			'menu_icon'				:	$("#menu_icon").val(),
			'menu_order*'			:	$("#menu_order").val(),
			'menu_url*'				:	$("#menu_url").val(),
			'id*'					:	$("#id").val(),
			'menu_status*'			:	menu_status,

		};

		var _true = true	;

		for (var key in columns) {
			if (columns.hasOwnProperty(key)) {

				if( columns[key] === "" || columns[key] === "-1" ){
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
			//data['columns']['date_naissance*'] = 
			var data = {

						't_n'				:	'Menu',
						'columns'			:	columns
			};
			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.post("pages/default/ajax/menu/save.php",{'data':data},function(response){

				$(".modal").removeClass("show");
				if(response === "1"){

					swal("SUCCESS!", "Le produit a été ajouté!", "success");
					var data = {
						"page"	:	"menu",
						"p"		:	{
							"s"		:	0,
							"pp"	:	50
						}
					};

					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/includes/menu.php",
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



				}else{
					$(".debug_client").html("Impossible d\'enregistrer le client : " + response);
				}


			});	
		}
	});	
	
	
	/******************************
			LIBRARY
	******************************/

	$(".show_library").on("click", function(){
		var data;
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		$.post("pages/default/includes/library.php",{'data':data},function(response){
			$(".modal").html("<div class='modal-content' style='width:450px; padding:0; border:0; border-radius:3px'>"+response+"</div>");
		});
		
	});
	
	$(document).on("click", ".show_files", function(){
		
		if($(this).hasClass("propriete")){
			var data = {
				id_produit	:	$(this).val()
			};
			$(".show_files_result").prepend("<div style='padding:10px; color:black;position:absolute; top:0; width:100%; background-color:yellow; opacity:0.5; text-align:center'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.ajax({

				type		: 	"POST",
				url			: 	"pages/default/ajax/propriete/get_files.php",
				data		:	data,
				success 	: 	function(response){

									$('.show_files_result').html(response);
								},
				error		:	function(response){
									$('.content').html(response);
									$(".modal").removeClass("show");

				}
			});
		}else if($(this).hasClass("contrat")){
			var data = {
				id_produit	:	$(this).val()
			};
			$(".show_files_result").prepend("<div style='padding:10px; color:black;position:absolute; top:0; width:100%; background-color:yellow; opacity:0.5; text-align:center'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.ajax({

				type		: 	"POST",
				url			: 	"pages/default/ajax/contrat/get_files.php",
				data		:	data,
				success 	: 	function(response){

									$('.show_files_result').html(response);
								},
				error		:	function(response){
									$('.content').html(response);
									$(".modal").removeClass("show");

				}
			});			
		}else if($(this).hasClass("person")){
			var data = {
				id_produit	:	$(this).val()
			};
			$(".person_image").prepend("<div class='_loader' style='padding:10px; color:black;position:absolute; top:0; width:100%; background-color:yellow; opacity:0.5; text-align:center'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			$.ajax({

				type		: 	"POST",
				url			: 	"pages/default/ajax/person/get_picture.php",
				data		:	data,
				success 	: 	function(response){
									$("._loader").remove();
									$('.person_image .image').html(response);
								},
				error		:	function(response){
									alert(response);
									$(".modal").removeClass("show");

				}
			});
		}


	});

	$(document).on("click", ".upload_btn", function(){
		
		//$("#upload_file").trigger("click");

	});
	
	$(document).on('change','#upload_file',function(){
		var id_client = $("#UID").val();
		
		var params = {
			IdIputFile			:	"upload_file",
			PHPUploader			:	"pages/default/ajax/upload_files.php",
			PHPUploaderParams	:	"?id="+id_client+"&name=some text here"
			
		};
		
		
		if($(this).val() !== ""){
			uploader(params);
		}
		
	});
	
	$(document).on('click', '.edit_file', function(){
		$(this).parent().parent().find('span').addClass('hide');
		
		var _this = $(this).parent().parent().find('.file_name_input');
		_this.removeClass('hide');
		_this.select();
	});
	
	$(document).on('keypress', '.file_name_input', function(e){
		if(e.which === 13) {
			
			var value = $(this).val().replace(" ", "_");
			value = value.replace(".", "_");
			value = value.replace(",", "_");
			value = value.replace("'", "_");
			var link = $(this).attr('data-link');
			
			if(link !== ""){
				$(this).parent().find('span').removeClass('hide').html(value);
				$(this).parent().find('.file_name_input').addClass('hide');

				$.post("pages/default/ajax/rename_file.php",{'link':link,'new_name':value}, function(r){
					if(r === "1"){
						$(".show_files").trigger('click');
					}else{
						alert(r);
					}

				});				
			}
			

			
			
		}
	});	
	
	$(document).on("click", ".delete_file", function(){
		
		
		var data = {
					link	:	$(this).val()
				};
		
		swal({
			  title: "Vous êtes sûr?",
			  text: "Êtes vous sûr de vouloir supprimer ce Fichier? ",
				type:"warning",
				showCancelButton:!0,
				confirmButtonColor:"#3085d6",
				cancelButtonColor:"#d33",
				confirmButtonText:"Oui, Supprimer!"
			}).then(function(t){
			  if (t.value) {

					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
				  
					$.ajax({

						type		: 	"POST",
						url			: 	"pages/default/ajax/delete_file.php",
						data		:	data,
						success 	: 	function(){
											//alert(response);
											$(".modal").removeClass("show");
											$(".show_files").trigger('click');
										},
						error		:	function(){
											$(".modal").removeClass("show");
											$(".show_files").trigger('click');

						}
					});	
			  }
		});	
	});
		
	$(document).on('click', '.download_file', function(){
		var data = {
					link	:	$(this).attr("data-link")
				};
		//$(".debug").html(data.link);
		
		jQuery('<form target="_blank" action="' + data.link + '" method="get"></form>').appendTo('body').submit().remove();
		
	});
		
	$(document).on('click', '.showImage', function(){
		var src = $(this).attr("src");		
		$(".modal").removeClass("hide").addClass("show").html("<div class='modal-content'><div class='_close' style='background-color:red; padding:5px 7px; color:white; cursor:pointer'>&times;</div><img src='"+src+"' style='width:100%; height:auto'></div>");
	});

	$(document).on('click', '._close',function(){
		$(".modal").removeClass("show");
	});

});
