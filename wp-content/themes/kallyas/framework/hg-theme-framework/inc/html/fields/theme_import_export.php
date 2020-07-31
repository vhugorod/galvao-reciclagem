<?php

class ZnHgFw_Html_Theme_Import_Export extends ZnHgFw_BaseFieldType{

	var $type = 'theme_import_export';

	function render($options) {
		ob_start();
		?>
		<!--//#! IMPORT OPTIONS -->
		<div class="zn_option_container clearfix">

			<h4><?php esc_html_e('Import options', 'zn_framework');?></h4>
			<p class="zn_option_desc"><?php esc_html_e('Click the button to restore a previously exported archive containing the theme options.', 'zn_framework');?></p>
			<div class="zn_option_content">

				<div class="zn_impexp_notice zn_theme_options_import_msg_container"></div>

				<button type="button"
					   id="zn_theme_import_button"
					   name="zn_theme_import"
					   class="zn_admin_button zn-add-media-trigger"
					   data-media_type="media_field_import"
					   data-insert_title="Select file"
					   data-title="Select file"
					   data-type="application/zip"
					   data-state="library"
					   data-class="zn-media-video media-frame"
					   data-value_type="url">
				   <span class="dashicons dashicons-upload"></span> <?php esc_html_e('Select file to import', 'zn_framework');?>
				</button>
			</div>
		</div>

		<div class="clearfix"></div>

		<!--//#! EXPORT OPTIONS -->
		<div class="zn_option_container clearfix">
			<h4><?php esc_html_e('Export options', 'zn_framework');?></h4>
			<p class="zn_option_desc"><?php esc_html_e('Click the button to export theme options zip archive (once the process finishes the download should start automatically).', 'zn_framework');?></p>

			<div class="zn_option_content">

				<div class="zn_impexp_notice zn_theme_options_export_msg_container"></div>

				<div class="exp_images_chb">
					<input type="checkbox"
						   id="zn_theme_export_with_images"
						   name="zn_theme_export_with_images"
						   class=""
						   value="1"/>
					<label for="zn_theme_export_with_images"><?php esc_html_e('Export images too.', 'zn_framework');?></label>
				</div>

				<div class="clearfix"></div>

				<button type="button"
					   id="zn_theme_export_button"
					   name="zn_theme_export"
					   class="zn_admin_button">
					<span class="dashicons dashicons-download"></span> <?php esc_html_e('Export theme options.', 'zn_framework');?>
				</button>
			</div>
		</div>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
}
