<?php

class ZnHgFw_Html_Smart_Slider extends ZnHgFw_BaseFieldType{

	var $type = 'smart_slider';

	function render($option) {

			$support_breakpoints = !empty($option['supports']) && in_array( 'breakpoints', $option['supports'] );
			$support_props = isset($option['properties']) && !empty( $option['properties'] );

			if ( !isset($option['units']) ){
				// default units
				$option['units'] = array('px', '%');
			}

			$std = array(
				'lg' => '',
				'unit_lg' => '',
				'breakpoints' => '',
				'md' => '',
				'unit_md' => 'px',
				'sm' => '',
				'unit_sm' => 'px',
				'xs' => '',
				'unit_xs' => 'px',
			);
			if(isset($option['std']) && !empty($option['std']) ){

				if(is_array($option['std'])){
					$std['lg'] = $option['std']['lg'] != '' ? $option['std']['lg'] : '';
				}
				else{
					$std['lg'] = $option['std'];
				}
				$std['unit_lg'] = !empty($option['std']['unit_lg']) ? $option['std']['unit_lg'] : $option['units'][0];

				if($support_breakpoints) {
					$std['breakpoints'] = isset($option['std']['breakpoints']) && $option['std']['breakpoints'] != '' ? $option['std']['breakpoints'] : '';

					$std['md'] = isset($option['std']['md']) && $option['std']['md'] != '' ? $option['std']['md'] : '';
					$std['unit_md'] = isset($option['std']['unit_md']) && !empty($option['std']['unit_md']) ? $option['std']['unit_md'] : $std['unit_md'];

					$std['sm'] = isset($option['std']['sm']) && $option['std']['sm'] != '' ? $option['std']['sm'] : '';
					$std['unit_sm'] = isset($option['std']['unit_sm']) && !empty($option['std']['unit_sm']) ? $option['std']['unit_sm'] : $std['unit_sm'];

					$std['xs'] = isset($option['std']['xs']) && $option['std']['xs'] != '' ? $option['std']['xs'] : '';
					$std['unit_xs'] = isset($option['std']['unit_xs']) && !empty($option['std']['unit_xs']) ? $option['std']['unit_xs'] : $std['unit_xs'];
				}
				if($support_props){
					$std['properties'] = isset($option['std']['properties']) && !empty($option['std']['properties']) ? $option['std']['properties'] : '';
				}
			}

			$step = !empty($option['helpers']['step']) ? $option['helpers']['step'] : '1';
			$min = !empty($option['helpers']['min']) ? $option['helpers']['min'] : '0';
			$max = !empty($option['helpers']['max']) ? $option['helpers']['max'] : '1000';

			$output  = '<div class="zn_slider znSmartSlider clearfix">';

				// add properties
				if ( $support_props ){
					$output  .= '<div class="znSmartSlider-properties">';
					$output .= '<span class="znSmartSlider-opt-title">PROPERTY:</span>';

					foreach($option['properties'] as $property){

						$is_live_property = isset($option['live']) ? 'data-live-property="'.$property.'"' : '';

						$current = !empty($std['properties']) ? $std['properties'] : $option['properties'][0];

						$output .= '<input type="radio" class="zn_input" name="'.$option['id'].'[properties]" '.$is_live_property.' value="'.$property.'" id="'.$option['id'].'_properties_'.$property.'" '.checked( $current, $property, false ).'><label for="'.$option['id'].'_properties_'.$property.'">'.$property.'</label>';
					}
					$output  .= '</div>';
				}
				$output  .= '<div class="clearfix"></div>';


				// add breakpoints button
				if ( $support_breakpoints ){
					$checked = $std['breakpoints'] == 1 ? 'checked="checked"' : '';
					$output .= '<input type="checkbox" class="zn_input znSmartSlider-breakpointsBtnInput" name="'.$option['id'].'[breakpoints]" value="1" id="'.$option['id'].'_breakpoints" '.$checked.'><label for="'.$option['id'].'_breakpoints" class="znSmartSlider-breakpointsBtnLabel tooltip-top-right" data-tooltip="CUSTOM BREAKPOINTS"><span class="znSmartSlider-LabelIcon"></span></label>';
				}

				// dynamic properties not supported yet
				$is_live = isset($option['live']) ? 'data-live-input="1"' : '';

				// main imput
				$output .= '<span class="znSmartSlider-mainInputIcon" data-tooltip="Desktops / +1200px"><span></span></span>';

				$output .= '<input type="text" class="zn_input zn_input_text znSmartSlider-input znSmartSlider-mainInput wp-slider-input js-dragkeyfield" data-no-unit '.$is_live.' min="'.$min.'" step="'.$step.'" max="'.$max.'" name="'.$option['id'].'[lg]" value="'.$std['lg'].'" '.(isset($option['disabled_slider']) ? 'readonly':'').' >';

				// dynamic properties not supported yet
				$is_live_unit = isset($option['live']) ? 'data-live-unit="1"' : '';

				// add units to large
				$output  .= '<select name="'.$option['id'].'[unit_lg]" '.$is_live_unit.' class="znSmartSlider-mainUnits zn_input_select " '.(isset($option['disabled_slider']) ? 'readonly':'').' >';
				foreach($option['units'] as $units){
					$output .= '<option value="'.$units.'" '.selected( $std['unit_lg'], $units, false ).'>'.$units.'</option>';
				}
				$output  .= '</select>';

				$output .= '<div class="wp-slider slider-range-max znSmartSlider-mainSlider '.($support_breakpoints ? 'with-breakpoints':'').'" data-min="'.$min.'" data-step="'.$step.'" data-max="'.$max.'" data-value="'.$std['lg'].'" '.(isset($option['disabled_slider']) ? 'data-disabled="true"':'').'></div>';


				$output  .= '<div class="clearfix"></div>';

				// Add breakpoints fields
				if ( $support_breakpoints ){

					$output  .= '<div class="znSmartSlider-breakpointsWrapper clearfix">';

						// MD
						$output  .= '<div class="znSmartSlider-breakpointsField" >';
							$output  .= '<label class="znSmartSlider-breakpointsFieldIcon" for="'.$option['id'].'_md" data-tooltip="Small laptops / Big tablets / 1199px - 992px"><span class="znSmartSlider-breakpointsFieldIcon--laptop"></span></label>';
							$output .= '<input type="text" class="zn_input zn_input_text znSmartSlider-input znSmartSlider-breakpointsInput" min="'.$min.'" step="'.$step.'" max="'.$max.'" name="'.$option['id'].'[md]" id="'.$option['id'].'_md" value="'.$std['md'].'" >';
							// add unit list
							$output  .= '<select name="'.$option['id'].'[unit_md]" class="znSmartSlider-units zn_input_select">';
							foreach($option['units'] as $units){
								$output .= '<option value="'.$units.'" '.selected( $std['unit_md'], $units, false ).'>'.$units.'</option>';
							}
							$output  .= '</select>';
							$output  .= '<span class="znSmartSlider-breakpointsTitle">MEDIUM</span>';
						$output .= '</div>';

						// SM
						$output  .= '<div class="znSmartSlider-breakpointsField" >';
							$output  .= '<label class="znSmartSlider-breakpointsFieldIcon " for="'.$option['id'].'_sm" data-tooltip="Tablets / 991px - 768px"><span class="znSmartSlider-breakpointsFieldIcon--tablet"></span></label>';
							$output .= '<input type="text" class="zn_input zn_input_text znSmartSlider-input znSmartSlider-breakpointsInput" min="'.$min.'" step="'.$step.'" max="'.$max.'" name="'.$option['id'].'[sm]" id="'.$option['id'].'_sm" value="'.$std['sm'].'" >';
							// add unit list
							$output  .= '<select name="'.$option['id'].'[unit_sm]" class="znSmartSlider-units zn_input_select">';
							foreach($option['units'] as $units){
								$output .= '<option value="'.$units.'" '.selected( $std['unit_sm'], $units, false ).'>'.$units.'</option>';
							}
							$output  .= '</select>';
							$output  .= '<span class="znSmartSlider-breakpointsTitle">SMALL</span>';
						$output .= '</div>';

						// XS
						$output  .= '<div class="znSmartSlider-breakpointsField znSmartSlider-breakpointsField--last" >';
							$output  .= '<label class="znSmartSlider-breakpointsFieldIcon " for="'.$option['id'].'_xs" data-tooltip="Smartphones / max. 767px"><span class="znSmartSlider-breakpointsFieldIcon--mobile"></span></label>';
							$output .= '<input type="text" class="zn_input zn_input_text znSmartSlider-input znSmartSlider-breakpointsInput" min="'.$min.'" step="'.$step.'" max="'.$max.'" name="'.$option['id'].'[xs]" id="'.$option['id'].'_xs" value="'.$std['xs'].'" >';
							// add unit list
							$output  .= '<select name="'.$option['id'].'[unit_xs]" class="znSmartSlider-units zn_input_select">';
							foreach($option['units'] as $units){
								$output .= '<option value="'.$units.'" '.selected( $std['unit_xs'], $units, false ).'>'.$units.'</option>';
							}
							$output  .= '</select>';
							$output  .= '<span class="znSmartSlider-breakpointsTitle">EXTRA SMALL</span>';
						$output .= '</div>';

					$output .= '</div>';
				}


			$output .= '</div>';

			return $output;
		}

}
