(function($){

	$(document).ready(function() {

		if(typeof $.ZnThemeJs != 'undefined') {

			var fw = $.ZnThemeJs;
			var counter_element = function( scope ) {

				var elements = scope.find('.zn-counterElm');

				if(elements && elements.length){
					elements.each(function(i, el){

						if(typeof($.fn.countTo) != 'undefined') {

							var $el = $(el),
								$params = $el.is("[data-zn-count]") ? JSON.parse( $el.attr("data-zn-count") ) : {},
								$paramsInt = {},
								loaded = false;

							$.map( $params, function( val, i ) {
								$paramsInt[i] = parseInt(val);
							});

							var doElement = function(){
								// animate counter
								$el.addClass('is-appeared').countTo( $paramsInt );
								// set loaded
								loaded = true;
							};

							var isInViewport = function(element) {
								var rect = element.getBoundingClientRect();
								var html = document.documentElement;
								var tolerance = rect.height * 0.75; // 3/4 of itself
								return (
									rect.top >= -tolerance
									&& rect.bottom <= (window.innerHeight || html.clientHeight) + tolerance
								);
							};

							// If it's in viewport, load it
							$(window).on('scroll', function() {
								if( isInViewport( $el[0] ) ){
									if(!loaded){
										doElement();
									}
								}
							}).trigger('scroll');
						}
					});
				}
			};

			$.extend( true, fw.prototype.zinit, counter_element( $(document) ) );

			$(window).on('ZnNewContent',function(e){
				counter_element( e.content );
			});
		}

	});

})(jQuery);