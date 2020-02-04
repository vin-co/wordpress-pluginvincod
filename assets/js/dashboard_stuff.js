jQuery(document).ready(function($) {

	/*
	 * This is to make a favorite button to activate when the user press enter
	 * @author Schaffner Laurent
	 */
	$("#settings input").bind("keydown", function(event) {

		var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));

		if(keycode === 13) { // Key ENTER

			document.getElementById('validate_settings').click();

			return false;

		}
		else {

			return true;

		}

	});

	$('#first-time-visit').on('closed.bs.alert', function () {
		wpCookies.setHash( 'wp_vincod_first_visit', 'true' );
	});

});
