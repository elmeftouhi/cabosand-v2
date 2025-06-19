// JavaScript Document
	

$(document).ready(function(){
	
	"use strict";

	/******************************
			LISTVIEW
	*******************************/
	
	$(document).on('click', '.styles .del', function(){
		if($(".listview_name.selected").length > 0){
						
			var module = $(".listview_module.selected").attr('data-module');
			var name = $(".listview_name.selected").attr('data-name');
			
			swal({
				  title: "Vous êtes sûr?",
				  text: "Êtes vous sûr de vouloir supprimer cette ligne? " + name,
					type:"warning",
					showCancelButton:!0,
					confirmButtonColor:"#3085d6",
					cancelButtonColor:"#d33",
					confirmButtonText:"Oui, Supprimer!"
				}).then(function(t){
				  if (t.value) {


					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
					var data = {
						'module'		: 'delete',
						'options'		:	{'module':module,'name':name}
					};

					var selected;
					$("a.listview_module").each(function(){
						if($(this).hasClass("selected")){
							selected = $(this);
						}
					});

					//$.post("pages/default/ajax/list/util.php", data, function(r){ selected.trigger('click'); $(".debug").html(r); });


					$.ajax({
						type		: 	"POST",
						url			: 	"pages/default/ajax/list/util.php",
						data		:	data,
						dataType	: 	"json",
					}).done(function(response){

						if (response.code === 1){
							$(".modal").html("");
							$(".modal").removeClass("show");
							selected.trigger('click');
						}else{
							$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
						}

					}).fail(function(response, textStatus){
						alert(textStatus);
						$(".debug").html(textStatus);
					});					


				  } else {

				  }
			});	
			
			
			
			
			

	
			
		}else{
			alert("not selected!");
		}

	});
	
	$(document).on('click', '.styles .edit', function(){
		
		if($(".listview_name.selected").length > 0){
			var module = $(".listview_module.selected").attr('data-module');
			var name = $(".listview_name.selected").attr('data-name');
			var is_default = $(".listview_name.selected .far").hasClass("fa-check-square")? 1 : 0;

			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			var data = {
				'module'	: 'edit',
				'options'		:	{'module':module,'name':name,'is_default':is_default}
			};
			console.log(data.options);
			//$.post("pages/default/ajax/depense/util.php", {'module':'propriete'}, function(r){$(".debug_client").html(r);});

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/list/util.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				if (response.code === 1){
					$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");

				}else{
					$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				}

			}).fail(function(response, textStatus){
				alert(textStatus);
				$(".debug").html(textStatus);
			});				
		}

	});
	
	$(document).on('click', '.edit_column', function(){
		var _data = {};
		var column = {};
		var i=0;
		$(".column").each(function(){
			column = {};
			column.display = ($(this).find(".display").is(":checked"))? 1: 0;
			column.column = $(this).find("._column").val();
			column.label = $(this).find("._label").val();
			column.style = $(this).find("._style").val();
			column.format = $(this).find("._format").val();

			_data[i] = column;
			i++;
		});

		var module = $(".listview_module.selected").attr('data-module');
		var name = $(".listview_name.selected").attr('data-name');

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			'module'	: 'edit_column',
			'options'		:	{'columns':_data,'module':module,'name':name}
		};

		//$.post("pages/default/ajax/list/util.php", data, function(r){$(".debug").html(r);});
		
		var selected;
		$("a.listview_module").each(function(){
			if($(this).hasClass("selected")){
				selected = $(this);
			}
		});
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/list/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			if (response.code === 1){
				$(".modal").html("");
				$(".modal").removeClass("show");
				selected.trigger('click');
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
		}).fail(function(response, textStatus){
			alert(textStatus);
			$(".debug").html(textStatus);
		});			
		
		
	});
	
	$(document).on('click', '.column_delete', function(){
		$(this).parents("li:first").remove();
	});
	
	$(document).on('click', '.column_add', function(){
		var returned = "<li>";
		var formats = ["date", "money", "on_off_default", "on_off"];
		var format = "<select class='_format' style='padding:3px 3px'>";
		format += "	<option value=''></option>";
		for(var i=0; i<formats.length;i++){
			format += "	<option value='"+formats[i]+"'>"+formats[i]+"</option>";
		}	
		format += "</select>";
		
		returned += "	<div class='row column' style='padding:0'>";
		returned += "		<div class='col_1-inline' style='padding:0'>";
		returned += "			<input class='display' type='checkbox'>";
		returned += "		</div>";

		returned += "		<div class='col_1-inline' style='padding:0'><input class='_column' type='text' value=''></div>";
		returned += "		<div class='col_2-inline' style='padding:0'><input class='_label' type='text' value=''></div>";				
		returned += "		<div class='col_5-inline' style='padding:0'><input class='_style' type='text' value=''></div>";
		returned += "		<div class='col_1-inline' style='padding:0'>"+format+"</div>";
		returned += "		<div class='col_2-inline' style='padding:0'>";
		returned += "			<div class='btn-group'>";
		returned += "				<button class='btn btn-red column_delete'><i class='fas fa-minus-circle'></i></button>";
		returned += "				<button class='btn btn-default column_up'><i class='fas fa-chevron-up'></i></button>";
		returned += "				<button class='btn btn-default column_down'><i class='fas fa-chevron-down'></i></button>";				
		returned += "			</div>";
		returned += "		</div>";
		returned += "	</div>";
		returned += "	</li>";
		
		$("ul.definitions").append(returned);
	});
	
	$(document).on('click', '.column_up,.column_down', function(){
        var row = $(this).parents().parents().parents("li:first");
        if ($(this).is(".column_up")) {
			if(!row.prev().hasClass('columns_name')){
				row.insertBefore(row.prev());
			}
        } else {
            row.insertAfter(row.next());
        }
    });
	
	$(document).on('click', '#columns_check_all', function(){
		if($(this).is(':checked')){
			$(".column .display").attr('checked','checked');
		}else{
			$(".column .display").removeAttr('checked');
		}
	});
	
	$(document).on('click', '.styles .add', function(){
		
		var module = $(".listview_module.selected").attr('data-module');

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			'module'	: 'add',
			'options'		:	{'module':module}
		};

		//$.post("pages/default/ajax/depense/util.php", {'module':'propriete'}, function(r){$(".debug_client").html(r);});

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/list/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			console.log(response)
			$('body').modal();
			$('#modal').html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			/*
			if (response.code == 1){
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");

			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}
			*/

		}).fail(function(response, textStatus){
			alert(textStatus);
			$(".debug").html(textStatus);
		});			

		
	});
	
	$(document).on('click', '.listview_save_', function(){

		var module = $(this).attr('data-module');
		var is_default = 1;
		var list_name = $("input[name='list']:checked").val();

		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			'module'	: 'save_edit',
			'options'		:	{
				'module'	:	module,
				'data'		:	{
					'is_default'		:	is_default,
					'name'				:	list_name,
					'name_temp'			:	list_name
								}
			}
		};


		//$.post("pages/default/ajax/list/util.php", data, function(r){ $(".debug").html(r); });

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/list/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){

			if (response.code === 1){
				$(".actions .refresh").trigger('click');
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}

		}).fail(function(response, textStatus){
			alert(textStatus);
			$(".debug").html(textStatus);
		});		
		
	});
	
	$(document).on('click', '.listview_save', function(){

		if($("#list_name").val() === ""){
			$("#list_name").addClass("error");
		}else{
			
			$("#list_name").removeClass("error");
			var module = $(".listview_module.selected").attr('data-module');
			var is_default = $("#is_default").hasClass("on")? 1 : 0;
			var list_name = $("#list_name").val();

			$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
			var data = {
				'module'	: 'save',
				'options'		:	{
					'module'	:	module,
					'data'		:	{
						'is_default'		:	is_default,
						'name'				:	list_name
									}
				}
			};
			
			if($(this).hasClass("edit")){
				data.module="save_edit";
				data.options.data.name_temp =  $(this).attr("data-name");
			}
			
			console.log(data);
			
			var selected;
			$("a.listview_module").each(function(){
				if($(this).hasClass("selected")){
					selected = $(this);
				}
			});
			
			//$.post("pages/default/ajax/list/util.php", data, function(r){ $(".debug").html(r); });

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/list/util.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				
				if (response.code === 1){
					$(".modal").html("");
					$(".modal").removeClass("show");
					selected.trigger('click');
				}else{
					$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
				}
				
			}).fail(function(response, textStatus){
				alert(textStatus);
				$(".debug").html(textStatus);
			});	

		}
		
				
		
	});
	
	$(document).on("click", "a.listview_module", function(){

		var module = $(this).attr("data-module");
		$(".listview .modules ul li a").removeClass('selected');
		$(this).addClass("selected");
		var data = {
				'module'	:	module
		};
		
		$.post("pages/default/ajax/list/get.php",data,function(r){
			$(".styles").html(r);
			$(".definitions").html("");
			$(".debug").html("");
		});
		
	});
	
	$(document).on("click", ".module_refresh", function(){ 
		$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
		var data = {
			'module'		: 'get_module'
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/list/util.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){

			if (response.code === 1){
				$(".modules ul.unstyle").html(response.msg);
				$(".modal").removeClass("show");
				$(".styles").html('<h3 style="margin-left: 7px">Styles</h3>');
				$(".definitions").html('<h3 style="margin-left: 7px">Définitions</h3>');
			}else{
				$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
			}

		}).fail(function(response, textStatus){
			alert(textStatus);
			$(".debug").html(textStatus);
		});	
	});
	
	$(document).on("click", ".module_del", function(){ 
		if($(".listview_module.selected").length > 0){
						
			var module = $(".listview_module.selected").attr('data-module');
			
			swal({
				  title: "Vous êtes sûr?",
				  text: "Êtes vous sûr de vouloir supprimer cette ligne? " + name,
					type:"warning",
					showCancelButton:!0,
					confirmButtonColor:"#3085d6",
					cancelButtonColor:"#d33",
					confirmButtonText:"Oui, Supprimer!"
				}).then(function(t){
				  if (t.value) {


					$(".modal").addClass("show").html("<div class='modal-content' style='width:75px; opacity:0.9'><i style='font-size:30px;' class='fas fa-cog fa-spin'></i></div>");
					var data = {
						'module'		: 'del_module',
						'options'		:	{'module':module}
					};

					//$.post("pages/default/ajax/list/util.php", data, function(r){ $(".debug").html(r); });
					  
					
					$.ajax({
						type		: 	"POST",
						url			: 	"pages/default/ajax/list/util.php",
						data		:	data,
						dataType	: 	"json",
					}).done(function(response){

						if (response.code === 1){
							$(".modal").html("");
							$(".modal").removeClass("show");
							$(".module_refresh").trigger('click');
							$(".styles").html('<h3 style="margin-left: 7px">Styles</h3>');
							$(".definitions").html('<h3 style="margin-left: 7px">Définitions</h3>');
							
						}else{
							$(".modal").html("<div class='modal-content' style='width:420px; padding:0; border:0; border-radius:3px'>" + response.msg + "</div>");
						}

					}).fail(function(response, textStatus){
						alert(textStatus);
						$(".debug").html(textStatus);
					});					
					
					

				  }
			});	
			
			
			
			
			

	
			
		}else{
			alert("not selected!");
		}
	});
	
	$(document).on("click", "a.listview_name", function(){
		$(".listview .listview_name").removeClass('selected');
		$(this).addClass("selected");
		
		var name = $(this).attr("data-name");
		var module = $(this).attr("data-module");
		
		var data = {
				'module'	:	module,
				'name'		:	name
		};
		
		$.post("pages/default/ajax/list/get.php",data,function(r){
			$(".definitions").html(r);
			$(".debug").html("");
		});
		
	});	

});