<?php 
	
	/*
		ACF Image Field Image Aspect Ratio Validation
		Adds a field setting to ACF Image fields and validates images
		to ensure that they meet image aspect ratio requirement
		
		This also serves as an example of how to add multiple settings
		to a single row when adding settings to an ACF field type
		
		side note: after implementing this code clear your browser cache
		to ensure the needed JS and WP media window is refreshed
		
		What is "Margin"?
		
		Let's say that you set an aspect ratio of 1:1 with a margin of 10%
		If the width of the image is 100 pixels, this means that the
		height of the image can be from 90 pixels to 110 pixels
		100 +/- 10% (10px)
		
		If the aspect ration is set to 4:3 and the margin at 1%
		if the width of the uploaded image is 800 pixels
		then the height can be 594 to 606 pixels
		600 +/- 1% (6px)
		
	*/
	
	// add new settings for aspect ratio to image field
	add_filter('acf/render_field_settings/type=image', 'acf_image_aspect_ratio_settings', 20);
	function acf_image_aspect_ratio_settings($field) {
		// the technique used for adding multiple fields to a
		// single setting is copied directly from the ACF Image
		// field code. anything that ACF does can be replicated,
		// you just need to look at how Elliot does it
		// also, any ACF field type can be used as a setting
		// field for other field types
		$args = array(
			'name' => 'ratio_width',
			'type' => 'number',
			'label' => __('Aspect Ratio'),
			'instructions' => __('Restrict which images can be uploaded'),
			'default_value' => 0,
			'min' => 0,
			'step' => 1,
			'prepend' => __('Width'),
		);
		acf_render_field_setting($field, $args);
		
		$args = array(
			'name' => 'ratio_height',
			'type' => 'number',
			// notice that there's no label when appending a setting
			'label' => '',
			'default_value' => 0,
			'min' => 0,
			'step' => 1,
			'prepend' => __('Height'),
			// this how we append a setting to the previous one
			'wrapper'		=> array(
				'data-append' => 'ratio_width',
				'width' => '',
				'class' => '',
				'id' => ''
			)
		);
		acf_render_field_setting($field, $args);
		
		$args = array(
			'name' => 'ratio_margin',
			'type' => 'number',
			'label' => '',
			'default_value' => 0,
			'min' => 0,
			'step' => .5,
			'prepend' => __('&plusmn;'),
			'append'		=> __('%'),
			'wrapper'		=> array(
				'data-append' => 'ratio_width',
				'width' => '',
				'class' => '',
				'id' => ''
			)
		);
		acf_render_field_setting($field, $args);
	} // end function acf_image_aspect_ratio_settings	
	
	// add filter to validate images to ratio
	add_filter('acf/validate_attachment/type=image', 'acf_image_aspect_ratio_validate', 20, 4);
	function acf_image_aspect_ratio_validate($errors, $file, $attachment, $field) {
		// check to make sure everything has a value
		if (empty($field['ratio_width']) || empty($field['ratio_height']) ||
		    empty($file['width']) || empty($file['height'])) {
			// values we need are not set or otherwise empty
			// bail early
			return $errors;
		}
		// make sure all values are numbers, you never know
		$ratio_width = intval($field['ratio_width']);
		$ratio_height = intval($field['ratio_height']);
		// make sure we don't try to divide by 0
		if (!$ratio_width || !$ratio_height) {
			// cannot do calculations if something is 0
			// bail early
			return $errors;
		}
		$width = intval($file['width']);
		$height = intval($file['height']);
		// do simple ratio math to see how tall
		// the image is allowed to be based on width
		$allowed_height = $width/$ratio_width*$ratio_height;
		// get margin and calc min/max
		$margin = 0;
		if (!empty($field['ratio_margin'])) {
			$margin = floatval($field['ratio_margin']);
		}
		$margin = $margin/100; // convert % to decimal
		$min = round($allowed_height - ($allowed_height*$margin));
		$max = round($allowed_height + ($allowed_height*$margin));
		if ($height < $min || $height > $max) {
			// does not meet the requirement, generate an error
			$errors['aspect_ratio'] = __('Image does not meet Aspect Ratio Requirements of ').
			                          $ratio_width.__(':').$ratio_height.__('&plusmn;').$ratio_margin.__('%');
		}
		// return the errors
		return $errors;
	} // end function acf_image_aspect_ratio_validate
	
?>
