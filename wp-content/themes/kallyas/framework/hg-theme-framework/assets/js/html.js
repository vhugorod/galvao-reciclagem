(function($) {
	"use strict";

	$.ZnHgTFW_Html = {};
	$.ZnHgTFW_Html.init = function(){
		$.ZnHgTFW_Html.theme_options_export();
		ZnHgFw.media.registerMediaType( 'media_field_import', $.ZnHgTFW_Html.media_field_import );
	};

	/**
	 * @kos
	 * @TODO: TO BE IMPLEMENTED
	 *
	 *
	 * @type {{init: Function, get_media: Function, get_data: Function}}
	 */
	$.ZnHgTFW_Html.media_field_import =
	{
		init : function( media_data, value_holder ){
			this.media_data = media_data;
			this.saved_values = value_holder.val();

			return this.get_media();
		},
		/**
		 *	This function will build the wp.media object for video option type
		 *
		 *	@returns : object
		 */
		get_media : function(){

			// Prepare the default arguments for wp.media
			var args = {
				title:   this.media_data.title,
				library: { type: this.media_data.type },
				button:  { text: this.media_data.insert_title },
				className: this.media_data['class'],
				state : this.media_data.state,
				frame : this.media_data.frame
			};

			// Create the frame
			return wp.media( args );

		},
		/**
		 *	This function is used to display the popup window that will allow users to select the export archive and
		 *	then import the theme options
		 *
		 *	@returns : object
		*/
		get_data : function( selection ){
			var media_object = this,
				values = selection.map( function( attachment ){
					attachment = attachment.toJSON();
					if ( media_object.media_data.value_type == 'url' ){
						var message_container = $('.zn_theme_options_import_msg_container');
						if(message_container && attachment['url'])
						{
							var btn = message_container.next('#zn_theme_import_button');

							btn.addClass('zn-button--loading');

							$.ajax({
								url: ajaxurl,
								type: 'POST',
								cache: false,
								async: true,
								data : {
									'attachment': attachment,
									action: 'zn_theme_options_import',
									zn_ajax_nonce: ZnAjax.security
								}
							}).done(function(response){
								btn.removeClass('zn-button--loading');
								setTimeout(function(){
									message_container.append(
										'<div class="alert alert-success">'+response.data+'</div>'
									).show();
								}, 3000);
							}).fail(function(a,b){
								btn.removeClass('zn-button--loading');
								setTimeout(function(){
									message_container.append(
										'<div class="alert alert-danger">Something went wrong... please try again in a few moments</div>'
									).show();
								}, 3000);
								console.error('Error: ',b);
							});
						}
					}
				});

			return {
				values : values
			};
		}
	};


	$.ZnHgTFW_Html.theme_options_export = function() {
		var message_container = $('.zn_theme_options_export_msg_container');
		var data = {
			data: {},
			action: 'zn_theme_export',
			zn_ajax_nonce: ZnAjax.security
		};
		$('#zn_theme_export_button').on('click', function() {
			// clean up any previous messages
			message_container.html('');

			data.data.export_images = $('#zn_theme_export_with_images').is(":checked");

			$.post( ajaxurl, data, function(response,textStatus, jqXHR) {

				if( textStatus.status == '500' || typeof response === 'undefined' || ! response ){
					setTimeout(function(){
						message_container.html(
							'<div class="alert alert-danger">Something went wrong... please try again in a few moments.</div>'
						).show();
					}, 3000);
					return false;
				}

				response = $.parseJSON( response );
				var hasData = (response.data && response.data.length);

				if(response.success)
				{
					if(hasData){
						message_container.html('<div class="alert alert-success">' + response.data + '</div>').show();

						// Make the ajax call to download the export archive
						$.post( ZnAjax.ajaxurl, data, function( response ) {

							if ( response.success === true ) {
								// Direct the user to the file location
								location.href = ZnAjax.ajaxurl+"?action=zn_theme_export_download&nonce=" + ZnAjax.security;
							}
							else{
								new $.ZnModalMessage('There was a problem downloading the export archive!');
							}
						});
					}
				}
				else {
					if(hasData){
						message_container.html('<div class="alert alert-danger">' + response.data + '</div>').show();
					}
					else {
						setTimeout(function(){
							message_container.html(
								'<div class="alert alert-danger">Something went wrong... please try again in a few moments.</div>'
							).show();
						}, 3000);
					}
				}
			}, 'html').fail(function(){
				setTimeout(function(){
					message_container.html(
						'<div class="alert alert-danger">Something went wrong... please try again in a few moments.</div>'
					).show();
				}, 3000);
			});
		});
	};

	// Init funcctions
	$.ZnHgTFW_Html.init();

})(jQuery)
