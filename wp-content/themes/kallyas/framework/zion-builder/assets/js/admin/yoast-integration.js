(function ($) {
	/**
	 * Adds pagebuilder data to Yoast analysis
	 */
	$(window).on('YoastSEO:ready',function(){

		// YOAST CONTENT FILTER
		var ZnbYoast = function() {
			YoastSEO.app.registerPlugin( 'ZnbYoast', {status: 'loading'} );
			this.fetchData();
		};

		ZnbYoast.prototype.fetchData = function() {
			var _self = this,
				ajaxData = {
					action: 'znpb_get_page_content',
					nonce: ZnPbYoastConfig.nonce,
					post_id: ZnPbYoastConfig.post_id
				};

			// Get the page content as seen on the frontend
			$.post( ajaxurl, ajaxData, function(response){
				if( response.success ){
					_self.content = response.data.content
					YoastSEO.app.pluginReady( 'ZnbYoast' );
					YoastSEO.app.registerModification( 'content', $.proxy(_self.ModifyContent, _self) , 'ZnbYoast', 5 );
				}
			});
		}

		// Add the pagebuilder data
		// Yoast already loads all the custom fields... we just need to use them
		ZnbYoast.prototype.ModifyContent = function(data) {
			return this.content.length > 0 ? this.content : data;
		};

		new ZnbYoast();

	});
})(jQuery);
