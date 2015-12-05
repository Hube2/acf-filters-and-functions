<?php 
	// safely apply wp_kses_post to all fields
	// when using acf_form()
	function acf_wp_kses_post($data) {
		if (!is_array($data)) {
			return wp_kses_post($data);
		}
		$return = array();
		foreach ($data as $index => $value) {
			$return[$index] = acf_wp_kses_post($value);
		}
	  return $return;
	}
?>
