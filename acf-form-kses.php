<?php 
	// safely apply wp_kses_post to all fields
	// when using acf_form()
	function acf_wp_kses_post($data, $post_id=0, $field=array()) {
		if (isset($field['type']) && ($field['type'] == 'repeater' || $field['type'] == 'flexible_content')) {
			// no need to run it on repeaters
			// will be called agaian for each subfield
			return $value;
		}
		if (!is_array($data)) {
			return wp_kses_post($data);
		}
		$return = array();
		if (count($data)) {
			foreach ($data as $index => $value) {
				$return[$index] = acf_wp_kses_post($value);
			}
		}
		return $return;
	}
	add_filter('acf/update_value', 'acf_wp_kses_post', 10, 3)
	/*
		there is another way to do it posted by the author of ACF that can be
		found here http://www.advancedcustomfields.com/resources/acf_form/#security
		I'll keep this one here as an alternate
		
		This technique could also be used to apply any function safely to all ACF field types.
		Just replace wp_kses_post with the function you want to use
		
	*/
?>
