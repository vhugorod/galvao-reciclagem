(function($){

	$(document).ready(function() {

		/**
		 * WooCommerce Images and Thumbnails
		 */
		if ( typeof ZnWooCommerce != 'undefined' ){

			var doWCThumbsMfp = function(){
				if(typeof($.fn.magnificPopup) != 'undefined')
				{
					// Enable WooCommerce lightbox
					return $('a[data-shop-mfp="image"]').magnificPopup({
						mainClass: 'mfp-fade',
						type: 'image',
						gallery: {enabled:true},
						tLoading: '',
					});
				}
			};

			// Made the shop image to change on HOVER over thumbnails
			if ( ZnWooCommerce.thumbs_behavior == 'yes' ){
				var znwoo_main_imgage = $( 'a.woocommerce-main-image' ).attr( 'href' );

				$('.single_product_main_image, .summary').hover(function(){

					$('.thumbnails',this).find('a').hover(function(el){

						var width  = $('.woocommerce-main-image').width();
						var height = $('.woocommerce-main-image').height();

						var photo_fullsize = $( this ).attr( 'href' );
						$( '.woocommerce-main-image img' ).attr( 'src', photo_fullsize ).attr( 'srcset', photo_fullsize );
						$( '.product:not(.prodpage-style3) .woocommerce-main-image' ).css({'min-width': width,'min-height': height});
					}) ;

				});

				doWCThumbsMfp();

			}
			else if ( ZnWooCommerce.thumbs_behavior == 'click' ){

				var main_img = $( 'a.woocommerce-main-image' );

				$('.single_product_main_image .thumbnails a, .summary.entry-summary .thumbnails a').on('click', function(e){

					e.preventDefault();

					var photo_fullsize = $( this ).attr( 'href' );
					main_img.find( 'img' ).attr( 'src', photo_fullsize ).attr( 'srcset', photo_fullsize );
					main_img.attr( 'href', photo_fullsize );

				});

				main_img.on('click', function(e){
					e.preventDefault();

					var whichOne,
						items = [];

					$('a[data-shop-mfp="image"]:not(.woocommerce-main-image)').each(function(i, el) {
						items.push({
							src: $(el).attr('href'),
							type: 'image'
						});
						if(main_img.attr('href') == $(el).attr('href')){
							whichOne = i;
						}
					});

					if(typeof($.fn.magnificPopup) != 'undefined' && items.length) {

						$.magnificPopup.open({
							gallery:{
								enabled:true
							},
							items: items,
							mainClass: 'mfp-fade',
							tLoading: ''
						}, whichOne );
					}
					else if(main_img.length > 0){
						doWCThumbsMfp().magnificPopup('open');
					}
				});

			}
			else if(ZnWooCommerce.thumbs_behavior == 'zn_dummy_value') {
				doWCThumbsMfp();
			}
			else if(ZnWooCommerce.thumbs_behavior == 'disabled') {
				// nothing
			}
		}

	});

})(jQuery);