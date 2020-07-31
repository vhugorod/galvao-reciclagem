<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class ZNB_GoogleMap extends ZionElement {
	function options() {
		$zoom = array();

		for ( $i = 1; $i < 24; $i++ ) {
			$zoom[$i] = $i;
		}

		$icon_sizes = array(
			'20' => '20 x 20',
			'30' => '30 x 30',
			'40' => '40 x 40',
			'50' => '50 x 50',
			'60' => '60 x 60',
			'70' => '70 x 70',
			'80' => '80 x 80',
		);

		$mapstyleurl   = 'http://snazzymaps.com';
		$latlong_url   = esc_url( 'http://www.latlong.net/' );
		$itouchmap_url = esc_url( 'http://itouchmap.com/latlong.html' );

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs' => true,
			'general'  => array(
				'title'   => 'General options',
				'options' => array(

					array(
						"name"        => __( "Google Maps Api Key (Mandatory!)", 'zn_framework' ),
						"description" => __( 'Add a Google Map Api Key. More on <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key">Google Maps Key</a>', 'zn_framework' ),
						"id"          => "map_apikey",
						"std"         => "",
						"type"        => "text",
						"class"       => "zn_input_xl",
					),

					array(
						'id'            => 'locations',
						'name'          => __( 'Locations', 'zn_framework' ),
						'description'   => __( 'Here you can add your map locations.', 'zn_framework' ),
						'type'          => 'group',
						'sortable'      => true,
						'element_title' => __( 'Map Location', 'zn_framework' ),
						'subelements'   => array(
							array(
								"name"        => __( "Marker Latitude", 'zn_framework' ),
								"description" => sprintf( __( 'Please enter the latitude value for your location. Here\'s 2 links where you can get the coordinates <a href="%s" target="_blank">LatLong.net</a> or <a href="%s" target="_blank">iTouchMap.com</a>', 'zn_framework' ), $latlong_url, $itouchmap_url ),
								"id"          => "map_latitude",
								"std"         => "41.447390",
								"type"        => "text",
							),
							array(
								"name"        => __( "Marker Longitude", 'zn_framework' ),
								"description" => sprintf( __( 'Please enter the longitude value for your location. Here\'s 2 links where you can get the coordinates <a href="%s" target="_blank">LatLong.net</a> or <a href="%s" target="_blank">iTouchMap.com</a>', 'zn_framework' ), $latlong_url, $itouchmap_url ),
								"id"          => "map_longitude",
								"std"         => "-72.843868",
								"type"        => "text",
							),
							array(
								"name"        => __( "Marker tooltip", 'zn_framework' ),
								"description" => __( "Add a text that will appear when the user clicks on the marker.", 'zn_framework' ),
								"id"          => "tooltip",
								"type"        => "textarea",
							),
							array(
								"name"        => __( "Marker location icon", 'zn_framework' ),
								"description" => __( "Select an icon that will appear as your current location. The default icon will be used if this is left blank.", 'zn_framework' ),
								"id"          => "map_icon",
								"std"         => "",
								'class'       => 'zn_full',
								"type"        => "media",
							),
							array(
								"name"        => __( "Marker animation", 'zn_framework' ),
								"description" => __( "Select an animation that the icon will use.", 'zn_framework' ),
								"id"          => "map_icon_animation",
								"std"         => "",
								"type"        => "select",
								"options"     => array( "" => __( "None", 'zn_framework' ), "DROP" => __( "Drop", 'zn_framework' ), "BOUNCE" => __( "Bounce", 'zn_framework' ) ),
							),
							array(
								"name"        => __( "Icon size", 'zn_framework' ),
								"description" => __( "Select the size of the marker icon.", 'zn_framework' ),
								"id"          => "icon_size",
								"type"        => "select",
								"options"     => $icon_sizes,
							),
						),

					),
					array(
						"name"        => __( "Zoom level", 'zn_framework' ),
						"description" => __( "Select the start zoom level you want to use for this map ( default is 14 )", 'zn_framework' ),
						"id"          => "map_zoom",
						"std"         => "14",
						"type"        => "select",
						"options"     => $zoom,
						"class"       => "",
					),
					array(
						"name"        => __( "Map Type", 'zn_framework' ),
						"description" => __( "Select the desired map type you want to use.", 'zn_framework' ),
						"id"          => "map_type",
						"std"         => "roadmap",
						"type"        => "select",
						"options"     => array(
							"ROADMAP"   => __( "Roadmap", 'zn_framework' ),
							"SATELLITE" => __( "Satellite", 'zn_framework' ),
							"TERRAIN"   => __( "Terrain", 'zn_framework' ),
							"HYBRID"    => __( "Hybrid", 'zn_framework' ),
						),
						"class" => "",
					),
					array(
						"name"        => __( "Add directions box", 'zn_framework' ),
						"description" => __( "Select if you want to add a textbox in which the user can enter a departure location and get directions to the office location (first one if there are more than one).", 'zn_framework' ),
						"id"          => "map_directions",
						"std"         => 'yes',
						"type"        => "toggle2",
						"value"       => "yes",
					),
					array(
						"name"        => __( "Directions box text", 'zn_framework' ),
						"description" => __( "Please enter the direction box text you want to use.", 'zn_framework' ),
						"id"          => "map_directions_text",
						"std"         => __( 'Visit us from...', 'zn_framework' ),
						"type"        => "text",
						'dependency'  => array( 'element' => 'map_directions', 'value' => array('yes') ),
					),

					array(
						"name"        => __( "Directions box position", 'zn_framework' ),
						"description" => __( "Please select the direction box's position.", 'zn_framework' ),
						"id"          => "map_directions_pos",
						"std"         => 'top-left',
						"type"        => "select",
						"options"     => array(
							"top-left"      => __( "Top Left", 'zn_framework' ),
							"middle-left"   => __( "Middle Left", 'zn_framework' ),
							"bottom-left"   => __( "Bottom Left", 'zn_framework' ),
							"top-right"     => __( "Top Right", 'zn_framework' ),
							"middle-right"  => __( "Middle Right", 'zn_framework' ),
							"bottom-right"  => __( "Bottom Right", 'zn_framework' ),
							"top-center"    => __( "Top Center", 'zn_framework' ),
							"bottom-center" => __( "Bottom Center", 'zn_framework' ),
						),
						'dependency' => array( 'element' => 'map_directions', 'value' => array('yes') ),
					),

					array(
						'id'          => 'show_overview',
						'name'        => __( 'Show overview map', 'zn_framework' ),
						'description' => __( 'Select if you wish to add the overview map option', 'zn_framework' ),
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => 'yes',
					),
					array(
						'id'          => 'show_streetview',
						'name'        => __( 'Show street view', 'zn_framework' ),
						'description' => __( 'Select if you wish to add the street view option', 'zn_framework' ),
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => 'yes',
					),
					array(
						'id'          => 'show_maptype',
						'name'        => __( 'Show map type', 'zn_framework' ),
						'description' => __( 'Select if you wish to add the map type option', 'zn_framework' ),
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => 'yes',
					),

				),
			),
			'styling' => array(
				'title'   => 'Styling options',
				'options' => array(

					array(
						"name"        => __( "Map Height", 'zn_framework' ),
						"description" => __( "Please select value in pixels for the map height. <br>*TIP: Use 100vh to have a full-height element.", 'zn_framework' ),
						"id"          => "map_height",
						'type'        => 'smart_slider',
						'std'         => '',
						'helpers'     => array(
							'min'  => '200',
							'max'  => '1080',
							'step' => '1',
						),
						'supports' => array('breakpoints'),
						'units'    => array('px', 'vh'),
						'live'     => array(
							'type'      => 'css',
							'css_class' => '.' . $uid,
							'css_rule'  => 'height',
							'unit'      => 'px',
						),
					),
					array(
						'id'          => 'use_custom_style',
						'name'        => __( 'Map custom style', 'zn_framework' ),
						'description' => sprintf( __( 'Use a custom map style. You can get custom styles from <a href="%s" target="_blank">%s</a>', 'zn_framework' ), $mapstyleurl, $mapstyleurl ),
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => 'yes',
					),
					array(
						'id'          => 'custom_style',
						'name'        => __( 'Normal map style', 'zn_framework' ),
						'description' => sprintf( __( 'Paste your custom style here (Javascript style array). You can get custom styles from <a href="%s" target="_blank">%s</a>', 'zn_framework' ), $mapstyleurl, $mapstyleurl  ),
						'type'        => 'textarea',
						'std'         => '',
						'dependency'  => array( 'element' => 'use_custom_style', 'value' => array('yes') ),
					),
					array(
						'id'          => 'custom_style_active',
						'name'        => __( 'Active map style (when a popup is visible)', 'zn_framework' ),
						'description' => sprintf( __( 'Paste your custom style here (Javascript style array). You can get custom styles from <a href="%s" target="_blank">%s</a>', 'zn_framework' ), $mapstyleurl, $mapstyleurl  ),
						'type'        => 'textarea',
						'std'         => '',
						'dependency'  => array( 'element' => 'use_custom_style', 'value' => array('yes') ),
					),
				),
			),
			'misc' => array(
				'title'   => 'Miscellaneous',
				'options' => array(

					array(
						"name"        => __( "Custom center point", 'zn_framework' ),
						"description" => __( "You might want to have the center point of the map onto the a side. Therefore you can custom center the map to show all markers.", 'zn_framework' ),
						"id"          => "sc_ccenter",
						"std"         => "",
						"value"       => "1",
						"type"        => "toggle2",
					),
					array(
						"name"        => __( "Marker Latitude", 'zn_framework' ),
						"description" => sprintf( __( 'Please enter the latitude value for your location. Here\'s 2 links where you can get the coordinates <a href="%s" target="_blank">LatLong.net</a> or <a href="%s" target="_blank">iTouchMap.com</a>', 'zn_framework' ), $latlong_url, $itouchmap_url ),
						"id"          => "cc_latitude",
						"std"         => "",
						"placeholder" => 'eg: 41.447390',
						"type"        => "text",
						"dependency"  => array( 'element' => 'sc_ccenter', 'value' => array('1') ),
					),
					array(
						"name"        => __( "Marker Longitude", 'zn_framework' ),
						"description" => sprintf( __( 'Please enter the longitude value for your location. Here\'s 2 links where you can get the coordinates <a href="%s" target="_blank">LatLong.net</a> or <a href="%s" target="_blank">iTouchMap.com</a>', 'zn_framework' ), $latlong_url, $itouchmap_url ),
						"id"          => "cc_longitude",
						"std"         => "",
						"placeholder" => 'eg: -72.843868',
						"type"        => "text",
						"dependency"  => array( 'element' => 'sc_ccenter', 'value' => array('1') ),
					),
					array(
						"name"        => __( "Allow Mousewheel", 'zn_framework' ),
						"description" => __( "Select if you want to allow map zooming using the mouse scroll (may interfere with page scroll).", 'zn_framework' ),
						"id"          => "map_zooming_mousewheel",
						"std"         => "",
						"type"        => "toggle2",
						"value"       => "yes",
					),
					array(
						"name"        => __( "Map localization", 'zn_framework' ),
						"description" => __( "Force the map localization to a specific language", 'zn_framework' ),
						"id"          => "map_localization",
						"std"         => "",
						"type"        => "select",
						"options"     => array( '' => __( 'Use browser language', 'zn_framework' ), 'ar' => 'ARABIC', 'eu' => 'BASQUE', 'bg' => 'BULGARIAN', 'bn' => 'BENGALI', 'ca' => 'CATALAN', 'cs' => 'CZECH', 'da' => 'DANISH', 'de' => 'GERMAN', 'el' => 'GREEK', 'en' => 'ENGLISH', 'en-AU' => 'ENGLISH (AUSTRALIAN)', 'en-GB' => 'ENGLISH (GREAT BRITAIN)', 'es' => 'SPANISH', 'eu' => 'BASQUE', 'fa' => 'FARSI', 'fi' => 'FINNISH', 'fil' => 'FILIPINO', 'fr' => 'FRENCH', 'gl' => 'GALICIAN', 'gu' => 'GUJARATI', 'hi' => 'HINDI', 'hr' => 'CROATIAN', 'hu' => 'HUNGARIAN', 'id' => 'INDONESIAN', 'it' => 'ITALIAN', 'iw' => 'HEBREW', 'ja' => 'JAPANESE', 'kn' => 'KANNADA', 'ko' => 'KOREAN', 'lt' => 'LITHUANIAN', 'lv' => 'LATVIAN', 'ml' => 'MALAYALAM', 'mr' => 'MARATHI', 'nl' => 'DUTCH', 'no' => 'NORWEGIAN', 'pl' => 'POLISH', 'pt' => 'PORTUGUESE', 'pt-BR' => 'PORTUGUESE (BRAZIL)', 'pt-PT' => 'PORTUGUESE (PORTUGAL)', 'ro' => 'ROMANIAN', 'ru' => 'RUSSIAN', 'sk' => 'SLOVAK', 'sl' => 'SLOVENIAN', 'sr' => 'SERBIAN', 'sv' => 'SWEDISH', 'tl' => 'TAGALOG', 'ta' => 'TAMIL', 'te' => 'TELUGU', 'th' => 'THAI', 'tr' => 'TURKISH', 'uk' => 'UKRAINIAN', 'vi' => 'VIETNAMESE', 'zh-CN' => 'CHINESE (SIMPLIFIED)', 'zh-TW' => 'CHINESE (TRADITIONAL)'),
						"class"       => "",
					),

				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#qtw5ShCYcNY',
				// 'docs'    => 'https://my.hogash.com/documentation/google-map/',
				'copy'      => $uid,
				'general'   => true,
				'custom_id' => true,
			) ),

		);

		return $options;
	}

	function element() {
		if ( ! $this->validation( 'locations' ) ) {
			echo '<div class="zn-pb-notification">' . __( 'Please configure the element options and add <u>at least one location</u>.', 'zn_framework' ) . '</div>';
			return;
		} else {
			if ( ! $this->validation( 'key' ) && ZNB()->utility->isActiveEditor() ) {
				$key_notice = sprintf(
				'%s <a href="%s" target="_blank">%s</a>.',
				__( 'Please add a <u>Google Maps API Key</u>.', 'zn_framework' ),
				esc_url( 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,places_backend&keyType=CLIENT_SIDE&reusekey=true' ),
				__( 'Generate one here', 'zn_framework' )
			);
				echo '<div class="zn-pb-notification">' . $key_notice . '</div>';
				return;
			}
		}

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid     = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes( $options );
		$classes[] = 'zn-googleMap';

		$attributes[] = zn_get_element_attributes( $options, $this->opt( 'custom_id', $uid ) );
		$attributes[] = 'class="' . implode( ' ', $classes ) . '"'; ?>

		<div <?php echo zn_join_spaces( $attributes ); ?>>

			<div id="zn_google_map_<?php echo esc_attr( $uid ); ?>" class="zn-mapCanvas"></div><!-- /.zn-mapCanvas -->

			<?php if ( $this->opt( 'map_directions' ) === 'yes' ) {
			?>
				<div class="zn-visit-container zn-visit--pos-<?php echo esc_attr( $this->opt( 'map_directions_pos', 'top-left' ) ); ?>">
					<input type="text" required placeholder="<?php echo esc_attr( $this->opt( 'map_directions_text', __( 'Visit us from...', 'zn_framework' ) ) ); ?>" class="zn-visit-startLocation" />
					<span class="zn-visit-removeRoute">
						<?php echo znb_get_svg( array('icon' => 'znb_close-thin' ) ) ?>
					</span>
				</div>
			<?php
		} ?>

		</div><!-- /.zn-googleMap -->

	<?php
	}

	function scripts() {
		$params   = array();
		$params[] = ( $this->opt( 'map_localization', '' ) ? 'language=' . $this->opt( 'map_localization' ) : '' );
		$params[] = ( $this->opt( 'map_apikey', '' ) ? 'key=' . $this->opt( 'map_apikey' ) : '' );
		wp_enqueue_script( 'zn_google_api', 'https://maps.googleapis.com/maps/api/js?v=3.exp' . implode( '&', $params ), array('jquery'), ZNB()->getVersion(), true );
		wp_enqueue_script( 'zn_gmap', ZNB()->getFwUrl( 'inc/modules/google_map/assets/gmaps.js' ), array('jquery'), ZNB()->getVersion(), true );
	}

	// Loads the required JS
	function js() {
		$locations           = $this->opt( 'locations' ) ? $this->opt( 'locations' ) : array();
		$zoom                = $this->opt( 'map_zoom' ) ? $this->opt( 'map_zoom' ) : '14';
		$terrain             = $this->opt( 'map_type' ) ? $this->opt( 'map_type' ) : 'ROADMAP';
		$scroll              = $this->opt( 'map_zooming_mousewheel' ) === 'yes' ? 'true' : 'false';
		$routingColor        = false;
		$uid                 = $this->data['uid'];
		$mainOfficeLocation  = '[0,0]';
		$markers             = '';
		$use_custom_style    = $this->opt( 'use_custom_style', '' );
		$custom_style        = 'null';
		$custom_style_active = 'null';
		if ( $use_custom_style === 'yes' ) {
			$custom_style        = $this->opt( 'custom_style', 'null' );
			$custom_style_active = $this->opt( 'custom_style_active', 'null' );
		}
		$show_overview   = $this->opt( 'show_overview' ) === 'yes' ? 'true' : 'false';
		$show_streetview = $this->opt( 'show_streetview' ) === 'yes' ? 'true' : 'false';
		$show_maptype    = $this->opt( 'show_maptype' ) === 'yes' ? 'true' : 'false';

		if ( ! empty( $locations ) ) {
			$mainOfficeLocation = '[' . $locations[0]['map_latitude'] . ', ' . $locations[0]['map_longitude'] . ']';
			// Custom Center map
			if ( $this->opt( 'sc_ccenter', '' ) == 1 ) {
				if ( $cc_lat = $this->opt( 'cc_latitude', '' ) && $cc_lon = $this->opt( 'cc_longitude', '' ) ) {
					$mainOfficeLocation = '[' . $this->opt( 'cc_latitude', '' ) . ', ' . $this->opt( 'cc_longitude', '' ) . ']';
				}
			}

			//** Build the markers [[lat, long, tooltip, icon, size, animation, anchor],...]
			$markers = '[';
			foreach ( $locations as $location ) {
				$latitude  = preg_match( "/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", $location['map_latitude'], $matches ) ? $location['map_latitude'] : false;
				$longitude = preg_match( "/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/", $location['map_longitude'], $matches ) ? $location['map_longitude'] : false;

				if ( empty( $latitude ) || empty( $longitude ) ) {
					continue;
				}

				$tooltip            = ! empty( $location['tooltip'] ) ? $location['tooltip'] : '';
				$icon_size          = ! empty( $location['icon_size'] ) ? $location['icon_size'] : '20';
				$map_icon_animation = ! empty( $location['map_icon_animation'] ) ? $location['map_icon_animation'] : '';
				$markers .= sprintf( '[%1$s,%2$s,\'%3$s\',\'%4$s\',%5$s,\'%6$s\',%7$s],',
						$latitude,
						$longitude,
						preg_replace( "/\r|\n/", "", wpautop( addslashes( $tooltip ) ) ),
						$location['map_icon'],
						$icon_size,
						$map_icon_animation,
						''
					);
			}
			$markers .= ']';

			$zn_g_map = array( 'gmap' . $this->data['uid'] => "
					var zn_google_map_$uid = new Zn_google_map('zn_google_map_$uid', $mainOfficeLocation, '$routingColor', $markers, '$terrain', $zoom, $scroll, $custom_style, $custom_style_active, $show_overview, $show_streetview, $show_maptype);
					zn_google_map_$uid.init_map();
					window.addEventListener('zn_tabs_refresh', function(){ zn_google_map_$uid.refreshUI(); }, false);
				");
			return $zn_g_map;
		};

		return false;
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css() {
		$css = '';
		$uid = $this->data['uid'];

		if ( $height = $this->opt( 'map_height', '' ) ) {
			$css .= zn_smart_slider_css( $height, '.' . $uid, 'height', 'px' );
		}
		return $css;
	}


	function validation( $which ) {
		$is_ok      = true;
		$map_apikey = $this->opt( 'map_apikey', '' );
		$locations  = $this->opt( 'locations', '' );
		if ( $which == 'locations' && empty( $locations ) ) {
			$is_ok = false;
		} elseif ( $which == 'key' && empty( $map_apikey ) ) {
			$is_ok = false;
		}
		return $is_ok;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_GoogleMap( array(
	'id'          => 'ZnGoogleMap',
	'name'        => __( 'Google Map', 'zn_framework' ),
	'description' => __( 'This element will render a Google Map.', 'zn_framework' ),
	'level'       => 3,
	'category'    => 'Content, Fullwidth',
	'legacy'      => false,
	'keywords'    => array('maps'),
	'scripts'     => true,
	// 'styles' => true,
) ) );
