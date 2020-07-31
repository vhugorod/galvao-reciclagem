<?php
if( ! function_exists( 'zn_hex_to_rgb' ) ){
	function zn_hex_to_rgb($hex){
		$hex = str_replace('#', '', $hex);
		if ( strlen($hex) == 3 ) {
			$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
		}
		else{
			$hex = substr($hex,0,2).substr($hex,2,2).substr($hex,4,2);

		}

		return $hex;
	}
}

if( ! function_exists( 'zn_hex2rgb' ) ){
	function zn_hex2rgb($hex) {
	   $hex = str_replace('#', '', $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
}

if( ! function_exists( 'zn_hex2rgb_str' ) ){
	function zn_hex2rgb_str($hex){
		$hex = zn_hex2rgb($hex);
		return 'rgb('.$hex[0].','.$hex[1].','.$hex[2].')';
	}
}

if( ! function_exists( 'zn_hex2rgba' ) ){
	function zn_hex2rgba($hex, $percent = 100) {
		$rgb = zn_hex2rgb($hex);
		$argb = array($rgb[0], $rgb[1], $rgb[2], $percent/100);
		return $argb;
	}
}

if( ! function_exists( 'zn_hex2rgba_str' ) ){
	function zn_hex2rgba_str($hex, $percent = 100){
		$argb = zn_hex2rgba($hex, $percent);
		return 'rgba('.$argb[0].','.$argb[1].','.$argb[2].','.$argb[3].')';
	}
}

if( ! function_exists( 'adjustBrightness' ) ){
	function adjustBrightness($hex, $percentage_adjuster) {
		// fallback if rgba color
		if(strpos($hex, 'rgba') === true){
			return $hex;
		}

		// Steps should be between . Negative = darker, positive = lighter
		$percentage_adjuster = round( $percentage_adjuster/100,2 );

		// Format the hex color string
		  $hex = str_replace('#','',$hex);
		$r = (strlen($hex) == 3)? hexdec(substr($hex,0,1).substr($hex,0,1)):hexdec(substr($hex,0,2));
		$g = (strlen($hex) == 3)? hexdec(substr($hex,1,1).substr($hex,1,1)):hexdec(substr($hex,2,2));
		$b = (strlen($hex) == 3)? hexdec(substr($hex,2,1).substr($hex,2,1)):hexdec(substr($hex,4,2));
		$r = round($r - (max(1,$r)*$percentage_adjuster));
		$g = round($g - (max(1,$g)*$percentage_adjuster));
		$b = round($b - (max(1,$b)*$percentage_adjuster));

		return '#'.str_pad(dechex( max(0,min(255,$r)) ),2,'0',STR_PAD_LEFT)
			.str_pad(dechex( max(0,min(255,$g)) ),2,'0',STR_PAD_LEFT)
			.str_pad(dechex( max(0,min(255,$b)) ),2,'0',STR_PAD_LEFT);

	}
}

if( ! function_exists( 'adjustBrightnessByStep' ) ){
	function adjustBrightnessByStep($hex, $diff) {
		$rgb = str_split(trim($hex, '# '), 2);

		foreach ($rgb as &$hex) {
			$dec = hexdec($hex);
			if ($diff >= 0) {
				$dec += $diff;
			}
			else {
				$dec -= abs($diff);
			}
			$dec = max(0, min(255, $dec));
			$hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
		}

		return '#'.implode($rgb);

	}
}

if( ! function_exists( 'get_brightness' ) ){
	function get_brightness($hex, $compare = false) {

		// strip off any leading #
		$hex = zn_hex_to_rgb($hex);

		$c_r = hexdec(substr($hex, 0, 2));
		$c_g = hexdec(substr($hex, 2, 2));
		$c_b = hexdec(substr($hex, 4, 2));

		$brighntess = (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;

		if ( $compare ) {
			$brighntess = $brighntess > $compare ? true : false;
		}

		return $brighntess;
	}
}
