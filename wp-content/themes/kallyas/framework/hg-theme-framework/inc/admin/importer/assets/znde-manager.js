(function($) {
    $.ZnDemoImportManager =
    {
        //<editor-fold desc="::: MODAL WINDOW">
        Modal : function(options){
            var _options = {};

            if(typeof(options) == 'string'){
                _options.modal_content = '<p style="margin: 35px 0 0 20px;">'+options+'</p>';
            }

            // Override some of the default settings
            _options.modal_title = ZN_THEME_DEMO.demo_title || 'Demo Install';
            _options.close_button_title = ZN_THEME_DEMO.modal_close || 'Close';
            _options.show_resize = false;

            new $.ZnModal( _options );
        },
        //</editor-fold desc="::: MODAL WINDOW">

        //<editor-fold desc="::: POPUP WINDOW">
        PopupWindow : {
            __instance : null,
            __installing: false,
            __popupWin: null,

            /**
             * Retrieve the reference to the instance of this object
             * @returns {$.ZnDemoImportManager.PopupWindow}
             */
            getInstance: function(){
                if(! this.__instance){
                    this.__instance = this;
                }
                if(! this.__popupWin) {
                    this.__create();
                }
                return this.__instance;
            },

            /**
             * Create the popup window
             * @returns {null}
             * @private
             */
            __create: function()
            {
                this.__destroy();

                // clone and prepare
                var self = this,
                    tmpl = $('.zn-install-popup-template'),
                    clone = tmpl.clone(),
                    parent = tmpl.parent();

                clone.removeClass('zn-install-popup-template').addClass('zn-install-popup');
                clone.insertAfter(tmpl);

                this.__popupWin = $('.zn-install-popup').first();

                // Register default event handlers
                $('.zn-install-popup-close-button', this.__popupWin).on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    if(self.__installing){
                        // do not allow partial demo install (that is, closing the popup while installing the demo data)
                        alert('You cannot close the popup until the demo data is installed.');
                        return false;
                    }
                    self.__destroy();
                });

                // Check all options by default
                var installOptions = $(':checkbox', this.__popupWin);
                installOptions.attr('checked', 'checked');

                // Bind to the click event in order to be able to update the steps list
                installOptions.on('click', function(e){
                    var status = '',
                        step = $(this).data('step');
                    if($(this).attr('checked')){
                        status = ZN_THEME_DEMO.status_waiting;
                        $.ZnDemoImportManager.Installer.activateStep( step );
                    }
                    else {
                        status = ZN_THEME_DEMO.status_none;
                        $.ZnDemoImportManager.Installer.deactivateStep( step );
                    }
                    self.updateProgressList( 0, step, status );
                });

                self.prepareForInstalling();
            },
            __destroy: function(){
                var self = this;
                $('.zn-install-popup').each(function(){
                    $(this).fadeOut(300).remove();
                    // Restore page state
                    $( '.js-znAbout-btnInstall').removeClass('zn-submitting zn-installing');
                    $( '.zn-dummy-import-block').hide();
                    self.__installing = false;
                    self.__instance = null;
                    self.__popupWin = null;
                });

            },
            blockUI: function( value ){
                this.__installing = value;
            },
            show: function(){
                $('.zn-install-popup').addClass('zn-install-popup-visible').fadeIn(300);
            },

            setTitle: function( title ){
                $('.zn-install-popup .zn-install-popup-title').text( title );
            },
            setImage: function( imageUrl ){
                $('.zn-install-popup .zn-demo-image').attr('src', imageUrl );
            },

            getInstallButtonRef: function(){
                return $('.js-znAbout-btnInstallDemo');
            },
            /*
             * Retrieve all extra options from the popup as an object
             */
            getSelectedOptions: function(){
                var result = {};

                if(! this.hasSelectedOptions()){
                    return result;
                }
                var checkboxes = $('.zn-install-popup :checkbox');
                $.each(checkboxes, function(a,b){
                    var self = $(b);
                    if(self.is(':checked')) {
                        result[self.attr('id')] = self.data('step');
                    }
                });
                return result;
            },

            hasSelectedOptions: function() {
                var checkboxes = $('.zn-install-popup :checkbox:checked');
                return (checkboxes.length >= 1);
            },

            prepareForInstalling : function(){
                // Set the initial progress bar status
                var importerProgressStatus = $('#zn-import-progress-status-text', this.__popupWin);
                if( importerProgressStatus ) {
                    importerProgressStatus.text('0%');
                }
                var importerProgressBar = $('#zn-import-progress-bar', this.__popupWin);
                if( importerProgressBar ) {
                    importerProgressBar.css('width', '0');
                }
            },

            /**
             * Adds a new installation step
             * @param {{}} stepData Options for adding a new step
             */
            addStep: function( stepData )
            {
                if(! stepData ){ return; }
                var statusListWrapper = $('#zn-import-steps', this.__popupWin);
                if(statusListWrapper)
                {
                    var html = '<span class="zn-import-steps-block clearfix" data-step="'+stepData['step']+'">';
                    html += '<span class="zn-import-step-text">'+stepData['msg']+' </span>';
                    html += '<strong class="zn-import-step-status status-waiting">'+ZN_THEME_DEMO.status_waiting+'</strong>';
                    html += '</span>';

                    if( stepData.method == 'prepend' ) {
                        statusListWrapper.prepend(html);
                    }
                    else {
                        statusListWrapper.append(html);
                    }
                }
            },

            updateProgressList: function( progressText, step, status )
            {
                $('#zn-import-progress-status-text', this.__popupWin).text( progressText+'%' );
                $('#zn-import-progress-bar', this.__popupWin).css( 'width', progressText+'%' );
                this.updateProgressListStatus( step, status );
            },
            updateProgressListStatus: function( step, status )
            {
                if(! step ){
                    return;
                }
                var $step = $('span[data-step="'+step+'"]', this.__popupWin);

                if( $step ){
                    var cssClass = '';
                    if(status == ZN_THEME_DEMO.status_waiting){
                        cssClass = 'status-waiting';
                    }
                    else if(status == ZN_THEME_DEMO.status_in_progress){
                        cssClass = 'status-in-progress';
                    }
                    else if(status == ZN_THEME_DEMO.status_completed){
                        cssClass = 'status-completed';
                    }
                    else if(status == ZN_THEME_DEMO.status_failed){
                        cssClass = 'status-failed';
                    }
                    else {
                        cssClass = 'status-none';
                    }

                    $('.zn-import-step-status', $step)
                        .removeAttr('class')
                        .addClass('zn-import-step-status '+cssClass)
                        .text(status);
                }
            }
        },
        //</editor-fold desc="::: POPUP WINDOW">

        //<editor-fold desc="::: INSTALLER">
        Installer : {
            /**
             * Whether or not there is a installation step currently running
             * @private
             */
            _installing: false,
            /**
             * Holds the list of all registered steps to execute
             * @private
             */
            _steps: [],

            /**
             * Register an installation step
             * @param step string The name of the function to trigger
             * @param options {{}} The list of options to pass to the function when triggered
             * @param {{}} stepData The list of options to configure the step
             */
            registerInstallationStep: function( step, options, stepData ){
                this._steps.push({
                    'step': step,
                    'options': options,
                    'install': true
                });
                $.ZnDemoImportManager.PopupWindow.getInstance().addStep( stepData );
                return this;
            },

            /**
             * Retrieve the list of all registered steps
             * @returns {Array}
             */
            getRegisteredSteps: function(){
                return this._steps;
            },

            /**
             * Retrieve the list of all steps that are going to be installed.
             * @returns {Array}
             */
            getInstallingSteps: function(){
                var steps = [];
                $.each(this._steps, function(ix, obj){
                    if(obj.install){
                        steps.push(obj);
                    }
                });
                return steps;
            },

            /**
             * Deactivate a step
             * @param {string} stepName
             * @returns {$.ZnDemoImportManager}
             */
            deactivateStep: function( stepName ){
                $.each(this._steps, function(ix,obj){
                    if(obj.step == stepName){
                        obj.install = false;
                    }
                });
                return this;
            },

            /**
             * Activate a step
             * @param {string} stepName
             * @returns {$.ZnDemoImportManager}
             */
            activateStep: function( stepName ) {
                $.each(this._steps, function(ix,obj){
                    if(obj.step == stepName){
                        obj.install = true;
                    }
                });
                return this;
            },

            /**
             * Retrieve the information about the next install step
             * @returns {*}
             */
            getNextInstallStep: function()
            {
                if( this._steps.length >=1 ) {
                    var data = this._steps.shift();
                    if (data && data.install) {
                        return data;
                    }
                    else {
                        return this.getNextInstallStep();
                    }
                }
                return false;
            },

            /**
             * Calculate the step size to apply to the progress bar
             * @returns {number}
             * @private
             */
            _calculateStepSize: function(){
                var steps = this.getInstallingSteps();
                if(steps.length < 1){
                    return 0;
                }
                else {
                    var list = [];
                    $.each(steps, function(ix, e){
                        if(e.install){
                            list.push(e);
                        }
                    });
                }
                return Math.floor( 100 / steps.length );
            }
        }
        //</editor-fold desc="::: INSTALLER">
    }
})(jQuery);
