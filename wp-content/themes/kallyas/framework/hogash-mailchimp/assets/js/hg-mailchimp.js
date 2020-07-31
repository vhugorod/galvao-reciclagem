(function($){
	var hgMailchimp = {};

	hgMailchimp.cachedDom = function(){
		this.doc = $(document);
	};

	hgMailchimp.init = function(){

		this.cachedDom();

		this.refresh_events( this.doc );
		// Refresh events on new content
		this.doc.on('ZnNewContent',function(e){
			this.refresh_events( e.content );
		}.bind(this));
	};

	hgMailchimp.refresh_events = function(scope){
		this.mailchimp_subscribe( scope );
	};

	hgMailchimp.mailchimp_subscribe = function( scope ){

		var element = scope.find('.js-mcForm');

		if(element && element.length){
			element.each(function(index, el) {

				$(el).on('submit', function(e) {

					e.preventDefault();

					var self = $(this),
						ajax_url = self.attr('data-url'),
						email_field = self.find('.js-mcForm-email').val(),
						result_placeholder = self.parent().find('.js-mcForm-result');

					self.addClass('is-submitting');

					if(email_field === ''){
						self.addClass('has-error');
						self.removeClass('is-submitting');
						return;
					}
					else if ( !email_field.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/) ) {
						self.addClass('has-error'); //see #1902
						self.removeClass('is-submitting');
						return;
					}

					self.removeClass('has-error');
					result_placeholder.html('');

					formData = self.serialize();

					$.post( hgMailchimpConfig.ajaxurl, formData).success(function( data ){
						self.removeClass('is-submitting');
						if( data.success ){
							result_placeholder.html('<div class="dn-alert alert alert-success">' + data.data.message + '</div>');
						}
						else{
							result_placeholder.html('<div style="color:#ff0000;"><b>'+ hgMailchimpConfig.l10n.error +'</b> ' + data.data.message + '</div>');
						}
					}).error(function() {
						self.removeClass('is-submitting');
						result_placeholder.html('ERROR.').css('color', 'red');
					});

				});
			});
		}
	};

	// Init the script
	hgMailchimp.init();

})(jQuery);
