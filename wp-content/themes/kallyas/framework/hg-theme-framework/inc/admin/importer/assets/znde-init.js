/*
 * Theme Demo Import
 */
jQuery(function ($) {

	var popupTrigger = $('.js-znAbout-btnInstall');
	var _xData = {};
	if(! popupTrigger)
	{
		alert(ZN_THEME_DEMO.msg_invalid_markup);
		throw new Error(ZN_THEME_DEMO.msg_invalid_markup);
	}

	/**
	 * Parse teh ajax response and retrieve the json data
	 * @param {string} response
	 * @returns {boolean|{}}
	 */
	var parseResponse = function( response ){
		try {
			if(typeof response != 'undefined' && response.length){
				response.trim();
				return JSON.parse(response);
			}
		}
		catch(err) {
			console.warn( err );
			return false;
		}

	};

	popupTrigger.on('click', function(e){

		e.preventDefault();

		// Holds the reference to the popupTrigger button
		var self = $(this);

		var popupObj = $.ZnDemoImportManager.PopupWindow.getInstance(),
			popupWin = popupObj.__popupWin,
			el_title = $(this).parents('.zn-about-dummy-wrapper').find('.zn-about-dummy-title').first(),
			el_image = $(this).parents('.zn-about-dummy-wrapper').find('.zn-about-dummy-image > img').first();

		if(! popupWin){
			alert(ZN_THEME_DEMO.msg_invalid_markup);
			throw new Error(ZN_THEME_DEMO.msg_invalid_markup);
		}

		/**
		 * The default ajax data to send to server when installing the demo. Please note that this object will be
		 * merged with the options added by the $.ZnDemoImportManager.Installer object.
		 * @type {{action: string, nonce: string, demo_name: string}}
		 */
		var defaultAjaxData = {
			'action': 'install_demo',
			'nonce': ZN_THEME_DEMO.nonce,
			'demo_name': self.data('demoName')
		};

		// Display the Demo popup
		popupObj.setTitle( el_title ? el_title.text() : '' );
		popupObj.setImage( el_image ? el_image.attr('src') : '' );
		popupObj.show();

//<editor-fold desc="::: REGISTER INSTALLATION STEPS">
		// Register the special steps separately (since they're not part of the options)
		$.ZnDemoImportManager.Installer
			.registerInstallationStep( 'zn_get_demo', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_get_demo,
				'step': 'zn_get_demo'
			} )
			.registerInstallationStep( 'zn_install_plugins', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_plugins,
				'step': 'zn_install_plugins'
			} )
			.registerInstallationStep( 'zn_import_images', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_import_images,
				'step': 'zn_import_images'
			} )
			.registerInstallationStep( 'zn_custom_icons', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_custom_icons,
				'step': 'zn_custom_icons'
			} )
			.registerInstallationStep( 'zn_custom_fonts', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_custom_fonts,
				'step': 'zn_custom_fonts'
			} )
			.registerInstallationStep( 'zn_import_rev_sliders', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_import_rev_sliders,
				'step': 'zn_import_rev_sliders'
			} )
			.registerInstallationStep( 'zn_install_content', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_content,
				'step': 'zn_install_content'
			} )
			.registerInstallationStep( 'zn_import_menus', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_import_menus,
				'step': 'zn_import_menus'
			} )
			.registerInstallationStep( 'zn_install_theme_options', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_theme_options,
				'step': 'zn_install_theme_options'
			} )
			.registerInstallationStep( 'zn_install_widgets', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_widgets,
				'step': 'zn_install_widgets'
			} )
			.registerInstallationStep( 'zn_global_settings', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_install_global_opt,
				'step': 'zn_global_settings'
			} )
			.registerInstallationStep( 'zn_import_post_processing', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_import_post_processing,
				'step': 'zn_import_post_processing'
			} )
			.registerInstallationStep( 'zn_import_cleanup', defaultAjaxData, {
				'method': 'append',
				'msg': ZN_THEME_DEMO.msg_import_cleanup,
				'step': 'zn_import_cleanup'
			} )
		;
