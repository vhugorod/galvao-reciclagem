<?php if(! defined('ABSPATH')){ return; }
/**
* Single Social - Facebook
*/
?>
<!-- Social sharing -->
<div class="blog-item-share">
	<?php
		echo zn_social_share_icons(array(
			'share_media' => get_the_post_thumbnail_url()
		));
	?>
</div><!-- social links -->
