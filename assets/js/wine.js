jQuery(document).ready(function ($) {


   /*
    * Add many things into the wine.php template
    * @author Ges Jeremie
    */
    var wine = {

    	"current_block": '',

    	"init": function() {

    		// Here you can check what do you want before script run
    		wine.run();

    		

    	},

    	"run": function() {

    		wine.year_run();
    		wine.blocks_run();

    	},

    	"year_run": function() {


    		$('.plugin-vincod select[name=years]').change(function() {

    			var url = $('.plugin-vincod select[name=years] option:selected').val();

    			wine.redirect(url);

    		});

    	},

    	"blocks_run": function() {

    		// Hide all
    		$('.plugin-vincod div[data-blocks]').hide();

    		// Stock data-block first block and display
    		wine.current_block = $('.plugin-vincod div[data-blocks]:first').data('blocks');
    		$('.plugin-vincod div[data-blocks]:first').show();


    		$('.plugin-vincod a[id^="trigger"]').click(function(e) {

				e.preventDefault();
								    			
    			// Get the id
    			var id = $(this).attr('id').split('trigger-').join('');
    			
    			// Check if you must display
    			if (wine.current_block != id) {

    				// Hide current block
    				$('.plugin-vincod div[data-blocks=' + wine.current_block + ']').hide();

    				// Show new block
    				$('.plugin-vincod div[data-blocks=' + id + ']').show();

    				// Stock new id 
    				wine.current_block = id;

    			}

    			return false;

    		});


    	},

    	"redirect": function(url) {

    		window.location = url;

    	}


    }

    // Run !
    wine.init();


});