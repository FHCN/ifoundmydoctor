function practice_on_delete_alert() {
	return confirm("Are you sure you want to delete this practice? \nThis will also delete the associated doctors, locations and articles.");
}

jQuery(function($) {

	$(document).on('ready', () => {
		$("#contextual-help-link").click(function () {
			 $("#contextual-help-wrap").css("cssText", "display: block !important;");
		});
		$("#show-settings-link").click(function () {
			 $("#screen-options-wrap").css("cssText", "display: block !important;");
		});
	});

	$('body.admin-edit-single-practice #delete-action a.submitdelete.deletion').on('click', function() {
		var confirm = practice_on_delete_alert();
		if (confirm) {
			return true;
		}

		setTimeout(function() {
			$('#wpcontent .ajax-loading').css('visibility', 'hidden');
			$('#publishing-action #publish').removeClass('button-primary-disabled');
		}, 200);
		return false;
	})

});
