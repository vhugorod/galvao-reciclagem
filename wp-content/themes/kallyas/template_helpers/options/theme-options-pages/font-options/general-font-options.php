<?php
/**
 * Theme options > Font Options  > General Font Options
 */

// Google fonts
$url             = esc_url( __( '//www.google.com/fonts', 'zn_framework' ) );
$admin_options[] = array(
	'slug'        => 'gfont_setup',
	'parent'      => 'google_font_options',
	'id'          => 'zn_google_fonts_setup',
	'name'        => 'Google Fonts Setup',
	'description' => 'Here you can setup the <a href="' . $url . '" target="blank">Google web fonts</a> that you want to use in your site.',
	'type'        => 'zn_google_fonts_setup',
	'std'         => array(
		'Roboto' => array(
			'font_family'   => 'Roboto',
			'font_variants' => array(
				0 => 'regular',
				1 => '300',
				2 => '700',
				3 => '900',
			),
		),
	),
	'class'       => 'zn_full',
);

// General fonts subset
$admin_options[] = array(
	'slug'        => 'gfont_setup',
	'parent'      => 'google_font_options',
	'id'          => 'zn_google_fonts_subsets',
	'name'        => 'Google Fonts Subset',
	'description' => 'Select which subsets you want to load for the Google fonts.',
	'type'        => 'checkbox',
	'options'     => array(
		'latin'        => 'Latin',
		'latin-ext'    => 'Latin Ext',
		'greek'        => 'Greek',
		'cyrillic'     => 'Cyrillic',
		'cyrillic-ext' => 'Cyrillic Ext',
		'khmer'        => 'Khmer',
		'greek-ext'    => 'Greek Ext',
		'vietnamese'   => 'Vietnamese',
	),
	'std'         => '',
	'class'       => 'zn_full',
);

