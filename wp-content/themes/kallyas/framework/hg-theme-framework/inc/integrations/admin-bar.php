<?php

class ZnHgTFw_AdminBarIntegration{
	function __construct(){
		// Add theme options to admin Bar
		$action = 'admin_' . 'bar_menu';
		add_action( $action, array( __CLASS__, 'addThemeOptionsAdminBar' ), 100 );
	}

	/**
	 * Add the theme Options menu entry in the admin bar
	 * @param $wp_admin_bar
	 * @see functions.php
	 */
	static public function addThemeOptionsAdminBar( $wp_admin_bar )
	{
		if ( is_user_logged_in() )
		{
			if ( current_user_can( 'manage_options' ) )
			{
				$mainMenuArgs = array(
					'id' => 'znhgtfw-theme-options-menu-item',
					'title' => ZNHGTFW()->getThemeName() . ' ' . esc_html__( 'Options', 'zn_framework' ),
					'href' => ZNHGTFW()->getComponent('utility')->get_options_page_url(),
					'meta' => array(
						'class' => 'znhgtfw-theme-options-menu-item'
					)
				);
				$wp_admin_bar->add_node( $mainMenuArgs );

				//#! Make sure the Zion Builder plugin is installed and active
				if(! class_exists('ZionBuilder')) {
					return;
				}

				// Set parent
				$parentMenuID = $mainMenuArgs[ 'id' ];

				// Add the theme's pages
				$pages = ZNHGTFW()->getComponent('utility')->get_theme_options_pages();

				//[since 4.12]  Add node for Dashboard and its submenus
				$wp_admin_bar->add_node( array(
					'parent' => 'znhgtfw-theme-options-menu-item',
					'id' => 'znhgtfw-theme-options-submenu-item-dashboard',
					'title' => esc_html__( 'Dashboard', 'zn_framework' ),
					'href' => ZNHGTFW()->getComponent('utility')->get_options_page_url(),
				) );
				$wp_admin_bar->add_node( array(
					'parent' => 'znhgtfw-theme-options-submenu-item-dashboard',
					'id' => 'znhgtfw-theme-options-submenu-item-dashboard-register',
					'title' => esc_html__( 'Theme Registration', 'zn_framework' ),
					'href' => ZNHGTFW()->getComponent('utility')->get_options_page_url() . '#zn-about-tab-registration-dashboard',
				) );
				$wp_admin_bar->add_node( array(
					'parent' => 'znhgtfw-theme-options-submenu-item-dashboard',
					'id' => 'znhgtfw-theme-options-submenu-item-dashboard-addons',
					'title' => esc_html__( 'Theme Addons', 'zn_framework' ),
					'href' => ZNHGTFW()->getComponent('utility')->get_options_page_url() . '#zn-about-tab-addons-dashboard',
				) );
				$wp_admin_bar->add_node( array(
					'parent' => 'znhgtfw-theme-options-submenu-item-dashboard',
					'id' => 'znhgtfw-theme-options-submenu-item-dashboard-demos',
					'title' => esc_html__( 'Theme Demos', 'zn_framework' ),
					'href' => ZNHGTFW()->getComponent('utility')->get_options_page_url() . '#zn-about-tab-dummy_data-dashboard',
				) );


				if ( !empty( $pages ) )
				{
					foreach ( $pages as $slug => $entry )
					{
						$menuID = 'znhgtfw-theme-options-menu-item-' . $slug;
						$menuUrl = admin_url( ZNHGTFW()->getComponent('utility')->get_options_page_base_url() . '?page=zn_tp_' . $slug );
						$title = isset($entry[ 'title' ]) ? $entry[ 'title' ] : '';
						$submenuArgs = array(
							'parent' => $parentMenuID,
							'id' => $menuID,
							'title' => $title,
							'href' => $menuUrl,
						);
						$wp_admin_bar->add_node( $submenuArgs );

						// check for submenus
						if ( isset( $entry[ 'submenus' ] ) && !empty( $entry[ 'submenus' ] ) )
						{
							foreach ( $entry[ 'submenus' ] as $item )
							{
								// Let's avoid duplicates
								if ( strcasecmp( $title, $item[ 'title' ] ) == 0 )
								{
									continue;
								}

								$submenuArgs = array(
									'parent' => $menuID,
									'id' => 'znhgtfw-theme-options-submenu-item-' . $item[ 'slug' ],
									'title' => $item[ 'title' ],
									'href' => $menuUrl . '#' . $item[ 'slug' ],
								);
								$wp_admin_bar->add_node( $submenuArgs );
							}
						}
					}
				}
			}
		}
	}
}

return new ZnHgTFw_AdminBarIntegration();
