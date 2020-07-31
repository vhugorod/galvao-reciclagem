<?php
/**
 * Theme options > General Options  > Favicon options
 */

/*--------------------------------------------------------------------------------------------------
	Sidebar Generator
	--------------------------------------------------------------------------------------------------*/

$admin_options[] = array(
	'slug'          => 'sidebar_options', // subpage
	'parent'        => 'unlimited_sidebars', // master page
	'id'            => 'sidebar_size',
	'name'          => 'Sidebar Size',
	'description'   => 'Select the desired sidebar size.',
	'type'          => 'select',
	'options'	    => array(
		'3' => '3/12',
		'4' => '4/12'
	)
);
/*--------------------------------------------------------------------------------------------------
	Unlimited Sidebars
--------------------------------------------------------------------------------------------------*/
// Unlimited Sidebars

$admin_options[] = array(
	'slug'          => 'unlimited_sidebars', // subpage
	'parent'        => 'unlimited_sidebars', // master page
	'id'            => 'unlimited_sidebars',
	'name'          => 'Unlimited Sidebars',
	'description'   => 'Here you can create unlimited sidebars that you can use all over the theme.',
	'type'          => 'group',
	'sortable'      => false,
	'element_title' => 'sidebar_name',
	'subelements'   => array(
							array(
								'id'          => 'sidebar_name',
								'name'        => 'Sidebar Name',
								'description' => 'Please enter a name for this sidebar. Please note that the name should only contain alphanumeric characters',
								'type'        => 'text',
								'supports'    => 'block'
							),
					)
);


$admin_options[] = array (
	'slug'          => 'unlimited_sidebars', // subpage
	'parent'        => 'unlimited_sidebars', // master page
	"name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
	"description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
	"id"          => "usbo_title",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#M7TcpipwAKw', __( "Click here to access the video tutorial for this section's options.", 'zn_framework' ), array(
	'slug'        => 'unlimited_sidebars',
	'parent'      => 'unlimited_sidebars'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
	'slug'        => 'unlimited_sidebars',
	'parent'      => 'unlimited_sidebars',
));

// Sidebars settings
$sidebar_options = array( 'right_sidebar' => 'Right sidebar' , 'left_sidebar' => 'Left sidebar' , 'no_sidebar' => 'No sidebar' );
$admin_options[] = array(
	'slug'        => 'sidebar_settings',
	'parent'      => 'unlimited_sidebars',
	'id'          => 'archive_sidebar',
	'name'        => 'Sidebar on archive pages',
	'description' => 'Please choose the sidebar position for the archive pages.',
	'type'        => 'sidebar',
	'class'     => 'zn_full',
	'std'       => array (
		'layout' => 'sidebar_right',
		'sidebar' => 'default_sidebar',
	),
	'supports'  => array(
		'default_sidebar' => 'defaultsidebar',
		'sidebar_options' => $sidebar_options
	),
);

$admin_options[] = array(
	'slug'        => 'sidebar_settings',
	'parent'      => 'unlimited_sidebars',
	'id'          => 'blog_sidebar',
	'name'        => 'Sidebar on Blog',
	'description' => 'Please choose the sidebar position for the blog page.',
	'type'        => 'sidebar',
	'class'     => 'zn_full',
	'std'       => array (
		'layout' => 'sidebar_right',
		'sidebar' => 'default_sidebar',
	),
	'supports'  => array(
		'default_sidebar' => 'defaultsidebar',
		'sidebar_options' => $sidebar_options
	),
);

$admin_options[] = array(
	'slug'        => 'sidebar_settings',
	'parent'      => 'unlimited_sidebars',
	'id'          => 'single_sidebar',
	'name'        => 'Sidebar on single blog post',
	'description' => 'Please choose the sidebar position for the single blog posts.',
	'type'        => 'sidebar',
	'class'     => 'zn_full',
	'std'       => array (
		'layout' => 'sidebar_right',
		'sidebar' => 'default_sidebar',
	),
	'supports'  => array(
		'default_sidebar' => 'defaultsidebar',
		'sidebar_options' => $sidebar_options
	),
);

$admin_options[] = array(
	'slug'        => 'sidebar_settings',
	'parent'      => 'unlimited_sidebars',
	'id'          => 'page_sidebar',
	'name'        => 'Sidebar on pages',
	'description' => 'Please choose the sidebar position for the pages.',
	'type'        => 'sidebar',
	'class'     => 'zn_full',
	'std'       => array (
		'layout' => 'sidebar_right',
		'sidebar' => 'default_sidebar',
	),
	'supports'  => array(
		'default_sidebar' => 'defaultsidebar',
		'sidebar_options' => $sidebar_options
	),
);

// add options for all post types
$post_types = get_post_types( array(
	'public' => true,
	'publicly_queryable' => true
), 'objects' );

// Only add options if the post type is not in the exclude list
$exclude_post_type = array(
	'zn_layout',
	'znpb_template_mngr',
	'attachment',
	'product',
	'post',
);

$args = array(
	'_builtin'    => false,
);

$output = 'names';
$archived_custom_post_types = get_post_types( $args, 'object' );

// Create a list of post types
foreach ( $post_types as $key => $post_type_object ) {

	if ( ! in_array( $key, $exclude_post_type ) ) {

		$sidebar_options_with_default = array_merge( array(
			'' => 'Use default setting.',
		), $sidebar_options);

		$admin_options[] = array(
			'slug'        => 'sidebar_settings',
			'parent'      => 'unlimited_sidebars',
			'id'          => $key . '_sidebar',
			'name'        => 'Sidebar on ' . $post_type_object->label . ' single page',
			'description' => 'Please choose the sidebar position.',
			'type'        => 'sidebar',
			'class'     => 'zn_full',
			'std'       => array (
				'layout' => '',
				'sidebar' => '',
			),
			'supports'  => array(
				'default_sidebar' => 'defaultsidebar',
				'sidebar_options' => $sidebar_options_with_default,
				'sidebars_options'=> array(
					'' => 'Use default setting',
				)
			),
		);

		$excluded_archive_post_types = array(
			'portfolio',
			'documentation'
		);

		// Add options for archive
		if( $post_type_object->has_archive && ! in_array( $post_type_object->name, $excluded_archive_post_types ) ){
			$admin_options[] = array(
				'slug'        => 'sidebar_settings',
				'parent'      => 'unlimited_sidebars',
				'id'          => $key . '_archive_sidebar',
				'name'        => 'Sidebar on ' . $post_type_object->label . ' archive page.',
				'description' => 'Please choose the sidebar position.',
				'type'        => 'sidebar',
				'class'     => 'zn_full',
				'std'       => array (
					'layout' => '',
					'sidebar' => '',
				),
				'supports'  => array(
					'default_sidebar' => 'defaultsidebar',
					'sidebar_options' => $sidebar_options_with_default,
					'sidebars_options'=> array(
						'' => 'Use default setting',
					)
				),
			);
		}

	}
}