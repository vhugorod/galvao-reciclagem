/*--------------------------------------------------------------------------------------------------

 File: znscript.js

 Description: This is the main javascript file for this theme
 Please be careful when editing this file

 --------------------------------------------------------------------------------------------------*/
(function ($) {
	$.ZnThemeJs = function () {
		this.scope = $(document);
		// Holds information if the header is sticked or not
		this.isHeaderStick = false;
		this.zinit();
	};

	$.ZnThemeJs.prototype = {
		zinit: function () {
			var fw = this;

			fw.addactions();
			fw.initHelpers();
			// EVENTS THAT CAN BE REFRESHED
			fw.refresh_events($(document));
			// $('.main-menu').ZnMegaMenu();
			fw.enable_responsive_menu();
			// Enable follow menu
			fw.enable_follow_menu();
			fw.sticky_header();

			// Used for Slick videos
			this.videoBackArr = [];
			this.videoAutoplay = [];

		},

		refresh_events: function (content) {

			var fw = this;


			// FITVIDS
			fw.enable_fitvids(content);

			// Enable the logo in menu for style 11
			fw.enable_logoinmenu(content);

			// Fixed Position fix for header
			fw.fixed_header_relative(content);

			// Enable menu offset - Prevents the submenus from existing the viewport
			fw.enable_menu_offset();

			// Enable magnificpopup lightbox
			fw.enable_magnificpopup(content);
			// Enable isotope
			fw.enable_isotope(content);
			// enable woocommerce lazy images
			fw.enable_lazyload(content);
			// Enable header sparkles
			fw.enable_header_sparkles(content);
			// Slick Carousel
			fw.enable_slick_carousel(content);
			// ENABLE CONTACT FORMS
			fw.enable_contact_forms(content);
			// Enable circular carousel
			fw.enable_circular_carousel(content);
			// Enable flickr feed
			fw.enable_flickr_feed(content);
			// Enable iCarousel
			fw.enable_icarousel(content);
			// Enable latest posts css accordion
			fw.enable_latest_posts_accordion(content);
			// Enable portfolio sortable
			fw.enable_portfolio_sortable(content);
			// Enable Grid photo gallery
			fw.enable_gridphotogallery(content);
			// Enable nivo slider
			fw.enable_nivo_slider(content);
			// Enable WOW slider
			fw.enable_wow_slider(content);
			// Enable Static content - Weather
			fw.enable_static_weather(content);
			// Enable IconBox
			fw.enable_iconbox(content);
			// Enable vertical tabs hover
			fw.enable_vtabs_hover(content);
			// Enable SearchBox
			fw.enable_searchbox(content);
			// Enable toggle class
			fw.enable_toggle_class(content);
			// Enable diagram
			fw.enable_diagram(content);
			// Enable services
			fw.enable_services(content);
			// enable scrollspy
			fw.enable_scrollspy(content);
			// enable bootstrap tooltips
			fw.enable_tooltips(content);

			fw.enable_customMenuDropdown(content);
			fw.customMenuElm_toggleSubmenus(content);
			fw.enable_portfolio_readmore(content);

			// General woocommerce stuff
			fw.general_wc_stuff(content);

			// Init skillbars
			fw.init_skill_bars(content);

			// General stuff
			fw.general_stuff(content);

		},

		RefreshOnWidthChange: function (content) {
		},

		addactions: function () {
			var fw = this;

			// Refresh events on new content
			fw.scope.on('ZnWidthChanged', function (e) {
				fw.RefreshOnWidthChange(e.content);
				$(window).trigger('resize');
			});

			// Refresh events on new content
			fw.scope.on('ZnNewContent', function (e) {
				fw.refresh_events(e.content);
			});

			// go to the latest tab, if it exists:
			var activeTabs = JSON.parse( localStorage.getItem('znkl_savedTabs') ) || {};
			if (activeTabs && !jQuery.isEmptyObject(activeTabs)) {
				$.each(activeTabs, function (key, value) {
					$('[href="' + value + '"]').tab('show').addClass('active');
				})
			}

			// Refresh events on new content
			// Commented in 4.10.0 as IosSlider has been removed
			// fw.scope.on('ZnBeforePlaceholderReplace ZnBeforeElementRemove',function(e){
			// 	fw.unbind_events( e.content );
			// });
		},

		unbind_events: function (scope) {

		},

		initHelpers: function () {

			/**
			 * Helper Functions
			 */
			this.helpers = {};

			this.helpers.isInViewport = function (element) {
				var rect = element.getBoundingClientRect();
				var html = document.documentElement;
				var tolerance = rect.height * 0.75; // 3/4 of itself
				return (
					rect.top >= -tolerance
					&& rect.bottom <= (window.innerHeight || html.clientHeight) + tolerance
					//  && rect.left >= -( rect.width / 2 )
					//  && rect.right <= (window.innerWidth || html.clientWidth)
				);
			};


			// Returns a function, that, as long as it continues to be invoked, will not
			// be triggered. The function will be called after it stops being called for
			// N milliseconds. If `immediate` is passed, trigger the function on the
			// leading edge, instead of the trailing.
			this.helpers.debounce = function (func, wait, immediate) {
				var timeout;
				return function () {
					var context = this, args = arguments;
					var later = function () {
						timeout = null;
						if (!immediate) func.apply(context, args);
					};
					var callNow = immediate && !timeout;
					clearTimeout(timeout);
					timeout = setTimeout(later, wait);
					if (callNow) func.apply(context, args);
				};
			};
		},

		enable_logoinmenu: function (scope) {

			var header = $(scope).find('.site-header.kl-center-menu');

			if (header.length > 0) {
				var logo = header.find('.main-menu-wrapper').prev('.logo-container');
				// Split the menu
				var menuParents = $(".main-nav > ul > li");
				var countMenuParents = menuParents.length;
				if (countMenuParents !== 0) {
					var centerChild;
					if (countMenuParents > 1) {
						var $val = countMenuParents / 2;
						centerChild = header.hasClass('center-logo-ceil') ? Math.ceil($val) : Math.floor($val);
					} else {
						centerChild = 1;
					}
					// Clone logo into the menu
					if (logo.length) {
						var logoClone = logo.clone().insertAfter(menuParents.eq(centerChild - 1));
						logoClone.removeClass('zn-original-logo').wrap('<li class="logo-menu-wrapper"></li>');
						setTimeout(function () { logoClone.parent().addClass('is-loaded'); }, 400);
					}
				}
			}

		},

		fixed_header_relative: function (scope) {

			var $fixed_header = $(scope).find('#header.site-header--relative.header--fixed');

			if ($fixed_header.length > 0 || window.matchMedia("(min-width: 768px)").matches) {

				$fixed_header.after('<div id="site-header-FixedRelativeFix" />');

				var $fix = $('#site-header-FixedRelativeFix'),
					addHeight = function () {
						var $getHeight = $fixed_header.outerHeight();
						if (typeof $getHeight != 'undefined') {
							$fix.css('height', $getHeight);
						}
					};
				addHeight();

				$(window).on('debouncedresize', function () {
					addHeight();
				});
			}

		},

		enable_lazyload: function () {

			/** Trigger Echo Lazy Load */
			echo.init({
				offset: 50,
				throttle: 250,
				unload: false,
				callback: function (element, op) {
					if (op === 'load') {
						element.classList.add('is-loaded');
					} else {
						element.classList.remove('is-loaded');
					}
				}
			});

			$(window).on('zn_tabs_refresh', function () {
				echo.render();
			});
		},

		enable_portfolio_readmore: function (scope) {
			var element = scope.find('.znprt_load_more_button');
			if (element.length === 0) { return; }

			var fw = this;

			element.on('click', function (e) {

				e.preventDefault();

				var $this = $(this),
					page = $this.data('page'),
					ppp = $this.data('ppp'),
					container = $this.parent().find('.ptf-stb-thumbs'),
					categories = $this.data('categories');

				if ($this.hasClass('zn_loadmore_disabled')) {
					return false;
				}

				$this.addClass('kl-ptfsortable-loadmore--loading');

				$.post(ZnThemeAjax.ajaxurl, {
					action: 'zn_loadmore',
					offset: page + 1,
					ppp: ppp,
					categories: categories,
					show_item_title: $this.data('show_item_title'),
					show_item_desc: $this.data('show_item_desc'),
					zn_link_portfolio: $this.data('portfolio_links'),
					ptf_sortby_type: $this.data('ptf_sortby_type'),
					ptf_sort_dir: $this.data('ptf_sort_dir')

				}).success(function (data) {

					$this.removeClass('kl-ptfsortable-loadmore--loading');
					$this.data('page', page + 1);

					var newItems = $(data.postsHtml).css('opacity', 0).appendTo(container);
					container.imagesLoaded(function () {
						container.isotope('updateSortData', newItems).isotope('appended', newItems).isotope('layout');
						fw.refresh_events(newItems);
					});

					// Disable the load more button if there are no more posts
					if (data.isLastPage) {
						$this.addClass('zn_loadmore_disabled');
					}

				});
			});

		},

		/**
		 * Fixes submenus exiting the page on smaller screens
		 */
		enable_menu_offset: function () {

			$('#main-menu').find('ul li').on({
				"mouseenter.zn": function () {
					var $submenu = $(this).children('.sub-menu').first();
					if ($submenu.length > 0) {
						var left_offset = $submenu.offset().left;
						var width = $submenu.width();
						var pagewidth;

						if ($('body').has('.boxed')) {
							pagewidth = $('#page_wrapper').width();
						}
						else {
							pagewidth = $(window).width();
						}


						if ((left_offset + width) > pagewidth) {
							$submenu.addClass('zn_menu_on_left');
						}
					}
				},
				"mouseleave.zn": function () {
					var $submenu = $(this).children('ul').first();
					$submenu.removeClass('zn_menu_on_left');
				}
			});
		},

		enable_fitvids: function (scope) {

			var element = scope.find('.zn_iframe_wrap, .zn_pb_wrapper, .fitvids-resize-wrapper');
			if (element.length === 0) { return; }

			element.fitVids({ ignore: '.no-adjust, .kl-blog-post-body,.no-fitvids' });

		},

		enable_contact_forms: function (scope) {
			var fw = this,
				element = (scope) ? scope.find('.zn_contact_form_container > form') : $('.zn_contact_form_container > form');

			element.each(function (index, el) {

				var $el = $(el),
					time_picker = $el.find('.zn_fr_time_picker'),
					date_picker = $el.find('.zn_fr_date_picker'),
					datepicker_lang = date_picker.is('[data-datepickerlang]') ? date_picker.attr('data-datepickerlang') : '',
					date_format = date_picker.is('[data-dateformat]') ? date_picker.attr('data-dateformat') : 'yy-mm-dd',
					timeformat = time_picker.is('[data-timeformat]') ? time_picker.attr('data-timeformat') : 'h:i A';

				if (time_picker.length > 0) {
					time_picker.timepicker({
						'timeFormat': timeformat,
						'className': 'cf-elm-tp'
					});
				}
				if (date_picker.length > 0) {
					date_picker.datepicker({
						dateFormat: date_format,
						showOtherMonths: true
					}).datepicker('widget').wrap('<div class="ll-skin-melon"/>');

					if (datepicker_lang !== '') {
						$.datepicker.setDefaults($.datepicker.regional[datepicker_lang]);
					}

				}

				// Material forms
				$('.kl-material-form.zn_cf_text .zn_form_input, .kl-material-form.zn_cf_textarea .zn_form_input, .kl-material-form.zn_cf_datepicker .zn-field-datepicker').on('change focus blur', function (e) {
					if ($(this).val() != '') {
						$(this).addClass('input-has-content');
					} else {
						$(this).removeClass('input-has-content');
					}
				});

				// SUBMIT
				$el.on('submit', function (e) {

					e.preventDefault();

					if (fw.form_submitting === true) { return false; }

					fw.form_submitting = true;

					var form = $(this),
						response_container = form.find('.zn_contact_ajax_response:eq(0)'),
						has_error = false,
						inputs =
							{
								fields: form.find('textarea, select, input[type="text"], input[type="checkbox"], input[type="hidden"]')
							},
						form_id = response_container.attr('id'),
						submit_button = form.find('.zn_contact_submit');

					// Some IE Fix
					if ((isIE11 || isIE10 || isIE9) && form.is('[action="#"]')) {
						form.attr('action', '');
					}

					// FADE THE BUTTON
					submit_button.addClass('zn_form_loading');

					// PERFORM A CHECK ON ELEMENTS :
					inputs.fields.each(function () {
						var field = $(this),
							p_container = field.parent();

						// Set the proper value for checkboxes
						if (field.is(':checkbox')) {
							if (field.is(':checked')) { field.val(true); } else { field.val(''); }
						}

						// Check if this is a select multiple field
						// if( field.is('select') && field.attributes['multiple'] != 'undefined' ){

						// }

						p_container.removeClass('zn_field_not_valid');

						// Check fields that needs to be filled
						if (field.hasClass('zn_validate_not_empty')) {
							if (field.is(':checkbox')) {
								if (!field.is(':checked')) {
									p_container.addClass('zn_field_not_valid');
									has_error = true;
								}
							}
							else {
								if (field.val() === '' || field.val() === null) {
									p_container.addClass('zn_field_not_valid');
									has_error = true;
								}
							}
						}
						else if (field.hasClass('zn_validate_is_email')) {
							if (!field.val().match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
								p_container.addClass('zn_field_not_valid');
								has_error = true;
							}
						}
						else if (field.hasClass('zn_validate_is_letters_ws')) {
							if (field.val().match(/[^A-Za-z\s]/i)) {
								p_container.addClass('zn_field_not_valid');
								has_error = true;
							}
						}
						else if (field.hasClass('zn_validate_is_numeric')) {
							if (isNaN(field.val())) {
								p_container.addClass('zn_field_not_valid');
								has_error = true;
							}
						}
					});

					if (has_error) {
						submit_button.removeClass('zn_form_loading');
						fw.form_submitting = false;
						return false;
					}

					var data = form.serialize();
					$.post(form.attr('action'), data).success(function (result) {

						// DO SOMETHING
						fw.form_submitting = false;
						submit_button.removeClass('zn_form_loading');

						// Perform the redirect if the form was valid
						var response = $(result).find('#' + form_id + ' > .zn_cf_response'),
							responseContainer = $('#' + form_id),
							redirect_uri = form.data('redirect');

						responseContainer.html(response);

						// If the form was successfull
						if (response.hasClass('alert-success')) {
							inputs.fields.val('');
							if (redirect_uri) {
								window.location.replace(redirect_uri);
							}
						}
					})
						.error(function () {
							console.log('Error loading page');
						})
					return false;

				});
			});

		},

		/* Button to toggle a class
		* example: class="js-toggle-class" data-target=".kl-contentmaps__panel" data-target-class="is-closed"
		*/
		enable_toggle_class: function (scope) {
			var elements = scope.find('.js-toggle-class');
			elements.each(function (index, el) {
				var $el = $(el);
				$el.on('click', function (e) {
					e.preventDefault();

					$el.toggleClass('is-toggled');

					if (!$el.is('[data-multiple-targets]')) {
						var target = $el.is('[data-target]') ? $el.attr('data-target') : $el,
							target_class = $el.is('[data-target-class]') ? $el.attr('data-target-class') : '';
						if (target && target.length && target_class && target_class.length) {
							$(target).toggleClass(target_class);
							window.didScroll = false;
						}
					}
					else {
						var targets = $el.is('[data-targets]') ? $el.attr('data-targets') : '',
							target_classes = $el.is('[data-target-classes]') ? $el.attr('data-target-classes') : '';
						if (targets && targets.length && target_classes && target_classes.length) {
							var split_targets = targets.split(','),
								split_target_classes = target_classes.split(',');
							if (split_targets.length > 0) {
								$(split_targets).each(function (i, target) {
									$(target).toggleClass(split_target_classes[i]);
								});
							}
						}
					}

					$(window).trigger('resize');

				});
			});
		},

		enable_isotope: function (scope) {

			scope.find('.js-isotope, .zn_blog_columns:not(.kl-cols-1)').each(function (index, el) {

				var $el = $(el),
					$custom = IsJsonString($el.attr("data-kl-isotope")) ? JSON.parse($el.attr("data-kl-isotope")) : {};

					
				var $opts = {
					itemSelector: ".blog-isotope-item",
					animationOptions: {
						duration: 250,
						easing: "easeOutExpo",
						queue: false
					},
					sortAscending: true,
					sortBy: '',
					isInitLayout: false,
				};

				if (!$.isEmptyObject($custom)) {
					$.extend($opts, $custom);
				}
				$el.imagesLoaded(function () {
					if (typeof $.fn.isotope != 'undefined') {
						$el.isotope($opts);
						$el.isotope('on', 'arrangeComplete', function () {
							$el.addClass('isotope-initialized');
						});
						$el.isotope();
					}
				});
			});
		},

		enable_follow_menu: function () {
			var header = $('header#header'),
				chaser = $('#main-menu > ul'),
				forch = 120,
				_chaser;

			if (!header.hasClass('header--follow') || window.matchMedia("(max-width: 1024px)").matches) {
				return;
			}

			if (chaser && chaser.length > 0) {

				chaser.clone()
					.appendTo(document.body)
					.wrap('<div class="chaser" id="site-chaser"><div class="container"><div class="row"><div class="col-md-12"></div></div></div></div>')
					.addClass('chaser-main-menu');

				_chaser = $('#site-chaser')[0];

				if (is_undefined(scrollMagicController)) return;

				var scene = new ScrollMagic.Scene({
					offset: forch,
					reverse: true
				});
				scene.setClassToggle(_chaser, 'visible');
				scene.addTo(scrollMagicController);

			}
		},

		sticky_header: function () {

			var $el = $("#header.header--sticky"),
				fw = this;

			if ($el.length === 0)
				return;

			var classForVisibleState = 'header--is-sticked',
				classForHiddenState = 'header--not-sticked',
				headerParts = $el.find('.site-header-top-wrapper, .site-header-main-wrapper, .site-header-bottom-wrapper'),
				forch = 1;

			if (is_undefined(scrollMagicController)) return;

			var scene = new ScrollMagic.Scene({
				offset: forch
			});

			var switchTextScheme = function (e) {

				if (!$el.is('[data-custom-sticky-textscheme]')) return;

				var originalClass = $el.attr('data-original-sticky-textscheme');
				var customClass = $el.attr('data-custom-sticky-textscheme');

				headerParts.removeClass('sh--dark sh--light sh--gray');

				if (e == 'leave')
					headerParts.addClass(originalClass);
				else if (e == 'enter')
					headerParts.addClass(customClass);
			};

			scene.on("enter", function (e) {
				$el.removeClass(classForHiddenState).addClass(classForVisibleState);
				$el.one( 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
					fw.isHeaderStick = true;
				});

				// Add sticky text-scheme class
				switchTextScheme('enter');
			});
			scene.on("leave", function (e) {
				$el.removeClass(classForVisibleState).addClass(classForHiddenState);
				$el.one( 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
					fw.isHeaderStick = false;
				});

				// Remove sticky text-scheme class and add initial one
				switchTextScheme('leave');
			});

			scene.addTo(scrollMagicController);

		},

		enable_responsive_menu: function () {

			var main_menu = $('#main-menu.mainnav--sidepanel > ul');

			if (main_menu.length > 0) {

				var fw = this,
					page_wrapper = $('#page_wrapper'),
					responsive_trigger = $('#zn-res-trigger'),
					menu_activated = false,
					color_theme = ZnThemeAjax.zn_color_theme,
					cloned_menu = main_menu.clone().attr({ id: "zn-res-menu", "class": "zn-res-menu-nav znResMenu-" + color_theme });

				// Change id's See KAL-81
				cloned_menu.find('.zn-megaMenuSmartArea [id]').attr('id', function (index, currentId) {
					return currentId + '_cloned';
				});

				cloned_menu.find('.zn-megaMenuSmartArea [data-toggle]').attr('href', function (index, currentId) {
					return currentId + '_cloned';
				});

				var back_text = function (tag) {
					return '<' + tag + ' class="zn_res_menu_go_back"><span class="zn_res_back_icon glyphicon glyphicon-chevron-left"></span><a href="#" class="zn_res_menu_go_back_link">' + ZnThemeAjax.zn_back_text + '</a></' + tag + '>';
				};

				var closeMenu = function () {
					cloned_menu.removeClass('zn-menu-visible');
					responsive_trigger.removeClass('is-active');
					removeHeight();
				};

				var removeHeight = function () {
					page_wrapper.css({ 'height': 'auto' });
				};

				var openMenu = function () {
					cloned_menu.addClass('zn-menu-visible');
					responsive_trigger.addClass('is-active');
					set_height();
				};

				var set_height = function () {
					var _menu = $('.zn-menu-visible').last(),
						height = _menu.css({ height: 'auto' }).outerHeight(true),
						window_height = $(window).height(),
						adminbar_height = 0,
						admin_bar = $('#wpadminbar');

					// CHECK IF WE HAVE THE ADMIN BAR VISIBLE
					if (height < window_height) {
						height = window_height;
						if (admin_bar.length > 0) {
							adminbar_height = admin_bar.outerHeight(true);
							height = height - adminbar_height;
						}
					}
					_menu.attr('style', '');
					page_wrapper.css({ 'height': height });
				};

				var start_responsive_menu = function () {

					var responsive_menu = cloned_menu.prependTo(page_wrapper);

					// ADD ARROWS TO SUBMENUS TRIGGERS
					responsive_menu.find('li:has(> ul.sub-menu), li:has(> div.zn_mega_container)')
						.addClass('zn_res_has_submenu')
						.prepend('<span class="zn_res_submenu_trigger glyphicon glyphicon-chevron-right"></span>');

					// Add back  for 1st level
					responsive_menu.prepend(back_text('li'));

					// Add back buttons in submenus
					responsive_menu.find('.zn_res_has_submenu > ul.sub-menu')
						.prepend(back_text('li'));

					// Add back buttons in mega menus
					responsive_menu.find('.zn_res_has_submenu > div.zn_mega_container')
						.prepend(back_text('div'));

					// Close the menu when a link is clicked
					responsive_menu.find('a:not([rel*="mfp-"]):not(.zn_res_menu_go_back_link)').on('click', function (e) {
						closeMenu();
					});

					// Close levels
					cloned_menu.find('.zn_res_back_icon, .zn_res_menu_go_back_link').on('click', function (e) {
						e.preventDefault();

						var active_menu = $(this).closest('.zn-menu-visible');

						if (active_menu.is('#zn-res-menu')) {
							closeMenu();
						}
						else {
							active_menu.removeClass('zn-menu-visible');
							set_height();
						}
					});

					// OPEN SUBMENU'S ON CLICK
					cloned_menu.find('.zn_res_submenu_trigger').on('click', function (e) {
						e.preventDefault();
						$(this).siblings('ul, .zn_mega_container').addClass('zn-menu-visible');
						set_height();
					});

					// BIND OPEN MENU TRIGGER
					responsive_trigger.on('click', function (e) {
						e.preventDefault();
						if ($(this).hasClass('is-active')) {
							closeMenu();
						}
						else {
							openMenu();
						}
					});
				};

				// MAIN TRIGGER FOR ACTIVATING THE RESPONSIVE MENU
				$(window).on('debouncedresize', function () {
					if ($(window).width() <= ZnThemeAjax.res_menu_trigger) {
						if (!menu_activated) {
							start_responsive_menu();
							menu_activated = true;
							fw.refresh_events(cloned_menu);
						}
						page_wrapper.addClass('zn_res_menu_visible');
					}
					else {
						// WE SHOULD HIDE THE MENU
						closeMenu();
						page_wrapper.css({ 'height': 'auto' }).removeClass('zn_res_menu_visible');
					}
					// Fix for triggering the responsive menu
				}).trigger('debouncedresize');
			}
		},

		enable_header_sparkles: function (content) {

			var sparkles = content.find('.th-sparkles:visible');
			if (sparkles.length === 0) { return false; }

			sparkles.each(function () {
				var a = 40,
					i = 0;
				for (i; i < a; i++) {
					new Spark($(this));
				}

			});

		},

		enable_magnificpopup: function (content) {
			var fw = this;

			if (typeof ($.fn.magnificPopup) !== 'undefined') {

				$('a.kl-login-box, .zn-loginModalBtn>a').magnificPopup({
					type: 'inline',
					closeBtnInside: true,
					showCloseBtn: true,
					mainClass: 'mfp-fade mfp-bg-lighter',
					callbacks: {
						close: function () {
							var $content = this.content;
							$content.find('.zn_form_login-result').html('');
							$content.find('input.form-control').val('');
						}
					}
				});

				var gal_config = {
					delegate: 'a[data-type="image"]',
					type: 'image',
					gallery: { enabled: true },
					tLoading: '',
					mainClass: 'mfp-fade'
				};

				$('a[data-lightbox="image"]:not([data-type="video"]), .mfp-image').each(function (i, el) {
					var $el = $(el);
					//single image popup
					if ($el.parents('.gallery').length === 0) {
						$el.magnificPopup({
							type: 'image',
							tLoading: '',
							mainClass: 'mfp-fade'
						});
					}
					else {
						$el.parents('.gallery').magnificPopup(gal_config);
					}
				});

				$('.zn-modal-img-gallery').each(function (i, el) {
					$(el).magnificPopup(gal_config);
				});


				$('.mfp-gallery.mfp-gallery--images').each(function (i, el) {
					$(el).magnificPopup({
						delegate: 'a',
						type: 'image',
						gallery: { enabled: true },
						tLoading: '',
						mainClass: 'mfp-fade'
					});
				});
				// Notice the .misc class, this is a gallery which contains a variatey of sources
				// links in gallery need data-mfp attributes eg: data-mfp="image"
				$('.mfp-gallery.mfp-gallery--misc').each(function (i, el) {
					$(el).magnificPopup({
						mainClass: 'mfp-fade',
						delegate: 'a[data-lightbox="mfp"]',
						type: 'image',
						gallery: { enabled: true },
						tLoading: '',
						callbacks: {
							elementParse: function (item) {
								item.type = $(item.el).attr('data-mfp');
							}
						}
					});
				});

				// Link post images
				var post_img_config = {
					delegate: 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"]',
					type: 'image',
					gallery: { enabled: true },
					tLoading: '',
					mainClass: 'mfp-fade'
				};
				// Full content's archive images
				$('.kl-blog-content-full .kl-blog-item-content a[href$=".jpg"], .kl-blog-content-full .kl-blog-item-content a[href$=".jpeg"], .kl-blog-content-full .kl-blog-item-content a[href$=".png"]').each(function (i, el) {
					$(el).parents('.kl-blog-item-content').magnificPopup(post_img_config);
				});
				// Single post content's images
				$('.kl-blog-link-images .kl-blog-post-body a[href$=".jpg"], .kl-blog-link-images .kl-blog-post-body a[href$=".jpeg"], .kl-blog-link-images .kl-blog-post-body a[href$=".png"]').each(function (i, el) {
					$(el).parents('.kl-blog-post-body').magnificPopup(post_img_config);
				});

				$('a[data-lightbox="iframe"], a[rel="mfp-iframe"], .mfp-iframe').magnificPopup({ type: 'iframe', mainClass: 'mfp-fade', tLoading: '' });
				$('a[data-lightbox="inline"], a[rel="mfp-inline"], .kallyas-inline-modal-trigger > a').magnificPopup({ type: 'inline', mainClass: 'mfp-fade', tLoading: '' });
				$('a[data-lightbox="ajax"], a[rel="mfp-ajax"]').magnificPopup({ type: 'ajax', mainClass: 'mfp-fade', tLoading: '' });
				$('a[data-lightbox="youtube"], a[data-lightbox="vimeo"], a[data-lightbox="gmaps"], a[data-type="video"], a[rel="mfp-media"]').magnificPopup({
					disableOn: 700,
					type: 'iframe',
					removalDelay: 160,
					preloader: true,
					fixedContentPos: false,
					mainClass: 'mfp-fade',
					tLoading: ''
				});

				// Dynamic inline modal
				// Will pass the title attribute to a dynamic field in a form
				var dynModalWin = $('a[data-lightbox="inline-dyn"]');
				dynModalWin.each(function (index, el) {
					$(el).magnificPopup({
						type: 'inline',
						mainClass: 'mfp-fade',
						callbacks: {
							open: function () {
								var inst = $.magnificPopup.instance,
									form = $(inst.content).find('form'),
									itemTitle = $(el).attr('title');

								if ($(form).length > 0 && itemTitle !== '') {
									var dynamicField = form.first().find('.zn-field-dynamic');
									if ($(dynamicField).length > 0) {
										$(dynamicField).first().val(itemTitle).attr('readonly', 'readonly');
									}
								}

							},
						}
					});
				});


				var getExpired = function (e) {
					if (e == 'halfhour') {
						return 60 * 30 * 1000;
					}
					else if (e == 'hour') {
						return 60 * 60 * 1000;
					}
					else if (e == 'day') {
						return 24 * 60 * 60 * 1000;
					}
					else if (e == 'week') {
						return 7 * 24 * 60 * 60 * 1000;
					}
					else if (e == '2week') {
						return 2 * 7 * 24 * 60 * 60 * 1000;
					}
					else if (e == 'month') {
						return 30 * 24 * 60 * 60 * 1000;
					}
					else if (e == '1year') {
						return 365 * 24 * 60 * 60 * 1000;
					}
				};

				function setCookie(cname, cvalue, expire) {
					var d = new Date();
					d.setTime(d.getTime() + (expire));
					var expires = "expires=" + d.toUTCString();
					document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
				}

				function getCookie(cname) {
					var name = cname + "=";
					var ca = document.cookie.split(';');
					for (var i = 0; i < ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0) == ' ') {
							c = c.substring(1);
						}
						if (c.indexOf(name) == 0) {
							return c.substring(name.length, c.length);
						}
					}
					return "";
				}


				// Auto-Popup Modal Window - Immediately
				// Options located in Section element > Advanced
				$('body:not(.zn_pb_editor_enabled) .zn_section--auto-immediately').each(function (index, el) {

					var $el = $(el),
						window_id = $el.attr('id'),
						thecookie = 'automodal' + window_id;

					if (typeof getCookie(thecookie) != 'undefined' && getCookie(thecookie) == 'true') {
						return;
					}

					$.magnificPopup.open({
						items: {
							src: $el,
							type: 'inline'
						},
						mainClass: 'mfp-fade',
						callbacks: {
							open: function () {
								// Check if force cookie is added
								if ($el.is('[data-autoprevent]')) {
									// Assign cookie
									setCookie(thecookie, 'true', getExpired($el.attr('data-autoprevent')));
								}
							}
						}
					});
				});

				// Auto-Popup Modal Window - On Scroll
				// Options located in Section element > Advanced
				$('body:not(.zn_pb_editor_enabled) .zn_section--auto-scroll').each(function (index, el) {

					var $el = $(el),
						window_id = $el.attr('id'),
						thecookie = 'automodal' + window_id,
						isAppeared = false;

					if (typeof getCookie(thecookie) != 'undefined' && getCookie(thecookie) == 'true') {
						return;
					}

					$(window).on('scroll', fw.helpers.debounce(function () {
						if ($(window).scrollTop() > ($(document).outerHeight() / 2) && isAppeared === false) {
							$.magnificPopup.open({
								items: {
									src: $el,
									type: 'inline'
								},
								mainClass: 'mfp-fade',
								callbacks: {
									open: function () {
										// Check if force cookie is added
										if ($el.is('[data-autoprevent]')) {
											// Assign cookie
											setCookie(thecookie, 'true', getExpired($el.attr('data-autoprevent')));
										}
									}
								}
							});
							isAppeared = true;
						}
					}, 300));
				});

				// Auto-Popup Modal Window - On X seconds Delay
				// Options located in Section element > Advanced
				$('body:not(.zn_pb_editor_enabled) .zn_section--auto-delay').each(function (index, el) {

					var $el = $(el),
						window_id = $el.attr('id'),
						thecookie = 'automodal' + window_id,
						isAppeared = false,
						delay = $el.is("[data-auto-delay]") ? parseInt($el.attr("data-auto-delay")) : 5;

					if (typeof getCookie(thecookie) != 'undefined' && getCookie(thecookie) == 'true') {
						return;
					}

					setTimeout(function () {
						$.magnificPopup.open({
							items: {
								src: $el,
								type: 'inline'
							},
							mainClass: 'mfp-fade',
							callbacks: {
								open: function () {
									// Check if force cookie is added
									if ($el.is('[data-autoprevent]')) {
										// Assign cookie
										setCookie(thecookie, 'true', getExpired($el.attr('data-autoprevent')));
									}
								}
							}
						});
						isAppeared = true;
					}, delay * 1000);
				});

			}
		},

		// Will fix video volume inside iOs slider see #KAL-306
		checkVideosVolume: function(args){
			var fw = this,
				$slider = $(args.slider),
				$videoSlides = $slider.find( '.slick-cloned[data-video-slide]' );

				$videoSlides.each(function(){
					// find & load videos
					var $slide = $(this),
						$vid = $slide.find('.zn-videoBg'),
						$iframeVid = $slide.find('iframe'),
						isLoaded = $vid.hasClass('video-loaded'),
						$vidParams = $vid.is("[data-video-setup]") && IsJsonString($vid.attr("data-video-setup")) ? JSON.parse($vid.attr("data-video-setup")) : {};

					// Remove old iframe
					$iframeVid.remove();

					// Load video
					if (!isLoaded && $vid.length) {
						if (typeof video_background !== 'undefined' && !$.isEmptyObject($vidParams)) {
							new video_background($vid, $vidParams);
							$vid.addClass('video-loaded');
						}
					}
				});
		},

		checkSlickVideos: function (args) {

			var fw = this,
				theSlider = $(args.sliderObject),
				currentSlide = args.currentSlideNumber,
				currentSlideObj = $(theSlider[currentSlide]),
				prevSlide = args.previousSlideNumber,
				prevSlideObj = $(theSlider[prevSlide]);

			// Pause previous video (if any)
			if (prevSlideObj.is('[data-video-slide]')) {
				if (fw.videoBackArr[prevSlide]) {
					if (fw.videoBackArr[prevSlide].isPlaying()) {
						// Pause the previous video
						fw.videoBackArr[prevSlide].pause();
						// if video was played and had autoplay disabled, force enable it
						fw.videoAutoplay[prevSlide] = true;
					}
				}
			}

			// stop if current slide doesn't have a video
			if (!currentSlideObj.is('[data-video-slide]')) {
				return;
			}

			// find & load videos
			var $vid = currentSlideObj.find('.zn-videoBg'),
				isLoaded = $vid.hasClass('video-loaded'),
				$vidParams = $vid.is("[data-video-setup]") && IsJsonString($vid.attr("data-video-setup")) ? JSON.parse($vid.attr("data-video-setup")) : {};

			// Load video
			if (!isLoaded && $vid.length) {
				if (typeof video_background !== 'undefined' && !$.isEmptyObject($vidParams)) {
					fw.videoBackArr[currentSlide] = new video_background($vid, $vidParams);
					$vid.addClass('video-loaded');
				}
				// check if autoplay is enabled and undefined
				fw.videoAutoplay[currentSlide] = $vidParams.autoplay === true;
			}

			if (fw.videoBackArr[currentSlide]) {
				// check if video's autoplay enabled
				if (!fw.videoAutoplay[currentSlide]) return;
				// play the current video
				fw.videoBackArr[currentSlide].play();
			}

		},

		enable_slick_carousel: function (content) {
			var fw = this;
			var elements = content.find('.js-slick, .js-ios-slick');
			if (elements.length && typeof ($.fn.slick) !== 'undefined') {

				elements.each(function (i, el) {

					var $el = $(el),
						$attr = IsJsonString($el.attr("data-slick")) ? JSON.parse($el.attr("data-slick")) : {};

					$el.imagesLoaded(function () {
						$el.slick({
							"prevArrow": '<span class="znSlickNav-arr znSlickNav-prev"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="184,16 72,128 184,240"></polyline></svg></span>',
							"nextArrow": '<span class="znSlickNav-arr znSlickNav-next"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="72,16 184,128 72,240"></polyline></svg></span>',
							customPaging: function (slider, i) {
								return $('<button type="button" class="slickBtn" data-role="none" role="button" tabindex="0" />').text(i + 1);
							},
							rtl: ($('html').is('[dir]') && $('html').attr('dir') == 'rtl') ? true : false
						});
					});

					// Add preloader
					if (typeof $attr.loadingContainer != 'undefined') {
						$el.on('init', function (event, slick) {
							$el.closest($attr.loadingContainer).addClass('is-initialised');
						});
					}

					// Add thumbnails
					if (typeof $attr.thumbs != 'undefined' && $attr.thumbs) {
						$el.on('init', function (event, slick) {
							$($attr.appendDots).find('li').each(function (index, el) {
								var assocSlide = slick.$slides[index],
									thumbImg = $(assocSlide).attr('data-thumb');
								$(el).children('button').attr('style', 'background-image:url(' + thumbImg + ')');
							});
						});
					}

					// Fancy Slider
					var isFancy = typeof $attr.fancy != 'undefined' && $attr.fancy;
					// Callback function for fancy slider
					function slideCompleteFancy(e, slick, slide) {
						var slideshow = $(slick.$slider).closest('.kl-slideshow'),
							color = $(slick.$slides[slide]).attr('data-color');
						// appendFancy is defined
						if (typeof $attr.appendFancy != 'undefined' && $attr.appendFancy !== '') {
							slideshow = $($attr.appendFancy);
						}
						slideshow.css({ backgroundColor: color });
					}

					// Apply active index on container or custom defined container
					var activeIndex = typeof $attr.activeIndex != 'undefined' && $attr.activeIndex !== '';
					function slideActiveIndex(slide) {
						$($attr.activeIndex).attr('data-active-slide', slide);
					}

					var prevSlide = 0;
					// Check for videos
					// Used in IOS Slider.
					function checkVideo(slick, slide) {
						var args = {
							'sliderObject': slick.$slides,
							'currentSlideNumber': slide,
							'previousSlideNumber': prevSlide
						};
						prevSlide = slide;
						fw.checkSlickVideos(args);
					}

					function activateClass(slick) {
						$(slick.$slider).addClass('slickSlider--activated');
						setTimeout(function () {
							$(slick.$slider).removeClass('slickSlider--activated');
						}, (parseInt(slick.defaults.autoplaySpeed) * 2) - 500);
					}

					// Events
					$el
						.on('init', function (event, slick) {
							activateClass(slick);

							$(slick.$slides[0]).addClass('slick-item--activated');
							// If fancy slider enabled
							if (isFancy) slideCompleteFancy(event, slick, 0);
							// Active index
							if (activeIndex) slideActiveIndex(0);
							// Check videos
							checkVideo(slick, 0);
							fw.checkVideosVolume({
								slider: slick.$slider
							})
						})
						.on('beforeChange', function (event, slick, currentSlide, nextSlide) {

							slick.$slides.removeClass('slick-item--activated');
							// If fancy slider enabled
							if (isFancy) slideCompleteFancy(event, slick, nextSlide);
							// Active index
							if (activeIndex) slideActiveIndex(nextSlide);
							// Check videos
							checkVideo(slick, nextSlide);

							// Check for @echo lazy loads
							if ($el.hasClass('spp-list') && $(slick.$slides[nextSlide]).nextAll('.slick-slide').find('img[data-echo]').length) {
								echo.render();
							}
						})
						.on('afterChange', function (event, slick, currentSlide, nextSlide) {

							activateClass(slick);

							// Allow us to pass a callback function for the afterChange event
							var fn = fw[$attr.afterChangeCallback];
							if (typeof fn === 'function') {
								fn(event, slick, currentSlide, nextSlide);
							}

							$(slick.$slides[currentSlide]).addClass('slick-item--activated');

						});

				});

			}
		},

		// Add an active class to laptop descriptions
		laptopSliderChangeCallback: function (event, slick, currentSlide, nextSlide) {
			var $slider = $(event.currentTarget),
				$sliderContainer = $slider.closest('.ls__container'),
				$descriptions = $sliderContainer.find('.ls_slide_item-details');

			$descriptions.removeClass('znlp-is-active');
			$descriptions.eq(currentSlide).addClass('znlp-is-active');

		},

		enable_circular_carousel: function (content) {
			var cirContentContainer = content.find('.ca-container'),
				elements = cirContentContainer.children('.ca-wrapper');

			// do the carousel
			if (elements && elements.length > 0) {
				$.each(elements, function (i, e) {

					var self = $(e);

					// Open wrapper panel
					var opened = false;
					self.find('.js-ca-more, .js-ca-close').on('click', function (e) {
						e.preventDefault();

						var th = $(this).hasClass('ca-item') ? $(this) : $(this).closest('.ca-item'),
							isCloseButton = $(this).hasClass('js-ca-close');

						if (!opened) {

							self.slick('slickPause');
							self.closest('.ca-container').addClass('ca--is-rolling');
							th.addClass('ca--opened');

							var activeItems = self.find('.ca-item.slick-active'),
								openedIndex = activeItems.index(th),
								moveTo = (self.width() / activeItems.length) * openedIndex;

							th.css({
								"-webkit-transform": "translateX(-" + moveTo + "px)",
								"-ms-transform": "translateX(-" + moveTo + "px)",
								"transform": "translateX(-" + moveTo + "px)"
							});
							opened = true;

						} else if (opened && isCloseButton) {

							self.slick('slickPlay');
							self.closest('.ca-container').removeClass('ca--is-rolling');
							th.removeClass('ca--opened');

							th.css({
								"-webkit-transform": "translateX(0)",
								"-ms-transform": "translateX(0)",
								"transform": "translateX(0)"
							});
							opened = false;
							e.stopPropagation();
						}
					});

					//@kos Links clicked inside ca-item
					//#! Fixes KAL-114
					self.find('.ca-content-wrapper a').on('click', function (ev) {
						ev.stopPropagation();
						return (/\/\/+/.test($(this).attr('href')));
					});
				});

			}
		},


		enable_flickr_feed: function (content) {
			var elements = content.find('.flickr_feeds');
			if (elements && elements.length) {
				$.each(elements, function (i, e) {
					var self = $(e),
						ff_limit = (self.attr('data-limit') ? self.attr('data-limit') : 6),
						fid = self.attr('data-fid');
					if (typeof ($.fn.jflickrfeed) != 'undefined') {
						self.jflickrfeed({
							limit: ff_limit,
							qstrings: { id: fid },
							itemTemplate: '<li class="flickrfeed-item"><a href="{{image_b}}" class="flickrfeed-link hoverBorder" data-lightbox="image"><img src="{{image_s}}" alt="{{title}}" class="flickrfeed-img" /></a></li>'
						},
							function (data) {
								self.find(" a[data-lightbox='image']").magnificPopup({ type: 'image', tLoading: '' });
								self.parent().removeClass('loading');
							});
					}
				});
			}
		},


		enable_icarousel: function (content) {
			var elements = content.find('.th-icarousel');
			if (elements && elements.length) {
				$.each(elements, function (i, e) {

					var element = $(e),
						carouselSettings = {
							easing: 'easeInOutQuint',
							pauseOnHover: true,
							timerPadding: 0,
							timerStroke: 4,
							timerBarStroke: 0,
							animationSpeed: 700,
							nextLabel: "",
							previousLabel: "",
							autoPlay: element.is("[data-autoplay]") ? element.data('autoplay') : true,
							slides: element.is("[data-slides]") ? element.data('slides') : 7,
							pauseTime: element.is("[data-timeout]") ? element.data('timeout') : 5000,
							perspective: element.is("[data-perspective]") ? element.data('perspective') : 75,
							slidesSpace: element.is("[data-slidespaces]") ? element.data('slidespaces') : 300,
							direction: element.is("[data-direction]") ? element.data('direction') : "ltr",
							timer: element.is("[data-timer]") ? element.data('timer') : "Bar",
							timerOpacity: element.is("[data-timeropc]") ? element.data('timeropc') : 0.4,
							timerDiameter: element.is("[data-timerdim]") ? element.data('timerdim') : 220,
							keyboardNav: element.is("[data-keyboard]") ? element.data('keyboard') : true,
							mouseWheel: element.is("[data-mousewheel]") ? element.data('mousewheel') : true,
							timerColor: element.is("[data-timercolor]") ? element.data('timercolor') : "#FFF",
							timerPosition: element.is("[data-timerpos]") ? element.data('timerpos') : "bottom-center",
							timerX: element.is("[data-timeroffx]") ? element.data('timeroffx') : 0,
							timerY: element.is("[data-timeroffy]") ? element.data('timeroffy') : -20
						};

					// Start the carousel already :)
					if (typeof ($.fn.iCarousel) != 'undefined') {
						element.imagesLoaded(function () {
							element.iCarousel(carouselSettings);
						});
					}
				});
			}
		},

		enable_iconbox: function (content) {

			// Iconboxes with appearance effect
			var el_stage = content.find('.kl-iconbox[data-stageid]');
			if (el_stage && el_stage.length) {

				$.each(el_stage, function (i, e) {
					var self = $(e),
						stageid = self.attr('data-stageid'),
						title = self[0].getAttribute('data-pointtitle'),
						nr = self.is('[data-point-number]') ? 'data-nr="' + self.attr('data-point-number') + '"' : '',
						px = self.attr('data-pointx'),
						py = self.attr('data-pointy'),
						theStage = $('.stage-ibx--src-ibx.' + stageid),
						unit = self.attr('data-unit') || 'px';

					if (stageid && px && py) {
						var span = $('<span style="top:' + py + unit + '; left: ' + px + unit + ';" class="stage-ibx__point" ' + nr + '></span>');

						// Set the title. This method fixes #2128
						if (title) {
							span[0].setAttribute('data-title', title);
						}

						theStage.find('.stage-ibx__stage').append(span);
						setTimeout(function () {
							span.css('opacity', 1);
						}, 300 * i);
						self.on('mouseover', span, function () {
							span.addClass('is-hover');
						});
						self.on('mouseout', span, function () {
							span.removeClass('is-hover');
						});
					}
				});
			}
		},

		enable_vtabs_hover: function (content) {
			$el = content.find('.vertical_tabs');

			$el.each(function(){

				var hasEnabledHover = $(this).data('is-hover-active-enabled') === 1;

				if( hasEnabledHover ) {
					$(this).find( '[data-toggle="tab"]' ).on('mouseenter.bs.tab.data-api', function (event) {
						event.stopImmediatePropagation();
						$(this).tab('show');
					});
				}
			})
		},

		enable_searchbox: function (content) {

			var el = content.find('.elm-searchbox--eff-typing');
			if (el && el.length) {
				$.each(el, function (i, e) {

					$(e).find('.elm-searchbox__input')
						.on('focus', function (ev) {
							$(this).addClass('is-focused');
						})
						.on('keyup', function (ev) {
							if ($(this).val() !== '') {
								$(this).addClass('is-focused');
							}
						})
						.on('blur', function (ev) {
							if ($(this).val() === '') {
								$(this).removeClass('is-focused');
							}
						});

				});
			}

		},

		enable_latest_posts_accordion: function (content) {
			var elements = content.find('.css3accordion');
			if (elements && elements.length > 0) {
				elements.each(function (i, el) {
					var $el = $(el);

					var doResize = function (el) {
						el.find('.inner-acc').css('width', el.width() / 2);
					};
					doResize($el);

					$(window).on('debouncedresize', function (event) {
						doResize($el);
					});

					// If is positioned into a tab, refresh.
					var $parentTab = elements.closest('.tabbable');
					if ($parentTab.length) {
						$parentTab.on('shown.bs.tab', function (event) {
							doResize($(event.target).attr('href'));
						});
					}

				});
			}
		},

		enable_portfolio_sortable: function (content) {

			var wpkznSelector = $(content).find(".ptf-stb-thumbs");
			if (wpkznSelector.length === 0) { return; }

			function getHashFilter() {
				var hash = location.hash;
				if (hash) {
					return decodeURIComponent(hash);
				}
				return false;
			}

			$(wpkznSelector).each(function (index, el) {

				var $el = $(el),
					$container = $el.closest('.kl-ptfsortable'),
					sortbyList = $container.find('.ptf-stb-sortby'),
					sortBy = $container.is('[data-sortby]') ? $container.attr('data-sortby') : 'date',
					sortDirList = $container.find('.ptf-stb-direction'),
					sortAscending = $container.is('[data-sortdir]') && $container.attr('data-sortdir') == 'asc' ? true : false,
					layoutMode = $el.is('[data-layout-mode]') ? $el.attr('data-layout-mode') : 'masonry',
					$ptNav = $container.find('.ptf-stb-ptfnav');

				var hashFilter = getHashFilter(),
					theFilter;

				if (!hashFilter) {
					theFilter = $ptNav.find('li.current a').attr('data-filter');
				}
				// if hash found
				else {
					var hashItem = $ptNav.find('a[href="' + hashFilter + '"]');
					theFilter = hashItem.attr('data-filter');
					hashItem.parent().siblings('li').removeClass('current');
					hashItem.parent().addClass('current');
				}

				$el.imagesLoaded(function () {
					$el.isotope({
						itemSelector: ".item",
						// animationEngine: "jquery",
						animationOptions: {
							duration: 250,
							easing: "easeOutExpo",
							queue: false
						},
						layoutMode: layoutMode,
						filter: theFilter,
						sortBy: sortBy,
						sortAscending: sortAscending,
						getSortData: {
							name: '.name',
							date: '[data-date] parseInt'
						},
						isInitLayout: false
					});

					$el.isotope('on', 'arrangeComplete', function () {
						$el.addClass('isotope-initialized');
					});

					$el.isotope();
				});

				//#1 Filtering
				$ptNav.on('click', '.kl-ptfsortable-nav-link', function (e) {
					var $t = $(this);

					if ($t.attr('href') === '#') { e.preventDefault(); }

					$ptNav.children('li').removeClass('current');
					$t.parent().addClass('current');
					$el.isotope({ filter: $t.data('filter') });
					$el.isotope('updateSortData').isotope();
				});

				//#! Sorting (name | date)
				var b_elements = sortbyList.find('li a');
				if (b_elements && b_elements.length > 0) {
					b_elements.removeClass('selected');
					$.each(b_elements, function (index, element) {
						var t = $(element),
							csb = t.data('optionValue');
						if (csb == sortBy) {
							t.addClass('selected');
						}
					});

					b_elements.on('click', function (e) {
						e.preventDefault();
						b_elements.removeClass('selected');
						$(this).addClass('selected');
						sortBy = $(this).data('optionValue');
						$el.isotope({ sortBy: $(this).data('optionValue') });
						$el.isotope('updateSortData').isotope();
					});

				}

				//#! Sorting Direction (asc | desc)
				var c_elements = sortDirList.find('li a');
				if (c_elements && c_elements.length > 0) {
					c_elements.removeClass('selected');
					$.each(c_elements, function (index, element) {
						var t = $(element),
							csv = t.data('option-value');

						if (csv == sortAscending) {
							t.addClass('selected');
						}

					});

					c_elements.on('click', function (e) {
						e.preventDefault();
						c_elements.removeClass('selected');
						$(this).addClass('selected');
						$el.isotope({ sortAscending: $(this).data('option-value'), sortBy: sortBy });
						$el.isotope('updateSortData').isotope();
					});

				}
			});

		},

		enable_gridphotogallery: function (content) {
			var gridPhotoGallery = content.find('.gridPhotoGallery:not(.stop-isotope)');
			if (typeof ($.fn.isotope) != 'undefined') {
				gridPhotoGallery.each(function (i, el) {
					var $el = $(el),
						itemWidth = Math.floor($(el).width() / $el.attr('data-cols')),
						layoutType = $el.is('[data-layout]') ? $el.attr('data-layout') : 'masonry';

					// Find better fix when JS files can be loaded dynamically
					// in the future updates
					if ($('body').hasClass('zn_pb_editor_enabled')) {
						if (layoutType == 'packery') {
							layoutType = 'masonry';
						}
					}

					$el.imagesLoaded(function () {
						$el.isotope({
							layoutMode: layoutType,
							itemSelector: '.gridPhotoGallery__item',
							layoutType: {
								columnWidth: '.gridPhotoGallery__item--sizer',
								gutter: 0
							},
							isInitLayout: false
						});

						$el.isotope('on', 'arrangeComplete', function () {
							$el.addClass('isotope-initialized');
						});

						$el.isotope();
					});
				});
			}
		},

		enable_nivo_slider: function (content) {
			var elements = $('.nivoslider .nivoSlider');
			if (elements && elements.length) {
				$.each(elements, function (i, e) {
					var slider = $(e),
						transition = slider.attr('data-transition'),
						autoslide = slider.attr('data-autoslide') != '1' ? true : false,
						pausetime = slider.attr('data-pausetime');
					if (typeof ($.fn.nivoSlider) != 'undefined') {
						slider.nivoSlider({
							effect: transition,
							boxCols: 8,
							boxRows: 4,
							slices: 15,
							animSpeed: 500,
							pauseTime: pausetime,
							startSlide: 0,
							directionNav: 1,
							controlNav: 1,
							controlNavThumbs: 0,
							pauseOnHover: 1,
							manualAdvance: autoslide,
							afterLoad: function () {
								/* slideFirst() */
								setTimeout(function () {
									slider.find('.nivo-caption').animate({ left: 20, opacity: 1 }, 500, 'easeOutQuint');
								}, 1000);
							},
							beforeChange: function () {
								/* slideOut() */
								slider.find('.nivo-caption').animate({ left: 120, opacity: 0 }, 500, 'easeOutQuint');
							},
							afterChange: function () {
								/* slideIn() */
								slider.find('.nivo-caption').animate({ left: 20, opacity: 1 }, 500, 'easeOutQuint');
							}
						});
					}
				});
			}
		},


		enable_wow_slider: function (content) {
			var elements = content.find('.th-wowslider');
			if (elements && elements.length) {
				$.each(elements, function (i, e) {
					var self = $(e);
					if (typeof ($.fn.wowSlider) != 'undefined') {
						self.imagesLoaded(function(){
							self.wowSlider({
								effect: self.attr('data-transition'),
								duration: 900,
								delay: self.is('[data-timeout]') ? parseInt( self.attr('data-timeout') ) : 3000,
								width: 1170,
								height: 470,
								cols: 6,
								autoPlay: self.attr('data-autoplay'),
								stopOnHover: true,
								loop: true,
								bullets: true,
								caption: true,
								controls: true,
								captionEffect: "slide",
								logo: "image/loading_light.gif",
								images: 0,
								onStep: function () {
									self.addClass('transitioning');
									setTimeout(function () {
										self.removeClass('transitioning');
									}, 1400);

								}
							});
						});
					}
				});
			}
		},

		enable_static_weather: function (content) {

			var elements = content.find('.sc__weather');

			if (elements && elements.length) {
				$.each(elements, function (i, e) {
					var self = $(e),
						tunit,
						temp,
						loc = self.attr('data-location') ? self.attr('data-location') : 'Chicago',
						unit = self.attr('data-unit') ? self.attr('data-unit') : 'imperial',
						apikey = self.attr('data-apikey') ? atob( self.attr('data-apikey') ) : '';

					if ( loc !== '' && unit !== '' && apikey !== '' ) {
						console.log(elements)
						$.ajax({
							url: "https://api.openweathermap.org/data/2.5/forecast?q=" + loc + "&units=" + unit +"&appid=" + apikey,
							type: "GET",
							dataType: "jsonp",
							success: function(data){

								var html = '<ul class="scw_list clearfix">';
								for ( var i=0; i<=data.list.length; i+=8) {
									var currentItem = data.list[i];
									if (currentItem != null) {
										var d = new Date(currentItem.dt_txt),
										days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
										currentDay = days[d.getDay()];
										if ( unit === 'imperial' ) {
											tunit = 'F';
											temp = parseInt((parseInt(currentItem.main.temp) - 32) * 5 / 9).toString();
											temp += '&deg;<span class="uppercase">C</span>';
										} else if ( unit === 'metric' ) {
											tunit = 'C';
											temp = parseInt((parseInt(currentItem.main.temp) * 9) / 5 + 32).toString();
											temp += '&deg;<span class="uppercase">F</span>';
										}

										html += '<li><i class="wt-icon wt-icon wt-icon-' + currentItem.weather[0].icon + '"></i>';
										html += '<div class="scw__degs">';
										html += '<span class="scw__high">' + parseInt(currentItem.main.temp_max) + '&deg;<span class="uppercase">' + tunit + '</span></span>';
										html += '<span class="scw__low">' + parseInt(currentItem.main.temp_min) + '</span>';
										html += '</div>';
										html += '<span class="scw__day">' + znLocalizeDay(currentDay) + '</span>';
										html += '<span class="scw__temp">' + temp + '</span>';
										html += '</li>';
									}

								}
								html += '</ul>';
								html += '</div>';

								jQuery(self).html(html);
							},
							error: function (error) {
								jQuery(self).html('<p>' + error + '</p>');
								console.warn('Some problems: ' + error);
							}
						});
					}
				});
			}
		},

		enable_diagram: function (content) {

			var diagram_el = content.find('.kl-skills-diagram');

			if (diagram_el && diagram_el.length) {
				diagram_el.each(function (index, el) {
					if (typeof diagramElement != 'undefined') {
						diagramElement.init(el);
					}
				});
			}

		},

		enable_services: function (content) {

			var elements = content.find('.services_box--boxed');

			if (elements && elements.length) {
				elements.each(function (index, el) {

					var $el = $(el);

					var doBoxes = function (el) {
						// see how tall the box is and add an extra 30px
						el.find('.services_box__list').css('padding-top', el.height() + 30);
					};
					doBoxes($el);

					$(window).on('debouncedresize', function (event) {
						doBoxes($el);
					});

					// If is positioned into a tab, refresh.
					var $parentTab = elements.closest('.tabbable');
					if ($parentTab.length) {
						$parentTab.on('shown.bs.tab', function (event) {
							doBoxes($(event.target).attr('href'));
						});
					}

				});

			}
		},

		enable_scrollspy: function (content) {

			var fw = this,
				url = location.href.replace(/#.*/, ''),
				topMenu = $('#main-menu, #site-chaser, .elm-custommenu, #zn-res-menu'),
				menuItems = topMenu.find(".main-menu-item > a");

			var scrollItems = menuItems.map(function () {
				var href = $(this).is('[href]') ? $(this).attr('href').replace(url, '') : '';
				var item = $($(this.hash.replace(/([ ;?%&,.+*~\':"!^$[\]()=>|\/@])/g, '\\$1')));
				if (item.length) {
					return item;
				}
			});

			if (!scrollItems.length) return;

			$(window).on('scroll', fw.helpers.debounce(function () {

				var fromTop = window.pageYOffset || window.scrollTop || 0,
					lastId = false,
					the_offset = getTopOffset() - 1,
					smaller = 0,
					higher = 0;

				// console.clear();
				scrollItems.each(function (i, el) {
					var current_offset = Math.max(0, $(el).offset().top + the_offset);

					// console.log({
					// 	'A': 'Current: ' + current_offset ,
					// 	'B': "Top Offset: " + $(el).offset().top ,
					// 	'C': 'Offset: ' + the_offset,
					// 	'D': 'From Top: ' + fromTop,
					// 	'G': "Higher: " + higher
					// });

					if (current_offset <= fromTop && current_offset >= higher) {
						smaller = this;
						higher = current_offset;
					}

				});

				// Get the id of the current element
				var id = smaller && $(smaller).length ? smaller[0].id : "zn_invalid_id";

				if (lastId !== id) {
					lastId = id;
					// Check if the menu has such an item
					if (topMenu.find('a[href*="#' + id + '"]').length > 0 && id != 'zn_invalid_id') {
						var freshTopMenu = $('#main-menu, #site-chaser, .elm-custommenu, #zn-res-menu');
						$('li.active', freshTopMenu).removeClass("current_page_item current-menu-item active");
						$('a[href*="#' + id + '"]', freshTopMenu).parent().addClass("current_page_item current-menu-item active");
					}
				}

			}, 50)).trigger('scroll');

		},

		enable_tooltips: function (content) {
			// activate tooltips
			var tooltips = content.find('[data-toggle="tooltip"], [data-rel="tooltip"]');
			if (tooltips && tooltips.length > 0) {
				tooltips.tooltip();
			}
		},

		enable_customMenuDropdown: function (content) {

			var ddmenu = content.find('.elm-custommenu--dd');
			if (ddmenu.length) {
				ddmenu.each(function(){
					var $currentMenu = $(this);

					var $ddmenu_pick = $currentMenu.find('.elm-custommenu-pick');
					$ddmenu_pick.on('click', function (event) {
						$currentMenu.toggleClass('is-opened');
					});
					// Close on click outside
					$(document).on('click', function (e) {
						if ($currentMenu.hasClass('is-opened')) {
							$currentMenu.removeClass('is-opened');
						}
					});
					$currentMenu.on('click', function (event) {
						event.stopPropagation();
					});

				});
			}
		},

		customMenuElm_toggleSubmenus: function (content) {

			var tgmenu = content.find('.elm-custommenu-toggleSubmenus .elm-cmlist');

			if (tgmenu.length) {

				tgmenu.find('.menu-item-has-children > a').on('click', function (e) {
					e.preventDefault();

					var $el = $(this),
						$parent = $el.parent('.menu-item-has-children');
					$submenu = $el.next('ul.sub-menu');

					if ($submenu.is(':visible')) {
						if ($submenu.is(':animated')) return;
						$submenu.slideUp({
							start: function () {
								$parent.removeClass('is-active');
							}
						});
					}
					else {
						if ($submenu.is(':animated')) return;
						$submenu.slideDown({
							start: function () {
								$parent.addClass('is-active');
							}
						});
					}
				});
			}
		},



		general_wc_stuff: function (content) {

			// Toggle review form in WC product page (tabs)
			content.find('.prodpage-style2 #reviews .comment-respond .comment-reply-title, .prodpage-style3 #reviews .comment-respond .comment-reply-title').each(function (index, el) {
				$(el).on('click', function () {
					$(el).toggleClass('opened-form');
					$(el).next('.comment-form').toggleClass('show-form');
				});
			});
		},

		init_skill_bars: function (scope) {

			var fw = this;
			var skillBarContainers = $(scope).find('.skills_wgt');
			// Set transitions for main containers
			var liElements = $('li', skillBarContainers);
			if (liElements && liElements.length > 0) {

				// Skill bars
				var cssRules = '';

				$.each(skillBarContainers, function (i, e) {
					var container = $(e),
						loaded = false;

					var doBars = function () {

						var start = 0.2;
						var skillBars = $('.skill-bar', container);

						$.each(skillBars, function (j, v) {
							var element = $(v);
							var percentage = element.data('loaded'),
								$i = $('.skill-bar-inner', element);

							$(container).addClass('started');

							/* increment transition step */
							start += 0.1;
							$i.css('-webkit-transition-delay', start + 's');
							$i.css(' transition-delay: ' + start + 's');
							$i.css('width', percentage + '%'); // Set the width
						});
					};

					if (!loaded) {
						if (fw.helpers.isInViewport(container[0])) {
							doBars();
							loaded = true;
						}
						$(window).on('scroll', fw.helpers.debounce(function () {
							if (fw.helpers.isInViewport(container[0])) {
								doBars();
								loaded = true;
							}
						}, 500));
					}
				});
			}
		},

		general_stuff: function (content) {

			// // Fallback for IE's missing object-fit
			if (typeof Modernizr == 'object') {
				if (!Modernizr.objectfit) {
					$.each(['cover', 'contain'], function (index, el) {
						$('.' + el + '-fit-img').each(function () {
							var $container = $(this),
								imgUrl = $container.prop('src'),
								imgClasses = $container.prop('class');
							if (imgUrl) {
								$container.replaceWith('<div class="' + imgClasses + ' ' + el + '-fit-img-fallback" style="background-image:url(' + imgUrl + ');"></div>');
							}
						});
					});
				}
			}

			// Mobile logo
			var logo_img = content.find('.site-logo-img');
			if (logo_img.length > 0 && logo_img.is('[data-mobile-logo]')) {
				var initial_src = logo_img.attr('src');
				$(window).on('debouncedresize', function () {
					if (window.matchMedia("(max-width: 767px)").matches) {
						logo_img.attr('src', logo_img.attr('data-mobile-logo'));
					} else {
						logo_img.attr('src', initial_src);
					}
				}).trigger('debouncedresize');
			}

			// Enable Hidden panel through menu
			content.find('.show-top-hidden-panel > .main-menu-link').on('click', function (event) {
				event.preventDefault();
				$('#sliding_panel').addClass('is-opened');
			});


			/*
				Column Sticky
			 */
			if (window.matchMedia("(min-width: 992px)").matches) {

				var stickyCols = content.find('.znColumnElement-stickyCol[data-sticky-col]');
				if (stickyCols.length) {
					stickyCols.each(function (index, el) {

						if (is_undefined(scrollMagicController)) return;

						var $el = $(el),
							params = IsJsonString($el.attr("data-sticky-col")) ? JSON.parse($el.attr("data-sticky-col")) : {},
							distance = !is_undefined(params.distance) && params.distance !== '' ? params.distance : 100,
							offset = !is_undefined(params.offset) && params.offset !== '' ? params.offset : 0;
						// apply extra offset (if any)
						offset = getTopOffset(offset);

						// build scenes
						var $scene = new ScrollMagic.Scene({ triggerElement: $el[0], triggerHook: "onLeave", duration: distance, offset: offset });
						$scene.setPin($el[0]);
						$scene.addTo(scrollMagicController);

						// remove scene if resized to mobile
						$(window).on('debouncedresize', function () {
							if (window.matchMedia("(max-width: 991px)").matches) {
								$scene.removePin(true).enabled(false);
							} else {
								if (!$scene.enabled()) {
									$scene.setPin($el[0]).enabled(true);
								}
							}
						});
					});
				}
			}
		},

	};

	// Helper Functions
	function IsJsonString(a) {
		try {
			JSON.parse(a);
		} catch (e) {
			return false;
		}
		return true;
	}
	function is_null(a) {
		return (a === null);
	}
	function is_undefined(a) {
		return (typeof a == 'undefined' || a === null || a === '' || a === 'undefined');
	}
	function is_number(a) {
		return ((a instanceof Number || typeof a == 'number') && !isNaN(a));
	}
	function is_true(a) {
		return (a === true || a === 'true');
	}
	function is_false(a) {
		return (a === false || a === 'false');
	}

	var getTopOffset = function (offset) {

		var theOffset = offset || 0;

		if ($('.chaser').length > 0) {
			theOffset -= $('.chaser').outerHeight();
		}
		if ($('#header.header--sticky').length > 0) {
			theOffset -= $('#header.header--is-sticked').outerHeight();
		}
		if ($('#header.header--fixed').length > 0) {
			theOffset -= $('#header.header--fixed').outerHeight();
		}

		// Custom Offset
		if (typeof ZnThemeAjax.top_offset_tolerance != 'undefined' && ZnThemeAjax.top_offset_tolerance != '') {
			theOffset = parseFloat(ZnThemeAjax.top_offset_tolerance);
		}

		// Must be after
		if ($('#wpadminbar').length > 0) {
			theOffset -= $('#wpadminbar').outerHeight();
		}

		// console.log(theOffset)

		return theOffset;

	};

	var dnow = Date.now || function () {
		return new Date().getTime();
	};

	var throttle = function (func, wait, options) {
		var timeout, context, args, result;
		var previous = 0;
		if (!options) options = {};

		var later = function () {
			previous = options.leading === false ? 0 : dnow();
			timeout = null;
			result = func.apply(context, args);
			if (!timeout) context = args = null;
		};

		var throttled = function () {
			var now = dnow();
			if (!previous && options.leading === false) previous = now;
			var remaining = wait - (now - previous);
			context = this;
			args = arguments;
			if (remaining <= 0 || remaining > wait) {
				if (timeout) {
					clearTimeout(timeout);
					timeout = null;
				}
				previous = now;
				result = func.apply(context, args);
				if (!timeout) context = args = null;
			} else if (!timeout && options.trailing !== false) {
				timeout = setTimeout(later, remaining);
			}
			return result;
		};

		throttled.cancel = function () {
			clearTimeout(timeout);
			previous = 0;
			timeout = context = args = null;
		};

		return throttled;
	};


	// Helper vars
	var $w = $(window),
		$body = $('body'),
		hasTouch = (typeof Modernizr == 'object' && Modernizr.touchevents) || false,
		hasTouchMobile = hasTouch && window.matchMedia("(max-width: 1024px)").matches,
		ua = navigator.userAgent,
		isMac = /^Mac/.test(navigator.platform),
		is_mobile_ie = -1 !== ua.indexOf("IEMobile"),
		is_firefox = -1 !== ua.indexOf("Firefox"),
		is_safari = /^((?!chrome|android).)*safari/i.test(ua),
		isAtLeastIE11 = !!(ua.match(/Trident/) && !ua.match(/MSIE/)),
		isIE11 = !!(ua.match(/Trident/) && ua.match(/rv[ :]11/)),
		isIE10 = navigator.userAgent.match("MSIE 10"),
		isIE9 = navigator.userAgent.match("MSIE 9"),
		is_EDGE = /Edge\/12./i.test(ua),
		is_pb = !is_undefined($.ZnPbFactory);

	if (is_EDGE) {
		$body.addClass('is-edge');
	}
	if (isIE11) {
		$body.addClass('is-ie11');
	}
	if (is_safari) {
		$body.addClass('is-safari');
	}

	// Init ScrollMagic controller
	var scrollMagicController = typeof ScrollMagic !== 'undefined' ? new ScrollMagic.Controller() : undefined;

	//////  WINDOW LOAD   //////
	$(window).on('load', function () {
		// REMOVE PRELOADER

		var preloader = $('#page-loading');
		if (preloader.length > 0) {
			setTimeout(function () {
				preloader.fadeOut("slow", function () {
					preloader.remove();
				});
			}, (typeof window.preloaderDelay != 'undefined' ? window.preloaderDelay : 0));
		}

		// Ref #1562 - Firefox only
		if (is_firefox && window.location.hash.length > 0) {
			var hashOffset = $(window.location.hash).offset();
			if (typeof hashOffset != 'undefined') {
				$('body,html').animate({ scrollTop: getTopOffset(hashOffset.top) }, 600, 'easeOutCubic');
			}
		}

	});
	////// END WINDOW LOAD

	$(document).ready(function () {

		// Call this on document ready
		$.themejs = new $.ZnThemeJs();

		// prevent clicking on cart button
		// for touch screens
		if (hasTouchMobile) {
			$('a[href="#"]').on('click', function (e) {
				e.preventDefault();
			});
		}

		$('body').bind('added_to_cart', function (evt, ret) {

			// console.log( evt );
			if (!is_undefined(ret.zn_added_to_cart) && ret.zn_added_to_cart.length > 0) {
				var modal = $(ret.zn_added_to_cart);
				$('body').append(modal);

				// FadeOut and Close the modal after 5 seconds
				setTimeout(function () {
					$(modal).fadeOut('fast', 'easeInOutExpo', function () {
						$(this).remove();
					});
				}, 3000);

				$(modal).fadeIn('slow', 'easeInOutExpo', function () {
					modal.find('.kl-addedtocart-close').click(function (e) {
						e.preventDefault();
						$(modal).fadeOut('fast', 'easeInOutExpo', function () {
							$(this).remove();
						});
					});
				});
			}
		});

		// Check if Top Sliding panel is opened and close it on scroll
		window.didScroll = false;
		$(window).on('scroll', function () {
			if (!window.didScroll) {
				// Close Sliding panel when scrolling the page (in sticky header mode)
				var sliding_panel = $('.kl-sticky-header #sliding_panel');
				if (sliding_panel.hasClass('is-opened')) {
					sliding_panel.removeClass('is-opened');
					$('#open_sliding_panel').removeClass('is-toggled');
				}
				window.didScroll = true;
			}
		});

		// LOGIN FORM
		var zn_form_login = $('.zn_form_login');
		zn_form_login.each(function (index, el) {
			$(el).on('submit', function (event) {
				event.preventDefault();

				var form = $(this),
					warning = false,
					button = $('.zn_sub_button', this),
					values = form.serialize(),
					is_login_form = form.hasClass('znhg-ajax-login-form');

				button.addClass('zn_blocked');

				$('input', form).each(function (i, el) {
					var $el = $(el);
					if (!$el.val()) {
						warning = true;
						$el.parent('.form-group').addClass('fg-input-invalid');
					} else {
						$el.parent('.form-group').removeClass('fg-input-invalid');
					}
				});

				if (warning) {
					button.removeClass('zn_blocked');
					return false;
				}

				// Login the user
				$.post(zn_do_login.ajaxurl, values, function (response) {

					// Re-activate the login button
					button.removeClass('zn_blocked');
					var result_block = $('.zn_form_login-result', form);

					// If the user didn't successfully logged in
					if (!response.success) {
						result_block.html('<div class="zn-notification zn-notification--error">' + response.data.message + '</div>');
					}
					else {
						// Reset the inputs
						$('input[type="text"], input[type="password"]', form).val('');

						// check to see if we have a message to display
						if (response.data.message) {
							result_block.html('<div class="zn-notification zn-notification--success">' + response.data.message + '</div>');
						}

						if (result_block.find('.kl-login-box').length) {
							result_block.find('.kl-login-box').magnificPopup({ type: 'inline', closeBtnInside: true, showCloseBtn: true, mainClass: 'mfp-fade mfp-bg-lighter' });
						}

						/**
						 * We need to do some etra work if this is a login form
						 */
						if (is_login_form) {
							// close the login modal
							$.magnificPopup.close();

							// Redirect the user
							if (response.data.redirect_url.length) {
								window.location = response.data.redirect_url;
							}
							else {
								window.location.reload();
							}
						}

					}


				});
			});
		});

		// Logout user
		$('.zn-logoutBtn>a').on('click', function (e) {
			e.preventDefault();
			if (!is_undefined(ZnThemeAjax.logout_url)) {
				window.location = ZnThemeAjax.logout_url;
			}
		});

		// LOST PASSWORD
		var zn_form_lost_pass = $('.zn_form_lost_pass');
		zn_form_lost_pass.on('submit', function () {
			event.preventDefault();

			var form = $(this),
				warning = false,
				button = $('.zn_sub_button', this),
				values = form.serialize() + '&ajax_login=true';

			button.addClass('zn_blocked');

			$('input', form).each(function (i, el) {
				var $el = $(el);
				if (!$el.val()) {
					warning = true;
					$el.parent('.form-group').addClass('fg-input-invalid');
				} else {
					$el.parent('.form-group').removeClass('fg-input-invalid');
				}
			});

			if (warning) {
				button.removeClass('zn_blocked');
				return false;
			}

			$.ajax({
				url: form.attr('action'), data: values, type: 'POST', cache: false, success: function (resp) {
					var data = $(document.createElement('div')).html(resp);
					var message;
					if ($('#login_error', data).length) {
						// We have an error
						var error = $('#login_error', data);
						$('.zn_form_login-result', form).html(error);
					}
					else if ($('.message', data).length) {
						message = $('.message', data);
						$('.zn_form_login-result', form).html(message);
					}
					else if ($('.woocommerce-message', data).length) {
						message = $('.woocommerce-message', data);
						$('.zn_form_login-result', form).html(message);
						// console.log('woocommerce message');
					}
					else if ($('.woocommerce-error', data).length) {
						message = $('.woocommerce-error', data);
						$('.zn_form_login-result', form).html(message);
						// console.log('woocommerce message');
					}
					else {
						jQuery.magnificPopup.close();
						window.location = $('.zn_login_redirect', form).val();
					}
					button.removeClass('zn_blocked');
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('.zn_form_login-result', form).html(errorThrown);
				}
			});
		});


		(function () {
			if ($.isFunction($.fn.wc_product_gallery) && typeof wc_single_product_params !== 'undefined') {

				var $galleryContainer = $('.zn-wooGalleryThumbs-summary'),
					$dots = $galleryContainer.find('.woocommerce-product-gallery__image'),
					flexSliderInstance = $('.zn-wooSlickGallery--productStyle3').data('flexslider');

				var flexSliderEnabled = $.isFunction($.fn.flexslider) && wc_single_product_params.flexslider_enabled;

				if (!flexSliderEnabled) {
					var args = {
						flexslider_enabled: false,
						zoom_enabled: false,
						photoswipe_enabled: typeof PhotoSwipe !== 'undefined' && wc_single_product_params.photoswipe_enabled
					};
					$galleryContainer.wc_product_gallery(args);
				}

				if ($dots.length > 0) {

					//  Allow clicking on the summary thumbnails
					$dots.on('click', function (e) {

						if (typeof flexSliderInstance !== 'undefined') {

							e.preventDefault();
							e.stopPropagation();

							// Set active classes
							$dots.removeClass('slick-active');
							$(this).addClass('slick-active');

							// Change the active slide
							var index = $(this).index();
							flexSliderInstance.flexAnimate(index + 1);
						}
					});

				}
			}

		})();


		// --- search panel
		var searchBtn = $('#search .searchBtn'),
			searchPanel = searchBtn.next(),
			searchP = searchBtn.parent();
		if (searchBtn && searchBtn.length > 0) {
			searchBtn.on('click', function (e) {
				e.preventDefault();
				var self = $(this);
				var target = $('span:first-child', self);
				if (!self.hasClass('active')) {
					self.addClass('active');
					target.toggleClass('glyphicon-remove');
					searchPanel.addClass('panel-opened');
				}
				else {
					self.removeClass('active');
					target.toggleClass('glyphicon-remove');
					searchPanel.removeClass('panel-opened');
				}
			});
			if (searchP.hasClass('headsearch--def')) {
				$(document).click(function (e) {
					var searchBtn = $('#search .searchBtn');
					searchBtn.removeClass('active');
					searchBtn.next().removeClass('panel-opened');
					$('span:first-child', searchBtn).removeClass('glyphicon-remove').addClass('glyphicon-search');
				});
			}
			searchP.click(function (event) {
				event.stopPropagation();
			});
		}

		// --- end search panel

		/* scroll to top */
		var toTop = $("#totop");
		if (toTop && toTop.length > 0) {
			toTop.on('click', function (e) {
				e.preventDefault();
				$('body,html').animate({ scrollTop: 0 }, 600, 'easeOutCubic');
			});
		}
		// --- end scroll to top

		/* Tonext button - Scrolls to next block (used for fullscreen slider) */
		$(".js-tonext-btn").on('click', function (e) {

			if (hasTouchMobile) return;

			e.preventDefault();
			var endof = $(this).attr('data-endof') ? $(this).attr('data-endof') : false,
				dest = 0;

			if (endof)
				dest = $(endof).height() + $(endof).offset().top;

			$('html,body').animate({ scrollTop: getTopOffset(dest) }, 1000, 'easeOutCubic');
		});


		/**
		 * Returns the top offset for a specific element considering various elements like WP admin bar or sticky header
		 *
		 * @param {target} Dom element that needs to be scrolled to
		 */
		function znCalculateScrollOffset($target){
			return $target.offset().top + getTopOffset();
		}

		/**
		 * will scroll to a specific element on the page.
		 *
		 * @param {target} Dom element that needs to be scrolled to
		 */
		function znAnimateBodyScroll($target){

			var offset = znCalculateScrollOffset($target),
				cachedStickyHeader = $.themejs.isHeaderStick;

			// Don't animate if we are already there
			if( Math.round( jQuery('html,body').scrollTop() ) === Math.round( offset ) ){
				return;
			}

			$('html,body').stop().animate({
				scrollTop: offset
			}, {
				duration: 1000,
				easing: 'easeOutCubic',
				step: function(){
					if( cachedStickyHeader != $.themejs.isHeaderStick ){
						cachedStickyHeader = $.themejs.isHeaderStick;
						znAnimateBodyScroll($target);
					}
				}
			});
		}


		/* Smooth scroll to id */
		$("body").on('click', "a[data-target='smoothscroll'][href*='#']:not([href='#']), .main-menu a.main-menu-link[href*='#']:not([href='#']), .nav-with-smooth-scroll a[href*='#']:not([href='#']) ", function (e) {

			var url = $(this).attr('href'),
				href = url.substring(url.indexOf('#'));

			if (typeof href !== 'undefined' && href.indexOf("#") != -1 && $(href).length > 0) {

				e.preventDefault();

				//go to destination
				if ($(href).length) {

					znAnimateBodyScroll($(href));

					// if supported by the browser we can even update the URL.
					if (window.history && window.history.pushState) {
						history.pushState("", document.title, href);
					}
				}
			} else {
				console.log('Not a valid link');
			}
		});

		/**
		 * Smoothscroll options
		 */
		(function () {
			if (typeof ZnSmoothScroll != 'undefined') {
				if (!hasTouchMobile && !is_mobile_ie && !is_pb) {

					var smType = ZnSmoothScroll.type || 'no',
						smOptions = {};

					smOptions.touchpadSupport = ZnSmoothScroll.touchpadSupport == 'no' ? true : false;

					switch (smType) {
						// Ultra Fast
						case "0.1":
							smOptions.animationTime = 150;
							smOptions.stepSize = 70;
							break;
						// Fast
						case "0.25":
							smOptions.animationTime = 300;
							smOptions.stepSize = 70;
							break;
						// Moderate
						case "yes":
							smOptions.animationTime = 500;
							smOptions.stepSize = 70;
							break;
						// Slow speed
						case "0.75":
							smOptions.animationTime = 700;
							smOptions.stepSize = 70;
							break;
						// Super Slow speed
						case "1":
							smOptions.animationTime = 1000;
							smOptions.stepSize = 50;
							smOptions.accelerationMax = 1;
							break;
						// Snail speed
						case "1.6":
							smOptions.animationTime = 2000;
							smOptions.stepSize = 68;
							smOptions.accelerationMax = 1;
							break;
					}

					// // Pulse (less tweakable)
					// // ratio of "tail" to "acceleration"
					// smOptions.pulseAlgorithm   = true;
					// smOptions.pulseScale       = 4;
					// smOptions.pulseNormalize   = 1;
					// // Acceleration
					// smOptions.accelerationDelta = 50;  // 50
					// smOptions.accelerationMax   = 1;   // 3

					SmoothScroll(smOptions);

				}
			}
		})();

		/**
		 * Add a modifier class to an element, upon user defined scrolling target element or number
		 * @forch 					Is the point where, upon scrolling, the class modifier is added. Default is "1".
		 * @targetElementForClass	Target element which will have the modifier class. Default is "body".
		 * @classForVisibleState	Modifier class name. Default is "is--visible".
		 * usage: <tag class="js-scroll-event" data-forch="100" or data-forch="#some_id" data-target="#header" data-visibleclass="is--scrolling" data-hiddenclass="not--scrolling"></tag>
		 */
		$(".js-scroll-event").each(function (index, el) {

			var $el = $(el),
				targetElementForClass = $el.is('[data-target]') ? $el.attr("data-target") : $el,
				classForVisibleState = $el.is('[data-visibleclass]') ? $el.attr("data-visibleclass") : 'is--visible',
				classForHiddenState = $el.is('[data-hiddenclass]') ? $el.attr("data-hiddenclass") : '';

			var forch = function () {
				var f = 1,
					dataForch = $el.is('[data-forch]') ? $el.attr('data-forch') : '';
				// check if data-forch attribute is added
				if (typeof dataForch !== 'undefined' && dataForch !== '') {
					if (!isNaN(parseFloat(dataForch)) && isFinite(dataForch)) {
						f = parseInt(dataForch);
					}
					else {
						var specifiedElement = $(dataForch).first();
						if (specifiedElement && specifiedElement.length > 0) {
							f = specifiedElement.offset().top;
						}
					}
				}
				return f;
			};

			if (is_undefined(scrollMagicController)) return;

			var scene = new ScrollMagic.Scene({
				offset: forch()
			})
				.setClassToggle($(targetElementForClass)[0], classForVisibleState)
				.addTo(scrollMagicController);

			if (classForHiddenState) {
				$(targetElementForClass).addClass(classForHiddenState);
				var outscene = new ScrollMagic.Scene({
					offset: 0,
					duration: forch()
				})
					.setClassToggle($(targetElementForClass)[0], classForHiddenState)
					.addTo(scrollMagicController);
			}
		});

		// Check portfolio content
		$.each($('.portfolio-item-desc-inner-compacted'), function (i, el) {
			var $el = $(el),
				collapseAt = $el.is('[data-collapse-at]') && $el.attr('data-collapse-at') ? $el.attr('data-collapse-at') : 150;
			if ($el.outerHeight() < parseInt(collapseAt)) {
				$el.parent('.portfolio-item-desc').addClass('no-toggle');
			}
		});

		if (window.matchMedia("(min-width: 992px)").matches) {

			// Check portfolio content
			$.each($('.portfolio-item-content.affixcontent'), function (i, el) {

				var $el = $(el);
				var portfolio_page = $el.closest('.hg-portfolio-item');

				portfolio_page.imagesLoaded(function () {

					if (is_undefined(scrollMagicController)) return;

					var duration = portfolio_page.outerHeight() - $el.outerHeight();
					var $scene = new ScrollMagic.Scene({ triggerElement: portfolio_page[0], triggerHook: "onLeave", duration: duration, offset: getTopOffset('-30') });
					$scene.setPin($el[0]);
					$scene.addTo(scrollMagicController);

					$(window).on('debouncedresize', function () {
						if (window.matchMedia("(max-width: 991px)").matches) {
							$scene.removePin(true).enabled(false);
						} else {
							if (!$scene.enabled()) {
								$scene.setPin($el[0]).enabled(true);
							}
						}
					});
				});
			});
		}

	});

	// Keep the last tab active
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

		var $element = $(e.target).closest('.hg-tabs'),
			tabTarget = null,
			elementId = null;

		// check if the save active tab option is active
		if( $element.data('tabs-history') === 1 ) {
			tabTarget = $(this).attr('href');
			elementId = $element.attr('id');

			if (elementId) {
				var savedTabsStore = JSON.parse( localStorage.getItem('znkl_savedTabs') ) || {};
				savedTabsStore[elementId] = tabTarget
				savedTabsStore = JSON.stringify(savedTabsStore)
				localStorage.setItem('znkl_savedTabs', savedTabsStore);
			}
		}

		// trigger tab refreshing
		var target = $($(e.target).attr('href'));
		if (typeof target != 'undefined') {
			// trigger event
			$(window).trigger('zn_tabs_refresh');
			// do slicks
			if (target.find('.slick-slider').length) {
				target.find('.slick-slider').slick('setPosition');
			}
			// do isotopes
			if (target.find('.isotope-initialized').length) {
				target.find('.isotope-initialized').isotope('layout');
			}
		}
	});

	// trigger debounced resize on accordions
	$(document).on("shown.bs.collapse hidden.bs.collapse", ".collapse", function (e) {
		e.stopPropagation();

		if (e.type == 'shown') {

			// trigger tab refreshing
			var target = $($(e.target));
			if (typeof target != 'undefined') {
				// do slicks
				if (target.find('.slick-slider').length) {
					target.find('.slick-slider').slick('setPosition');
				}
				// do isotopes
				if (target.find('.isotope-initialized').length) {
					target.find('.isotope-initialized').isotope('layout');
				}
				// trigger event
				$(window).trigger('zn_tabs_refresh');
			}
		}

	});


	/*--------------------------------------------------------------------------------------------------
	 Sparkles
	 --------------------------------------------------------------------------------------------------*/
	var Spark = function (sparkles_container) {
		this.sparkles_container = $(sparkles_container);
		this.s = ["shiny-spark1", "shiny-spark2", "shiny-spark3", "shiny-spark4", "shiny-spark5", "shiny-spark6"];
		this.i = this.s[this.random(this.s.length)];
		this.n = document.createElement("span");
		this.newSpeed().newPoint().display().newPoint().fly();
	};
	Spark.prototype.display = function () {
		$(this.n).attr("class", this.i).css("z-index", this.random(3)).css("top", this.pointY).css("left", this.pointX);
		this.sparkles_container.append(this.n);
		return this;
	};
	Spark.prototype.fly = function () {
		var a = this;
		$(this.n).animate({ top: this.pointY, left: this.pointX }, this.speed, "linear", function () {
			a.newSpeed().newPoint().fly();
		});
	};
	Spark.prototype.newSpeed = function () {
		this.speed = (this.random(10) + 5) * 1100;
		return this;
	};
	Spark.prototype.newPoint = function () {
		var parentPos = this.sparkles_container,
			parentSlideshow = parentPos.closest('.kl-slideshow'),
			parentPh = parentPos.closest('.page-subheader');
		if (parentSlideshow.length > 0) {
			parentPos = parentSlideshow;
		} else if (parentPh.length > 0) {
			parentPos = parentPh;
		}
		this.pointX = this.random(parentPos.width());
		this.pointY = this.random(parentPos.height());
		return this;
	};
	Spark.prototype.random = function (a) {
		return Math.ceil(Math.random() * a) - 1;
	};

})(jQuery);

var klRecaptchaLoaded = false;
var kallyasOnloadCallback = function () {

	// Don't run the callback twice. Every callback is called for each script loaded on the page.
	if( klRecaptchaLoaded ) {
		return;
	}

	klRecaptchaLoaded = true;
	jQuery('.kl-recaptcha').each(function () {
		grecaptcha.render(jQuery(this).attr('id'), {
			'sitekey': jQuery(this).data('sitekey'),
			'theme': jQuery(this).data('colorscheme')
		});
	});
};

if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
	var msViewportStyle = document.createElement("style");
	msViewportStyle.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}"));
	document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
}
