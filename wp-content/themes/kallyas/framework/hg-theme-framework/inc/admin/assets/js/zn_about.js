(function ($) {
	$.winReload = function(){
		//window.location.href = document.URL;
		location.reload();
	};
	$.ZnAboutJs = function () {
		this.scope = $(document);
		this.zn_dummy_step = 0;
		this.failed = 0;

		/**
		 * Holds a refference to the active plugin element
		 */
		this.activePluginElement;

		/**
		 * Holds the filesystem close callback
		 * @type {Function}
		 */
		this.callback = null;

		/**
		 * Holds the FTP credentials
		 * @type {Object}
		 */
		this.ftpCredentials = {
			ftp:       {
				host:           '',
				username:       '',
				password:       '',
				connectionType: ''
			},
			ssh:       {
				publicKey:  '',
				privateKey: ''
			},
			available: false
		};


		/**
		 * Holds the jQuery object for the filesystem credentials
		 * @type jQuery element
		 */
		this.filesystemModal = $( '#request-filesystem-credentials-dialog' );

		this.shouldRequestFilesystemCredentials = this.filesystemModal.length > 0;

		// Main init
		this.zinit();

	};

	$.ZnAboutJs.prototype = {
		zinit : function() {
			var fw = this;

			fw.init_tabs();
			// Init theme registration form
			fw.init_theme_registration();
			fw.init_theme_unlink();
			// Init misc
			fw.init_misc();
			// Init tooltips
			fw.init_tooltips();
			// Init plugin ajax actions
			fw.init_plugin_ajax();
			//#! Allow the registration button displayed on the Sample data page to change to Registration tab.
			fw.initDemosRegisterButton();

			fw.filesystemModal.on( 'click', '[data-js-action="close"], .notification-dialog-background', fw.requestForCredentialsModalClose );
			fw.filesystemModal.on( 'submit', 'form', function( event ) {
				event.preventDefault();

				// Persist the credentials input by the user for the duration of the page load.
				fw.ftpCredentials.ftp.hostname       = $( '#hostname' ).val();
				fw.ftpCredentials.ftp.username       = $( '#username' ).val();
				fw.ftpCredentials.ftp.password       = $( '#password' ).val();
				fw.ftpCredentials.ftp.connectionType = $( 'input[name="connection_type"]:checked' ).val();
				fw.ftpCredentials.ssh.publicKey      = $( '#public_key' ).val();
				fw.ftpCredentials.ssh.privateKey     = $( '#private_key' ).val();
				fw.ftpCredentials.available          = true;

				// Redo the plugin install
				fw.doCallback();

				fw.requestForCredentialsModalClose();
			} );

		},

		registerCallback : function( callback ){
			this.callback = callback;
		},

		doCallback: function(){
			if( typeof this.callback === 'function' ){
				this.callback();
			}
		},

		init_tooltips : function(){
			$( '.zn-server-status-column-icon' ).tooltip({
				position : { my: 'center bottom', at: 'center top-10' }
			});
		},

		init_tabs : function(){

			var nav_li = $('.zn-about-navigation > li'),
				nav_links = $('.zn-about-navigation > li > a'),
				actions_area = $('#zn-about-actions');

				// Check if first or last to show next/prev or both
				var doNextprev = function(index){
					if( index == 0 ){
						actions_area.addClass('is-first').removeClass('is-last');
					}
					else if( index == (nav_li.length - 1 ) ){
						actions_area.addClass('is-last').removeClass('is-first');
					}
					else {
						actions_area.removeClass('is-first is-last');
					}
				}

			nav_li.on('click', function(e){

				var curlink = $('a', e.currentTarget).attr('href');
				$('.zn-about-header').attr('id', curlink + "-dashboard");
				// window.location.hash = '#dashboard-top';
				e.preventDefault();

				// Activate the menu
				$(e.currentTarget).addClass('active');
				$(e.currentTarget).siblings('li').removeClass('active');

				// Activate the current tab
				var tabs = $(this).closest('.zn-about-tabs-wrapper').find('.zn-about-tabs > .zn-about-tab'),
					current_tab = $( curlink );
				window.location.hash = curlink + "-dashboard";

				tabs.removeClass('active');
				current_tab.addClass('active');

			});

			// Activate
			var hash = window.location.hash;
			if (hash !== '') {
				var nodashboard = hash.replace('-dashboard', '');
				nav_li.find('a[href="' + nodashboard + '"]').parent().trigger('click');
			}

			// Init next and prev buttons
			$( '.zn-about-action-nav' ).on('click', function(){
				var tabs = $('.zn-about-tabs-wrapper').find('.zn-about-tabs > .zn-about-tab'),
					current_tab = tabs.filter('.active'),
					to = $(this).attr('data-to');

				// Change menu
				$('.zn-about-navigation > li').removeClass('active');
				$('.zn-about-navigation > li a[href="#'+current_tab.attr('id')+'"]').parent()[to]().addClass('active')
				// theparent;

				// Change tab
				tabs.removeClass('active');
				current_tab[to]().addClass('active');

				doNextprev( nav_li.filter('.active').index() );

			});
		},

        /**
		 * Allow the registration button displayed on the Sample data page to change to Registration tab.
         */
        initDemosRegisterButton: function() {
			$('.hg-demos-button-register').on('click', function(e){
				e.preventDefault();
				e.stopPropagation();
                $('.zn-about-navigation > li a[href="#zn-about-tab-registration"]').trigger('click');
                return false;
			});
		},

		init_theme_registration : function(){
			$('.zn-about-register-form').submit(function(e){
				e.preventDefault();

				var api_key  = $('.zn-about-register-form-api', this).val(),
					nonce = $('#zn_nonce', this).val(),
					form = $(this),
					button = form.find( '.zn-about-register-form-submit' ),
					is_submit = false;

				if( form.hasClass('zn-submitting') ){
					return;
				}

				// Don't do anything if we don't have the values filled in
				if( ! api_key.length || ! nonce.length ){
					$(this).addClass('zn-about-register-form--error');
					return;
				}

				var data = {
					'action': 'zn_theme_registration',
					'dash_api_key': api_key,
					'zn_nonce': nonce
				};

				var alertContainer = $('#zn-register-theme-alert');
				$(this).removeClass('zn-about-register-form--error');
				$(this).addClass('zn-submitting');
				alertContainer.html('');

				// hide the label on click
				$('.js-zn-label-tfusername', form).hide();

				// Perform the Ajax call
				jQuery.post(ajaxurl, data, function(response)
				{
					// If we received an error, display it
					if( response.success === false ){
						if( response.data.error ){
							alertContainer.html('<div class="zn-adminNotice zn-adminNotice-error">ERROR: '+response.data.error+'</div>').show();
						}
					}
					else if( response.success === true ){
						alertContainer.html('<div class="zn-adminNotice zn-adminNotice-success">'+response.data.message+'</div>').show();
						location.reload();
					}
					else{
						alertContainer.html('<div class="zn-adminNotice zn-adminNotice-error">Something went wrong. Please try again later.</div>').show();
					}
					form.removeClass('zn-submitting');
				});
			});
		},

		init_theme_unlink: function() {
			var unlinkThemeButton = $('#unlink_theme_button');
			if( typeof(unlinkThemeButton) !== 'undefined' ) {
				unlinkThemeButton.on( 'click', function(e) {
					e.preventDefault();
					var form = $(this).parents('form').first(),
						nonce = $('#zn_nonce', form).val();

					// Don't do anything if we don't have the values filled in
					if( ! nonce.length ){
						form.addClass('zn-about-register-form--error');
						return false;
					}

					var confirmMessage = $(this).data( 'confirm'),
						cancelButtonText = $(this).data( 'buttonCancel'),
						okButtonText = $(this).data( 'buttonOk');

					new $.ZnModalConfirm( confirmMessage, cancelButtonText, okButtonText, function() {
						var data = {
							'action': 'zn_theme_unlink',
							'zn_nonce': nonce
						};

						$.post(ajaxurl, data, function(response){
							form.addClass('zn-submitting');

							var alertContainer = $('#zn-register-theme-alert');

							if( response ) {
								if( response.success == true ){
									new $.ZnModalMessage( response.data.message, function() {
										location.reload();
									} );
								}
								else if( response.data ) {
									alertContainer.html('<div class="zn-adminNotice zn-adminNotice-error">ERROR: '+response.data.error+'</div>').show();
								}
							}
						}).always(function(){
							form.removeClass('zn-submitting');
						});
					});
				});
			}
			return false;
		},

		init_misc : function(){
			var fw = this;

			var refreshDemosButton = $('.js-refresh-demos');
			if(typeof(refreshDemosButton) != 'undefined')
			{
				refreshDemosButton.on('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					var self = $(this);
					self.addClass('zn-action-disabled zn-installing');
					$.ajax({
						'type' : 'post',
						'dataType' : 'json',
						'url' : ajaxurl,
						'data' : {
							'action': 'zn_refresh_theme_demos',
							'zn_nonce': self.data('nonce')
						}
					}).done(function(response){
						if(response && response.data)
						{
							if(response.success){
								if( '1' == response.data.toLowerCase() ){
									$.winReload();
								}
								else {
									new $.ZnModalMessage(response.data);
								}
							}
							else {
								if( response.data.error ){
									new $.ZnModalMessage(response.data.error);
									$.winReload();
								}
								else {
									new $.ZnModalMessage(response.data);
								}
							}

						}
						else {
							new $.ZnModalMessage(response.data);
						}
					}).fail(function(e){
						new $.ZnModalMessage('An error occurred: '+e);
					}).always(function(){
						self.removeClass('zn-action-disabled zn-installing');
					});
				});
			}

			var refreshPluginsButton = $('.js-refresh-plugins');
			if(typeof(refreshPluginsButton) != 'undefined')
			{
				refreshPluginsButton.on('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					var self = $(this);
					self.addClass('zn-action-disabled zn-installing');
					$.ajax({
						'type' : 'post',
						'dataType' : 'json',
						'url' : ajaxurl,
						'data' : {
							'action': 'zn_refresh_plugins_list',
							'zn_nonce': self.data('nonce')
						}
					}).done(function(response){
						if(response && response.data)
						{
							if(response.success){
								if( '1' == response.data.toLowerCase() ){
									$.winReload();
								}
								else {
									new $.ZnModalMessage(response.data);
								}
							}
							else {
								if( response.data.error ){
									new $.ZnModalMessage(response.data.error);
									$.winReload();
								}
								else {
									new $.ZnModalMessage(response.data);
								}
							}
						}
						else {
							new $.ZnModalMessage(response.data);
						}
					}).fail(function(e){
						new $.ZnModalMessage('An error occurred: '+e);
					}).always(function(){
						self.removeClass('zn-action-disabled zn-installing');
					});
				});
			}

			$( document ).on( 'click', '.zn-extension-button', function(event){
				event.preventDefault();

				fw.activePluginElement = $(event.target);

				//  Check to see if we need to request the credentials
				if( fw.shouldRequestFilesystemCredentials ){
					fw.requestForCredentialsModalOpen();
					fw.registerCallback(function(){
						fw.do_plugin_action();
					});
				}
				else{
					fw.do_plugin_action();
				}

			});

			$('.js-installMandatoryPlugs' ).on('click', function(event) {
				event.preventDefault();
				$( '.zn-extension-inner[data-type="required_addon"] .zn-extension-button' ).trigger( 'click' );
				console.log( $( '.zn-extension-inner[data-type="required_addon"][data-action="enable_plugin"] .zn-extension-button' ) );
				console.log( $( '.zn-extension-inner[data-type="required_addon"][data-action="update_plugin"] .zn-extension-button' ) );
				console.log( $( '.zn-extension-inner[data-type="required_addon"][data-action="install_plugin"] .zn-extension-button' ) );

				$(this).fadeOut('long');
			});

		},

		init_plugin_ajax : function(){


		},

		requestForCredentialsModalOpen: function(){

			$( 'body' ).addClass( 'modal-open' );

			this.filesystemModal.show();
			this.filesystemModal.find( 'input:enabled:first' ).focus();
		},

		requestForCredentialsModalClose : function(){
			$( '#request-filesystem-credentials-dialog' ).hide();
			$( 'body' ).removeClass( 'modal-open' );
		},

		do_plugin_action : function(){

			// Perform the ajax call based on action
			var config = {};
				config.button			= this.activePluginElement;
				// config.button			= config.button.find('.spinner');
				config.status_classes	= 'zn-active zn-inactive zn-not-installed';
				config.elm_container	= config.button.closest('.zn-extension');
				config.status_holder	= config.elm_container.find( '.zn-extension-status' );
				// config.status_text		= config.button.closest( '.zn-extension-status' );
				config.action			= config.button.data( 'action' );
				config.nonce			= config.button.data( 'nonce' );
				config.slug				= config.button.data( 'slug' );

			var data = {
				security 		: config.nonce,
				action 			: 'zn_do_plugin_action',
				plugin_action 	: config.button.data( 'action' ) || false,
				source_type 	: config.button.data( 'sourceType' ) || false,
				slug 			: config.button.data( 'slug' ) 	 || false
			};

			// Don't allow the user to spam the button
			if( config.button.hasClass('is-active') ) {
				return false;
			}

			// Add the loading class
			config.button.addClass( 'is-active' );

			this.perform_ajax_call( data, config );

			return false;
		},

		perform_ajax_call : function( data, config, callback ){
			var fw = this;
			// Perform the ajax call
			data = $.extend( data, {
				username:        this.ftpCredentials.ftp.username,
				password:        this.ftpCredentials.ftp.password,
				hostname:        this.ftpCredentials.ftp.hostname,
				connection_type: this.ftpCredentials.ftp.connectionType,
				public_key:      this.ftpCredentials.ssh.publicKey,
				private_key:     this.ftpCredentials.ssh.privateKey
			});

			$.ajax({
				'type' : 'post',
				'dataType' : 'json',
				'url' : ajaxurl,
				'data' : data,
				'success' : function( response ){

					// If we have invalid credentials
					if( response.data.error_code && response.data.error_code == 'invalid_ftp_credentials' ){

						fw.requestForCredentialsModalOpen();
						config.button.removeClass( 'is-active' );
						return;
					}

					// If we received an error, display it
					if( response.data.error ){
						new $.ZnModalMessage( "ERROR: " + response.data.error );
					}

					// Update the plugin status
					config.elm_container.removeClass( config.status_classes );
					config.elm_container.addClass( response.data.status );
					config.status_holder.text( response.data.status_text );

					// Update the plugin
					config.button.data( 'action', response.data.action );
					config.button.text( response.data.action_text );

					if( typeof callback != 'undefined' ){
						callback();
					}

					config.button.removeClass( 'is-active' );
				},
				'error' : function(response){
					if( typeof callback != 'undefined' ){
						callback();
					}
					new $.ZnModalMessage( 'There was a problem performing the action.' );
					config.button.removeClass( 'is-active' );
				}
			});
		}
	};

	$(document).ready(function() {
		// Call this on document ready
		$.ZnAboutJs = new $.ZnAboutJs();
	});

})(jQuery);
