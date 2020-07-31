(function ($) {

	/* Add a new element for theme options */
	$(document).on('click', '#zn_enable_pb', function() {

		// GET THE ELEMENT TO BE ADDED
		var el = $(this),
			edit_btn = $('#zn_edit_page'),
			spinner = $('.zn_pb_buttons .spinner'),
			data = {
				action: 'zn_editor_enabler',
				post_id: $(this).data('postid'),
				security: ZnAjax.security
			};

			spinner.addClass('is-active');

			// SAVE THE VALUE TO A CUSTOM FIELD SO IT CAN BE SAVED UPON POST/PAGE SAVE BUTTON PRESS
			$.post( ajaxurl, data, function(response) {

				if (response.success) {
					if ( response.data.status == 'disabled'){
						$('.zn_bt_text',el).text(el.data('inactive-text'));
						edit_btn.hide();
					}
					else {
						$('.zn_bt_text',el).text(el.data('active-text'));
						edit_btn.show();
					}

				}
				else{
					if( response.data.message.length > 0 ){
						alert( response.data.message );
					}
					else{
						alert('We received an improper message from the server!');
					}

				}

				spinner.removeClass('is-active');

			},'json');

	return false;

	});

})(jQuery);
