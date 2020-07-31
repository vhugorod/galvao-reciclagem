<?php

/*
*	Sanitize theme options
*	Will convert the string to a database sage option string
*/
function zn_fix_insecure_content($url){
	return preg_replace('#^https?://#', '//', $url);
}

function zn_uid( $prepend = 'eluid', $length = 8 ){
	return $prepend . substr(str_shuffle(MD5(microtime())), 0, $length);
}

function zn_create_folder( &$folder, $addindex = true ) {
	if( is_dir( $folder ) && $addindex == false) {
		return true;
	}


	$created = wp_mkdir_p( trailingslashit( $folder ) );

	// Set permissions
	@chmod( $folder, 0777 );

	if($addindex == false) { return $created; }

	// Add an index.php file
	$index_file = trailingslashit( $folder ) . 'index.php';
	if ( is_file( $index_file ) ) {
		return $created;
	}

	$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
	$fs->put_contents( $index_file, "<?php\r\necho 'Directory browsing is not allowed!';\r\n?>" );

	return $created;
}

function zn_delete_folder( $path ) {
	//echo $path;
	//check if folder exists
	if( is_dir( $path) )
	{

		$it = new RecursiveDirectoryIterator($path);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file) {
			if ($file->getFilename() === '.' || $file->getFilename() === '..')
			{
				continue;
			}

			if ( $file->isDir() ){
				rmdir($file->getRealPath());
			}
			else {
				unlink($file->getRealPath());
			}
		}

		rmdir($path);
	}
}

function find_file( $folder , $extension ) {
	$files = scandir( $folder );

	foreach($files as $file)
	{
		if(strpos(strtolower($file), $extension )  !== false && $file[0] != '.')
		{
			return $file;
		}
	}

	return false;
}


/**
 * Function to return type of target for links
 */
if ( !function_exists( 'zn_get_target' ) )
{
	function zn_get_target( $target = '_self' )
	{

		$link_target = '';

		if ( $target == '_blank' || $target == '_self' )
		{
			$link_target = 'target="' . $target . '"';
		}

		return apply_filters('zn_default_link_target_html', $link_target, $target);
	}
}


/**
 * Display a list of link targets
 */
if ( !function_exists( 'zn_get_link_targets' ) )
{
	function zn_get_link_targets( $exclude = array() )
	{
		$targets = apply_filters('zn_default_link_target_type', array(
			'_self' => __( "Same window", 'zn_framework' ),
			'_blank' => __( "New window", 'zn_framework' ),
		) );

		if ( !empty( $exclude ) )
		{
			foreach ( $exclude as $v )
			{
				if ( array_key_exists( $v, $targets ) )
				{
					unset( $targets[ $v ] );
				}
			}
		}
		return $targets;
	}
}

/*--------------------------------------------------------------------------------------------------
	zn_extract_link - This function will return the option
	@accepts : A link option
	@returns : array containing a link start and link end HTML
--------------------------------------------------------------------------------------------------*/
function zn_extract_link( $link_array , $class = false , $attributes = false, $def_start = '', $def_end = '', $def_url = false ){

	if($def_url && empty($link_array['url'])){
		$link_array['url'] = trim($def_url);
	}

	if ( !is_array( $link_array ) || empty( $link_array['url'] ) ) {
		$link['start'] = $def_start ? $def_start : '';
		$link['end'] = $def_end ? $def_end : '';
	}
	else{

		$title 	= ! empty( $link_array['title'] ) ? 'title="'.$link_array['title'].'"' : '';
		$targetHtml = ! empty( $link_array['target'] ) ? zn_get_target( esc_attr( $link_array['target'] ) ) : '';
		// Allow others to modify the URL based on link target
		$link_array = apply_filters( 'zn_process_link_extraction', $link_array );
		$link_no_opener = $link_array['target'] === '_blank' ? 'rel="noopener"' : '';
		$link 	= array( 'start' => '<a href="'.esc_url( $link_array['url'] ).'" '.$attributes.' class="'.$class.'" '.$title.' '.$targetHtml.' '.$link_no_opener.' '.zn_schema_markup('url').'>' , 'end' => '</a>' );
	}

	return $link;

}

