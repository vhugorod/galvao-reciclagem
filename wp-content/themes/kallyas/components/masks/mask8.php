<?php if(! defined('ABSPATH')){ return; }

if ( $mask == 'mask8 mask8s' ) { ?>
<svg class="svgmask" width="2700px" height="57px" viewBox="0 0 2700 57" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <polygon fill="#fff" style="fill:<?php echo esc_attr( $bgcolor ); ?>" class="bmask-bgfill" points="0 57 0 0 1350 55.5 2700 0 2700 57"></polygon>
</svg>
<?php }

elseif ( $mask == 'mask8 mask8b' ) { ?>
<svg class="svgmask" width="2700px" height="126px" viewBox="0 0 2700 126" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <polygon fill="#fff" style="fill:<?php echo esc_attr( $bgcolor ); ?>" class="bmask-bgfill" points="0 126 0 0 1350 122.684211 2700 0 2700 126"></polygon>
</svg>
<?php } ?>