// Custom fonts subset
$admin_options[] = array(
	'slug'          => 'custom_font_setup',
	'parent'        => 'google_font_options',
	'id'            => 'zn_custom_fonts',
	'name'          => 'Custom Fonts Setup',
	'description'   => 'Using this option you can add your own custom fonts to the theme.',
	'type'          => 'group',
	'element_title' => 'cf_name',
	'subelements'   => array(
		array(
			'name'        => __( 'Font Name', 'zn_framework' ),
			'description' => __( 'Here you can type the font name that will be used.', 'zn_framework' ),
			'id'          => 'cf_name',
			'std'         => '',
			'type'        => 'text',
		),
		array(
			'name'        => __( 'Font Weight', 'zn_framework' ),
			'description' => __( 'Assign a font weight to this font.', 'zn_framework' ),
			'id'          => 'cf_fontweight',
			'std'         => '400',
			'type'        => 'select',
			'options'     => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		),
		array(
			'name'        => __( 'Custom font .woff', 'zn_framework' ),
			'description' => __( 'Upload the .woff font file.', 'zn_framework' ),
			'id'          => 'cf_woff',
			'std'         => '',
			'type'        => 'zn_media',
			'data'        => array(
				'button_title' => 'Add .woff font',
				'media_type'   => 'media_field_upload', // The text that will appear on the insert button from the media manager
				'insert_title' => 'Select font', // The text that will appear on the insert button from the media manager
				'title'        => 'Add Custom Font', // The text that will appear as the main option button for adding images
				'type'         => 'application/font-woff', // The media type : image, video, etc
				'state'        => 'library', // The media manager state
				'frame'        => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
				'class'        => 'zn-media-video media-frame', // A css class that will be applied to the modal
				'value_type'   => 'url', // The media manager state
				'preview'      => 'text',
			),

		),
		array(
			'name'        => __( 'Custom font .ttf', 'zn_framework' ),
			'description' => __( 'Upload the .ttf font file.', 'zn_framework' ),
			'id'          => 'cf_ttf',
			'std'         => '',
			'type'        => 'zn_media',
			'data'        => array(
				'button_title' => 'Add .ttf font',
				'media_type'   => 'media_field_upload', // The text that will appear on the insert button from the media manager
				'insert_title' => 'Select font', // The text that will appear on the insert button from the media manager
				'title'        => 'Add Custom Font', // The text that will appear as the main option button for adding images
//                'type' => 'font/ttf', // The media type : image, video, etc
				'type'         => 'application/x-font-ttf', // The media type : image, video, etc
				'state'        => 'library', // The media manager state
				'frame'        => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
				'class'        => 'zn-media-video media-frame', // A css class that will be applied to the modal
				'value_type'   => 'url', // The media manager state
				'preview'      => 'text',
			),
		),
		array(
			'name'        => __( 'Custom font .svg', 'zn_framework' ),
			'description' => __( 'Upload the .svg font file.', 'zn_framework' ),
			'id'          => 'cf_svg',
			'std'         => '',
			'type'        => 'zn_media',
			'data'        => array(
				'button_title' => 'Add .svg font',
				'media_type'   => 'media_field_upload', // The text that will appear on the insert button from the media manager
				'insert_title' => 'Select font', // The text that will appear on the insert button from the media manager
				'title'        => 'Add Custom Font', // The text that will appear as the main option button for adding images
				'type'         => 'image/svg+xml', // The media type : image, video, etc
				'state'        => 'library', // The media manager state
				'frame'        => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
				'class'        => 'zn-media-video media-frame', // A css class that will be applied to the modal
				'value_type'   => 'url', // The media manager state
				'preview'      => 'text',
			),
		),
		array(
			'name'        => __( 'Custom font .eot', 'zn_framework' ),
			'description' => __( 'Upload the .eot font file.', 'zn_framework' ),
			'id'          => 'cf_eot',
			'std'         => '',
			'type'        => 'zn_media',
			'data'        => array(
				'button_title' => 'Add .eot font',
				'media_type'   => 'media_field_upload', // The text that will appear on the insert button from the media manager
				'insert_title' => 'Select font', // The text that will appear on the insert button from the media manager
				'title'        => 'Add Custom Font', // The text that will appear as the main option button for adding images
				'type'         => 'application/vnd.ms-fontobject', // The media type : image, video, etc
				'state'        => 'library', // The media manager state
				'frame'        => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
				'class'        => 'zn-media-video media-frame', // A css class that will be applied to the modal
				'value_type'   => 'url', // The media manager state
				'preview'      => 'text',
			),
		),
	),
);

$admin_options[] = array(
	'slug'        => 'custom_font_setup',
	'parent'      => 'google_font_options',
	'name'        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
	'description' => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
	'id'          => 'gfto_title',
	'type'        => 'zn_title',
	'class'       => 'zn_full zn-custom-title-md zn-top-separator zn-sep-dark',
);

$admin_options[] = zn_options_video_link_option( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#ffnXlhSxpaI', __( "Click here to access the video tutorial for this section's options.", 'zn_framework' ), array(
	'slug'   => 'custom_font_setup',
	'parent' => 'google_font_options',
) );

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
	'slug'   => 'custom_font_setup',
	'parent' => 'google_font_options',
) );


//#! Typekit Fonts
//#! since v4.15.6
$typekitListsOptionName = ZNHGTFW()->getThemeDbId() . '_' . 'typekit_lists';
$typekitFontsOptionName = ZNHGTFW()->getThemeDbId() . '_' . 'typekit_fonts';
$admin_options[]        = array(
	'slug'        => 'gfont_setup',
	'parent'      => 'google_font_options',
	'name'        => __( 'Typekit fonts', 'zn_framework' ),
	'description' => __( 'Typekit fonts', 'zn_framework' ),
	'id'          => 'info_title-typekit',
	'type'        => 'zn_title',
	'class'       => 'zn_full zn-custom-title-large zn-toptabs-margin',
);