/*--------------------------------------------------------------------------------------------------
	zn_extract_link_title - This function will return the title string from link array
	@accepts : An link option
	@returns : string
--------------------------------------------------------------------------------------------------*/
function zn_extract_link_title( $link_array, $esc = false ){

	return is_array( $link_array ) && !empty( $link_array['title'] ) ? ( $esc ? esc_attr( $link_array['title'] ) : $link_array['title'] )  : '';

}

/*--------------------------------------------------------------------------------------------------
	Minimifyes CSS code
--------------------------------------------------------------------------------------------------*/
function zn_minimify( $css_code ){

	// Minimiy CSS
	$css_code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css_code); // Remove comments
	$css_code = str_replace(': ', ':', $css_code); // Remove space after colons
	$css_code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css_code); // Remove whitespace

	return $css_code;
}


/*--------------------------------------------------------------------------------------------------
	Preety print
--------------------------------------------------------------------------------------------------*/
function print_z($string, $hidden = false) {
	echo '<pre '. ( $hidden ? 'style="display:none"':'' ) .'>';
		print_r($string);
	echo '</pre>';
}

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function zn_get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	return $sizes;
}

/**
 * Get size information for a specific image size.
 *
 * @uses   zn_get_image_sizes()
 * @param  string $size The image size for which to retrieve data.
 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
 */
function zn_get_image_size( $size ) {
	$sizes = zn_get_image_sizes();

	if ( isset( $sizes[ $size ] ) ) {
		return $sizes[ $size ];
	}

	return false;
}

function zn_get_image_sizes_list( $custom = false ){

	$image_sizes = zn_get_image_sizes();

	$sizes = array ();
	if ( ! empty( $image_sizes ) ) {
		foreach ( $image_sizes as $key => $value ) {
			$sizes[ $key ] = ucwords( str_replace( '_', ' ', $key ) ) . sprintf( ' - %d x %d', $value['width'], $value['height'] );
		}
	}

	if( $custom ){
		$sizes = array_merge($sizes, $custom);
	}

	return $sizes;
}

/**
 * Implodes an array with spaces. Useful for classes or attributes.
 * @param  array $arr array
 * @return string      string united
 */
if( ! function_exists('zn_join_spaces') ) {
	function zn_join_spaces( $arr )
	{
		if ( empty( $arr ) ) {
			return '';
		}
		return implode( ' ', array_unique( $arr ) );
	}
}



/**
 * Load video iframe from link
 */
