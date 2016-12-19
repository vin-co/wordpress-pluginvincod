jQuery(document).ready(function($) {


	/*
	 * This is to check the API connection (with keys, id, and check the server itself)
	 * @author Ges Jeremie
	 */

	var api_connection_check = {

		"console_content": [],

		"run": function(e) {

			// Clean console
			api_connection_check.reset_console();

			// First we disable the button to avoid doublons
			$("#api_connection_check").attr("disabled", "disabled");

			// Push start message
			api_connection_check.set_console(window.vincod_plugin_app.api_connexion_try);

			// Display console
			api_connection_check.view_console();

			// Get form datas
			var customer_id = $('input[name=vincod_setting_customer_id]').val();
			var customer_api = $('input[name=vincod_setting_customer_api]').val();

			// Little check
			if(customer_id != '' && customer_api != '') {

				// Url test api
				var api = window.vincod_plugin_app.api + '?api=' + customer_api + '&id=' + customer_id;
				;

				$.ajax({
					url:      api,
					success:  function(output) {

						console.log(output);

						// Check if error
						if("error" in output.owners) {

							api_connection_check.set_console('<i class="ion-alert-circled"></i> ' + window.vincod_plugin_app.api_connexion_error);

						}
						else {

							if(output.owners.checkApi.code == 1) {

								api_connection_check.set_console('<i class="ion-checkmark-circled"></i> ' + window.vincod_plugin_app.api_connexion_success);
								api_connection_check.set_console('<i class="ion-information-circled"></i> ' + window.vincod_plugin_app.api_connexion_save);

							}
							else {

								api_connection_check.set_console('<i class="ion-alert-circled"></i> ' + window.vincod_plugin_app.api_connexion_error);

							}

						}

						api_connection_check.view_console();

						// Activate disabled button
						$("#api_connection_check").removeAttr('disabled');

					},
					type:     'GET',
					dataType: 'json'
				});

			}
			else {

				api_connection_check.set_console('<i class="ion-alert-circled"></i> ' + window.vincod_plugin_app.api_connexion_missing);

				// Activate disabled button
				$("#api_connection_check").removeAttr('disabled');

			}

			api_connection_check.view_console();

			// Nothing yet

			// At the end, we cancel the form submit
			e.preventDefault();

		},

		"set_console": function(msg) {

			// Push message in the array
			api_connection_check.console_content.push(msg);

		},

		"view_console": function() {

			// Start block
			var output = '<div class="devlog_content">';

			// Add console content
			var content = api_connection_check.console_content;
			var count_content = content.length;

			for(var i = 0; i < count_content; i++) {

				output += '<div class="time"></div><div class="msg">';
				output += content[i];
				output += '</div>';

			}

			// End block
			output += '</div></div>';

			// Display
			$("#api_connection_check_console div").html(output);

		},

		"reset_console": function() {

			// Clean HTML
			$("#api_connection_check_console div").empty();

			// Clean Content
			api_connection_check.console_content = [];

		}

	};

	// Trigger
	$('#api_connection_check').click(function(e) {

		api_connection_check.run(e);

	});

});
