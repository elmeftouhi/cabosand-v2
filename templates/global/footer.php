<script src="<?= HTTP.HOST ?>templates/global/js/api/jquery-3.5.0.min.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/api/jquery.table2excel.min.js"></script>

<script src="<?= HTTP.HOST ?>templates/global/js/api/Chart.min.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/api/sweetalert2.min.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/api/moment.min.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/api/moment.min.fr.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/api/Yjs.js"></script>
<script src="<?= HTTP.HOST ?>templates/global/js/app.js"></script>

<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/load.js?version=<?= time() ?>"></script>
<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/app.js?version=<?= time() ?>"></script>
<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/list.js?version=<?= time() ?>"></script>
<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/manager.js?version=<?= time() ?>"></script>

<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/support.js?version=<?= time() ?>"></script>
<script src="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/js/calendar.js?version=<?= time() ?>"></script>
<!--
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
-->

<script>
    $(document).ready(function(){
        /*
        var timer = setInterval(() => {
            var controler = "Helpers.Session";
            var data = {
                'controler'		:	controler,
                'function'		:	'expired'
            };
            
            $.ajax({
                type		: 	"POST",
                url			: 	"pages/default/ajax/ajax.php",
                data		:	data,
                dataType	: 	"json",
            }).done(function(response){
                if(response.msg == 'Error'){
                    location.reload();
                }
            }).fail(function(xhr) {
                alert("Error");
                console.log(xhr.responseText);
                $("#preloader").remove();
            });           
        }, 1000);
        */
    });
</script>

<script>
	$(document).ready(function(){
		$('.page_search_button').trigger('click');

		$(document).on('click', '.tr-highlight', function(){
			$('tr').removeClass('border-l-8 border-red-500')
			$(this).addClass('border-l-8 border-red-500')
		})

		$(document).on('click', '.close_right-container_2', function(){
			$('.right-container_2').toggleClass('hidden')
		})

		$(document).on('click', '.show_right-container_2', function(){
			$('.right-container_2_ID').html($(this).data('id'))
			$('.code_here').html($(this).html())
			$('.right-container_2').removeClass('hidden')
			$('.show-tab.bg-green-600').trigger('click')
		})
		
		$(document).on('click','.show-tab', function(){
			$('.show-tab').removeClass('bg-green-600 text-white')
			$(this).addClass('bg-green-600 text-white')
			var tab = $(this).data('tab');

			var ID = $('.right-container_2_ID').html();

			$('.tab-container').html('Loading');
			if(tab == 'form'){
				$('.tab-container').html('Loading');
				loadPropriete(ID)
			}else if(tab == 'depense'){
				$('.tab-container').html('Loading');
				loadDepense(ID)
			}else if(tab == 'contrat'){
				$('.tab-container').html('Loading');
				loadContrat(ID)
			}else if(tab == 'location'){
				$('.tab-container').html('Loading');
				loadLocation(ID)
			}else{
				$('.tab-container').html('');
			}
		})

		/** Supprimer periode from location */
		$(document).on('click', '.supprimer_location', function(){
			var id_location = $(this).data('id_location');

			var _next = confirm('Etes vous sure de vouloir supprimer?');
			if(_next){
				var data = {
					'controler'		:	'Propriete_Location',
					'function'		:	'Remove',
					'params'		:	{
						'id'	:	id_location
					}
				};
				
				$.ajax({
					type		: 	"POST",
					url			: 	"pages/default/ajax/ajax.php",
					data		:	data,
					dataType	: 	"json",
				}).done(function(response){
					$('.show-tab.bg-green-600').trigger('click')
				}).fail(function(xhr) {
					console.log(xhr.responseText);
				});					
			}

		})
		
	});

	// Load Propriete Form

	function loadPropriete(id){
		var data = {
			'controler'		:	'Propriete',
			'function'		:	'Edit',
			'params'		:	{
				'id'	:	id
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.tab-container').html(response.msg);
		}).fail(function(xhr) {
			console.log(xhr.responseText);
		});
	}

	// Load Depense
	function loadDepense(id_propriete){
		var data = {
			'controler'		:	'Depense',
			'function'		:	'ByPropriete',
			'params'		:	{
				'id_propriete'	:	id_propriete
			}
		};

		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.tab-container').html(response.msg);
		}).fail(function(xhr) {
			console.log(xhr.responseText);
		});
	}

	

	// Load Contrats envers Proprtitaire
	function loadContrat(id){
		var year = $("#Années").val();
		var data = {
			'controler'		:	'Propriete_Proprietaire_Location',
			'function'		:	'ByPropriete',
			'params'		:	{
				'id_propriete'		:	id,
				'year'				:	 year
			}
		};
		console.log(data);
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.tab-container').html(response.msg);
		}).fail(function(xhr) {
			console.log(xhr.responseText);
		});
	}

	// Load Contrats envers Proprtitaire
	function loadLocation(id){
		var data = {
			'controler'		:	'Propriete_Location',
			'function'		:	'ByPropriete',
			'params'		:	{
				'id_propriete'		:	id,
				'year'				:	$("#Années").val()
			}
		};
		
		$.ajax({
			type		: 	"POST",
			url			: 	"pages/default/ajax/ajax.php",
			data		:	data,
			dataType	: 	"json",
		}).done(function(response){
			$('.tab-container').html(response.msg);
		}).fail(function(xhr) {
			console.log(xhr.responseText);
		});
	}

</script>

</body>
</html>