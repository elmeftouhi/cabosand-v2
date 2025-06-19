<div class="w-full h-full relative">    
    <div class="flex items-center justify-between">
        <div class="text-2xl flex-1">Dépenses d'appartement</div>

        <select data-id_propriete="<?= $id_propriete ?>" id="depense_year" class="w-24 rounded">
            <option value="-1">-- Année</option>
            <?php 
                $start_year = 2019;
                $this_year = intval( date('Y') );
                for($start_year = 2019; $start_year<=$this_year; $start_year++){
                    if($start_year == $this_year)
                        echo '<option selected value="'.$start_year.'">'.$start_year.'</option>';
                    else
                        echo '<option value="'.$start_year.'">'.$start_year.'</option>';
                }
            ?>            
        </select>

    </div>
    <div class="depense_body">
        <div class="flex gap-4 justify-center border border-green-100 bg-green-50 py-4 p2 my-2">
            <div class="font-bold">
                Total : 
            </div>
            <div class="font-bold">
                <?= $Obj->format($total) ?>
            </div>
        </div>

        <div class="w-full my-4 shadow">
            <div class="border border-gray-300 bg-gray-200 h-10 px-2 flex items-center rounded-t-md">
                <div class="text-gray-700 font-bold w-32"> Date </div>
                <div class="text-gray-700 font-bold flex-1"> Designation </div>
                <div class="text-gray-700 font-bold w-24 text-right"> Montant </div>
            </div>
            <?php foreach($depenses as $exp){ ?>
                <div class="border border-t-0 border-gray-300 hover:bg-gray-50 h-10 px-2 flex items-center">
                    <div class="font-light w-32"> <?= $exp["created"] ?> </div>
                    <div class="font-light flex-1"> <?= $exp["libelle"] ?> </div>
                    <div class="font-bold text-yellow-600 w-24 text-right"> <?= $Obj->format($exp["montant"]) ?></div>
                </div>
            <?php } ?>
            <?php if(count($depenses) == 0){ ?>
                <div class="border border-t-0 border-gray-300 hover:bg-gray-50 flex items-center">
                    <div class="font-light w-full py-4 bg-blue-50 text-center"> Aucune dépense pour <b><?= $this_year ?></b> </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#depense_year').on('change', function(){
            var year = $(this).val();
            var id_propriete = $(this).data('id_propriete');

			var data = {
				'controler'		:	'Depense',
				'function'		:	'ByProprieteByYear',
				'params'		:	{
					'id_propriete'		:	id_propriete,
					'year'			    :	year
				}
			};
            $('.depense_body').preloader()

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
                $('.depense_body').html(response.msg)
                $("#preloader").remove();
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});
        })
    })
</script>