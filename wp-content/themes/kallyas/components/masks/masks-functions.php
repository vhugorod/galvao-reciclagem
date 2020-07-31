<?php if(! defined('ABSPATH')) { return; }

/**
 * Display the custom bottom mask markup
 *
 * @param  [type] $mask The mask ID
 *
 * @return [type]     HTML Markup to be used as mask
 */
if(!function_exists('zn_bottommask_markup')) {
	function zn_bottommask_markup( $mask, $bgcolor = false, $pos = 'bottom', $optionBG = null, $bgHeight = 100 )
    {
        if ( $mask == 'none' ) {

            echo '<div class="zn_header_'.$pos.'_style"></div>';

        }
        elseif( $mask == 'custom' )
        {
            //** Set the background image for the container
            $style = '';
            if ( !empty($optionBG) && isset( $optionBG['image'] ) ){
                $style = "background-image: url('".$optionBG['image']."');";
                $style .= 'background-repeat:'. $optionBG['repeat'].';';
                $style .= 'background-position:'. $optionBG['position']['x'].' '.$optionBG['position']['y'].';';
                $style .= 'background-attachment:'. $optionBG['attachment'].';';
                $style .= 'background-size:'. $optionBG['size'].';';
            }
            if( ! empty($bgHeight)){
                $style .= 'height:'.$bgHeight.'px;';
            }

            echo '<div class="kl-mask kl-mask--custom '.esc_attr( $pos == 'top' ? 'kl-topmask' : 'kl-bottommask' ).'" style="'.esc_attr($style).'">';
            do_action( 'znhgpb_section_mask_content', $mask, $bgcolor, $pos );
            echo '</div>';
        }
        else {
            $classes[] = 'kl-mask';
            $classes[] = 'kl-' . $pos . 'mask';
            $classes[] = 'kl-mask--' . $mask;
            $classes[] = 'kl-mask--' . zget_option( 'zn_main_style', 'color_options', false, 'light' );

            echo '<div class="'. implode(' ', $classes) .'">';

            if ( strpos($mask, 'mask3') !== false ) {
                include(locate_template('components/masks/mask3.php'));
            }
            else if ( strpos($mask, 'mask4') !== false ) {
                include(locate_template('components/masks/mask4.php'));
            }
            else if ( strpos($mask, 'mask5') !== false ) {
                include(locate_template('components/masks/mask5.php'));
            }
            else if ( strpos($mask, 'mask6') !== false ) {
                include(locate_template('components/masks/mask6.php'));
            }
            else if ( strpos($mask, 'mask7') !== false ) {
                include(locate_template('components/masks/mask7.php'));
            }
            else if ( strpos($mask, 'mask8') !== false ) {
                include(locate_template('components/masks/mask8.php'));
            }

            do_action( 'znhgpb_section_mask_content', $mask, $bgcolor, $pos );

            echo '</div>';
        }
    }
}

// TODO: to be prepared for future mask plugins

function zn_shared_masks(){
    return array (
        'none' => __( 'None.', 'zn_framework' ),
        'custom' => __( 'Custom', 'zn_framework' ),
        'shadow_simple' => __( 'Shadow Up', 'zn_framework' ),
        'shadow_simple_down' => __( 'Shadow Down', 'zn_framework' ),
        'shadow' => __( 'Shadow Up (with border and small arrow)', 'zn_framework' ),
        'shadow_ud' => __( 'Shadow Up and down', 'zn_framework' ),
        'mask3' => __( 'Vector Mask 3 CENTER', 'zn_framework' ),
        'mask3 mask3l' => __( 'Vector Mask 3 LEFT', 'zn_framework' ),
        'mask3 mask3r' => __( 'Vector Mask 3 RIGHT', 'zn_framework' ),
        'mask4' => __( 'Vector Mask 4 CENTER', 'zn_framework' ),
        'mask4 mask4l' => __( 'Vector Mask 4 LEFT', 'zn_framework' ),
        'mask4 mask4r' => __( 'Vector Mask 4 RIGHT', 'zn_framework' ),
        'mask5' => __( 'Vector Mask 5', 'zn_framework' ),
        'mask6' => __( 'Vector Mask 6', 'zn_framework' ),
        'mask7 mask7l' => __( 'Mask 7 - Skew Left', 'zn_framework' ),
        'mask7 mask7r' => __( 'Mask 7 - Skew Right', 'zn_framework' ),
        'mask7 mask7big mask7l' => __( 'Mask 7 Bigger - Skew Left', 'zn_framework' ),
        'mask7 mask7big mask7r' => __( 'Mask 7 Bigger - Skew Right', 'zn_framework' ),
        'mask8 mask8s' => __( 'Mask 8 - V shaped', 'zn_framework' ),
        'mask8 mask8b' => __( 'Mask 8 Bigger - V shaped', 'zn_framework' ),
    );
}
function zn_shared_masks_deps() {
    $dependencies = array(
        'mask3',
        'mask3 mask3l',
        'mask3 mask3r',
        'mask4',
        'mask4 mask4l',
        'mask4 mask4r',
        'mask5',
        'mask6',
        'mask7 mask7l',
        'mask7 mask7r',
        'mask7 mask7big mask7l',
        'mask7 mask7big mask7r',
        'mask8 mask8s',
        'mask8 mask8b',
    );

    $dependencies = apply_filters( 'znhgpb_section_masks_dependencies', $dependencies );

    return $dependencies;
}

/**
 * Return Bottom masks for options list
 */
if( !function_exists('zn_get_masks') ){
    function zn_get_masks($extra = array()){
        $masks = zn_shared_masks();
        if( !empty($extra) ){
            $masks = array_merge($masks, $extra);
        }
        $masks = apply_filters( 'znhgpb_section_masks', $masks );

        return $masks;
    }
}
/** For backwards compatibility */
if( !function_exists('zn_get_bottom_masks') ){
    function zn_get_bottom_masks(){
        return zn_get_masks();
    }
}

/**
 * Background color dependencies for masks
 */
if( !function_exists('zn_get_masks_deps') ){
    function zn_get_masks_deps($extra = array()){
        $values = zn_shared_masks_deps();
        if( !empty($extra) ){
            $values = array_merge($values, $extra);
        }
        return $values;
    }
}
