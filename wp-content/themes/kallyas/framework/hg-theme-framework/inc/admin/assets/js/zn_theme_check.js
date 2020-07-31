jQuery(function($){
	"use_strict";

	var ZnThemeCheck = {
		init: function(){
			this.__initServerCheck();
			this.__initTooltips();
		},
		__initServerCheck: function(){
			var checkButton = $('.zn-action-input-custom');
			if(typeof(checkButton) != 'undefined'){
				checkButton.on('click', function(e){
					e.stopPropagation();
					e.preventDefault();

					var configData = {
						'action': 'zn_server_check',
						'zn_nonce': ZnAjaxThemeCheck.security
					};

					var icon = $('.js-zn-server-status-icon'),
						updateIcon = (typeof(icon) != 'undefined');

					$.ajax({
						url: ZnAjaxThemeCheck.ajaxurl,
						method: 'POST',
						cache: false,
						timeout: 30000,
						data: configData
					}).done(function(response){
						if(response){
							if(response.success){
								if(updateIcon){
									icon.removeClass('dashicons-warning').addClass('dashicons-yes');
								}

								new $.ZnModalMessage('Success! Your sever could successfully communicate with our server.');
							}
							else {
								icon.removeClass('dashicons-yes,dashicons-no').addClass('dashicons-warning');

								var params = {};
								params.modal_content = '<div class="zn_modal_confirm zn_modal_message">' + '<p>' + response.data + '</p>' + '</div>';
								params.show_resize = false;

								var modal = new $.ZnModal(params);
							}
						}
						else {
							new $.ZnModalMessage('An error occurred. Please try again in a few moments');
						}
					}).fail(function(e){
						new $.ZnModalMessage('An error occurred. Please try again in a few moments');
					});
				});
			}
		},
		__initTooltips : function(){
			$( '.zn-server-status-column-icon' ).tooltip({
				position : { my: 'center bottom', at: 'center top-10' }
			});
		}
	};
	ZnThemeCheck.init();
});