if ( !function_exists( 'hgfw_get_video_from_link' ) )
{
	/**
	 * Load video iframe from link
	 * @param string $url
	 * @param null $css
	 * @param int $width
	 * @param int $height
	 * @return mixed|null|string
	 */
	function hgfw_get_video_from_link( $url, $css = null, $width = 425, $height = 239, $v_attr = null )
	{
		// Save old string in case no video is provided
		$old_url = $url;
		$v_url = parse_url( $url );

		$extra_options = array();

		if ( !empty( $v_attr ) && is_array( $v_attr ) )
		{
			$extra_options[] = 'autoplay=' . ( isset( $v_attr[ 'autoplay' ] ) && !empty( $v_attr[ 'autoplay' ] ) ? $v_attr[ 'autoplay' ] : 0 );
			$extra_options[] = 'loop=' . ( isset( $v_attr[ 'loop' ] ) && !empty( $v_attr[ 'loop' ] ) ? $v_attr[ 'loop' ] : 0 );
			$extra_options[] = 'controls=' . ( isset( $v_attr[ 'controls' ] ) && !empty( $v_attr[ 'controls' ] ) ? $v_attr[ 'controls' ] : 0 );
		}

		if ( hgfw_is_youtube_url( $v_url[ 'host' ] ) )
		{

			if ( !empty( $v_attr ) && is_array( $v_attr ) )
			{
				// Youtube Specific
				$extra_options[] = 'iv_load_policy=3';
				$extra_options[] = 'feature=player_embedded';
				$extra_options[] = 'modestbranding=' . ( isset( $v_attr[ 'yt_modestbranding' ] ) && !empty( $v_attr[ 'yt_modestbranding' ] ) ? $v_attr[ 'yt_modestbranding' ] : 1 );
				$extra_options[] = 'autohide=' . ( isset( $v_attr[ 'yt_autohide' ] ) && !empty( $v_attr[ 'yt_autohide' ] ) ? $v_attr[ 'yt_autohide' ] : 1 );
				$extra_options[] = 'showinfo=' . ( isset( $v_attr[ 'yt_showinfo' ] ) && !empty( $v_attr[ 'yt_showinfo' ] ) ? $v_attr[ 'yt_showinfo' ] : 0 );
				$extra_options[] = 'rel=' . ( isset( $v_attr[ 'yt_rel' ] ) && !empty( $v_attr[ 'yt_rel' ] ) ? $v_attr[ 'yt_rel' ] : 0 );
				$extra_options[] = 'enablejsapi=' . ( isset( $v_attr[ 'enablejsapi' ] ) && !empty( $v_attr[ 'enablejsapi' ] ) ? $v_attr[ 'enablejsapi' ] : 0 );
				$extra_options[] = 'origin=' . urlencode( site_url() );
			}

			$yt_id = hgfw_grab_youtube_id($url);
			if( $yt_id ){
				$url = sprintf( '<iframe class="%s" width="%s" height="%s" src="//www.youtube.com/embed/%s?%s" %s allowfullscreen></iframe>',
					$css,
					$width,
					$height,
					$yt_id,
					implode( '&amp;', $extra_options ),
					zn_schema_markup( 'video' )
				);
			}

		}

		elseif ( hgfw_is_vimeo_url( $v_url[ 'host' ] ) )
		{
			if ( !empty( $v_attr ) && is_array( $v_attr ) )
			{
				// Vimeo Specific
				$extra_options[] = 'title=' . ( isset( $v_attr[ 'vim_title' ] ) && !empty( $v_attr[ 'vim_title' ] ) ? $v_attr[ 'vim_title' ] : 1 );
				$extra_options[] = 'byline=' . ( isset( $v_attr[ 'vim_byline' ] ) && !empty( $v_attr[ 'vim_byline' ] ) ? $v_attr[ 'vim_byline' ] : 1 );
				$extra_options[] = 'portrait=' . ( isset( $v_attr[ 'vim_portrait' ] ) && !empty( $v_attr[ 'vim_portrait' ] ) ? $v_attr[ 'vim_portrait' ] : 1 );
			}

			$vm_id = hgfw_grab_vimeo_id($url);
			if( $vm_id ){
				$url = sprintf( '<iframe class="%s" width="%s" height="%s" src="//player.vimeo.com/video/%s?%s" %s allowfullscreen></iframe>',
					$css,
					$width,
					$height,
					$vm_id,
					implode( '&amp;', $extra_options ),
					zn_schema_markup( 'video' )
				);
			}
		}
		else
		{
			$url = sprintf( '<iframe class="%s" width="%s" height="%s" src="%s" %s allowfullscreen></iframe>',
					$css,
					$width,
					$height,
					$old_url,
					zn_schema_markup( 'video' )
				);
		}

		// If no video link was provided return the full link
		return ( $url != $old_url ) ? $url : null;
	}
}


if ( !function_exists( 'hgfw_is_youtube_url' ) )
{
	function hgfw_is_youtube_url($url){
		return $url == 'www.youtube.com' || $url == 'youtube.com' || $url == 'www.youtu.be' || $url == 'youtu.be';
	}
}

if ( !function_exists( 'hgfw_is_vimeo_url' ) )
{
	function hgfw_is_vimeo_url($url){
		return $url == 'vimeo.com' || $url == 'www.vimeo.com';
	}
}
if ( !function_exists( 'hgfw_grab_youtube_id' ) )
{
	function hgfw_grab_youtube_id($url){
		$video_id = false;
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			$video_id = $match[1];
		}
		return $video_id;
	}
}
if ( !function_exists( 'hgfw_grab_vimeo_id' ) )
{
	function hgfw_grab_vimeo_id($url){
		$video_id = false;
		if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $match)) {
			$video_id = $match[5];
		}
		return $video_id;
	}
}
