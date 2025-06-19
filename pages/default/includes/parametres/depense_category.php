<div class="w-full h-full px-2 relative">    
    <div class="hidden form absolute top-0 right-0 left-0 bottom-0 bg-gray-500 bg-opacity-30">
        <div class="border bg-gray-100 rounded-lg shadow p-4 mt-4 w-3/5 mx-auto">
            <div class="text-md font-bold">
                Ajouter / Modifier Catégorie dépense
            </div>
            <div class="my-4">
                <div class="text-xs mb-2">
                    Catégorie :
                </div>
                <input type="text" name="" value="fff" id="depense_category" class="py-2 px-3 border border-gray-200 rounded">
                <input type="hidden" name="" id="id" value="0">
            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="is_default" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="is_default" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Définir valeur par défaut
                </label>
            </div>
            <div class="pt-4 border-t border-gray-400 flex gap-4">
                <button class="btn btn-green enregistrer">
                    <i class="fas fa-floppy-disk"></i> 
                    Enregistrer
                </button>
                <button class="btn annuller">
                    Annuler
                </button>
            </div>
        </div>        
    </div>



    <div class="flex items-center justify-between">
        <div class="text-2xl">Catégorie ddépenses</div>
        <div class="ajouter h-8 w-8 bg-green-500 rounded text-white text-lg justify-center flex items-center cursor-pointer hover:bg-green-600 focus:bg-red-700">
            <i class="fas fa-plus"></i>
        </div>
    </div>

    <div class="w-full my-4 shadow">
        <div class="border border-gray-300 bg-gray-200 h-10 px-2 flex items-center rounded-t-md">
            <div class="text-gray-700 font-bold w-20"> #ID </div>
            <div class="text-gray-700 font-bold flex-1"> Catégorie Dépense </div>
            <div class="text-gray-700 font-bold w-32 text-right"> Total </div>
            <div class="text-gray-700 font-bold w-32 text-center"> Par Défaut </div>
            <div class="text-gray-700 font-bold w-32"> </div>
        </div>
        <?php foreach($categories as $category){ ?>
        <div class="border border-t-0 border-gray-300 hover:bg-gray-50 h-10 px-2 flex items-center">
            <div class="font-light w-20"> <?= $category["id"] ?> </div>
            <div class="font-light flex-1"> <?= trim($category["depense_category"]) ?> </div>
            <div class="font-bold text-green-600 w-32 text-right"> <?= $Obj->format($category["total"]) ?></div>
            <div class="font-light w-32 text-center"> <?= $category["is_default"]? "<span class='bg-green-200 rounded-lg py-1 px-4 border'><i class='fa-solid fa-circle-check'></i></span>": "" ?> </div>
            <div class="font-light w-32 flex justify-between"> 
                <div data-id="<?= $category["id"] ?>" data-depense_category="<?= trim($category["depense_category"]) ?>" data-is_default="<?= $category["is_default"] ?>" class="modifier border rounded py-1 px-2 bg-gray-400 text-gray-900 rounded cursor-pointer hover:bg-gray-600 hover:text-white">
                    Modifier
                </div>
                <?php if($category["total"] == 0){ ?>
                <div data-id="<?= $category["id"] ?>"  class="supprimer rounded py-1 px-2 text-red-600 text-white rounded cursor-pointer hover:bg-red-600 hover:text-white flex items-center">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function(){

        /** Add New */
        $('.ajouter').on('click', function(){
            $("#id").val("")
            $("#depense_category").val("")
            $('#is_default').prop('checked', false)
            $('.form').removeClass('hidden')
            $("#depense_category").focus()
        })

        /** Edit */
        $('.modifier').on('click', function(){
            var depense_category = $(this).data('depense_category')
            var id = $(this).data('id')
            var is_default = $(this).data('is_default')
            $("#id").val(id)
            $("#depense_category").val(depense_category)
            if(is_default){
                $('#is_default').prop('checked', true)
            }else{
                $('#is_default').prop('checked', false)
            }
            $('.form').removeClass('hidden')
            $("#depense_category").focus()
        })

        /** Save */
        $('.enregistrer').on('click', function(){
			var depense_category = $('#depense_category').val();
			var is_default = $('#is_default').prop("checked");

			var data = {
				'controler'		:	'Depense_Category',
				'function'		:	'Store',
				'params'		:	{
					'depense_category'		    :	depense_category,
					'is_default'			    :	is_default
				}
			};

            if($('#id').val() != 0){
                data.params.id = $('#id').val()
            }

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
                $('.parametre_menu').find('.selected').trigger('click')
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});

        });

        /** Delete */
        $('.supprimer').on('click', function(){
            var id = $(this).data("id");
            if(confirm("Etes vous sur de vouloir supprimer? #ID = " + id)){
                
                var data = {
                    'controler'		:	'Depense_Category',
                    'function'		:	'Remove',
                    'params'		:	{
                        'id'			:	id
                    }
                };

                $.ajax({
                    type		: 	"POST",
                    url			: 	"pages/default/ajax/ajax.php",
                    data		:	data,
                    dataType	: 	"json",
                }).done(function(response){
                    $('.parametre_menu').find('.selected').trigger('click')
                }).fail(function(xhr) {
                    console.log(xhr.responseText);
                });
            }
        })

        /** Abort operation */
        $('.annuller').on('click', function(){
            $('.form').addClass('hidden')
        })
    })
</script>