$admin_options[] = array(
	'slug'        => 'gfont_setup',
	'parent'      => 'google_font_options',
	'id'          => 'zn_typekit_token',
	'name'        => __('Typekit token', 'zn_framework'),
	'description' => sprintf(
		__( 'Please provide the token you got from the <a href="%s" target="_blank" title="Link will open in a new tab/window">Typekit website</a>. After saving the token, you will need to refresh the page to see your kits.', 'zn_framework'), 'https://typekit.com/account/tokens'
	),
	'type'        => 'text',
	'std'         => '',
	'class'       => 'zn_full',
);

$token = zget_option( 'zn_typekit_token', 'google_font_options', false, '' );
if ( ! empty( $token ) ) {
	$znTypekitClient = ZNHGFW()->getComponent('typekit');
	$znTypekitClient->setToken($token);
	$typekitLists    = $znTypekitClient->getAllKits();

	//#! Check for errors
	if ( is_wp_error($typekitLists)) {
		$admin_options[] = array(
			'slug'        => 'gfont_setup',
			'parent'      => 'google_font_options',
			'id'          => 'zn_typekit_err_message',
			'name'        => __('No kits found', 'zn_framework'),
			'description' => $typekitLists->get_error_message(),
			'type'        => 'zn_message',
			'std'         => '',
			'class'       => 'zn_full',
		);
	} else {
		$kits = array();
		if ( ! empty( $typekitLists ) && isset($typekitLists['kits'])) {
			//#! This list will be used in the new filter: znb_field_font_families
			$fontsList = array();
			foreach ( $typekitLists as $entries ) {
				foreach ( $entries as $kit ) {
					if ( ! isset( $kit[ 'id' ] ) || empty($kit['id'])) {
						continue;
					}
					$kitID = wp_strip_all_tags( $kit[ 'id' ] );
					//#! Get name...since it is not provided in the list
					$kitInfo = $znTypekitClient->getKitInfo( $kitID );
					if ( is_wp_error($kitInfo)) {
						$admin_options[] = array(
							'slug'        => 'gfont_setup',
							'parent'      => 'google_font_options',
							'id'          => 'zn_typekit_err_message',
							'name'        => __('No kits found', 'zn_framework'),
							'description' => $typekitLists->get_error_message(),
							'type'        => 'zn_message',
							'std'         => '',
							'class'       => 'zn_full',
						);
					} else {
						if ( ! empty($kitInfo) && isset($kitInfo[ 'kit' ][ 'name' ]) && ! empty($kitInfo[ 'kit' ][ 'name' ])) {
							$kits[ $kitID ] = wp_strip_all_tags( $kitInfo[ 'kit' ][ 'name' ] );
							//#! Update the fonts option
							if ( isset($kitInfo[ 'kit' ][ 'families' ]) && ! empty($kitInfo[ 'kit' ][ 'families' ])) {
								foreach ( $kitInfo[ 'kit' ][ 'families' ] as $entry ) {
									$fontSlug             = wp_strip_all_tags( $entry[ 'slug' ] );
									$fontName             = wp_strip_all_tags( $entry[ 'name' ] );
									$fontsList[$fontSlug] = $fontName;
								}
							}
						}
					}
				}
			}
			update_option( $typekitFontsOptionName, $fontsList );
		}

		//#!  Update list option in db that stores all lists
		update_option( $typekitListsOptionName, $kits );
		if ( empty( $kits ) ) {
			$admin_options[] = array(
				'slug'        => 'gfont_setup',
				'parent'      => 'google_font_options',
				'id'          => 'zn_typekit_err_message',
				'name'        => __('No kits found', 'zn_framework'),
				'description' => __('Please make sure you have kits in your typekit account.', 'zn_framework'),
				'type'        => 'zn_message',
				'std'         => '',
				'class'       => 'zn_full',
			);
		} else {
			$admin_options[] = array(
				'slug'        => 'gfont_setup',
				'parent'      => 'google_font_options',
				'id'          => 'zn_typekit_lists',
				'name'        => __('Typekit lists', 'zn_framework'),
				'description' => __('Select the kit(s) you want to load fonts from.', 'zn_framework'),
				'type'        => 'select',
				'multiple'    => true,
				'options'     => $kits,
				'std'         => '',
				'class'       => 'zn_full',
			);
		}
	}
}
