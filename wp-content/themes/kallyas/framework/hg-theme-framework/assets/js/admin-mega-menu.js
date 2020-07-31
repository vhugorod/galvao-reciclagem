(function($)
{
	"use strict";

	/*
	 * Localized through ZnHgAdminMegaMenu
	 */
	var ZnHgImageUpload = function( $mainWrapper, popupTitle ) {
		var $uploadButton = $mainWrapper.find('.zn-hg-image-upload-button'),
			$hInput = $mainWrapper.find('.zn-hg-bg-image-input-h'),
			$imagePreviewWrapper = $mainWrapper.find('.zn-hg-image-preview-wrapper'),
			$imageTarget = $imagePreviewWrapper.find('.zn-hg-image-preview'),
			$removeImageButton = $imagePreviewWrapper.find('.zn-hg-deleteImagePreview-button'),
			$sectionWrapper = $imagePreviewWrapper.parents('.field-add-image-mega-menu');

		if( typeof(wp) === 'undefined' || typeof(wp.media) === 'undefined') {
            console.error('ZN HG Mega Menu: wp.media was not loaded!');
            $sectionWrapper.removeClass('is-active');
        }

        if( '' == $hInput.val().trim()  ) {
            $sectionWrapper.removeClass('is-visible');
        }
        else {
            $removeImageButton.fadeIn('fast');
		}

		var selectImage = function() {
            var image = wp.media({
                title: popupTitle,
                // multiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // Convert uploaded_image to a JSON object to make accessing it easier

                    var imageInfo = uploaded_image.toJSON();

                    $sectionWrapper.addClass('is-visible');
                    $imageTarget.attr('src', imageInfo.url).fadeIn('fast');
                    $removeImageButton.fadeIn('fast');
                    $hInput.val(imageInfo.id);
                });
		};

		var removeImage = function() {
            $removeImageButton.fadeOut('fast');
            $imageTarget.fadeOut('fast');
            $imageTarget.attr('src', '');
            $hInput.val('');
		};


		//#! Bind listeners
        $uploadButton.on('click', function(e) {
        	e.preventDefault();
        	e.stopPropagation();
        	selectImage();
		});

        $removeImageButton.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            removeImage();
		});
    };

	/*
	 * Initialize the functionality for image upload
	 */
	var initImageUploadFields = function($menuMarkup){

		var $wrappers = $menuMarkup? $menuMarkup.find('.zn-hg-image-upload-mega-menu-wrapper') : $('.zn-hg-image-upload-mega-menu-wrapper');

        if( $wrappers.length >=1 )
        {
            $.each( $wrappers, function( a,b ) {

                new ZnHgImageUpload( $(b), ZnHgAdminMegaMenu.popupTitle );

            });
        }
	};

	//#! OnLoad - allow images to be selected
	initImageUploadFields();

	//#! When a menu item is added to the menu
    $( document ).on( 'menu-item-added', function(event, $scope){
        initImageUploadFields($($scope));
	});

    //#! Listen to changes for "Use as mega menu checkbox"
	$(document).on('change', '.znkl-mega-menu-enable', function( e ){
		var checked = $(this).is(':checked'),
			menu_container = $(this).closest('.menu-item-settings'),
			smart_area_option = menu_container.find('.field-enable-mega-menu-smart-area'),
			ref = $('[data-ref="'+$(this).attr('data-target')+'"]');

		if( checked ){
			smart_area_option.slideDown();
			if( ref ) {
				ref.addClass('is-visible');
			}
		}
		else{
			smart_area_option.slideUp();
            if( ref ) {
                ref.removeClass('is-visible');
            }
		}
	});
	$('.znkl-mega-menu-enable').trigger('change');

})(jQuery);
