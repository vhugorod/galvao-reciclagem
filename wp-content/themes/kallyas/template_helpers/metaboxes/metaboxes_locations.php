<?php if(! defined('ABSPATH')){ return; }

	$zn_meta_locations = array(
		array( 	'title' =>  __( 'Post Options', 'zn_framework' ), 'slug'=>'post_options', 'page'=>array('post'), 'context'=>'side', 'priority'=>'default' ),
		array( 	'title' =>  __( 'Page Options', 'zn_framework' ), 'slug'=>'page_options', 'page'=>array('page', 'product'), 'context'=>'side', 'priority'=>'default' ),
		array( 	'title' =>  __( 'Portfolio Options', 'zn_framework' ), 'slug'=>'portfolio_options', 'page'=>array('portfolio', 'showcase'), 'context'=>'side', 'priority'=>'default' ),
		array( 	'title' =>  __( 'General Options', 'zn_framework' ), 'slug'=>'portfolio_g_options', 'page'=>array('portfolio'), 'context'=>'normal', 'priority'=>'high' ),
	);
