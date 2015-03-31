$(document).ready(function(){

	$('#season_choice').change(function(){
		// $('select option:')

		var selectvalue = $(this).val();
		var pseudo = $('h1').html();
		$('#game-stats').html('');

		$.ajax({url: 'choiceSeason.php?pseudo='+pseudo+'&season_choice='+selectvalue,
	    	success: function(output) {
	        $('#game-stats').html(output);
	    },
		 	error: function (xhr, ajaxOptions, thrownError) {
		    alert(xhr.status + " "+ thrownError);
		}});
	});

	
});