//</editor-fold desc="::: REGISTER INSTALLATION STEPS">

		/**
		 * This object stores the variables we need for the ajax request enquiring the install state
		 * @type {{timeout: number, xhrInterval: number, failedRetries: number, maxFailedRetries: number, maxSpecialFailedRetries: number, xhrData: {}, currentlyProcessing: string, crtProgress: number, stepSize: (*|number)}}
		 * @private
		 */
		var __STATE__ = {
			// default ajax request timeout. 20 seconds
			timeout: 20000,
			xhrInterval: 0,
			failedRetries: 0,
			// This is use for normal Ajax request errors, such as timeout
			maxFailedRetries: 50,
			// This is used in cases the Ajax request receives an either 404 or 500 error response
			maxSpecialFailedRetries: 5,
			xhrData: {},
			// The currently processing install step
			currentlyProcessing: 'zn_get_demo',
			// The current installing progress
			crtProgress: 0,
			// The installing progress step size
			stepSize: $.ZnDemoImportManager.Installer._calculateStepSize()
		};

		/**
		 * Function to trigger when install has failed or has completed. It will clear the UI and any other blocking features
		 * @private
		 */
		var __finishInstall = function( msg ){
			// Cleanup - prevent memory leakage
			window.clearInterval(__STATE__.xhrInterval);
			__STATE__.failedRetries = 0;
			installButton.removeClass('zn-installing');
			$.ZnDemoImportManager.PopupWindow.getInstance().blockUI(false);
			// console.info('[__finishInstall] triggered');
			msg = msg || ZN_THEME_DEMO.msg_install_complete;
			$.ZnDemoImportManager.Modal( msg );
			__STATE__.crtProgress = 0;
			// play sound
			var audioSound = document.getElementById('zn-about-dummySounds');
			if(typeof audioSound != 'undefined'){
				audioSound.play();
			}
		};

		var doDemoInstall = function(){

			if( installButton.hasClass( 'zn-submitting' ) ){
				return;
			}

			// Check to see we have something to install
			var installingSteps = $.ZnDemoImportManager.Installer.getInstallingSteps();
			if(installingSteps.length < 1) {
				$.ZnDemoImportManager.Modal(ZN_THEME_DEMO.msg_install_configure);
				return false;
			}

			// Store the currently installing steps to be sent server-side
			var __steps = [];
			$.each(installingSteps, function (index, stepInfo) {
				__steps.push(stepInfo.step);
			});

			//#! Update step size depending on the selected options
			__STATE__.stepSize = $.ZnDemoImportManager.Installer._calculateStepSize();

			//#! Clear the progress status list and update values
			popupObj.prepareForInstalling();
			installButton.addClass('zn-submitting zn-installing');
			$.ZnDemoImportManager.PopupWindow.getInstance().blockUI(true);

			// #! Set the initial step
			if(__STATE__.currentlyProcessing == ''){
				var _s = $.ZnDemoImportManager.Installer.getNextInstallStep();
				__STATE__.currentlyProcessing = _s.step;
			}
			__STATE__.xhrData['step'] = __STATE__.currentlyProcessing;
			var _xData = $.extend({}, defaultAjaxData, __STATE__.xhrData);

			__install(_xData);

			return false;
		}

		// if/when the install button is clicked
		var installButton = popupObj.getInstallButtonRef();
		if( installButton ) {
			//<editor-fold desc="::: installButton.on('click')">
			installButton.on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();

				if( $.ZnAboutJs.shouldRequestFilesystemCredentials ){
					$.ZnAboutJs.requestForCredentialsModalOpen();
					$.ZnAboutJs.registerCallback(function(){
						doDemoInstall();
					});
				}
				else{
					doDemoInstall();
				}

			});
			//</editor-fold desc="::: installButton.on('click')">
		}

		var __install = function( __data ) {
			if( __STATE__.failedRetries > __STATE__.maxSpecialFailedRetries || __STATE__.failedRetries > __STATE__.maxFailedRetries ){
				// console.warn('[__install][__install] Max failed retries reached.');
				popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
				__finishInstall(ZN_THEME_DEMO.msg_install_failed_retries);
				return false;
			}

			var stepInfo, stepName;

			if(! __data){
				stepInfo = $.ZnDemoImportManager.Installer.getNextInstallStep();
				stepName = stepInfo.step;
				__STATE__.xhrData['step'] = stepName;
				__data = $.extend({}, defaultAjaxData, __STATE__.xhrData);
			}
			else {
				stepName = __data.step;
			}

			// console.log('[__install] Currently processing: '+stepName);

			// Check if this is a valid step
			if( ! stepName ){
				// console.info('[__install][DONE] Current install process state is: COMPLETE');
				// console.info(ZN_THEME_DEMO.msg_install_complete);
				popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
				__finishInstall(ZN_THEME_DEMO.msg_install_complete);
			}
			else
			{
				if( /*'zn_get_demo' == stepName && */ __STATE__.crtProgress < 1)
				{
					popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_in_progress);
				}

				__data = $.extend( __data, {
					username:        $.ZnAboutJs.ftpCredentials.ftp.username,
					password:        $.ZnAboutJs.ftpCredentials.ftp.password,
					hostname:        $.ZnAboutJs.ftpCredentials.ftp.hostname,
					connection_type: $.ZnAboutJs.ftpCredentials.ftp.connectionType,
					public_key:      $.ZnAboutJs.ftpCredentials.ssh.publicKey,
					private_key:     $.ZnAboutJs.ftpCredentials.ssh.privateKey
				});

				// Process installation step
				$.ajax({
					url: ajaxurl,
					cache: false,
					method: 'POST',
					async: true,
					/*timeout: __STATE__.timeout,*/
					dataType: 'html',
					data: __data,
					statusCode : {
						//#! Internal Server Error
						500: function(){
							var t = setTimeout(function(){
								__STATE__.failedRetries += 1;
								// console.info('[__install][500] Failed. Retries: '+__STATE__.failedRetries);

								if( __STATE__.failedRetries <= __STATE__.maxSpecialFailedRetries ){
									__install(__data);
								}
								else{
									// console.warn('[__install][500] Max failed retries reached.');
									popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_failed);
									__finishInstall(ZN_THEME_DEMO.msg_install_failed_retries);
								}
							}, 3000);
							clearTimeout(t);
						},
						//#! Probably server down
						404: function(){
							var t = setTimeout(function(){
								__STATE__.failedRetries += 1;
								// console.info('[__install][404] Failed. Retries: '+__STATE__.failedRetries);

								if( __STATE__.failedRetries <= __STATE__.maxSpecialFailedRetries ){
									__install(__data);
								}
								else{
									// console.warn('[__install][404] Max failed retries reached.');
									popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_failed);
									__finishInstall(ZN_THEME_DEMO.msg_install_failed_retries);
								}
							}, 3000);
							clearTimeout(t);
						}
					}
				}).done(function(response){
					response = parseResponse( response );
					// console.info('[__install] Retrieved state: '+response.state);

					if( response.error_code && response.error_code == 'invalid_ftp_credentials' ){
						$.ZnAboutJs.requestForCredentialsModalOpen();
						installButton.removeClass('zn-submitting zn-installing');
						$.ZnDemoImportManager.PopupWindow.getInstance().blockUI(false);
						return;
					}

					// Invalid response
					if(! response || ! response.state){
						var t = setTimeout(function(){
							__STATE__.failedRetries += 1;

							if( __STATE__.failedRetries <= __STATE__.maxFailedRetries ){
								__install(__data);
							}
							else{
								// console.warn('[__install][DONE] Max failed retries reached.');
								popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
								__finishInstall(ZN_THEME_DEMO.msg_install_failed_retries);
								clearTimeout(t);
							}
						}, 3000);
					}
					else
					{
						var state = (typeof(response.state) == 'undefined' ? null : response.state),
							msg = (typeof(response.msg) != 'undefined' ? response.msg : typeof(response.data) != 'undefined' ? response.data : '');

						if( state === null ){
							popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
							__finishInstall(ZN_THEME_DEMO.msg_install_failed_invalid_response);
						}

						// Check the state
						if( state == ZN_THEME_DEMO.state_none || state == ZN_THEME_DEMO.state_done )
						{
							var crtState = 'STATE_NONE';
							if( state == ZN_THEME_DEMO.state_done ){
								crtState = 'STATE_DONE';
							}
							// console.info('[__install][DONE] Current install process state is: '+crtState);

							// Reset failed retries
							__STATE__.failedRetries = 0;

							if(state == ZN_THEME_DEMO.state_done) {
								// console.warn('[1] Step %s completed.', __STATE__.currentlyProcessing);
								popupObj.updateProgressList(__STATE__.crtProgress += __STATE__.stepSize, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
							}

							// Get next step and proceed to install
							stepInfo = $.ZnDemoImportManager.Installer.getNextInstallStep();
							// console.info('NEXT STEP: ');
							// console.info(stepInfo);
							// Check to see whether or not we've finished the installing steps
							if( ! stepInfo || typeof(stepInfo) == 'undefined' )
							{
								// Nothing to do here. Install completed.
								// console.info(ZN_THEME_DEMO.msg_install_complete);
								popupObj.updateProgressList( 100, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
								__finishInstall(ZN_THEME_DEMO.msg_install_complete);
							}
							else {
								// Execute the next installation step
								__STATE__.currentlyProcessing = stepInfo.step;
								// console.info('Currently processing: '+ __STATE__.currentlyProcessing);
								popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_in_progress);

								__STATE__.xhrData['step'] = __STATE__.currentlyProcessing;
								__data = $.extend({}, defaultAjaxData, __STATE__.xhrData);
								setTimeout(function(){__install(__data); }, 2000);
							}
						}
						else if( state == ZN_THEME_DEMO.state_fail )
						{
							// console.warn('Step %s failed.', __STATE__.currentlyProcessing);
							popupObj.updateProgressList( __STATE__.crtProgress += __STATE__.stepSize, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_failed);

							// Get next step and proceed to install
							stepInfo = $.ZnDemoImportManager.Installer.getNextInstallStep();

							// Check to see whether or not we've finished the installing steps
							if( ! stepInfo || typeof(stepInfo) == 'undefined' )
							{
								// Nothing to do here. Install completed.
								// console.info(ZN_THEME_DEMO.msg_install_complete);
								popupObj.updateProgressList( 100, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
								__finishInstall(ZN_THEME_DEMO.msg_install_complete);
							}
							else {
								// Execute the next installation step
								__STATE__.currentlyProcessing = stepInfo.step;
								// console.info('Currently processing: '+ __STATE__.currentlyProcessing);
								popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_in_progress);

								__STATE__.xhrData['step'] = __STATE__.currentlyProcessing;
								__data = $.extend({}, defaultAjaxData, __STATE__.xhrData);
								setTimeout(function(){__install(__data); }, 2000);
							}
						}
						else if( state == ZN_THEME_DEMO.state_abort )
						{
							// console.warn('[__install][DONE] Current install process state is: ABORT');
							// console.error(ZN_THEME_DEMO.msg_install_abort + ' ' + msg);
							popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_failed);
							__finishInstall(ZN_THEME_DEMO.msg_install_abort + ' ' + msg);
						}
						else if( state == ZN_THEME_DEMO.state_complete )
						{
							// console.info('[__install][DONE] Current install process state is: COMPLETE');
							// console.info(ZN_THEME_DEMO.msg_install_complete);
							popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
							__finishInstall(ZN_THEME_DEMO.msg_install_complete);
						}
						else if( state == ZN_THEME_DEMO.state_unknown )
						{
							// console.warn('[__install][DONE] Current install process state is: UNKNOWN');
							// console.error(ZN_THEME_DEMO.msg_install_abort + ' ' + msg);
							popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_failed);
							__finishInstall(ZN_THEME_DEMO.msg_install_abort + ' ' + msg);
						}
						// STATE COMPLETE
						else
						{
							// console.info('[__install][DONE] response:');
							// console.info(response);
							popupObj.updateProgressList( 100, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
							__finishInstall(ZN_THEME_DEMO.msg_install_complete);
						}
					}
				}).fail(function(a,b,e){
					var t = setTimeout(function(){
						__STATE__.failedRetries += 1;
						// console.warn('[Fail] ['+e+'] Fail retry: '+__STATE__.failedRetries);

						if( __STATE__.failedRetries <= __STATE__.maxSpecialFailedRetries ){
							__install(__data);
						}
						else{
							// console.warn('[__install][FAIL] Max failed retries reached.');
							popupObj.updateProgressList( __STATE__.crtProgress, __STATE__.currentlyProcessing, ZN_THEME_DEMO.status_completed);
							__finishInstall(ZN_THEME_DEMO.msg_install_failed_retries);
							clearTimeout(t);
						}
					}, 3000);
				});
			}
		};

	});
});
